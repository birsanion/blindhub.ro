<?php

function getPlaceholderImg() {
    $dir = "placeholder/";
    $images = glob("media/uploads/{$dir}*.png");
    $randKey = array_rand($images);
    return $dir . basename($images[$randKey]);
}


$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
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

    ]);

    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $error = array_values($errors->firstOfAll())[0];
        throw new Exception("EROARE: {$error}!", 400);
    }

     $arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users', [
        'idx', '=', $this->AUTH->GetUserId()
    ]);
    if ($arrUser === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    if (empty($arrUser)) {
        throw new Exception("EROARE: acest utilizator nu există !", 400);
    }

    $arrUser = $arrUser[0];
    if ($arrUser['tiputilizator'] != 2) {
        throw new Exception("EROARE: acest utilizator nu este de tip universitate !", 400);
    }

    $arrUniversitate = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati', [
        'idxauth', '=', $arrUser['idx']
    ]);
    if ($arrUniversitate === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrUniversitate)) {
        throw new Exception("EROARE: acesta universitate nu există !", 400);
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
        throw new Exception("EROARE INTERNA", 500);
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
        throw new Exception("EROARE INTERNA", 500);
    }

    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'universitati_orase', ['idx_universitate', 'idx_oras'], $arrUniversitateOrase);
    if ($res === false) {
        throw new Exception($this->DATABASE->GetLastQuery(), 500);
    }
});
