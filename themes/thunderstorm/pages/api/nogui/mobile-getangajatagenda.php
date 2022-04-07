<?php

function MySQLDate_to_RomanianDate($strDate)
{
    // 0123-56-89 12:45:78
    return substr($strDate, 8, 2) . '/' . substr($strDate, 5, 2) . '/' .
        substr($strDate, 0, 4) . substr($strDate, 10, 6);
}

function MySQLDate_to_Seconds($strDate)
{
    // 0123-56-89 11:14:17
    return mktime(
        (int)substr($strDate, 11, 2),
        (int)substr($strDate, 14, 2),
        (int)substr($strDate, 17, 2),
        (int)substr($strDate, 5, 2),
        (int)substr($strDate, 8, 2),
        (int)substr($strDate, 0, 4)
    );
}

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

    $arrInterviuri = $this->DATABASE->RunQuery(sprintf(
        "SELECT interviuri.*, " .
        "       angajatori.companie, " .
        "       locurimunca.idx AS idxlocmunca, " .
        "       locurimunca.titlu AS titlulocmunca, " .
        "       locurimunca.idx_oras AS idx_oras_locmunca, " .
        "       locurimunca.idx_domeniu_cv AS idx_domeniu_locmunca, " .
        "       universitati.nume AS numeuniversitate, " .
        "       locuriuniversitate.idx AS idxlocuniversitte, " .
        "       locuriuniversitate.facultate, " .
        "       locuriuniversitate.idx_oras AS idx_oras_locuniversitate, " .
        "       locuriuniversitate.idx_domeniu_universitate AS idx_domeniu_locuniversitate " .
        "FROM `%s` interviuri " .
        "LEFT JOIN `%s` angajatori " .
        "ON (interviuri.idxauthangajator = angajatori.idxauth) " .
        "LEFT JOIN `%s` locurimunca " .
        "ON (interviuri.idxobject = locurimunca.idx AND interviuri.idxauthangajator = angajatori.idxauth) " .
        "LEFT JOIN `%s` universitati " .
        "ON (interviuri.idxauthuniversitate = universitati.idxauth) " .
        "LEFT JOIN `%s` locuriuniversitate " .
        "ON (interviuri.idxobject = locuriuniversitate.idx AND locuriuniversitate.idxauth = universitati.idxauth) " .
        "WHERE interviuri.idxauthangajat = %d " .
        "AND interviuri.tstamp >= CURDATE() " .
        "ORDER BY interviuri.tstamp",
        SYSCFG_DB_PREFIX . 'interviuri',
        SYSCFG_DB_PREFIX . 'angajatori',
        SYSCFG_DB_PREFIX . 'locurimunca',
        SYSCFG_DB_PREFIX . 'universitati',
        SYSCFG_DB_PREFIX . 'locuriuniversitate',
        $arrUser['idx']
    ));
    if ($arrInterviuri === false) {
        throw new Exception($this->DATABASE->GetError(), 500);
    }

    $this->DATA['nrlocuri'] = count($arrInterviuri);
    $this->DATA['locuri'] = [];
    foreach ($arrInterviuri as $arrInterviu) {
        $this->DATA['locuri'][] = [
            'nume'                       => $arrInterviu['idxauthangajator'] ? $arrInterviu['companie'] : $arrInterviu['numeuniversitate'],
            'dataora'                    => MySQLDate_to_RomanianDate($arrInterviu['tstamp']),
            'idxauthnevazator'           => (int)$arrInterviu['idxauthangajat'],
            'tstampsecunde'              => MySQLDate_to_Seconds($arrInterviu['tstamp']),
            'existalocmunca'             => $arrInterviu['idxlocmunca'] ? 1 : 0,
            'existalocuniversitate'      => $arrInterviu['idxlocuniversitte'] ? 1 : 0,
            'titlulocmunca'              => (string)$arrInterviu['titlulocmunca'],
            'idx_oras_locmunca'          => (int)$arrInterviu['idx_oras_locmunca'],
            'idx_domeniu_locmunca'       => (int)$arrInterviu['idx_domeniu_locmunca'],
            'facultate'                  => (string)$arrInterviu['facultate'],
            'idx_oras_locuniversitate'   => (int)$arrInterviu['idx_oras_locuniversitate'],
            'idx_domeniu_locuniversitate'=> (int)$arrInterviu['idx_domeniu_locuniversitate'],
            'vonagesessid'               => $arrInterviu['vonagesessid'],
            'token'                      => $arrInterviu['vonagenevaztoken']
        ];
    }
});

