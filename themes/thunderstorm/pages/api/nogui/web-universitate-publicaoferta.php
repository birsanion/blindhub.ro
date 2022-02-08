<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);


if ((int)$this->AUTH->GetAdvancedDetail('tiputilizator') == 2){
    // insert
    $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'locuriuniversitate',
        'idxauth, facultate, domeniu, numarlocuri',
        array(array(
            'idxauth' => $this->AUTH->GetUserId(),
            'facultate' => POST('hEditFacultate'),
            'domeniu' => POST('hComboDomeniu'),
            'numarlocuri' => (int)POST('hEditNrLocuri')
        ))
    );
}else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip universitate !';
