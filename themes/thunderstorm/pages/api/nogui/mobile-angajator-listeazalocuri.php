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

    $arrLocuriMunca = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locurimunca', [
        'idxauth', '=', (int)$arrUser['idx']
    ]);
    if ($arrLocuriMunca === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $this->DATA['nrlocuri'] = count($arrLocuriMunca);
    foreach ($arrLocuriMunca as $arrLoc) {
        $this->DATA['locuri'][] = [
            'idx'                   => (int)$arrLoc['idx'],
            'idxauth'               => (int)$arrLoc['idxauth'],
            'idx_oras'              => (int)$arrLoc['idx_oras'],
            'idx_domeniu_cv'        => (int)$arrLoc['idx_domeniu_cv'],
            'competente'            => $arrLoc['competente'],
            'titlu'                 => $arrLoc['titlu'],
            'descriere'             => $arrLoc['descriere'],
            'idx_optiune_tipslujba' => (int)$arrLoc['idx_optiune_tipslujba'],
            'datapostare'           => $arrLoc['datapostare'],
        ];
    }
});
