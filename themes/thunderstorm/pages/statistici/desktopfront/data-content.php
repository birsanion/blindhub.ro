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

if (!$this->AUTH->GetAdvancedDetail('statistics_permission')) {
    $this->ROUTE->Redirect(qurl_l(''));
}

try {
    $arrRezultate = $this->DATABASE->RunQuery(
        "SELECT " .
        "   au.username, " .
        "   COALESCE(u.nume, a.companie, CONCAT(aj.nume, ' ', aj.prenume)) as `nume`, " .
        "   IF(au.tiputilizator = 1, 'companie', IF(au.tiputilizator = 2, 'universitate', 'angajat')) AS `tiputilizator` " .
        "FROM qwf_auth_users au " .
        "LEFT JOIN qwf_angajatori a " .
        "ON (au.idx = a.idxauth) " .
        "LEFT JOIN qwf_universitati u " .
        "ON (au.idx = u.idxauth) " .
        "LEFT JOIN qwf_angajati aj " .
        "ON (au.idx = aj.idxauth) " .
        "WHERE au.idx > 1 " .
        "ORDER BY au.idx DESC"
    );
    if ($arrRezultate === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $this->DATA['users'] = $arrRezultate;
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}