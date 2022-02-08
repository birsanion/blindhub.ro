<?php
////////////////////////////////////////////////////////////////////////////////
// Part of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.
// -- For history see the end of this file.

if (!defined('SYSCAP_QWFDRL')){
// this tells the whole framework that this system file has been included
define('SYSCAP_QWFDRL', true);

/****
 * QWF Data Representation Language
 * ****
 * A similar language to YAML, but more primitive and somewhat easier to
 * handwrite.
 * 
 * root (invisible)
 *      /nkey/n: 1234           # integer value
 *      /bkey/b: true           # boolean value
 *      /fkey/f: 3.14           # floating point value
 *      /skey: something        # string value
 *      /" some space key ": value  # a key containing spaces in front and/or back
 * 
 *      + branch_name           # array
 *          /something: value
 * 
 *      # this is a comment; it stands on it's own line (required)
 * 
 *      /some long text: This text starts on this line 
 *          and ends on some other line; 
 *          all lines must have +1 identation relative to the 
 *          starting line !
 * 
 *      /keyx|hex: 0AFB553E9C     # hex encoded string
 *      /keyz|base64: 3948yrfdskgdr   # base64 encoded string
 * 
 * TAB = 4 SPACES
 */

define('QWFDRL_TAB',    "\t");
define('QWFDRL_CR',     "\r");
define('QWFDRL_LF',     "\n");
define('QWFDRL_CRLF',   "\r\n");

define('QWFDRL_POST_GZIP',      1);
define('QWFDRL_POST_BASE64',    2);
define('QWFDRL_POST_HEX',       4);

define('QWFDRL_INT',        0);
define('QWFDRL_FLOAT',      1);
define('QWFDRL_BOOL',       2);
define('QWFDRL_STRING',     3);
define('QWFDRL_ARRAY',      4);
define('QWFDRL_NULL',       5);

class CQBinaryData
{
    private $mxData;
    private $nProcessing;
    private $bEncodedState;
    
    function __construct()
    {
        $this->mxData = NULL;
        $this->nProcessing = 0;
        $this->bEncodedState = false;
    }
    
    function SetData($mxInput)
    {
        $this->mxData = $mxInput;
    }
    
    function SetProcessing($nProc)
    {
        $this->nProcessing = $nProc;
    }
    
    function Encode()
    {
        switch ($this->nProcessing)
        {
            case QWFDRL_POST_HEX:{
                $this->mxData = bin2hex($this->mxData);
                $this->bEncodedState = true;
                return $this->mxData;
            }break;
                
            case QWFDRL_POST_BASE64:{
                $this->mxData = base64_encode($this->mxData);
                $this->bEncodedState = true;
                return $this->mxData;
            }break;
        }
        
        return false;
    }
    
    function Decode()
    {
        switch ($this->nProcessing)
        {
            case QWFDRL_POST_HEX:{
                $this->mxData = hex2bin($this->mxData);
                $this->bEncodedState = false;
                return $this->mxData;
            }break;
                
            case QWFDRL_POST_BASE64:{
                $this->mxData = base64_decode($this->mxData);
                $this->bEncodedState = false;
                return $this->mxData;
            }break;
        }
        
        return false;
    }
    
    function IsDecoded()
    {
        return !$this->bEncodedState;
    }
    
    function GetData()
    {
        return $this->mxData;
    }
    
    function GetProcessing()
    {
        return $this->nProcessing;
    }
}

function CQBinary($mxData, $nProcess = 0)
{
    $kData = new CQBinaryData();
    $kData->SetData($mxData);
    $kData->SetProcessing($nProcess);
    
    return $kData;
}


////////////////////////////////////////////////////////////////////////////////
class CQWFDRL
{
    private $arrStructured;
    private $strSerialized;
    
    private $nCursor;
    private $nLength;
    
    function __construct()
    {
        $this->arrStructured = array();
        $this->strSerialized = '';
        
        $this->nCursor = 0;
        $this->nLength = 0;
    }
    
    static function GetExampleArray()
    {
        return array(
            'integer' => 14,
            'float' => 3.14159265358979,
            'bool' => true,
            'null' => NULL,
            'null string' => '',
            'a string containing "NULL"' => 'NULL',
            'children' => array(
                'what\'s your' => 'name ?',
                'PHP version' => PHP_VERSION
            ),
            'many branches' => array(
                array(
                    array(
                        array(
                            'first',
                            'second',
                            'third',
                            '4th'
                        )
                    ),
                    'hex-encoded-string' => CQBinary('This is a hex encoded string '.
                        'that will be decoded at unserialization time', QWFDRL_POST_HEX),
                    'base64-encoded-string' => CQBinary('This is a base64 encoded string '.
                        'that will be decoded at unserialization time', QWFDRL_POST_BASE64)
                )
            ),
            'really long text' => "This is a really long text. If you were to write this manually,\r\n".
                "you would most likely write it on multiple lines.\r\nHere on serialization, ".
                "it won't appear on multiple lines. However in the next example it will."
        );
    }

    static function GetExampleSerialized()
    {
        return <<<EOT
/integer/n: 14
/float/f: 3.1415926535898
/bool/b: true
/null/u: NULL
/null string: 
/a string containing \\"NULL\\": NULL
+ children
    /what's your: name ?
    /PHP version: 5.5.15

+ many branches
    +
        +
            +
                /: first
                /: second
                /: third
                /: 4th
                # all of the above are index-keys containing string values
                //n: 86
                # this was index-key integer value
                //f: 1.68
                # this was index-key float
                # you can use all the /nfbu suffixes like this for values that are
                # indexed-keyed; also, you can write comments like this,
                # placing them on their own lines and starting them with '#'

        /hex-encoded-string|hex: 5468697320697320612068657820656e636f64656420737472696e6720746861742077696c6c206265206465636f64656420617420756e73657269616c697a6174696f6e2074696d65
        /base64-encoded-string|base64: VGhpcyBpcyBhIGJhc2U2NCBlbmNvZGVkIHN0cmluZyB0aGF0IHdpbGwgYmUgZGVjb2RlZCBhdCB1bnNlcmlhbGl6YXRpb24gdGltZQ==

# when handwriting, you can leave empty lines like this, to visually separate things
/really long text: This is a really long text. If you were to write this manually, 
    you would most likely write it on multiple lines. 
    On serialization it didn't appear on multiple lines. 
    However in this example we are doing so. 
    All lines must have +1 identation relative to the 
    starting line ! \\r\\n
    Also, no new line is being introduced into the decoded 
    text. In order to have new lines, add "\\\\r\\\\n" characters ...
    \\r\\nlike this :)

EOT;
    }
    
    // for serialization
    private function CleanKeyString($strKey)
    {
        return str_replace(array("\\", "\r", "\n", "\t", '/', ':', '"'),
            array("\\\\", "\\r", "\\n", "\\t", '\\/', '\\:', '\\"'), $strKey);
    }
    
    // for serialization
    private function CleanValueString($strValue)
    {
        return str_replace(array("\\", "\r", "\n"), array("\\\\", "\\r", "\\n"), $strValue);
    }
    
    // for serialization
    private function GetKeyRepresentation($mxKey)
    {
        $strKey = '';
        
        if (is_string($mxKey)){
            if ($mxKey[0] == ' ' || $mxKey[0] == QWFDRL_TAB || $mxKey[0] == QWFDRL_CR ||
                $mxKey[0] == QWFDRL_LF || $mxKey[strlen($mxKey)-1] == ' ' ||
                $mxKey[strlen($mxKey)-1] == QWFDRL_TAB || $mxKey[strlen($mxKey)-1] == QWFDRL_CR ||
                $mxKey[strlen($mxKey)-1] == QWFDRL_LF)
            {
                $strKey = '"' . $this->CleanKeyString($mxKey) . '"';
            }else $strKey = $this->CleanKeyString($mxKey);
        
        }elseif (is_int($mxKey)){
            $strKey = $mxKey;
        }else{
            // what the hell, this shouldn't happen
            $strKey = '??????????';
        }
        
        return $strKey;
    }
    
    // for serialization
    private function SubSerialize($arrData, $nLevel)
    {
        $strOutput = '';
        $strPad = str_repeat('    ', $nLevel);
        
        foreach ($arrData as $mxKey => $mxValue){
            if (is_array($mxValue)){
                $strOutput .= $strPad . '+ ' . $this->GetKeyRepresentation($mxKey) . QWFDRL_CRLF;
                
                $strOutput .= $this->SubSerialize($mxValue, $nLevel + 1);
            
            }elseif (is_int($mxValue)){
                $strOutput .= $strPad . '/' . $this->GetKeyRepresentation($mxKey) .
                    '/n: ' . $mxValue . QWFDRL_CRLF;
                
            }elseif (is_float($mxValue)){
                $strOutput .= $strPad . '/' . $this->GetKeyRepresentation($mxKey) .
                    '/f: ' . $mxValue . QWFDRL_CRLF;
                
            }elseif (is_bool($mxValue)){
                $strOutput .= $strPad . '/' . $this->GetKeyRepresentation($mxKey) .
                    '/b: ' . ($mxValue ? 'true' : 'false') . QWFDRL_CRLF;
                    
            }elseif (is_string($mxValue)){
                $strOutput .= $strPad . '/' . $this->GetKeyRepresentation($mxKey) .
                    ': ' . $this->CleanValueString($mxValue) . QWFDRL_CRLF;
                    
            }elseif ($mxValue === NULL){
                $strOutput .= $strPad . '/' . $this->GetKeyRepresentation($mxKey) .
                    '/u: NULL' . QWFDRL_CRLF;
                    
            }elseif (is_a($mxValue, 'CQBinaryData')){
                $strOutput .= $strPad . '/' . $this->GetKeyRepresentation($mxKey) .
                    ($mxValue->GetProcessing() == QWFDRL_POST_BASE64 ? '|base64' :
                    ($mxValue->GetProcessing() == QWFDRL_POST_HEX ? '|hex' : '')).
                    ': ' . $mxValue->Encode() . QWFDRL_CRLF;
                    
            }else{
                // we can't really store this, but we'll try something anyway
                $strOutput .= $strPad . '/??' . QWFDRL_CRLF;
            }
        }

        return $strOutput;
    }
    
    // for serialization
    function Serialize($arrData, $nPostprocess = 0)
    {
        $this->strSerialized = '';
        
        foreach ($arrData as $mxKey => $mxValue){
            if (is_array($mxValue)){
                $this->strSerialized .= '+ ' . $this->GetKeyRepresentation($mxKey) . QWFDRL_CRLF;
                
                $this->strSerialized .= $this->SubSerialize($mxValue, 1);
            
            }elseif (is_int($mxValue)){
                $this->strSerialized .= '/' . $this->GetKeyRepresentation($mxKey) .
                    '/n: ' . $mxValue . QWFDRL_CRLF;
                
            }elseif (is_float($mxValue)){
                $this->strSerialized .= '/' . $this->GetKeyRepresentation($mxKey) .
                    '/f: ' . $mxValue . QWFDRL_CRLF;
                
            }elseif (is_bool($mxValue)){
                $this->strSerialized .= '/' . $this->GetKeyRepresentation($mxKey) .
                    '/b: ' . ($mxValue ? 'true' : 'false') . QWFDRL_CRLF;
                    
            }elseif (is_string($mxValue)){
                $this->strSerialized .= '/' . $this->GetKeyRepresentation($mxKey) .
                    ': ' . $this->CleanValueString($mxValue) . QWFDRL_CRLF;
            
            }elseif ($mxValue === NULL){
                $this->strSerialized .= '/' . $this->GetKeyRepresentation($mxKey) .
                    '/u: NULL' . QWFDRL_CRLF;
                    
            }elseif (is_a($mxValue, 'CQBinaryData')){
                $this->strSerialized .= '/' . $this->GetKeyRepresentation($mxKey) .
                    ($mxValue->GetProcessing() == QWFDRL_POST_BASE64 ? '|base64' :
                    ($mxValue->GetProcessing() == QWFDRL_POST_HEX ? '|hex' : '')).
                    ': ' . $mxValue->Encode() . QWFDRL_CRLF;
                    
            }else{
                // we can't really store this, but we'll try something anyway
                $this->strSerialized .= '/??' . QWFDRL_CRLF;
            }
        }

        // postprocessing
        if ($nPostprocess & QWFDRL_POST_GZIP)
            $this->strSerialized = gzencode($this->strSerialized, 9);
        
        if ($nPostprocess & QWFDRL_POST_BASE64)
            $this->strSerialized = base64_encode($this->strSerialized);
        
        if ($nPostprocess & QWFDRL_POST_HEX && !($nPostprocess & QWFDRL_POST_BASE64))
            $this->strSerialized = bin2hex($this->strSerialized);
        
        return $this->strSerialized;
    }
    
    // for serialization
    function GetStoredSerialized()
    {
        return $this->strSerialized;
    }
    
    // for unserialization
    private function DecodeKeyString($strKey)
    {
        return str_replace(
            array("\\r", "\\n", "\\t", '\\/', '\\:', '\\"', "\\\\"),
            array("\r", "\n", "\t", '/', ':', '"', "\\"),
            $strKey
        );
    }
    
    // for unserialization
    private function DecodeValueString($strValue)
    {
        $strOut = '';
        $nInLen = strlen($strValue);
        
        for ($i=0; $i < $nInLen; $i++){
            if ($strValue[$i] == '\\'){
                if ($i+1 < $nInLen){
                    switch ($strValue[$i + 1])
                    {
                        case 'r': $strOut .= "\r"; $i++; break;
                        case 'n': $strOut .= "\n"; $i++; break;
                        default:{
                            $strOut .= $strValue[$i + 1];
                            $i++;
                        }
                    }
                }
            }else $strOut .= $strValue[$i];
        }
        
        return $strOut;
    }
    
    // for unserialization
    private function GetNextLine()
    {
        if ($this->nCursor >= $this->nLength - 1) return NULL;
        
        $arrResult = array(
            'identation' => 0,
            'key' => '',
            'keytype' => QWFDRL_STRING,
            'value' => NULL,
            'hasvalue' => false,
            'postprocess' => 0
        );
        
        // extract next line from text
        $y = $this->nCursor;
        while ($y < $this->nLength && $this->strSerialized[$y] != QWFDRL_LF)
            $y++;
        
        $strThisLine = '';
        
        if ($this->strSerialized[$y-1] == QWFDRL_CR)
            $strThisLine = substr($this->strSerialized, $this->nCursor, $y - $this->nCursor - 1);
        else $strThisLine = substr($this->strSerialized, $this->nCursor, $y - $this->nCursor);
        
        $this->nCursor = $y + 1;
        
        while ($this->nCursor < $this->nLength &&
            ($this->strSerialized[$this->nCursor] == QWFDRL_LF ||
            $this->strSerialized[$this->nCursor] == QWFDRL_CR))
        {
            $this->nCursor++;
        }
        
        // count spaces
        $nSpaces = 0;
        $nTabs = 0;
        $i = 0;
        $nLineLen = strlen($strThisLine);
        
        for ($i=0; $i < $nLineLen; $i++){
            if ($strThisLine[$i] == ' ') $nSpaces++;
            elseif ($strThisLine[$i] == QWFDRL_TAB) $nTabs++;
            else{
                $y = $i;
                $i = $nLineLen + 1; // break out
            }
        }
        
        $i = $y;
        $nSpaces += $nTabs * 4;
        
        if ($nSpaces % 4 != 0){
            $i -= $nSpaces % 4;
            $nSpaces = $i;
        }
        
        $arrResult['identation'] = $nSpaces / 4;
        
        if ($i >= $nLineLen){
            // blank line, treat as comment
            $arrResult['key'] = NULL;
            return $arrResult;
        }
        
        if ($strThisLine[$i] == '/'){
            // we have a simple key
            $nThisDataType = QWFDRL_STRING;
            
            if ($i+1 < $nLineLen){
                if ($strThisLine[$i+1] == '"'){
                    // special key name
                    $y = $i+2;
                    
                    $bContinue = true;
                    
                    while ($bContinue && $y < $nLineLen){
                        if ($strThisLine[$y] == '\\' && $y+1 < $nLineLen){
                            switch ($strThisLine[$y+1])
                            {
                                case 'n': $arrResult['key'] .= QWFDRL_LF; break;
                                case 'r': $arrResult['key'] .= QWFDRL_CR; break;
                                case 't': $arrResult['key'] .= QWFDRL_TAB; break;
                                default:{
                                    $arrResult['key'] .= $strThisLine[$y+1];
                                }
                            }
                            
                            $y++;
                        }elseif ($strThisLine[$y] == '"'){
                            $bContinue = false;
                        }else $arrResult['key'] .= $strThisLine[$y];
                        
                        $y++;
                    }
                    
                    $i = $y;
                }else{
                    // regular key name
                    $y = $i+1;
                    
                    $bContinue = true;
                    
                    while ($bContinue && $y < $nLineLen){
                        if ($strThisLine[$y] == '\\' && $y+1 < $nLineLen){
                            switch ($strThisLine[$y+1])
                            {
                                case 'n': $arrResult['key'] .= QWFDRL_LF; break;
                                case 'r': $arrResult['key'] .= QWFDRL_CR; break;
                                case 't': $arrResult['key'] .= QWFDRL_TAB; break;
                                default:{
                                    $arrResult['key'] .= $strThisLine[$y+1];
                                }
                            }
                            
                            $y++;
                        }elseif ($strThisLine[$y] == '/' || $strThisLine[$y] == '|' || $strThisLine[$y] == ':'){
                            $bContinue = false;
                        }else $arrResult['key'] .= $strThisLine[$y];
                        
                        $y++;
                    }
                    
                    $i = $y-1;
                }
                
                // check for flags
                $nConvertTo = QWFDRL_STRING;
                
                if ($strThisLine[$i] == '/'){
                    switch ($strThisLine[$i+1])
                    {
                        case 'n': $nConvertTo = QWFDRL_INT; break;
                        case 'f': $nConvertTo = QWFDRL_FLOAT; break;
                        case 'b': $nConvertTo = QWFDRL_BOOL; break;
                        case 'u': $nConvertTo = QWFDRL_NULL; break;
                        case ':': $i--; break;
                    }
                    
                    $i += 2;
                }
                
                if ($strThisLine[$i] == '|'){
                    $strTemp = '';
                    $i++;
                    
                    while ($strThisLine[$i] != ':' && $i < $nLineLen){
                        $strTemp .= $strThisLine[$i];
                        $i++;
                    }
                    
                    if ($strTemp == 'hex')
                        $arrResult['postprocess'] = QWFDRL_POST_HEX;
                    elseif ($strTemp == 'base64')
                        $arrResult['postprocess'] = QWFDRL_POST_BASE64;
                }
                
                $i += 2;
                
                // extract value
                if ($i < $nLineLen){
                    switch ($nConvertTo)
                    {
                        case QWFDRL_BOOL:{
                            if (substr($strThisLine, $i) == 'true')
                                $arrResult['value'] = true;
                            else $arrResult['value'] = false;
                        }break;
                            
                        case QWFDRL_FLOAT:{
                            $arrResult['value'] = (float)substr($strThisLine, $i);
                        }break;
                        
                        case QWFDRL_INT:{
                            $arrResult['value'] = (int)substr($strThisLine, $i);
                        }break;
                        
                        case QWFDRL_NULL:{
                            $arrResult['value'] = NULL;
                        }break;
                        
                        default:{
                            $arrResult['value'] = $this->DecodeValueString(substr($strThisLine, $i));
                        }
                    }
                }else{
                    switch ($nConvertTo)
                    {
                        case QWFDRL_BOOL:   $arrResult['value'] = false;    break;
                        case QWFDRL_FLOAT:  $arrResult['value'] = 0.0;      break;
                        case QWFDRL_INT:    $arrResult['value'] = 0;        break;
                        case QWFDRL_NULL:   $arrResult['value'] = NULL;     break;
                        default:            $arrResult['value'] = '';
                    }
                }
                
                $arrResult['hasvalue'] = true;
            }// else error
        }elseif ($strThisLine[$i] == '+'){
            // we have an array
            if ($i+2 < $nLineLen){
                if ($strThisLine[$i+2] == '"'){
                    // special key name
                    $y = $nLineLen - 1;
                    
                    while ($strThisLine[$y] != '"' && $y > 0) $y--;
                    
                    $arrResult['key'] = $this->DecodeKeyString(substr($strThisLine, $i+3, $y-$i-3));
                    $arrResult['keytype'] = (ctype_digit($arrResult['key']) ? QWFDRL_INT : QWFDRL_STRING);
                }else{
                    // regular key name
                    $arrResult['key'] = $this->DecodeKeyString(trim(substr($strThisLine, $i+2)));
                    $arrResult['keytype'] = (ctype_digit($arrResult['key']) ? QWFDRL_INT : QWFDRL_STRING);
                }
            }else{
                $arrResult['key'] = '';
                $arrResult['keytype'] = QWFDRL_INT;
            }
        }elseif ($strThisLine[$i] == '#'){
            // this is just a comment, we should ignore it
            $arrResult['key'] = NULL;
        }else{
            // we just have an extra text line
            $arrResult['key'] = NULL;
            $arrResult['value'] = $this->DecodeValueString(substr($strThisLine, $i));
            $arrResult['hasvalue'] = true;
        }
        
        return $arrResult;
    }

    // for unserialization
    function Unserialize($strData = NULL, $nPreprocess = 0)
    {
        if ($strData !== NULL) $this->strSerialized = $strData;
        
        // preprocessing
        if ($nPreprocess & QWFDRL_POST_BASE64)
            $this->strSerialized = base64_decode($this->strSerialized);
        
        if ($nPreprocess & QWFDRL_POST_HEX && !($nPreprocess & QWFDRL_POST_BASE64))
            $this->strSerialized = hex2bin($this->strSerialized);
        
        if ($nPreprocess & QWFDRL_POST_GZIP)
            $this->strSerialized = gzdecode($this->strSerialized);
        
        // let's go
        $this->nLength = strlen($this->strSerialized);
        $this->arrStructured = array();
        
        $arrPath = array();
        $arrPath[0] = &$this->arrStructured;
        
        $arrThisLine = array();
        
        $arrLastLine = NULL;
        $pLastElement = NULL;
        
        do {
            $arrThisLine = $this->GetNextLine();
            
            if ($arrThisLine !== NULL){
                if ($arrThisLine['key'] !== NULL){
                    if ($arrThisLine['hasvalue']){
                        // postprocess previous, if needed
                        if ($arrLastLine !== NULL && $arrLastLine['postprocess'] > 0 && $pLastElement !== NULL){
                            $kBinData = CQBinary($pLastElement, $arrLastLine['postprocess']);
                            $pLastElement = $kBinData->Decode();
                        }
                        
                        // key value pair
                        if (strlen($arrThisLine['key']) > 0){
                            $arrPath[$arrThisLine['identation']][$arrThisLine['key']] = $arrThisLine['value'];
                            $pLastElement = &$arrPath[$arrThisLine['identation']][$arrThisLine['key']];
                        }else{
                            $arrPath[$arrThisLine['identation']][] = $arrThisLine['value'];
                            
                            $nMax = -1;
                            foreach ($arrPath[$arrThisLine['identation']] as $mxKey => $ni)
                                if ((int)$mxKey > $nMax)
                                    $nMax = (int)$mxKey;
                                
                            $pLastElement = &$arrPath[$arrThisLine['identation']][$nMax];
                        }
                        
                        $nCount = count($arrPath);
                        for ($i = $arrThisLine['identation'] + 1; $i < $nCount; $i++)
                            unset($arrPath[$i]);
                        
                        $arrLastLine = $arrThisLine;
                    }else{
                        // postprocess previous, if needed
                        if ($arrLastLine !== NULL && $arrLastLine['postprocess'] > 0 && $pLastElement !== NULL){
                            $kBinData = CQBinary($pLastElement, $arrLastLine['postprocess']);
                            $pLastElement = $kBinData->Decode();
                        }
                        
                        // key for array
                        if (strlen($arrThisLine['key']) > 0){
                            $arrPath[$arrThisLine['identation']][$arrThisLine['key']] = array();
                            $arrPath[$arrThisLine['identation'] + 1] = &$arrPath[$arrThisLine['identation']][$arrThisLine['key']];
                            
                            $nCount = count($arrPath);
                            for ($i = $arrThisLine['identation'] + 2; $i < $nCount; $i++)
                                unset($arrPath[$i]);
                        }else{
                            $arrPath[$arrThisLine['identation']][] = array();
                            
                            $nMax = -1;
                            foreach ($arrPath[$arrThisLine['identation']] as $mxKey => $ni)
                                if ((int)$mxKey > $nMax)
                                    $nMax = (int)$mxKey;
                                
                            $arrPath[$arrThisLine['identation'] + 1] = &$arrPath[$arrThisLine['identation']][$nMax];
                            
                            $nCount = count($arrPath);
                            for ($i = $arrThisLine['identation'] + 2; $i < $nCount; $i++)
                                unset($arrPath[$i]);
                        }
                        
                        $arrLastLine = NULL;
                    }
                }else{
                    if ($arrThisLine['hasvalue']){
                        // extra value line for previous
                        if ($arrLastLine !== NULL && $pLastElement !== NULL){
                            if ($arrThisLine['identation'] == $arrLastLine['identation'] + 1)
                                $pLastElement .= $arrThisLine['value'];
                            else $arrLastLine = NULL;
                        }
                    }else{
                        // else comment, we ignore
                        // but first, postprocess previous, if needed
                        if ($arrLastLine !== NULL && $arrLastLine['postprocess'] > 0 && $pLastElement !== NULL){
                            $kBinData = CQBinary($pLastElement, $arrLastLine['postprocess']);
                            $pLastElement = $kBinData->Decode();
                        }
                        
                        $arrLastLine = NULL;
                    }
                }
            }
        }while($arrThisLine !== NULL);
        
        // postprocess last element, if needed
        if ($arrLastLine !== NULL && $arrLastLine['postprocess'] > 0 && $pLastElement !== NULL){
            $kBinData = CQBinary($pLastElement, $arrLastLine['postprocess']);
            $pLastElement = $kBinData->Decode();
        }
        
        return $this->arrStructured;
    }

    // for unserialization
    function GetStoredUnserialized()
    {
        return $this->arrStructured;
    }
    
    static function GetBranches($arrInput)
    {
        if (!is_array($arrInput)) return array();
        
        $arrRet = array();
        foreach ($arrInput as $strKey => $mxValue)
            if (is_array($mxValue))
                $arrRet[] = $strKey;
            
        return $arrRet;
    }
    
    static function GetAttributes($arrInput)
    {
        if (!is_array($arrInput)) return array();
        
        $arrRet = array();
        foreach ($arrInput as $strKey => $mxValue)
            if (!is_array($mxValue))
                $arrRet[$strKey] = $mxValue;
            
        return $arrRet;
    }
    
    static function GetAttribute($arrInput, $mxKey, $mxDefault = '')
    {
        if (!is_array($arrInput)) return array();
        
        if (!is_array($arrInput[$mxKey])){
            if (isset($arrInput[$mxKey]))
                return $arrInput[$mxKey];
            else return $mxDefault;
        }else return NULL;
    }
}

} // endif defined
