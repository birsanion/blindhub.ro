<?php

call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'bootbox.min.js'
    )
);

if (!$this->AUTH->IsAuthenticated()) $this->ROUTE->Redirect(qurl_l(''));

try {
    $arrDomeniiUniversitate = $this->DATABASE->RunQuickSelect(['idx', 'nume'], SYSCFG_DB_PREFIX . 'domenii_universitate', NULL);
    if ($arrDomeniiUniversitate === false) {
        throw new Exception("Eroare internă");
    }

    $arrOrase = $this->DATABASE->RunQuickSelect(['idx', 'nume'], SYSCFG_DB_PREFIX . 'orase', NULL, ['nume']);
    if ($arrOrase === false) {
        throw new Exception("Eroare internă");
    }

    $this->DATA['orase'] = $arrOrase;
    $this->DATA['domenii_universitate'] = $arrDomeniiUniversitate;
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}

