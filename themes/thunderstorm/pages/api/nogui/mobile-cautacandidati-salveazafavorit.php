<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'          => 'required',
        'idxauthnevazator' => 'required|numeric'
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
    if ($arrUser['tiputilizator'] != 1) {
        throw new Exception("EROARE: acest utilizator nu este de tip angajator!", 400);
    }

    $res = $this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'angajatori_angajati_favoriti', [
        ['idxauthangajator', '=', (int)$arrUser['idx'], 'AND'],
        ['idxauthangajat', '=', $validation->getValue('idxauthnevazator')],
    ]);
    if ($res === false) {
        throw new Exception("Eroare interna", 500);
    }
    if (!$res) {
        $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajatori_angajati_favoriti', [
            'idxauthangajator',
            'idxauthangajat',
        ], [[
            'idxauthangajator' => (int)$arrUser['idx'],
            'idxauthangajat'   => $validation->getValue('idxauthnevazator'),
        ]]);
        if (!$res) {
            throw new Exception("EROARE: Nu s-a putut adăuga cererea de interviu !", 500);
        }
    } else {
        $res = $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'angajatori_angajati_favoriti', [
            ['idxauthangajator', '=', (int)$arrUser['idx'], 'AND'],
            ['idxauthangajat', '=', $validation->getValue('idxauthnevazator')],
        ]);
        if ($res === false) {
            throw new Exception("EROARE INTERNA", 500);
        }
    }
});
