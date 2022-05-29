<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'     => 'nullable',
        'idxlocmunca' => 'required|numeric'
    ]);

    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $error = array_values($errors->firstOfAll())[0];
        throw new Exception("EROARE: {$error}!", 400);
    }

    $conds = [];
    if ($validation->getValue('userkey')) {
        $conds = [ 'apploginid', '=', $validation->getValue('userkey') ];
    } else if ($this->AUTH->IsAuthenticated()) {
        $conds = [ 'idx', '=', $this->AUTH->GetUserId() ];
    } else {
        throw new Exception("Cerere invalida", 400);
    }

    $arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users', $conds);
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

    $res = $this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'angajati_locurisalvate', [
        ['idxauthangajat', '=', (int)$arrUser['idx'], 'AND'],
        ['idxauthangajator', '=', (int)$arrLocMunca['idxauth'], 'AND'],
        ['idxlocmunca', '=', (int)$validation->getValue('idxlocmunca')]
    ]);
    if ($res === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (!$res) {
        $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajati_locurisalvate', [
            'idxauthangajat',
            'idxauthangajator',
            'idxlocmunca'
        ], [[
            'idxauthangajat'   => (int)$arrUser['idx'],
            'idxauthangajator' => (int)$arrLocMunca['idxauth'],
            'idxlocmunca'      => (int)$validation->getValue('idxlocmunca')
        ]]);
        if (!$res) {
            throw new Exception("EROARE: Nu s-a putut salva locul de muncă în listă !", 500);
        }
    }
});

