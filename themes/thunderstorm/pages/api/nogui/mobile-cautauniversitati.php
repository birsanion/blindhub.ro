<?php

$this->handleAPIRequest(function() {
    $this->DATA['nrrezultate'] = 0;
    $this->DATA['rezultate'] = [];

    $validation = $this->validator->make($_POST, [
        'idxauthuniversitate'      => 'required',
        'idx_domeniu_universitate' => 'required|numeric',
        'userkey'                  => 'nullable',
    ]);

    $validation->validate();
    if ($validation->fails()) {
        throw new Exception("Cerere invalidă", 400);
    }

    $conds = [];
    if ($validation->getValue('userkey')) {
        $conds = [ 'apploginid', '=', $validation->getValue('userkey') ];
    } else if ($this->AUTH->IsAuthenticated()) {
        $conds = [ 'idx', '=', $this->AUTH->GetUserId() ];
    } else {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users', $conds);
    if ($arrUser === false) {
        throw new Exception("Eroare internă", 500);
    }

    if (empty($arrUser)) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrUser = $arrUser[0];
    if ($arrUser['tiputilizator'] != 0) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrUniversitate = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati', [
        ['idxauth', '=', $validation->getValue('idxauthuniversitate')],
    ]);
    if ($arrUniversitate === false) {
        throw new Exception("Eroare internă", 500);
    }
    if (empty($arrUniversitate)) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrUniversitate = $arrUniversitate[0];
    $arrLocuri = $this->DATABASE->RunQuery(sprintf(
        "SELECT locuriuniversitate.*, orase.nume AS oras " .
        "FROM `%s` locuriuniversitate " .
        "INNER JOIN `%s` orase " .
        "ON (locuriuniversitate.idx_oras = orase.idx) " .
        "WHERE locuriuniversitate.idx_domeniu_universitate = %d " .
        "AND locuriuniversitate.idxauth = %d ",
        SYSCFG_DB_PREFIX . 'locuriuniversitate',
        SYSCFG_DB_PREFIX . 'orase',
        $validation->getValue('idx_domeniu_universitate'),
        (int)$arrUniversitate['idxauth']
    ));
    if ($arrLocuri === false) {
        throw new Exception("Eroare internă", 500);
    }

    $this->DATA['nrrezultate'] = 0;
    $this->DATA['rezultate'] = [];
    foreach ($arrLocuri as $arrLoc) {
        $this->DATA['nrrezultate']++;
        $this->DATA['rezultate'][] = [
            'numeuniversitate'         => $arrUniversitate['nume'],
            'idxauth'                  => (int)$arrLoc['idxauth'],
            'facultate'                => $arrLoc['facultate'],
            'nrlocuri'                 => (int)$arrLoc['numarlocuri'],
            'idxloc'                   => (int)$arrLoc['idx'],
            'idx_oras'                 => (int)$arrLoc['idx_oras'],
            'oras'                     => $arrLoc['oras'],
            'idx_domeniu_universitate' => (int)$arrLoc['idx_domeniu_universitate'],
        ];
    }
});
