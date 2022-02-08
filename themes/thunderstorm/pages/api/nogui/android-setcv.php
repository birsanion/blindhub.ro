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

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 0){
        if ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'angajati_cv',
            array('idxauth', '=', (int)$arrUser['idx'])) > 0)
        {
            // update
            $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'angajati_cv', 'domenii, oras',
                array(
                    'domenii' => POST('domenii'),
                    'oras' => $arrOrasMap[POST('oras')]
                ),
                array('idxauth', '=', (int)$arrUser['idx'])
            );
        }else{
            // insert
            $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'angajati_cv', 'idxauth, domenii, oras',
                array(array(
                    'idxauth' => (int)$arrUser['idx'],
                    'domenii' => POST('domenii'),
                    'oras' => $arrOrasMap[POST('oras')]
                ))
            );
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';


