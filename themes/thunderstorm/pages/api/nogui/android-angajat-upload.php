<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'debug' => ''
);

$strUserKey = POST('userkey', GET('userkey', PARAM(2)));

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', $strUserKey));
    
$this->LOG->Log('upload', 'strUserKey: ' . $strUserKey . "\r\n" . 
    print_r($_POST, true) . "\r\n\r\n" . print_r($_GET, true) . "\r\n\r\n" .
    print_r($_FILES, true) . "\r\n\r\n" .
    $_SERVER['REQUEST_URI']);

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 0){
        if (isset($_FILES['uploaded_file']['name'])){
            if (file_exists('media/uploads/nevazator_cv_' . (int)$arrUser['idx'] .
                strtolower(substr($_FILES['uploaded_file']['name'], strrpos($_FILES['uploaded_file']['name'], '.')))))
            {
                unlink('media/uploads/nevazator_cv_' . (int)$arrUser['idx'] .
                    strtolower(substr($_FILES['uploaded_file']['name'], strrpos($_FILES['uploaded_file']['name'], '.'))));
            }
            
            if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'],
                'media/uploads/nevazator_cv_' . (int)$arrUser['idx'] .
                strtolower(substr($_FILES['uploaded_file']['name'], strrpos($_FILES['uploaded_file']['name'], '.')))))
            {
                $this->DATA['result'] = 'EROARE: Nu s-a putut muta fișierul în directorul final !';
            }
        }else $this->DATA['result'] = 'EROARE: nu ați încărcat fișierul !';
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip nevăzător !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
