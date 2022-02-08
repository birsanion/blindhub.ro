<?php
////////////////////////////////////////////////////////////////////////////////
// Quick Web Frame - v1.0 - 21/06/2019
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.
// -- For history see the end of this file.

// include the system config file
require_once 'system/config.php';

// let the rest of the world know we are inside the framework.
// If you take away parts and use them in other places, they will detect this
define('QUICKWEBFRAME', true);
define('QUICKWEBFRAME_VERSION', '0.9');
define('DEBUGMODE', false);

// start session
if (strlen(SYSCFG_HTTP_SESSIONNAME) > 0){
    session_name(SYSCFG_HTTP_SESSIONNAME);
    session_start();
}

// include core, router and loader - the only 3 mandatory modules
require_once 'system/core.php';
require_once 'system/route.php';
require_once 'system/loader.php';

class CQAPP
{
    private $CORE;
    private $LOG;
    private $CONFIG;
    private $DATABASE;
    private $ROUTE;
    private $AUTH;
    private $LOADER;

    function __construct()
    {
        // set variables initially to null
        $this->CORE = NULL;
        $this->LOG = NULL;
        $this->CONFIG = NULL;
        $this->DATABASE = NULL;
        $this->ROUTE = NULL;
        $this->AUTH = NULL;
        $this->LOADER = NULL;

        // initialize core object
        $this->CORE = new CQCore();
        $this->CORE->Init();

        // set some environments
        chdir($this->CORE->GetScriptFilepath());

        if (strlen(SYSCFG_DEFAULT_TIMEZONE) > 0)
            date_default_timezone_set(SYSCFG_DEFAULT_TIMEZONE);

        // load other system-wide modules
        $arrTemp = explode(',', SYSCFG_APPLICATION_CAPABILITIES);
        $nTemp = count($arrTemp);
        for ($i=0; $i<$nTemp; $i++){
            $arrTemp[$i] = trim($arrTemp[$i]);

            switch ($arrTemp[$i])
            {
                case 'log':{
                    require_once 'system/syslog.php';
                    $this->LOG = CQSyslog::GetInstance();
                }break;

                case 'database':{
                    require_once 'system/database.php';
                    $this->DATABASE = new CQDatabaseWrapper();
                }break;

                case 'config':{
                    require_once 'system/siteconfig.php';
                    $this->CONFIG = new CQSiteConfig();
                }break;

                case 'auth':{
                    require_once 'system/authentication.php';
                    $this->AUTH = new CQAuth();
                }break;
            }
        }

        // load dummy library, just in case we need something that hasn't been included
        require_once 'system/dummy.php';

        // initialize each system object (give each their dependencies)
        if ($this->DATABASE){
            $this->DATABASE->Init(SYSCFG_DB_TYPE, SYSCFG_DB_ADDRESS,
                SYSCFG_DB_NAME, SYSCFG_DB_USER, SYSCFG_DB_PASS);

            $this->DATABASE->Open();
        }

        if ($this->LOG && $this->DATABASE) $this->LOG->Init($this->DATABASE);
        if ($this->CONFIG && $this->DATABASE) $this->CONFIG->Init($this->DATABASE);

        // authentication is a bit more special, besides initialisation it also
        // requires processing actions (login, logout, etc.) before anything else
        if ($this->AUTH){
            $this->AUTH->Init($this->DATABASE, $this->CONFIG);
            $this->AUTH->CheckActionNeeded();
        }

        // initialize router object
        $this->ROUTE = CQRouter::GetInstance();
        $this->ROUTE->Init($this->CORE, $this->CONFIG, $this->AUTH);

        // start loading the theme (loader needs ALL system objects as dependencies
        // so that it can make them available to themes)
        $this->LOADER = CQLoader::GetInstance();
        $this->LOADER->Init(
            $this->DATABASE,
            $this->ROUTE,
            $this->CONFIG,
            $this->CORE,
            $this->AUTH,
            $this->LOG
        );
        $this->LOADER->LoadTheme();

        // last but not least ...
        $this->CORE->UpdateExecFinishTime();
        $this->LOG->Log('loading', $this->LOADER->GetLoadedElements());

        //PrintHTMLDebug($this);
        //PrintHTMLDebug($_SESSION);

        /*
        PrintHTMLDebug(array(
            'qurl_f' => $this->ROUTE->qurl_f('images/some-image.jpg'),
            'qurl_d' => $this->ROUTE->qurl_d('extra'),
            'qurl_file' => $this->ROUTE->qurl_file('file/to/be/accessed/from/url.txt'),
            'qurl_l' => $this->ROUTE->qurl_l('this/is/some/link'),
            'qurl_l SELF' => $this->ROUTE->qurl_l(URL_SELF),
            'qurl_s_noslash' => $this->ROUTE->qurl_s('no-slash'),
            'qurl_s_withslash' => $this->ROUTE->qurl_s('with/slash/and/more'),
            'qurl_serverfile' => $this->ROUTE->qurl_serverfile('file/on/root/filesystem.txt'),
            'qurl_si_noslash' => $this->ROUTE->qurl_si('no-slash'),
            'qurl_si_withslash' => $this->ROUTE->qurl_si('with/slash/and/more'),
            'qurl_v' => $this->ROUTE->qurl_v('something'),
            'qurl_lang' => $this->ROUTE->qurl_lang()
        ));
        */
    }

    function __destruct()
    {
        if ($this->CONFIG && $this->DATABASE) $this->CONFIG->CommitChanges();
        if ($this->DATABASE) $this->DATABASE->Close();
    }
}

new CQAPP();

////////////////////////////////////////////////////////////////////////////////
// History:
//  -- 21/06/2019 - v1 created;