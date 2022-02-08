<?php

class CQEmailReader
{
    // imap server connection
    private $hConn;
     
    // inbox storage and inbox message count
    private $arrInbox;
    private $nMessages;
     
    // email login credentials
    private $pcServer;
    private $pcUser;
    private $pcPass;
    private $nPort;
    private $arrConnFlags;
    
    private $bOpenedConn;
    private $arrProfiling;
     
    // connect to the server and get the inbox emails
    function __construct()
    {
        $this->hConn=NULL;
        $this->arrInbox=array();
        $this->nMessages=0;
        $this->pcServer='';
        $this->pcUser='';
        $this->pcPass='';
        $this->nPort=0;
        $this->arrConnFlags=array();
        $this->bOpenedConn=false;
        $this->arrProfiling=array();
    }
    
    // static function for sorting mails based on date
    static function mailsort($a, $b)
    {
        return ($a['header']->udate == $b['header']->udate ? 0 : (
            $a['header']->udate < $b['header']->udate ? -1 : 1));
    }
    
    // open a new connection
    function Open($pcServer, $nPort, $pcUser, $pcPass, $arrFlags, $pcFolder='INBOX')
    {
        $nProfilingStart=microtime(true);
        
        $this->hConn = imap_open('{'.$pcServer.':'.$nPort.
            (!empty($arrFlags) && is_array($arrFlags) ? '/'.implode('/', $arrFlags) : '').'}'.
            $pcFolder, $pcUser, $pcPass);
        
        $nProfilingStop=microtime(true);
        $this->arrProfiling[]='Open() = '.($nProfilingStop-$nProfilingStart);
        
        if ($this->hConn !== FALSE){
            $this->pcServer=$pcServer;
            $this->pcUser=$pcUser;
            $this->pcPass=$pcPass;
            $this->nPort=$nPort;
            $this->arrConnFlags=$arrFlags;
            
            $this->bOpenedConn=true;
            
            return true;
        }
        
        return false;
    }
     
    // close the server connection
    function Close()
    {
        $nProfilingStart=microtime(true);
        
        if (($this->bOpenedConn && imap_close($this->hConn)) || !$this->bOpenedConn){
            $this->hConn=NULL;
            $this->arrInbox=array();
            $this->nMessages=0;
            $this->pcServer='';
            $this->pcUser='';
            $this->pcPass='';
            $this->nPort=0;
            $this->arrConnFlags=array();
            $this->bOpenedConn=false;
            
            $nProfilingStop=microtime(true);
            $this->arrProfiling[]='Close() = '.($nProfilingStop-$nProfilingStart);
            
            return true;
        }
        
        $nProfilingStop=microtime(true);
        $this->arrProfiling[]='Close() = '.($nProfilingStop-$nProfilingStart);

        return false;
    }
    
    function ListFolders($pcFolder, $pcPattern='*')
    {
        return imap_list($this->hConn, '{'.$this->pcServer.':'.$this->nPort.
            (!empty($this->arrConnFlags) && is_array($this->arrConnFlags) ?
                '/'.implode('/', $this->arrConnFlags) : '').'}'.$pcFolder, $pcPattern);
    }
    
    function GetNumberOfMailsServer()
    {
        $nProfilingStart=microtime(true);
        
        $kCheckInfo=imap_check($this->hConn);
        $this->nMessages = $kCheckInfo->Nmsgs; //imap_num_msg($this->hConn);
        
        $nProfilingStop=microtime(true);
        $this->arrProfiling[]='GetNumberOfMailsServer() = '.($nProfilingStop-$nProfilingStart);
        
        return $this->nMessages;
    }
    
    function GetNumberOfMailsHere()
    {
        return count($this->arrInbox);
    }
    
    function RetrieveBasicInfoMails()
    {
        $nProfilingStart=microtime(true);
        
        $kCheckInfo=imap_check($this->hConn);
        $this->nMessages = $kCheckInfo->Nmsgs; //imap_num_msg($this->hConn);
         
        $this->arrInbox = array();
        
        for ($i=1; $i<=$this->nMessages; $i++){
            $this->arrInbox[] = array(
                'index' => $i,
                'header' => imap_headerinfo($this->hConn, $i),
                'uid'   => imap_uid($this->hConn, $i)
            );
        }
        
        // sort mails by date ascending
        usort($this->arrInbox, array('CQEmailReader', 'mailsort'));
        
        $nProfilingStop=microtime(true);
        $this->arrProfiling[]='RetrieveBasicInfoMails() = '.($nProfilingStop-$nProfilingStart);
    }
    
    function RetrieveAdvInfoMails()
    {
        $nProfilingStart=microtime(true);
        
        $kCheckInfo=imap_check($this->hConn);
        $this->nMessages = $kCheckInfo->Nmsgs; //imap_num_msg($this->hConn);
         
        $this->arrInbox = array();
        
        for ($i=1; $i<=$this->nMessages; $i++){
            $this->arrInbox[] = array(
                'index'     => $i,
                'header'    => imap_headerinfo($this->hConn, $i),
                'uid'       => imap_uid($this->hConn, $i),
                'body'      => imap_body($this->hConn, $i),
                'structure' => imap_fetchstructure($this->hConn, $i)
            );
        }
        
        $nProfilingStop=microtime(true);
        $this->arrProfiling[]='RetrieveAdvInfoMails() = '.($nProfilingStop-$nProfilingStart);
    }
    
    function GetMessage($n)
    {
        if ($n<0 || $n>=count($this->arrInbox)) return array();
        
        return $this->arrInbox[$n];
    }
    
    function GetProfilingInfo($bAsString=true)
    {
        if ($bAsString) return implode("\r\n", $this->arrProfiling);
        else return $this->arrProfiling;
    }
    
    function ClearProfilingInfo()
    {
        $this->arrProfiling=array();
    }
    
    function SaveMailsToJSONFile($pcFile)
    {
        file_put_contents($pcFile, json_encode($this->arrInbox));
    }
    
    function SaveMailsToSerializedFile($pcFile)
    {
        file_put_contents($pcFile, serialize($this->arrInbox));
    }
    
    function LoadMailsFromJSONFile($pcFile)
    {
        if (!$this->bOpenedConn){
            $this->arrInbox=json_decode(file_get_contents($pcFile));
            return true;
        }
        
        return false;
    }
    
    function LoadMailsFromSerializedFile($pcFile)
    {
        if (!$this->bOpenedConn){
            $this->arrInbox=unserialize(file_get_contents($pcFile));
            return true;
        }
        
        return false;
    }
    
    function MoveEmail($nIndex, $pcFolder)
    {
        if ($this->bOpenedConn){
            if (imap_mail_move($this->hConn, (string)$nIndex, $pcFolder, CP_UID)){
                if (imap_expunge($this->hConn)){
                    return true;
                }
            }
        }
        
        return false;
    }
    
    function MoveEmailByCopy($nIndex, $pcFolder)
    {
        if ($this->bOpenedConn){
            if (imap_mail_copy($this->hConn, (string)$nIndex, $pcFolder, CP_UID)){
                if (imap_delete($this->hConn, (string)$nIndex, FT_UID)){
                    if (imap_expunge($this->hConn)){
                        return 'suc - ';
                    }else return 'err exp - ';
                }else return 'err del - ';
            }else return 'err cpy - ';
        }else return 'err con - ';
        
        return 'unk - ';
    }
}

?>