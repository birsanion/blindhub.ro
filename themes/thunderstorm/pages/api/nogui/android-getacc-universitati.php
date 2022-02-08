<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'idxuser' => -1
);

$arrOrasReverseMap = array(
    'albaiulia' => 'Alba Iulia',
    'alexandria' => 'Alexandria',
    'arad' => 'Arad',
    'baiamare' => 'Baia Mare',
    'bistrita' => 'Bistrița Năsăud',
    'braila' => 'Brăila',
    'bucuresti' => 'București',
    'botosani' => 'Botoșani',
    'brasov' => 'Brașov',
    'bacau' => 'Bacău',
    'buzau' => 'Buzău',
    'calarasi' => 'Călărași',
    'cluj' => 'Cluj',
    'constanta' => 'Constanța',
    'craiova' => 'Craiova',
    'deva' => 'Deva',
    'iasi' => 'Iași',
    'focsani' => 'Focșani',
    'galati' => 'Galați',
    'giurgiu' => 'Giurgiu',
    'oradea' => 'Oradea',
    'ploiesti' => 'Ploiești',
    'pitesti' => 'Pitești',
    'piatraneamt' => 'Piatra Neamț',
    'resita' => 'Reșița',
    'ramnicuvalcea' => 'Râmnicu Vâlcea',
    'timisoara' => 'Timișoara',
    'targumures' => 'Târgu Mureș',
    'targujiu' => 'Târgu Jiu',
    'slatina' => 'Slatina',
    'sibiu' => 'Sibiu',
    'satumare' => 'Satu Mare',
    'suceava' => 'Suceava',
    'vaslui' => 'Vaslui'
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 2){
        $arrUniversitate = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati',
            array('idxauth', '=', (int)$arrUser['idx']));
        
        if (is_array($arrUniversitate) && !empty($arrUniversitate)){
            $arrUniversitate = $arrUniversitate[0];
            
            $this->DATA['nume'] = $arrUniversitate['nume'];
            $this->DATA['oras'] = $arrOrasReverseMap[$arrUniversitate['oras']];
            $this->DATA['reprezentant'] = $arrUniversitate['reprezentant'];
            $this->DATA['gradacces'] = $arrUniversitate['gradacces'];
            $this->DATA['gradechipare'] = $arrUniversitate['gradechipare'];
            $this->DATA['studdiz'] = $arrUniversitate['studdiz'];
            $this->DATA['studcentru'] = $arrUniversitate['studcentru'];
            $this->DATA['camerecamine'] = $arrUniversitate['camerecamine'];
            $this->DATA['persdedic'] = $arrUniversitate['persdedic'];
            $this->DATA['cazare'] = $arrUniversitate['cazare'];
            $this->DATA['costuri'] = $arrUniversitate['costuri'];
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip universitate !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';
