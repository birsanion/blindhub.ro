<?php

$this->handleAPIRequest(function() {
    $this->DATA['nrrezultate'] = 0;
    $this->DATA['rezultate'] = [];

    $validation = $this->validator->make($_POST, [
        'idxauthuniversitate'      => 'required',
        'idx_domeniu_universitate' => 'required|numeric',
        'userkey'                  => 'required',
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

    $arrUniversitate = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati', [
        ['idxauth', '=', $validation->getValue('idxauthuniversitate')],
    ]);
    if ($arrUniversitate === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrUniversitate)) {
        throw new Exception("Eroare: aceasta universitate nu exista", 400);
    }

    $arrUniversitate = $arrUniversitate[0];

    $arrLocuri = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locuriuniversitate', [
        ['idx_domeniu_universitate', '=', $validation->getValue('idx_domeniu_universitate'), 'AND'],
        ['idxauth', '=', (int)$arrUniversitate['idxauth']]
    ]);
    if ($arrLocuri === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $this->DATA['nrrezultate'] = 0;
    $this->DATA['rezultate'] = [];
    foreach ($arrLocuri as $arrLoc) {
        $this->DATA['nrrezultate']++;
        $this->DATA['rezultate'][] = [
            'numeuniversitate'         => $arrUniversitate['nume'],
            'idxauth'                  => (int)$arrLoc['idxauth'],
            'facultate'                => $arrLoc['facultate'],
            'nrlocuri'                 => (int)$arrLoc['numarlocuri'],
            'idxloc'                   => (int)$arrLoc['idx'],
            'idx_oras'                 => (int)$arrLoc['idx_oras'],
            'idx_domeniu_universitate' => (int)$arrLoc['idx_domeniu_universitate'],
        ];
    }
});