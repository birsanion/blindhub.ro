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
    $idxInterviu = PARAM(1);
    $arrInterviu = $this->DATABASE->RunQuery(sprintf(
        "SELECT angajati.* " .
        "FROM `%s` interviuri " .
        "INNER JOIN `%s` angajati " .
        "ON (interviuri.idxauthangajat = angajati.idxauth) " .
        "WHERE interviuri.idx = %d ",
        SYSCFG_DB_PREFIX . 'interviuri',
        SYSCFG_DB_PREFIX . 'angajati',
        $idxInterviu
    ));

    if ($arrInterviu === false) {
        throw new Exception("Eroare internă", 500);
    }

    if (empty($arrInterviu)) {
        throw new Exception("Cerere invalidă", 400);
    }

    $this->DATA['cv'] = $arrInterviu[0]['cv_fisier_video'];
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}
