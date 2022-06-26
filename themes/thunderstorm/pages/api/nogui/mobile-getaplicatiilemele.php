<?php

////////////////////////////////
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

    $arrRezultate = $this->DATABASE->RunQuery(sprintf(
        "SELECT locurimunca.*, angajatori.companie " .
        "FROM `%s` angajati_locurisalvate " .
        "INNER JOIN `%s` locurimunca " .
        "ON (angajati_locurisalvate.idxlocmunca = locurimunca.idx) " .
        "INNER JOIN `%s` angajatori " .
        "ON (locurimunca.idxauth = angajatori.idxauth) " .
        "WHERE angajati_locurisalvate.idxauthangajat = %d",
        SYSCFG_DB_PREFIX . 'angajati_locurisalvate',
        SYSCFG_DB_PREFIX . 'locurimunca',
        SYSCFG_DB_PREFIX . 'angajatori',
        (int)$arrUser['idx']
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
            'idxlocmunca'           => (int)$arrRezultat['idx'],
            'idxauth'               => (int)$arrRezultat['idxauth'],
            'titlu'                 => $arrRezultat['titlu'],
            'descriere'             => $arrRezultat['descriere'],
            'competente'            => $arrRezultat['competente'],
            'idx_domeniu_cv'        => (int)$arrRezultat['idx_domeniu_cv'],
            'idx_oras'              => (int)$arrRezultat['idx_oras'],
            'idx_optiune_tipslujba' => (int)$arrRezultat['idx_optiune_tipslujba']
        ];
    }
});
