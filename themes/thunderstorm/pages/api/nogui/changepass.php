<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'parolaveche' => 'required',
        'parolanoua'  => 'required',
        'userkey'     => 'required',
    ]);

    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $error = array_values($errors->firstOfAll())[0];
        throw new Exception("EROARE: {$error}!", 400);
    }

    $arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users', [
        'apploginid', '=', $validation->getValue('userkey')
    ]);
    if ($arrUser === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrUser)) {
        throw new Exception("EROARE: acest utilizator nu există !", 400);
    }

    $arrUser = $arrUser[0];
    $passHash = $this->AUTH->GetPasswordHash($validation->getValue('parolaveche'), $arrUser['username'], $arrUser['salt']);
    if (!$this->AUTH->CompareHashes($arrUser['passhash'], $passHash)) {
        throw new Exception("EROARE: Parola veche este incorectă !", 400);
    }

    if ($this->AUTH->ResetPassword((int)$arrUser['idx'], $validation->getValue('parolanoua')) != AUTH_SUCCESS) {
        throw new Exception("EROARE: Parola nu a putut fi modificată !", 400);
    }

    $this->DATA['result'] = 'success';
});
/*
$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];

    if ($this->AUTH->CompareHashes($arrUser['passhash'],
        $this->AUTH->GetPasswordHash(POST('parolaveche'), $arrUser['username'], $arrUser['salt'])))
    {
        if ($this->AUTH->ResetPassword((int)$arrUser['idx'], POST('parolanoua')) != AUTH_SUCCESS)
            $this->DATA['result'] = 'EROARE: Parola nu a putut fi modificată !';
    }else $this->DATA['result'] = 'EROARE: Parola veche este incorectă !';
}else $this->DATA['result'] = 'EROARE: Utilizatorul nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
*/