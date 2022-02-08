<?php
////////////////////////////////////////////////////////////////////////////////
// Part of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.
// -- For history see the end of this file.

////////////////////////////////////////////////////////////////////////////////
// This file contains utilities to read and write website and user configuration

if (!defined('SYSCAP_SITECONFIG')){
// this tells the whole framework that this system file has been included
define('SYSCAP_SITECONFIG',	true);

class CQSiteConfig
{
    private $arrData;
    private $DATABASE;
    
    function __construct()
    {
        $this->arrData = array();
        $this->DATABASE = NULL;
        
        // set default values for some mandatory configurations
        $this->arrData['default-language-code']=array(
            'value' => 'en',
            'modified' => false
        );
        
        $this->arrData['theme']=array(
            'value' => 'thunderstorm',
            'modified' => false
        );
        
        $this->arrData['force-desktop']=array(
            'value' => true,
            'modified' => false
        );
    }
    
    function Init(&$kDatabase)
    {
        $this->DATABASE = $kDatabase;
        
        if ($this->DATABASE){
            $arrCfg = $this->DATABASE->RunQuickSelect(array('shortname','type','value'),
                SYSCFG_DB_PREFIX . 'config');
            $nConfigs = (is_array($arrCfg) ? count($arrCfg) : 0);
            
            for ($i=0; $i<$nConfigs; $i++){
                switch ($arrCfg[$i]['type'])
                {
                    case 'int':{
                        $this->arrData[$arrCfg[$i]['shortname']]=array(
                            'value' => (int)$arrCfg[$i]['value'],
                            'modified' => false
                        );
                    }break;
                    
                    case 'bool':{
                        $this->arrData[$arrCfg[$i]['shortname']]=array(
                            'value' => ($arrCfg[$i]['value']=='true') ? true : false,
                            'modified' => false
                        );
                    }break;
                    
                    case 'float':{
                        $this->arrData[$arrCfg[$i]['shortname']]=array(
                            'value' => (float)$arrCfg[$i]['value'],
                            'modified' => false
                        );
                    }break;
                    
                    default:{
                        $this->arrData[$arrCfg[$i]['shortname']]=array(
                            'value' => $arrCfg[$i]['value'],
                            'modified' => false
                        );
                    }
                }
            }
        }
    }
    
    function Get($strStr, $strDefault='')
    {
        if (isset($this->arrData[$strStr])) return $this->arrData[$strStr]['value'];
        else return $strDefault;
    }
    
    function Set($strStr, $mxValue)
    {
        $this->arrData[$strStr]=array(
            'value' => $mxValue,
            'modified' => true
        );
    }
    
    function Remove($strStr)
    {
        if (isset($this->arrData[$strStr])) unset($this->arrData[$strStr]);
        
        if ($this->DATABASE)
            return $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX.'config',
                array('shortname', '=', $strStr));
        
        return true;
    }
    
    function EnumerateKeys()
    {
        $arrKeys = array();
        foreach ($this->arrData as $strKey => $ni)
            $arrKeys[] = $strKey;
        
        return $arrKeys;
    }
    
    function CommitChanges()
    {
        if ($this->DATABASE){
            $bRet = true;
            
            foreach ($this->arrData as $strKey => $arrElem){
                if ($arrElem['modified']){
                    $nCount = $this->DATABASE->RunQuickCount(
                        SYSCFG_DB_PREFIX . 'config', array('shortname', '=', $strKey));
                    
                    if ($nCount<=0)
                        $bRet = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX.'config',
                            'shortname, type, value', array(array(
                                'shortname' => $strKey,
                                'type' => (is_int($arrElem['value']) ? 'int' : (is_bool($arrElem['value']) ? 'bool' :
                                    (is_float($arrElem['value']) ? 'float' : 'string'))),
                                'value' => $arrElem['value']
                            )));
                    else $bRet = $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX.'config',
                            'type, value',
                            array(
                                'type' => (is_int($arrElem['value']) ? 'int' : (is_bool($arrElem['value']) ? 'bool' :
                                    (is_float($arrElem['value']) ? 'float' : 'string'))),
                                'value' => $arrElem['value']
                            ),
                            array('shortname', '=', $strKey));
                    
                    if ($bRet) $this->arrData[$strKey]['modified']=false;
                    else return false;
                }
            }

            return true;
        }else return false;
    }
}

} // endif defined

////////////////////////////////////////////////////////////////////////////////
// History:
//  -- 21/06/2019 - v1 created;