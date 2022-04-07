<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'          => 'required',
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

    $res = $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'auth_users', ['idx', '=', $arrUser['idx']]);
    if (!$res) {
        throw new Exception("EROARE INTERNA", 500);
    }
});
