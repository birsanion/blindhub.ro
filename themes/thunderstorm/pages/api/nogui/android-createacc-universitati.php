<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);

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

$nUserIdx = 0;

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
                'oras' => $arrOrasMap[POST('oras')],
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
            $this->AUTH->ResetPassword((int)$arrUser['idx'], POST('parola'));
            
            $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'universitati',
                'nume, oras, reprezentant, gradacces, gradechipare, studdiz, studcentru, camerecamine, persdedic, cazare, costuri',
                array(
                    'nume' => POST('nume'),
                    'oras' => $arrOrasMap[POST('oras')],
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
