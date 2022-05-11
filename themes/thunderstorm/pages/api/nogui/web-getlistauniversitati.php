<?php
/*
$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'nruniversitati' => 0,
    'universitati' => array()
);

$arrUniversitati = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati',
    array('oras', '=', POST('oras')),
    'nume'
);

if (is_array($arrUniversitati) && !empty($arrUniversitati)){
    $this->DATA['nruniversitati'] = count($arrUniversitati);

    foreach ($arrUniversitati as $arrUniversitate){
        $this->DATA['universitati'][] = array(
            'idxauth' => $arrUniversitate['idxauth'],
            'nume' => $arrUniversitate['nume']
        );
    }
}
*/

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'idx_oras' => 'required|numeric'
    ]);

    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $error = array_values($errors->firstOfAll())[0];
        throw new Exception("EROARE: {$error}!", 400);
    }

    $arrUniversitati = $this->DATABASE->RunQuery(sprintf(
        "SELECT universitati.* " .
        "FROM `%s` universitati_orase " .
        "INNER JOIN `%s` universitati " .
        "ON (universitati_orase.idx_universitate = universitati.idx) " .
        "WHERE universitati_orase.idx_oras = %d " .
        "ORDER BY universitati.nume",
        SYSCFG_DB_PREFIX . 'universitati_orase',
        SYSCFG_DB_PREFIX . 'universitati',
        $validation->getValue('idx_oras')
    ));
    if ($arrUniversitati === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $this->DATA['universitati'] = [];
    foreach ($arrUniversitati as $arrUniversitate) {
        $this->DATA['universitati'][] = [
            'idxauth' => (int)$arrUniversitate['idxauth'],
            'nume'    => $arrUniversitate['nume']
        ];
    }
});
