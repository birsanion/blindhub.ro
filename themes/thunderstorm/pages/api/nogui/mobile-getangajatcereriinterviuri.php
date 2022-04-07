<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'debug' => '',
    'locuri' => array()
);

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey' => 'required',
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

    $arrLocuri = $this->DATABASE->RunQuery(sprintf(
        "SELECT angajatori.*, " .
        "       locurimunca.idx AS locmunca_idx, " .
        "       locurimunca.idxauth AS locmunca_idxauth, " .
        "       locurimunca.idx_oras AS locmunca_idx_oras, " .
        "       locurimunca.idx_domeniu_cv AS locmunca_idx_domeniu_cv, " .
        "       locurimunca.competente AS locmunca_competente, " .
        "       locurimunca.titlu AS locmunca_titlu, " .
        "       locurimunca.descriere AS locmunca_descriere, " .
        "       locurimunca.idx_optiune_tipslujba AS locmunca_idx_optiune_tipslujba, " .
        "       locurimunca.datapostare AS locmunca_datapostare " .
        "FROM `%s` cereriinterviu " .
        "INNER JOIN `%s` angajatori " .
        "ON (cereriinterviu.idxauthangajator = angajatori.idxauth) " .
        "INNER JOIN `%s` locurimunca " .
        "ON (cereriinterviu.idxlocmunca = locurimunca.idx) " .
        "WHERE cereriinterviu.idxauthangajat = %d ",
        SYSCFG_DB_PREFIX . 'cereriinterviu',
        SYSCFG_DB_PREFIX . 'angajatori',
        SYSCFG_DB_PREFIX . 'locurimunca',
        $arrUser['idx']
    ));
    if ($arrLocuri === false) {
        throw new Exception("Eroare interna", 500);
    }

    $this->DATA['nrlocuri'] = count($arrLocuri);
    $this->DATA['locuri'] = [];
    foreach ($arrLocuri as $arrLoc) {
        $res = [
            'angajator' => [
                'idxauth'                     => (int)$arrLoc['idxauth'],
                'companie'                    => $arrLoc['companie'],
                'idx_optiune_dimensiunefirma' => (int)$arrLoc['idx_optiune_dimensiunefirma'],
            ],
            'locmunca'  => [
                'idx'                   => (int)$arrLoc['locmunca_idx'],
                'idxauth'               => (int)$arrLoc['locmunca_idxauth'],
                'idx_oras'              => (int)$arrLoc['locmunca_idx_oras'],
                'idx_domeniu_cv'        => (int)$arrLoc['locmunca_idx_domeniu_cv'],
                'competente'            => $arrLoc['locmunca_competente'],
                'titlu'                 => $arrLoc['locmunca_titlu'],
                'descriere'             => $arrLoc['locmunca_descriere'],
                'idx_optiune_tipslujba' => (int)$arrLoc['locmunca_idx_optiune_tipslujba'],
                'datapostare'           => $arrLoc['locmunca_datapostare']
            ],
        ];
        $this->DATA['locuri'][] = $res;
    }
});
