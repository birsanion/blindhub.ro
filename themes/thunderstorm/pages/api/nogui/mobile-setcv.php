<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'          => 'nullable',
        'idx_orase'        => 'required|array',
        'idx_orase.*'      => 'numeric',
        'idx_domenii_cv'   => 'required|array',
        'idx_domenii_cv.*' => 'numeric',
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

    $arrAngajat = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati', [
        'idxauth', '=', $arrUser['idx']
    ]);
    if ($arrAngajat === false) {
        throw new Exception("Eroare internă", 500);
    }
    if (empty($arrAngajat)) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrAngajat = $arrAngajat[0];
    $arrAngajatOrase = [];
    foreach ($validation->getValue('idx_orase') as $idx_oras) {
        $arrAngajatOrase[] = [
            'idx_angajat' => $arrAngajat['idx'],
            'idx_oras'    => $idx_oras
        ];
    }

    $res = $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'angajati_orase', [
        'idx_angajat', '=', $arrAngajat['idx']
    ]);
    if ($res === false) {
        throw new Exception("Eroare internă", 500);
    }

    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajati_orase', ['idx_angajat', 'idx_oras'], $arrAngajatOrase);
    if ($res === false) {
        throw new Exception("Eroare internă", 500);
    }

    $arrAngajatDomenii = [];
    foreach ($validation->getValue('idx_domenii_cv') as $idx_domeniu) {
        $arrAngajatDomenii[] = [
            'idx_angajat'    => $arrAngajat['idx'],
            'idx_domeniu_cv' => $idx_domeniu
        ];
    }

    $res = $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'angajati_domenii_cv', [
        'idx_angajat', '=', $arrAngajat['idx'],
    ]);
    if ($res === false) {
        throw new Exception("Eroare internă", 500);
    }

    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajati_domenii_cv', ['idx_angajat', 'idx_domeniu_cv'], $arrAngajatDomenii);
    if ($res === false) {
        throw new Exception("Eroare internă", 500);
    }
});
