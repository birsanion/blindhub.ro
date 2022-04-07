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
        throw new Exception("EROARE: acest utilizator nu este de tip angajator !", 400);
    }

    $arrAngajator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori', [
        'idxauth', '=', $arrUser['idx']
    ]);
    if ($arrAngajator === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrAngajator)) {
        throw new Exception("EROARE: acest angajator nu există !", 400);
    }

    $arrAngajator = $arrAngajator[0];

    $arrAngajati = $this->DATABASE->RunQuery(sprintf(
        "SELECT angajati.*, " .
        "       GROUP_CONCAT(DISTINCT angajati_orase.idx_oras) idx_orase, " .
        "       GROUP_CONCAT(DISTINCT angajati_domenii.idx_domeniu_cv) idx_domenii_cv " .
        "FROM `%s` angajatori_angajati_favoriti " .
        "INNER JOIN `%s` angajati " .
        "ON (angajatori_angajati_favoriti.idxauthangajat = angajati.idxauth) " .
        "INNER JOIN `%s` angajati_orase " .
        "ON (angajati_orase.idx_angajat = angajati.idx) " .
        "INNER JOIN `%s` angajati_domenii " .
        "ON (angajati_domenii.idx_angajat = angajati.idx) " .
        "WHERE angajatori_angajati_favoriti.idxauthangajator = %d " .
        "GROUP BY angajati.idx ",
        SYSCFG_DB_PREFIX . 'angajatori_angajati_favoriti',
        SYSCFG_DB_PREFIX . 'angajati',
        SYSCFG_DB_PREFIX . 'angajati_orase',
        SYSCFG_DB_PREFIX . 'angajati_domenii_cv',
        (int)$arrAngajator['idxauth']
    ));
    if ($arrAngajati === false) {
        throw new Exception($this->DATABASE->GetError(), 500);
    }

    $this->DATA['nrcandidati'] = count($arrAngajati);
    $this->DATA['candidati'] = [];
    foreach ($arrAngajati as $arrAngajat) {
        $res = [
            'nume'                    => $arrAngajat['nume'] . ' ' . $arrAngajat['prenume'],
            'idx_optiune_gradhandicap'=> (int)$arrAngajat['idx_optiune_gradhandicap'],
            'nevoispecifice'          => $arrAngajat['nevoispecifice'],
            'idxauthnevazator'        => (int)$arrAngajat['idxauth'],
            'favorit'                 => 1,
            'idx_orase'               => array_map(function ($val) {
                return (int)$val;
            }, explode(',', $arrAngajat['idx_orase'])),
            'idx_domenii_cv'          => array_map(function ($val) {
                return (int)$val;
            }, explode(',', $arrAngajat['idx_domenii_cv'])),
        ];
        $this->DATA['candidati'][] = $res;
    }
});
