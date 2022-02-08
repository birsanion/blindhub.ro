<?php

require 'system/thirdparty/vendor/autoload.php';
use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;

define('VONAGE_API_KEY', '47381251');
define('VONAGE_API_SECRET', 'b906f5b041ad14c221930bd5230305abee699c5e');

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
    
    if ((int)$arrUser['tiputilizator'] == 1){
        if ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'interviuri', array(
                array('idxauthangajat', '=', (int)POST('idxauthnevazator'), 'AND'),
                array('idxauthangajator', '=', (int)$arrUser['idx'], 'AND'),
                array('idxobject', '=', (int)POST('idxlocmunca'))
            )) <= 0)
        {
            $strSessionId = '';
            $strNevazatorKey = '';
            $strInterlocutorKey = '';
            
            try {
                $kOpentok = new OpenTok(VONAGE_API_KEY, VONAGE_API_SECRET);
                
                $kSession = $kOpentok->createSession();
                $strSessionId = $kSession->getSessionId();
                
                $strNevazatorKey = $kOpentok->generateToken($strSessionId);
                $strInterlocutorKey = $kOpentok->generateToken($strSessionId);
            }catch(Exception $kEx){
                $this->DATA['result'] = 'EROARE: nu se pot genera datele pentru videochat !';
                $this->DATA['debug'] = print_r($kEx, true);
            }
            
            $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'interviuri',
                'idxauthangajat, idxauthangajator, idxauthuniversitate, tstamp, idxobject, ' .
                    'vonagesessid, vonagenevaztoken, vonageinterlocutortoken',
                array(array(
                    'idxauthangajat' => (int)POST('idxauthnevazator'),
                    'idxauthangajator' => (int)$arrUser['idx'],
                    'idxauthuniversitate' => 0,
                    'tstamp' => RomanianDate_to_MySQLDate(POST('datacalend')) . ' ' . POST('ora') . ':00',
                    'idxobject' => (int)POST('idxlocmunca'),
                    
                    'vonagesessid' => $strSessionId,
                    'vonagenevaztoken' => $strNevazatorKey,
                    'vonageinterlocutortoken' => $strInterlocutorKey
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
                    array('idxauthangajator', '=', (int)$arrUser['idx'], 'AND'),
                    array('idxobject', '=', (int)POST('idxlocmunca'))
                )
            );
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
