<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey' => 'nullable',
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
    $res = $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'auth_users', ['idx', '=', $arrUser['idx']]);
    if (!$res) {
        throw new Exception("Eroare internă", 500);
    }
});
