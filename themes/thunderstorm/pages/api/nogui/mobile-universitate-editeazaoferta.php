<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'                  => 'nullable',
        'nume'                     => 'required',
        'nrlocuri'                 => 'required|numeric',
        'idx_domeniu_universitate' => 'required|numeric',
        'idx_oras'                 => 'required|numeric',
        'idxloc'                   => 'required|numeric',
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
    if ($arrUser['tiputilizator'] != 2) {
        throw new Exception("EROARE: acest utilizator nu este de tip universitate !", 400);
    }

    $res = $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'locuriuniversitate', [
        'facultate',
        'numarlocuri',
        'idx_domeniu_universitate',
        'idx_oras'
    ], [
        'facultate'                => $validation->getValue('nume'),
        'numarlocuri'              => $validation->getValue('nrlocuri'),
        'idx_domeniu_universitate' => $validation->getValue('idx_domeniu_universitate'),
        'idx_oras'                 => $validation->getValue('idx_oras')
    ], [
        ['idx', '=', (int)$validation->getValue('idxloc'), 'AND'],
        ['idxauth', '=', (int)$arrUser['idx']]
    ]);
    if (!$res) {
        throw new Exception("EROARE: nu s-au putut modifica informatiile in baza de date !", 500);
    }
});
