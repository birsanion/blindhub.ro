<?php

function RomanianDate_to_MySQLDate($pcDate)
{
    return substr($pcDate,6,4).'-'.substr($pcDate,3,2).'-'.substr($pcDate,0,2).
        (strlen($pcDate)>10 ? ' '.substr($pcDate, 11) : '');
}

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'debug' => ''
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 2){
        if ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'interviuri', array(
                array('idxauthangajat', '=', (int)POST('idxauthnevazator'), 'AND'),
                array('idxauthuniversitate', '=', (int)$arrUser['idx'])
            )) <= 0)
        {
            $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'interviuri',
                'idxauthangajat, idxauthangajator, idxauthuniversitate, tstamp',
                array(array(
                    'idxauthangajat' => (int)POST('idxauthnevazator'),
                    'idxauthangajator' => 0,
                    'idxauthuniversitate' => (int)$arrUser['idx'],
                    'tstamp' => RomanianDate_to_MySQLDate(POST('datacalend')) . ' ' . POST('ora') . ':00'
                ))
            );
        }else{
            $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'interviuri',
                'tstamp',
                array(
                    'tstamp' => RomanianDate_to_MySQLDate(POST('datacalend')) . ' ' . POST('ora') . ':00'
                ),
                array(
                    array('idxauthangajat', '=', (int)POST('idxauthnevazator'), 'AND'),
                    array('idxauthuniversitate', '=', (int)$arrUser['idx'])
                )
            );
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';


