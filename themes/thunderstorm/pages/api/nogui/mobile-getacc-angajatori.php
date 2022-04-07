<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey' => 'required'
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

    $arrAngajator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori', [
        'idxauth', '=', $arrUser['idx']
    ]);
    if ($arrAngajator === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrAngajator)) {
        throw new Exception("EROARE: acest angajator nu există !", 400);
    }

    $arrAngajator = $arrAngajator[0];
    $this->DATA['companie'] = $arrAngajator['companie'];
    $this->DATA['adresa'] = $arrAngajator['adresa'];
    $this->DATA['cui'] = $arrAngajator['cui'];
    $this->DATA['firmaprotejata'] = (int)$arrAngajator['firmaprotejata'];
    $this->DATA['idx_optiune_dimensiunefirma'] = (int)$arrAngajator['idx_optiune_dimensiunefirma'];
    $this->DATA['email'] = $arrUser['username'];
    $this->DATA['idx_orase'] = [];
    $this->DATA['idx_domenii_cv'] = [];
    $this->DATA['img'] = '';
    if ($arrAngajator['img']) {
        $this->DATA['img'] = qurl_file('media/uploads/' .  $arrAngajator['img']);
    }

    $arrOrase = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori_orase', [
        'idx_angajator', '=', $arrAngajator['idx']
    ]);
    if ($arrOrase === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    foreach ($arrOrase as $oras) {
        $this->DATA['idx_orase'][] = (int)$oras['idx_oras'];
    }

    $arrDomenii = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori_domenii_cv', [
        'idx_angajator', '=', $arrAngajator['idx']
    ]);
    if ($arrDomenii === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    foreach ($arrDomenii as $domeniu) {
        $this->DATA['idx_domenii_cv'][] = (int)$domeniu['idx_domeniu_cv'];
    }
});
