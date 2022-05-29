<?php

try {
    $arrLocuri = $this->DATABASE->RunQuery(sprintf(
        "SELECT angajatori.*, " .
        "       locurimunca.idx AS locmunca_idx, " .
        "       locurimunca.idxauth AS locmunca_idxauth, " .
        "       locurimunca.competente AS locmunca_competente, " .
        "       locurimunca.titlu AS locmunca_titlu, " .
        "       locurimunca.descriere AS locmunca_descriere, " .
        "       locurimunca.idx_optiune_tipslujba AS locmunca_idx_optiune_tipslujba, " .
        "       optiuni.nume AS tipslujba, " .
        "       orase.nume AS oras, " .
        "       domenii_cv.nume AS domeniu_cv " .
        "FROM `%s` cereriinterviu " .
        "INNER JOIN `%s` angajatori " .
        "ON (cereriinterviu.idxauthangajator = angajatori.idxauth) " .
        "INNER JOIN `%s` locurimunca " .
        "ON (cereriinterviu.idxlocmunca = locurimunca.idx) " .
        "INNER JOIN `%s` optiuni " .
        "ON (locurimunca.idx_optiune_tipslujba = optiuni.idx) " .
        "INNER JOIN `%s` orase " .
        "ON (locurimunca.idx_oras = orase.idx) " .
        "INNER JOIN `%s` domenii_cv " .
        "ON (locurimunca.idx_domeniu_cv = domenii_cv.idx) " .
        "WHERE cereriinterviu.idxauthangajat = %d ",
        SYSCFG_DB_PREFIX . 'cereriinterviu',
        SYSCFG_DB_PREFIX . 'angajatori',
        SYSCFG_DB_PREFIX . 'locurimunca',
        SYSCFG_DB_PREFIX . 'optiuni',
        SYSCFG_DB_PREFIX . 'orase',
        SYSCFG_DB_PREFIX . 'domenii_cv',
        $this->AUTH->GetUserId()
    ));
    if ($arrLocuri === false) {
        throw new Exception("Eroare interna", 500);
    }

    $this->DATA['locuri'] = [];
    foreach ($arrLocuri as $arrLoc) {
        $res = [
            'idx'                   => (int)$arrLoc['locmunca_idx'],
            'companie'              => $arrLoc['companie'],
            'idxauth'               => (int)$arrLoc['locmunca_idxauth'],
            'oras'                  => $arrLoc['oras'],
            'domeniu_cv'            => $arrLoc['domeniu_cv'],
            'competente'            => $arrLoc['locmunca_competente'],
            'titlu'                 => $arrLoc['locmunca_titlu'],
            'descriere'             => $arrLoc['locmunca_descriere'],
            'tipslujba'             => $arrLoc['tipslujba'],
        ];
        $this->DATA['locuri'][] = $res;
    }
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}