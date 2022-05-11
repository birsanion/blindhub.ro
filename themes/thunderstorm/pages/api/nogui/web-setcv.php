<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'idx_orase'        => 'required|array',
        'idx_orase.*'      => 'numeric',
        'idx_domenii_cv'   => 'required|array',
        'idx_domenii_cv.*' => 'numeric',
    ]);
    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $error = array_values($errors->firstOfAll())[0];
        throw new Exception("EROARE: {$error}!", 400);
    }

    $arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users', [
        'idx', '=', $this->AUTH->GetUserId()
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

    $arrAngajat = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati', [
        'idxauth', '=', $arrUser['idx']
    ]);
    if ($arrAngajat === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrAngajat)) {
        throw new Exception("EROARE: acest angajat nu există !", 400);
    }

    $arrAngajat = $arrAngajat[0];

    $arrAngajatOrase = [];
    foreach ($validation->getValue('idx_orase') as $idx_oras) {
        $arrAngajatOrase[] = [
            'idx_angajat' => $arrAngajat['idx'],
            'idx_oras'    => $idx_oras
        ];
    }

    $res = $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'angajati_orase', [
        'idx_angajat', '=', $arrAngajat['idx']
    ]);
    if ($res === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajati_orase', ['idx_angajat', 'idx_oras'], $arrAngajatOrase);
    if ($res === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $arrAngajatDomenii = [];
    foreach ($validation->getValue('idx_domenii_cv') as $idx_domeniu) {
        $arrAngajatDomenii[] = [
            'idx_angajat'    => $arrAngajat['idx'],
            'idx_domeniu_cv' => $idx_domeniu
        ];
    }

    $res = $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'angajati_domenii_cv', [
        'idx_angajat', '=', $arrAngajat['idx'],
    ]);
    if ($res === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajati_domenii_cv', ['idx_angajat', 'idx_domeniu_cv'], $arrAngajatDomenii);
    if ($res === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
});
