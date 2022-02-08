<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'userkey' => ''
);
/*
$arrOrasMap = array(
    'Alba Iulia' => 'albaiulia',
    'Alexandria' => 'alexandria',
    'Arad' => 'arad',
    'Baia Mare' => 'baiamare',
    'Bistrița Năsăud' => 'bistrita',
    'Brăila' => 'braila',
    'București' => 'bucuresti',
    'Botoșani' => 'botosani',
    'Brașov' => 'brasov',
    'Bacău' => 'bacau',
    'Buzău' => 'buzau',
    'Călărași' => 'calarasi',
    'Cluj' => 'cluj',
    'Constanța' => 'constanta',
    'Craiova' => 'craiova',
    'Deva' => 'deva',
    'Iași' => 'iasi',
    'Focșani' => 'focsani',
    'Galați' => 'galati',
    'Giurgiu' => 'giurgiu',
    'Oradea' => 'oradea',
    'Ploiești' => 'ploiesti',
    'Pitești' => 'pitesti',
    'Piatra Neamț' => 'piatraneamt',
    'Reșița' => 'resita',
    'Râmnicu Vâlcea' => 'ramnicuvalcea',
    'Timișoara' => 'timisoara',
    'Târgu Mureș' => 'targumures',
    'Târgu Jiu' => 'targujiu',
    'Slatina' => 'slatina',
    'Sibiu' => 'sibiu',
    'Satu Mare' => 'satumare',
    'Suceava' => 'suceava',
    'Vaslui' => 'vaslui'
);
*/
$nUserIdx = 0;

$this->LOG->Log('createaccuniversitate', print_r($_POST, true));

if (strlen(POST('userkey')) <= 0){
    // introducere utilizator nou
    $nNewUserResult = $this->AUTH->AddNewUser(POST('email'), POST('parola'), $nUserIdx);
    
    if ($nNewUserResult == AUTH_SUCCESS){
        $this->AUTH->ChangeAdvancedDetails(array(
            'tiputilizator' => 2
        ), $nUserIdx);
        
        $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'universitati',
            'idxauth, nume, oras, reprezentant, gradacces, gradechipare, studdiz, studcentru, camerecamine, persdedic, cazare, costuri',
            array(array(
                'idxauth' => $nUserIdx,
                'nume' => POST('nume'),
                'oras' => POST('oras'),
                'reprezentant' => POST('reprezentant'),
                'gradacces' => POST('gradacces'),
                'gradechipare' => POST('gradechipare'),
                'studdiz' => POST('studdiz'),
                'studcentru' => POST('studcentru'),
                'camerecamine' => POST('camerecamine'),
                'persdedic' => POST('persdedic'),
                'cazare' => POST('cazare'),
                'costuri' => POST('costuri')
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
        
        if ((int)$arrUser['tiputilizator'] == 2){
            if (strlen(trim(POST('parola'))) > 0)
                $this->AUTH->ResetPassword((int)$arrUser['idx'], POST('parola'));
            
            $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'universitati',
                'nume, oras, reprezentant, gradacces, gradechipare, studdiz, studcentru, camerecamine, persdedic, cazare, costuri',
                array(
                    'nume' => POST('nume'),
                    'oras' => POST('oras'),
                    'reprezentant' => POST('reprezentant'),
                    'gradacces' => POST('gradacces'),
                    'gradechipare' => POST('gradechipare'),
                    'studdiz' => POST('studdiz'),
                    'studcentru' => POST('studcentru'),
                    'camerecamine' => POST('camerecamine'),
                    'persdedic' => POST('persdedic'),
                    'cazare' => POST('cazare'),
                    'costuri' => POST('costuri')
                ),
                array('idxauth', '=', (int)$arrUser['idx'])
            );
        }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip universitate !';
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';
}

if ($this->DATA['result'] != 'success') http_response_code(400);
