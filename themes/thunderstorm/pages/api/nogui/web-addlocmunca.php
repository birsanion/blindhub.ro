<?php

function RomanianDate_to_MySQLDate($pcDate)
{
    return substr($pcDate,6,4).'-'.substr($pcDate,3,2).'-'.substr($pcDate,0,2).
        (strlen($pcDate)>10 ? ' '.substr($pcDate, 11) : '');
}

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);

if ((int)$this->AUTH->GetAdvancedDetail('tiputilizator') == 1){
    // insert
    $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'locurimunca',
        'idxauth, domeniu, oras, competente, titlu, descriere, expirare',
        array(array(
            'idxauth' => $this->AUTH->GetUserId(),
            'domeniu' => POST('hComboDomeniu'),
            'oras' => POST('hComboOras'),
            'competente' => POST('hEditCompetente'),
            'titlu' => POST('hEditTitlu'),
            'descriere' => POST('hEditDescriere'),
            'expirare' => RomanianDate_to_MySQLDate(POST('hEditDataExp'))
        ))
    );
}else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator !';


