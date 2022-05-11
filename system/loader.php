<?php

require_once('system/thirdparty/vendor/autoload.php');

use Rakit\Validation\Validator;
////////////////////////////////////////////////////////////////////////////////
// Part of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.
// -- For history see the end of this file.

////////////////////////////////////////////////////////////////////////////////
// This file contains the loader that loads all the theme files

if (!defined('SYSCAP_LOADER')){
// this tells the whole framework that this system file has been included
define('SYSCAP_LOADER',	true);

define('MANOP_GET',             0);
define('MANOP_SET',             1);
define('MANOP_ARRAYAPPEND',     2);

////////////////////////////////////////////////////////////////////////////////
// CQSingleLoader
//
// The purpose of this class is to load a single module and hold it's contents
////////////////////////////////////////////////////////////////////////////////
class CQSingleLoader
{
    private $DATA;
    private $LANGUAGE;

    private $strLayout;
    private $strModule;
    private $nIndexModule;

    private $fncCallback;

    private $CORE;
    private $LOG;
    private $CONFIG;
    private $DATABASE;
    private $ROUTE;
    private $AUTH;

    private $GLOBAL;

    private $arrLoadedFiles;

    private $validator;

    function __construct()
    {
        $this->DATA = array();
        $this->LANGUAGE = array();

        $this->strLayout = '';
        $this->strModule = '';
        $this->nIndexModule = 0;

        $this->fncCallback = NULL;

        $this->DATABASE = NULL;
        $this->ROUTE = NULL;
        $this->CONFIG = NULL;
        $this->CORE = NULL;
        $this->AUTH = NULL;
        $this->LOG = NULL;

        $this->GLOBAL = NULL;

        $this->arrLoadedFiles = array();
        $this->validator = new Validator;
    }

    function Init($strLayout, $strModule, $nIndex, $fncCall, &$arrPublicVars,
        &$kDatabase, &$kRoute, &$kConfig, &$kCore, &$kAuth, &$kLog)
    {
        $this->strLayout = $strLayout;
        $this->strModule = $strModule;
        $this->nIndexModule = $nIndex;

        $this->fncCallback = $fncCall;

        $this->DATABASE = $kDatabase;
        $this->ROUTE = $kRoute;
        $this->CONFIG = $kConfig;
        $this->CORE = $kCore;
        $this->AUTH = $kAuth;
        $this->LOG = $kLog;

        $this->GLOBAL = &$arrPublicVars;
    }

    function RequestOperation($strKey, $nWhat = MANOP_GET, $mxValue = NULL)
    {
        switch ($nWhat)
        {
            case MANOP_GET:{
                return (isset($this->DATA[$strKey]) ? $this->DATA[$strKey] : NULL);
            }break;

            case MANOP_SET:{
                $this->DATA[$strKey] = $mxValue;
                return true;
            }break;

            case MANOP_ARRAYAPPEND:{
                if (isset($this->DATA[$strKey])){
                    if (is_array($this->DATA[$strKey])){
                        $this->DATA[$strKey][] = $mxValue;
                        return true;
                    }else return false;
                }else{
                    $this->DATA[$strKey] = array($mxValue);
                    return true;
                }
            }break;
        }
    }

    private function ProcessLanguageText($strFile)
    {
        $arrRet = array();

        if (file_exists($strFile)){
            $pcFileContents = file_get_contents($strFile);
            $pcFileContents = str_replace("\r", '', $pcFileContents);

            $arrContents = trimexplode("\n", $pcFileContents);

            foreach ($arrContents as $pcLine){
                $pcTrimmed = trim($pcLine);

                if (strlen($pcTrimmed) > 0 && $pcTrimmed[0] != '#'){
                    $pcKey = trim(substr($pcTrimmed, 0, strpos($pcTrimmed, '=')));
                    $pcVal = trim(substr($pcTrimmed, strpos($pcTrimmed, '=') + 1));

                    if (strlen($pcKey) > 0){
                        $arrRet[$pcKey] = $pcVal;
                    }
                }
            }
        }

        return $arrRet;
    }

    function ProcessConfigText($strContent, $strKeyValueDelim = NULL,
        $strSubvalueDelim = NULL, $bIndexByLeftColumn = false)
    {
        $arrRet = array();

        $strInterm = str_replace("\r", '', $strContent);
        $arrInterm = trimexplode("\n", $strInterm);

        foreach ($arrInterm as $strLine){
            if (strlen($strLine) > 0 && $strLine[0] != '#') $arrRet[] = $strLine;
        }

        if ($strKeyValueDelim == NULL) return $arrRet;

        $n = count($arrRet);

        for ($i=0; $i < $n; $i++){
            $arrRet[$i] = trimexplode($strKeyValueDelim, $arrRet[$i]);

            if ($strSubvalueDelim != NULL)
                $arrRet[$i][1] = trimexplode($strSubvalueDelim, $arrRet[$i][1]);
        }

        if ($bIndexByLeftColumn){
            $arrOther = array();

            for ($i=0; $i < $n; $i++)
                $arrOther[$arrRet[$i][0]] = $arrRet[$i][1];

            $arrRet = $arrOther;
        }

        return $arrRet;
    }

    function RecomposeConfigText($arrContent, $pcKeyValueDelim = NULL, $pcSubvalueDelim = NULL)
    {
        if (!is_array($arrContent)) return '';

        $pcRet='';

        foreach ($arrContent as $mxLine){
            if (is_array($mxLine))
                $pcRet.=$mxLine[0];

            if ($pcKeyValueDelim) $pcRet.=' '.$pcKeyValueDelim.' ';

            if (is_array($mxLine[1]) && $pcSubvalueDelim){
                $n=count($mxLine[1]);

                for ($i=0; $i<$n; $i++)
                    $pcRet.=$mxLine[1][$i].($i<$n-1 ? $pcSubvalueDelim.' ' : '');

            }else $pcRet.=$mxLine[1];

            $pcRet.="\r\n";
        }

        return $pcRet;
    }

    function LoadLanguage($strFile)
    {
        $this->LANGUAGE = $this->ProcessLanguageText($strFile);
    }

    function LANG($strText)
    {
        return (isset($this->LANGUAGE[$strText]) ? $this->LANGUAGE[$strText] : $strText);
    }

    function L($strText)
    {
        return $this->LANG($strText);
    }

    function LoadData($strFile)
    {
        if (file_exists($strFile)) include $strFile;
    }

    function LoadView($strFile, $strRenderEngine)
    {
        switch ($strRenderEngine)
        {
            case 'json':{
                return json_encode($this->DATA);
            }break;

            case 'unbuffered-php':{
                if (file_exists($strFile . '.php')) include $strFile . '.php';
                return '';
            }break;

            case 'buffered-php':{
                // TODO: later
                return '';
            }break;

            case 'qht':{    // QWF HTML Template engine
                // TODO: later
                return '';
            }break;

            default:{
                $this->LOG->Write('ERROR: undefined rendering engine: ' . $strRenderEngine);
                return '';
            }
        }
    }

    function logException(\Exception $e) {
        $msg = "\nGET PARAMS: " . http_build_query($_GET) . "\n";
        $msg .= "POST PARAMS: " . print_r($_POST, true) . "\n";
        $msg .= "FILES: " . print_r($_FILES, true) . "\n";
        $msg .= "REQUEST URI: " . $_SERVER['REQUEST_URI'] . "\n";
        $msg .= "REMOTE_ADDR: " . $_SERVER['REMOTE_ADDR'] . "\n";
        $msg .= "User-Agent: " . (@$_SERVER['HTTP_USER_AGENT'] ? : 'UNKNOWN') . "\n";
        $msg .= "Error message: " . $e->getMessage() . "\n";
        $this->LOG->Log('error', $msg);
    }

    function handleAPIRequest($callable) {
        $this->DATA = [
            'result' => 'success',
            'tstamp' => date('YmdHis'),
        ];

        try {
            $callable();
        } catch (\Exception $e) {
            $this->DATA['result'] = $e->getMessage();
            http_response_code($e->getCode());
            $this->logException($e);
        }
    }
}

class CQLoader
{
    private static $hInstance = NULL;
    private static $bLoading = false;  // makes sure the loading process only happens once

    private $CORE;
    private $LOG;
    private $CONFIG;
    private $DATABASE;
    private $ROUTE;
    private $AUTH;

    public $GLOBAL;

    private $arrTheme;
    private $arrPageDetails;
    private $arrLayout;
    private $arrModules;
    private $arrModuleData;

    function __construct()
    {
        $this->DATABASE = NULL;
        $this->ROUTE = NULL;
        $this->CONFIG = NULL;
        $this->CORE = NULL;
        $this->AUTH = NULL;
        $this->LOG = NULL;

        $this->GLOBAL = array();

        $this->arrTheme = array(
            'title' => 'n/a',
            'version' => '0.0',
            'version-major' => 0,
            'version-minor' => 0,
            'type' => 'general'
        );

        $this->arrPageDetails = array(
            'pagevalue_number_of_targets' => 0,
            'renderer' => 'unbuffered-php'
        );

        $this->arrLayout = NULL;
        $this->arrModules = NULL;

        $this->arrModuleData = array();
    }

    static function GetInstance()
    {
        if (self::$hInstance === NULL) self::$hInstance = new CQLoader();

        return self::$hInstance;
    }

    static function IsLoading(){ return self::$bLoading; }
    static function SignalLoading(){ self::$bLoading = true; }

    function Init(&$kDatabase, &$kRoute, &$kConfig, &$kCore, &$kAuth, &$kLog)
    {
        $this->DATABASE = $kDatabase;
        $this->ROUTE = $kRoute;
        $this->CONFIG = $kConfig;
        $this->CORE = $kCore;
        $this->AUTH = $kAuth;
        $this->LOG = $kLog;
    }

    private function ProcessConfigText($strContent, $strKeyValueDelim = NULL,
        $strSubvalueDelim = NULL, $bIndexByLeftColumn = false)
    {
        $arrRet = array();

        $strInterm = str_replace("\r", '', $strContent);
        $arrInterm = trimexplode("\n", $strInterm);

        foreach ($arrInterm as $strLine){
            if (strlen($strLine) > 0 && $strLine[0] != '#') $arrRet[] = $strLine;
        }

        if ($strKeyValueDelim == NULL) return $arrRet;

        $n = count($arrRet);

        for ($i=0; $i < $n; $i++){
            $arrRet[$i] = trimexplode($strKeyValueDelim, $arrRet[$i]);

            if ($strSubvalueDelim != NULL)
                $arrRet[$i][1] = trimexplode($strSubvalueDelim, $arrRet[$i][1]);
        }

        if ($bIndexByLeftColumn){
            $arrOther = array();

            for ($i=0; $i < $n; $i++)
                $arrOther[$arrRet[$i][0]] = $arrRet[$i][1];

            $arrRet = $arrOther;
        }

        return $arrRet;
    }

    function ManipulateOtherModules($strModuleName, $strKey, $nOperation = MANOP_GET, $mxData = NULL, $nWhichModule = 0)
    {
        foreach ($this->arrModules as $strLayout => $arrModuleList){
            foreach ($arrModuleList as $strModule){
                if ($strModule == $strModuleName){
                    if (isset($this->arrModuleData[$strLayout]['modules'][$strModule][$nWhichModule])){
                        return $this->arrModuleData[$strLayout]['modules'][$strModule][$nWhichModule]->RequestOperation($strKey, $nOperation, $mxData);
                    }
                }
            }
        }

        return NULL;
    }

    private function LoadExtraLibraries($strLib)
    {
        switch ($strLib)
        {
            case 'input_excel':{
                require_once 'system/thirdparty/excel-reader/excel_reader2.php';
                require_once 'system/thirdparty/excel-reader/SpreadsheetReader.php';
            }break;

            case 'output_excel':{
                require_once 'system/thirdparty/excel-writer/Spreadsheet/Excel/Writer.php';
            }break;

            case 'tree':{
                require_once 'system/structure_tree.php';
            }break;

            case 'list':{
                require_once 'system/structure_list.php';
            }break;

            case 'xmlparser':{
                require_once 'system/input_xmlparser.php';
            }break;

            case 'swiftmailer':{
                require_once 'system/thirdparty/swiftmailer-master/lib/swift_required.php';
            }break;

            case 'output_quick_csv':{
                require_once 'system/export_quick_csv.php';
            }break;

            case 'input_emails':{
                require_once 'system/input_emails.php';
            }break;

            case 'test':{
                require_once 'system/test.php';
            }break;

            case 'qwfdrl':{
                require_once 'system/qwfdrl.php';
            }break;
        }
    }

    function LoadTheme()
    {
        if (!CQLoader::IsLoading()){
            // makes sure the loading process only happens once
            CQLoader::SignalLoading();

            // load theme details
            if (file_exists($this->ROUTE->qurl_themeroot('theme.txt'))){
                $this->arrTheme = $this->ProcessConfigText(
                    file_get_contents($this->ROUTE->qurl_themeroot('theme.txt')), '=', NULL, true);

                $this->arrLoadedFiles[] = $this->ROUTE->qurl_themeroot('theme.txt');
            }else{
                $this->LOG->Write('WARNING: This theme does not have a root \'theme.txt\''.
                    ' description file: ' . $this->ROUTE->qurl_themeroot());
            }

            ////////////////////////////////////////////////////////////////////
            $strPlatformType = '';
            $strSection = '';

            // load page / details
            switch ($this->CORE->GetBrowserType())
            {
                case BRWSR_TYPE_DESKTOP:
                case BRWSR_TYPE_CLI:
                case BRWSR_TYPE_BOT:{
                    $strPlatformType = 'desktop';
                }break;

                case BRWSR_TYPE_MOBILE:{
                    $strPlatformType = 'mobile';
                }break;
            }

            // if bot and mobile available => mobile
            if (file_exists($this->ROUTE->qurl_pageroot('mobilefront')) &&
                $this->CORE->GetBrowserTypeReal() == BRWSR_TYPE_BOT)
            {
                $strPlatformType = 'mobile';
            }

            switch ($this->ROUTE->GetFlagsSection())
            {
                case 'index': $strSection = 'front'; break;
                case 'admin': $strSection = 'admin'; break;
                case 'nogui': $strSection = 'nogui'; break;
            }

            if ($strSection == 'nogui'){
                $strPlatformType = '';

                $this->arrPageDetails = array();
                $this->arrPageDetails['pagevalue_number_of_targets'] = 1;

                // update ROUTE pagevalue_number_of_targets, earlier
                $this->ROUTE->UpdatePageValue($this->arrPageDetails['pagevalue_number_of_targets']);

                // load a detail txt file, if present, just in case we need to
                // load some other libraries and/or need to output differently
                $strFile = $this->ROUTE->qurl_pageroot('nogui/' . $this->ROUTE->GetPageValue() . '.txt');

                if (file_exists($strFile)){
                    $this->arrPageDetails = $this->ProcessConfigText(
                        file_get_contents($strFile), '=', NULL, true);

                    $this->arrLoadedFiles[] = $strFile;
                }

                if (!isset($this->arrPageDetails['pagevalue_number_of_targets']))
                    $this->arrPageDetails['pagevalue_number_of_targets'] = 1;

                if (!isset($this->arrPageDetails['renderer']))
                    $this->arrPageDetails['renderer'] = 'json';
            }else{
                // TODO: search deeper as well
                $strFile = $this->ROUTE->qurl_pageroot($strPlatformType . $strSection . '/details.txt');

                if (file_exists($strFile)){
                    $this->arrPageDetails = $this->ProcessConfigText(
                        file_get_contents($strFile), '=', NULL, true);

                    $this->arrLoadedFiles[] = $strFile;
                }

                $this->arrPageDetails['pagevalue_number_of_targets'] =
                    (isset($this->arrPageDetails['pagevalue_number_of_targets']) ?
                        (int)$this->arrPageDetails['pagevalue_number_of_targets'] : 0);

                // load page layout
                // TODO: search in database too
                $strFile = $this->ROUTE->qurl_pageroot($strPlatformType . $strSection . '/layout.txt');

                if (file_exists($strFile)){
                    $this->arrLayout = $this->ProcessConfigText(
                        file_get_contents($strFile), ',', NULL, false);

                    $this->arrLayout = $this->arrLayout[0];

                    $this->arrLoadedFiles[] = $strFile;
                }

                // load page modules
                // TODO: search in database too
                $strFile = $this->ROUTE->qurl_pageroot($strPlatformType . $strSection . '/modules.txt');

                if (file_exists($strFile)){
                    $this->arrModules = $this->ProcessConfigText(
                        file_get_contents($strFile), '=', ',', true);

                    $this->arrLoadedFiles[] = $strFile;
                }
            }

            // load extra libraries
            $arrAllLibraries = array();

            if (isset($this->arrPageDetails['libraries']))
                $arrAllLibraries = trimexplode(',', $this->arrPageDetails['libraries']);


            // ... check modules for 'details.txt', just in case they request for a certain library
            if (is_array($this->arrModules)){
                foreach ($this->arrModules as $strLayout => $arrModuleList){
                    foreach ($arrModuleList as $strModule){
                        $strFile = $this->ROUTE->qurl_themeroot('modules/' .
                            $strModule . '/details.txt');

                        if (file_exists($strFile)){
                            $arrThisModule = $this->ProcessConfigText(
                                file_get_contents($strFile), '=', ',', true);

                            $this->arrLoadedFiles[] = $strFile;

                            if (isset($arrThisModule['libraries']))
                                foreach ($arrThisModule['libraries'] as $strLibrary)
                                    if (!in_array($strLibrary, $arrAllLibraries))
                                        $arrAllLibraries[] = $strLibrary;
                        }
                    }
                }
            }

            foreach ($arrAllLibraries as $strLib){
                $this->LoadExtraLibraries($strLib);

                $this->arrLoadedFiles[] = 'lib: ' . $strLib;
            }


            // update ROUTE pagevalue_number_of_targets
            $this->ROUTE->UpdatePageValue($this->arrPageDetails['pagevalue_number_of_targets']);

            // if NOGUI
            if ($strSection == 'nogui'){
                // create module data bucket
                $this->arrModuleData = new CQSingleLoader();

                $this->arrModuleData->Init('-', '-', 0,
                    array($this, 'ManipulateOtherModules'), $this->GLOBAL,
                    $this->DATABASE, $this->ROUTE,
                    $this->CONFIG, $this->CORE, $this->AUTH, $this->LOG);

                // load content language layer
                $strLangFile = $this->ROUTE->qurl_pageroot('nogui/' .
                    $this->ROUTE->GetPageValue() . '-' . $this->ROUTE->GetFlagsLanguage() . '.txt');

                $this->arrModuleData->LoadLanguage($strLangFile);
                $this->arrLoadedFiles[] = $strLangFile;

                // load content data layer
                $strDataFile = $this->ROUTE->qurl_pageroot('nogui/' . $this->ROUTE->GetPageValue() . '.php');

                $this->arrModuleData->LoadData($strDataFile);
                $this->arrLoadedFiles[] = $strDataFile;

                // create render object
                // force unbuffered raw output
                // set json as default data encoding, unless otherwise
                // render & output
                echo $this->arrModuleData->LoadView(NULL, $this->arrPageDetails['renderer']);
            }else{
                // create modules data bucket
                $this->arrModuleData = array();

                foreach ($this->arrModules as $strLayout => $arrModuleList){
                    $this->arrModuleData[$strLayout] = array(
                        'begin' => new CQSingleLoader(),
                        'end' => new CQSingleLoader(),
                        'modules' => array()
                    );

                    foreach ($arrModuleList as $strModule){
                        if (!isset($this->arrModuleData[$strLayout]['modules'][$strModule]))
                            $this->arrModuleData[$strLayout]['modules'][$strModule] = array();

                        $this->arrModuleData[$strLayout]['modules'][$strModule][] = new CQSingleLoader();
                    }
                }

                // load content language layer
                // load content data layer
                foreach ($this->arrModules as $strLayout => $arrModuleList){
                    foreach ($arrModuleList as $strModule){
                        if (strpos($strModule, '::') === 0){
                            // this is page content file
                            for ($i=0; $i < count($this->arrModuleData[$strLayout]['modules'][$strModule]); $i++)
                            {
                                $this->arrModuleData[$strLayout]['modules'][$strModule][$i]->Init(
                                    $strLayout, $strModule, $i,
                                    array($this, 'ManipulateOtherModules'), $this->GLOBAL,
                                    $this->DATABASE, $this->ROUTE,
                                    $this->CONFIG, $this->CORE, $this->AUTH, $this->LOG
                                );

                                // lang
                                $strLangFile = $this->ROUTE->qurl_pageroot('lang/' .
                                    $this->ROUTE->GetFlagsLanguage() . '.txt');

                                $this->arrModuleData[$strLayout]['modules'][$strModule][$i]->LoadLanguage($strLangFile);
                                $this->arrLoadedFiles[] = $strLangFile;

                                // data
                                $strDataFile = $this->ROUTE->qurl_pageroot(
                                    $strPlatformType . $strSection . '/data-' . substr($strModule, 2) . '.php');

                                $this->arrModuleData[$strLayout]['modules'][$strModule][$i]->LoadData($strDataFile);
                                $this->arrLoadedFiles[] = $strDataFile;
                            }
                        }
                    }
                }

                // load modules / language layer
                foreach ($this->arrModules as $strLayout => $arrModuleList){
                    foreach ($arrModuleList as $strModule){
                        if (strpos($strModule, '::') === false){
                            for ($i=0; $i < count($this->arrModuleData[$strLayout]['modules'][$strModule]); $i++)
                            {
                                $this->arrModuleData[$strLayout]['modules'][$strModule][$i]->Init(
                                    $strLayout, $strModule, $i,
                                    array($this, 'ManipulateOtherModules'), $this->GLOBAL,
                                    $this->DATABASE, $this->ROUTE,
                                    $this->CONFIG, $this->CORE, $this->AUTH, $this->LOG
                                );

                                // lang
                                $strLangFile = $this->ROUTE->qurl_themeroot('modules/' .
                                    $strModule . '/' . $this->ROUTE->GetFlagsLanguage() . '.txt');

                                $this->arrModuleData[$strLayout]['modules'][$strModule][$i]->LoadLanguage($strLangFile);
                                $this->arrLoadedFiles[] = $strLangFile;
                            }
                        }
                    }
                }

                // load modules / data layer
                foreach ($this->arrModules as $strLayout => $arrModuleList){
                    foreach ($arrModuleList as $strModule){
                        if (strpos($strModule, '::') === false){
                            for ($i=0; $i < count($this->arrModuleData[$strLayout]['modules'][$strModule]); $i++){
                                // data
                                $strDataFile = $this->ROUTE->qurl_themeroot('modules/' . $strModule . '/data.php');

                                $this->arrModuleData[$strLayout]['modules'][$strModule][$i]->LoadData($strDataFile);
                                $this->arrLoadedFiles[] = $strDataFile;
                            }
                        }
                    }
                }

                // load layout begin / end data layer
                foreach ($this->arrModules as $strLayout => $arrModuleList){
                    $strDataFile = $this->ROUTE->qurl_themeroot('layouts/' . $strLayout . '/data-start.php');
                    $this->arrModuleData[$strLayout]['begin']->LoadData($strDataFile);
                    $this->arrLoadedFiles[] = $strDataFile;

                    $strDataFile = $this->ROUTE->qurl_themeroot('layouts/' . $strLayout . '/data-end.php');
                    $this->arrModuleData[$strLayout]['end']->LoadData($strDataFile);
                    $this->arrLoadedFiles[] = $strDataFile;
                }

                // render everything in natural order
                $strOutputViewLayer = '';

                foreach ($this->arrModules as $strLayout => $arrModuleList){
                    // layout start
                    $strViewFile = $this->ROUTE->qurl_themeroot('layouts/' . $strLayout . '/view-start');
                    $strOutputViewLayer .=
                        $this->arrModuleData[$strLayout]['begin']->LoadView($strViewFile, $this->arrPageDetails['renderer']);
                    $this->arrLoadedFiles[] = $strViewFile;

                    foreach ($arrModuleList as $strModule){
                        // if content
                        if (strpos($strModule, '::') === 0){
                            // this is page content file
                            for ($i=0; $i < count($this->arrModuleData[$strLayout]['modules'][$strModule]); $i++){
                                // view
                                $strViewFile = $this->ROUTE->qurl_pageroot(
                                    $strPlatformType . $strSection . '/view-' . substr($strModule, 2));

                                $strOutputViewLayer .=
                                    $this->arrModuleData[$strLayout]['modules'][$strModule][$i]->LoadView($strViewFile, $this->arrPageDetails['renderer']);
                                $this->arrLoadedFiles[] = $strViewFile;
                            }
                        }else{
                            // module
                            for ($i=0; $i < count($this->arrModuleData[$strLayout]['modules'][$strModule]); $i++){
                                // view
                                $strViewFile = $this->ROUTE->qurl_themeroot('modules/' . $strModule . '/view');

                                $strOutputViewLayer .=
                                    $this->arrModuleData[$strLayout]['modules'][$strModule][$i]->LoadView($strViewFile, $this->arrPageDetails['renderer']);
                                $this->arrLoadedFiles[] = $strViewFile;
                            }
                        }
                    }

                    // layout end
                    $strViewFile = $this->ROUTE->qurl_themeroot('layouts/' . $strLayout . '/view-end');
                    $strOutputViewLayer .=
                        $this->arrModuleData[$strLayout]['end']->LoadView($strViewFile, $this->arrPageDetails['renderer']);
                    $this->arrLoadedFiles[] = $strViewFile;
                }

                // output the rendered content
                if ($this->arrPageDetails['renderer'] == 'buffered-php' ||
                    $this->arrPageDetails['renderer'] == 'qht')
                {
                    // TODO: do some compressing here, if possible
                    echo $strOutputViewLayer;
                }
            }
        }
    }

    function GetLoadedElements()
    {
        return $this->arrLoadedFiles;
    }
}

} // endif defined

////////////////////////////////////////////////////////////////////////////////
// History:
//  -- 21/06/2019 - v1 created;