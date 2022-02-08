<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);


switch ((int)$this->AUTH->GetAdvancedDetail('tiputilizator'))
{
    case 0:{ // nevazatori
        if (!$this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'mesaje',
                'idxauthangajat, idxauthinterlocutor, idxauthmesaj, mesaj',
                array(array(
                    'idxauthangajat' => $this->AUTH->GetUserId(),
                    'idxauthinterlocutor' => (int)POST('idxauthinter'),
                    'idxauthmesaj' => $this->AUTH->GetUserId(),
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
                    'idxauthinterlocutor' => $this->AUTH->GetUserId(),
                    'idxauthmesaj' => $this->AUTH->GetUserId(),
                    'mesaj' => POST('mesaj')
                ))
            ))
        {
            $this->DATA['result'] = 'EROARE: Nu s-a putut introduce mesajul !';
        }
    }break;
}
