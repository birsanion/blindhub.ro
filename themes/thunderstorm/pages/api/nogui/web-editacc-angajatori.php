<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'companie'                    => 'required',
        'adresa'                      => 'required',
        'cui'                         => 'required',
        'idx_domenii_cv'              => 'required|array',
        'idx_domenii_cv.*'            => 'numeric',
        'idx_orase'                   => 'required|array',
        'idx_orase.*'                 => 'numeric',
        'firmaprotejata'              => 'required|boolean',
        'idx_optiune_dimensiunefirma' => 'required|numeric',
    ]);

    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $error = array_values($errors->firstOfAll())[0];
        throw new Exception("EROARE: {$error}!", 400);
    }

    $arrAngajator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori', [
        'idxauth', '=', $this->AUTH->GetUserId()
    ]);
    if ($arrAngajator === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrAngajator)) {
        throw new Exception("EROARE: acest angajator nu există !", 400);
    }

    $arrAngajator = $arrAngajator[0];
    $res = $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'angajatori', [
        'companie',
        'adresa',
        'firmaprotejata',
        'idx_optiune_dimensiunefirma',
    ], [
        'companie'                    => $validation->getValue('companie'),
        'adresa'                      => $validation->getValue('adresa'),
        'firmaprotejata'              => $validation->getValue('firmaprotejata'),
        'idx_optiune_dimensiunefirma' => $validation->getValue('idx_optiune_dimensiunefirma'),
    ], [
        'idxauth', '=', $this->AUTH->GetUserId()
    ]);
    if ($res === false) {
        throw new Exception("EROARE INTERNAa", 500);
    }

    $arrAngajatorOrase = [];
    foreach ($validation->getValue('idx_orase') as $idxOras) {
        $arrAngajatorOrase[] = [
            'idx_angajator' => $arrAngajator['idx'],
            'idx_oras'      => $idxOras
        ];
    }

    $res = $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'angajatori_orase', [
        'idx_angajator', '=', $arrAngajator['idx'],
    ]);
    if ($res === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajatori_orase', ['idx_angajator', 'idx_oras'], $arrAngajatorOrase);
    if ($res === false) {
        throw new Exception($this->DATABASE->GetError(), 500);
    }

    $arrAngajatorDomenii = [];
    foreach ($validation->getValue('idx_domenii_cv') as $idxDomeniu) {
        $arrAngajatorDomenii[] = [
            'idx_angajator'  => $arrAngajator['idx'],
            'idx_domeniu_cv' => $idxDomeniu
        ];
    }

    $res = $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'angajatori_domenii_cv', [
        'idx_angajator', '=', $arrAngajator['idx'],
    ]);
    if ($res === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajatori_domenii_cv', ['idx_angajator', 'idx_domeniu_cv'], $arrAngajatorDomenii);
    if ($res === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
});
