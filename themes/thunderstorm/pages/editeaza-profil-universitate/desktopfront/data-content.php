<?php
call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'bootbox.min.js'
    )
);

try {
    $arrDetails = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati', [
        'idxauth', '=', $this->AUTH->GetUserId()
    ]);
    if ($arrDetails === false) {
        throw new Exception("Eroare internă");
    }
    $arrDetails = $arrDetails[0];

    $arrOrase = $this->DATABASE->RunQuickSelect(['idx', 'nume'], SYSCFG_DB_PREFIX . 'orase', NULL, ['nume']);
    if ($arrOrase === false) {
        throw new Exception("Eroare internă");
    }

    $arrOptiuni = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'optiuni', NULL, ['categorie', 'nume']);
    if ($arrOptiuni === false) {
        throw new Exception("Eroare internă");
    }

    $arrUniversitateOrase = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati_orase',  [
        'idx_universitate', '=', $arrDetails['idx']
    ]);
    if ($arrUniversitateOrase === false) {
        throw new Exception("Eroare internă");
    }

    $this->DATA['details'] = $arrDetails;
    $this->DATA['orase'] = $arrOrase;
    $this->DATA['optiuni'] = [];
    foreach ($arrUniversitateOrase as $oras) {
        $this->DATA['details']['idx_orase'][] = $oras['idx_oras'];
    }
    foreach ($arrOptiuni as $arrOptiune) {
        $this->DATA['optiuni'][$arrOptiune['categorie']][$arrOptiune['idx']] = $arrOptiune['nume'];
    }
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}