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
            
            $strAdvQuery = "SELECT * FROM `" . SYSCFG_DB_PREFIX . 'angajatori` WHERE (';
            
            $arrDomenii = explode('|', $arrCVNevazator['domenii']);
            $nDomenii = count($arrDomenii);
            
            for ($i=0; $i < $nDomenii; $i++)
                $arrDomenii[$i] = '`domenii` LIKE \'%' . $this->DATABASE->CleanString($arrDomenii[$i]) . '%\'';
                
            $strAdvQuery .= implode(' OR ', $arrDomenii);
            $strAdvQuery .= ') AND `orase` LIKE \'%' . $this->DATABASE->CleanString($arrCVNevazator['oras']) . '%\'';
            
            $arrRezultate = $this->DATABASE->RunQuery($strAdvQuery);
            
            if (is_array($arrRezultate) && count($arrRezultate) > 0){
                $this->DATA['nrlocuri'] = count($arrRezultate);
                $this->DATA['locuri'] = array();
                
                foreach ($arrRezultate as $arrRezultat){
                    $this->DATA['locuri'][] = array(
                        'nume' => $arrRezultat['companie'],
                        'firmaprotejata' => ($arrRezultat['firmaprotejata'] == 'da' ?
                            'este firmă protejată' : 'nu este firmă protejată'),
                        
                        'dimensiunefirma' => ($arrRezultat['dimensiunefirma'] == 'peste50' ?
                            'are peste 50 de angajați' : 'are sub 50 de angajați'),
                        
                        'tipslujba' => ($arrRezultat['tipslujba'] == 'fulltime' ? 'Full-time' : 'Part-time'),
                        
                        'idxangajator' => (int)$arrRezultat['idx']
                    );
                }
            }else{
                $this->DATA['nrlocuri'] = 0;
                $this->DATA['locuri'] = array();
            }
        }else $this->DATA['result'] = 'EROARE: nu aveți completat CV-ul !';
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';


