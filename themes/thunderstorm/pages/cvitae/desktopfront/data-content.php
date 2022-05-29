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
    )
);

if (!$this->AUTH->IsAuthenticated()) {
    $this->ROUTE->Redirect(qurl_l(''));
}

try {
    $arrDetails = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati', [
        'idxauth', '=', $this->AUTH->GetUserId()
    ]);
    if ($arrDetails === false) {
        throw new Exception("Eroare internă");
    }

    $arrDetails = $arrDetails[0];

    $arrAngajatOrase = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati_orase',  [
        'idx_angajat', '=', $arrDetails['idx']
    ]);
    if ($arrAngajatOrase === false) {
        throw new Exception("Eroare internă");
    }

    $arrAngajatDomeniiCv = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati_domenii_cv', [
        'idx_angajat', '=', $arrDetails['idx']
    ]);
    if ($arrAngajatDomeniiCv === false) {
        throw new Exception("Eroare internă");
    }


    $arrDomeniiCv = $this->DATABASE->RunQuickSelect(['idx', 'nume'], SYSCFG_DB_PREFIX . 'domenii_cv', NULL);
    if ($arrDomeniiCv === false) {
        throw new Exception("Eroare internă");
    }

    $arrOrase = $this->DATABASE->RunQuickSelect(['idx', 'nume'], SYSCFG_DB_PREFIX . 'orase', NULL, ['nume']);
    if ($arrOrase === false) {
        throw new Exception("Eroare internă");
    }

    $this->DATA['details'] = $arrDetails;
    $this->DATA['orase'] = $arrOrase;
    $this->DATA['domenii_cv'] = $arrDomeniiCv;
    foreach ($arrAngajatOrase as $oras) {
        $this->DATA['details']['idx_orase'][] = $oras['idx_oras'];
    }
    foreach ($arrAngajatDomeniiCv as $domeniu) {
        $this->DATA['details']['idx_domenii_cv'][] = $domeniu['idx_domeniu_cv'];
    }

} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}