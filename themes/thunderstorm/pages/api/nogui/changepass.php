<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'parolaveche' => 'required',
        'parolanoua'  => 'required',
        'userkey'     => 'nullable',
    ]);

    $validation->validate();
    if ($validation->fails()) {
        // $errors = $validation->errors();
        // $error = array_values($errors->firstOfAll())[0];
        throw new Exception("Cerere invalida", 400);
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
    $passHash = $this->AUTH->GetPasswordHash($validation->getValue('parolaveche'), $arrUser['username'], $arrUser['salt']);
    if (!$this->AUTH->CompareHashes($arrUser['passhash'], $passHash)) {
        throw new Exception("EROARE: Parola veche este incorectă !", 400);
    }

    if ($this->AUTH->ResetPassword((int)$arrUser['idx'], $validation->getValue('parolanoua')) != AUTH_SUCCESS) {
        throw new Exception("Eroare internă", 500);
    }

    $this->DATA['result'] = 'success';
});
