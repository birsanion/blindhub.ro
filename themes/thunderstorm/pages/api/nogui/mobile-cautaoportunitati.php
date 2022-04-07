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
    if ($arrUser['tiputilizator'] != 0) {
        throw new Exception("EROARE: acest utilizator nu este de tip angajat !", 400);
    }

    $arrAngajat = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati', [
        'idxauth', '=', $arrUser['idx'],
    ]);
    if ($arrAngajat === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrAngajat)) {
        throw new Exception("EROARE: acest angajat nu există !", 400);
    }

    $arrAngajat = $arrAngajat[0];

    $arrOrase = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati_orase', [
        ['idx_angajat', '=', $arrAngajat['idx']]
    ]);
    if ($arrOrase === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $idxOrase = [];
    foreach ($arrOrase as $oras) {
        $idxOrase[] = $oras['idx_oras'];
    }

    $arrDomenii = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati_domenii_cv', [
        ['idx_angajat', '=', $arrAngajat['idx']]
    ]);
    if ($arrDomenii === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $idxDomenii = [];
    foreach ($arrDomenii as $domeniu) {
        $idxDomenii[] = $domeniu['idx_domeniu_cv'];
    }

    $arrRezultate = $this->DATABASE->RunQuery(sprintf(
        "SELECT angajatori.*, " .
        "   GROUP_CONCAT(DISTINCT angajatori_orase.idx_oras) AS idx_orase, " .
        "   GROUP_CONCAT(DISTINCT angajatori_domenii.idx_domeniu_cv) AS idx_domenii_cv " .
        "FROM `%s` angajatori " .
        "INNER JOIN `%s` angajatori_orase " .
        "ON (angajatori.idx = angajatori_orase.idx_angajator AND angajatori_orase.idx_oras IN (%s)) " .
        "INNER JOIN `%s` angajatori_domenii " .
        "ON (angajatori.idx = angajatori_domenii.idx_angajator AND angajatori_domenii.idx_domeniu_cv IN (%s)) " .
        "GROUP BY angajatori.idx ",
        SYSCFG_DB_PREFIX . 'angajatori',
        SYSCFG_DB_PREFIX . 'angajatori_orase',
        implode(',', $idxOrase),
        SYSCFG_DB_PREFIX . 'angajatori_domenii_cv',
        implode(',', $idxDomenii)
    ));
    if ($arrRezultate === false) {
        throw new Exception($this->DATABASE->GetError(), 500);
    }

    $this->DATA['nrlocuri'] = count($arrRezultate);
    $this->DATA['locuri'] = [];
    foreach ($arrRezultate as $arrRezultat) {
        $this->DATA['locuri'][] = [
            'nume'                        => $arrRezultat['companie'],
            'idx_optiune_dimensiunefirma' => (int)$arrRezultat['idx_optiune_dimensiunefirma'],
            'idxangajator'                => (int)$arrRezultat['idxauth'],
            'idx_orase'                   => array_map(function ($val) {
                return (int)$val;
            }, explode(',', $arrRezultat['idx_orase'])),
            'idx_domenii_cv'              => array_map(function ($val) {
                return (int)$val;
            }, explode(',', $arrRezultat['idx_domenii_cv']))
        ];
    }
});
