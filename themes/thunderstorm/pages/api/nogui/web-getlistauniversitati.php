<?php

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
