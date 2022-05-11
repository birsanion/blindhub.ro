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

if ($this->AUTH->IsAuthenticated()) {
    switch ((int)$this->AUTH->GetAdvancedDetail('tiputilizator')) {
        case 0:
            $this->ROUTE->Redirect(qurl_l('home-nevaz'));
            break;

        case 1:
            $this->ROUTE->Redirect(qurl_l('home-angajator'));
            break;

        case 2:
            $this->ROUTE->Redirect(qurl_l('home-universitate'));
            break;
    }
}

try {
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


    $this->DATA['orase'] = $arrOrase;
    $this->DATA['domenii_cv'] = $arrDomeniiCv;
    $this->DATA['optiuni'] = [];
	foreach ($arrOptiuni as $arrOptiune) {
    	$this->DATA['optiuni'][$arrOptiune['categorie']][$arrOptiune['idx']] = $arrOptiune['nume'];
	}
} catch (\Exception $e) {
   	$this->GLOBAL['errormsg'] = $e->getMessage();
}