<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'nume'                     => 'required',
        'prenume'                  => 'required',
        'idx_optiune_gradhandicap' => 'required|numeric',
        'nevoispecifice'           => 'nullable',
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
});
