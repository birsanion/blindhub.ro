<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);

$nUserIdx = 0;

if (strlen(POST('userkey')) <= 0){
    // introducere utilizator nou
    $nNewUserResult = $this->AUTH->AddNewUser(POST('hEditEmail'), POST('hEditParola'), $nUserIdx);
    
    if ($nNewUserResult == AUTH_SUCCESS){
        $this->AUTH->ChangeAdvancedDetails(array(
            'tiputilizator' => 0
        ), $nUserIdx);
        
        $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajati',
            'idxauth, nume, prenume, gradhandicap, nevoispecifice', array(array(
                'idxauth' => $nUserIdx,
                'nume' => POST('hEditNume'),
                'prenume' => POST('hEditPrenume'),
                'gradhandicap' => POST('hRadioHandicapVizual'),
                'nevoispecifice' => POST('hEditNevoiSpecifice')
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
    }else{
        $this->DATA['result'] = 'EROARE: nu poate fi adăugat acest utilizator !<br />E posibil ca această adresă de email să existe deja !';
    }
}else{
    // actualizam utilizatorul
    if ((int)$this->AUTH->GetAdvancedDetail('tiputilizator') == 0){
        $this->AUTH->ResetPassword($this->AUTH->GetUserId(), POST('parola'));
        
        $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'angajati',
            'nume, prenume, gradhandicap, nevoispecifice',
            array(
                'nume' => POST('hEditNume'),
                'prenume' => POST('hEditPrenume'),
                'gradhandicap' => POST('hRadioHandicapVizual'),
                'nevoispecifice' => POST('hEditNevoiSpecifice')
            ),
            array('idxauth', '=', $this->AUTH->GetUserId())
        );
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';
}
