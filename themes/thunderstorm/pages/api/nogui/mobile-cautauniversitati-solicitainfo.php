<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'idxauthuniversitate' => 'required|numeric',
        'idxloc'              => 'required|numeric',
        'userkey'             => 'nullable'
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

    $res = $this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate', [
        ['idxauthangajat', '=', (int)$arrUser['idx'], 'AND'],
        ['idxauthuniversitate', '=', (int)$validation->getValue('idxauthuniversitate'), 'AND'],
        ['idxlocuniversitate', '=', (int)$validation->getValue('idxloc')]
    ]);
    if ($res === false) {
        throw new Exception("Eroare internă", 500);
    }
    if ($res) {
        throw new Exception("Ați aplicat deja pentru acest interviu în trecut!", 400);
    }

    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate', [
        'idxauthangajat',
        'idxauthuniversitate',
        'idxlocuniversitate',
    ], [[
        'idxauthangajat'      => (int)$arrUser['idx'],
        'idxauthuniversitate' => (int)$validation->getValue('idxauthuniversitate'),
        'idxlocuniversitate'  => (int)$validation->getValue('idxloc')
    ]]);
    if (!$res) {
        throw new Exception("Eroare internă", 500);
    }
});
