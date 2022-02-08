<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'debug' => '',
    'locuri' => array()
);

$strUserKey = POST('userkey');

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', $strUserKey));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 0){
        $arrLocuri = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'cereriinterviu',
            array('idxauthangajat', '=', (int)$arrUser['idx']));
        
        if (is_array($arrLocuri) && !empty($arrLocuri)){
            foreach ($arrLocuri as $arrLoc){
                $arrAngajator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori',
                    array('idxauth', '=', (int)$arrLoc['idxauthangajator']));
                
                $arrLocMunca = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locurimunca',
                    array('idx', '=', (int)$arrLoc['idxlocmunca']));
                    
                if (is_array($arrAngajator) && is_array($arrLocMunca) && !empty($arrLocMunca) && !empty($arrAngajator)){
                    $this->DATA['locuri'][] = array(
                        'angajator' => $arrAngajator[0],
                        'locmunca' => $arrLocMunca[0]
                    );
                }
            }
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip nevăzător !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
            
        