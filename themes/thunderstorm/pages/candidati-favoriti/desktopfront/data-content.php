<?php

call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'jquery-ui-1-10-3-custom-min.js',
        'bootbox.min.js',
    )
);

call_user_func($this->fncCallback, 'htmlheader', 'structure-styles', MANOP_SET,
    array(
        'jquery-ui-1-10-3-custom.css',
        '../vendor/bootstrap-icons/bootstrap-icons.css',
    )
);

if (!$this->AUTH->IsAuthenticated()) {
    $this->ROUTE->Redirect(qurl_l(''));
}

try {
    $arrAngajator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori', [
        'idxauth', '=', $this->AUTH->GetUserId()
    ]);
    if ($arrAngajator === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrAngajator)) {
        throw new Exception("EROARE: acest angajator nu există !", 400);
    }

    $arrAngajator = $arrAngajator[0];

    $arrAngajatorOrase = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori_orase', [
        'idx_angajator', '=', $arrAngajator['idx']
    ]);
    if ($arrAngajatorOrase === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrAngajatorOrase)) {
        throw new Exception("EROARE: nu exista orase pentru acest angajator!", 400);
    }

    $arrAngajatorDomenii = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori_domenii_cv', [
        'idx_angajator', '=', $arrAngajator['idx']
    ]);
    if ($arrAngajatorDomenii === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrAngajatorDomenii)) {
        throw new Exception("EROARE: nu exista orase pentru acest angajator!", 400);
    }

    $idxOrase = [];
    foreach ($arrAngajatorOrase as $arrAngajatorOras) {
        $idxOrase[] = $arrAngajatorOras['idx_oras'];
    }

    $idxDomenii = [];
    foreach ($arrAngajatorDomenii as $arrAngajatorDomeniu) {
        $idxDomenii[] = $arrAngajatorDomeniu['idx_domeniu_cv'];
    }

    $arrRezultate = $this->DATABASE->RunQuery(sprintf(
        "SELECT angajati.*, " .
        "       optiuni.nume AS gradhandicap, " .
        "       IF(angajatori_angajati_favoriti.idxauthangajat IS NOT NULL, 1, 0) AS favorit, " .
        "       GROUP_CONCAT(DISTINCT orase.nume) AS orase, " .
        "       GROUP_CONCAT(DISTINCT domenii_cv.nume) AS domenii_cv, " .
        "       interviuri.tstamp AS interviu_tstamp " .
        "FROM `%s` angajati " .
        "INNER JOIN `%s` angajatori_angajati_favoriti " .
        "ON (angajati.idxauth = angajatori_angajati_favoriti.idxauthangajat AND angajatori_angajati_favoriti.idxauthangajator = %d) " .
        "INNER JOIN `%s` angajati_orase " .
        "ON (angajati.idx = angajati_orase.idx_angajat AND angajati_orase.idx_oras IN (%s)) " .
        "INNER JOIN `%s` orase " .
        "ON (angajati_orase.idx_oras = orase.idx) " .
        "INNER JOIN `%s` angajati_domenii_cv " .
        "ON (angajati.idx = angajati_domenii_cv.idx_angajat AND angajati_domenii_cv.idx_domeniu_cv IN (%s)) " .
        "INNER JOIN `%s` domenii_cv " .
        "ON (angajati_domenii_cv.idx_domeniu_cv = domenii_cv.idx) " .
        "LEFT JOIN `%s` optiuni " .
        "ON (angajati.idx_optiune_gradhandicap = optiuni.idx) " .
        "LEFT JOIN `%s` interviuri " .
        "ON (angajati.idxauth = interviuri.idxauthangajat AND interviuri.idxauthangajator = %d AND interviuri.idxobject = 0) " .
        "GROUP BY angajati.idx, interviuri.idx ",
        SYSCFG_DB_PREFIX . 'angajati',
        SYSCFG_DB_PREFIX . 'angajatori_angajati_favoriti',
        $arrAngajator['idxauth'],
        SYSCFG_DB_PREFIX . 'angajati_orase',
        implode(',', $idxOrase),
        SYSCFG_DB_PREFIX . 'orase',
        SYSCFG_DB_PREFIX . 'angajati_domenii_cv',
        implode(',', $idxDomenii),
        SYSCFG_DB_PREFIX . 'domenii_cv',
        SYSCFG_DB_PREFIX . 'optiuni',
        SYSCFG_DB_PREFIX . 'interviuri',
        $arrAngajator['idxauth']
    ));
    if ($arrRezultate === false) {
        throw new Exception($this->DATABASE->GetError(), 500);
    }

    $this->DATA['nrlocuri'] = count($arrRezultate);
    $this->DATA['locuri'] = [];
    foreach ($arrRezultate as $arrRezultat) {
        $this->DATA['locuri'][] = [
            'nume'            => $arrRezultat['nume'] . ' ' . $arrRezultat['prenume'],
            'cv_fisier_video' => $arrRezultat['cv_fisier_video'],
            'gradhandicap'    => $arrRezultat['gradhandicap'],
            'nevoispecifice'  => $arrRezultat['nevoispecifice'],
            'idxauth'         => (int)$arrRezultat['idxauth'],
            'favorit'         => (int)$arrRezultat['favorit'],
            'orase'           => $arrRezultat['orase'],
            'domenii_cv'      => $arrRezultat['domenii_cv'],
            'interviu_tstamp' => $arrRezultat['interviu_tstamp'],
        ];
    }
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}