<?php
////////////////////////////////////////////////////////////////////////////////
// Part of theme Thunderstorm, of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.

call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'jquery-ui-1-10-3-custom-min.js',
        'jq-file-upload/jquery.iframe-transport.js',
        'jq-file-upload/jquery.fileupload.js',
        'bootbox.min.js',

    )
);

call_user_func($this->fncCallback, 'htmlheader', 'structure-styles', MANOP_SET,
    array(
        'jquery-ui-1-10-3-custom.css',
        'jquery-fileupload-ui.css'
    )
);

if (!$this->AUTH->IsAuthenticated()) $this->ROUTE->Redirect(qurl_l(''));

function GetTimeDifferenceFromNow($strPastDate)
{
    // 0123-56-89
    $nPast = mktime(0, 0, 0, (int)substr($strPastDate, 5, 2),
        (int)substr($strPastDate, 8, 2), (int)substr($strPastDate, 0, 4));

    return floor((time() - $nPast) / 86400);
}

//$this->GLOBAL['infomsg'] = 'info message';
//$this->GLOBAL['errormsg'] = (string)$this->AUTH->GetLastActionResult();
/*
$arrCVNevazator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati_cv',
    array('idxauth', '=', $this->AUTH->GetUserId()));

if (is_array($arrCVNevazator) && count($arrCVNevazator) > 0){
    $arrCVNevazator = $arrCVNevazator[0];

    $strAdvQuery = "SELECT * FROM `" . SYSCFG_DB_PREFIX . 'angajatori` WHERE (';

    $arrDomenii = explode('|', $arrCVNevazator['domenii']);
    $nDomenii = count($arrDomenii);

    for ($i=0; $i < $nDomenii; $i++)
        $arrDomenii[$i] = '`domenii` LIKE \'%' . $this->DATABASE->CleanString($arrDomenii[$i]) . '%\'';

    $strAdvQuery .= implode(' OR ', $arrDomenii);
    $strAdvQuery .= ') AND `orase` LIKE \'%' . $this->DATABASE->CleanString($arrCVNevazator['oras']) . '%\'';

    $arrRezultate = $this->DATABASE->RunQuery($strAdvQuery);

    if (is_array($arrRezultate) && count($arrRezultate) > 0){
        $this->DATA['nrlocuri'] = count($arrRezultate);
        $this->DATA['locuri'] = array();

        foreach ($arrRezultate as $arrRezultat){
            $this->DATA['locuri'][] = array(
                'nume' => $arrRezultat['companie'],
                'firmaprotejata' => ($arrRezultat['firmaprotejata'] == 'da' ?
                    'este firmă protejată' : 'nu este firmă protejată'),

                'dimensiunefirma' => ($arrRezultat['dimensiunefirma'] == 'peste50' ?
                    'are peste 50 de angajați' : 'are sub 50 de angajați'),

                'tipslujba' => ($arrRezultat['tipslujba'] == 'fulltime' ? 'Full-time' : 'Part-time'),

                'idxangajator' => (int)$arrRezultat['idx']
            );
        }
    }else{
        $this->DATA['nrlocuri'] = 0;
        $this->DATA['locuri'] = array();
    }
}else $this->GLOBAL['errormsg'] = 'EROARE: nu aveți completat CV-ul !<br />' .
    'Întâi de toate trebuie să vă completați CV-ul fiindcă rezultatele depind de criteriile selectate de dumneavoastră.';
*/

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
        "       domenii_cv.nume AS domeniu_cv " .
        "FROM `%s` locurimunca " .
        "INNER JOIN `%s` angajatori " .
        "ON (locurimunca.idxauth = angajatori.idxauth) " .
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
        "GROUP BY locurimunca.idx " .
        "ORDER BY locurimunca.idx DESC ",
        SYSCFG_DB_PREFIX . 'locurimunca',
        SYSCFG_DB_PREFIX . 'angajatori',
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
            'oras'                  => $arrRezultat['oras'],
            'domeniu_cv'            => $arrRezultat['domeniu_cv'],
            'competente'            => $arrRezultat['competente'],
            'titlu'                 => $arrRezultat['titlu'],
            'descriere'             => $arrRezultat['descriere'],
            'tipslujba'             => $arrRezultat['tipslujba'],
        ];
    }
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}

