<?php
////////////////////////////////////////////////////////////////////////////////
// Part of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.
// -- For history see the end of this file.

////////////////////////////////////////////////////////////////////////////////
// This file contains utilities to route the request somewhere, generate links
// or addresses to include files, parse URL parameters, etc.

if (!defined('SYSCAP_ROUTE')){
// this tells the whole framework that this system file has been included
define('SYSCAP_ROUTE', true);

// DEFINITIONS
define('URL_FILESYSTEM',                0);
define('URL_RELATIVE',                  1);
define('URL_ABSOLUTE',                  2);
define('URL_RELTOINDEX',                3);
define('URLTYPE_LINK',                  1);
define('URLTYPE_FILEROOT',              2);
define('URLTYPE_FILETHEME',             3);
define('URLTYPE_FILEMEDIAIMAGE',        4);
define('URLTYPE_FILEMEDIASOUND',        5);
define('URLTYPE_FILEMEDIAVIDEO',        6);
define('URLTYPE_FILEMEDIAARCHIVE',      7);
define('URLTYPE_FILEMEDIADOC',          8);
define('URLTYPE_FILEMEDIAOTHER',        9);
define('URLTYPE_SERVERFILE',            10);

define('URLTYPE_FILETHEME_FABRIC',      11);
define('URLTYPE_FILETHEME_DATA',        12);
define('URLTYPE_FILETHEME_NOGUI',       13);
define('URLTYPE_FILETHEME_VIEW',        14);
define('URLTYPE_FILETHEME_ROOT',        15);

define('TARGET_TRANSLATED',             0);
define('TARGET_LOADED',                 1);
define('URL_SELF',                      -1);

class CQRouter
{
    private static $hInstance = NULL;
    
    private $CORE;
    private $CONFIG;
    private $AUTH;
    
    private $arrFlags;
    private $arrTargets;
    
    private $arrFlagsTranslated;
    private $arrTargetsRequested;
    private $arrTargetsTranslated;
    private $arrTargetsFinal;
    
    function __construct()
    {
        $this->CORE = NULL;
        $this->CONFIG = NULL;
        $this->AUTH = NULL;
        
        $this->arrFlags = array();
        $this->arrTargets = array();
        $this->arrFlagsTranslated = array(
            'section' => '',
            'language' => ''
        );
        $this->arrTargetsRequested = array();
        $this->arrTargetsTranslated = array();
        $this->arrTargetsFinal = array(
            'page' => '',               // defines the specific page that should be loaded
            'parameters' => array(),    // defines parameters for that particular page
            'pagevalue' => '',          // defines what page (included in parameters)
            'filters' => array()        // defines filters used within that page (included in parameters)
        );
        // this is complex, so let's give an ample example
        // http://www.website.com/ro/category/monitors/brand/diamond/price/100/500/instock
        // so we would have:
        //  - http://www.website.com = the website address, as found in CORE->strBareURLAbsolute
        //  - ro = flag/language
        //  - index = flag/section (implicit value here)
        //  - category = desired page (to load later)
        //  - monitors/brand/diamond/price/100/500/instock = parameters for that page
        //  - monitors = pagevalue (what page ?)
        //  - brand/diamond/price/100/500/instock = filters
    }
    
    static function GetInstance()
    {
        if (self::$hInstance === NULL) self::$hInstance = new CQRouter();
        
        return self::$hInstance;
    }
    
    function Init(&$kCore, &$kConfig, &$kAuth)
    {
        $this->CORE = $kCore;
        $this->CONFIG = $kConfig;
        $this->AUTH = $kAuth;
        
        $this->DetermineURLQueryLocation();
        $this->TranslateURLQueryFlags();
        
        if ($this->CONFIG->Get('force-desktop'))
            $this->CORE->ForceBrowserType(BRWSR_TYPE_DESKTOP);
        
        $this->TranslateURLQueryTarget();
        
        // TODO: before authentication, see if this is a SEO URL and translate it
        
        // some more things after authentication
        if ($this->AUTH){
            if (!$this->AUTH->HasPermissions($this->arrFlagsTranslated, $this->arrTargetsTranslated)){
                $this->arrTargetsTranslated = array(
                    'unauthorized-' . $this->arrFlagsTranslated['section']
                );
            }
        }
        
        // make a final translation of targets, since we'll need them for URL functions
        $strRoot = $this->CORE->GetScriptFilepath() . '/themes/' .
            $this->CONFIG->Get('theme') . '/pages';
        $arrPage = array();
        $nLastIdx = 0;
        
        foreach ($this->arrTargetsTranslated as $nIndex => $strTarget){
            $strRoot .= '/' . $strTarget;
            
            if (file_exists($strRoot)){
                $arrPage[] = $strTarget;
                $nLastIdx = $nIndex;
            }else break;
        }
        
        if (empty($arrPage)){
            $this->arrTargetsFinal['page'] = 'err404';
            $this->arrTargetsFinal['parameters'] = $this->arrTargetsTranslated;
        }else{
            $this->arrTargetsFinal['page'] = implode('/', $arrPage);
            
            $nCount = count($this->arrTargetsTranslated);
            for ($i = $nLastIdx+1; $i < $nCount; $i++)
                $this->arrTargetsFinal['parameters'][] = $this->arrTargetsTranslated[$i];
        }
        
        // filters will be applied later, so we're good for now :)
    }
    
    private function IsURLFlag($strCode)
    {
        return in_array($strCode, array(
           'aa', 'ab', 'ae', 'af', 'ak', 'am', 'an', 'ar', 'as', 'av', 'ay', 'az',
           'ba', 'be', 'bg', 'bh',
           'bi', 'bm', 'bn', 'bo', 'br', 'bs', 'ca', 'ce', 'ch', 'co', 'cr', 'cs',
           'cu', 'cv', 'cy', 'da', 'de', 'dv',
           'dz', 'ee', 'el', 'en', 'eo', 'es', 'et', 'eu', 'fa', 'ff', 'fi', 'fj',
           'fo', 'fr', 'fy', 'ga', 'gd', 'gl',
           'gn', 'gu', 'gv', 'ha', 'he', 'hi', 'ho', 'hr', 'ht', 'hu', 'hy', 'hz',
           'ia', 'id', 'ie', 'ig', 'ii', 'ik',
           'io', 'is', 'it', 'iu', 'ja', 'jv', 'ka', 'kg', 'ki', 'kj', 'kk', 'kl',
           'km', 'kn', 'ko', 'kr', 'ks', 'ku',
           'kv', 'kw', 'ky', 'la', 'lb', 'lg', 'li', 'ln', 'lo', 'lt', 'lu', 'lv',
           'mg', 'mh', 'mi', 'mk', 'ml', 'mn',
           'mr', 'ms', 'mt', 'my', 'na', 'nb', 'nd', 'ne', 'ng', 'nl', 'nn', 'no',
           'nr', 'nv', 'ny', 'oc', 'oj', 'om',
           'or', 'os', 'pa', 'pi', 'pl', 'ps', 'pt', 'qu', 'rm', 'rn', 'ro', 'ru',
           'rw', 'sa', 'sc', 'sd', 'se', 'sg',
           'si', 'sk', 'sl', 'sm', 'sn', 'so', 'sq', 'sr', 'ss', 'st', 'su', 'sv',
           'sw', 'ta', 'te', 'tg', 'th', 'ti',
           'tk', 'tl', 'tn', 'to', 'tr', 'ts', 'tt', 'tw', 'ty', 'ug', 'uk', 'ur',
           'uz', 've', 'vi', 'vo', 'wa', 'wo',
           'xh', 'yi', 'yo', 'za', 'zh', 'zu', 'admin', 'index', 'nogui')
        );
    }

    private function DetermineURLQueryLocation()
    {
        // getting the location to parse (URL requested)
        $strStrToParse = '';
        
        if ($this->CORE->GetCallingMethod() == CALLMETH_HTTP){
            if (strlen($this->CORE->GetRequestURLRelative()) <= 0 ||
                $this->CORE->GetAppURLRelative() == $this->CORE->GetRequestURLRelative())
            {
                $this->arrTargets[]= 'index';
                return;
            }
            
            if ($this->CORE->GetAppURLType() == REQTYPE_CLEAN){
                if (strlen($this->CORE->GetAppURLRelative()) > 0){
                    $nFound = strpos($this->CORE->GetRequestURLRelative(),
                        $this->CORE->GetAppURLRelative());
                        
                    $strStrToParse = substr($this->CORE->GetRequestURLRelative(),
                        $nFound + strlen($this->CORE->GetAppURLRelative()));
                }else $strStrToParse = $this->CORE->GetRequestURLRelative();
            }else{
                $strStrToParse = isset($_GET['p']) ? $_GET['p'] : 'index/index';
            }
        }else{
            $arrArgs = $this->CORE->GetCLIArguments();
            $nArgs = count($arrArgs);
            $arrFinal = array();
            
            if ($nArgs <= 1){
                $this->arrTargets[]= 'index';
                return;
            }else{
                for ($i=1; $i < $nArgs; $i++){
                    if (strlen($arrArgs[$i]) > 0) $arrFinal[] = $arrArgs[$i];
                }
                
                $strStrToParse = implode('/', $arrFinal);
            }
        }
        
        // we have now what we need, parse the location
        if ($strStrToParse[0] == '/') $strStrToParse = substr($strStrToParse, 1);
        $arrTemp = explode('/', $strStrToParse);
        
        // remove php session id if present
        //if (strpos($arrTemp[0], 'psid') === 0 && strlen($arrTemp[0]) == 30)
            //array_splice($arrTemp, 0, 1);
        
        $nCount = count($arrTemp);
        
        /* ADDRESS REQUEST EXAMPLE
        
            website.com
            website.com/en
            website.com/en-admin
            website.com/admin
            website.com/somethin-somethin
            website.com/en/somethin-somethin
            website.com/en-admin/articles/somethin-somethin
        */
        
        $bFlagsSegmentFound = false;
        
        for ($i=0; $i<$nCount; $i++){
            // determine whether or not we have an URL segment with '-'
            if (!$bFlagsSegmentFound && is_int(strpos($arrTemp[$i], '-'))){
                // break it
                $arrSegment = explode('-', $arrTemp[$i]);
                $nSegments = count($arrSegment);
                $nFlagsFound = 0;
                
                // search for flags ...
                for ($j=0; $j < $nSegments; $j++){
                    if ($this->IsURLFlag($arrSegment[$j])) $nFlagsFound++;
                    else $j = $nSegments+1;   // ... stop when first non-flag found
                }
                
                // count flags
                if ($nFlagsFound == $nSegments){
                    // this is the segment with flags
                    $this->arrFlags = $arrSegment;
                    $bFlagsSegmentFound = true;
                }else{
                    // this is just another 'target' segment
                    if (strpos($arrTemp[$i], 'psid') === false)
                        $this->arrTargets[] = $arrTemp[$i];
                }
            }else{
                if (!$bFlagsSegmentFound && $this->IsURLFlag($arrTemp[$i])){
                    $this->arrFlags[] = $arrTemp[$i];
                    $bFlagsSegmentFound = true;
                }else{
                    // this is just another 'target' segment
                    // but make sure the user did not type several slashes,
                    // resulting in empty segments
                    if (strlen($arrTemp[$i]) > 0 && strpos($arrTemp[$i], 'psid') === false)
                        $this->arrTargets[] = $arrTemp[$i];
                }
            }
            
            // By default, the flags should be the first elements
            // in our URL query. If not found there, assume default flags.
            // Comment the following lines to enable flag-segment search
            // throughout our URL query and allow it to be anywhere
            $nDesiredFlagPos = 0;
            if ($this->CORE->GetSessionType() == SESSTYPE_URL) $nDesiredFlagPos = 1; 
            if ($i == $nDesiredFlagPos) $bFlagsSegmentFound = true;
        }
    }

    private function TranslateURLQueryFlags()
    {
        // search language
        $this->arrFlagsTranslated['language'] =
            $this->CONFIG->Get('default-language-code', 'en');
        
        $arrKeywords=array(
            'aa', 'ab', 'ae', 'af', 'ak', 'am', 'an', 'ar', 'as', 'av', 'ay', 
            'az', 'ba', 'be', 'bg', 
            'bh', 'bi', 'bm', 'bn', 'bo', 'br', 'bs', 'ca', 'ce', 'ch', 'co', 
            'cr', 'cs', 'cu', 'cv', 'cy', 'da', 'de', 
            'dv', 'dz', 'ee', 'el', 'en', 'eo', 'es', 'et', 'eu', 'fa', 'ff', 
            'fi', 'fj', 'fo', 'fr', 'fy', 'ga', 'gd', 
            'gl', 'gn', 'gu', 'gv', 'ha', 'he', 'hi', 'ho', 'hr', 'ht', 'hu', 
            'hy', 'hz', 'ia', 'id', 'ie', 'ig', 'ii', 
            'ik', 'io', 'is', 'it', 'iu', 'ja', 'jv', 'ka', 'kg', 'ki', 'kj', 
            'kk', 'kl', 'km', 'kn', 'ko', 'kr', 'ks', 
            'ku', 'kv', 'kw', 'ky', 'la', 'lb', 'lg', 'li', 'ln', 'lo', 'lt', 
            'lu', 'lv', 'mg', 'mh', 'mi', 'mk', 'ml', 
            'mn', 'mr', 'ms', 'mt', 'my', 'na', 'nb', 'nd', 'ne', 'ng', 'nl', 
            'nn', 'no', 'nr', 'nv', 'ny', 'oc', 'oj', 
            'om', 'or', 'os', 'pa', 'pi', 'pl', 'ps', 'pt', 'qu', 'rm', 'rn', 
            'ro', 'ru', 'rw', 'sa', 'sc', 'sd', 'se', 
            'sg', 'si', 'sk', 'sl', 'sm', 'sn', 'so', 'sq', 'sr', 'ss', 'st', 
            'su', 'sv', 'sw', 'ta', 'te', 'tg', 'th', 
            'ti', 'tk', 'tl', 'tn', 'to', 'tr', 'ts', 'tt', 'tw', 'ty', 'ug', 
            'uk', 'ur', 'uz', 've', 'vi', 'vo', 'wa', 
            'wo', 'xh', 'yi', 'yo', 'za', 'zh', 'zu'
        );
        
        $nFlags = count($this->arrFlags);
        
        for ($i=0; $i < $nFlags; $i++){
            if (in_array($this->arrFlags[$i], $arrKeywords)){
                $this->arrFlagsTranslated['language'] = $this->arrFlags[$i];
                $i = $nFlags+1;
            }
        }
        
        // search section
        $this->arrFlagsTranslated['section'] = 
            in_array('admin', $this->arrFlags) ? 'admin' :
                (in_array('nogui', $this->arrFlags) ? 'nogui' : 'index');
    }

    private function TranslateURLQueryTarget()
    {
        $arrTargetOutput=array();
        
        foreach ($this->arrTargets as $nKey => $strVal){
            if (strpos($strVal, 'psid') === false) $arrTargetOutput[] = $strVal;
        }
        
        // if array is empty, insert index
        if (count($arrTargetOutput) == 0) $arrTargetOutput = array('index');
        
        $this->arrTargetsTranslated = $arrTargetOutput;
        $this->arrTargetsRequested = $arrTargetOutput;
    }
    
    
    //private function SysGetInnerTargetStr($mxTarget=TARGET_LOADED, $bWithFlags=true)
    //{
    //    global $APP;
    //    
    //    if ($mxTarget===TARGET_TRANSLATED){
    //        return ($bWithFlags ? '*/'.$APP['target']['flags-translated']['section'].'/' : '').
    //            implode('/', $APP['target']['targets-translated']);
    //            
    //    }elseif ($mxTarget===TARGET_LOADED){
    //        return ($bWithFlags ? '*/'.$APP['target']['flags-translated']['section'].'/' : '').
    //            implode('/', $APP['target']['targets-loaded']);
    //            
    //    }elseif (is_array($mxTarget)){
    //        return ($bWithFlags ? '*/'.$APP['target']['flags-translated']['section'].'/' : '').
    //            implode('/', $mxTarget);
    //    }
    //    
    //    return '-';
    //}
    //
    
    public function BuildURL($nRelativity, $nType, $arrFlags, $strTarget)
    {
        /*
        if $this->CORE->GetAppURLType() is clean, we'll use http//path/to/our/link
        else we'll use http://index.php?p=path/to/our/link
        
        if $this->CORE->GetSessionType() is clean we'll omit the session Id from URL
        else we'll add either "psidSESSID or index.php?SYSCFG_HTTP_SESSIONNAME=SESSID
        
        if $nRelativity is ABSOLUTE we'll write http://domanin.tld/fullpath
        else we'll write /path/to/our/link
        */
        
        $strRet = '';
        
        switch ($nRelativity)
        {
            case URL_ABSOLUTE: $strRet = $this->CORE->GetAppURLAbsolute(); break;
            case URL_RELATIVE: $strRet = $this->CORE->GetAppURLRelative(); break;
            case URL_RELTOINDEX: $strRet=''; break;
        }
        
        $strSessStr = '';
        $strTargetStr = '';
        
        if (!(isset($arrFlags['behaviour']['ignoresession']) && $arrFlags['behaviour']['ignoresession'])){
            if ($this->CORE->GetSessionType() == SESSTYPE_URL){
                if ($this->CORE->GetAppURLType() == REQTYPE_CLEAN)
                    $strSessStr = 'psid' . session_id();
                else $strSessStr = SYSCFG_HTTP_SESSIONNAME . '=' . session_id();
            }
        }
        
        switch ($nType)
        {
            case URLTYPE_LINK:{
                $strFlagsImploded = '';
                
                if ($arrFlags === NULL){
                    $arrFlagsHere = array();
                    
                    if ($this->arrFlagsTranslated['language'] != $this->CONFIG->Get('default-language-code'))
                        $arrFlagsHere[] = $this->arrFlagsTranslated['language'];
                    
                    if ($this->arrFlagsTranslated['section'] != 'index')
                        $arrFlagsHere[] = $this->arrFlagsTranslated['section'];
                    
                    $strFlagsImploded = implode('-', $arrFlagsHere);
                }else{
                    $arrFlagsHere = array();
                    
                    if (isset($arrFlags['language']) &&
                        $arrFlags['language'] != $this->CONFIG->Get('default-language-code'))
                        $arrFlagsHere[] = $arrFlags['language'];
                    
                    if (isset($arrFlags['section']) && $arrFlags['section'] != 'index')
                        $arrFlagsHere[] = $arrFlags['section'];
                    
                    $strFlagsImploded = implode('-', $arrFlagsHere);
                }
                
                if (strlen($strFlagsImploded) > 0)
                    $strTargetStr = $strFlagsImploded . '/' . $strTarget;
                else $strTargetStr = $strTarget;
            }break;
                
            case URLTYPE_FILEROOT:{
                $strTargetStr = $strTarget;
            }break;
            
            case URLTYPE_SERVERFILE:{
                return $this->CORE->GetScriptFilepath() . '/' . $strTarget;
            }break;
            
            case URLTYPE_FILETHEME_FABRIC:{
                $strTargetStr = 'themes/' . $this->CONFIG->Get('theme') . '/fabric/';
                
                $nPlatform = 0;
                
                if (isset($arrFlags['platform'])) $nPlatform = $arrFlags['platform'];
                else $nPlatform = $this->CORE->GetBrowserType();
                
                switch ($nPlatform)
                {
                    case BRWSR_TYPE_DESKTOP: case BRWSR_TYPE_BOT: case BRWSR_TYPE_CLI:{
                        if ($arrFlags === NULL){
                            switch ($this->arrFlagsTranslated['section'])
                            {
                                case 'index':{
                                    $strTargetStr .= 'desktopfront/';
                                }break;
                                case 'admin':{
                                    $strTargetStr .= 'desktopadmin/';
                                }break;
                                case 'nogui':{
                                    $strTargetStr .= 'nogui/';
                                }break;
                            }
                        }else{
                            switch ($arrFlags['section'])
                            {
                                case 'index':{
                                    $strTargetStr .= 'desktopfront/';
                                }break;
                                case 'admin':{
                                    $strTargetStr .= 'desktopadmin/';
                                }break;
                                case 'nogui':{
                                    $strTargetStr .= 'nogui/';
                                }break;
                            }
                        }
                    }break;
                    case BRWSR_TYPE_MOBILE:{
                        if ($arrFlags === NULL){
                            switch ($this->arrFlagsTranslated['section'])
                            {
                                case 'index':{
                                    $strTargetStr .= 'mobilefront/';
                                }break;
                                case 'admin':{
                                    $strTargetStr .= 'mobileadmin/';
                                }break;
                                case 'nogui':{
                                    $strTargetStr .= 'nogui/';
                                }break;
                            }
                        }else{
                            switch ($arrFlags['section'])
                            {
                                case 'index':{
                                    $strTargetStr .= 'mobilefront/';
                                }break;
                                case 'admin':{
                                    $strTargetStr .= 'mobileadmin/';
                                }break;
                                case 'nogui':{
                                    $strTargetStr .= 'nogui/';
                                }break;
                            }
                        }
                    }break;
                }
                
                $strTargetStr .= $strTarget;
            }break;
            
            case URLTYPE_FILETHEME_DATA:{
                $strTargetStr = 'themes/' . $this->CONFIG->Get('theme') . '/pages/' .
                    $this->arrTargetsFinal['page'] . '/';
                
                $nPlatform = 0;
                
                if (isset($arrFlags['platform'])) $nPlatform = $arrFlags['platform'];
                else $nPlatform = $this->CORE->GetBrowserType();
                
                switch ($nPlatform)
                {
                    case BRWSR_TYPE_DESKTOP: case BRWSR_TYPE_BOT: case BRWSR_TYPE_CLI:{
                        if ($arrFlags === NULL){
                            switch ($this->arrFlagsTranslated['section'])
                            {
                                case 'index':{
                                    $strTargetStr .= 'desktopfront/';
                                }break;
                                case 'admin':{
                                    $strTargetStr .= 'desktopadmin/';
                                }break;
                                case 'nogui':{
                                    $strTargetStr .= 'nogui/';
                                }break;
                            }
                        }else{
                            switch ($arrFlags['section'])
                            {
                                case 'index':{
                                    $strTargetStr .= 'desktopfront/';
                                }break;
                                case 'admin':{
                                    $strTargetStr .= 'desktopadmin/';
                                }break;
                                case 'nogui':{
                                    $strTargetStr .= 'nogui/';
                                }break;
                            }
                        }
                    }break;
                    case BRWSR_TYPE_MOBILE:{
                        if ($arrFlags === NULL){
                            switch ($this->arrFlagsTranslated['section'])
                            {
                                case 'index':{
                                    $strTargetStr .= 'mobilefront/';
                                }break;
                                case 'admin':{
                                    $strTargetStr .= 'mobileadmin/';
                                }break;
                                case 'nogui':{
                                    $strTargetStr .= 'nogui/';
                                }break;
                            }
                        }else{
                            switch ($arrFlags['section'])
                            {
                                case 'index':{
                                    $strTargetStr .= 'mobilefront/';
                                }break;
                                case 'admin':{
                                    $strTargetStr .= 'mobileadmin/';
                                }break;
                                case 'nogui':{
                                    $strTargetStr .= 'nogui/';
                                }break;
                            }
                        }
                    }break;
                }
                
                $strTargetStr .= 'data-' . $strTarget;
            }break;
            
            case URLTYPE_FILETHEME_VIEW:{
                $strTargetStr = 'themes/' . $this->CONFIG->Get('theme') . '/pages/' .
                    $this->arrTargetsFinal['page'] . '/';
                
                $nPlatform = 0;
                
                if (isset($arrFlags['platform'])) $nPlatform = $arrFlags['platform'];
                else $nPlatform = $this->CORE->GetBrowserType();
                
                switch ($nPlatform)
                {
                    case BRWSR_TYPE_DESKTOP: case BRWSR_TYPE_BOT: case BRWSR_TYPE_CLI:{
                        if ($arrFlags === NULL){
                            switch ($this->arrFlagsTranslated['section'])
                            {
                                case 'index':{
                                    $strTargetStr .= 'desktopfront/';
                                }break;
                                case 'admin':{
                                    $strTargetStr .= 'desktopadmin/';
                                }break;
                                case 'nogui':{
                                    $strTargetStr .= 'nogui/';
                                }break;
                            }
                        }else{
                            switch ($arrFlags['section'])
                            {
                                case 'index':{
                                    $strTargetStr .= 'desktopfront/';
                                }break;
                                case 'admin':{
                                    $strTargetStr .= 'desktopadmin/';
                                }break;
                                case 'nogui':{
                                    $strTargetStr .= 'nogui/';
                                }break;
                            }
                        }
                    }break;
                    case BRWSR_TYPE_MOBILE:{
                        if ($arrFlags === NULL){
                            switch ($this->arrFlagsTranslated['section'])
                            {
                                case 'index':{
                                    $strTargetStr .= 'mobilefront/';
                                }break;
                                case 'admin':{
                                    $strTargetStr .= 'mobileadmin/';
                                }break;
                                case 'nogui':{
                                    $strTargetStr .= 'nogui/';
                                }break;
                            }
                        }else{
                            switch ($arrFlags['section'])
                            {
                                case 'index':{
                                    $strTargetStr .= 'mobilefront/';
                                }break;
                                case 'admin':{
                                    $strTargetStr .= 'mobileadmin/';
                                }break;
                                case 'nogui':{
                                    $strTargetStr .= 'nogui/';
                                }break;
                            }
                        }
                    }break;
                }
                
                $strTargetStr .= 'view-' . $strTarget;
            }break;
            
            case URLTYPE_FILETHEME_NOGUI:{
                if (strpos($strTarget, '/') === FALSE){
                    $strTargetStr = 'themes/' . $this->CONFIG->Get('theme') . '/pages/' .
                        $this->arrTargetsFinal['page'] . '/nogui/';
                    
                    $strTargetStr .= $strTarget . '.php';
                }else{
                    $strPage = substr($strTarget, 0, strrpos($strTarget, '/'));
                    $strScript = substr($strTarget, strrpos($strTarget, '/')+1);
                    
                    $strTargetStr = 'themes/' . $this->CONFIG->Get('theme') . '/pages/' .
                        $strPage . '/nogui/' . $strScript . '.php';
                }
            }break;
            
            case URLTYPE_FILETHEME:{
                if (isset($arrFlags['section']))
                    $strTargetStr = 'themes/' . $this->CONFIG->Get('theme') . '/pages/' .
                        $arrFlags['section'] . (strlen($arrFlags['section']) > 0 ? '/' : '');
                else $strTargetStr = 'themes/' . $this->CONFIG->Get('theme') . '/pages/' .
                    $this->arrTargetsFinal['page'] . '/';
                
                $strTargetStr .= $strTarget;
            }break;
            
            case URLTYPE_FILETHEME_ROOT:{
                $strTargetStr = 'themes/' . $this->CONFIG->Get('theme') . '/';
                
                $strTargetStr .= $strTarget;
            }break;
            
            case URLTYPE_FILEMEDIAIMAGE:{
                $strTargetStr = 'media/images/' . $strTarget;
            }break;
            case URLTYPE_FILEMEDIASOUND:{
                $strTargetStr = 'media/sounds/' . $strTarget;
            }break;
            case URLTYPE_FILEMEDIAVIDEO:{
                $strTargetStr = 'media/videos/' . $strTarget;
            }break;
            case URLTYPE_FILEMEDIAARCHIVE:{
                $strTargetStr = 'media/archives/' . $strTarget;
            }break;
            case URLTYPE_FILEMEDIADOC:{
                $strTargetStr = 'media/documents/' . $strTarget;
            }break;
            case URLTYPE_FILEMEDIAOTHER:{
                $strTargetStr = 'media/others/' . $strTarget;
            }break;
        }
        
        // further processing of the output string
        if ($this->CORE->GetAppURLType() == REQTYPE_CLEAN){
            if (strlen($strRet) > 0 && $strRet[strlen($strRet)-1] != '/' &&
                $nRelativity != URL_RELTOINDEX)
                $strRet .= '/';
                
            if (strlen($strSessStr) > 0 && $nType == URLTYPE_LINK)
                $strRet .= $strSessStr.'/';
            
            if (strlen($strTargetStr) > 0) $strRet .= $strTargetStr;
        }else{
            if ($nType == URLTYPE_LINK){
                if (strlen($strRet) > 0 && $strRet[strlen($strRet)-1] != '/' &&
                    $nRelativity != URL_RELTOINDEX)
                    $strRet .= '/';
                    
                $strRet .= 'index.php?';
                
                if (strlen($strSessStr) > 0) $strRet .= $strSessStr;
                
                if (strlen($strTargetStr) > 0){
                    if (strlen($strSessStr) > 0) $strRet .= '&';
                    $strRet .= 'p=' . $strTargetStr;
                }
            }else{
                if (strlen($strRet) > 0 && $strRet[strlen($strRet)-1] != '/' &&
                    $nRelativity != URL_RELTOINDEX)
                    $strRet .= '/';
                    
                if (strlen($strTargetStr) > 0) $strRet .= $strTargetStr;
            }
        } 
        
        return $strRet;
    }

    /**
     * qurl_f function - shortcut for BuildURL() function / fabric special.
     * @param {String} pcFabric - the desired 'fabric' file, e.g.: images/logo.jpg
     * @return {String} - The result of BuildURL() function
     */
    function qurl_f($strFabric, $strSection=NULL)
    {
        $mxSection = NULL;
        if ($strSection) $mxSection = array('section' => $strSection);
        
        return $this->BuildURL(URL_ABSOLUTE, URLTYPE_FILETHEME_FABRIC,
            $mxSection, $strFabric);
    }
    
    /**
     * qurl_d function - shortcut for BuildURL() function / data special.
     * @param {String} pcItem - the desired 'fabric' file, e.g.: index/index.php
     * @return {String} - The result of BuildURL() function
     */
    function qurl_d($strItem='content')
    {
        return $this->BuildURL(URL_RELTOINDEX, URLTYPE_FILETHEME_DATA, NULL,
            $strItem . '.php');
    }
    
    /**
     * qurl_v function - shortcut for BuildURL() function / view special.
     * @param {String} pcItem - the desired 'fabric' file, e.g.: common/header.php
     * @return {String} - The result of BuildURL() function
     */
    function qurl_v($strItem='content')
    {
        return $this->BuildURL(URL_RELTOINDEX, URLTYPE_FILETHEME_VIEW, NULL,
            $strItem . '.php');
    }
    
    /**
     * qurl_lang function - shortcut for BuildURL() function / language special.
     * @return {String} - The result of BuildURL() function
     */
    function qurl_lang()
    {
        return $this->BuildURL(URL_RELTOINDEX, URLTYPE_FILETHEME, NULL, 'lang/' .
            $this->arrFlagsTranslated['language'] . '.txt');
    }
    
    /**
     * qurl_l function - shortcut for BuildURL() function / link.
     * @param {String} pcItem - the desired link location
     * @param (array) arrFlags - desired language and/or section flags; NULL for nothing
     * @return {String} - The result of BuildURL() function
     */
    function qurl_l($mxItem, $arrFlags = NULL)
    {
        if ($mxItem === URL_SELF){
            return $this->BuildURL(URL_ABSOLUTE, URLTYPE_LINK,
                $this->arrFlagsTranslated, implode('/', $this->arrTargets));
        }else return $this->BuildURL(URL_ABSOLUTE, URLTYPE_LINK, $arrFlags, $mxItem);
    }
    
    /**
     * qurl_s function - shortcut for BuildURL() function to create a link towards
     * a 'nogui' script
     * @param {String} pcItem - the desired link location
     * @return {String} - The result of BuildURL() function
     */
    function qurl_s($strItem)
    {
        $arrFlags=array();
        
        if ($this->arrFlagsTranslated['language'] != $this->CONFIG->Get('default-language-code'))
            $arrFlags['language'] = $this->arrFlagsTranslated['language'];
        
        $arrFlags['section'] = 'nogui';
        return $this->BuildURL(URL_ABSOLUTE, URLTYPE_LINK, $arrFlags, $strItem);
    }
    
    /**
     * qurl_si function - shortcut for BuildURL() function to include
     * a 'nogui' script from within
     * @param {String} pcItem - the desired link location
     * @return {String} - The result of BuildURL() function
     */
    function qurl_si($strItem)
    {
        return $this->BuildURL(URL_RELTOINDEX, URLTYPE_FILETHEME_NOGUI, NULL, $strItem);
    }
    
    /**
     * qurl_serverfile function - shortcut for BuildURL() function to use
     * when the actual server paths are needed, like when uploading a file
     * @param {String} pcItem - the desired link location
     * @return {String} - The result of BuildURL() function
     */
    function qurl_serverfile($strItem)
    {
        return $this->BuildURL(0, URLTYPE_SERVERFILE, NULL, $strItem);
    }
    
    /**
     * qurl_m function - shortcut for BuildURL() function / media file.
     * @param {String} pcItem - the desired link location
     * @return {String} - The result of BuildURL() function
     */
    function qurl_file($strItem)
    {
        return $this->BuildURL(URL_ABSOLUTE, URLTYPE_FILEROOT, NULL, $strItem);
    }
    
    function qurl_themeroot($strTarget = '')
    {
        return $this->BuildURL(URL_RELTOINDEX, URLTYPE_FILETHEME_ROOT, NULL, $strTarget);
    }
    
    function qurl_pageroot($strItem = '')
    {
        return $this->BuildURL(URL_RELTOINDEX, URLTYPE_FILETHEME, NULL, $strItem);
    }
    
    function UpdatePageValue($n)
    {
        if ($n > 0){
            $arrPageValue = array();
            
            for ($i=0; $i < min(count($this->arrTargetsFinal['parameters']), $n); $i++)
                $arrPageValue[] = $this->arrTargetsFinal['parameters'][$i];
            
            $this->arrTargetsFinal['pagevalue'] = implode('/', $arrPageValue);
        }
    }
    
    function PARAMS()
    {
        return count($this->arrTargetsTranslated) - 1;
    }
    
    function PARAM($n, $mxDefault='')
    {
        return (isset($this->arrTargetsTranslated[$n]) ?
            $this->arrTargetsTranslated[$n] : $mxDefault);
    }
    
    function GetFlagsLanguage()
    {
        return $this->arrFlagsTranslated['language'];
    }
    
    function GetFlagsSection()
    {
        return $this->arrFlagsTranslated['section'];
    }
    
    function GetPage()
    {
        return $this->arrTargetsFinal['page'];
    }
    
    function GetPageValue()
    {
        return $this->arrTargetsFinal['pagevalue'];
    }
    
    function GetTargetsRequested($bAsString = true)
    {
        return ($bAsString ? implode('/', $this->arrTargetsRequested) : $this->arrTargetsRequested);
    }
    
    function GetExistingTargetList($bMobile = false, $strSection = 'index')
    {
        $arrRet = array();
        $pcDir = '';
        
        $pcDir = $this->BuildURL(URL_RELTOINDEX, URLTYPE_FILETHEME, array('section' => ''), '');
        
        $strReference = ($bMobile ? 'mobile' : 'desktop') . ($strSection == 'index' ? 'front' : 'admin');
        
        $hDirectory = opendir($pcDir);
        if ($hDirectory){
            while (false !== ($pcFileObject = readdir($hDirectory))){
                if ($pcFileObject != '.' && $pcFileObject != '..' && is_dir("$pcDir/$pcFileObject")){
                    if (file_exists("$pcDir/$pcFileObject/$strReference"))
                        $arrRet[] = $pcFileObject;
                }
            }
            
            closedir($hDirectory);
        }
        
        return $arrRet;
    }
    
    function GetExistingModuleList($bMobile=false)
    {
        $arrRet = array();
        $pcDir = '';
        
        // for data layer
        if ($bMobile)
            $pcDir = $this->BuildURL(URL_RELTOINDEX, URLTYPE_FILETHEME_ROOT,
                array('section' => 'index', 'platform' => 'mobile'), 'modules');
        else $pcDir = $this->BuildURL(URL_RELTOINDEX, URLTYPE_FILETHEME_ROOT,
                array('section' => 'index', 'platform' => 'desktop'), 'modules');
        
        $hDirectory = opendir($pcDir);
        if ($hDirectory){
            while (false !== ($pcFileObject = readdir($hDirectory))){
                if ($pcFileObject != '.' && $pcFileObject != '..' && is_dir("$pcDir/$pcFileObject")){
                    $arrRet[] = $pcFileObject;
                }
            }
            
            closedir($hDirectory);
        }
        
        return $arrRet;
    }
    
    function Redirect($pcAddr, $nStatusCode=301)
    {
        header('Location: ' . $pcAddr, true, $nStatusCode);
        exit;
    }
}

// a few convenience functions
function qurl_f($strFabric, $strSection=NULL)
{
    $kRoute = CQRouter::GetInstance();
    return $kRoute->qurl_f($strFabric, $strSection);
}

function qurl_d($strItem='index')
{
    $kRoute = CQRouter::GetInstance();
    return $kRoute->qurl_d($strItem);
}

function qurl_v($strItem='content')
{
    $kRoute = CQRouter::GetInstance();
    return $kRoute->qurl_v($strItem);
}

function qurl_lang()
{
    $kRoute = CQRouter::GetInstance();
    return $kRoute->qurl_lang();
}

function qurl_l($mxItem, $arrFlags = NULL)
{
    $kRoute = CQRouter::GetInstance();
    return $kRoute->qurl_l($mxItem, $arrFlags);
}

function qurl_s($strItem)
{
    $kRoute = CQRouter::GetInstance();
    return $kRoute->qurl_s($strItem);
}

function qurl_si($strItem)
{
    $kRoute = CQRouter::GetInstance();
    return $kRoute->qurl_si($strItem);
}

function qurl_serverfile($strItem)
{
    $kRoute = CQRouter::GetInstance();
    return $kRoute->qurl_serverfile($strItem);
}

function qurl_file($strItem)
{
    $kRoute = CQRouter::GetInstance();
    return $kRoute->qurl_file($strItem);
}

function qurl_themeroot($strTarget = '')
{
    $kRoute = CQRouter::GetInstance();
    return $kRoute->qurl_themeroot($strTarget);
}

function qurl_pageroot($strItem = '')
{
    $kRoute = CQRouter::GetInstance();
    return $kRoute->qurl_pageroot($strItem);
}

function PARAMS()
{
    $kRoute = CQRouter::GetInstance();
    return $kRoute->PARAMS();
}

function PARAM($n, $mxDefault = '')
{
    $kRoute = CQRouter::GetInstance();
    return $kRoute->PARAM($n, $mxDefault);
}


} // endif defined SYSCAP_ROUTE

////////////////////////////////////////////////////////////////////////////////
// History:
//  -- 21/06/2019 - v1 created;