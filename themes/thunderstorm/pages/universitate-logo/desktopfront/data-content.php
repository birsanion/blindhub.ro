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
        throw new Exception("EROARE INTERNA");
    }
    if (empty($arrAngajator)) {
        throw new Exception("EROARE: acest angajator nu există !");
    }

    $arrAngajator = $arrAngajator[0];
    $arrCereri = $this->DATABASE->RunQuery(sprintf(
        "SELECT cereriinterviu.idxauthangajat, " .
        "       locurimunca.titlu, " .
        "       locurimunca.idx, " .
        "       angajati.idx_optiune_gradhandicap, " .
        "       angajati.nevoispecifice, " .
        "       angajati.nume, " .
        "       angajati.prenume, " .
        "       angajati.cv_fisier_video, " .
        "       IF(angajatori_angajati_favoriti.idxauthangajat IS NOT NULL, 1, 0) AS favorit, " .
        "       GROUP_CONCAT(DISTINCT orase.nume) orase, " .
        "       GROUP_CONCAT(DISTINCT domenii_cv.nume) domenii_cv, " .
        "       optiuni.nume AS gradhandicap, " .
        "       interviuri.tstamp AS interviu_tstamp " .
        "FROM `%s` cereriinterviu " .
        "LEFT JOIN `%s` locurimunca " .
        "ON (cereriinterviu.idxlocmunca = locurimunca.idx) " .
        "LEFT JOIN `%s` angajati " .
        "ON (cereriinterviu.idxauthangajat = angajati.idxauth) " .
        "LEFT JOIN `%s` angajatori_angajati_favoriti " .
        "ON (locurimunca.idxauth = angajatori_angajati_favoriti.idxauthangajator AND " .
        "   angajati.idxauth = angajatori_angajati_favoriti.idxauthangajat " .
        ") " .
        "LEFT JOIN `%s` angajati_orase " .
        "ON (angajati.idx = angajati_orase.idx_angajat) " .
        "LEFT JOIN `%s` orase " .
        "ON (angajati_orase.idx_oras = orase.idx) " .
        "LEFT JOIN `%s` angajati_domenii " .
        "ON (angajati.idx = angajati_domenii.idx_angajat) ".
        "LEFT JOIN `%s` domenii_cv " .
        "ON (angajati_domenii.idx_domeniu_cv = domenii_cv.idx) " .
        "INNER JOIN `%s` optiuni " .
        "ON (angajati.idx_optiune_gradhandicap = optiuni.idx) " .
        "LEFT JOIN `%s` interviuri " .
        "ON (cereriinterviu.idxauthangajat = interviuri.idxauthangajat AND cereriinterviu.idxauthangajator = interviuri.idxauthangajator AND cereriinterviu.idxlocmunca = interviuri.idxobject) " .
        "WHERE cereriinterviu.idxauthangajator = %d " .
        "GROUP BY cereriinterviu.idx, interviuri.idx ",
        SYSCFG_DB_PREFIX . 'cereriinterviu',
        SYSCFG_DB_PREFIX . 'locurimunca',
        SYSCFG_DB_PREFIX . 'angajati',
        SYSCFG_DB_PREFIX . 'angajatori_angajati_favoriti',
        SYSCFG_DB_PREFIX . 'angajati_orase',
        SYSCFG_DB_PREFIX . 'orase',
        SYSCFG_DB_PREFIX . 'angajati_domenii_cv',
        SYSCFG_DB_PREFIX . 'domenii_cv',
        SYSCFG_DB_PREFIX . 'optiuni',
        SYSCFG_DB_PREFIX . 'interviuri',
        $this->AUTH->GetUserId()
    ));
    if ($arrCereri === false) {
        throw new Exception("Eroare interna");
    }

    $this->DATA['locuri'] = [];
    foreach ($arrCereri as $arrCerere) {
        $this->DATA['locuri'][] = [
            'nume'             => $arrCerere['nume'] . ' ' . $arrCerere['prenume'],
            'cv_fisier_video'  => $arrCerere['cv_fisier_video'],
            'gradhandicap'     => $arrCerere['gradhandicap'],
            'nevoispecifice'   => $arrCerere['nevoispecifice'],
            'idxauthnevazator' => (int)$arrCerere['idxauthangajat'],
            'favorit'          => (int)$arrCerere['favorit'],
            'orase'            => $arrCerere['orase'],
            'domenii_cv'       => $arrCerere['domenii_cv'],
            'interviu_tstamp'  => $arrCerere['interviu_tstamp'],
            'locmunca' => [
                'idx'   => (int)$arrCerere['idx'],
                'titlu' => (string)$arrCerere['titlu'],
            ]
        ];
    }
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}
