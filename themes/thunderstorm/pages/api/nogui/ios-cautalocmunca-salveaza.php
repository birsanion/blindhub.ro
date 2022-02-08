<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'debug' => ''
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 0){
        $arrLocMunca = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locurimunca',
            array('idx', '=', (int)POST('idxlocmunca')));
        
        if (is_array($arrLocMunca) && !empty($arrLocMunca)){
            $arrLocMunca = $arrLocMunca[0];
            
            if ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'angajati_locurisalvate',
                array(
                    array('idxauthangajat', '=', (int)$arrUser['idx'], 'AND'),
                    array('idxauthangajator', '=', (int)$arrLocMunca['idxauth'], 'AND'),
                    array('idxlocmunca', '=', (int)POST('idxlocmunca'))
                )) <= 0)
            {
                if (!$this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajati_locurisalvate',
                        'idxauthangajat, idxauthangajator, idxlocmunca',
                        array(array(
                            'idxauthangajat' => (int)$arrUser['idx'],
                            'idxauthangajator' => (int)$arrLocMunca['idxauth'],
                            'idxlocmunca' => (int)POST('idxlocmunca')
                        ))
                    ))
                {
                    $this->DATA['result'] = 'EROARE: Nu s-a putut salva locul de muncă în listă !';
                }
            }else $this->DATA['result'] = 'Ați salvat deja acest loc de muncă în trecut !';
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
