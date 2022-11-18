<?php

call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'datatables.min.js',
    )
);

call_user_func($this->fncCallback, 'htmlheader', 'structure-styles', MANOP_SET,
    array(
        'datatables.min.css',
    )
);

if (!$this->AUTH->IsAuthenticated()) {
    $this->ROUTE->Redirect(qurl_l(''));
}

if ($this->AUTH->GetAdvancedDetail('tiputilizator') != -1) {
    $this->ROUTE->Redirect(qurl_l(''));
}

try {
    $arrRezultate = $this->DATABASE->RunQuery(
        "SELECT " .
        "   au.username, " .
        "   COALESCE(u.nume, a.companie, CONCAT(aj.nume, ' ', aj.prenume)) as `nume`, " .
        "   IF(au.tiputilizator = 1, 'companie', IF(au.tiputilizator = 2, 'universitate', 'angajat')) AS `tiputilizator`, " .
        "   COALESCE(GROUP_CONCAT(orase_angajatori.nume, ', '), GROUP_CONCAT(orase_universitati.nume , ', '), GROUP_CONCAT(orase_angajati.nume, ', ')) AS orase " .
        "FROM qwf_auth_users au " .
        "LEFT JOIN qwf_angajatori a " .
        "ON (au.idx = a.idxauth) " .
        "LEFT JOIN qwf_angajatori_orase angajatori_orase " .
        "ON (a.idx = angajatori_orase.idx_angajator) " .
        "LEFT JOIN qwf_orase orase_angajatori " .
        "ON (angajatori_orase.idx_oras = orase_angajatori.idx) " .
        "LEFT JOIN qwf_universitati u " .
        "ON (au.idx = u.idxauth) " .
        "LEFT JOIN qwf_universitati_orase universitati_orase " .
        "ON (u.idx = universitati_orase.idx_universitate) " .
        "LEFT JOIN qwf_orase orase_universitati " .
        "ON (universitati_orase.idx_oras = orase_universitati.idx) " .
        "LEFT JOIN qwf_angajati aj " .
        "ON (au.idx = aj.idxauth) " .
        "LEFT JOIN qwf_angajati_orase angajati_orase " .
        "ON (aj.idx = angajati_orase.idx_angajat) " .
        "LEFT JOIN qwf_orase orase_angajati " .
        "ON (angajati_orase.idx_oras = orase_angajati.idx) " .
        "WHERE au.idx > 1 " .
        "AND au.tiputilizator >= 0 " .
        "GROUP BY au.idx " .
        "ORDER BY au.idx DESC"
    );

    if ($arrRezultate === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $this->DATA['users'] = $arrRezultate;
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}