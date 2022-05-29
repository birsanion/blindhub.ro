<?php

call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'bootbox.min.js',
    )
);

function GetTimeDifferenceFromNow($strPastDate)
{
    // 0123-56-89
    $nPast = mktime(0, 0, 0, (int)substr($strPastDate, 5, 2),
        (int)substr($strPastDate, 8, 2), (int)substr($strPastDate, 0, 4));

    return floor((time() - $nPast) / 86400);
}


try {
    $arrRezultate = $this->DATABASE->RunQuery(sprintf(
        "SELECT locurimunca.*, " .
        "       angajatori.companie, " .
        "       cereriinterviu.idx AS idxcerereinterviu, " .
        "       optiuni.nume AS tipslujba, " .
        "       orase.nume AS oras, " .
        "       domenii_cv.nume AS domeniu_cv " .
        "FROM `%s` angajati_locurisalvate " .
        "INNER JOIN `%s` locurimunca " .
        "ON (angajati_locurisalvate.idxlocmunca = locurimunca.idx) " .
        "LEFT JOIN `%s` cereriinterviu " .
        "ON (angajati_locurisalvate.idxauthangajat = cereriinterviu.idxauthangajat AND angajati_locurisalvate.idxauthangajator = cereriinterviu.idxauthangajator AND angajati_locurisalvate.idxlocmunca = cereriinterviu.idxlocmunca) " .
        "INNER JOIN `%s` angajatori " .
        "ON (locurimunca.idxauth = angajatori.idxauth) " .
        "INNER JOIN %s optiuni " .
        "ON (locurimunca.idx_optiune_tipslujba = optiuni.idx) " .
        "INNER JOIN %s orase " .
        "ON (locurimunca.idx_oras = orase.idx) " .
        "INNER JOIN %s domenii_cv " .
        "ON (locurimunca.idx_domeniu_cv = domenii_cv.idx) " .
        "WHERE angajati_locurisalvate.idxauthangajat = %d",
        SYSCFG_DB_PREFIX . 'angajati_locurisalvate',
        SYSCFG_DB_PREFIX . 'locurimunca',
        SYSCFG_DB_PREFIX . 'cereriinterviu',
        SYSCFG_DB_PREFIX . 'angajatori',
        SYSCFG_DB_PREFIX . 'optiuni',
        SYSCFG_DB_PREFIX . 'orase',
        SYSCFG_DB_PREFIX . 'domenii_cv',
        $this->AUTH->GetUserId()
    ));

    if ($arrRezultate === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $this->DATA['locuri'] = [];
    foreach ($arrRezultate as $arrRezultat) {
        $nTimeDiff = GetTimeDifferenceFromNow($arrRezultat['datapostare']);
        $this->DATA['locuri'][] = [
            'companie'              => $arrRezultat['companie'],
            'vechimeanunt'          => 'Anunț postat ' .
                ($nTimeDiff <= 0 ? 'astăzi' :
                    ($nTimeDiff <= 1 ? ' acum o zi' :
                        ($nTimeDiff <= 19 ? 'acum ' . $nTimeDiff . ' zile' :
                            'acum ' . $nTimeDiff . ' de zile'))),
            'idxlocmunca'           => (int)$arrRezultat['idx'],
            'idxauth'               => (int)$arrRezultat['idxauth'],
            'idxcerereinterviu'     => $arrRezultat['idxcerereinterviu'],
            'titlu'                 => $arrRezultat['titlu'],
            'descriere'             => $arrRezultat['descriere'],
            'competente'            => $arrRezultat['competente'],
            'domeniu_cv'            => $arrRezultat['domeniu_cv'],
            'oras'                  => $arrRezultat['oras'],
            'tipslujba'             => $arrRezultat['tipslujba']
        ];
    }
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}