<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'userkey' => ''
);

foreach ($_SESSION as $strKey => $ni)
    unset($_SESSION[$strKey]);

$kNewAuth = new CQAuth();
$kNewAuth->Init($this->DATABASE, $this->CONFIG);

$nLoginCode = $kNewAuth->LogIn(POST('email'), POST('password'));

if ($nLoginCode == AUTH_SUCCESS){
    $nUserType = (int)$kNewAuth->GetAdvancedDetail('tiputilizator');

    if (($nUserType == 0 && POST('acctype') == 'angajat') ||
        ($nUserType == 1 && POST('acctype') == 'angajator') ||
        ($nUserType == 2 && POST('acctype') == 'universitate'))
    {
        $strUserKey = $kNewAuth->GetNewSalt();

        while ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'auth_users',
                array('apploginid', '=', $strUserKey)) > 0)
            $strUserKey = $kNewAuth->GetNewSalt();

        $arrAllDetails = array();
        $arrAllDetails['apploginid'] = $strUserKey;
        $kNewAuth->ChangeAdvancedDetails($arrAllDetails);

        $this->DATA['userkey'] = $strUserKey;
    }else $this->DATA['result'] = 'EROARE: Tipul de utilizator este incorect !';
}else $this->DATA['result'] = 'EROARE: Emailul, parola sau tipul de utilizator este incorect !';

if ($this->DATA['result'] != 'success') http_response_code(400);

$this->LOG->Log('login', print_r($_COOKIE, true) . "\r\n" .
    print_r($_SESSION, true) . "\r\n" . print_r($_POST, true) . "\r\n" .
    print_r($this->DATA, true));
