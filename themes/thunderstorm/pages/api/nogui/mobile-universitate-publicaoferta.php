<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'                  => 'required',
        'nume'                     => 'required',
        'nrlocuri'                 => 'required|numeric',
        'idx_domeniu_universitate' => 'required|numeric',
        'idx_oras'                 => 'required|numeric',
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
    if ($arrUser['tiputilizator'] != 2) {
        throw new Exception("EROARE: acest utilizator nu este de tip universitate !", 400);
    }

    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'locuriuniversitate', [
        'idxauth',
        'facultate',
        'numarlocuri',
        'idx_domeniu_universitate',
        'idx_oras'
    ], [[
        'idxauth'                  => $arrUser['idx'],
        'facultate'                => $validation->getValue('nume'),
        'numarlocuri'              => $validation->getValue('nrlocuri'),
        'idx_domeniu_universitate' => $validation->getValue('idx_domeniu_universitate'),
        'idx_oras'                 => $validation->getValue('idx_oras')
    ]]);
    if (!$res) {
        throw new Exception($this->DATABASE->GetError(), 500);
    }
});
