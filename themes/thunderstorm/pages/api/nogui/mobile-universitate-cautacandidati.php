<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey' => 'required',
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
    if ($arrUser['tiputilizator'] != 2) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrCereri = $this->DATABASE->RunQuery(sprintf(
        "SELECT cereriinterviuuniversitate.idxauthangajat, " .
        "       angajati.nume, " .
        "       angajati.prenume, " .
        "       angajati.idx_optiune_gradhandicap, " .
        "       angajati.nevoispecifice, " .
        "       locuriuniversitate.* " .
        "FROM `%s` cereriinterviuuniversitate " .
        "INNER JOIN `%s` locuriuniversitate " .
        "ON (cereriinterviuuniversitate.idxlocuniversitate = locuriuniversitate.idx) " .
        "INNER JOIN `%s` angajati " .
        "ON (cereriinterviuuniversitate.idxauthangajat = angajati.idxauth) " .
        "WHERE cereriinterviuuniversitate.idxauthuniversitate = %d ",
        SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate',
        SYSCFG_DB_PREFIX . 'locuriuniversitate',
        SYSCFG_DB_PREFIX . 'angajati',
        $arrUser['idx']
    ));
    if ($arrCereri === false) {
        throw new Exception("Eroare internă", 500);
    }

    $this->DATA['nrlocuri'] = count($arrCereri);
    $this->DATA['locuri'] = [];
    foreach ($arrCereri as $arrCerere) {
        $this->DATA['locuri'][] = [
            'nume'                     => $arrCerere['nume'] . ' ' . $arrCerere['prenume'],
            'idx_optiune_gradhandicap' => (int)$arrCerere['idx_optiune_gradhandicap'],
            'nevoispecifice'           => $arrCerere['nevoispecifice'],
            'idxauthnevazator'         => (int)$arrCerere['idxauthangajat'],
            'locuniversitate'          => [
                'idx'                      => (int)$arrCerere['idx'],
                'facultate'                => $arrCerere['facultate'],
                'idx_domeniu_universitate' => (int)$arrCerere['idx_domeniu_universitate'],
                'numarlocuri'              => (int)$arrCerere['numarlocuri'],
                'idx_oras'                 => (int)$arrCerere['idx_oras'],
            ]
        ];
    }
});
