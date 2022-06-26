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
    $arrCereri = $this->DATABASE->RunQuery(sprintf(
        "SELECT cereriinterviu.idxauthangajat, " .
        "       locurimunca.titlu, " .
        "       locurimunca.idx, " .
        "       angajati.idx_optiune_gradhandicap, " .
        "       angajati.nevoispecifice, " .
        "       angajati.nume, " .
        "       angajati.prenume, " .
        "       IF(angajatori_angajati_favoriti.idxauthangajat IS NOT NULL, 1, 0) AS favorit, " .
        "       GROUP_CONCAT(DISTINCT angajati_orase.idx_oras) idx_orase, " .
        "       GROUP_CONCAT(DISTINCT angajati_domenii.idx_domeniu_cv) idx_domenii_cv " .
        "FROM `%s` cereriinterviu " .
        "LEFT JOIN `%s` locurimunca " .
        "ON (cereriinterviu.idxlocmunca = locurimunca.idx) " .
        "LEFT JOIN `%s` angajati " .
        "ON (cereriinterviu.idxauthangajat = angajati.idxauth) " .
        "LEFT JOIN `%s` angajatori_angajati_favoriti " .
        "ON (locurimunca.idxauth = angajatori_angajati_favoriti.idxauthangajator AND " .
        "   angajati.idxauth = angajatori_angajati_favoriti.idxauthangajat " .
        ") " .
        "LEFT JOIN `%s` angajati_orase " .
        "ON (angajati.idx = angajati_orase.idx_angajat) " .
        "LEFT JOIN `%s` angajati_domenii " .
        "ON (angajati.idx = angajati_domenii.idx_angajat) ".
        "WHERE cereriinterviu.idxauthangajator = %d " .
        "GROUP BY cereriinterviu.idx",
        SYSCFG_DB_PREFIX . 'cereriinterviu',
        SYSCFG_DB_PREFIX . 'locurimunca',
        SYSCFG_DB_PREFIX . 'angajati',
        SYSCFG_DB_PREFIX . 'angajatori_angajati_favoriti',
        SYSCFG_DB_PREFIX . 'angajati_orase',
        SYSCFG_DB_PREFIX . 'angajati_domenii_cv',
        (int)$arrAngajator['idxauth']
    ));
    if ($arrCereri === false) {
        throw new Exception("Eroare internă", 500);
    }

    $this->DATA['nrlocuri'] = count($arrCereri);
    $this->DATA['locuri'] = [];
    foreach ($arrCereri as $arrCerere) {
        $this->DATA['locuri'][] = [
            'nume'                    => $arrCerere['nume'] . ' ' . $arrCerere['prenume'],
            'idx_optiune_gradhandicap'=> (int)$arrCerere['idx_optiune_gradhandicap'],
            'nevoispecifice'          => $arrCerere['nevoispecifice'],
            'idxauthnevazator'        => (int)$arrCerere['idxauthangajat'],
            'favorit'                 => (int)$arrCerere['favorit'],
            'idx_orase'               => array_map(function ($val) {
                return (int)$val;
            }, explode(',', $arrCerere['idx_orase'])),
            'idx_domenii_cv'          => array_map(function ($val) {
                return (int)$val;
            }, explode(',', $arrCerere['idx_domenii_cv'])),
            'locmunca'                => [
                'idx'   => (int)$arrCerere['idx'],
                'titlu' => (string)$arrCerere['titlu'],
            ]
        ];
    }
});
