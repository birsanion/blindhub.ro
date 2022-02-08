<?php

// this definition tells the whole framework that this system file has been included
define('SYSCAP_LIST_STRUCTURE',   true);

class CQListStructure
{
    // variables
    private $strDBTableNoLang;       // database table for no language data
    private $strDBTableLang;         // database table for language data
    
    private $bStoredInDB;           // is data stored in database, or just in memory ?
    
    private $arrNoLangDetails;      // details that don't depend on language;
                                    // if stored in database, these are column names
    private $arrLangDetails;        // details that do depend on language;
                                    // if stored in database, these are column names
    
    private $arrLanguages;          // list of languages used; must be declared; can be NULL
    
    private $bIsInit;               // whether or not this object has been initialized
    private $DATABASE;              // database object
    
    // constructor
    function __construct()
    {
        $this->strDBTableNoLang = '';
        $this->strDBTableLang = '';
        
        $this->bStoredInDB = false;
        
        $this->arrNoLangDetails = array();
        $this->arrLangDetails = array();
        $this->arrLanguages = array();
        
        $this->bIsInit = false;
        $this->DATABASE = NULL;
    }
    
    // functions
    function Init($arrSettings, $kDatabase = NULL)
    {
        if (!$this->bIsInit){
            $this->strDBTableNoLang= SYSCFG_DB_PREFIX.'l_'.$arrSettings['rootcode'].'_nolang';
            $this->strDBTableLang=   SYSCFG_DB_PREFIX.'l_'.$arrSettings['rootcode'].'_lang';
            
            $this->bStoredInDB=     $arrSettings['dbstored'];
            
            $this->arrNoLangDetails=$arrSettings['details_nolang'];
            $this->arrLangDetails=  $arrSettings['details_lang'];
            $this->arrLanguages=    $arrSettings['languages'];
            
            $this->bIsInit=true;
            $this->DATABASE = $kDatabase;
            
            return true;
        }else return false;
    }
    
    function AddElement($arrElem, &$nNewId)
    {
        /*
         * $arrElem should be like
         *      ['nolang']
         *          ['detail_1'] ...
         * 
         *      ['lang']
         *          [_LANG_CODE_]
         *              ['details_1'] ...
         */
        
        if ($this->bStoredInDB && $this->DATABASE !== NULL){
            $nNextId=$this->DATABASE->GetNextAutoincrement($this->strDBTableNoLang);
            
            $nNewId=$nNextId;
            
            $arrColumnList = array();
            foreach ($this->arrNoLangDetails as $pcDetail) $arrColumnList[]=$pcDetail;
            
            $arrData=array();
            foreach ($this->arrNoLangDetails as $pcDetail) $arrData[$pcDetail]=$arrElem['nolang'][$pcDetail];
            
            if (!$this->DATABASE->RunQuickInsert($this->strDBTableNoLang, $arrColumnList, array($arrData)))
                return false;
            
            //// if we have language-dependant informations ...
            if ($this->arrLanguages!==NULL){
                $arrColumnList=array('nolangidx', 'lang');
                foreach ($this->arrLangDetails as $pcDetail) $arrColumnList[]=$pcDetail;
                
                $arrData=array();
                
                foreach ($this->arrLanguages as $pcLanguage){
                    $arrDataSingle=array();
                    $arrDataSingle['nolangidx']=$nNextId;
                    $arrDataSingle['lang']=$pcLanguage;
                    
                    foreach ($this->arrLangDetails as $pcDetail) $arrDataSingle[$pcDetail]=$arrElem['lang'][$pcLanguage][$pcDetail];
                    
                    $arrData[]=$arrDataSingle;
                }
                
                if (!$this->DATABASE->RunQuickInsert($this->strDBTableLang, $arrColumnList, $arrData)){
                    $this->DATABASE->RunQuickDelete($this->strDBTableNoLang, array('idx', '=', $nNextId));
                    
                    return false;
                }
            }
            
            return true;
        }

        return false;
    }
    
    function DeleteElement($nId)
    {
        if ($this->bStoredInDB && $this->DATABASE !== NULL){
            if ($this->DATABASE->RunQuickDelete($this->strDBTableNoLang, array('idx', '=', $nId)) &&
                (($this->arrLanguages !== NULL &&
                    $this->DATABASE->RunQuickDelete($this->strDBTableLang, array('nolangidx', '=', $nId))) ||
                    $this->arrLanguages===NULL)
                )
                return true;
            else return false;
        }
        
        return false;
    }
    
    function UpdateElement($arrElem)
    {
        /*
         * $arrElem should be like
         *      ['nolang']
         *          ['idx'] (int)
         *          ['detail_1'] ...
         * 
         *      ['lang']
         *          [_LANG_CODE_]
         *              ['details_1'] ...
         */
        
        if ($this->bStoredInDB && $this->DATABASE !== NULL){
            $arrColumnList = array();
            
            foreach ($this->arrNoLangDetails as $pcDetail)
                if (isset($arrElem['nolang'][$pcDetail]))
                    $arrColumnList[]=$pcDetail;
            
            $arrData=array();
            
            foreach ($this->arrNoLangDetails as $pcDetail)
                if (isset($arrElem['nolang'][$pcDetail]))
                    $arrData[$pcDetail]=$arrElem['nolang'][$pcDetail];
            
            if (!$this->DATABASE->RunQuickUpdate($this->strDBTableNoLang, $arrColumnList, $arrData, array('idx', '=', $arrElem['nolang']['idx'])))
                return false;
            
            //// if we have language-dependant informations ...
            if ($this->arrLanguages!==NULL){
                foreach ($this->arrLanguages as $pcLanguage){
                    $arrColumnList=array();
                    
                    foreach ($this->arrLangDetails as $pcDetail)
                        if (isset($arrElem['lang'][$pcLanguage][$pcDetail]))
                            $arrColumnList[]=$pcDetail;
                    
                    $arrDataSingle=array();
                    $arrDataSingle['nolangidx']=$nNextId;
                    $arrDataSingle['lang']=$pcLanguage;
                    
                    foreach ($this->arrLangDetails as $pcDetail)
                        if (isset($arrElem['lang'][$pcLanguage][$pcDetail]))
                            $arrDataSingle[$pcDetail]=$arrElem['lang'][$pcLanguage][$pcDetail];
                    
                    $this->DATABASE->RunQuickUpdate($this->strDBTableLang, $arrColumnList, $arrDataSingle,
                        array(array('nolangidx', '=', $arrElem['nolang']['idx'], 'AND'), array('lang', '=', $pcLanguage)));
                }
            }
            
            return true;
        }

        return false;
    }

    function UpdateInBulk($arrConditions, $mxColumnListNoLang, $arrDataNoLang, $mxColumnListLang = NULL, $arrDataLang = NULL)
    {
        /*
         * $arrConditions = array(
         *      'nolang' => ...,
         *      'lang' => ...
         * )
         */
        
        if ($this->bStoredInDB && $this->DATABASE !== NULL){
            $arrRawNoLang = NULL;
            
            if ($mxColumnListLang !== NULL && $arrDataLang !== NULL)
                $arrRawNoLang = $this->DATABASE->RunQuickSelect('idx', $this->strDBTableNoLang, $arrConditions['nolang']);
            
            // a few sanity checks
            if (isset($mxColumnListNoLang['idx'])) unset($mxColumnListNoLang['idx']);
            if (isset($mxColumnListLang['idx'])) unset($mxColumnListLang['idx']);
            if (isset($mxColumnListLang['nolangidx'])) unset($mxColumnListLang['nolangidx']);
            
            $this->DATABASE->RunQuickUpdate($this->strDBTableNoLang, $mxColumnListNoLang, $arrDataNoLang, $arrConditions['nolang']);
            
            if ($mxColumnListLang !== NULL && $arrDataLang !== NULL){
                if (is_array($arrRawNoLang) && count($arrRawNoLang) > 0){
                    foreach ($arrRawNoLang as $arrRawNoLangItem){
                        $this->DATABASE->RunQuickUpdate($this->strDBTableLang,
                            $mxColumnListLang, $arrDataLang, $arrConditions['lang']);
                    }
                }
            }
        }
    }

    function CountElements($mxFilterNoLang=NULL, $mxFilterLang=NULL)
    {
        if ($this->bStoredInDB && $this->DATABASE !== NULL){
            $nCount=0;
            
            // get raw data
            $arrRawNoLang = $this->DATABASE->RunQuickSelect('idx', $this->strDBTableNoLang, $mxFilterNoLang);
            
            $arrRawLang = array();
            
            if ($this->arrLanguages!==NULL)
                $arrRawLang=$this->DATABASE->RunQuickSelect('nolangidx', $this->strDBTableLang, $mxFilterLang);
            
            // calculate
            if ($mxFilterLang===NULL){
                // we have only nolang filters
                $nCount = (is_array($arrRawNoLang) ? count($arrRawNoLang) : 0);
            }else{
                if (is_array($arrRawLang)){
                    foreach ($arrRawNoLang as $arrItemNoLang){
                        foreach ($arrRawLang as $arrItemLang){
                            if ($arrItemNoLang['idx']==$arrItemLang['nolangidx'])
                                $nCount++;
                        }
                    }
                }
            }
            
            return $nCount;
        }

        return 0;
    }
    
    function GetElementList($mxFilterNoLang=NULL, $mxFilterLang=NULL, $arrOrderByNoLang=NULL, $arrOrderByLang=NULL)
    {
        if ($this->bStoredInDB && $this->DATABASE !== NULL){
            $arrRet = array();
            
            // get raw data
            $arrRawNoLang = $this->DATABASE->RunQuickSelect('*', $this->strDBTableNoLang, $mxFilterNoLang, $arrOrderByNoLang);
            
            $arrRawLang = array();
            
            if ($this->arrLanguages !== NULL)
                $arrRawLang = $this->DATABASE->RunQuickSelect('*', $this->strDBTableLang, $mxFilterLang, $arrOrderByLang);
            
            // populate return array
            foreach ($arrRawNoLang as $arrItemNoLang){
                $arrLangData=array();
                
                if (is_array($arrRawLang)){
                    foreach ($arrRawLang as $arrItemLang){
                        if ($arrItemNoLang['idx']==$arrItemLang['nolangidx']){
                            // these 2 belong together
                            $arrLangData[$arrItemLang['lang']]=array();
                            
                            foreach ($this->arrLangDetails as $pcDetail)
                                $arrLangData[$arrItemLang['lang']][$pcDetail]=$arrItemLang[$pcDetail];
                        }
                    }
                }
                
                // if we can add this as valid item
                if (!empty($arrLangData) || (empty($arrLangData) && $mxFilterLang===NULL)){
                    // fill missing data
                    if ($this->arrLanguages!==NULL){
                        foreach ($this->arrLanguages as $pcLanguage){
                            if (!isset($arrLangData[$pcLanguage])) $arrLangData[$pcLanguage]=array();
                            
                            foreach ($this->arrLangDetails as $pcLangDetail)
                                if (!isset($arrLangData[$pcLanguage][$pcLangDetail]))
                                    $arrLangData[$pcLanguage][$pcLangDetail]=NULL;
                        }
                    }
                    
                    // add item
                    $arrCurrentItem=array('nolang' => array());
                    $arrCurrentItem['nolang']['idx']=$arrItemNoLang['idx'];
                    
                    foreach ($this->arrNoLangDetails as $pcDetail) $arrCurrentItem['nolang'][$pcDetail]=$arrItemNoLang[$pcDetail];
                    
                    if ($this->arrLanguages!==NULL) $arrCurrentItem['lang']=$arrLangData;
                    
                    $arrRet[]=$arrCurrentItem;
                }
            }

            return $arrRet;
        }

        return NULL;
    }
}
