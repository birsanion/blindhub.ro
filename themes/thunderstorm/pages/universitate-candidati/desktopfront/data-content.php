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
    )
);

if (!$this->AUTH->IsAuthenticated()) {
    $this->ROUTE->Redirect(qurl_l(''));
};

if ($this->AUTH->GetAdvancedDetail('tiputilizator') != 2) {
    $this->ROUTE->Redirect(qurl_l(''));
}

try {
    $arrCereri = $this->DATABASE->RunQuery(sprintf(
        "SELECT cereriinterviuuniversitate.idxauthangajat, " .
        "       angajati.nume, " .
        "       angajati.prenume, " .
        "       optiuni.nume AS gradhandicap, " .
        "       angajati.nevoispecifice, " .
        "       domenii_universitate.nume AS domeniu_universitate, " .
        "       orase.nume AS oras, " .
        "       interviuri.tstamp AS interviu_tstamp, " .
        "       locuriuniversitate.* " .
        "FROM `%s` cereriinterviuuniversitate " .
        "INNER JOIN `%s` locuriuniversitate " .
        "ON (cereriinterviuuniversitate.idxlocuniversitate = locuriuniversitate.idx) " .
        "LEFT JOIN `%s` interviuri " .
        "ON (cereriinterviuuniversitate.idxauthangajat = interviuri.idxauthangajat AND cereriinterviuuniversitate.idxauthuniversitate = interviuri.idxauthuniversitate AND cereriinterviuuniversitate.idxlocuniversitate = interviuri.idxobject) " .
        "INNER JOIN `%s` domenii_universitate " .
        "ON (locuriuniversitate.idx_domeniu_universitate = domenii_universitate.idx) " .
        "INNER JOIN `%s` orase " .
        "ON (locuriuniversitate.idx_oras = orase.idx) " .
        "INNER JOIN `%s` angajati " .
        "ON (cereriinterviuuniversitate.idxauthangajat = angajati.idxauth) " .
        "INNER JOIN `%s` optiuni " .
        "ON (angajati.idx_optiune_gradhandicap = optiuni.idx) " .
        "WHERE cereriinterviuuniversitate.idxauthuniversitate = %d " .
        "ORDER BY locuriuniversitate.idx DESC",
        SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate',
        SYSCFG_DB_PREFIX . 'locuriuniversitate',
        SYSCFG_DB_PREFIX . 'interviuri',
        SYSCFG_DB_PREFIX . 'domenii_universitate',
        SYSCFG_DB_PREFIX . 'orase',
        SYSCFG_DB_PREFIX . 'angajati',
        SYSCFG_DB_PREFIX . 'optiuni',
        $this->AUTH->GetUserId()
    ));
    if ($arrCereri === false) {
        die($this->DATABASE->GetError());
        throw new Exception("Eroare interna", 500);
    }

    $this->DATA['nrlocuri'] = count($arrCereri);
    $this->DATA['locuri'] = [];
    foreach ($arrCereri as $arrCerere) {
        $this->DATA['locuri'][] = [
            'nume'             => $arrCerere['nume'] . ' ' . $arrCerere['prenume'],
            'gradhandicap'     => $arrCerere['gradhandicap'],
            'nevoispecifice'   => $arrCerere['nevoispecifice'],
            'idxauthnevazator' => (int)$arrCerere['idxauthangajat'],
            'interviu_tstamp' => $arrCerere['interviu_tstamp'],
            'locuniversitate'  => [
                'idx'                  => (int)$arrCerere['idx'],
                'facultate'            => $arrCerere['facultate'],
                'domeniu_universitate' => $arrCerere['domeniu_universitate'],
                'oras'                 => $arrCerere['oras'],
            ]
        ];
    }
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}
