<?php
////////////////////////////////////////////////////////////////////////////////
// Part of theme Thunderstorm, of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.

call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'jquery-ui-1-10-3-custom-min.js',
        'jq-file-upload/jquery.iframe-transport.js',
        'jq-file-upload/jquery.fileupload.js'
    )
);

call_user_func($this->fncCallback, 'htmlheader', 'structure-styles', MANOP_SET,
    array(
        'jquery-ui-1-10-3-custom.css',
        'jquery-fileupload-ui.css'
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
/*
$arrDetails = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati', [
    'idxauth', '=', $this->AUTH->GetUserId(),
]);
if ($arrDetails === false) {
    $this->GLOBAL['errormsg'] = 'Eroare interna';
    return;
}

$this->DATA['details'] = $arrDetails[0];

$arrOptiuni = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'optiuni', NULL, ['categorie', 'nume']);
if ($arrOptiuni === false) {
    $this->GLOBAL['errormsg'] = 'Eroare interna';
    return;
}

$this->DATA['optiuni'] = [];
foreach ($arrOptiuni as $arrOptiune) {
    $this->DATA['optiuni'][$arrOptiune['categorie']][$arrOptiune['idx']] = $arrOptiune['nume'];
}
*/