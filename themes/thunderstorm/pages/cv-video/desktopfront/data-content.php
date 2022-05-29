<?php

if (!$this->AUTH->IsAuthenticated()) {
    $this->ROUTE->Redirect(qurl_l(''));
}

switch ((int)$this->AUTH->GetAdvancedDetail('tiputilizator')) {
    case 0:
        $idxAuth = $this->AUTH->GetUserId();
        break;

    case 1:
    case 2:
        $idxAuth = PARAM(1);
        break;
}

try {
    $arrAngajat = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati', [
        'idxauth', '=', $idxAuth
    ]);
    if ($arrAngajat === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrAngajat)) {
        throw new Exception("EROARE: acest utilizator nu există !", 400);
    }

    $this->DATA['cv'] = $arrAngajat[0]['cv_fisier_video'];
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}
