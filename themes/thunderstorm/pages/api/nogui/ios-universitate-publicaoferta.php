<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);
/*
$arrDomeniiMap = array(
    'IT' => 'it',
    'Medical' => 'medical',
    'Call center' => 'callcenter',
    'Resurse umane' => 'resurseumane',
    'Asistență socială' => 'asistentasociala',
    'Jurnalism și relații publice' => 'jurnalism',
    'Radio' => 'radio',
    'Psihologie, consiliere, coaching' => 'psihologie',
    'Educație și training' => 'educatie',
    'Industria creativă și artistică' => 'artistica',
    'Administrație publică și instituții' => 'administratie',
    'Desk office' => 'desk',
    'Wellness și SPA' => 'wellness',
    'Traducător / translator' => 'traducator',
    'Diverse' => 'diverse'
);
*/

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 2){
        // insert
        if (!$this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'locuriuniversitate',
            'idxauth, facultate, domeniu, numarlocuri, oras',
            array(array(
                'idxauth' => (int)$arrUser['idx'],
                'facultate' => POST('nume'),
                'domeniu' => POST('domeniu'),
                'numarlocuri' => (int)POST('nrlocuri'),
                'oras' => POST('oras')
            ))
        ))
        {
            $this->DATA['result'] = 'EROARE: nu s-au putut introduce informatiile in baza de date !';
            $this->DATA['reason'] = $this->DATABASE->GetError();
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip universitate !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
