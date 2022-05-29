<?php

call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'bootbox.min.js',

    )
);

call_user_func($this->fncCallback, 'htmlheader', 'structure-styles', MANOP_SET,
    array(
        'jquery-ui-1-10-3-custom.css',
        'jquery-fileupload-ui.css'
    )
);

if (!$this->AUTH->IsAuthenticated()) {
    $this->ROUTE->Redirect(qurl_l(''));
}

function GetTimeDifferenceFromNow($strPastDate)
{
    // 0123-56-89
    $nPast = mktime(0, 0, 0, (int)substr($strPastDate, 5, 2),
        (int)substr($strPastDate, 8, 2), (int)substr($strPastDate, 0, 4));

    return floor((time() - $nPast) / 86400);
}

try {
    $arrAngajat = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati', [
        'idxauth', '=', $this->AUTH->GetUserId(),
    ]);
    if ($arrAngajat === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrAngajat)) {
        throw new Exception("EROARE: acest angajat nu există !", 400);
    }

    $arrAngajat = $arrAngajat[0];
    $arrRezultate = $this->DATABASE->RunQuery(sprintf(
        "SELECT locurimunca.*, " .
        "       optiuni.nume AS tipslujba, " .
        "       angajatori.companie, " .
        "       orase.nume AS oras, " .
        "       cereriinterviu.idx AS idxcerereinterviu, " .
        "       angajati_locurisalvate.idx AS idxlocsalvat, " .
        "       domenii_cv.nume AS domeniu_cv " .
        "FROM `%s` locurimunca " .
        "INNER JOIN `%s` angajatori " .
        "ON (locurimunca.idxauth = angajatori.idxauth) " .
        "LEFT JOIN `%s` cereriinterviu " .
        "ON (cereriinterviu.idxauthangajat = %d AND cereriinterviu.idxauthangajator = angajatori.idxauth AND cereriinterviu.idxlocmunca = locurimunca.idx) " .
        "LEFT JOIN `%s` angajati_locurisalvate " .
        "ON (angajati_locurisalvate.idxauthangajat = %d AND angajati_locurisalvate.idxauthangajator = angajatori.idxauth AND angajati_locurisalvate.idxlocmunca = locurimunca.idx) " .
        "INNER JOIN `%s` angajati_orase " .
        "ON (locurimunca.idx_oras = angajati_orase.idx_oras AND angajati_orase.idx_angajat = %d) " .
        "INNER JOIN `%s` orase " .
        "ON (orase.idx = angajati_orase.idx_oras) " .
        "INNER JOIN `%s` angajati_domenii " .
        "ON (locurimunca.idx_domeniu_cv = angajati_domenii.idx_domeniu_cv AND angajati_domenii.idx_angajat = %d) " .
        "INNER JOIN `%s` domenii_cv " .
        "ON (domenii_cv.idx = angajati_domenii.idx_domeniu_cv) " .
        "INNER JOIN `%s` optiuni " .
        "ON (locurimunca.idx_optiune_tipslujba = optiuni.idx) " .
        "GROUP BY locurimunca.idx, cereriinterviu.idx, angajati_locurisalvate.idx " .
        "ORDER BY locurimunca.idx DESC ",
        SYSCFG_DB_PREFIX . 'locurimunca',
        SYSCFG_DB_PREFIX . 'angajatori',
        SYSCFG_DB_PREFIX . 'cereriinterviu',
        (int)$arrAngajat['idxauth'],
        SYSCFG_DB_PREFIX . 'angajati_locurisalvate',
        (int)$arrAngajat['idxauth'],
        SYSCFG_DB_PREFIX . 'angajati_orase',
        (int)$arrAngajat['idx'],
        SYSCFG_DB_PREFIX . 'orase',
        SYSCFG_DB_PREFIX . 'angajati_domenii_cv',
        (int)$arrAngajat['idx'],
        SYSCFG_DB_PREFIX . 'domenii_cv',
        SYSCFG_DB_PREFIX . 'optiuni'
    ));
    if ($arrRezultate === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $this->DATA['locuri'] = [];
    foreach ($arrRezultate as $arrRezultat) {
        $nTimeDiff = GetTimeDifferenceFromNow($arrRezultat['datapostare']);
        $this->DATA['locuri'][] = [
            'nume'                  => $arrRezultat['companie'],
            'vechimeanunt'          => 'Anunț postat ' .
                ($nTimeDiff <= 0 ? 'astăzi' :
                    ($nTimeDiff <= 1 ? ' acum o zi' :
                        ($nTimeDiff <= 19 ? 'acum ' . $nTimeDiff . ' zile' :
                            'acum ' . $nTimeDiff . ' de zile'))),
            'idxlocmunca'           => (int)$arrRezultat['idx'],
            'idxauth'               => (int)$arrRezultat['idxauth'],
            'idxcerereinterviu'     => $arrRezultat['idxcerereinterviu'],
            'oras'                  => $arrRezultat['oras'],
            'domeniu_cv'            => $arrRezultat['domeniu_cv'],
            'competente'            => $arrRezultat['competente'],
            'titlu'                 => $arrRezultat['titlu'],
            'descriere'             => $arrRezultat['descriere'],
            'tipslujba'             => $arrRezultat['tipslujba'],
            'locsalvat'             => $arrRezultat['idxlocsalvat'] ? 1 : 0,
        ];
    }
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}
