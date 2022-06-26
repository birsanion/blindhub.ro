<?php

function getPlaceholderImg() {
    $dir = "placeholder/";
    $images = glob("media/uploads/{$dir}*.png");
    $randKey = array_rand($images);
    return $dir . basename($images[$randKey]);
}


$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'email'                            => 'required_without:userkey|email',
        'parola'                           => 'required_without:userkey',
        'nume'                             => 'required',
        'reprezentant'                     => 'required',
        'idx_orase'                        => 'required|array',
        'idx_orase.*'                      => 'numeric',
        'idx_optiune_gradacces'            => 'required|numeric',
        'idx_optiune_gradechipare'         => 'required|numeric',
        'studdiz'                          => 'required|boolean',
        'studcentru'                       => 'required|boolean',
        'camerecamine'                     => 'required|boolean',
        'persdedic'                        => 'required|boolean',
        'braille'                          => 'required|boolean',
        'idx_optiune_accesibilizare_clasa' => 'required|numeric',
        'userkey'                          => 'nullable'

    ]);

    $validation->validate();
    if ($validation->fails()) {
        //$errors = $validation->errors();
        //$error = array_values($errors->firstOfAll())[0];
        throw new Exception("Cerere invalidă", 400);
    }

    $nUserIdx = 0;
    if (!$validation->getValue('userkey')) {
        $nNewUserResult = $this->AUTH->AddNewUser($validation->getValue('email'), $validation->getValue('parola'), $nUserIdx);
        if ($nNewUserResult != AUTH_SUCCESS) {
            throw new Exception("EROARE: nu poate fi adăugat acest utilizator! Poate contul există deja?", 400);
        }

        $this->AUTH->ChangeAdvancedDetails([
            'tiputilizator' => 2
        ], $nUserIdx);

        $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'universitati', [
            'idxauth',
            'nume',
            'reprezentant',
            'idx_optiune_gradacces',
            'idx_optiune_gradechipare',
            'studdiz',
            'studcentru',
            'camerecamine',
            'persdedic',
            'braille',
            'idx_optiune_accesibilizare_clasa',
            'img',
        ], [[
            'idxauth'                          => $nUserIdx,
            'nume'                             => $validation->getValue('nume'),
            'reprezentant'                     => $validation->getValue('reprezentant'),
            'idx_optiune_gradacces'            => $validation->getValue('idx_optiune_gradacces'),
            'idx_optiune_gradechipare'         => $validation->getValue('idx_optiune_gradechipare'),
            'studdiz'                          => $validation->getValue('studdiz'),
            'studcentru'                       => $validation->getValue('studcentru'),
            'camerecamine'                     => $validation->getValue('camerecamine'),
            'persdedic'                        => $validation->getValue('persdedic'),
            'braille'                          => $validation->getValue('braille'),
            'idx_optiune_accesibilizare_clasa' => $validation->getValue('idx_optiune_accesibilizare_clasa'),
            'img'                              => getPlaceholderImg()
        ]]);
        if ($res === false) {
            throw new Exception("Eroare internă", 500);
        }

        $idxUniversitate = $this->DATABASE->GetLastInsertID();
        $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'auth_userpermissions',
            'usridx, target, perm',
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

        $arrUniversitateOrase = [];
        foreach ($validation->getValue('idx_orase') as $idxOras) {
            $arrUniversitateOrase[] = [
                'idx_universitate' => $idxUniversitate,
                'idx_oras'         => $idxOras
            ];
        }

        $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'universitati_orase', ['idx_universitate', 'idx_oras'], $arrUniversitateOrase);
        if ($res === false) {
            throw new Exception("Eroare internă", 500);
        }

        // login
        $kNewAuth = new CQAuth();
        $kNewAuth->Init($this->DATABASE, $this->CONFIG);

        $nLoginCode = $kNewAuth->LogIn($validation->getValue('email'), $validation->getValue('parola'));
        if ($nLoginCode != AUTH_SUCCESS) {
            throw new Exception("Eroare internă", 500);
        }

        $strUserKey = $kNewAuth->GetNewSalt();
        while ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'auth_users', ['apploginid', '=', $strUserKey]) > 0) {
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
        if ($arrUser['tiputilizator'] != 2) {
            throw new Exception("Cerere invalidă", 400);
        }

        $arrUniversitate = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati', [
            'idxauth', '=', $arrUser['idx']
        ]);
        if ($arrUniversitate === false) {
            throw new Exception("Eroare internă", 500);
        }
        if (empty($arrUniversitate)) {
            throw new Exception("Cerere invalidă", 400);
        }

        $arrUniversitate = $arrUniversitate[0];
        $res = $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'universitati', [
            'nume',
            'reprezentant',
            'idx_optiune_gradacces',
            'idx_optiune_gradechipare',
            'studdiz',
            'studcentru',
            'camerecamine',
            'persdedic',
            'braille',
            'idx_optiune_accesibilizare_clasa',
        ], [
            'nume'                             => $validation->getValue('nume'),
            'reprezentant'                     => $validation->getValue('reprezentant'),
            'idx_optiune_gradacces'            => $validation->getValue('idx_optiune_gradacces'),
            'idx_optiune_gradechipare'         => $validation->getValue('idx_optiune_gradechipare'),
            'studdiz'                          => $validation->getValue('studdiz'),
            'studcentru'                       => $validation->getValue('studcentru'),
            'camerecamine'                     => $validation->getValue('camerecamine'),
            'persdedic'                        => $validation->getValue('persdedic'),
            'braille'                          => $validation->getValue('braille'),
            'idx_optiune_accesibilizare_clasa' => $validation->getValue('idx_optiune_accesibilizare_clasa'),
        ], [
            'idxauth', '=', (int)$arrUser['idx']
        ]);
        if ($res === false) {
            throw new Exception("Eroare internă", 500);
        }

        $arrUniversitateOrase = [];
        foreach ($validation->getValue('idx_orase') as $idxOras) {
            $arrUniversitateOrase[] = [
                'idx_universitate' => $arrUniversitate['idx'],
                'idx_oras'         => $idxOras
            ];
        }

        $res = $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'universitati_orase', [
            'idx_universitate', '=', $arrUniversitate['idx'],
        ]);
        if ($res === false) {
            throw new Exception("Eroare internă", 500);
        }

        $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'universitati_orase', ['idx_universitate', 'idx_oras'], $arrUniversitateOrase);
        if ($res === false) {
            throw new Exception("Eroare internă", 500);
        }
    }
});
