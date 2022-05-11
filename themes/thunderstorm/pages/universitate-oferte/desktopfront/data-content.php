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
        'jquery-fileupload-ui.css'
    )
);

if (!$this->AUTH->IsAuthenticated()) $this->ROUTE->Redirect(qurl_l(''));

try {
    $arrRezultate = $this->DATABASE->RunQuery(sprintf(
        "SELECT locuriuniversitate.*, " .
        "       orase.nume AS oras, " .
        "       domenii_universitate.nume AS domeniu_universitate " .
        "FROM `%s` locuriuniversitate " .
        "INNER JOIN `%s` orase " .
        "ON (locuriuniversitate.idx_oras = orase.idx) " .
        "INNER JOIN `%s` domenii_universitate " .
        "ON (locuriuniversitate.idx_domeniu_universitate = domenii_universitate.idx) " .
        "WHERE locuriuniversitate.idxauth = %d " .
        "ORDER BY locuriuniversitate.idx DESC ",
        SYSCFG_DB_PREFIX . 'locuriuniversitate',
        SYSCFG_DB_PREFIX . 'orase',
        SYSCFG_DB_PREFIX . 'domenii_universitate',
        $this->AUTH->GetUserId()
    ));
    if ($arrRezultate === false) {
        die($this->DATABASE->GetError());
        throw new Exception("EROARE INTERNA", 500);
    }

    $this->DATA['locuri'] = [];
    foreach ($arrRezultate as $loc) {
        $this->DATA['locuri'][] = [
            'idx'                  => (int)$loc['idx'],
            'facultate'            => $loc['facultate'],
            'numarlocuri'          => (int)$loc['numarlocuri'],
            'domeniu_universitate' => $loc['domeniu_universitate'],
            'oras'                 => $loc['oras'],
        ];
    }
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}

