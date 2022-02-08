<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);

if ((int)$this->AUTH->GetAdvancedDetail('tiputilizator') == 0){
    if ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate',
        array(
            array('idxauthangajat', '=', $this->AUTH->GetUserId(), 'AND'),
            array('idxauthuniversitate', '=', (int)POST('idxauthuniversitate'), 'AND'),
            array('idxlocuniversitate', '=', (int)POST('idxloc'))
        )) <= 0)
    {
        $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate',
            'idxauthangajat, idxauthuniversitate, idxlocuniversitate',
            array(array(
                'idxauthangajat' => $this->AUTH->GetUserId(),
                'idxauthuniversitate' => (int)POST('idxauthuniversitate'),
                'idxlocuniversitate' => (int)POST('idxloc')
            ))
        );
    }else{
        $this->DATA['result'] = 'Ați aplicat deja pentru acest interviu în trecut !';
    }
}else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';
