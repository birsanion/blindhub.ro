<?php
////////////////////////////////////////////////////////////////////////////////
// Part of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.
// -- For history see the end of this file.

if (!defined('SYSCAP_HTMLPARSER')){
// this tells the whole framework that this system file has been included
define('SYSCAP_HTMLPARSER', true);

/*
    <!DOCTYPE ...

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

    <!-- comentariu html -->

    <tag attribute="value">text content</tag>
    <tag attribute="value" />
    <tag attribute="value">
*/

class CQHTMLElement
{
    private $strTag;
    private $arrAttributes;
    private $arrChildren;
    
    private $strID;
    private $arrClasses;
    private $strSubtype;
    
    private $strValue;
    private $arrPossibleValues;
    
    private $arrVoidElements;
    
    function __construct()
    {
        $this->strTag = '';
        $this->arrAttributes = array();
        $this->arrChildren = array();
        
        $this->strID = NULL;
        $this->arrClasses = array();
        $this->strSubtype = NULL;
        
        $this->strValue = NULL;
        $this->arrPossibleValues = NULL;
        
        $this->arrVoidElements = array('area', 'base', 'br', 'col', 'embed', 'hr',
            'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr');
    }
    
    function ImportData(&$arrElement)
    {
        $this->strTag = $arrElement['tag'];
        
        if (isset($arrElement['attr']))
            $this->arrAttributes = $arrElement['attr'];
        
        if (isset($this->arrAttributes['id'])){
            if (strlen(trim($this->arrAttributes['id'])) > 0)
                $this->strID = trim($this->arrAttributes['id']);
            
            unset($this->arrAttributes['id']);
        }
        
        if (isset($this->arrAttributes['class'])){
            $nClassLen = strlen(trim($this->arrAttributes['class']));
            if ($nClassLen > 0){
                $i = 0;
                $strClass = '';
                $strClasses = trim($this->arrAttributes['class']);
                
                while ($i < $nClassLen){
                    $strClass = '';
                    
                    while ($i < $nClassLen && $strClasses[$i] != ' '){
                        $strClass .= $strClasses[$i];
                        $i++;
                    }
                    
                    $this->arrClasses[] = $strClass;
                    
                    while ($i < $nClassLen && $strClasses[$i] == ' ') $i++;
                    $strClass = '';
                }
                
                if (strlen($strClass) > 0) $this->arrClasses[] = $strClass;
            }
            
            unset($this->arrAttributes['class']);
        }
        
        if ($this->strTag == 'input' && isset($this->arrAttributes['type'])){
            $this->strSubtype = $this->arrAttributes['type'];
            unset($this->arrAttributes['type']);
        }
        
        switch ($this->strTag)
        {
            case 'select':{
                if (isset($arrElement['children'])){
                    $this->arrPossibleValues = array();
                    
                    foreach ($arrElement['children'] as $nKey => &$arrChild){
                        $strLabel = (!empty($arrChild['children']) ?
                            (isset($arrChild['children'][0]['text']) ? $arrChild['children'][0]['text'] : '') : '');
                        
                        $strValue = (isset($arrChild['attr']['value']) ?
                                $arrChild['attr']['value'] : $strLabel);
                        
                        if (strlen($strLabel . $strValue))
                            $this->arrPossibleValues[] = array(
                                'value' => $strValue,
                                'label' => $strLabel
                            );
                            
                        if (isset($arrChild['attr']['selected']))
                            $this->strValue = $strValue;
                    }
                }
            }break;
                
            case 'textarea':{
                if (isset($arrChild['children'][0]['text'])){
                    $this->strValue = $arrChild['children'][0]['text'];
                }
            }break;
                
            case '**rawtext**':{
                $this->strValue = $arrElement['text'];
            }break;
                
            default:{
                if (isset($arrElement['children'])){
                    foreach ($arrElement['children'] as $nKey => &$arrChild){
                        $this->arrChildren[$nKey] = new CQHTMLElement();
                        $this->arrChildren[$nKey]->ImportData($arrChild);
                    }
                }
            }
        }
    }
}

class CQHTMLStructure
{
    private $strDoctype;
    private $arrVoidElements;
    private $arrDimensionVector;
    private $nVectorIndex;
    
    private $arrStructure;
    /*
     *  index
     *      tag
     *      attr
     *      children
     */
    
    private $kDOM;
    
    function __construct()
    {
        $this->arrVoidElements = array('area', 'base', 'br', 'col', 'embed', 'hr',
            'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr');
        
        $this->arrDimensionVector = array();
        $this->nVectorIndex = 0;
        
        $this->strDoctype = '';
        $this->arrStructure = array(
            array(
                'tag' => '**root**',
                'attr' => array(),
                'children' => array()
            )
        );
        
        $this->arrDimensionVector[0] = &$this->arrStructure[0];
        $this->kDOM = NULL;
    }
    
    function Debug()
    {
        //print_r($this->kDOM);
        print_r($this->arrStructure);
    }
    
    function GenerateText()
    {
        
    }
    
    function ParseText($strInput)
    {
        // get rid of XML and DOCTYPE, but first store them
        if (strpos($strInput, '<?xml') !== false){
            $nStart = strpos($strInput, '<?xml');
            $nStop = strpos($strInput, '?>', $nStart + 4);
            
            $this->strDoctype .= substr($strInput, $nStart, $nStop+2-$nStart);
            
            $strInput = substr($strInput, 0, $nStart) . substr($strInput, $nStop + 2);
        }
        
        if (strpos($strInput, '<!DOCTYPE') !== false){
            $nStart = strpos($strInput, '<!DOCTYPE');
            $nStop = strpos($strInput, '>', $nStart + 4);
            
            $this->strDoctype .= substr($strInput, $nStart, $nStop+1-$nStart);
            
            $strInput = substr($strInput, 0, $nStart) . substr($strInput, $nStop + 1);
        }
        
        // now we can scan
        $strInput = str_replace(array("\t", "\r", "\n"), array(' ', '', ''), $strInput);
        
        $nLength = strlen($strInput);
        
        // pad input for safety
        $strInput .= '          ';
        
        $i = 0;
        $bWasVoidElement = false;
        $bInsideTag = true;         // refers to the tag declaration, with the list of
                                    // attributes, not the tag content
        
        while ($i < $nLength){
            // start of tag
            if ($strInput[$i] == '<' && $strInput[$i+1] != '/' && $strInput[$i+1] != '!'){
                $nStart = $i+1;
                while ($i < $nLength && $strInput[$i] != ' ' &&
                    $strInput[$i] != '>' && $strInput[$i] != '/') $i++;
                
                $strTag = strtolower(substr($strInput, $nStart, $i-$nStart));
                
                // add to structure & move vector
                $this->arrDimensionVector[$this->nVectorIndex]['children'][] = array(
                    'tag' => $strTag,
                    'attr' => array(),
                    'children' => array()
                );
                
                $nThisChild = count($this->arrDimensionVector[$this->nVectorIndex]['children']) - 1;
                
                $this->arrDimensionVector[$this->nVectorIndex + 1] = &$this->arrDimensionVector[$this->nVectorIndex]['children'][$nThisChild];
                $this->nVectorIndex++;
                
                if (in_array($strTag, $this->arrVoidElements)) $bWasVoidElement = true;
                $bInsideTag = true;
                
                // advance past spaces
                while ($i < $nLength && $strInput[$i] == ' ') $i++;
                
            // closing tag
            }elseif ($strInput[$i] == '<' && $strInput[$i+1] == '/'){
                $i += 2;
                $nStart = $i;
                
                while ($i < $nLength && $strInput[$i] != ' ' && $strInput[$i] != '>') $i++;
                
                $strTag = strtolower(substr($strInput, $nStart, $i-$nStart));
                
                if ($strTag == $this->arrDimensionVector[$this->nVectorIndex]['tag']){
                    // valid ending tag, closing branch
                    unset($this->arrDimensionVector[$this->nVectorIndex]);
                    $this->nVectorIndex--;
                }// else we just ignore it
                
                $bInsideTag = false;
                
                // jump over tag and spaces
                while ($i < $nLength && ($strInput[$i] == ' ' || $strInput[$i] == '>')) $i++;
                
            // end of tag
            }elseif ($strInput[$i] == '>' || ($strInput[$i] == '/' && $strInput[$i+1] == '>')){
                if ($bWasVoidElement){
                    unset($this->arrDimensionVector[$this->nVectorIndex]);
                    $this->nVectorIndex--;
                }
                
                $bWasVoidElement = false;
                $bInsideTag = false;
                
                $i++;
                if ($strInput[$i] == '/') $i++;
                
                // advance past spaces
                while ($i < $nLength && $strInput[$i] == ' ') $i++;
                
            // comments (to be skipped)
            }elseif ($strInput[$i] == '<' && $strInput[$i+1] == '!' &&
                        $strInput[$i+2] == '-' && $strInput[$i+3] == '-')
            {
                while ($i < $nLength && !($strInput[$i] == '-' &&
                    $strInput[$i+1] == '-' && $strInput[$i+2] == '>')) $i++;
                
                $i += 3;
                
            // other text
            }else{
                if ($bInsideTag){
                    // we have some attributes
                    $nStart = $i;
                    
                    while ($i < $nLength && $strInput[$i] != ' ' && $strInput[$i] != '=') $i++;
                    
                    $strAttr = strtolower(substr($strInput, $nStart, $i-$nStart));
                    $mxValue = '';
                    
                    // advance past spaces
                    while ($i < $nLength && $strInput[$i] == ' ') $i++;
                    
                    // jump over equal
                    if ($i < $nLength && $strInput[$i] == '='){
                        $i++;
                    
                        // advance past spaces again, if any
                        while ($i < $nLength && $strInput[$i] == ' ') $i++;
                        
                        if ($strInput[$i] == '"'){
                            $i++;
                            
                            $nStart = $i;
                            while ($i < $nLength && $strInput[$i] != '"') $i++;
                            
                            $mxValue = substr($strInput, $nStart, $i-$nStart);
                            
                            $i++;
                        }elseif ($strInput[$i] == '\''){
                            $i++;
                            
                            $nStart = $i;
                            while ($i < $nLength && $strInput[$i] != '\'') $i++;
                            
                            $mxValue = substr($strInput, $nStart, $i-$nStart);
                            
                            $i++;
                        }else{
                            $nStart = $i;
                            while ($i < $nLength && $strInput[$i] != ' ') $i++;
                            
                            $mxValue = substr($strInput, $nStart, $i-$nStart);
                        }
                    }else{
                        // we don't have a value for this attribute, so we just add it as it is
                        $mxValue = true;
                    }
                    
                    $this->arrDimensionVector[$this->nVectorIndex]['attr'][$strAttr] = $mxValue;
                    
                    // advance past spaces
                    while ($i < $nLength && $strInput[$i] == ' ') $i++;
                }else{
                    // we have some raw text
                    $nStart = $i;
                    
                    while ($i < $nLength && $strInput[$i] != '<') $i++;
                    
                    if ($i > $nStart){
                        $str = substr($strInput, $nStart, $i-$nStart);
                        
                        if (strlen(trim($str)) > 0)
                            $this->arrDimensionVector[$this->nVectorIndex]['children'][] = array(
                                'tag' => '**rawtext**',
                                'text' => $str
                            );
                    }
                }
            }
        }

        // now transform array to objects
        $this->kDOM = new CQHTMLElement();
        $this->kDOM->ImportData($this->arrStructure[0]);
    }
}

} // endif defined
