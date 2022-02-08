<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'idxuser' => -1
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 2){
        $arrUniversitate = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati',
            array('idxauth', '=', (int)$arrUser['idx']));
        
        if (is_array($arrUniversitate) && !empty($arrUniversitate)){
            $arrUniversitate = $arrUniversitate[0];
            
            $this->DATA['nume'] = $arrUniversitate['nume'];
            $this->DATA['oras'] = $arrUniversitate['oras'];
            $this->DATA['reprezentant'] = $arrUniversitate['reprezentant'];
            $this->DATA['gradacces'] = $arrUniversitate['gradacces'];
            $this->DATA['gradechipare'] = $arrUniversitate['gradechipare'];
            $this->DATA['studdiz'] = $arrUniversitate['studdiz'];
            $this->DATA['studcentru'] = $arrUniversitate['studcentru'];
            $this->DATA['camerecamine'] = $arrUniversitate['camerecamine'];
            $this->DATA['persdedic'] = $arrUniversitate['persdedic'];
            $this->DATA['cazare'] = $arrUniversitate['cazare'];
            $this->DATA['costuri'] = $arrUniversitate['costuri'];
            $this->DATA['email'] = $arrUser['username'];
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip universitate !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
