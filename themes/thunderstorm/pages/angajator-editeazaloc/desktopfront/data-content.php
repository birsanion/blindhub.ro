<?php
call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'bootbox.min.js'
    )
);

if (!$this->AUTH->IsAuthenticated()) {
    $this->ROUTE->Redirect(qurl_l(''));
}

try {
    $idxLoc = PARAM(1);
    $arrLoc = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locurimunca', [
        ['idx', '=', $idxLoc,  'AND'],
        ['idxauth', '=', $this->AUTH->GetUserId()],
    ]);
    if ($arrLoc === false) {
        throw new Exception("Eroare internă");
    }
    if (empty($arrLoc)) {
        throw new Exception("Cerere invalidă");
    }

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

    $this->DATA['loc'] = $arrLoc[0];
    $this->DATA['orase'] = $arrOrase;
    $this->DATA['domenii_cv'] = $arrDomeniiCv;
    $this->DATA['optiuni'] = [];
	foreach ($arrOptiuni as $arrOptiune) {
    	$this->DATA['optiuni'][$arrOptiune['categorie']][$arrOptiune['idx']] = $arrOptiune['nume'];
	}
} catch (\Exception $e) {
   	$this->GLOBAL['errormsg'] = $e->getMessage();
}
