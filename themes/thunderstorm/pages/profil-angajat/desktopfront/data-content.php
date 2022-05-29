<?php

call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'bootbox.min.js'
    )
);

try {
    $arrDetails = $this->DATABASE->RunQuery(sprintf(
        "SELECT angajati.*, " .
        "       GROUP_CONCAT(DISTINCT orase.nume) AS orase, " .
        "       GROUP_CONCAT(DISTINCT domenii_cv.nume) AS domenii_cv, " .
        "       optiuni.nume AS gradhandicap " .
        "FROM %s angajati " .
        "LEFT JOIN %s angajati_orase " .
        "ON (angajati_orase.idx_angajat = angajati.idx)" .
        "LEFT JOIN %s orase " .
        "ON (angajati_orase.idx_oras = orase.idx) " .
        "LEFT JOIN %s angajati_domenii_cv " .
        "ON (angajati_domenii_cv.idx_angajat = angajati.idx) " .
        "LEFT JOIN %s domenii_cv " .
        "ON (angajati_domenii_cv.idx_domeniu_cv = domenii_cv.idx) " .
        "LEFT JOIN %s optiuni " .
        "ON (angajati.idx_optiune_gradhandicap = optiuni.idx) " .
        "WHERE angajati.idxauth = %d " .
        "GROUP BY angajati.idx ",
        SYSCFG_DB_PREFIX . 'angajati',
        SYSCFG_DB_PREFIX . 'angajati_orase',
        SYSCFG_DB_PREFIX . 'orase',
        SYSCFG_DB_PREFIX . 'angajati_domenii_cv',
        SYSCFG_DB_PREFIX . 'domenii_cv',
        SYSCFG_DB_PREFIX . 'optiuni',
        $this->AUTH->GetUserId()
    ));

    if ($arrDetails === false) {
        throw new Exception("Eroare internă");
    }

    $this->DATA['details'] = $arrDetails[0];
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}
