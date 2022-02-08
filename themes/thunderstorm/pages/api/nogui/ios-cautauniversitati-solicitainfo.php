<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 0){
        if ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate',
            array(
                array('idxauthangajat', '=', (int)$arrUser['idx'], 'AND'),
                array('idxauthuniversitate', '=', (int)POST('idxauthuniversitate'), 'AND'),
                array('idxlocuniversitate', '=', (int)POST('idxloc'))
            )) <= 0)
        {
            $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate',
                'idxauthangajat, idxauthuniversitate, idxlocuniversitate',
                array(array(
                    'idxauthangajat' => (int)$arrUser['idx'],
                    'idxauthuniversitate' => (int)POST('idxauthuniversitate'),
                    'idxlocuniversitate' => (int)POST('idxloc')
                ))
            );
        }else{
            $this->DATA['result'] = 'Ați aplicat deja pentru acest interviu în trecut !';
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
