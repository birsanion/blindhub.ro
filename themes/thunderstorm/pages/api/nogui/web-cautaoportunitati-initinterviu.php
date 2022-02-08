<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'debug' => ''
);


if ((int)$this->AUTH->GetAdvancedDetail('tiputilizator') == 0){
    if ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'cereriinterviu',
        array(
            array('idxauthangajat', '=', $this->AUTH->GetUserId(), 'AND'),
            array('idxauthangajator', '=', (int)POST('idxangajator'), 'AND'),
            array('idxlocmunca', '=', 0)
        )) <= 0)
    {
        if (!$this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'cereriinterviu',
                'idxauthangajat, idxauthangajator, idxlocmunca',
                array(array(
                    'idxauthangajat' => $this->AUTH->GetUserId(),
                    'idxauthangajator' => (int)POST('idxangajator'),
                    'idxlocmunca' => 0
                ))
            ))
        {
            $this->DATA['result'] = 'EROARE: Nu s-a putut adăuga cererea de interviu !';
        }
    }else $this->DATA['result'] = 'Ați aplicat deja la această companie în trecut !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';
