<?php

$this->handleAPIRequest(function() {
    foreach ($_SESSION as $strKey => $ni) {
        unset($_SESSION[$strKey]);
    }

    $validation = $this->validator->make($_POST, [
        'email'    => 'required|email',
        'password' => 'required',
        'acctype'  => 'required|in:angajat,angajator,universitate'
    ]);

    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $error = array_values($errors->firstOfAll())[0];
        throw new Exception("EROARE: {$error}!", 400);
    }

    $kNewAuth = new CQAuth();
    $kNewAuth->Init($this->DATABASE, $this->CONFIG);
    $nLoginCode = $kNewAuth->LogIn($validation->getValue('email'), $validation->getValue('password'));
    if ($nLoginCode !== AUTH_SUCCESS) {
        throw new Exception("EROARE: Emailul, parola sau tipul de utilizator este incorect!", 400);
    }

    $nUserType = (int)$kNewAuth->GetAdvancedDetail('tiputilizator');
    if (($nUserType == 0 && $validation->getValue('acctype') == 'angajat') ||
        ($nUserType == 1 && $validation->getValue('acctype') == 'angajator') ||
        ($nUserType == 2 && $validation->getValue('acctype') == 'universitate')
    ) {
        $strUserKey = $kNewAuth->GetNewSalt();

        while ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'auth_users', ['apploginid', '=', $strUserKey]) > 0) {
            $strUserKey = $kNewAuth->GetNewSalt();
        }

        $arrAllDetails = [];
        $arrAllDetails['apploginid'] = $strUserKey;
        $kNewAuth->ChangeAdvancedDetails($arrAllDetails);
        $this->DATA['userkey'] = $strUserKey;
    } else {
        throw new Exception("EROARE: Tipul de utilizator este incorect !", 400);
    }
});
