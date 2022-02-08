<?php

function RomanianDate_to_MySQLDate($pcDate)
{
    return substr($pcDate,6,4).'-'.substr($pcDate,3,2).'-'.substr($pcDate,0,2).
        (strlen($pcDate)>10 ? ' '.substr($pcDate, 11) : '');
}

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
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
*/
$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 1){
        // insert
        $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'locurimunca',
            'idxauth, domeniu, oras, competente, titlu, descriere, expirare, tipslujba',
            array(array(
                'idxauth' => (int)$arrUser['idx'],
                'domeniu' => POST('domeniu'),
                'oras' => POST('oras'),
                'competente' => POST('competente'),
                'titlu' => POST('titlu'),
                'descriere' => POST('descriere'),
                'expirare' => RomanianDate_to_MySQLDate(POST('expirare')),
                'tipslujba' => POST('tipslujba')
            ))
        );
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
