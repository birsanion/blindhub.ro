<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'debug' => ''
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 0){
        if ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'cereriinterviu',
            array(
                array('idxauthangajat', '=', (int)$arrUser['idx'], 'AND'),
                array('idxauthangajator', '=', (int)POST('idxangajator'), 'AND'),
                array('idxlocmunca', '=', 0)
            )) <= 0)
        {
            if (!$this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'cereriinterviu',
                    'idxauthangajat, idxauthangajator, idxlocmunca',
                    array(array(
                        'idxauthangajat' => (int)$arrUser['idx'],
                        'idxauthangajator' => (int)POST('idxangajator'),
                        'idxlocmunca' => 0
                    ))
                ))
            {
                $this->DATA['result'] = 'EROARE: Nu s-a putut adăuga cererea de interviu !';
            }
        }else $this->DATA['result'] = 'Ați aplicat deja la această companie în trecut !';
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

