<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'      => 'required',
        'idxauthinter' => 'required|numeric',
        'mesaj'        => 'required'
    ]);

    $validation->validate();
    if ($validation->fails()) {
        // $errors = $validation->errors();
        // $error = array_values($errors->firstOfAll())[0];
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
    switch ((int)$arrUser['tiputilizator']) {
        case 0: // nevazatori
            $arrAngajat = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati', [
                'idxauth', '=', $arrUser['idx']
            ]);
            if ($arrAngajat === false) {
                throw new Exception("Eroare internă", 500);
            }
            if (empty($arrAngajat)) {
                throw new Exception("Cerere invalidă", 400);
            }

            $idxAuthAngajat = $arrUser['idx'];
            $idxAuthInterlocutor = $validation->getValue('idxauthinter');
            $idxAuthMesaj = $arrUser['idx'];
            $titluNotificare = $arrAngajat[0]['nume'] . " " . $arrAngajat[0]['prenume'];
            break;

        case 1: // angajatori
            $arrAngajator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori', [
                'idxauth', '=', $arrUser['idx']
            ]);
            if ($arrAngajator === false) {
                throw new Exception("Eroare internă", 500);
            }
            if (empty($arrAngajator)) {
                throw new Exception("Cerere invalidă", 400);
            }

            $idxAuthAngajat = $validation->getValue('idxauthinter');
            $idxAuthInterlocutor = $arrUser['idx'];
            $idxAuthMesaj = $arrUser['idx'];
            $titluNotificare = $arrAngajator[0]['companie'];
            break;

        case 2: // universitati
            $arrUniversitate = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati', [
                'idxauth', '=', $arrUser['idx']
            ]);
            if ($arrUniversitate === false) {
                throw new Exception("Eroare internă", 500);
            }
            if (empty($arrUniversitate)) {
                throw new Exception("Cerere invalidă", 400);
            }

            $idxAuthAngajat = $validation->getValue('idxauthinter');
            $idxAuthInterlocutor = $arrUser['idx'];
            $idxAuthMesaj = $arrUser['idx'];
            $titluNotificare = $arrUniversitate[0]['nume'];
            break;

        default:
            throw new Exception("Cerere invalidă", 400);

    }

    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'mesaje', [
        'idxauthangajat',
        'idxauthinterlocutor',
        'idxauthmesaj',
        'mesaj'
    ], [[
        'idxauthangajat'      => $idxAuthAngajat,
        'idxauthinterlocutor' => $idxAuthInterlocutor,
        'idxauthmesaj'        => $idxAuthMesaj,
        'mesaj'               => $validation->getValue('mesaj')
    ]]);
    if (!$res) {
        throw new Exception("Eroare internă", 500);
    }

    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'notificari', [
        'idxauth',
        'idxmesaj',
        'titlu',
        'mesaj',
    ], [[
        'idxauth'  => $validation->getValue('idxauthinter'),
        'idxmesaj' => $this->DATABASE->GetLastInsertID(),
        'titlu'    => $titluNotificare,
        'mesaj'    => $validation->getValue('mesaj'),
    ]]);

    if (!$res) {
        throw new Exception("Eroare internă", 500);
    }
});
