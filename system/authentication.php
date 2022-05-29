<?php
////////////////////////////////////////////////////////////////////////////////
// Part of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.
// -- For history see the end of this file.

if (!defined('SYSCAP_AUTH')){
// this tells the whole framework that this system file has been included
define('SYSCAP_AUTH',	true);

////////////////////////////////////////////////////////////////////////////////
// DEFINITIONS

define('AUTH_SUCCESS',			0);
define('AUTH_USERNOTEXISTENT',	-1);
define('AUTH_MULTIPLEUSERS',	-2);
define('AUTH_WRONGPASS',		-3);
define('AUTH_CANNOTCHANGE',     -4);
define('AUTH_USEREXISTSALREADY',-5);
define('AUTH_DATABASEERR',      -6);
define('AUTH_INIT_DIFFDEVICE',  -7);
define('AUTH_INIT_SESSEXP',     -8);
define('AUTH_IPNOTALLOWED',     -9);
define('AUTH_USERNOTALLOWED',   -10);
define('AUTH_INIT_SESSEXPFORCED',   -11);
define('AUTH_USERDISABLED',     -12);

define('AUTH_STARTTIMEOUTONFAIL', 300);

define('AUTH_HMAC_MD5',         0);
define('AUTH_HMAC_SHA1',        1);

////////////////////////////////////////////////////////////////////////////////
// CLASSES

class CQAuth
{
    // variables
    private $strUser;               // username
    private $arrPermissions;        // array with permissions
    private $bAuthenticated;        // status of user in this session
    private $nInternalId;           // user id from database
    private $strCSRF;               // Cross-Site Request Forgery protection token
    private $strDefaultAPI;         // default API key
    private $arrAuxDetails;         // other details the database may hold

    private $bHasBeenInit;          // signals whether or not this object has been initialized

    private $arrOptions;            // stores various options for various behaviours that this object should have
    private $DATABASE;              // stores database object dependency
    private $CONFIG;                // stores website configuration dependency

    private $bHasHadActions;        // whether or not this object was requested to perform a login or logout action
    private $nLastActionResult;     // last action's result

    // constructors
    function __construct()
    {
        $this->strUser = 'public';
        $this->arrPermissions = array();
        $this->bAuthenticated = false;
        $this->nInternalId = -1;
        $this->bHasBeenInit = false;

        $this->bHasHadActions = false;
        $this->nLastActionResult = AUTH_SUCCESS;

        $this->arrOptions = array(
            'binduserdevice' => false,      // whether or not to try to bind a session
                                            // to a user device (defined by IP, user agent & others)
            'sessionexpiration' => 0,       // a number between 0 and session.gc_maxlifetime
                                            // to force sessions to be shorter than what's
                                            // configured in php.ini; 0 disables this function
            'rejectfailed' => 0             // whether or not to ban a certain IP / user
                                            // if it tries too many times to login and fails;
                                            // 0 disables this function, any positive number
                                            // signifies the number of failed consecutive attempts
        );

        $this->strCSRF = '';

        $this->DATABASE = NULL;
        $this->CONFIG = NULL;

        $this->strDefaultAPI = '';
        $this->arrAuxDetails = array();
    }

    // functions
    function Init(&$kDatabase, &$kConfig)
    {
        $this->DATABASE = $kDatabase;
        $this->CONFIG = $kConfig;

        if (!$this->DATABASE) return false;

        $this->arrPermissions = array();
        $this->bHasBeenInit = true;

        // get a few preferences first
        if ($kConfig){
            $this->arrOptions['binduserdevice'] = $kConfig->Get('auth-binduserdevice', false);
            $this->arrOptions['sessionexpiration'] = $kConfig->Get('auth-sessionexpiration', 0);
            $this->arrOptions['rejectfailed'] = $kConfig->Get('auth-rejectfailed', 0);
        }

        // check for existing session
        if (isset($_SESSION['authenticated'])){
            // we may have an opened session
            $bGoOn = true;

            if ($_SESSION['authenticated']){
                // DONE: add extra checks if necessary
                if ($this->arrOptions['binduserdevice']){
                    if (SESSION('userdevice', '-') != $this->GetDeviceId()){
                        $bGoOn = false;
                        $this->nLastActionResult = AUTH_INIT_DIFFDEVICE;
                    }
                }

                if ($this->arrOptions['sessionexpiration'] > 0 &&
                    SESSION('lastaccess', 0) + $this->arrOptions['sessionexpiration'] < time())
                {
                    $bGoOn = false;
                    $this->nLastActionResult = AUTH_INIT_SESSEXPFORCED;
                }
            }else $bGoOn = false;

            if ($bGoOn){
                // we surely have an opened session
                $this->strUser = SESSION('user');
                $this->nInternalId = SESSION('userid');
                $this->arrPermissions = unserialize(SESSION('useraccess'));
                $this->strCSRF = SESSION('csrf');
                $this->bAuthenticated = true;
                $this->nLastActionResult = AUTH_SUCCESS;

                $this->strDefaultAPI = SESSION('defaultapikey');
                $this->arrAuxDetails = unserialize(SESSION('auxdetails'));
            }else{
                // either something's wrong or the session is closed
                $this->strUser = 'public';

                if ($this->nLastActionResult == AUTH_SUCCESS)
                    $this->nLastActionResult = AUTH_INIT_SESSEXP;

                // get public permissions
                $arrRules = array();
                $arrResult = $this->DATABASE->RunQuickSelect('*',
                    SYSCFG_DB_PREFIX.'auth_users', array('username', '=', 'public'));

                if (is_array($arrResult) && count($arrResult) > 0){
                    $this->nInternalId = (int)$arrResult[0]['idx'];

                    $arrResult = $this->DATABASE->RunQuickSelect('*',
                        SYSCFG_DB_PREFIX.'auth_userpermissions',
                        array('usridx', '=', (int)$arrResult[0]['idx']),
                        array(array('LENGTH(`target`)', 'DESC'))
                    );

                    $nRules = (is_array($arrResult) ? count($arrResult) : 0);
                    if ($nRules > 0){
                        for ($i=0; $i < $nRules; $i++){
                            $arrAdv = array_map('trim', explode('|', $arrResult[$i]['advanced']));
                            foreach ($arrAdv as $xKey => $arrVal){
                                if (strpos($arrVal,':') !== FALSE){
                                    $arrAdv[$xKey] = array_map('trim', explode(':', $arrVal));
                                }
                            }

                            $arrRules[] = array($arrResult[$i]['target'],
                                (int)$arrResult[$i]['perm'], $arrAdv);
                        }
                    }else{
                        // default permissions for a regular user
                        $arrRules[] = array('*', 0, '');
                        $arrRules[] = array('*/index', 1, '');
                        $arrRules[] = array('*/admin', 0, '');
                    }
                }else{
                    $this->nInternalId = -1;
                    $arrRules[] = array('*', 0, '');
                    $arrRules[] = array('*/index', 1, '');
                    $arrRules[] = array('*/admin', 0, '');
                }

                $this->arrPermissions = $arrRules;
                $_SESSION['useraccess'] = serialize($arrRules);

                if (!isset($_SESSION['csrf'])){
                    $this->strCSRF = $this->GetNewSalt();
                    $_SESSION['csrf'] = $this->strCSRF;
                }else $this->strCSRF = SESSION('csrf');

                $this->bAuthenticated = false;

                $_SESSION['authenticated'] = false;
                $_SESSION['user'] = 'public';
                $_SESSION['userid'] = $this->nInternalId;
                $_SESSION['userdevice'] = '-';
            }
        }else{
            $_SESSION['authenticated'] = false;
            $_SESSION['user'] = 'public';
            $_SESSION['userid'] = $this->nInternalId;

            $this->strUser = 'public';

            // get public permissions
            $arrRules = array();
            $arrResult = $this->DATABASE->RunQuickSelect('*',
                SYSCFG_DB_PREFIX.'auth_users', array('username', '=', 'public'));

            if (is_array($arrResult) && count($arrResult)>0){
                $this->nInternalId = (int)$arrResult[0]['idx'];

                $arrResult = $this->DATABASE->RunQuickSelect('*',
                    SYSCFG_DB_PREFIX.'auth_userpermissions',
                    array('usridx', '=', (int)$arrResult[0]['idx']),
                    array(array('LENGTH(`target`)', 'DESC'))
                );

                $nRules = (is_array($arrResult) ? count($arrResult) : 0);
                if ($nRules > 0){
                    for ($i=0; $i < $nRules; $i++){
                        $arrAdv = array_map('trim', explode('|', $arrResult[$i]['advanced']));
                        foreach ($arrAdv as $xKey => $arrVal){
                            if (strpos($arrVal, ':') !== FALSE){
                                $arrAdv[$xKey] = array_map('trim', explode(':', $arrVal));
                            }
                        }
                        $arrRules[] = array($arrResult[$i]['target'],
                            (int)$arrResult[$i]['perm'], $arrAdv);
                    }
                }else{
                    $arrRules[] = array('*', 0, '');
                    $arrRules[] = array('*/index', 1, '');
                    $arrRules[] = array('*/admin', 0, '');
                }
            }else{
                $this->nInternalId = -1;

                $arrRules[] = array('*', 0, '');
                $arrRules[] = array('*/index', 1, '');
                $arrRules[] = array('*/admin', 0, '');
            }

            $this->arrPermissions = $arrRules;

            $this->strCSRF = $this->GetNewSalt();
            $_SESSION['csrf'] = $this->strCSRF;

            $this->bAuthenticated = false;
        }

        // if for some reason CSRF does not exist, we create one now
        if (strlen($this->strCSRF) <= 0){
            $this->strCSRF = $this->GetNewSalt();
            $_SESSION['csrf'] = $this->strCSRF;
        }

        $_SESSION['lastaccess'] = time();

        return true;
    }

    function IsAuthenticated()
    {
        return $this->bAuthenticated;
    }

    function GetUsername()
    {
        return $this->strUser;
    }


    function GetNewSalt()
    {
        // this will return a 32 character base64 encoded salt
        $strRet = '';

        if (function_exists('openssl_random_pseudo_bytes'))
            $strRet = base64_encode(openssl_random_pseudo_bytes(24));
        elseif (function_exists('mcrypt_create_iv'))
            $strRet = base64_encode(mcrypt_create_iv(24));
        else{
            // we need to create a new salt on our own. Not a good idea, but still ...
            $strBytes = '';
            for ($i=0; $i < 24; $i++) $strBytes .= chr(mt_rand(0, 255));
            $strRet = base64_encode($strBytes);
        }

        return str_replace(array('+', '/', '='), array('-', '_', '$'), $strRet);
    }

    function GetPasswordHash($strPass, $strUser='', $strSalt='')
    {
        return hash('sha512',
            $strSalt . substr($strPass, 0, round(strlen($strPass)/2, 0)).
            $strUser . substr($strPass, round(strlen($strPass)/2, 0))
        );
    }

    function GetDeviceId()
    {
        // an attempt to get a unique identifier of the device that makes this request;
        // it is by no means a perfect method, but it's the best that we can have
        // based on how the web was created so far

        $strInfo = '';
        $arrKeys = array('REMOTE_ADDR', 'HTTP_USER_AGENT',
            'HTTP_ACCEPT_LANGUAGE', 'HTTP_ACCEPT_ENCODING', 'HTTP_CONNECTION',
            'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED');

        // removed HTTP_CACHE_CONTROL because Chrome / Chromium
        // removed HTTP_UPGRADE_INSECURE_REQUESTS and HTTP_ACCEPT also,
        // because they were breaking nogui calls

        if (DEBUGMODE && class_exists('CQSyslog')){
            $LOG = CQSyslog::GetInstance();

            $strDebug = '';

            foreach ($arrKeys as $strKey)
                if (isset($_SERVER[$strKey]))
                    $strDebug .= '$_SERVER['. $strKey .'] = ' . $_SERVER[$strKey] . "\r\n";

            $LOG->Debug('_SERVER', $strDebug);
        }

        foreach ($arrKeys as $strKey)
            if (isset($_SERVER[$strKey]))
                $strInfo .= $_SERVER[$strKey];

        return strtolower(hash('sha512', $strInfo));
    }

    function CompareHashes($strStrLeft, $strStrRight)
    {
        $nLeftLen = strlen($strStrLeft);
        $nRightLen = strlen($strStrRight);

        $nDiff = $nLeftLen ^ $nRightLen;
        for ($i=0; $i < $nLeftLen && $i < $nRightLen; $i++)
            $nDiff |= ord($strStrLeft[$i]) ^ ord($strStrRight[$i]);

        return $nDiff == 0;
    }

    private function HandleFailedAttempts($nUser = NULL)
    {
        if ($this->arrOptions['rejectfailed'] > 0){
            $arrRejections = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX.'auth_rejections',
                array(
                    array('idxuser', '=', ($nUser === NULL ? 0 : $nUser), 'AND'),
                    array('ipaddr', '=', $_SERVER['REMOTE_ADDR'])
                )
            );

            if (is_array($arrRejections) && !empty($arrRejections)){
                // we have had fails in the past
                $arrRejections = $arrRejections[0];

                $arrRejections['numoftries'] = (int)$arrRejections['numoftries'] + 1;

                if ($arrRejections['numoftries'] >= $this->arrOptions['rejectfailed']){
                    $arrRejections['numoftries'] = 1;
                    $arrRejections['numofbans'] = (int)$arrRejections['numofbans'] + 1;
                }

                $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX.'auth_rejections',
                    'numoftries, numofbans',
                    array(
                        'numoftries' => $arrRejections['numoftries'],
                        'numofbans' => $arrRejections['numofbans']
                    ),
                    array('idx', '=', (int)$arrRejections['idx'])
                );
            }else{
                // this is the first fail
                $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX.'auth_rejections',
                    'idxuser, ipaddr, numoftries, numofbans, tstamp',
                    array(array(
                        'idxuser' => ($nUser === NULL ? 0 : $nUser),
                        'ipaddr' => $_SERVER['REMOTE_ADDR'],
                        'numoftries' => 1,
                        'numofbans' => 0,
                        'tstamp' => date('Y-m-d H:i:s')
                    ))
                );
            }
        }
    }

    private function MySQL_TS_to_Integer($pcDate)
    {
        // 0123-56-89 12:45:78
        return mktime((int)substr($pcDate,11,2),(int)substr($pcDate,14,2),(int)substr($pcDate,17,2),
            (int)substr($pcDate,5,2),(int)substr($pcDate,8,2),(int)substr($pcDate,0,4));
    }

    private function CheckIfAllowed($nUser = NULL)
    {
        if ($this->arrOptions['rejectfailed'] <= 0) return true;

        $arrRejections = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX.'auth_rejections',
            array(
                array('idxuser', '=', ($nUser === NULL ? 0 : $nUser), 'AND'),
                array('ipaddr', '=', $_SERVER['REMOTE_ADDR'])
            )
        );

        if (is_array($arrRejections) && count($arrRejections) > 0){
            $arrRejections = $arrRejections[0];

            if ((int)$arrRejections['numofbans'] <= 0) return true;
            else{
                $nTimeout = AUTH_STARTTIMEOUTONFAIL;

                for ($i=2; $i <= (int)$arrRejections['numofbans']; $i++)
                    $nTimeout = $nTimeout * 3;

                if (time() > $this->MySQL_TS_to_Integer($arrRejections['tstamp']) + $nTimeout)
                    return true;
                else return false;
            }
        }else return true;
    }

    private function HandleSuccessfulLogin($nUser)
    {
        $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX.'auth_rejections',
            array(
                array('idxuser', '=', $nUser, 'OR'),
                array('ipaddr', '=', $_SERVER['REMOTE_ADDR'])
            )
        );
    }

    function LogIn($strUser, $strPass)
    {
        // if the user is 'public' it's like logout
        if ($strUser == 'public') return $this->LogOut();

        // if invalid user, get out
        if (strlen($strUser) <= 0){
            $this->bHasHadActions = true;
            $this->nLastActionResult = AUTH_USERNOTEXISTENT;

            $this->HandleFailedAttempts();

            return AUTH_USERNOTEXISTENT;
        }

        // if we are logged in, logout first
        if ($this->bAuthenticated) $this->LogOut();

        // check first if IP is allowed
        if ($this->CheckIfAllowed()){
            // login
            $strUser = strtolower($strUser);

            $arrUserData = $this->DATABASE->RunQuickSelect('*',
                SYSCFG_DB_PREFIX.'auth_users', array('username', '=', $strUser));
            $nResults = (is_array($arrUserData) ? count($arrUserData) : 0);

            if ($nResults <= 0){
                $this->bHasHadActions = true;
                $this->nLastActionResult = AUTH_USERNOTEXISTENT;

                $this->HandleFailedAttempts();

                return AUTH_USERNOTEXISTENT;
            }elseif ($nResults == 1){
                $nId = (int)$arrUserData[0]['idx'];
                $strPassHash = $arrUserData[0]['passhash'];
                $strSalt = $arrUserData[0]['salt'];
                $bEnabled = ((int)$arrUserData[0]['enabled'] == 1 ? true : false);

                if ($bEnabled){
                    // check if user allowed
                    if ($this->CheckIfAllowed($nId)){
                        if ($this->CompareHashes($strPassHash,
                            $this->GetPasswordHash($strPass, $strUser, $strSalt)))
                        {
                            // success
                            $this->strUser = $strUser;
                            $this->nInternalId = $nId;
                            $this->bAuthenticated = true;

                            // get permissions
                            $arrResult = $this->DATABASE->RunQuickSelect('*',
                                SYSCFG_DB_PREFIX.'auth_userpermissions',
                                array('usridx', '=', $nId),
                                array(array('LENGTH(`target`)','DESC'))
                            );
                            $nRules = (is_array($arrResult) ? count($arrResult) : 0);
                            $this->arrPermissions = array();

                            for ($i=0; $i < $nRules; $i++){
                                $arrAdv = array_map('trim', explode('|', $arrResult[$i]['advanced']));
                                foreach ($arrAdv as $xKey => $arrVal){
                                    if (strpos($arrVal, ':') !== FALSE){
                                        $arrAdv[$xKey] = array_map('trim', explode(':', $arrVal));
                                    }
                                }
                                $this->arrPermissions[] = array($arrResult[$i]['target'],
                                    (int)$arrResult[$i]['perm'], $arrAdv);
                            }

                            // get auxiliary details
                            $this->arrAuxDetails = array();
                            $arrNotThese = array('idx', 'username', 'passhash',
                                'salt', 'enabled', 'recoverhash', 'defaultapikey');

                            foreach ($arrUserData[0] as $strKey => $strValue)
                                if (!in_array($strKey, $arrNotThese))
                                    $this->arrAuxDetails[$strKey] = $strValue;

                            $this->strDefaultAPI = $this->GetNewSalt();

                            // write session variables
                            $_SESSION['authenticated'] = true;
                            $_SESSION['user'] = $strUser;
                            $_SESSION['userid'] = $this->nInternalId;
                            $_SESSION['useraccess'] = serialize($this->arrPermissions);

                            $this->strCSRF = $this->GetNewSalt();
                            $_SESSION['csrf'] = $this->strCSRF;

                            $_SESSION['defaultapikey'] = $this->strDefaultAPI;
                            $_SESSION['auxdetails'] = serialize($this->arrAuxDetails);

                            // update default api
                            $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX.'auth_users',
                                'defaultapikey',
                                array('defaultapikey' => $this->strDefaultAPI),
                                array('idx', '=', $nId));

                            // user device
                            if ($this->arrOptions['binduserdevice'])
                                $_SESSION['userdevice'] = $this->GetDeviceId();

                            $this->bHasHadActions = true;
                            $this->nLastActionResult = AUTH_SUCCESS;

                            $this->HandleSuccessfulLogin($nId);

                            return AUTH_SUCCESS;
                        }else{
                            $this->bHasHadActions = true;
                            $this->nLastActionResult = AUTH_WRONGPASS;

                            $this->HandleFailedAttempts($nId);

                            return AUTH_WRONGPASS;
                        }
                    }else{
                        $this->bHasHadActions = true;
                        $this->nLastActionResult = AUTH_USERNOTALLOWED;

                        $this->HandleFailedAttempts($nId);

                        return AUTH_USERNOTALLOWED;
                    }
                }else{
                    $this->bHasHadActions = true;
                    $this->nLastActionResult = AUTH_USERDISABLED;

                    $this->HandleFailedAttempts($nId);

                    return AUTH_USERDISABLED;
                }
            }else{
                $this->bHasHadActions = true;
                $this->nLastActionResult = AUTH_MULTIPLEUSERS;

                return AUTH_MULTIPLEUSERS;
            }
        }else{
            $this->bHasHadActions = true;
            $this->nLastActionResult = AUTH_IPNOTALLOWED;

            $this->HandleFailedAttempts();

            return AUTH_IPNOTALLOWED;
        }
    }

    function LogOut()
    {
        $this->strUser = 'public';
        $this->bAuthenticated = false;

        $_SESSION['authenticated'] = false;
        $_SESSION['user'] = 'public';

        // get public permissions
        $arrRules = array();
        $arrResult = $this->DATABASE->RunQuickSelect('*',
            SYSCFG_DB_PREFIX.'auth_users', array('username', '=', 'public')
        );

        if (is_array($arrResult) && count($arrResult)>0){
            $this->nInternalId = (int)$arrResult[0]['idx'];

            $arrResult = $this->DATABASE->RunQuickSelect('*',
                SYSCFG_DB_PREFIX.'auth_userpermissions',
                array('usridx', '=', (int)$arrResult[0]['idx']),
                array(array('LENGTH(`target`)', 'DESC'))
            );

            $nRules = (is_array($arrResult) ? count($arrResult) : 0);

            if ($nRules > 0){
                for ($i=0; $i < $nRules; $i++){
                    $arrAdv = array_map('trim', explode('|', $arrResult[$i]['advanced']));
                    foreach ($arrAdv as $xKey => $arrVal){
                        if (strpos($arrVal, ':') !== FALSE){
                            $arrAdv[$xKey] = array_map('trim', explode(':', $arrVal));
                        }
                    }
                    $arrRules[] = array($arrResult[$i]['target'],
                        (int)$arrResult[$i]['perm'], $arrAdv);
                }
            }else{
                $arrRules[] = array('*', 0, '');
                $arrRules[] = array('*/index', 1, '');
                $arrRules[] = array('*/admin', 0, '');
            }
        }else{
            $this->nInternalId = -1;

            $arrRules[] = array('*', 0, '');
            $arrRules[] = array('*/index', 1, '');
            $arrRules[] = array('*/admin', 0, '');
        }

        $_SESSION['useraccess'] = serialize($arrRules);
        $_SESSION['userid'] = $this->nInternalId;
        $this->arrPermissions = $arrRules;

        if ($this->arrOptions['binduserdevice'])
            $_SESSION['userdevice'] = '-';

        $this->bHasHadActions = true;
        $this->nLastActionResult = AUTH_SUCCESS;

        $_SESSION['defaultapikey'] = '';
        $_SESSION['auxdetails'] = serialize(array());

        return AUTH_SUCCESS;
    }

    function CheckActionNeeded()
    {
        switch (POST('hSpecial_AUTH_Action'))
        {
            case 'login':{
                $this->LogIn(POST('hSpecial_AUTH_User'), POST('hSpecial_AUTH_Pass'));
            }break;

            case 'logout':{
                $this->LogOut();
            }break;

            // other values will be added in the future, like 'prelogin'
        }
    }

    function GetHasHadActions()
    {
        return $this->bHasHadActions;
    }

    function GetLastActionResult()
    {
        return $this->nLastActionResult;
    }

    function ChangePassword($strUser, $strOldPass, $strNewPass)
    {
        // if the user is 'public' it cannot be done
        if ($strUser == 'public') return AUTH_USERNOTEXISTENT;

        // if invalid user, get out
        if (strlen($strUser) <= 0) return AUTH_USERNOTEXISTENT;

        // check pass again
        $arrResult = $this->DATABASE->RunQuickSelect('*',
            SYSCFG_DB_PREFIX.'auth_users', array('username', '=', $strUser));

        $nResults = (is_array($arrResult) ? count($arrResult) : 0);

        if ($nResults <= 0) return AUTH_USERNOTEXISTENT;
        elseif ($nResults == 1){
            $nId = (int)$arrResult[0]['idx'];
            $strPassHash = $arrResult[0]['passhash'];
            $strSalt = $arrResult[0]['salt'];

            if ($this->CompareHashes($strPassHash,
                $this->GetPasswordHash($strOldPass, $strUser, $strSalt)))
            {
                // success
                $strNewSalt = $this->GetNewSalt();
                $strNewHash = $this->GetPasswordHash($strNewPass, $strUser, $strNewSalt);

                if ($this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX.'auth_users',
                    'passhash, salt',
                    array(
                        'passhash' => $strNewHash,
                        'salt' => $strNewSalt
                    ),
                    array('idx', '=', $nId)))
                {
                    return AUTH_SUCCESS;
                }else return AUTH_CANNOTCHANGE;
            }else return AUTH_WRONGPASS;
        }else return AUTH_MULTIPLEUSERS;
    }

    function ResetPassword($nUser, $strNewPass)
    {
        // if the user is 'public' it cannot be done
        if ($nUser <= 1) return AUTH_USERNOTEXISTENT;

        // check pass again
        if (!$this->DATABASE) return AUTH_DATABASEERR;

        $arrResult = $this->DATABASE->RunQuickSelect('*',
            SYSCFG_DB_PREFIX.'auth_users', array('idx', '=', $nUser));

        $nResults = (is_array($arrResult) ? count($arrResult) : 0);

        if ($nResults <= 0) return AUTH_USERNOTEXISTENT;
        elseif ($nResults == 1){
            $strNewSalt = $this->GetNewSalt();
            $strNewHash = $this->GetPasswordHash($strNewPass, $arrResult[0]['username'], $strNewSalt);

            if ($this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX.'auth_users',
                'passhash, salt',
                array(
                    'passhash' => $strNewHash,
                    'salt' => $strNewSalt
                ),
                array('idx', '=', $nUser)))
            {
                return AUTH_SUCCESS;
            }else return AUTH_CANNOTCHANGE;
        }else return AUTH_MULTIPLEUSERS;
    }

    function HasAdvancedPerms($arrFlags, $mxLocation, $strAdvSection, $strAdvPerm=NULL)
    {
        if (!$this->HasPermissions($arrFlags, $mxLocation)) return false;

        $bRet=true;

        $nPerms = count($this->arrPermissions);

        // search permission
        // ... build permission string
        $strPermString = '';

        if (is_array($mxLocation)){
            $strPermString = '*/' . $arrFlags['section'];

            $nLocations = count($mxLocation);
            for ($i=0; $i < $nLocations; $i++)
                $strPermString .= '/' . $mxLocation[$i];
        }elseif (is_string($mxLocation)){
            $strPermString = $mxLocation;
        }

        // search in permission array
        for ($i=0; $i < $nPerms; $i++){

            if ($strPermString == $this->arrPermissions[$i][0]){
                // if this is empty string, we have full advanced permissions
                if (empty($this->arrPermissions[$i][2]) ||
                    (is_array($this->arrPermissions[$i][2]) &&
                        count($this->arrPermissions[$i][2]) == 1 &&
                        empty($this->arrPermissions[$i][2][0]))) return true;

                // else we have only something
                $bRet = false;

                foreach ($this->arrPermissions[$i][2] as $arrAdvPerm){
                    if (is_string($arrAdvPerm) && $arrAdvPerm == $strAdvSection) return true;
                    elseif (is_array($arrAdvPerm)){
                        if ($arrAdvPerm[0] == $strAdvSection &&
                            strpos($arrAdvPerm[1], $strAdvPerm) !== FALSE)
                            return true;
                    }
                }

                $i = $nPerms + 1;
            }
        }

        return $bRet;
    }

    function HasPermissions($arrFlags, $mxLocation)
    {
        $bRet = false;

        $nPerms = count($this->arrPermissions);

        // search permission
        // ... build permission string
        $strPermString = '';

        if (is_array($mxLocation)){
            $strPermString = '*/' . $arrFlags['section'];

            $nLocations = count($mxLocation);
            for ($i=0; $i < $nLocations; $i++){
                $strPermString .= '/' . $mxLocation[$i];
            }
        }elseif (is_string($mxLocation)){
            $strPermString = $mxLocation;
        }

        // search in permission array
        $bFound = false;
        $bContinue = true;

        while(!$bFound && $bContinue){
            for ($i=0; $i < $nPerms; $i++){
                if ($this->arrPermissions[$i][0] == $strPermString){
                    $bFound = true;
                    $bContinue = false;
                    $bRet = ($this->arrPermissions[$i][1] == 1) ? true : false; // permission
                    $i = $nPerms + 1; // exit loop
                }
            }

            if (!$bFound){
                // cut the last /part
                if (strlen($strPermString) > 1 &&
                    $strPermString[strlen($strPermString)-1] == '*')
                {
                    // if the last character is a star (*) then remove it and continue
                    $mxLastSlash = strrpos($strPermString, '/');
                    if (is_int($mxLastSlash))
                        $strPermString = substr($strPermString, 0, $mxLastSlash);
                    else{
                        $strPermString = '';
                        $bContinue = false;
                    }
                }else{
                    // if the last character is not a star, remove it and add a star
                    $mxLastSlash = strrpos($strPermString, '/');
                    if (is_int($mxLastSlash)){
                        $strPermString = substr($strPermString, 0, $mxLastSlash);
                        $strPermString .= '/*';
                    }else{
                        $strPermString = '';
                        $bContinue = false;
                    }
                }
            }
        }

        return $bRet;
    }

    function GetUser()
    {
        return $this->strUser;
    }

    function GetUserId()
    {
        return $this->nInternalId;
    }

    function GetUserIdFromName($strName)
    {
        if (!$this->DATABASE) return AUTH_DATABASEERR;

        $arrResult = $this->DATABASE->RunQuickSelect('*',
            SYSCFG_DB_PREFIX.'auth_users', array('username', '=', $strName));

        if (is_array($arrResult) && count($arrResult) == 1)
            return (int)$arrResult[0]['idx'];
        else return AUTH_MULTIPLEUSERS;
    }

    function IsMaster()
    {
        if (count($this->arrPermissions)==1 && $this->arrPermissions[0][0]=='*'
            && $this->arrPermissions[0][1]) return true;
        else return false;
    }

    function IsPublicUser()
    {
        return $this->strUser == 'public';
    }

    function AddNewUser($strUser, $strNewPass, &$nNewId)
    {
        // if the user is 'public' it cannot be done
        if ($strUser == 'public') return AUTH_USERNOTEXISTENT;

        // if invalid user, get out
        if (strlen($strUser) <= 0) return AUTH_USERNOTEXISTENT;

        // check pass again
        if (!$this->DATABASE) return AUTH_CANNOTCHANGE;

        $arrResult = $this->DATABASE->RunQuickSelect('*',
            SYSCFG_DB_PREFIX.'auth_users', array('username', '=', $strUser));

        $nResults = (is_array($arrResult) ? count($arrResult) : 0);

        if ($nResults != 0) {
            return AUTH_USEREXISTSALREADY;
        } else {
            $strNewSalt = $this->GetNewSalt();
            $strNewHash = $this->GetPasswordHash($strNewPass, $strUser, $strNewSalt);
            if ($this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX.'auth_users', 'passhash, salt, username',
                array(array(
                    'username' => $strUser,
                    'passhash' => $strNewHash,
                    'salt' => $strNewSalt
                ))))
            {
                $nNewId = $this->DATABASE->GetLastInsertID();
                return AUTH_SUCCESS;
            } else {
                return AUTH_DATABASEERR;
            }
        }
    }

    function DeleteUser($nIdx)
    {
        if (!$this->DATABASE) return AUTH_CANNOTCHANGE;

        if ($nIdx > 1){
            if ($this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX.'auth_users',
                array('idx', '=', $nIdx)) &&
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX.'auth_userpermissions',
                array('usridx', '=', $nIdx)))
            {
                return AUTH_SUCCESS;
            }
        }else return AUTH_CANNOTCHANGE;
    }

    function CheckRequestPermission($strReq, $nTolerance, $strKey)
    {
        $arrHashes = array();
        $nTStamp = time() - $nTolerance;

        for ($i = $nTStamp; $i <= ($nTStamp + (2 * $nTolerance)); $i++)
            $arrHashes[] = strtoupper(md5(gmdate('YmdHis', $i) . $strKey));

        if (in_array($strReq, $arrHashes)) return true;
        else return false;
    }

    function CreateRequestPermission($strKey)
    {
        return strtoupper(md5(gmdate('YmdHis') . $strKey));
    }

    function GenerateHMAC($strMessage, $strKey, $nType)
    {
        $nBlocksize = 64;
        $strKeyHere = $strKey;

        switch ($nType)
        {
            case AUTH_HMAC_MD5: $nBlocksize=64; break;
            case AUTH_HMAC_SHA1: $nBlocksize=64; break;
        }

        if (strlen($strKeyHere) > $nBlocksize){
            switch ($nType)
            {
                case AUTH_HMAC_MD5: $strKeyHere = md5($strKeyHere); break;
                case AUTH_HMAC_SHA1: $strKeyHere = sha1($strKeyHere); break;
            }
        }

        if (strlen($strKeyHere) < $nBlocksize)
            $strKeyHere = $strKeyHere . str_repeat("\x00", $nBlocksize-strlen($strKeyHere));

        $strOuterPad = str_repeat("\x5C", $nBlocksize);

        for ($i=0; $i < $nBlocksize; $i++)
            $strOuterPad[$i] = chr(ord($strOuterPad[$i]) ^ ord($strKeyHere[$i]));

        $strInnerPad = str_repeat("\x36", $nBlocksize);

        for ($i=0; $i < $nBlocksize; $i++)
            $strInnerPad[$i] = chr(ord($strInnerPad[$i]) ^ ord($strKeyHere[$i]));

        switch ($nType)
        {
            case AUTH_HMAC_MD5: return strtoupper(md5($strOuterPad .
                md5($strInnerPad . $strMessage, true)));
            case AUTH_HMAC_SHA1: return strtoupper(sha1($strOuterPad .
                sha1($strInnerPad . $strMessage, true)));
        }

        return NULL;
    }

    function GetCSRFCode()
    {
        return $this->strCSRF;
    }

    function CheckCSRF($strCode)
    {
        return $this->CompareHashes($this->strCSRF, $strCode);
    }

    function GetAdvancedDetail($strDetail, $mxDefault = '')
    {
        return (isset($this->arrAuxDetails[$strDetail]) ?
            $this->arrAuxDetails[$strDetail] : $mxDefault);
    }

    function GetAllAdvancedDetails()
    {
        return $this->arrAuxDetails;
    }

    function ChangeAdvancedDetails($arrDetails, $nUserIdx = -1)
    {
        $arrDetailKeys = array();

        foreach ($arrDetails as $strKey => $strValue){
            $arrDetailKeys[] = $strKey;
            $this->arrAuxDetails[$strKey] = $strValue;
        }

        $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX.'auth_users', $arrDetailKeys,
            $arrDetails, array('idx', '=', ($nUserIdx == -1 ? $this->nInternalId : $nUserIdx)));
    }

    function GetDefaultAPIKey()
    {
        return $this->strDefaultAPI;
    }

    ////////////////////////////////////////////////////////////////////////////
    // functions for setting and getting elegant permission variables
    function LoadUserPermissions($nIdUsr)
    {
        if ($this->bHasBeenInit) return AUTH_CANNOTCHANGE;

        $arrResult = $this->DATABASE->RunQuickSelect('*',
            SYSCFG_DB_PREFIX.'auth_users', array('idx', '=', $nIdUsr));

        $nResults = (is_array($arrResult) ? count($arrResult) : 0);

        if ($nResults <= 0) return AUTH_USERNOTEXISTENT;
        elseif ($nResults == 1){
            // success
            $this->strUser = $arrResult['username'];
            $this->nInternalId = $nIdUsr;
            $this->bAuthenticated = true;

            // get permissions
            $arrResult = $this->DATABASE->RunQuickSelect('*',
                SYSCFG_DB_PREFIX.'auth_userpermissions',
                array('usridx', '=', $nIdUsr),
                array(array('LENGTH(`target`)','DESC'))
            );

            $nRules = (is_array($arrResult) ? count($arrResult) : 0);
            $this->arrPermissions = array();

            for ($i=0; $i < $nRules; $i++){
                $arrAdv = array_map('trim', explode('|', $arrResult[$i]['advanced']));
                foreach ($arrAdv as $xKey => $arrVal){
                    if (strpos($arrVal, ':') !== FALSE){
                        $arrAdv[$xKey] = array_map('trim', explode(':', $arrVal));
                    }
                }
                $this->arrPermissions[] = array($arrResult[$i]['target'],
                    (int)$arrResult[$i]['perm'], $arrAdv);
            }

            return AUTH_SUCCESS;
        }else return AUTH_MULTIPLEUSERS;
    }

    private function GetSinglePermission(&$arrPermSng)
    {
        // check permissions
        $bHasPerm=true;

        foreach ($arrPermSng['permissions'] as $arrPerm){
            if (empty($arrPerm['permadvanced'])){
                if (!$this->HasPermissions(NULL, $arrPerm['permstring']))
                    $bHasPerm = false;
            }else{
                if (!$this->HasAdvancedPerms(NULL, $arrPerm['permstring'],
                    $arrPerm['permadvanced']))
                    $bHasPerm = false;
            }
        }

        $arrPermSng['perm'] = $bHasPerm;

        // if has children, go deeper
        if (!empty($arrPermSng['children'])){
            foreach ($arrPermSng['children'] as &$arrChild)
                $this->GetSinglePermission($arrChild);
        }
    }

    function GetStructuredPermissions(&$arrPerms)
    {
        // get default permissions
        foreach ($arrPerms['default'] as $nKey => $arrSinglePerm){
            if (empty($arrSinglePerm['permadvanced'])){
                if ($this->HasPermissions(NULL, $arrSinglePerm['permstring']))
                    $arrPerms['default'][$nKey]['perm'] = true;
                else $arrPerms['default'][$nKey]['perm'] = false;
            }else{
                if ($this->HasAdvancedPerms(NULL, $arrSinglePerm['permstring'],
                    $arrSinglePerm['permadvanced']))
                    $arrPerms['default'][$nKey]['perm'] = true;
                else $arrPerms['default'][$nKey]['perm'] = false;
            }
        }

        // get custom permissions
        foreach ($arrPerms['custom'] as &$arrSinglePerm)
            $this->GetSinglePermission($arrSinglePerm);
    }

    private function GeneratePermissionList(&$arrList, $arrElement)
    {
        // add these permissions
        foreach ($arrElement['permissions'] as $arrSingle){
            $arrList[] = array(
                'permstring' => $arrSingle['permstring'],
                'perm' => $arrElement['perm'],
                'permadvanced' => $arrSingle['permadvanced']
            );
        }

        // add children if available
        if (!empty($arrElement['children'])){
            foreach ($arrElement['children'] as &$arrChild)
                $this->GeneratePermissionList($arrList, $arrChild);
        }
    }

    function SetStructuredPermissions($arrPerms)
    {
        $arrPermissionList = array();

        foreach ($arrPerms['default'] as $arrSinglePerm){
            $arrPermissionList[] = array(
                'permstring' => $arrSinglePerm['permstring'],
                'perm' => $arrSinglePerm['perm'],
                'permadvanced' => $arrSinglePerm['permadvanced']
            );
        }

        foreach ($arrPerms['custom'] as $arrSinglePerm)
            $this->GeneratePermissionList($arrPermissionList, $arrSinglePerm);

        // now combine them all into a single array
        $arrKeyedPermissions = array();

        foreach ($arrPermissionList as $arrPerm){
            $arrKeyedPermissions[$arrPerm['permstring']] = array(
                'perm' => ($arrPerm['perm'] ? 1 : 0),
                'permadvanced' => (empty($arrPerm['permadvanced']) ? '' :
                    implode('|', $arrPerm['permadvanced']))
            );
        }

        unset($arrPermissionList);

        // now write to database
        $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX.'auth_userpermissions',
            array('usridx', '=', $this->GetUserId()));

        foreach ($arrKeyedPermissions as $strPermStr => $arrDetails){
            $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX.'auth_userpermissions',
                'usridx, target, perm, advanced',
                array(array(
                    'usridx' => $this->GetUserId(),
                    'target' => $strPermStr,
                    'perm' => $arrDetails['perm'],
                    'advanced' => $arrDetails['permadvanced']
                ))
            );
        }
    }
}

} // endif defined SYSCAP_AUTH

////////////////////////////////////////////////////////////////////////////////
// A BIT OF THEORY
//---------------------
// Permissions exists at 2 different levels: the technical one and the more
// human-usable one. The technical level of permissioning determines whether
// a user has rights to access a certain URL, and maybe some finer grained
// details like various other sub-items inside that URL. This is a verry strong
// way of accepting or rejecting user access, from the application's point of
// vue, however this is not verry useful in terms of human usability, simply
// because a web interface may be comprised of several URLs. Tipically it's one
// web page (in which case the platform will select to access a PHP file from
// the 'data' layer), but that page may also access other scripts via AJAX
// (in which case the platform will load other PHP files, found in the 'nogui'
// layer).
//
// In this case the platform must supply a mapping system from the human
// way of structuring permissions to the technical way. This mapping needs to
// be done by the web developer, however it needs to be easy to understand,
// create and modify.
//
// On top of this, the structure needs to have a hierarchy, and it needs to be
// able to be built easily. One more reason to have a user-to-technical mapping
// is that this human usable hierarchy doesn't usually have the same structure
// as the technical one.
//
// That's why this class gives you the possibility to pass a nicely formatted
// and structured array of both user informations (that can be displayed on
// screen) and technical data (permission strings that are stored in the
// database).
//
// This array should look something like this:
/*

$arrPerms=array(
    'default' => array(
        array(
            'permstring'        => '',
            'perm'              => true, // or false
            'permadvanced'      => array('with all the advanced keywords')
        )
    ),
    'custom' => array(
        array(
            'name'              => 'Human readable name of permission',
            'perm'              => true, // or false, this is the permission,
            'permissions'       => array(
                array(
                    'permstring'        => '* /the/permission/string',
                    'permadvanced'      => array('with all the advanced keywords')
                ),

                array(
                    'permstring'        => '* /the/permission/string',
                    'permadvanced'      => array('with all the advanced keywords')
                )

                // ....
            ),

            'children' => array(
                array(
                    // use this only if the item has sub-items
                    'name'              => 'Human readable name of permission',
                    'perm'              => true, // or false, this is the permission
                    'permissions'       => array(
                        array(
                            'permstring'        => '* /the/permission/string',
                            'permadvanced'      => array('with all the advanced keywords')
                        ),

                        array(
                            'permstring'        => '* /the/permission/string',
                            'permadvanced'      => array('with all the advanced keywords')
                        )

                        // ....
                    )
                ),
                ...
            )
        )
    )
);


**** AUTHENTICATION DATA STORED IN $_SESSION ****

authenticated - boolean
user - string
userid - int
useraccess - string (serialized array)
csrf - string
userdevice - string
lastaccess - int

*/

////////////////////////////////////////////////////////////////////////////////
// History:
//  -- 21/06/2019 - v1 created;
