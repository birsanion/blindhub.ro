<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'nruniversitati' => 0,
    'universitati' => array()
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

$arrUniversitati = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati',
    array('oras', '=', $arrOrasMap[POST('oras')]),
    'nume'
);

if (is_array($arrUniversitati) && !empty($arrUniversitati)){
    $this->DATA['nruniversitati'] = count($arrUniversitati);
    
    foreach ($arrUniversitati as $arrUniversitate){
        $this->DATA['universitati'][] = array(
            'idxauth' => $arrUniversitate['idxauth'],
            'nume' => $arrUniversitate['nume']
        );
    }
}
