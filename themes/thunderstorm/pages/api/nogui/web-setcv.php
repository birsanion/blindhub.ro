<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);

$arrDomenii = array();

foreach ($_POST as $strKey => $strValue){
    if (strpos($strKey, 'hCheck') === 0)
        $arrDomenii[] = $strValue;
}

if ((int)$this->AUTH->GetAdvancedDetail('tiputilizator') == 0){
    if ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'angajati_cv',
        array('idxauth', '=', $this->AUTH->GetUserId())) > 0)
    {
        // update
        $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'angajati_cv', 'domenii, oras',
            array(
                'domenii' => implode('|', $arrDomenii),
                'oras' => POST('hCombo_Oras')
            ),
            array('idxauth', '=', $this->AUTH->GetUserId())
        );
    }else{
        // insert
        $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajati_cv', 'idxauth, domenii, oras',
            array(array(
                'idxauth' => $this->AUTH->GetUserId(),
                'domenii' => implode('|', $arrDomenii),
                'oras' => POST('hCombo_Oras')
            ))
        );
    }
}else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';


