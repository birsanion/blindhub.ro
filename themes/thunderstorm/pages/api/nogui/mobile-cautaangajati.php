<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey' => 'required'
    ]);

    $validation->validate();
    if ($validation->fails()) {
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
    if ($arrUser['tiputilizator'] != 1) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrAngajator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori', [
        'idxauth', '=', $arrUser['idx']
    ]);
    if ($arrAngajator === false) {
        throw new Exception("Eroare internă", 500);
    }
    if (empty($arrAngajator)) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrAngajator = $arrAngajator[0];

    $arrAngajatorOrase = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori_orase', [
        'idx_angajator', '=', $arrAngajator['idx']
    ]);
    if ($arrAngajatorOrase === false) {
        throw new Exception("Eroare internă", 500);
    }
    if (empty($arrAngajatorOrase)) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrAngajatorDomenii = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori_domenii_cv', [
        'idx_angajator', '=', $arrAngajator['idx']
    ]);
    if ($arrAngajatorDomenii === false) {
        throw new Exception("Eroare internă", 500);
    }
    if (empty($arrAngajatorDomenii)) {
        throw new Exception("Cerere invalidă", 400);
    }

    $idxOrase = [];
    foreach ($arrAngajatorOrase as $arrAngajatorOras) {
        $idxOrase[] = $arrAngajatorOras['idx_oras'];
    }

    $idxDomenii = [];
    foreach ($arrAngajatorDomenii as $arrAngajatorDomeniu) {
        $idxDomenii[] = $arrAngajatorDomeniu['idx_domeniu_cv'];
    }

    $arrRezultate = $this->DATABASE->RunQuery(sprintf(
        "SELECT angajati.*, " .
        "       IF(angajatori_angajati_favoriti.idxauthangajat IS NOT NULL, 1, 0) AS favorit, " .
        "       GROUP_CONCAT(DISTINCT angajati_orase.idx_oras) AS idx_orase, " .
        "       GROUP_CONCAT(DISTINCT angajati_domenii_cv.idx_domeniu_cv) AS idx_domenii_cv " .
        "FROM `%s` angajati " .
        "LEFT JOIN `%s` angajatori_angajati_favoriti " .
        "ON (angajati.idxauth = angajatori_angajati_favoriti.idxauthangajat AND angajatori_angajati_favoriti.idxauthangajator = %d) " .
        "INNER JOIN `%s` angajati_orase " .
        "ON (angajati.idx = angajati_orase.idx_angajat AND angajati_orase.idx_oras IN (%s)) " .
        "INNER JOIN `%s` angajati_domenii_cv " .
        "ON (angajati.idx = angajati_domenii_cv.idx_angajat AND angajati_domenii_cv.idx_domeniu_cv IN (%s)) " .
        "GROUP BY angajati.idx",
        SYSCFG_DB_PREFIX . 'angajati',
        SYSCFG_DB_PREFIX . 'angajatori_angajati_favoriti',
        $arrAngajator['idxauth'],
        SYSCFG_DB_PREFIX . 'angajati_orase',
        implode(',', $idxOrase),
        SYSCFG_DB_PREFIX . 'angajati_domenii_cv',
        implode(',', $idxDomenii)
    ));
    if ($arrRezultate === false) {
        throw new Exception("Eroare internă", 500);
    }

    $this->DATA['nrlocuri'] = count($arrRezultate);
    $this->DATA['locuri'] = [];
    foreach ($arrRezultate as $arrRezultat) {
        $this->DATA['locuri'][] = [
            'nume'                     => $arrRezultat['nume'] . ' ' . $arrRezultat['prenume'],
            'idx_optiune_gradhandicap' => (int)$arrRezultat['idx_optiune_gradhandicap'],
            'nevoispecifice'           => $arrRezultat['nevoispecifice'],
            'idxauth'                  => (int)$arrRezultat['idxauth'],
            'favorit'                  => (int)$arrRezultat['favorit'],
            'idx_orase'                => array_map(function ($val) {
                return (int)$val;
            }, explode(',', $arrRezultat['idx_orase'])),
            'idx_domenii_cv'           => array_map(function ($val) {
                return (int)$val;
            }, explode(',', $arrRezultat['idx_domenii_cv']))
        ];
    }
});
