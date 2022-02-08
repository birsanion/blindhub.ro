<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'nrlocuri' => 0,
    'debug' => ''
);

$strUserKey = POST('userkey', GET('userkey', PARAM(2)));

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', $strUserKey));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];

    if ((int)$arrUser['tiputilizator'] == 1){
        if (isset($_FILES['uploaded_file']['name'])){
            if (file_exists('media/uploads/angajator_' . (int)$arrUser['idx'] .
                substr($_FILES['uploaded_file']['name'], strrpos($_FILES['uploaded_file']['name'], '.'))))
            {
                unlink('media/uploads/angajator_' . (int)$arrUser['idx'] .
                    substr($_FILES['uploaded_file']['name'], strrpos($_FILES['uploaded_file']['name'], '.')));
            }

            move_uploaded_file($_FILES['uploaded_file']['tmp_name'],
                'media/uploads/angajator_' . (int)$arrUser['idx'] .
                substr($_FILES['uploaded_file']['name'], strrpos($_FILES['uploaded_file']['name'], '.')));
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
