<?php

call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'bootbox.min.js'
    )
);

if (!$this->AUTH->IsAuthenticated()) {
    $this->ROUTE->Redirect(qurl_l(''));
}

try {
    $arrDetails = $this->DATABASE->RunQuery(sprintf(
        "SELECT angajatori.*, " .
        "       GROUP_CONCAT(DISTINCT orase.nume) AS orase, " .
        "       GROUP_CONCAT(DISTINCT domenii_cv.nume) AS domenii_cv " .
        "FROM %s angajatori " .
        "INNER JOIN %s angajatori_orase " .
        "ON (angajatori_orase.idx_angajator = angajatori.idx)" .
        "INNER JOIN %s orase " .
        "ON (angajatori_orase.idx_oras = orase.idx) " .
        "INNER JOIN %s angajatori_domenii_cv " .
        "ON (angajatori_domenii_cv.idx_angajator = angajatori.idx)" .
        "INNER JOIN %s domenii_cv " .
        "ON (angajatori_domenii_cv.idx_domeniu_cv = domenii_cv.idx) " .
        "WHERE angajatori.idxauth = %d " .
        "GROUP BY angajatori.idx ",
        SYSCFG_DB_PREFIX . 'angajatori',
        SYSCFG_DB_PREFIX . 'angajatori_orase',
        SYSCFG_DB_PREFIX . 'orase',
        SYSCFG_DB_PREFIX . 'angajatori_domenii_cv',
        SYSCFG_DB_PREFIX . 'domenii_cv',
        $this->AUTH->GetUserId()
    ));
    if ($arrDetails === false) {
        throw new Exception("Eroare internă");
    }

    $this->DATA['details'] = $arrDetails[0];
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}
