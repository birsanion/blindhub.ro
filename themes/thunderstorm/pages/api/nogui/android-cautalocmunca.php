<?php

////////////////////////////////
function GetTimeDifferenceFromNow($strPastDate)
{
    // 0123-56-89
    $nPast = mktime(0, 0, 0, (int)substr($strPastDate, 5, 2),
        (int)substr($strPastDate, 8, 2), (int)substr($strPastDate, 0, 4));
    
    return floor((time() - $nPast) / 86400);
}
////////////////////////////////

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
    
    if ((int)$arrUser['tiputilizator'] == 0){
        $arrCVNevazator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati_cv',
            array('idxauth', '=', (int)$arrUser['idx']));
        
        if (is_array($arrCVNevazator) && count($arrCVNevazator) > 0){
            $arrCVNevazator = $arrCVNevazator[0];
            
            $strAdvQuery = "SELECT * FROM `" . SYSCFG_DB_PREFIX . 'locurimunca` WHERE (';
            
            $arrOrase = explode('|', $arrCVNevazator['domenii']);
            $nOrase = count($arrOrase);
            
            for ($i=0; $i < $nOrase; $i++)
                $arrOrase[$i] = '`domeniu` = \'' . $this->DATABASE->CleanString($arrOrase[$i]) . '\'';
                
            $strAdvQuery .= implode(' OR ', $arrOrase);
            $strAdvQuery .= ') AND `oras` = \'' . $arrCVNevazator['oras'] . '\' AND `expirare` >= \'' .
                date('Y-m-d') . '\'';
                
            //$this->DATA['debug'] = $strAdvQuery;
            
            $arrRezultate = $this->DATABASE->RunQuery($strAdvQuery);
            
            if (is_array($arrRezultate) && count($arrRezultate) > 0){
                $this->DATA['nrlocuri'] = count($arrRezultate);
                $this->DATA['locuri'] = array();
                
                foreach ($arrRezultate as $arrRezultat){
                    $arrAngajator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori',
                        array('idxauth', '=', (int)$arrRezultat['idxauth']));
                    
                    if (is_array($arrAngajator) && count($arrAngajator) > 0){
                        $arrAngajator = $arrAngajator[0];
                        $nTimeDiff = GetTimeDifferenceFromNow($arrRezultat['datapostare']);
                        
                        $this->DATA['locuri'][] = array(
                            'nume' => $arrAngajator['companie'],
                            'firmaprotejata' => ($arrAngajator['firmaprotejata'] == 'da' ?
                                'este firmă protejată' : 'nu este firmă protejată'),
                            
                            'dimensiunefirma' => ($arrAngajator['dimensiunefirma'] == 'peste50' ?
                                'are peste 50 de angajați' : 'are sub 50 de angajați'),
                            
                            'vechimeanunt' => 'anunț postat ' .
                                ($nTimeDiff <= 0 ? 'astăzi' :
                                    ($nTimeDiff <= 1 ? ' acum o zi' :
                                        ($nTimeDiff <= 19 ? 'acum ' . $nTimeDiff . ' zile' :
                                            'acum ' . $nTimeDiff . ' de zile'))),
                            
                            'idxlocmunca' => (int)$arrRezultat['idx'],
                            'idxauth' => (int)$arrAngajator['idxauth']
                        );
                    }else $this->DATA['nrlocuri']--;
                }
            }else{
                $this->DATA['nrlocuri'] = 0;
                $this->DATA['locuri'] = array();
            }
        }else $this->DATA['result'] = 'EROARE: nu aveți completat CV-ul !';
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';


