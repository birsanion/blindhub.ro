<?php

function MySQLDate_to_RomanianDate($strDate)
{
    // 0123-56-89 11:14:17
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
    if ($arrUser['tiputilizator'] != 1) {
        throw new Exception("EROARE: acest utilizator nu este de tip angajator !", 400);
    }

    $arrInterviuri = $this->DATABASE->RunQuery(sprintf(
        "SELECT interviuri.*, " .
        "       angajati.nume, " .
        "       angajati.prenume, " .
        "       locurimunca.titlu AS titlulocmunca, " .
        "       locurimunca.idx_oras AS idx_oras_locmunca " .
        "FROM `%s` interviuri " .
        "INNER JOIN `%s` angajati " .
        "ON (interviuri.idxauthangajat = angajati.idxauth) " .
        "LEFT JOIN `%s` locurimunca " .
        "ON (interviuri.idxobject = locurimunca.idx) " .
        "WHERE interviuri.idxauthangajator = %d " .
        "AND interviuri.tstamp >= CURDATE() " .
        "ORDER BY interviuri.tstamp",
        SYSCFG_DB_PREFIX . 'interviuri',
        SYSCFG_DB_PREFIX . 'angajati',
        SYSCFG_DB_PREFIX . 'locurimunca',
        $arrUser['idx']
    ));
    if ($arrInterviuri === false) {
        throw new Exception($this->DATABASE->GetError(), 500);
    }

    $this->DATA['nrlocuri'] = count($arrInterviuri);
    $this->DATA['locuri'] = [];
    foreach ($arrInterviuri as $arrInterviu) {
        $this->DATA['locuri'][] = [
            'nume'              => $arrInterviu['nume'] . ' ' . $arrInterviu['prenume'],
            'dataora'           => MySQLDate_to_RomanianDate($arrInterviu['tstamp']),
            'idxauthnevazator'  => (int)$arrInterviu['idxauthangajat'],
            'tstampsecunde'     => MySQLDate_to_Seconds($arrInterviu['tstamp']),
            'existalocmunca'    => $arrInterviu['idxobject'] ? 1 : 0,
            'titlulocmunca'     => $arrInterviu['titlulocmunca'],
            'idx_oras_locmunca' => $arrInterviu['idx_oras_locmunca'] ? (int)$arrInterviu['idx_oras_locmunca'] : null,
            'vonagesessid'      => $arrInterviu['vonagesessid'],
            'token'             => $arrInterviu['vonageinterlocutortoken']
        ];
    }
});
