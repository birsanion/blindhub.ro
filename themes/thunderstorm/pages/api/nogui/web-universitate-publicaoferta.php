<?php

$this->handleAPIRequest(function() {
    if ($this->AUTH->GetAdvancedDetail('tiputilizator') != 2) {
        throw new Exception("EROARE: acest utilizator nu este de tip universitate !", 400);
    }

    $validation = $this->validator->make($_POST, [
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

    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'locuriuniversitate', [
        'idxauth',
        'facultate',
        'numarlocuri',
        'idx_domeniu_universitate',
        'idx_oras'
    ], [[
        'idxauth'                  => $this->AUTH->GetUserId(),
        'facultate'                => $validation->getValue('nume'),
        'numarlocuri'              => $validation->getValue('nrlocuri'),
        'idx_domeniu_universitate' => $validation->getValue('idx_domeniu_universitate'),
        'idx_oras'                 => $validation->getValue('idx_oras')
    ]]);
    if (!$res) {
        throw new Exception($this->DATABASE->GetError(), 500);
    }
});