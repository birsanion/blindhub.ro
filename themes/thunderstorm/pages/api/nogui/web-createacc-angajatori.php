<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'idxuser' => -1
);

$nUserIdx = 0;

if (strlen(POST('userkey')) <= 0){
    // introducere utilizator nou
    $nNewUserResult = $this->AUTH->AddNewUser(POST('hEditEmail'), POST('hEditParola'), $nUserIdx);
    
    if ($nNewUserResult == AUTH_SUCCESS){
        $this->AUTH->ChangeAdvancedDetails(array(
            'tiputilizator' => 1
        ), $nUserIdx);
        
        // get array POST
        $arrDomenii = array();
        foreach ($_POST as $strKey => $strVal)
            if (strpos($strKey, 'hCheck_Domenii') === 0)
                $arrDomenii[] = $strVal;
        
        $arrOrase = array();
        foreach ($_POST as $strKey => $strVal)
            if (strpos($strKey, 'hCheck_Orase') === 0)
                $arrOrase[] = $strVal;
        
        $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajatori',
            'idxauth, companie, adresa, cui, firmaprotejata, dimensiunefirma, tipslujba, domenii, orase',
            array(array(
                'idxauth' => $nUserIdx,
                'companie' => POST('hEditNumeFirma'),
                'adresa' => POST('hEditAdresa'),
                'cui' => POST('hEditCUI'),
                'firmaprotejata' => POST('hRadioFirmaProtej'),
                'dimensiunefirma' => POST('hRadioFirmaAngajati'),
                'tipslujba' => POST('hRadioFirmaPerContr'),
                'domenii' => implode('|', $arrDomenii),
                'orase' => implode('|', $arrOrase)
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
        $this->DATA['result'] = 'EROARE: nu poate fi adăugat acest utilizator !<br />E posibil ca această adresă de email să existe deja !';
    }
}else{
    // actualizam utilizatorul
    if ((int)$this->AUTH->GetAdvancedDetail('tiputilizator') == 1){
        $this->AUTH->ResetPassword($this->AUTH->GetUserId(), POST('hEditParola'));
        
        // get array POST
        $arrDomenii = array();
        foreach ($_POST as $strKey => $strVal)
            if (strpos($strKey, 'hCheck_Domenii') === 0)
                $arrDomenii[] = $strVal;
        
        $arrOrase = array();
        foreach ($_POST as $strKey => $strVal)
            if (strpos($strKey, 'hCheck_Orase') === 0)
                $arrOrase[] = $strVal;
        
        $this->DATABASE->RunQuickUpdate(
            SYSCFG_DB_PREFIX . 'angajatori',
            'companie, adresa, cui, firmaprotejata, dimensiunefirma, tipslujba, domenii, orase',
            array(
                'companie' => POST('hEditNumeFirma'),
                'adresa' => POST('hEditAdresa'),
                'cui' => POST('hEditCUI'),
                'firmaprotejata' => POST('hRadioFirmaProtej'),
                'dimensiunefirma' => POST('hRadioFirmaAngajati'),
                'tipslujba' => POST('hRadioFirmaPerContr'),
                'domenii' => implode('|', $arrDomenii),
                'orase' => implode('|', $arrOrase)
            ),
            array('idxauth', '=', $this->AUTH->GetUserId())
        );
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator !';
}
