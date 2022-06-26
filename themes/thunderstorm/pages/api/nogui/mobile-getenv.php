<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'appkey' => 'required',
    ]);

    $validation->validate();
    if ($validation->fails()) {
        throw new Exception("Cerere invalidă", 400);
    }

    if ($validation->getValue('appkey') != '8GLmkhmzDwP6wsFXTLUPs9kptX6Swb') {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrOrase = $this->DATABASE->RunQuickSelect(['idx', 'nume'], SYSCFG_DB_PREFIX . 'orase', NULL, ['nume']);
    if ($arrOrase === false) {
        throw new Exception("Eroare internă", 500);
    }

    $arrDomeniiCv = $this->DATABASE->RunQuickSelect(['idx', 'nume'], SYSCFG_DB_PREFIX . 'domenii_cv', NULL, ['nume']);
    if ($arrDomeniiCv === false) {
        throw new Exception("Eroare internă", 500);
    }

    $arrDomeniiUniversitate = $this->DATABASE->RunQuickSelect(['idx', 'nume'], SYSCFG_DB_PREFIX . 'domenii_universitate', NULL, ['nume']);
    if ($arrDomeniiUniversitate === false) {
        throw new Exception("Eroare internă", 500);
    }

    $arrOptiuni = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'optiuni', NULL, ['categorie', 'nume']);
    if ($arrOptiuni === false) {
        throw new Exception("Eroare internă", 500);
    }

    $this->DATA['mentenanta'] = false;
    $this->DATA['orase'] = [];
    foreach ($arrOrase as $arrOras) {
        $this->DATA['orase'][$arrOras['idx']] = $arrOras['nume'];
    }

    $this->DATA['domenii_cv'] = [];
    foreach ($arrDomeniiCv as $arrDomeniu) {
        $this->DATA['domenii_cv'][$arrDomeniu['idx']] = $arrDomeniu['nume'];
    }

    $this->DATA['domenii_universitate'] = [];
    foreach ($arrDomeniiUniversitate as $arrDomeniu) {
        $this->DATA['domenii_universitate'][$arrDomeniu['idx']] = $arrDomeniu['nume'];
    }

    $this->DATA['optiuni'] = [];
    foreach ($arrOptiuni as $arrOptiune) {
        $this->DATA['optiuni'][$arrOptiune['categorie']][$arrOptiune['idx']] = $arrOptiune['nume'];
    }
});
