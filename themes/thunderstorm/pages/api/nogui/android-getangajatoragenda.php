<?php

function MySQLDate_to_RomanianDate($strDate)
{
    // 0123-56-89 11:14:17
    return substr($strDate, 8, 2) . '/' . substr($strDate, 5, 2) . '/' .
        substr($strDate, 0, 4) . substr($strDate, 10, 6);
}


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
        $arrInterviuri = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'interviuri',
            array(
                array('idxauthangajator', '=', $arrUser['idx'], 'AND'),
                array('tstamp', '>=', date('Y-m-d') . ' 00:00:00')
            ),
            'tstamp'
        );
        
        if (is_array($arrInterviuri) && !empty($arrInterviuri)){
            foreach ($arrInterviuri as $arrInterviu){
                $arrAngajat = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati',
                    array('idxauth', '=', (int)$arrInterviu['idxauthangajat']));
                    
                if (is_array($arrAngajat) && !empty($arrAngajat)){
                    $arrAngajat = $arrAngajat[0];
                    
                    $strNumeSiPrenume = $arrAngajat['nume'] . ' ' . $arrAngajat['prenume'];
                    $strGradHandicap = $arrAngajat['gradhandicap'];
                    $strNevoi = $arrAngajat['nevoispecifice'];
                    
                    $this->DATA['locuri'][] = array(
                        'nume' => $strNumeSiPrenume,
                        'gradhandicap' => 'grad de handicap ' . $strGradHandicap,
                        'nevoispecifice' => 'nevoi specifice: ' . $strNevoi,
                        'dataora' => MySQLDate_to_RomanianDate($arrInterviu['tstamp']),
                        'idxauthnevazator' => (int)$arrInterviu['idxauthangajat']
                    );
                    
                    $this->DATA['nrlocuri']++;
                }
            }
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';
