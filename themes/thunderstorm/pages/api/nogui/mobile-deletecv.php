<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'          => 'required',
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
    $res = $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'angajati_orase', [
        'idx_angajat', '=', $arrAngajat['idx']
    ]);
    if ($res === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $res = $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'angajati_domenii_cv', [
        'idx_angajat', '=', $arrAngajat['idx']
    ]);
    if ($res === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    if ($arrAngajat['cv_fisier_video']) {
        if (!unlink('media/uploads/' . $arrAngajat['cv_fisier_video'])) {
            throw new Exception("EROARE INTERNA", 500);
        }

        $res = $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'angajati', 'cv_fisier_video', [
            'cv_fisier_video' => NULL,
        ], [
            'idxauth', '=', $arrUser['idx']
        ]);
        if ($res === false) {
            throw new Exception("EROARE INTERNA", 500);
        }
    }
});
