<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 2){
        // insert
        if (!$this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'locuriuniversitate',
            'facultate, domeniu, numarlocuri, oras',
            array(
                'facultate' => POST('nume'),
                'domeniu' => POST('domeniu'),
                'numarlocuri' => (int)POST('nrlocuri'),
                'oras' => POST('oras')
            ),
            array(
                array('idx', '=', (int)POST('idxloc'), 'AND'),
                array('idxauth', '=', (int)$arrUser['idx'])
            )
        ))
        {
            $this->DATA['result'] = 'EROARE: nu s-au putut modifica informatiile in baza de date !';
            $this->DATA['reason'] = $this->DATABASE->GetError();
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip universitate !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
