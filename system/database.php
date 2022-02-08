<?php
////////////////////////////////////////////////////////////////////////////////
// Part of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.
// -- For history see the end of this file.

////////////////////////////////////////////////////////////////////////////////
// This file contains abstraction classes for working with databases

if (!defined('SYSCAP_DATABASE')){
// this tells the whole framework that this system file has been included
define('SYSCAP_DATABASE',	true);

////////////////////////////////////////////////////////////////////////////////
// CLASSES

class CQDatabaseMySQL
{
	// variables
	private $pcAddress;
	private $pcDBName;
	private $pcUsername;
	private $pcPassword;
	private $rLink;		// this is the resource of the connection given by '...connect()' function
	
	// constructors
	function __construct()
	{
		$this->pcAddress='';
		$this->pcDBName='';
		$this->pcUsername='';
		$this->pcPassword='';
	}
	
	// functions
	function Init($pcAddr,$pcName,$pcUsr,$pcPass)
	{
		$this->pcAddress=$pcAddr;
		$this->pcDBName=$pcName;
		$this->pcUsername=$pcUsr;
		$this->pcPassword=$pcPass;
		$this->rLink=NULL;
	}
	
	function Open()
	{
		$this->rLink=mysql_connect($this->pcAddress,$this->pcUsername,$this->pcPassword);
        
        if ($this->rLink===FALSE) return false;
        
		if (!mysql_select_db($this->pcDBName,$this->rLink)) return false;
        
        mysql_set_charset('utf8',$this->rLink);
		
		$this->RunQuery('SET NAMES utf8;');
        
        //mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', 
            // character_set_connection = 'utf8', character_set_database = 'utf8', 
            // character_set_server = 'utf8'", $this->rLink);
            
        return true;
	}
	
	function Close()
	{
		mysql_close($this->rLink);
	}
	
	function RunQuery($pcQuery)
	{
		$rResult=mysql_query($pcQuery,$this->rLink);
		if ($rResult===TRUE || $rResult===FALSE) return $rResult;
		
		$nResults=mysql_num_rows($rResult);
		$arrReturn=array();
		$j=0;
		
		for ($i=0; $i<$nResults; $i++){
			$arrReturn[$j]=mysql_fetch_assoc($rResult);
			$j++;
		}
		
		mysql_free_result($rResult);
		
		return $arrReturn;
	}
	
	function CleanString($pcStr)
	{
		return mysql_real_escape_string($pcStr,$this->rLink);
	}
	
	function ChangeDatabase($pcNewDB)
	{
		$this->pcDBName=$pcNewDB;
		mysql_select_db($pcNewDB,$this->rLink);
	}
    
    function GetError()
    {
        return mysql_error($this->rLink);
    }
}

class CQDatabaseMySQLi
{
    // variables
    private $pcAddress;
    private $pcDBName;
    private $pcUsername;
    private $pcPassword;
    private $rLink;     // this is the resource of the connection given by '...connect()' function
    
    // constructors
    function __construct()
    {
        $this->pcAddress='';
        $this->pcDBName='';
        $this->pcUsername='';
        $this->pcPassword='';
    }
    
    // functions
    function Init($pcAddr,$pcName,$pcUsr,$pcPass)
    {
        $this->pcAddress=$pcAddr;
        $this->pcDBName=$pcName;
        $this->pcUsername=$pcUsr;
        $this->pcPassword=$pcPass;
        $this->rLink=NULL;
    }
    
    function Open()
    {
        $this->rLink=mysqli_connect($this->pcAddress, $this->pcUsername, $this->pcPassword, $this->pcDBName);
        
		if ($this->rLink===FALSE) return false;
        if (mysqli_connect_error()){
            if (defined('SYSCAP_LOG') && SYSCAP_LOG){
                $LOG = CQSyslog::GetInstance();
                $LOG->Write('ERROR: cannot connect to MySQL database on server `'.$this->pcAddress.'` through mysqli extension ('.
                    mysqli_connect_errno().'='.mysqli_connect_error().') !');
                
                return false;
            }
			
			return false;
        }
        
        mysqli_set_charset($this->rLink, 'utf8');
		
		$this->RunQuery('SET NAMES utf8;');
		
        //mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', 
            // character_set_connection = 'utf8', character_set_database = 'utf8', 
            // character_set_server = 'utf8'", $this->rLink);
        return true;
    }
    
    function Close()
    {
        if (is_object($this->rLink)) $this->rLink->close();
    }
    
    function RunQuery($pcQuery)
    {
        $rResult=mysqli_query($this->rLink, $pcQuery);
        if ($rResult===TRUE || $rResult===FALSE) return $rResult;
        
        $arrReturn=array();
        $j=0;
        
        for ($i=0; $i<$rResult->num_rows; $i++){
            $arrReturn[$j]=mysqli_fetch_assoc($rResult);
            $j++;
        }
        
        if (is_object($rResult)) $rResult->close();
        
        return $arrReturn;
    }
    
    function RunMultiQuery($pcQuery)
    {
        $mxQueryRes=mysqli_multi_query($this->rLink, $pcQuery);
        
        if ($mxQueryRes===false) return $mxQueryRes;
        
        $arrReturn=array();
        
        do {
            if ($rResult = mysqli_store_result($this->rLink)){
                while ($arrRow=mysqli_fetch_assoc($rResult)) $arrReturn[]=$arrRow;
                
                mysqli_free_result($rResult);
            }
        }
        while (mysqli_next_result($this->rLink));
        
        return $arrReturn;
    }
    
    function CleanString($pcStr)
    {
        return mysqli_real_escape_string($this->rLink, $pcStr);
    }
    
    function ChangeDatabase($pcNewDB)
    {
        $this->pcDBName=$pcNewDB;
        mysqli_select_db($this->rLink, $pcNewDB);
    }
    
    function GetError()
    {
        return mysqli_error($this->rLink);
    }
}

class CQDatabaseSQLite
{
    // variables
    private $pcAddress;     // actually the filename
    private $pcDBName;      // not used
    private $pcUsername;    // not used
    private $pcPassword;    // not used
    private $rLink;     // this is the resource of the connection given by '...connect()' function
    private $strError;
    
    // constructors
    function __construct()
    {
        $this->pcAddress='';
        $this->pcDBName='';
        $this->pcUsername='';
        $this->pcPassword='';
        $this->strError='';
    }
    
    // functions
    function Init($pcAddr)
    {
        $this->pcAddress=$pcAddr;
        $this->rLink=NULL;
    }
    
    function Open()
    {
        $this->strError='';
        
        $this->rLink=sqlite_open($this->pcAddress, 0666, $this->strError);
        
        if ($this->rLink===FALSE){
            if (defined('SYSCAP_LOG') && SYSCAP_LOG){
                $LOG = CQSyslog::GetInstance();
                $LOG->Write('ERROR: cannot connect to SQLite database `'.$this->pcAddress.'` ('.$this->strError.') !');
                
                return false;
            }
            
            return false;
        }
        
        return true;
    }
    
    function Close()
    {
        sqlite_close($this->rLink);
    }
    
    function RunQuery($pcQuery)
    {
        $this->strError='';
        
        $rResult=sqlite_unbuffered_query($this->rLink, $pcQuery, SQLITE_BOTH, $this->strError);
        if ($rResult===FALSE) return false;
        
        $arrReturn=array();
        
        $arrRow=sqlite_fetch_array($rResult, SQLITE_ASSOC);
        
        while ($arrRow!==FALSE){
            $arrReturn[]=$arrRow;
            
            // next row
            $arrRow=sqlite_fetch_array($rResult, SQLITE_ASSOC);
        }
        
        return $arrReturn;
    }
    
    function CleanString($pcStr)
    {
        return sqlite_escape_string($pcStr);
    }
    
    function ChangeDatabase($pcNewDB)
    {
        // does nothing here
    }
    
    function GetError()
    {
        return $this->strError;
    }
}

class CQDatabaseSQLite3
{
    // variables
    private $pcAddress;     // actually the filename
    private $pcDBName;      // not used
    private $pcUsername;    // not used
    private $pcPassword;    // not used
    private $rLink;     // this is the resource of the connection given by '...connect()' function
    private $strError;
    
    // constructors
    function __construct()
    {
        $this->pcAddress='';
        $this->pcDBName='';
        $this->pcUsername='';
        $this->pcPassword='';
        $this->strError='';
    }
    
    // functions
    function Init($pcAddr)
    {
        $this->pcAddress=$pcAddr;
        $this->rLink=NULL;
    }
    
    function Open()
    {
        $this->strError='';
        
        if ($this->rLink){
            if (!$this->rLink->close()){
                $this->strError='ERROR: Cannot close previous SQLite connection !';
                
                if (defined('SYSCAP_LOG') && SYSCAP_LOG){
                    $LOG = CQSyslog::GetInstance();
                    $LOG->Write('ERROR: Cannot close previous SQLite connection !');
                }
                
                return false;
            }
        }
        
        $this->rLink=NULL;
        
        $this->rLink=new SQLite3($this->pcAddress);
        
        if (!$this->rLink){
            $this->strError='ERROR: cannot connect to SQLite database `'.$this->pcAddress.'` !';
            
            if (defined('SYSCAP_LOG') && SYSCAP_LOG){
                $LOG = CQSyslog::GetInstance();
                $LOG->Write('ERROR: cannot connect to SQLite database `'.$this->pcAddress.'` ('.$this->strError.') !');
                
                return false;
            }
            
            return false;
        }
        
        return true;
    }
    
    function Close()
    {
        return $this->rLink->close();
    }
    
    function RunQuery($pcQuery)
    {
        $this->strError='';
        
        $rResult=$this->rLink->query($pcQuery);
        
        $pcFirstKeyword=trim(strtolower(substr($pcQuery, 0, 12)));
        
        if (!(strpos($pcFirstKeyword, 'select')===0)){
            // we have a query that returns true / false
            
            if ($rResult) return true;
            else return false;
        }
        
        $arrReturn=array();
        
        while ($arrRow = $rResult->fetchArray(SQLITE3_ASSOC))
            $arrReturn[]=$arrRow;
        
        $rResult->finalize();
        
        return $arrReturn;
    }
    
    function CleanString($pcStr)
    {
        return $this->rLink->escapeString($pcStr);
    }
    
    function ChangeDatabase($pcNewDB)
    {
        // does nothing here
    }
    
    function GetError()
    {
        return $this->rLink->lastErrorMsg();
    }
}

class CQDatabaseWrapper
{
	// variables
	var $pcType;
	var $pcAddress;
	var $pcDBName;
	var $pcUsername;
	var $pcPassword;
	var $kDb;	// this is the database object
	
	var $pcLastQ;  // last query
	
	// constructors
	function __construct()
	{
		$this->pcType='';
		$this->pcAddress='';
		$this->pcDBName='';
		$this->pcUsername='';
		$this->pcPassword='';
		$this->kDb=NULL;
	}
    
    function __destruct()
    {
        if ($this->kDb){
            $this->kDb->Close();
            $this->pcType='';
            $this->pcAddress='';
            $this->pcDBName='';
            $this->pcUsername='';
            $this->pcPassword='';
            $this->kDb=NULL;
        }
    }
	
	// functions
	function Init($pcTypeofDB, $pcAddr, $pcName, $pcUsr, $pcPass)
	{
		$this->pcType=$pcTypeofDB;
		$this->pcAddress=$pcAddr;
		$this->pcDBName=$pcName;
		$this->pcUsername=$pcUsr;
		$this->pcPassword=$pcPass;
		
		switch ($pcTypeofDB)
		{
			case 'mysql':{
				$this->kDb=new CQDatabaseMySQL;
				if ($this->kDb){
					$this->kDb->Init($pcAddr,$pcName,$pcUsr,$pcPass);
				}
			}break;
            
            case 'mysqli':{
                $this->kDb=new CQDatabaseMySQLi;
                if ($this->kDb){
                    $this->kDb->Init($pcAddr,$pcName,$pcUsr,$pcPass);
                }
            }break;
            
            case 'sqlite':{
                $this->kDb=new CQDatabaseSQLite;
                if ($this->kDb){
                    $this->kDb->Init($pcAddr);
                }
            }break;
            
            case 'sqlite3':{
                $this->kDb=new CQDatabaseSQLite3;
                if ($this->kDb){
                    $this->kDb->Init($pcAddr);
                }
            }break;
		}
	}
	
	function Open()
	{
		return $this->kDb->Open();
	}
	
	function Close()
	{
		// calling this function will force you to call
		// Init() again, if you need to use database again
		
		$this->kDb->Close();
		$this->pcType='';
		$this->pcAddress='';
		$this->pcDBName='';
		$this->pcUsername='';
		$this->pcPassword='';
		$this->kDb=NULL;
	}
	
	function RunQuery($pcQuery)
	{
	    if (defined('QUICKWEBFRAME') && QUICKWEBFRAME){
            if (defined('SYSCAP_LOG')){
                $LOG = CQSyslog::GetInstance();
                if (defined('DEBUGMODE') && DEBUGMODE) $LOG->Write($pcQuery);
            }
        }
        
		return $this->kDb->RunQuery($pcQuery);
	}
    
    function RunMultiQuery($pcQuery)
    {
        if (defined('QUICKWEBFRAME') && QUICKWEBFRAME){
            if (defined('SYSCAP_LOG')){
                $LOG = CQSyslog::GetInstance();
                if (defined('DEBUGMODE') && DEBUGMODE) $LOG->Write($pcQuery);
            }
        }
        
        return $this->kDb->RunMultiQuery($pcQuery);
    }
	
	/**
     * RunQuickDelete - make DELETE FROM queries into database
     * 
     * pcFrom      = 'table_name'
     * arrWhere    = array(array('col_1','operator_1','value_1','cond_op_1'), ...) OR array('column','operator','value')
     * 
     * @return {Boolean} - success / fail
     */
	function RunQuickDelete($pcFrom, $arrWhere)
    {
        // DELETE FROM `table` WHERE `conditions`
        
        switch ($this->pcType)
        {
            case 'mysql': case 'mysqli':{
                // build 'where' clause
                $pcFinalWhere='';
                if ($arrWhere!=NULL){
                    $nCount=count($arrWhere);
                    for ($i=0; $i<$nCount; $i++){
                        if (is_array($arrWhere[$i])){
                            // add column and operator
                            $pcFinalWhere.='`'.$arrWhere[$i][0].'`'.$arrWhere[$i][1];
                            // add value
                            if (strtoupper($arrWhere[$i][1])==' LIKE ')
                                $pcFinalWhere.="'".$arrWhere[$i][2]."'";
                            else $pcFinalWhere.=is_string($arrWhere[$i][2]) ?
                                "'".$this->kDb->CleanString($arrWhere[$i][2])."'" : $arrWhere[$i][2];
                            // add condition
                            if ($i<$nCount-1) $pcFinalWhere.=' '.$arrWhere[$i][3].' ';
                        }else{
                            // add column and operator
                            $pcFinalWhere.='`'.$arrWhere[$i].'`'.$arrWhere[$i+1];
                            // add value
                            if (strtoupper($arrWhere[$i+1])==' LIKE ')
                                $pcFinalWhere.="'".$arrWhere[$i+2]."'";
                            else $pcFinalWhere.=is_string($arrWhere[$i+2]) ?
                                "'".$this->kDb->CleanString($arrWhere[$i+2])."'" : $arrWhere[$i+2];
                            // exit loop
                            $i=$nCount+1;
                        }
                    }
                }
                
                // build final query string
                if (strlen($pcFinalWhere)>0) $pcFinalWhere="WHERE $pcFinalWhere";
                $pcFinalQuery="DELETE FROM `$pcFrom` $pcFinalWhere";
				$this->pcLastQ = $pcFinalQuery;
                
                return $this->RunQuery($pcFinalQuery);
            }break;
            
            case 'sqlite': case 'sqlite3':{
                // build 'where' clause
                $pcFinalWhere='';
                if ($arrWhere!=NULL){
                    $nCount=count($arrWhere);
                    for ($i=0; $i<$nCount; $i++){
                        if (is_array($arrWhere[$i])){
                            // add column and operator
                            $pcFinalWhere.='`'.$arrWhere[$i][0].'`'.$arrWhere[$i][1];
                            // add value
                            if (strtoupper($arrWhere[$i][1])==' LIKE ')
                                $pcFinalWhere.="'".$arrWhere[$i][2]."'";
                            else $pcFinalWhere.=is_string($arrWhere[$i][2]) ?
                                "'".$this->kDb->CleanString($arrWhere[$i][2])."'" : $arrWhere[$i][2];
                            // add condition
                            if ($i<$nCount-1) $pcFinalWhere.=' '.$arrWhere[$i][3].' ';
                        }else{
                            // add column and operator
                            $pcFinalWhere.='`'.$arrWhere[$i].'`'.$arrWhere[$i+1];
                            // add value
                            if (strtoupper($arrWhere[$i+1])==' LIKE ')
                                $pcFinalWhere.="'".$arrWhere[$i+2]."'";
                            else $pcFinalWhere.=is_string($arrWhere[$i+2]) ?
                                "'".$this->kDb->CleanString($arrWhere[$i+2])."'" : $arrWhere[$i+2];
                            // exit loop
                            $i=$nCount+1;
                        }
                    }
                }
                
                // build final query string
                if (strlen($pcFinalWhere)>0) $pcFinalWhere="WHERE $pcFinalWhere";
                $pcFinalQuery="DELETE FROM `$pcFrom` $pcFinalWhere";
				$this->pcLastQ = $pcFinalQuery;
                
                return $this->RunQuery($pcFinalQuery);
            }break;
        }

        return NULL;
    }

    /**
     * RunQuickUpdate - make UPDATE queries into database
     * 
     * pcInto           = string, the table
     * mxColumnList     = string ('col1, col2, col3, ...') or array('col1', 'col2', ...)
     * arrData          = array ('col1'=>'value', 'col2'=>'value', ...)
     * arrWhere = array(array('col_1','operator_1','value_1','cond_op_1'), ...) OR array('column','operator','value')
     * 
     * @return {Boolean} - success / fail
     */
    function RunQuickUpdate($pcInto, $mxColumnList, $arrData, $arrWhere)
    {
        // UPDATE `table` SET `list`='value' WHERE conditions
        
        switch ($this->pcType)
        {
            case 'mysql': case 'mysqli':{
                // build column list
                $arrColList=array(); // needed later
                
                if ($mxColumnList!=NULL){
                    if (is_string($mxColumnList)){
                        $arrColList=explode(',',$mxColumnList);
                        foreach ($arrColList as $kKey => $pcElement) $arrColList[$kKey]=trim($pcElement);
                    }
                    if (is_array($mxColumnList)){
                        $arrColList=$mxColumnList;
                    }
                }else return FALSE;
                
                // build data
                $pcFinalData='';
                foreach ($arrColList as $pcColumn){
                    if (isset($arrData[$pcColumn])){
                        if (is_string($arrData[$pcColumn]))
                            $pcFinalData.="`$pcColumn`='".$this->CleanString($arrData[$pcColumn])."', ";
                        else $pcFinalData.="`$pcColumn`=".$arrData[$pcColumn].", ";
                    }else $pcFinalData.="`$pcColumn`='', ";
                }
                
                if (strlen($pcFinalData)==0) return FALSE;
                $pcFinalData[strlen($pcFinalData)-2]=' ';
                
                // build 'where' clause
                $pcFinalWhere='';
                if ($arrWhere!=NULL){
                    $nCount=count($arrWhere);
                    for ($i=0; $i<$nCount; $i++){
                        if (is_array($arrWhere[$i])){
                            // add column and operator
                            $pcFinalWhere.='`'.$arrWhere[$i][0].'`'.$arrWhere[$i][1];
                            // add value
                            if (strtoupper($arrWhere[$i][1])==' LIKE ')
                                $pcFinalWhere.="'".$arrWhere[$i][2]."'";
                            else $pcFinalWhere.=is_string($arrWhere[$i][2]) ?
                                "'".$this->kDb->CleanString($arrWhere[$i][2])."'" : $arrWhere[$i][2];
                            // add condition
                            if ($i<$nCount-1) $pcFinalWhere.=' '.$arrWhere[$i][3].' ';
                        }else{
                            // add column and operator
                            $pcFinalWhere.='`'.$arrWhere[$i].'`'.$arrWhere[$i+1];
                            // add value
                            if (strtoupper($arrWhere[$i+1])==' LIKE ')
                                $pcFinalWhere.="'".$arrWhere[$i+2]."'";
                            else $pcFinalWhere.=is_string($arrWhere[$i+2]) ?
                                "'".$this->kDb->CleanString($arrWhere[$i+2])."'" : $arrWhere[$i+2];
                            // exit loop
                            $i=$nCount+1;
                        }
                    }
                }

                if (strlen($pcFinalWhere)>0) $pcFinalWhere="WHERE $pcFinalWhere";
                
                // build final query string
                $pcFinalQuery="UPDATE `$pcInto` SET $pcFinalData $pcFinalWhere";
				$this->pcLastQ = $pcFinalQuery;
                
                return $this->RunQuery($pcFinalQuery);
            }break;
            
            case 'sqlite': case 'sqlite3':{
                // build column list
                $arrColList=array(); // needed later
                
                if ($mxColumnList!=NULL){
                    if (is_string($mxColumnList)){
                        $arrColList=explode(',',$mxColumnList);
                        foreach ($arrColList as $kKey => $pcElement) $arrColList[$kKey]=trim($pcElement);
                    }
                    if (is_array($mxColumnList)){
                        $arrColList=$mxColumnList;
                    }
                }else return FALSE;
                
                // build data
                $pcFinalData='';
                foreach ($arrColList as $pcColumn){
                    if (isset($arrData[$pcColumn])){
                        if (is_string($arrData[$pcColumn]))
                            $pcFinalData.="`$pcColumn`='".$this->CleanString($arrData[$pcColumn])."', ";
                        else $pcFinalData.="`$pcColumn`=".$arrData[$pcColumn].", ";
                    }else $pcFinalData.="`$pcColumn`='', ";
                }
                
                if (strlen($pcFinalData)==0) return FALSE;
                $pcFinalData[strlen($pcFinalData)-2]=' ';
                
                // build 'where' clause
                $pcFinalWhere='';
                if ($arrWhere!=NULL){
                    $nCount=count($arrWhere);
                    for ($i=0; $i<$nCount; $i++){
                        if (is_array($arrWhere[$i])){
                            // add column and operator
                            $pcFinalWhere.='`'.$arrWhere[$i][0].'`'.$arrWhere[$i][1];
                            // add value
                            if (strtoupper($arrWhere[$i][1])==' LIKE ')
                                $pcFinalWhere.="'".$arrWhere[$i][2]."'";
                            else $pcFinalWhere.=is_string($arrWhere[$i][2]) ?
                                "'".$this->kDb->CleanString($arrWhere[$i][2])."'" : $arrWhere[$i][2];
                            // add condition
                            if ($i<$nCount-1) $pcFinalWhere.=' '.$arrWhere[$i][3].' ';
                        }else{
                            // add column and operator
                            $pcFinalWhere.='`'.$arrWhere[$i].'`'.$arrWhere[$i+1];
                            // add value
                            if (strtoupper($arrWhere[$i+1])==' LIKE ')
                                $pcFinalWhere.="'".$arrWhere[$i+2]."'";
                            else $pcFinalWhere.=is_string($arrWhere[$i+2]) ?
                                "'".$this->kDb->CleanString($arrWhere[$i+2])."'" : $arrWhere[$i+2];
                            // exit loop
                            $i=$nCount+1;
                        }
                    }
                }

                if (strlen($pcFinalWhere)>0) $pcFinalWhere="WHERE $pcFinalWhere";
                
                // build final query string
                $pcFinalQuery="UPDATE `$pcInto` SET $pcFinalData $pcFinalWhere";
				$this->pcLastQ = $pcFinalQuery;
                
                return $this->RunQuery($pcFinalQuery);
            }break;
        }

        return NULL;
    }

    /**
     * RunQuickInsert - make INSERT INTO queries into database
     * 
     * pcInto           = string
     * mxColumnList     = string ('col1, col2, col3, ...') or array('col1', 'col2', ...)
     * arrData          = array([0] => array ('col1'=>'value', 'col2'=>'value', ...), [1] => array(...) ...)
     * 
     * @return {Boolean} - success / fail
     */
    function RunQuickInsert($pcInto, $mxColumnList, $arrData)
    {
        // INSERT INTO `table` (list, of, columns) VALUES (`v1`, `v2`, `v3`), (`v11`, `v21`, `v31`) ...
        
        switch ($this->pcType)
        {
            case 'mysql': case 'mysqli':{
                // build column list
                $pcFinalColumnList='';
                $arrColList=array(); // needed later
                
                if ($mxColumnList!=NULL){
                    if (is_string($mxColumnList)){
                        $arrColList=explode(',',$mxColumnList);
                        foreach ($arrColList as $kKey => $pcElement) $arrColList[$kKey]=trim($pcElement);
                        
                        $pcFinalColumnList=' ('.$mxColumnList.')';
                    }
                    if (is_array($mxColumnList)){
                        $arrColList=$mxColumnList;
                        $pcFinalColumnList=' ('.implode(', ',$mxColumnList).')';
                    }
                }else return FALSE;
                
                // build data
                $pcFinalData='';
                $nCount=count($arrData);
                for ($i=0; $i<$nCount; $i++){
                    $pcFinalData.=' (';
                    
                    foreach ($arrColList as $pcColumn){
                        if (isset($arrData[$i][$pcColumn])){
                            if (is_string($arrData[$i][$pcColumn]))
                                $pcFinalData.="'".$this->CleanString($arrData[$i][$pcColumn])."', ";
                            else $pcFinalData.=$arrData[$i][$pcColumn].", ";
                        }else $pcFinalData.="'', ";
                    }
                    
                    $pcFinalData[strlen($pcFinalData)-2]=' ';
                    
                    $pcFinalData.=')';
                    if ($i+1<$nCount) $pcFinalData.=',';
                }
                if (strlen($pcFinalData)==0) return FALSE;
                
                // build final query string
                $pcFinalQuery="INSERT INTO `$pcInto` $pcFinalColumnList VALUES $pcFinalData";
				$this->pcLastQ = $pcFinalQuery;
                
                return $this->RunQuery($pcFinalQuery);
            }break;
            
            case 'sqlite': case 'sqlite3':{
                // build column list
                $pcFinalColumnList='';
                $arrColList=array(); // needed later
                
                if ($mxColumnList!=NULL){
                    if (is_string($mxColumnList)){
                        $arrColList=explode(',',$mxColumnList);
                        foreach ($arrColList as $kKey => $pcElement) $arrColList[$kKey]=trim($pcElement);
                        
                        $pcFinalColumnList=' ('.$mxColumnList.')';
                    }
                    if (is_array($mxColumnList)){
                        $arrColList=$mxColumnList;
                        $pcFinalColumnList=' ('.implode(', ',$mxColumnList).')';
                    }
                }else return FALSE;
                
                // build data
                $pcFinalData='';
                $nCount=count($arrData);
                for ($i=0; $i<$nCount; $i++){
                    $pcFinalData.=' (';
                    
                    foreach ($arrColList as $pcColumn){
                        if (isset($arrData[$i][$pcColumn])){
                            if (is_string($arrData[$i][$pcColumn]))
                                $pcFinalData.="'".$this->CleanString($arrData[$i][$pcColumn])."', ";
                            else $pcFinalData.=$arrData[$i][$pcColumn].", ";
                        }else $pcFinalData.="'', ";
                    }
                    
                    $pcFinalData[strlen($pcFinalData)-2]=' ';
                    
                    $pcFinalData.=')';
                    if ($i+1<$nCount) $pcFinalData.=',';
                }
                if (strlen($pcFinalData)==0) return FALSE;
                
                // build final query string
                $pcFinalQuery="INSERT INTO `$pcInto` $pcFinalColumnList VALUES $pcFinalData";
				$this->pcLastQ = $pcFinalQuery;
                
                return $this->RunQuery($pcFinalQuery);
            }break;
        }

        return NULL;
    }
	
    /**
     * RunQuickSelect - make SELECT queries into database
     * 
     * mxWhat       = array('column_name_1', ...) OR array(array('column_name_1','alias_1'), ...) OR 'string_expression' OR 'string_column' OR '*'
     * pcFrom       = 'table_name'
     * arrWhere = array(array('col_1','operator_1','value_1','cond_op_1'), ...) OR array('column','operator','value')
     * mxOrderBy    = array('column_1', ...) OR array(array('col_1','order_1'), ...) OR 'column' OR 'string_expression' OR array(array('expresion_1','order_1'), ...)
     * mxLimits = array(lim_1, lim_2) ... or 'lim_1'
     * 
     * @return {Array} - The result
     */
	function RunQuickSelect($mxWhat, $pcFrom, $arrWhere=NULL, $mxOrderBy=NULL, $mxLimits=NULL)
	{
		switch ($this->pcType)
		{
			case 'mysql': case 'mysqli':{
				// build 'what' clause
				$pcFinalWhat='';
				
				if (is_string($mxWhat)){
					if ($mxWhat=='*') $pcFinalWhat='*';
					elseif (strpos($mxWhat,'(')===FALSE && strpos($mxWhat,'`')===FALSE){
						// we have a single column
						$pcFinalWhat="`$mxWhat`";
					}else{
						// we have one of those complex query cases, with functions, like COUNT(*)
						$pcFinalWhat=$mxWhat;
					}
				}elseif (is_array($mxWhat)){
					$nCount=count($mxWhat);
					for ($i=0; $i<$nCount; $i++){
						if (is_array($mxWhat[$i]))
							$pcFinalWhat.='`'.$mxWhat[$i][0].'` AS `'.$mxWhat[$i][1].'`';
						else $pcFinalWhat.='`'.$mxWhat[$i].'`';
						
						if ($i<$nCount-1) $pcFinalWhat.=', ';
					}
				}
				
				// build 'where' clause
				$pcFinalWhere='';
				if ($arrWhere!=NULL){
					$nCount=count($arrWhere);
					for ($i=0; $i<$nCount; $i++){
						if (is_array($arrWhere[$i])){
							// add column and operator
							if (strpos($arrWhere[$i][0], '(')===false)
                                $pcFinalWhere.='`'.$arrWhere[$i][0].'`'.$arrWhere[$i][1];
                            else $pcFinalWhere.=$arrWhere[$i][0].$arrWhere[$i][1];
							
							// add value
                            if (strtoupper($arrWhere[$i][1])==' LIKE ')
                                $pcFinalWhere.="'".$arrWhere[$i][2]."'";
                            else $pcFinalWhere.=is_string($arrWhere[$i][2]) ?
                                "'".$this->kDb->CleanString($arrWhere[$i][2])."'" : $arrWhere[$i][2];
							// add condition
							if ($i<$nCount-1) $pcFinalWhere.=' '.$arrWhere[$i][3].' ';
						}else{
							// add column and operator
							if (strpos($arrWhere[$i], '(')===false)
                                $pcFinalWhere.='`'.$arrWhere[$i].'`'.$arrWhere[$i+1];
                            else $pcFinalWhere.=$arrWhere[$i].$arrWhere[$i+1];
							
							// add value
							if (strtoupper($arrWhere[$i+1])==' LIKE ')
                                $pcFinalWhere.="'".$arrWhere[$i+2]."'";
                            else $pcFinalWhere.=is_string($arrWhere[$i+2]) ?
                                "'".$this->kDb->CleanString($arrWhere[$i+2])."'" : $arrWhere[$i+2];
							// exit loop
							$i=$nCount+1;
						}
					}
				}
				
				// build 'order by' clause
				$pcFinalOrderby='';
				if ($mxOrderBy!=NULL){
					if (is_array($mxOrderBy)){
						$nCount=count($mxOrderBy);
						for ($i=0; $i<$nCount; $i++){
							if (is_array($mxOrderBy[$i])){
								if (is_int(strpos($mxOrderBy[$i][0],'('))){
									$pcFinalOrderby.=$mxOrderBy[$i][0].' '.$mxOrderBy[$i][1];
								}else{
									$pcFinalOrderby.='`'.$mxOrderBy[$i][0].'` '.$mxOrderBy[$i][1];
								}
							}else{
								$pcFinalOrderby.='`'.$mxOrderBy[$i].'`';
							}
							
							// add condition
							if ($i<$nCount-1) $pcFinalOrderby.=', ';
						}
					}else{
						if (is_int(strpos($mxOrderBy,'('))) $pcFinalOrderby=(string)$mxOrderBy;
						else $pcFinalOrderby="`$mxOrderBy`";
					}
				}
				
				// build final limits
				$pcFinalLimits='';
				if ($mxLimits!=NULL){
					if (is_array($mxLimits)){
						$pcFinalLimits="$mxLimits[0], $mxLimits[1]";
					}else{
						$pcFinalLimits=(string)$mxLimits;
					}
				}
				
				// build final query string
				if (strlen($pcFinalWhere)>0) $pcFinalWhere="WHERE $pcFinalWhere";
				if (strlen($pcFinalOrderby)>0) $pcFinalOrderby="ORDER BY $pcFinalOrderby";
				if (strlen($pcFinalLimits)>0) $pcFinalLimits="LIMIT $pcFinalLimits";
				$pcFinalQuery="SELECT $pcFinalWhat FROM `$pcFrom` $pcFinalWhere $pcFinalOrderby $pcFinalLimits";
				$this->pcLastQ=$pcFinalQuery;
				
				return $this->RunQuery($pcFinalQuery);
			}break;
            
            case 'sqlite': case 'sqlite3':{
                // build 'what' clause
                $pcFinalWhat='';
                
                if (is_string($mxWhat)){
                    if ($mxWhat=='*') $pcFinalWhat='*';
                    elseif (strpos($mxWhat,'(')===FALSE && strpos($mxWhat,'`')===FALSE){
                        // we have a single column
                        $pcFinalWhat="`$mxWhat`";
                    }else{
                        // we have one of those complex query cases, with functions, like COUNT(*)
                        $pcFinalWhat=$mxWhat;
                    }
                }elseif (is_array($mxWhat)){
                    $nCount=count($mxWhat);
                    for ($i=0; $i<$nCount; $i++){
                        if (is_array($mxWhat[$i]))
                            $pcFinalWhat.='`'.$mxWhat[$i][0].'` AS `'.$mxWhat[$i][1].'`';
                        else $pcFinalWhat.='`'.$mxWhat[$i].'`';
                        
                        if ($i<$nCount-1) $pcFinalWhat.=', ';
                    }
                }
                
                // build 'where' clause
                $pcFinalWhere='';
                if ($arrWhere!=NULL){
                    $nCount=count($arrWhere);
                    for ($i=0; $i<$nCount; $i++){
                        if (is_array($arrWhere[$i])){
                            // add column and operator
                            if (strpos($arrWhere[$i][0], '(')===false)
                                $pcFinalWhere.='`'.$arrWhere[$i][0].'`'.$arrWhere[$i][1];
                            else $pcFinalWhere.=$arrWhere[$i][0].$arrWhere[$i][1];
                            
                            // add value
                            if (strtoupper($arrWhere[$i][1])==' LIKE ')
                                $pcFinalWhere.="'".$arrWhere[$i][2]."'";
                            else $pcFinalWhere.=is_string($arrWhere[$i][2]) ?
                                "'".$this->kDb->CleanString($arrWhere[$i][2])."'" : $arrWhere[$i][2];
                            // add condition
                            if ($i<$nCount-1) $pcFinalWhere.=' '.$arrWhere[$i][3].' ';
                        }else{
                            // add column and operator
                            if (strpos($arrWhere[$i], '(')===false)
                                $pcFinalWhere.='`'.$arrWhere[$i].'`'.$arrWhere[$i+1];
                            else $pcFinalWhere.=$arrWhere[$i].$arrWhere[$i+1];
                            
                            // add value
                            if (strtoupper($arrWhere[$i+1])==' LIKE ')
                                $pcFinalWhere.="'".$arrWhere[$i+2]."'";
                            else $pcFinalWhere.=is_string($arrWhere[$i+2]) ?
                                "'".$this->kDb->CleanString($arrWhere[$i+2])."'" : $arrWhere[$i+2];
                            // exit loop
                            $i=$nCount+1;
                        }
                    }
                }
                
                // build 'order by' clause
                $pcFinalOrderby='';
                if ($mxOrderBy!=NULL){
                    if (is_array($mxOrderBy)){
                        $nCount=count($mxOrderBy);
                        for ($i=0; $i<$nCount; $i++){
                            if (is_array($mxOrderBy[$i])){
                                if (is_int(strpos($mxOrderBy[$i][0],'('))){
                                    $pcFinalOrderby.=$mxOrderBy[$i][0].' '.$mxOrderBy[$i][1];
                                }else{
                                    $pcFinalOrderby.='`'.$mxOrderBy[$i][0].'` '.$mxOrderBy[$i][1];
                                }
                            }else{
                                $pcFinalOrderby.='`'.$mxOrderBy[$i].'`';
                            }
                            
                            // add condition
                            if ($i<$nCount-1) $pcFinalOrderby.=', ';
                        }
                    }else{
                        if (is_int(strpos($mxOrderBy,'('))) $pcFinalOrderby=(string)$mxOrderBy;
                        else $pcFinalOrderby="`$mxOrderBy`";
                    }
                }
                
                // build final limits
                $pcFinalLimits='';
                if ($mxLimits!=NULL){
                    if (is_array($mxLimits)){
                        $pcFinalLimits="$mxLimits[0], $mxLimits[1]";
                    }else{
                        $pcFinalLimits=(string)$mxLimits;
                    }
                }
                
                // build final query string
                if (strlen($pcFinalWhere)>0) $pcFinalWhere="WHERE $pcFinalWhere";
                if (strlen($pcFinalOrderby)>0) $pcFinalOrderby="ORDER BY $pcFinalOrderby";
                if (strlen($pcFinalLimits)>0) $pcFinalLimits="LIMIT $pcFinalLimits";
                $pcFinalQuery="SELECT $pcFinalWhat FROM `$pcFrom` $pcFinalWhere $pcFinalOrderby $pcFinalLimits";
                $this->pcLastQ=$pcFinalQuery;
                
                return $this->RunQuery($pcFinalQuery);
            }break;
		}

        return NULL;
	}

    /**
     * RunQuickCount - make SELECT queries into database using COUNT(*) argument
     * 
     * pcFrom       = 'table_name'
     * arrWhere = array(array('col_1','operator_1','value_1','cond_op_1'), ...) OR array('column','operator','value')
     * mxOrderBy    = array('column_1', ...) OR array(array('col_1','order_1'), ...) OR 'column' OR 'string_expression' OR array(array('expresion_1','order_1'), ...)
     * mxLimits = array(lim_1, lim_2) ... or 'lim_1'
     * 
     * @return {int} - The result
     */
    function RunQuickCount($pcFrom, $arrWhere=NULL)
    {
        switch ($this->pcType)
        {
            case 'mysql': case 'mysqli':{
                // build 'what' clause
                $pcFinalWhat='COUNT(*) AS `cnt`';
                
                // build 'where' clause
                $pcFinalWhere='';
                if ($arrWhere!=NULL){
                    $nCount=count($arrWhere);
                    for ($i=0; $i<$nCount; $i++){
                        if (is_array($arrWhere[$i])){
                            // add column and operator
                            $pcFinalWhere.='`'.$arrWhere[$i][0].'`'.$arrWhere[$i][1];
                            // add value
                            if (strtoupper($arrWhere[$i][1])==' LIKE ')
                                $pcFinalWhere.="'".$arrWhere[$i][2]."'";
                            else $pcFinalWhere.=is_string($arrWhere[$i][2]) ?
                                "'".$this->kDb->CleanString($arrWhere[$i][2])."'" : $arrWhere[$i][2];
                            // add condition
                            if ($i<$nCount-1) $pcFinalWhere.=' '.$arrWhere[$i][3].' ';
                        }else{
                            // add column and operator
                            $pcFinalWhere.='`'.$arrWhere[$i].'`'.$arrWhere[$i+1];
                            // add value
                            if (strtoupper($arrWhere[$i+1])==' LIKE ')
                                $pcFinalWhere.="'".$arrWhere[$i+2]."'";
                            else $pcFinalWhere.=is_string($arrWhere[$i+2]) ?
                                "'".$this->kDb->CleanString($arrWhere[$i+2])."'" : $arrWhere[$i+2];
                            // exit loop
                            $i=$nCount+1;
                        }
                    }
                }
                
                // build final query string
                if (strlen($pcFinalWhere)>0) $pcFinalWhere="WHERE $pcFinalWhere";
                $pcFinalQuery="SELECT $pcFinalWhat FROM `$pcFrom` $pcFinalWhere";
				$this->pcLastQ = $pcFinalQuery;
                
                $arrRes=$this->RunQuery($pcFinalQuery);
                return (int)$arrRes[0]['cnt'];
            }break;
            
            case 'sqlite': case 'sqlite3':{
                // build 'what' clause
                $pcFinalWhat='COUNT(*) AS `cnt`';
                
                // build 'where' clause
                $pcFinalWhere='';
                if ($arrWhere!=NULL){
                    $nCount=count($arrWhere);
                    for ($i=0; $i<$nCount; $i++){
                        if (is_array($arrWhere[$i])){
                            // add column and operator
                            $pcFinalWhere.='`'.$arrWhere[$i][0].'`'.$arrWhere[$i][1];
                            // add value
                            if (strtoupper($arrWhere[$i][1])==' LIKE ')
                                $pcFinalWhere.="'".$arrWhere[$i][2]."'";
                            else $pcFinalWhere.=is_string($arrWhere[$i][2]) ?
                                "'".$this->kDb->CleanString($arrWhere[$i][2])."'" : $arrWhere[$i][2];
                            // add condition
                            if ($i<$nCount-1) $pcFinalWhere.=' '.$arrWhere[$i][3].' ';
                        }else{
                            // add column and operator
                            $pcFinalWhere.='`'.$arrWhere[$i].'`'.$arrWhere[$i+1];
                            // add value
                            if (strtoupper($arrWhere[$i+1])==' LIKE ')
                                $pcFinalWhere.="'".$arrWhere[$i+2]."'";
                            else $pcFinalWhere.=is_string($arrWhere[$i+2]) ?
                                "'".$this->kDb->CleanString($arrWhere[$i+2])."'" : $arrWhere[$i+2];
                            // exit loop
                            $i=$nCount+1;
                        }
                    }
                }
                
                // build final query string
                if (strlen($pcFinalWhere)>0) $pcFinalWhere="WHERE $pcFinalWhere";
                $pcFinalQuery="SELECT $pcFinalWhat FROM `$pcFrom` $pcFinalWhere";
				$this->pcLastQ = $pcFinalQuery;
                
                $arrRes=$this->RunQuery($pcFinalQuery);
                return (int)$arrRes[0]['cnt'];
            }break;
        }

        return NULL;
    }
	
	function GetNextAutoincrement($pcTable)
    {
        switch ($this->pcType)
        {
            case 'mysql': case 'mysqli':{
                $pcQuery="SELECT `AUTO_INCREMENT` FROM  `INFORMATION_SCHEMA`.`TABLES`".
                    "WHERE `TABLE_SCHEMA` = '". $this->kDb->CleanString($this->pcDBName) .
                    "'AND   `TABLE_NAME`   = '". $this->kDb->CleanString($pcTable) ."'";
                
                $arrRes=$this->kDb->RunQuery($pcQuery);
                return (int)$arrRes[0]['AUTO_INCREMENT'];
            }break;
            
            case 'sqlite': case 'sqlite3':{
                $pcQuery="SELECT `seq` FROM  `sqlite_sequence`".
                    "WHERE `name` = '". $this->kDb->CleanString($pcTable) ."'";
                
                $arrRes=$this->kDb->RunQuery($pcQuery);
                if (empty($arrRes) || is_bool($arrRes) || is_null($arrRes)) return 1;
                
                return (int)$arrRes[0]['seq']+1;
            }break;
        }

        return NULL;
    }
	
	function CleanString($pcStr)
	{
		return $this->kDb->CleanString($pcStr);
	}
	
	function ChangeDatabase($pcNewDB)
	{
		$this->kDb->ChangeDatabase($pcNewDB);
	}
	
	function GetType()
	{
		return $this->pcType;
	}
    
    function GetError()
    {
        return $this->kDb->GetError();
    }
	
	function GetLastQuery()
    {
        return $this->pcLastQ;
    }
}

} // endif defined SYSCAP_DATABASE

////////////////////////////////////////////////////////////////////////////////
// History:
//  -- 21/06/2019 - v1 created;