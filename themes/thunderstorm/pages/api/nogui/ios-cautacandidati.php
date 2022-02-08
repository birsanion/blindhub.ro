<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'nrlocuri' => 0,
    'locuri' => array(),
    'debug' => ''
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 1){
        $arrCereri = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'cereriinterviu',
            array('idxauthangajator', '=', $arrUser['idx']));
            
        if (is_array($arrCereri) && !empty($arrCereri)){
            foreach ($arrCereri as $arrCerere){
                $arrLoc = $this->DATABASE->RunQuickSelect(array('idx', 'titlu'), SYSCFG_DB_PREFIX . 'locurimunca',
                    array('idx', '=', (int)$arrCerere['idxlocmunca'])
                );
                
                $arrAngajat = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati',
                    array('idxauth', '=', (int)$arrCerere['idxauthangajat']));
                    
                if (is_array($arrAngajat) && !empty($arrAngajat) && is_array($arrLoc) && !empty($arrLoc)){
                    $arrAngajat = $arrAngajat[0];
                    $arrLoc = $arrLoc[0];
                    
                    $strNumeSiPrenume = $arrAngajat['nume'] . ' ' . $arrAngajat['prenume'];
                    $strGradHandicap = $arrAngajat['gradhandicap'];
                    $strNevoi = $arrAngajat['nevoispecifice'];
                    
                    $arrDataCV = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati_cv',
                        array('idxauth', '=', (int)$arrCerere['idxauthangajat']));
                        
                    $strDomenii = (is_array($arrDataCV) && !empty($arrDataCV) ? $arrDataCV[0]['domenii'] : '');
                    $strOras = (is_array($arrDataCV) && !empty($arrDataCV) ? $arrDataCV[0]['oras'] : '');
                    
                    $this->DATA['locuri'][] = array(
                        'nume' => $strNumeSiPrenume,
                        'gradhandicap' => 'grad de handicap ' . $strGradHandicap,
                        'nevoispecifice' => 'nevoi specifice: ' . $strNevoi,
                        'idxauthnevazator' => (int)$arrCerere['idxauthangajat'],
                        'domenii' => $strDomenii,
                        'oras' => $strOras,
                        'locmunca' => $arrLoc
                    );
                    
                    $this->DATA['nrlocuri']++;
                }
            }
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
