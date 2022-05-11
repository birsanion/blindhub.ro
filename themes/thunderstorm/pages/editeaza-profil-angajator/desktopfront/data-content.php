<?php

call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'bootbox.min.js'
    )
);

if (!$this->AUTH->IsAuthenticated()) $this->ROUTE->Redirect(qurl_l(''));


try {
    $arrDetails = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori', [
        'idxauth', '=', $this->AUTH->GetUserId()
    ]);
    if ($arrDomeniiCv === false) {
        throw new Exception("Eroare internă");
    }
    if (empty($arrDetails)) {
        throw new Exception("Cerere invalidă");
    }
    $arrDetails = $arrDetails[0];

    $arrDomeniiCv = $this->DATABASE->RunQuickSelect(['idx', 'nume'], SYSCFG_DB_PREFIX . 'domenii_cv', NULL);
    if ($arrDomeniiCv === false) {
        throw new Exception("Eroare internă");
    }

    $arrOrase = $this->DATABASE->RunQuickSelect(['idx', 'nume'], SYSCFG_DB_PREFIX . 'orase', NULL, ['nume']);
    if ($arrOrase === false) {
        throw new Exception("Eroare internă");
    }

    $arrOptiuni = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'optiuni', NULL, ['categorie', 'nume']);
    if ($arrOptiuni === false) {
        throw new Exception("Eroare internă");
    }

    $arrAngajatorOrase = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori_orase',  [
        'idx_angajator', '=', $arrDetails['idx']
    ]);
    if ($arrAngajatoriOrase === false) {
        throw new Exception("Eroare internă");
    }

    $arrAngajatorDomeniiCv = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori_domenii_cv',  [
        'idx_angajator', '=', $arrDetails['idx']
    ]);
    if ($arrAngajatorDomeniiCv === false) {
        throw new Exception("Eroare internă");
    }

    $this->DATA['details'] = $arrDetails;
    $this->DATA['orase'] = $arrOrase;
    $this->DATA['domenii_cv'] = $arrDomeniiCv;
    $this->DATA['optiuni'] = [];
    foreach ($arrOptiuni as $arrOptiune) {
        $this->DATA['optiuni'][$arrOptiune['categorie']][$arrOptiune['idx']] = $arrOptiune['nume'];
    }
    foreach ($arrAngajatorOrase as $oras) {
        $this->DATA['details']['idx_orase'][] = $oras['idx_oras'];
    }
    foreach ($arrAngajatorDomeniiCv as $domeniu) {
        $this->DATA['details']['idx_domenii_cv'][] = $domeniu['idx_domeniu_cv'];
    }
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}
