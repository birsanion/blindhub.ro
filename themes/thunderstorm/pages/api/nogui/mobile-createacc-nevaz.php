<?php

function getPlaceholderImg() {
    $dir = "placeholder/";
    $images = glob("media/uploads/{$dir}*.png");
    $randKey = array_rand($images);
    return $dir . basename($images[$randKey]);
}

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'email'                    => 'required_without:userkey|email',
        'parola'                   => 'required_without:userkey',
        'nume'                     => 'required',
        'prenume'                  => 'required',
        'idx_optiune_gradhandicap' => 'required|numeric',
        'nevoispecifice'           => 'nullable',
        'userkey'                  => 'nullable'
    ]);

    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $error = array_values($errors->firstOfAll())[0];
        throw new Exception("EROARE: {$error}!", 400);
    }

    $nUserIdx = 0;
    if (!$validation->getValue('userkey')) {
        $nNewUserResult = $this->AUTH->AddNewUser($validation->getValue('email'), $validation->getValue('parola'), $nUserIdx);
        if ($nNewUserResult != AUTH_SUCCESS) {
            throw new Exception('EROARE: nu poate fi adăugat acest utilizator!', 400);
        }

        $this->AUTH->ChangeAdvancedDetails([
            'tiputilizator' => 0
        ], $nUserIdx);

        $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajati', [
            'idxauth',
            'nume',
            'prenume',
            'idx_optiune_gradhandicap',
            'nevoispecifice',
            'img',
        ], [[
            'idxauth'                  => $nUserIdx,
            'nume'                     => $validation->getValue('nume'),
            'prenume'                  => $validation->getValue('prenume'),
            'idx_optiune_gradhandicap' => $validation->getValue('idx_optiune_gradhandicap'),
            'nevoispecifice'           => $validation->getValue('nevoispecifice'),
            'img'                      => getPlaceholderImg(),
        ]]);
        if (!$res) {
            throw new Exception($this->DATABASE->GetError(), 500);
        }

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
        if (!$res) {
            throw new Exception("EROARE INTERNA", 500);
        }

        // login
        $kNewAuth = new CQAuth();
        $kNewAuth->Init($this->DATABASE, $this->CONFIG);

        $nLoginCode = $kNewAuth->LogIn($validation->getValue('email'), $validation->getValue('parola'));
        if ($nLoginCode !== AUTH_SUCCESS) {
            throw new Exception("EROARE: nu poate fi autentificat automat utilizatorul !", 500);
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
            throw new Exception("EROARE INTERNA", 500);
        }

        if (empty($arrUser)) {
            throw new Exception("EROARE: acest utilizator nu există !", 400);
        }

        $arrUser = $arrUser[0];
        if ($arrUser['tiputilizator'] != 0) {
            throw new Exception("EROARE: acest utilizator nu este de tip universitate!", 400);
        }

        $res = $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'angajati', [
            'nume',
            'prenume',
            'idx_optiune_gradhandicap',
            'nevoispecifice',
        ], [
            'nume'                     => $validation->getValue('nume'),
            'prenume'                  => $validation->getValue('prenume'),
            'idx_optiune_gradhandicap' => $validation->getValue('idx_optiune_gradhandicap'),
            'nevoispecifice'           => $validation->getValue('nevoispecifice'),
        ], [
            'idxauth', '=', (int)$arrUser['idx']
        ]);
        if (!$res) {
            throw new Exception("EROARE INTERNA", 500);
        }
    }
});
