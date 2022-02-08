<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'idxuser' => -1,
    'userkey' => ''
);

$nUserIdx = 0;

$this->LOG->Log('createaccangajator', print_r($_POST, true));

if (strlen(POST('userkey')) <= 0){
    // introducere utilizator nou
    $nNewUserResult = $this->AUTH->AddNewUser(POST('email'), POST('parola'), $nUserIdx);
    
    if ($nNewUserResult == AUTH_SUCCESS){
        $this->AUTH->ChangeAdvancedDetails(array(
            'tiputilizator' => 1
        ), $nUserIdx);
        
        $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajatori',
            'idxauth, companie, adresa, cui, firmaprotejata, dimensiunefirma, domenii, orase',
            array(array(
                'idxauth' => $nUserIdx,
                'companie' => POST('companie'),
                'adresa' => POST('adresa'),
                'cui' => POST('cui'),
                'firmaprotejata' => POST('firmaprotejata'),
                'dimensiunefirma' => POST('dimensiunefirma'),
                'domenii' => POST('domenii'),
                'orase' => POST('orase')
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
        
        $this->DATA['idxuser'] = $nUserIdx;
        
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
        $this->DATA['result'] = 'EROARE: nu poate fi adăugat acest utilizator ! Poate contul există deja ?';
    }
}else{
    // actualizam utilizatorul
    
    $arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
        array('apploginid', '=', POST('userkey')));
    
    if (is_array($arrUser) && count($arrUser) > 0){
        $arrUser = $arrUser[0];
        
        if ((int)$arrUser['tiputilizator'] == 1){
            if (strlen(trim(POST('parola'))) > 0)
                $this->AUTH->ResetPassword((int)$arrUser['idx'], POST('parola'));
            
            $this->DATABASE->RunQuickUpdate(
                SYSCFG_DB_PREFIX . 'angajatori',
                'companie, adresa, cui, firmaprotejata, dimensiunefirma, domenii, orase',
                array(
                    'companie' => POST('companie'),
                    'adresa' => POST('adresa'),
                    'cui' => POST('cui'),
                    'firmaprotejata' => POST('firmaprotejata'),
                    'dimensiunefirma' => POST('dimensiunefirma'),
                    'domenii' => POST('domenii'),
                    'orase' => POST('orase')
                ),
                array('idxauth', '=', (int)$arrUser['idx'])
            );
        }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator !';
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';
}

if ($this->DATA['result'] != 'success') http_response_code(400);
