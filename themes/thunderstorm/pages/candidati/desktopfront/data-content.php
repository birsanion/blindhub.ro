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

if (!$this->AUTH->IsAuthenticated()) $this->ROUTE->Redirect(qurl_l(''));

//$this->GLOBAL['infomsg'] = 'info message';
//$this->GLOBAL['errormsg'] = (string)$this->AUTH->GetLastActionResult();
/*
$this->DATA['locuri'] = array();

if ((int)$this->AUTH->GetAdvancedDetail('tiputilizator') == 1){
    $arrCereri = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'cereriinterviu',
        array('idxauthangajator', '=', $this->AUTH->GetUserId()));

    if (is_array($arrCereri) && !empty($arrCereri)){
        $arrGrupate = array();

        foreach ($arrCereri as $arrCerere){
            if (!isset($arrGrupate[(int)$arrCerere['idxauthangajat']]))
                $arrGrupate[(int)$arrCerere['idxauthangajat']] = array();

            $arrGrupate[(int)$arrCerere['idxauthangajat']][] = (int)$arrCerere['idxlocmunca'];
        }

        foreach ($arrGrupate as $nIdxAngajat => $arrLocuriMunca){
            $arrAngajat = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati',
                array('idxauth', '=', $nIdxAngajat));

            if (is_array($arrAngajat) && !empty($arrAngajat)){
                $arrAngajat = $arrAngajat[0];

                $strNumeSiPrenume = $arrAngajat['nume'] . ' ' . $arrAngajat['prenume'];
                $strGradHandicap = $arrAngajat['gradhandicap'];
                $strNevoi = $arrAngajat['nevoispecifice'];
                $arrSlujbe = array();

                foreach ($arrLocuriMunca as $nIdxLocMunca){
                    $arrLocMunca = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locurimunca',
                        array('idx', '=', $nIdxLocMunca));

                    if (is_array($arrLocMunca) && !empty($arrLocMunca))
                        $arrSlujbe[] = $arrLocMunca[0]['titlu'];
                }

                $this->DATA['locuri'][] = array(
                    'nume' => $strNumeSiPrenume,
                    'gradhandicap' => 'grad de handicap ' . $strGradHandicap,
                    'nevoispecifice' => 'nevoi specifice: ' . $strNevoi,
                    'slujbe' => implode(', ', $arrSlujbe),
                    'idxauthnevazator' => $nIdxAngajat
                );
            }
        }
    }
}else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator !';
*/
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
        "       optiuni.nume AS gradhandicap " .
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
        "WHERE cereriinterviu.idxauthangajator = %d " .
        "GROUP BY cereriinterviu.idx",
        SYSCFG_DB_PREFIX . 'cereriinterviu',
        SYSCFG_DB_PREFIX . 'locurimunca',
        SYSCFG_DB_PREFIX . 'angajati',
        SYSCFG_DB_PREFIX . 'angajatori_angajati_favoriti',
        SYSCFG_DB_PREFIX . 'angajati_orase',
        SYSCFG_DB_PREFIX . 'orase',
        SYSCFG_DB_PREFIX . 'angajati_domenii_cv',
        SYSCFG_DB_PREFIX . 'domenii_cv',
        SYSCFG_DB_PREFIX . 'optiuni',
        $this->AUTH->GetUserId()
    ));
    if ($arrCereri === false) {
        throw new Exception("Eroare interna");
    }

    $this->DATA['locuri'] = [];
    foreach ($arrCereri as $arrCerere) {
        $this->DATA['locuri'][] = [
            'nume'                    => $arrCerere['nume'] . ' ' . $arrCerere['prenume'],
            'cv_fisier_video'         => $arrCerere['cv_fisier_video'],
            'gradhandicap'            => $arrCerere['gradhandicap'],
            'nevoispecifice'          => $arrCerere['nevoispecifice'],
            'idxauthnevazator'        => (int)$arrCerere['idxauthangajat'],
            'favorit'                 => (int)$arrCerere['favorit'],
            'orase'                   => $arrCerere['orase'],
            'domenii_cv'              => $arrCerere['domenii_cv'],
            'locmunca'                => [
                'idx'   => (int)$arrCerere['idx'],
                'titlu' => (string)$arrCerere['titlu'],
            ]
        ];
    }
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}
