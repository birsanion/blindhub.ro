<?php

function MySQLDate_to_RomanianDate($strDate)
{
    // 0123-56-89 12:45:78
    return substr($strDate, 8, 2) . '/' . substr($strDate, 5, 2) . '/' .
        substr($strDate, 0, 4) . substr($strDate, 10, 6);
}

function MySQLDate_to_Seconds($strDate)
{
    // 0123-56-89 11:14:17
    return mktime(
        (int)substr($strDate, 11, 2),
        (int)substr($strDate, 14, 2),
        (int)substr($strDate, 17, 2),
        (int)substr($strDate, 5, 2),
        (int)substr($strDate, 8, 2),
        (int)substr($strDate, 0, 4)
    );
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
    
    if ((int)$arrUser['tiputilizator'] == 0){
        $arrInterviuri = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'interviuri',
            array(
                array('idxauthangajat', '=', $arrUser['idx'], 'AND'),
                array('tstamp', '>=', date('Y-m-d') . ' 00:00:00')
            ),
            'tstamp'
        );
        
        if (is_array($arrInterviuri) && !empty($arrInterviuri)){
            foreach ($arrInterviuri as $arrInterviu){
                $arrAngajat = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori',
                    array('idxauth', '=', (int)$arrInterviu['idxauthangajator']));
                
                //////////////
                $nExistaLocMunca = 0;
                $strTitluLocMunca = '';
                $strOrasLocMunca = '';
                $strDomeniuLocMunca = '';
                
                if ((int)$arrInterviu['idxobject'] > 0){
                    $arrLocMunca = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locurimunca',
                        array('idx', '=', (int)$arrInterviu['idxobject']));
                        
                    if (is_array($arrLocMunca) && !empty($arrLocMunca)){
                        $nExistaLocMunca = 1;
                        $strTitluLocMunca = $arrLocMunca[0]['titlu'];
                        $strOrasLocMunca = $arrLocMunca[0]['oras'];
                        $strDomeniuLocMunca = $arrLocMunca[0]['domeniu'];
                    }
                }
                
                if (is_array($arrAngajat) && !empty($arrAngajat)){
                    $arrAngajat = $arrAngajat[0];
                    
                    $strNumeSiPrenume = $arrAngajat['companie'];
                    $strFirmaProtejata = ($arrAngajat['firmaprotejata'] == 'nu' ? 'nu este firmă protejata' : 'este firmă protejata');
                    $strDimensiune = ($arrAngajat['dimensiunefirma'] == 'peste50' ? 'firmă peste 50 de angajați' : 'firmă sub 50 de angajați');
                    
                    $this->DATA['locuri'][] = array(
                        'nume' => $strNumeSiPrenume,
                        'dataora' => MySQLDate_to_RomanianDate($arrInterviu['tstamp']),
                        'tstampsecunde' => MySQLDate_to_Seconds($arrInterviu['tstamp']),
                        
                        'existalocmunca' => $nExistaLocMunca,
                        'titlulocmunca' => $strTitluLocMunca,
                        'oraslocmunca' => $strOrasLocMunca,
                        'domeniulocmunca' => $strDomeniuLocMunca,
                        
                        'vonagesessid' => $arrInterviu['vonagesessid'],
                        'token' => $arrInterviu['vonagenevaztoken']
                    );
                    
                    $this->DATA['nrlocuri']++;
                }
            }
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
