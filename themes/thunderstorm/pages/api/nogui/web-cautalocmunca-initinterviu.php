<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'debug' => ''
);

if ((int)$this->AUTH->GetAdvancedDetail('tiputilizator') == 0){
    $arrLocMunca = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locurimunca',
        array('idx', '=', (int)POST('idxlocmunca')));
    
    if (is_array($arrLocMunca) && !empty($arrLocMunca)){
        $arrLocMunca = $arrLocMunca[0];
        
        if ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'cereriinterviu',
            array(
                array('idxauthangajat', '=', $this->AUTH->GetUserId(), 'AND'),
                array('idxauthangajator', '=', (int)$arrLocMunca['idxauth'], 'AND'),
                array('idxlocmunca', '=', (int)POST('idxlocmunca'))
            )) <= 0)
        {
            if (!$this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'cereriinterviu',
                    'idxauthangajat, idxauthangajator, idxlocmunca',
                    array(array(
                        'idxauthangajat' => $this->AUTH->GetUserId(),
                        'idxauthangajator' => (int)$arrLocMunca['idxauth'],
                        'idxlocmunca' => (int)POST('idxlocmunca')
                    ))
                ))
            {
                $this->DATA['result'] = 'EROARE: Nu s-a putut adăuga cererea de interviu !';
            }
        }else $this->DATA['result'] = 'Ați aplicat deja la acest loc de muncă în trecut !';
    }
}else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';
