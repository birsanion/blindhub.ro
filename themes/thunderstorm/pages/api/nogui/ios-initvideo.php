<?php

function RomanianDate_to_MySQLDate($pcDate)
{
    return substr($pcDate,6,4).'-'.substr($pcDate,3,2).'-'.substr($pcDate,0,2).
        (strlen($pcDate)>10 ? ' '.substr($pcDate, 11) : '');
}

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'idxentry' => 0,
    'debug' => ''
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 1){
        if ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'interviuri', array(
                array('idxauthangajat', '=', (int)POST('idxauthnevazator'), 'AND'),
                array('idxauthangajator', '=', (int)$arrUser['idx'], 'AND'),
                array('tstamp', '=', RomanianDate_to_MySQLDate(POST('dataora')) . ':00')
            )) > 0)
        {
            $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'interviuri',
                'initvideo',
                array(
                    'initvideo' => 1
                ),
                array(
                    array('idxauthangajat', '=', (int)POST('idxauthnevazator'), 'AND'),
                    array('idxauthangajator', '=', (int)$arrUser['idx'], 'AND'),
                    array('tstamp', '=', RomanianDate_to_MySQLDate(POST('dataora')) . ':00')
                )
            );
            
            $arrData = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'interviuri', array(
                array('idxauthangajat', '=', (int)POST('idxauthnevazator'), 'AND'),
                array('idxauthangajator', '=', (int)$arrUser['idx'], 'AND'),
                array('tstamp', '=', RomanianDate_to_MySQLDate(POST('dataora')) . ':00')
            ));
            
            $this->DATA['idxentry'] = (int)$arrData[0]['idx'];
        }else{
            $this->DATA['result'] = 'EROARE: aceasta programare de interviu nu exista !';
        }
    
    }elseif ((int)$arrUser['tiputilizator'] == 2){
        if ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'interviuri', array(
                array('idxauthangajat', '=', (int)POST('idxauthnevazator'), 'AND'),
                array('idxauthuniversitate', '=', (int)$arrUser['idx'], 'AND'),
                array('tstamp', '=', RomanianDate_to_MySQLDate(POST('dataora')) . ':00')
            )) > 0)
        {
            $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'interviuri',
                'initvideo',
                array(
                    'initvideo' => 1
                ),
                array(
                    array('idxauthangajat', '=', (int)POST('idxauthnevazator'), 'AND'),
                    array('idxauthuniversitate', '=', (int)$arrUser['idx'], 'AND'),
                    array('tstamp', '=', RomanianDate_to_MySQLDate(POST('dataora')) . ':00')
                )
            );
            
            $arrData = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'interviuri', array(
                array('idxauthangajat', '=', (int)POST('idxauthnevazator'), 'AND'),
                array('idxauthuniversitate', '=', (int)$arrUser['idx'], 'AND'),
                array('tstamp', '=', RomanianDate_to_MySQLDate(POST('dataora')) . ':00')
            ));
            
            $this->DATA['idxentry'] = (int)$arrData[0]['idx'];
        }else{
            $this->DATA['result'] = 'EROARE: aceasta programare de interviu nu exista !';
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este nici de tip angajator, nici universitate !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
