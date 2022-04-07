<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'  => 'required',
        'idxloc'   => 'required|numeric',
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

    $arrLoc = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locuriuniversitate', [
        ['idx', '=', $validation->getValue('idxloc'), 'AND'],
        ['idxauth', '=', (int)$arrUser['idx']]
    ]);
    if ($arrLoc === false) {
        throw new Exception($this->DATABASE->GetError(), 500);
    }

    if (empty($arrLoc)) {
        throw new Exception("EROARE: acesta oferta nu există !", 400);
    }

    $res = $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'locuriuniversitate', [
        'idx', '=', $validation->getValue('idxloc')
    ]);
    if (!$res) {
        throw new Exception("EROARE INTERNA", 500);
    }
});
