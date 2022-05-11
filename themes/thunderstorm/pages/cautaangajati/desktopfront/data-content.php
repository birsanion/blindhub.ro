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
if ((int)$this->AUTH->GetAdvancedDetail('tiputilizator') == 1){
    $arrAngajator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori',
        array('idxauth', '=', $this->AUTH->GetUserId()));

    if (is_array($arrAngajator) && count($arrAngajator) > 0){
        $arrAngajator = $arrAngajator[0];

        $strAdvQuery = "SELECT * FROM `" . SYSCFG_DB_PREFIX . 'angajati_cv` WHERE (';

        $arrDomenii = explode('|', $arrAngajator['domenii']);
        $nDomenii = count($arrDomenii);

        for ($i=0; $i < $nDomenii; $i++)
            $arrDomenii[$i] = '`domenii` LIKE \'%' . $this->DATABASE->CleanString($arrDomenii[$i]) . '%\'';

        $strAdvQuery .= implode(' OR ', $arrDomenii);
        $strAdvQuery .= ') AND (';

        $arrOrase = explode('|', $arrAngajator['orase']);
        $nOrase = count($arrOrase);

        for ($i=0; $i < $nOrase; $i++)
            $arrOrase[$i] = '`oras` = \'' . $this->DATABASE->CleanString($arrOrase[$i]) . '\'';

        $strAdvQuery .= implode(' OR ', $arrDomenii);
        $strAdvQuery .= ')';

        //$this->DATA['debug'] = $strAdvQuery;

        $arrRezultate = $this->DATABASE->RunQuery($strAdvQuery);

        if (is_array($arrRezultate) && count($arrRezultate) > 0){
            $this->DATA['locuri'] = array();

            foreach ($arrRezultate as $arrRezultat){
                $arrAngajat = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati',
                    array('idxauth', '=', (int)$arrRezultat['idxauth']));

                if (is_array($arrAngajat) && count($arrAngajat) > 0){
                    $arrAngajat = $arrAngajat[0];

                    $this->DATA['locuri'][] = array(
                        'nume' => $arrAngajat['nume'] . ' ' . $arrAngajat['prenume'],
                        'gradhandicap' => 'grad de handicap ' . $arrAngajat['gradhandicap'],
                        'nevoispecifice' => 'nevoi specifice: ' . $arrAngajat['nevoispecifice'],

                        'idxauth' => (int)$arrRezultat['idxauth']
                    );
                }else $this->DATA['nrlocuri']--;
            }
        }else{
            $this->DATA['locuri'] = array();
        }
    }else $this->DATA['result'] = 'EROARE: nu aveți completat profilul !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator !';
*/


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
        "       GROUP_CONCAT(DISTINCT domenii_cv.nume) AS domenii_cv " .
        "FROM `%s` angajati " .
        "LEFT JOIN `%s` angajatori_angajati_favoriti " .
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
        "GROUP BY angajati.idx",
        SYSCFG_DB_PREFIX . 'angajati',
        SYSCFG_DB_PREFIX . 'angajatori_angajati_favoriti',
        $arrAngajator['idxauth'],
        SYSCFG_DB_PREFIX . 'angajati_orase',
        implode(',', $idxOrase),
        SYSCFG_DB_PREFIX . 'orase',
        SYSCFG_DB_PREFIX . 'angajati_domenii_cv',
        implode(',', $idxDomenii),
        SYSCFG_DB_PREFIX . 'domenii_cv',
        SYSCFG_DB_PREFIX . 'optiuni'
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
        ];
    }
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}