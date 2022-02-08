<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'nrrezultate' => 0,
    'rezultate' => array()
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

$arrDomeniiMap = array(
    'IT' => 'it',
    'Medical' => 'medical',
    'Call center' => 'callcenter',
    'Resurse umane' => 'resurseumane',
    'Asistență socială' => 'asistentasociala',
    'Jurnalism și relații publice' => 'jurnalism',
    'Radio' => 'radio',
    'Psihologie, consiliere, coaching' => 'psihologie',
    'Educație și training' => 'educatie',
    'Industria creativă și artistică' => 'artistica',
    'Administrație publică și instituții' => 'administratie',
    'Desk office' => 'desk',
    'Wellness și SPA' => 'wellness',
    'Traducător / translator' => 'traducator',
    'Diverse' => 'diverse'
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 0){
        $arrUniversitati = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati',
            array(
                array('nume', '=', POST('universitate'), 'AND'),
                array('oras', '=', $arrOrasMap[POST('oras')])
            )
        );
        
        if (is_array($arrUniversitati) && !empty($arrUniversitati)){
            foreach ($arrUniversitati as $arrUniversitate){
                $arrLocuri = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locuriuniversitate',
                    array(
                        array('domeniu', '=', $arrDomeniiMap[POST('domeniu')], 'AND'),
                        array('idxauth', '=', (int)$arrUniversitate['idxauth'])
                    )
                );
                
                if (is_array($arrLocuri) && !empty($arrLocuri)){
                    foreach ($arrLocuri as $arrLoc){
                        $this->DATA['nrrezultate']++;
                        
                        $this->DATA['rezultate'][] = array(
                            'numeuniversitate' => $arrUniversitate['nume'],
                            'idxauth' => (int)$arrUniversitate['idxauth'],
                            'facultate' => $arrLoc['facultate'],
                            'nrlocuri' => $arrLoc['numarlocuri'],
                            'idxloc' => (int)$arrLoc['idx']
                        );
                    }
                }
            }
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';


