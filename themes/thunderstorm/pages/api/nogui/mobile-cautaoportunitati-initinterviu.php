<?php


$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'      => 'nullable',
        'idxangajator' => 'required|numeric'
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

    $arrAngajator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori', [
        'idxauth', '=', (int)$validation->getValue('idxangajator')
    ]);
    if ($arrAngajator === false) {
        throw new Exception("Eroare internă", 500);
    }
    if (empty($arrAngajator)) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrAngajator = $arrAngajator[0];

    if ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'cereriinterviu', [
        ['idxauthangajat', '=', (int)$arrUser['idx'], 'AND'],
        ['idxauthangajator', '=', (int)$arrAngajator['idxauth'], 'AND'],
        ['idxlocmunca', '=', 0]
    ])) {
        throw new Exception('Ați aplicat deja la această companie în trecut!', 400);
    }

    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'cereriinterviu', [
        'idxauthangajat',
        'idxauthangajator',
        'idxlocmunca',
    ], [[
        'idxauthangajat'   => (int)$arrUser['idx'],
        'idxauthangajator' => (int)$arrAngajator['idxauth'],
        'idxlocmunca'      => 0
    ]]);
    if (!$res) {
        throw new Exception("Eroare internă", 500);
    }
});
