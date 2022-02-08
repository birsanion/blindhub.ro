<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'userkey' => ''
);

$nUserIdx = 0;

if (strlen(POST('userkey')) <= 0){
    // introducere utilizator nou
    $nNewUserResult = $this->AUTH->AddNewUser(POST('email'), POST('parola'), $nUserIdx);
    
    if ($nNewUserResult == AUTH_SUCCESS){
        $this->AUTH->ChangeAdvancedDetails(array(
            'tiputilizator' => 0
        ), $nUserIdx);
        
        $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajati',
            'idxauth, nume, prenume, gradhandicap, nevoispecifice', array(array(
                'idxauth' => $nUserIdx,
                'nume' => POST('nume'),
                'prenume' => POST('prenume'),
                'gradhandicap' => POST('gradhandicap'),
                'nevoispecifice' => POST('nevoispecifice')
            ))
        );
        
        $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'auth_userpermissions',
            'usridx, target, perm',
            array(
                array(
                    'usridx' => $nUserIdx,
                    'target' => '*',
                    'perm' => '0'
                ),
                array(
                    'usridx' => $nUserIdx,
                    'target' => '*/index',
                    'perm' => '1'
                ),
                array(
                    'usridx' => $nUserIdx,
                    'target' => '*/nogui',
                    'perm' => '1'
                )
            )
        );
        
        // login
        $kNewAuth = new CQAuth();
        $kNewAuth->Init($this->DATABASE, $this->CONFIG);
        
        $nLoginCode = $kNewAuth->LogIn(POST('email'), POST('parola'));
        
        if ($nLoginCode == AUTH_SUCCESS){
            $strUserKey = $kNewAuth->GetNewSalt();
            
            while ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'auth_users', 
                    array('apploginid', '=', $strUserKey)) > 0)
                $strUserKey = $kNewAuth->GetNewSalt();
                
            $arrAllDetails = array();
            $arrAllDetails['apploginid'] = $strUserKey;
            $kNewAuth->ChangeAdvancedDetails($arrAllDetails);
            
            $this->DATA['userkey'] = $strUserKey;
        }else $this->DATA['result'] = 'EROARE: nu poate fi autentificat automat utilizatorul !';
    }else{
        $this->DATA['result'] = 'EROARE: nu poate fi adăugat acest utilizator !';
    }
}else{
    // actualizam utilizatorul
    $arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
        array('apploginid', '=', POST('userkey')));
    
    if (is_array($arrUser) && count($arrUser) > 0){
        $arrUser = $arrUser[0];
        
        if ((int)$arrUser['tiputilizator'] == 0){
            if (strlen(trim(POST('parola'))) > 0)
                $this->AUTH->ResetPassword((int)$arrUser['idx'], POST('parola'));
            
            $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'angajati',
                'nume, prenume, gradhandicap, nevoispecifice',
                array(
                    'nume' => POST('nume'),
                    'prenume' => POST('prenume'),
                    'gradhandicap' => POST('gradhandicap'),
                    'nevoispecifice' => POST('nevoispecifice')
                ),
                array('idxauth', '=', (int)$arrUser['idx'])
            );
        }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';
}

if ($this->DATA['result'] != 'success') http_response_code(400);
