<?php

if (!$this->AUTH->IsAuthenticated()) $this->ROUTE->Redirect(qurl_l(''));

$this->handleAPIRequest(function() {
    $arrLocuriMunca = $this->DATABASE->RunQuery(sprintf(
        "SELECT locurimunca.*, " .
        "       orase.nume AS oras, " .
        "       domenii_cv.nume AS domeniu_cv, " .
        "       optiuni.nume AS tipslujba " .
        "FROM `%s` locurimunca " .
        "LEFT JOIN `%s` orase " .
        "ON (locurimunca.idx_oras = orase.idx) " .
        "LEFT JOIN `%s` domenii_cv " .
        "ON (locurimunca.idx_domeniu_cv = domenii_cv.idx) " .
        "LEFT JOIN `%s` optiuni " .
        "ON (locurimunca.idx_optiune_tipslujba = optiuni.idx) " .
        "WHERE locurimunca.idxauth = %d",
        SYSCFG_DB_PREFIX . 'locurimunca',
        SYSCFG_DB_PREFIX . 'orase',
        SYSCFG_DB_PREFIX . 'domenii_cv',
        SYSCFG_DB_PREFIX . 'optiuni',
        $this->AUTH->GetUserId(),
    ));
    if ($arrLocuriMunca === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    foreach ($arrLocuriMunca as $arrLoc) {
        $this->DATA['locuri'][] = [
            'idx'                   => (int)$arrLoc['idx'],
            'oras'                  => $arrLoc['oras'],
            'domeniu_cv'            => $arrLoc['domeniu_cv'],
            'competente'            => $arrLoc['competente'],
            'titlu'                 => $arrLoc['titlu'],
            'descriere'             => $arrLoc['descriere'],
            'tipslujba'             => $arrLoc['tipslujba'],
            'datapostare'           => $arrLoc['datapostare'],
        ];
    }
});