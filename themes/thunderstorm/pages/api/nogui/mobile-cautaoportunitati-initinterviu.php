<?php


$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'      => 'required',
        'idxangajator' => 'required|numeric'
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
    if ($arrUser['tiputilizator'] != 0) {
        throw new Exception("EROARE: acest utilizator nu este de tip angajat !", 400);
    }

    $arrAngajator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori', [
        'idxauth', '=', (int)$validation->getValue('idxangajator')
    ]);
    if ($arrAngajator === false) {
        throw new Exception("EROARE INTERNAa", 500);
    }
    if (empty($arrAngajator)) {
        throw new Exception("EROARE: acest angajator nu exista", 400);
    }

    $arrAngajator = $arrAngajator[0];

    if ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'cereriinterviu', [
        ['idxauthangajat', '=', (int)$arrUser['idx'], 'AND'],
        ['idxauthangajator', '=', (int)$arrAngajator['idxauth'], 'AND'],
        ['idxlocmunca', '=', 0]
    ])) {
        throw new Exception('Ați aplicat deja la această companie în trecut !', 400);
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
        throw new Exception("EROARE: Nu s-a putut adăuga cererea de interviu !", 500);
    }
});
