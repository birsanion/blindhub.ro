<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey' => 'required'
    ]);

    $validation->validate();
    if ($validation->fails()) {
        // $errors = $validation->errors();
        // $error = array_values($errors->firstOfAll())[0];
        throw new Exception("Cerere invalidă", 400);
    }

    $arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users', [
        'apploginid', '=', $validation->getValue('userkey')
    ]);
    if ($arrUser === false) {
        throw new Exception("Eroare internă", 500);
    }
    if (empty($arrUser)) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrUser = $arrUser[0];
    if ($arrUser['tiputilizator'] != 0) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrDetails = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati', [
        'idxauth', '=', $arrUser['idx']
    ]);
    if ($arrDetails === false) {
        throw new Exception("Eroare internă", 500);
    }
    if (empty($arrDetails)) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrDetails = $arrDetails[0];
    $this->DATA['email'] = $arrUser['username'];
    $this->DATA['nume'] = $arrDetails['nume'];
    $this->DATA['prenume'] = $arrDetails['prenume'];
    $this->DATA['idx_optiune_gradhandicap'] = (int)$arrDetails['idx_optiune_gradhandicap'];
    $this->DATA['nevoispecifice'] = $arrDetails['nevoispecifice'];
    $this->DATA['idx_orase'] = [];
    $this->DATA['idx_domenii_cv'] = [];
    $this->DATA['cv'] = '';
    if ($arrDetails['cv_fisier_video'])  {
        $this->DATA['cv'] = qurl_file('media/uploads/' .  $arrDetails['cv_fisier_video']);
    }
    $this->DATA['img'] = '';
    if ($arrDetails['img'])  {
        $this->DATA['img'] = qurl_file('media/uploads/' .  $arrDetails['img']);
    }

    $arrOrase = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati_orase', [
        'idx_angajat', '=', $arrDetails['idx']
    ]);
    if ($arrOrase === false) {
        throw new Exception("Eroare internă", 500);
    }

    foreach ($arrOrase as $oras) {
        $this->DATA['idx_orase'][] = (int)$oras['idx_oras'];
    }

    $arrDomenii = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati_domenii_cv', [
        'idx_angajat', '=', $arrDetails['idx']
    ]);
    if ($arrDomenii === false) {
        throw new Exception("Eroare internă", 500);
    }

    foreach ($arrDomenii as $domeniu) {
        $this->DATA['idx_domenii_cv'][] = (int)$domeniu['idx_domeniu_cv'];
    }
});
