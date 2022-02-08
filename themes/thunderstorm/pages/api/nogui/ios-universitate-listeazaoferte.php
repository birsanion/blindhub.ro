<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'nrlocuri' => 0,
    'locuri' => array()
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 2){
        $arrData = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locuriuniversitate',
            array('idxauth', '=', (int)$arrUser['idx']));
        
        if (is_array($arrData) && !empty($arrData)){
            $this->DATA['locuri'] = $arrData;
            $this->DATA['nrlocuri'] = count($arrData);
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip universitate !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
