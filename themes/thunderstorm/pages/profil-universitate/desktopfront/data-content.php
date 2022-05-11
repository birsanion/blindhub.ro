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

try {
    $arrDetails = $this->DATABASE->RunQuery(sprintf(
        "SELECT universitati.*, " .
        "       GROUP_CONCAT(DISTINCT orase.nume) AS orase, " .
        "       optiuni_gradacces.nume AS gradaccess, " .
        "       optiuni_gradechipare.nume AS gradechipare " .
        "FROM %s universitati " .
        "INNER JOIN %s universitati_orase " .
        "ON (universitati_orase.idx_universitate = universitati.idx)" .
        "INNER JOIN %s orase " .
        "ON (universitati_orase.idx_oras = orase.idx) " .
        "INNER JOIN %s optiuni_gradacces " .
        "ON (universitati.idx_optiune_gradacces = optiuni_gradacces.idx) " .
        "INNER JOIN %s optiuni_gradechipare " .
        "ON (universitati.idx_optiune_gradechipare = optiuni_gradechipare.idx) " .
        "WHERE universitati.idxauth = %d " .
        "GROUP BY universitati.idx ",
        SYSCFG_DB_PREFIX . 'universitati',
        SYSCFG_DB_PREFIX . 'universitati_orase',
        SYSCFG_DB_PREFIX . 'orase',
        SYSCFG_DB_PREFIX . 'optiuni',
        SYSCFG_DB_PREFIX . 'optiuni',
        $this->AUTH->GetUserId()
    ));
    if ($arrDetails === false) {
        throw new Exception("Eroare internă");
    }

    $this->DATA['details'] = $arrDetails[0];
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}

/*
$arrDetails = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati',
    array('idxauth', '=', $this->AUTH->GetUserId())
);

$this->DATA['details'] = $arrDetails[0];
(/)
//$this->GLOBAL['infomsg'] = 'info message';
//$this->GLOBAL['errormsg'] = (string)$this->AUTH->GetLastActionResult();

