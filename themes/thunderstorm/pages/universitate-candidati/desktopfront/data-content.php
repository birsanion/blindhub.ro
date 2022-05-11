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

if (!$this->AUTH->IsAuthenticated()) {
    $this->ROUTE->Redirect(qurl_l(''));
};

if ($this->AUTH->GetAdvancedDetail('tiputilizator') != 2) {
    $this->ROUTE->Redirect(qurl_l(''));
}



//$this->GLOBAL['infomsg'] = 'info message';
//$this->GLOBAL['errormsg'] = (string)$this->AUTH->GetLastActionResult();
/*
$arrDomeniiReverseMap = array(
    'it' => 'IT',
    'medical' => 'Medical',
    'callcenter' => 'Call center',
    'resurseumane' => 'Resurse umane',
    'asistentasociala' => 'Asistență socială',
    'jurnalism' => 'Jurnalism și relații publice',
    'radio' => 'Radio',
    'psihologie' => 'Psihologie consiliere coaching',
    'educatie' => 'Educație și training',
    'artistica' => 'Industria creativă și artistică',
    'administratie' => 'Administrație publică și instituții',
    'desk' => 'Desk office',
    'wellness' => 'Wellness și SPA',
    'traducator' => 'Traducător / translator',
    'diverse' => 'Diverse'
);

if ((int)$this->AUTH->GetAdvancedDetail('tiputilizator') == 2){
    $arrCereri = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate',
        array('idxauthuniversitate', '=', $this->AUTH->GetUserId()));

    if (is_array($arrCereri) && !empty($arrCereri)){
        $arrGrupate = array();

        foreach ($arrCereri as $arrCerere){
            if (!isset($arrGrupate[(int)$arrCerere['idxauthangajat']]))
                $arrGrupate[(int)$arrCerere['idxauthangajat']] = array();

            $arrGrupate[(int)$arrCerere['idxauthangajat']][] = (int)$arrCerere['idxlocuniversitate'];
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
                    $arrLocMunca = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locuriuniversitate',
                        array('idx', '=', $nIdxLocMunca));

                    if (is_array($arrLocMunca) && !empty($arrLocMunca))
                        $arrSlujbe[] = $arrLocMunca[0]['facultate'] . ' - ' . $arrDomeniiReverseMap[$arrLocMunca[0]['domeniu']];
                }

                $this->DATA['locuri'][] = array(
                    'nume' => $strNumeSiPrenume,
                    'gradhandicap' => 'grad de handicap ' . $strGradHandicap,
                    'nevoispecifice' => 'nevoi specifice: ' . $strNevoi,
                    'facultati' => implode(', ', $arrSlujbe),
                    'idxauthnevazator' => $nIdxAngajat
                );

                $this->DATA['nrlocuri']++;
            }
        }
    }
}else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator !';
*/

try {
    $arrCereri = $this->DATABASE->RunQuery(sprintf(
        "SELECT cereriinterviuuniversitate.idxauthangajat, " .
        "       angajati.nume, " .
        "       angajati.prenume, " .
        "       optiuni.nume AS gradhandicap, " .
        "       angajati.nevoispecifice, " .
        "       domenii_universitate.nume AS domeniu_universitate, " .
        "       orase.nume AS oras, " .
        "       locuriuniversitate.* " .
        "FROM `%s` cereriinterviuuniversitate " .
        "INNER JOIN `%s` locuriuniversitate " .
        "ON (cereriinterviuuniversitate.idxlocuniversitate = locuriuniversitate.idx) " .
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
        SYSCFG_DB_PREFIX . 'domenii_universitate',
        SYSCFG_DB_PREFIX . 'orase',
        SYSCFG_DB_PREFIX . 'angajati',
        SYSCFG_DB_PREFIX . 'optiuni',
        $this->AUTH->GetUserId()
    ));
    if ($arrCereri === false) {
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
