<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'idxuser' => -1
);

$nUserIdx = 0;

if (strlen(POST('userkey')) <= 0){
    // introducere utilizator nou
    $nNewUserResult = $this->AUTH->AddNewUser(POST('email'), POST('parola'), $nUserIdx);
    
    if ($nNewUserResult == AUTH_SUCCESS){
        $this->AUTH->ChangeAdvancedDetails(array(
            'tiputilizator' => 1
        ), $nUserIdx);
        
        $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajatori',
            'idxauth, companie, adresa, cui, firmaprotejata, dimensiunefirma, tipslujba, domenii, orase',
            array(array(
                'idxauth' => $nUserIdx,
                'companie' => POST('companie'),
                'adresa' => POST('adresa'),
                'cui' => POST('cui'),
                'firmaprotejata' => POST('firmaprotejata'),
                'dimensiunefirma' => POST('dimensiunefirma'),
                'tipslujba' => POST('tipslujba'),
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
    }else{
        $this->DATA['result'] = 'EROARE: nu poate fi adăugat acest utilizator !';
    }
}else{
    // actualizam utilizatorul
    
    $arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
        array('apploginid', '=', POST('userkey')));
    
    if (is_array($arrUser) && count($arrUser) > 0){
        $arrUser = $arrUser[0];
        
        if ((int)$arrUser['tiputilizator'] == 1){
            $this->AUTH->ResetPassword((int)$arrUser['idx'], POST('parola'));
            
            $this->DATABASE->RunQuickUpdate(
                SYSCFG_DB_PREFIX . 'angajatori',
                'companie, adresa, cui, firmaprotejata, dimensiunefirma, tipslujba, domenii, orase',
                array(
                    'companie' => POST('companie'),
                    'adresa' => POST('adresa'),
                    'cui' => POST('cui'),
                    'firmaprotejata' => POST('firmaprotejata'),
                    'dimensiunefirma' => POST('dimensiunefirma'),
                    'tipslujba' => POST('tipslujba'),
                    'domenii' => POST('domenii'),
                    'orase' => POST('orase')
                ),
                array('idxauth', '=', (int)$arrUser['idx'])
            );
        }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator !';
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';
}
