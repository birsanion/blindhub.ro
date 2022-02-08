<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey'))
);

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    switch ((int)$arrUser['tiputilizator'])
    {
        case 0:{ // nevazatori
            if (!$this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'mesaje',
                    'idxauthangajat, idxauthinterlocutor, idxauthmesaj, mesaj',
                    array(array(
                        'idxauthangajat' => (int)$arrUser['idx'],
                        'idxauthinterlocutor' => (int)POST('idxauthinter'),
                        'idxauthmesaj' => (int)$arrUser['idx'],
                        'mesaj' => POST('mesaj')
                    ))
                ))
            {
                $this->DATA['result'] = 'EROARE: Nu s-a putut introduce mesajul !';
            }
        }break;
        
        case 1: case 2:{ // angajatori, universitati
            if (!$this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'mesaje',
                    'idxauthangajat, idxauthinterlocutor, idxauthmesaj, mesaj',
                    array(array(
                        'idxauthangajat' => (int)POST('idxauthinter'),
                        'idxauthinterlocutor' => (int)$arrUser['idx'],
                        'idxauthmesaj' => (int)$arrUser['idx'],
                        'mesaj' => POST('mesaj')
                    ))
                ))
            {
                $this->DATA['result'] = 'EROARE: Nu s-a putut introduce mesajul !';
            }
        }break;
    }
}
        