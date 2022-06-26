<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'  => 'nullable',
        'idx_oras' => 'required|numeric'
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
        throw new Exception("Eroare internă", 500);
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
