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
    if ($arrUser['tiputilizator'] != 2) {
        throw new Exception("EROARE: acest utilizator nu este de tip universitate!", 400);
    }

    $arrUniversitate = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati', [
        'idxauth', '=', $arrUser['idx']
    ]);
    if ($arrUniversitate === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrUniversitate)) {
        throw new Exception("EROARE: acesta universitate nu există !", 400);
    }

    $arrUniversitate = $arrUniversitate[0];

    $arrOrase = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati_orase', [
        'idx_universitate', '=', $arrUniversitate['idx']
    ]);
    if ($arrOrase === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $this->DATA['nume'] = $arrUniversitate['nume'];
    $this->DATA['reprezentant'] = $arrUniversitate['reprezentant'];
    $this->DATA['idx_optiune_gradacces'] = (int)$arrUniversitate['idx_optiune_gradacces'];
    $this->DATA['idx_optiune_gradechipare'] = (int)$arrUniversitate['idx_optiune_gradechipare'];
    $this->DATA['studdiz'] = (int)$arrUniversitate['studdiz'];
    $this->DATA['studcentru'] = (int)$arrUniversitate['studcentru'];
    $this->DATA['camerecamine'] = (int)$arrUniversitate['camerecamine'];
    $this->DATA['persdedic'] = (int)$arrUniversitate['persdedic'];
    $this->DATA['idx_optiune_accesibilizare_clasa'] = (int)$arrUniversitate['idx_optiune_accesibilizare_clasa'];
    $this->DATA['braille'] = (int)$arrUniversitate['braille'];
    $this->DATA['email'] = $arrUser['username'];
    $this->DATA['img'] = '';
    if ($arrUniversitate['img']) {
        $this->DATA['img'] = qurl_file('media/uploads/' .  $arrUniversitate['img']);
    }

    foreach ($arrOrase as $oras) {
        $this->DATA['idx_orase'][] = (int)$oras['idx_oras'];
    }
});
