<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);

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

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 2){
        // insert
        $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'locuriuniversitate',
            'idxauth, facultate, domeniu, numarlocuri',
            array(array(
                'idxauth' => (int)$arrUser['idx'],
                'facultate' => POST('nume'),
                'domeniu' => $arrDomeniiMap[POST('domeniu')],
                'numarlocuri' => (int)POST('nrlocuri')
            ))
        );
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip universitate !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';


