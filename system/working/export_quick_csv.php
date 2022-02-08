<?php

class CQExportQuickCSV
{
    // variables
    private $pcFilename;
    private $arrData;
    
    // constructors
    function __construct()
    {
        $this->pcFilename='';
        $this->arrData=array();
    }
    
    function CreateNewSheet($pcFile)
    {
        $this->pcFilename=$pcFile;
        $this->arrData=array();
    }
    
    function write($nRow, $nCol, $pcText)
    {
        if (!isset($this->arrData[(int)$nRow]))
            $this->arrData[(int)$nRow]=array();
            
        $this->arrData[(int)$nRow][(int)$nCol]=$pcText;
    }
    
    function CleanValue($mxVal)
    {
        if (is_string($mxVal)){
            if (strpos($mxVal, '"')!==FALSE)
                return '"'. str_replace('"', '""', $mxVal) .'"';
            elseif (strpos($mxVal, ',')!==FALSE)
                return '"'. $mxVal .'"';
            else return $mxVal;
        }else return $mxVal;
            
        return 'error'; // should not get here
    }
    
    function SaveFile()
    {
        $pcFileContents='';
        $nMaxRows=0;
        $nMaxCols=0;
        
        // determine max rows and cols
        foreach ($this->arrData as $nKey => $arrRow){
            if ((int)$nKey>$nMaxRows) $nMaxRows=(int)$nKey;
            
            foreach ($arrRow as $nKeyCol => $ni)
                if ((int)$nKeyCol>$nMaxCols) $nMaxCols=(int)$nKeyCol;
        }
        
        $nMaxRows++;
        $nMaxCols++;
        
        // generate content
        for ($r=0; $r<$nMaxRows; $r++){
            for ($c=0; $c<$nMaxCols; $c++){
                if (isset($this->arrData[$r][$c])){
                    if ($c>0) $pcFileContents.=',';
                    $pcFileContents.=$this->CleanValue($this->arrData[$r][$c]);
                }else if ($c>0) $pcFileContents.=',';
            }
            
            $pcFileContents.="\r\n";
        }
        
        // write content to file
        return file_put_contents($this->pcFilename, $pcFileContents);
    }
}

?>