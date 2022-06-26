<?php

function getPlaceholderImg() {
    $dir = "placeholder/";
    $images = glob("media/uploads/{$dir}*.png");
    $randKey = array_rand($images);
    return $dir . basename($images[$randKey]);
}

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'email'                       => 'required_without:userkey|email',
        'parola'                      => 'required_without:userkey',
        'companie'                    => 'required',
        'adresa'                      => 'required',
        'cui'                         => 'required_without:userkey',
        'idx_domenii_cv'              => 'required|array',
        'idx_domenii_cv.*'            => 'numeric',
        'idx_orase'                   => 'required|array',
        'idx_orase.*'                 => 'numeric',
        'firmaprotejata'              => 'required|boolean',
        'idx_optiune_dimensiunefirma' => 'required|numeric',
        'userkey'                     => 'nullable'
    ]);

    $validation->validate();
    if ($validation->fails()) {
        throw new Exception("Cerere invalidă", 400);
    }

    $nUserIdx = 0;
    if (!$validation->getValue('userkey')) {
        $nNewUserResult = $this->AUTH->AddNewUser($validation->getValue('email'), $validation->getValue('parola'), $nUserIdx);
        if ($nNewUserResult !== AUTH_SUCCESS) {
            throw new Exception("EROARE: nu poate fi adăugat acest utilizator! Poate contul există deja?", 400);
        }

        $this->AUTH->ChangeAdvancedDetails([
            'tiputilizator' => 1
        ], $nUserIdx);


        $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajatori', [
            'idxauth',
            'companie',
            'adresa',
            'cui',
            'firmaprotejata',
            'idx_optiune_dimensiunefirma',
            'img'
        ], [[
            'idxauth'                     => $nUserIdx,
            'companie'                    => $validation->getValue('companie'),
            'adresa'                      => $validation->getValue('adresa'),
            'cui'                         => $validation->getValue('cui'),
            'firmaprotejata'              => $validation->getValue('firmaprotejata'),
            'idx_optiune_dimensiunefirma' => $validation->getValue('idx_optiune_dimensiunefirma'),
            'img'                         => getPlaceholderImg(),
        ]]);
        if ($res === false) {
            throw new Exception("Eroare internă", 500);
        }

        $idxAngajator = $this->DATABASE->GetLastInsertID();
        $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'auth_userpermissions',
            [
                'usridx',
                'target',
                'perm',
            ],
            [
                [
                    'usridx' => $nUserIdx,
                    'target' => '*',
                    'perm'   => '0'
                ],
                [
                    'usridx' => $nUserIdx,
                    'target' => '*/index',
                    'perm'   => '1'
                ],
                [
                    'usridx' => $nUserIdx,
                    'target' => '*/nogui',
                    'perm'   => '1'
                ]
            ]
        );
        if ($res === false) {
            throw new Exception("Eroare internă", 500);
        }

        $arrAngajatorOrase = [];
        foreach ($validation->getValue('idx_orase') as $idxOras) {
            $arrAngajatorOrase[] = [
                'idx_angajator' => $idxAngajator,
                'idx_oras'      => $idxOras
            ];
        }

        $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajatori_orase', ['idx_angajator', 'idx_oras'], $arrAngajatorOrase);
        if ($res === false) {
            throw new Exception("Eroare internă", 500);
        }

        $arrAngajatorDomenii = [];
        foreach ($validation->getValue('idx_domenii_cv') as $idxDomeniu) {
            $arrAngajatorDomenii[] = [
                'idx_angajator'  => $idxAngajator,
                'idx_domeniu_cv' => $idxDomeniu
            ];
        }

        $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajatori_domenii_cv', ['idx_angajator', 'idx_domeniu_cv'], $arrAngajatorDomenii);
        if ($res === false) {
            throw new Exception("Eroare internă", 500);
        }


        $this->DATA['idxuser'] = $nUserIdx;
         // login
        $kNewAuth = new CQAuth();
        $kNewAuth->Init($this->DATABASE, $this->CONFIG);

        $nLoginCode = $kNewAuth->LogIn($validation->getValue('email'), $validation->getValue('parola'));
        if ($nLoginCode !== AUTH_SUCCESS) {
            throw new Exception("Eroare internă", 500);
        }

        $strUserKey = $kNewAuth->GetNewSalt();
        while ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'auth_users', [
            'apploginid', '=', $strUserKey
        ]) > 0) {
            $strUserKey = $kNewAuth->GetNewSalt();
        }

        $arrAllDetails = [];
        $arrAllDetails['apploginid'] = $strUserKey;
        $kNewAuth->ChangeAdvancedDetails($arrAllDetails);
        $this->DATA['userkey'] = $strUserKey;
    } else {
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
        if ($arrUser['tiputilizator'] != 1) {
            throw new Exception("Cerere invalidă", 400);
        }

        $arrAngajator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori', [
            'idxauth', '=', $arrUser['idx']
        ]);
        if ($arrAngajator === false) {
            throw new Exception("Eroare internă", 500);
        }
        if (empty($arrAngajator)) {
            throw new Exception("Cerere invalidă", 400);
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
            'idxauth', '=', (int)$arrUser['idx']
        ]);
        if ($res === false) {
            throw new Exception("Eroare internă", 500);
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
            throw new Exception("Eroare internă", 500);
        }

        $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajatori_orase', ['idx_angajator', 'idx_oras'], $arrAngajatorOrase);
        if ($res === false) {
            throw new Exception("Eroare internă", 500);
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
            throw new Exception("Eroare internă", 500);
        }

        $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajatori_domenii_cv', ['idx_angajator', 'idx_domeniu_cv'], $arrAngajatorDomenii);
        if ($res === false) {
            throw new Exception("Eroare internă", 500);
        }
    }
});
