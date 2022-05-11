<?php
////////////////////////////////////////////////////////////////////////////////
// Part of theme Thunderstorm, of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.

call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'jquery-ui-1-10-3-custom-min.js',
        'bootbox.min.js',
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
    $idxLoc = PARAM(1);
    $arrLoc = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locuriuniversitate', [
        ['idx', '=', $idxLoc,  'AND'],
        ['idxauth', '=', $this->AUTH->GetUserId()],
    ]);
    if ($arrLoc === false) {
        throw new Exception("Eroare internă");
    }
    if (empty($arrLoc)) {
        throw new Exception("Cerere invalidă");
    }

	$arrOrase = $this->DATABASE->RunQuickSelect(['idx', 'nume'], SYSCFG_DB_PREFIX . 'orase', NULL, ['nume']);
    if ($arrOrase === false) {
        throw new Exception("Eroare internă");
    }

    $arrDomeniiUniversitate = $this->DATABASE->RunQuickSelect(['idx', 'nume'], SYSCFG_DB_PREFIX . 'domenii_universitate', NULL, ['nume']);
    if ($arrDomeniiUniversitate === false) {
        throw new Exception("Eroare internă");
    }

    $this->DATA['loc'] = $arrLoc[0];
    $this->DATA['orase'] = $arrOrase;
    $this->DATA['domenii_universitate'] = $arrDomeniiUniversitate;
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}

//$this->GLOBAL['infomsg'] = 'info message';
//$this->GLOBAL['errormsg'] = (string)$this->AUTH->GetLastActionResult();

