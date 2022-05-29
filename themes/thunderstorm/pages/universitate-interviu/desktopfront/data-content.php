<?php

if (!$this->AUTH->IsAuthenticated()) {
    $this->ROUTE->Redirect(qurl_l(''));
}

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

try {
    $arrInterviuri = $this->DATABASE->RunQuery(sprintf(
        "SELECT interviuri.*, " .
        "       angajati.nume, " .
        "       angajati.prenume, " .
        "       locuriuniversitate.facultate " .
        "FROM `%s` interviuri " .
        "INNER JOIN `%s` angajati " .
        "ON (interviuri.idxauthangajat = angajati.idxauth) " .
        "LEFT JOIN `%s` locuriuniversitate " .
        "ON (interviuri.idxobject = locuriuniversitate.idx) " .
        "WHERE interviuri.idxauthuniversitate = %d " .
        "AND interviuri.tstamp >= CURDATE() " .
        "ORDER BY interviuri.tstamp",
        SYSCFG_DB_PREFIX . 'interviuri',
        SYSCFG_DB_PREFIX . 'angajati',
        SYSCFG_DB_PREFIX . 'locuriuniversitate',
        $this->AUTH->GetUserId()
    ));
    if ($arrInterviuri === false) {
        throw new Exception("Eroare internă", 500);
    }

    $this->DATA['locuri'] = [];
    foreach ($arrInterviuri as $arrInterviu) {
        $this->DATA['locuri'][] = [
            'idx'                => $arrInterviu['idx'],
            'nume'               => $arrInterviu['nume'] . ' ' . $arrInterviu['prenume'],
            'dataora'            => MySQLDate_to_RomanianDate($arrInterviu['tstamp']),
            'idxauthnevazator'   => (int)$arrInterviu['idxauthangajat'],
            'tstampsecunde'      => MySQLDate_to_Seconds($arrInterviu['tstamp']),
            'existaloc'          => $arrInterviu['idxobject'] ? 1 : 0,
            'facultate'          => $arrInterviu['facultate'],
            'idxlocuniversitate' => (int)$arrInterviu['idxobject'],
            'vonagesessid'       => $arrInterviu['vonagesessid'],
            'token'              => $arrInterviu['vonageinterlocutortoken']
        ];
    }
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}
