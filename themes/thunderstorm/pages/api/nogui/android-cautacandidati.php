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
            $arrGrupate = array();
            
            foreach ($arrCereri as $arrCerere){
                if (!isset($arrGrupate[(int)$arrCerere['idxauthangajat']]))
                    $arrGrupate[(int)$arrCerere['idxauthangajat']] = array();
                
                $arrGrupate[(int)$arrCerere['idxauthangajat']][] = (int)$arrCerere['idxlocmunca'];
            }
            
            foreach ($arrGrupate as $nIdxAngajat => $arrLocuriMunca){
                $arrAngajat = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati',
                    array('idxauth', '=', $nIdxAngajat));
                    
                if (is_array($arrAngajat) && !empty($arrAngajat)){
                    $arrAngajat = $arrAngajat[0];
                    
                    $strNumeSiPrenume = $arrAngajat['nume'] . ' ' . $arrAngajat['prenume'];
                    $strGradHandicap = $arrAngajat['gradhandicap'];
                    $strNevoi = $arrAngajat['nevoispecifice'];
                    $arrSlujbe = array();
                    
                    foreach ($arrLocuriMunca as $nIdxLocMunca){
                        $arrLocMunca = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locurimunca',
                            array('idx', '=', $nIdxLocMunca));
                        
                        if (is_array($arrLocMunca) && !empty($arrLocMunca))
                            $arrSlujbe[] = $arrLocMunca[0]['titlu'];
                    }
                    
                    $this->DATA['locuri'][] = array(
                        'nume' => $strNumeSiPrenume,
                        'gradhandicap' => 'grad de handicap ' . $strGradHandicap,
                        'nevoispecifice' => 'nevoi specifice: ' . $strNevoi,
                        'slujbe' => implode(', ', $arrSlujbe),
                        'idxauthnevazator' => $nIdxAngajat
                    );
                    
                    $this->DATA['nrlocuri']++;
                }
            }
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';
