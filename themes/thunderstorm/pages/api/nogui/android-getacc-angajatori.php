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
    
    if ((int)$arrUser['tiputilizator'] == 1){
        $arrAngajator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori',
            array('idxauth', '=', (int)$arrUser['idx']));
        
        if (is_array($arrAngajator) && !empty($arrAngajator)){
            $arrAngajator = $arrAngajator[0];
            
            $this->DATA['companie'] = $arrAngajator['companie'];
            $this->DATA['adresa'] = $arrAngajator['adresa'];
            $this->DATA['cui'] = $arrAngajator['cui'];
            $this->DATA['firmaprotejata'] = $arrAngajator['firmaprotejata'];
            $this->DATA['dimensiunefirma'] = $arrAngajator['dimensiunefirma'];
            $this->DATA['tipslujba'] = $arrAngajator['tipslujba'];
            $this->DATA['domenii'] = $arrAngajator['domenii'];
            $this->DATA['orase'] = $arrAngajator['orase'];
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';
