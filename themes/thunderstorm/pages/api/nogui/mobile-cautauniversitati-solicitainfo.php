<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'idxauthuniversitate' => 'required|numeric',
        'idxloc'              => 'required|numeric',
        'userkey'             => 'required'
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
    if ($arrUser['tiputilizator'] != 0) {
        throw new Exception("EROARE: acest utilizator nu este de tip angajat!", 400);
    }

    $res = $this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate', [
        ['idxauthangajat', '=', (int)$arrUser['idx'], 'AND'],
        ['idxauthuniversitate', '=', (int)$validation->getValue('idxauthuniversitate'), 'AND'],
        ['idxlocuniversitate', '=', (int)$validation->getValue('idxloc')]
    ]);
    if ($res === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if ($res) {
        throw new Exception("Ați aplicat deja pentru acest interviu în trecut !", 400);
    }

    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate', [
        'idxauthangajat',
        'idxauthuniversitate',
        'idxlocuniversitate',
    ], [[
        'idxauthangajat'      => (int)$arrUser['idx'],
        'idxauthuniversitate' => (int)$validation->getValue('idxauthuniversitate'),
        'idxlocuniversitate'  => (int)$validation->getValue('idxloc')
    ]]);
    if (!$res) {
        throw new Exception("EROARE INTERNA", 500);
    }
});