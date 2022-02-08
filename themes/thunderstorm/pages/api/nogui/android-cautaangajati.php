<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'nrlocuri' => 0,
    'debug' => ''
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 1){
        $arrAngajator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori',
            array('idxauth', '=', (int)$arrUser['idx']));
        
        if (is_array($arrAngajator) && count($arrAngajator) > 0){
            $arrAngajator = $arrAngajator[0];
            
            $strAdvQuery = "SELECT * FROM `" . SYSCFG_DB_PREFIX . 'angajati_cv` WHERE (';
            
            $arrDomenii = explode('|', $arrAngajator['domenii']);
            $nDomenii = count($arrDomenii);
            
            for ($i=0; $i < $nDomenii; $i++)
                $arrDomenii[$i] = '`domenii` LIKE \'%' . $this->DATABASE->CleanString($arrDomenii[$i]) . '%\'';
                
            $strAdvQuery .= implode(' OR ', $arrDomenii);
            $strAdvQuery .= ') AND (';
            
            $arrOrase = explode('|', $arrAngajator['orase']);
            $nOrase = count($arrOrase);
            
            for ($i=0; $i < $nOrase; $i++)
                $arrOrase[$i] = '`oras` = \'' . $this->DATABASE->CleanString($arrOrase[$i]) . '\'';
            
            $strAdvQuery .= implode(' OR ', $arrDomenii);
            $strAdvQuery .= ')';
            
            //$this->DATA['debug'] = $strAdvQuery;
            
            $arrRezultate = $this->DATABASE->RunQuery($strAdvQuery);
            
            if (is_array($arrRezultate) && count($arrRezultate) > 0){
                $this->DATA['nrlocuri'] = count($arrRezultate);
                $this->DATA['locuri'] = array();
                
                foreach ($arrRezultate as $arrRezultat){
                    $arrAngajat = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati',
                        array('idxauth', '=', (int)$arrRezultat['idxauth']));
                    
                    if (is_array($arrAngajat) && count($arrAngajat) > 0){
                        $arrAngajat = $arrAngajat[0];
                        
                        $this->DATA['locuri'][] = array(
                            'nume' => $arrAngajat['nume'] . ' ' . $arrAngajat['prenume'],
                            'gradhandicap' => 'grad de handicap ' . $arrAngajat['gradhandicap'],
                            'nevoispecifice' => 'nevoi specifice: ' . $arrAngajat['nevoispecifice'],
                            
                            'idxauth' => (int)$arrRezultat['idxauth']
                        );
                    }else $this->DATA['nrlocuri']--;
                }
            }else{
                $this->DATA['nrlocuri'] = 0;
                $this->DATA['locuri'] = array();
            }
        }else $this->DATA['result'] = 'EROARE: nu aveți completat profilul !';
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';


