<?php

function RomanianDate_to_MySQLDate($pcDate)
{
    return substr($pcDate,6,4).'-'.substr($pcDate,3,2).'-'.substr($pcDate,0,2).
        (strlen($pcDate)>10 ? ' '.substr($pcDate, 11) : '');
}

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 1){
        // insert
        $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'locurimunca',
            'domeniu, oras, competente, titlu, descriere, expirare, tipslujba',
            array(
                'domeniu' => POST('domeniu'),
                'oras' => POST('oras'),
                'competente' => POST('competente'),
                'titlu' => POST('titlu'),
                'descriere' => POST('descriere'),
                'expirare' => RomanianDate_to_MySQLDate(POST('expirare')),
                'tipslujba' => POST('tipslujba')
            ),
            array(
                array('idx', '=', (int)POST('idxloc'), 'AND'),
                array('idxauth', '=', (int)$arrUser['idx'])
            )
        );
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
