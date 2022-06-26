<?php

function GetTimeDifferenceFromNow($strPastDate)
{
    // 0123-56-89
    $nPast = mktime(0, 0, 0, (int)substr($strPastDate, 5, 2),
        (int)substr($strPastDate, 8, 2), (int)substr($strPastDate, 0, 4));

    return floor((time() - $nPast) / 86400);
}

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey' => 'required'
    ]);

    $validation->validate();
    if ($validation->fails()) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users', [
        'apploginid', '=', $validation->getValue('userkey')
    ]);
    if ($arrUser === false) {
        throw new Exception("Eroare internă", 500);
    }
    if (empty($arrUser)) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrUser = $arrUser[0];
    if ($arrUser['tiputilizator'] != 0) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrAngajat = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati', [
        'idxauth', '=', $arrUser['idx'],
    ]);
    if ($arrAngajat === false) {
        throw new Exception("Eroare internă", 500);
    }
    if (empty($arrAngajat)) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrAngajat = $arrAngajat[0];
    $arrRezultate = $this->DATABASE->RunQuery(sprintf(
        "SELECT locurimunca.*, angajatori.companie  " .
        "FROM `%s` locurimunca " .
        "INNER JOIN `%s` angajatori " .
        "ON (locurimunca.idxauth = angajatori.idxauth) " .
        "INNER JOIN `%s` angajati_orase " .
        "ON (locurimunca.idx_oras = angajati_orase.idx_oras AND angajati_orase.idx_angajat = %d) " .
        "INNER JOIN `%s` angajati_domenii " .
        "ON (locurimunca.idx_domeniu_cv = angajati_domenii.idx_domeniu_cv AND angajati_domenii.idx_angajat = %d) " .
        "GROUP BY locurimunca.idx",
        SYSCFG_DB_PREFIX . 'locurimunca',
        SYSCFG_DB_PREFIX . 'angajatori',
        SYSCFG_DB_PREFIX . 'angajati_orase',
        (int)$arrAngajat['idx'],
        SYSCFG_DB_PREFIX . 'angajati_domenii_cv',
        (int)$arrAngajat['idx']
    ));
    if ($arrRezultate === false) {
        throw new Exception("Eroare internă", 500);
    }

    $this->DATA['nrlocuri'] = count($arrRezultate);
    $this->DATA['locuri'] = [];
    foreach ($arrRezultate as $arrRezultat) {
        $nTimeDiff = GetTimeDifferenceFromNow($arrRezultat['datapostare']);
        $this->DATA['locuri'][] = [
            'nume'                  => $arrRezultat['companie'],
            'vechimeanunt'          =>
                $nTimeDiff <= 0 ? $this->LANG('anunț postat astăzi') :
                    $nTimeDiff <= 1 ? $this->LANG('anunț postat acum o zi') :
                        $nTimeDiff <= 19 ? sprintf($this->LANG('anunț postat acum %s zile'), $nTimeDiff) :
                            sprintf($this->LANG('anunț postat acum %s de zile'), $nTimeDiff),
            'idxlocmunca'           => (int)$arrRezultat['idx'],
            'idxauth'               => (int)$arrRezultat['idxauth'],
            'idx_oras'              => (int)$arrRezultat['idx_oras'],
            'idx_domeniu_cv'        => (int)$arrRezultat['idx_domeniu_cv'],
            'competente'            => $arrRezultat['competente'],
            'titlu'                 => $arrRezultat['titlu'],
            'descriere'             => $arrRezultat['descriere'],
            'idx_optiune_tipslujba' => (int)$arrRezultat['idx_optiune_tipslujba']
        ];
    }
});
