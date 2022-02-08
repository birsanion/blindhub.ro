<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'nrlocuri' => 0,
    'locuri' => array(),
    'debug' => ''
);
/*
$arrDomeniiReverseMap = array(
    'it' => 'IT',
    'medical' => 'Medical',
    'callcenter' => 'Call center',
    'resurseumane' => 'Resurse umane',
    'asistentasociala' => 'Asistență socială',
    'jurnalism' => 'Jurnalism și relații publice',
    'radio' => 'Radio',
    'psihologie' => 'Psihologie consiliere coaching',
    'educatie' => 'Educație și training',
    'artistica' => 'Industria creativă și artistică',
    'administratie' => 'Administrație publică și instituții',
    'desk' => 'Desk office',
    'wellness' => 'Wellness și SPA',
    'traducator' => 'Traducător / translator',
    'diverse' => 'Diverse'
);
*/

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 2){
        $arrCereri = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate',
            array('idxauthuniversitate', '=', $arrUser['idx']));
            
        if (is_array($arrCereri) && !empty($arrCereri)){
            foreach ($arrCereri as $arrCerere){
                $arrLoc = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locuriuniversitate',
                    array('idx', '=', (int)$arrCerere['idxlocuniversitate'])
                );
                
                $arrAngajat = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati',
                    array('idxauth', '=', (int)$arrCerere['idxauthangajat']));
                    
                if (is_array($arrAngajat) && !empty($arrAngajat) && is_array($arrLoc) && !empty($arrLoc)){
                    $arrAngajat = $arrAngajat[0];
                    $arrLoc = $arrLoc[0];
                    
                    $strNumeSiPrenume = $arrAngajat['nume'] . ' ' . $arrAngajat['prenume'];
                    $strGradHandicap = $arrAngajat['gradhandicap'];
                    $strNevoi = $arrAngajat['nevoispecifice'];
                    
                    $this->DATA['locuri'][] = array(
                        'nume' => $strNumeSiPrenume,
                        'gradhandicap' => 'grad de handicap ' . $strGradHandicap,
                        'nevoispecifice' => 'nevoi specifice: ' . $strNevoi,
                        'idxauthnevazator' => (int)$arrCerere['idxauthangajat'],
                        'locuniversitate' => $arrLoc
                    );
                    
                    $this->DATA['nrlocuri']++;
                }
            }
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip universitate !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
