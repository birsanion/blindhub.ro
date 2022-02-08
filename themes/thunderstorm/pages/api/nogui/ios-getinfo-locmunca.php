<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'detalii' => array()
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrInfo = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locurimunca',
        array('idx', '=', (int)POST('idx')));
        
    if (is_array($arrInfo) && !empty($arrInfo)){
        $this->DATA['detalii'] = $arrInfo[0];
    }else $this->DATA['result'] = 'EROARE: acest loc de muncă nu are informații !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);

