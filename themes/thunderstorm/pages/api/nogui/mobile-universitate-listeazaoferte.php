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

    $arrData = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locuriuniversitate', [
        'idxauth', '=', (int)$arrUser['idx']
    ]);
    if ($arrData === false) {
        throw new Exception("Eroare internă", 500);
    }

    $this->DATA['nrlocuri'] = count($arrData);
    $this->DATA['locuri'] = [];
    foreach ($arrData as $loc) {
        $this->DATA['locuri'][] = [
            'idx'                      => (int)$loc['idx'],
            'nume'                     => $loc['facultate'],
            'numarlocuri'              => (int)$loc['numarlocuri'],
            'idx_domeniu_universitate' => (int)$loc['idx_domeniu_universitate'],
            'idx_oras'                 => (int)$loc['idx_oras'],
        ];
    }
});
