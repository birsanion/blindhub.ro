<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'  => 'required',
        'idx_oras' => 'required|numeric'
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
        throw new Exception("EROARE: acest utilizator nu este de tip nevăzător !", 400);
    }

    $arrUniversitati = $this->DATABASE->RunQuery(sprintf(
        "SELECT universitati.* " .
        "FROM `%s` universitati_orase " .
        "INNER JOIN `%s` universitati " .
        "ON (universitati_orase.idx_universitate = universitati.idx) " .
        "WHERE universitati_orase.idx_oras = %d " .
        "ORDER BY universitati.nume",
        SYSCFG_DB_PREFIX . 'universitati_orase',
        SYSCFG_DB_PREFIX . 'universitati',
        $validation->getValue('idx_oras')
    ));
    if ($arrUniversitati === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $this->DATA['nruniversitati'] = count($arrUniversitati);
    $this->DATA['universitati'] = [];
    foreach ($arrUniversitati as $arrUniversitate) {
        $this->DATA['universitati'][] = [
            'idxauth' => (int)$arrUniversitate['idxauth'],
            'nume'    => $arrUniversitate['nume']
        ];
    }
});
