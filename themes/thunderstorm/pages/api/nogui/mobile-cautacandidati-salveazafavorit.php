<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'          => 'nullable',
        'idxauthnevazator' => 'required|numeric'
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
    if ($arrUser['tiputilizator'] != 1) {
        throw new Exception("Cerere invalidă", 400);
    }

    $res = $this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'angajatori_angajati_favoriti', [
        ['idxauthangajator', '=', (int)$arrUser['idx'], 'AND'],
        ['idxauthangajat', '=', $validation->getValue('idxauthnevazator')],
    ]);
    if ($res === false) {
        throw new Exception("Eroare internă", 500);
    }
    if (!$res) {
        $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajatori_angajati_favoriti', [
            'idxauthangajator',
            'idxauthangajat',
        ], [[
            'idxauthangajator' => (int)$arrUser['idx'],
            'idxauthangajat'   => $validation->getValue('idxauthnevazator'),
        ]]);
        if (!$res) {
            throw new Exception("Eroare internă", 500);
        }
    } else {
        $res = $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'angajatori_angajati_favoriti', [
            ['idxauthangajator', '=', (int)$arrUser['idx'], 'AND'],
            ['idxauthangajat', '=', $validation->getValue('idxauthnevazator')],
        ]);
        if ($res === false) {
            throw new Exception("Eroare internă", 500);
        }
    }
});
