<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'     => 'required',
        'idxlocmunca' => 'required|numeric'
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
        throw new Exception("EROARE: acest utilizator nu este de tip angajat !", 400);
    }

    $arrLocMunca = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locurimunca', [
        'idx', '=', (int)$validation->getValue('idxlocmunca')
    ]);
    if ($arrLocMunca === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrLocMunca)) {
        throw new Exception("EROARE: acest loc munca nu exista", 400);
    }

    $arrLocMunca = $arrLocMunca[0];
    $res = $this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'cereriinterviu', [
        ['idxauthangajat', '=', (int)$arrUser['idx'], 'AND'],
        ['idxauthangajator', '=', (int)$arrLocMunca['idxauth'], 'AND'],
        ['idxlocmunca', '=', (int)$validation->getValue('idxlocmunca')]
    ]);
    if ($res === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if ($res) {
        throw new Exception('Ați aplicat deja la acest loc de muncă în trecut !', 400);
    }

    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'cereriinterviu', [
        'idxauthangajat',
        'idxauthangajator',
        'idxlocmunca',
    ], [[
        'idxauthangajat'   => (int)$arrUser['idx'],
        'idxauthangajator' => (int)$arrLocMunca['idxauth'],
        'idxlocmunca'      => (int)$validation->getValue('idxlocmunca')
    ]]);
    if (!$res) {
        throw new Exception("EROARE: Nu s-a putut adăuga cererea de interviu !", 1);
    }
});
