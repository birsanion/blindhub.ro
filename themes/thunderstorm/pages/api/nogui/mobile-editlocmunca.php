<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'               => 'required',
        'idx_domeniu_cv'        => 'required|numeric',
        'idx_oras'              => 'required|numeric',
        'competente'            => 'required',
        'titlu'                 => 'required',
        'descriere'             => 'required',
        'idx_optiune_tipslujba' => 'required|numeric',
        'idxloc'                => 'required|numeric',
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

    $arrLoc = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locurimunca', [
        ['idx', '=', $validation->getValue('idxloc'), 'AND'],
        ['idxauth', '=', (int)$arrUser['idx']]
    ]);
    if ($arrLoc === false) {
        throw new Exception($this->DATABASE->GetError(), 500);
    }
    if (empty($arrLoc)) {
        throw new Exception("EROARE: acesta oferta nu există !", 400);
    }

    $res = $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'locurimunca', [
        'idx_domeniu_cv',
        'idx_oras',
        'competente',
        'titlu',
        'descriere',
        'idx_optiune_tipslujba',
    ], [
        'idx_domeniu_cv'        => $validation->getValue('idx_domeniu_cv'),
        'idx_oras'              => $validation->getValue('idx_oras'),
        'competente'            => $validation->getValue('competente'),
        'titlu'                 => $validation->getValue('titlu'),
        'descriere'             => $validation->getValue('descriere'),
        'idx_optiune_tipslujba' => $validation->getValue('idx_optiune_tipslujba')
    ], [
        ['idx', '=', (int)$validation->getValue('idxloc'), 'AND'],
        ['idxauth', '=', (int)$arrUser['idx']]
    ]);
    if ($res === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
});
