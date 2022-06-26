<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'  => 'required',
        'idxnevaz' => 'required|numeric'
    ]);

    $validation->validate();
    if ($validation->fails()) {
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
    if ($arrUser['tiputilizator'] == 0) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrAngajat = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati', [
        'idxauth', '=', POST('idxnevaz')
    ]);
    if ($arrAngajat === false) {
        throw new Exception("Eroare internă", 500);
    }
    if (empty($arrAngajat)) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrAngajat = $arrAngajat[0];
    $this->DATA['file'] = '';
    if ($arrAngajat['cv_fisier_video']) {
         $this->DATA['file'] = qurl_file('media/uploads/' . $arrAngajat['cv_fisier_video']);
    }
});

