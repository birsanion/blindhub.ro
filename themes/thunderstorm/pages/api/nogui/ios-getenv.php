<?php

if (POST('appkey') == '8GLmkhmzDwP6wsFXTLUPs9kptX6Swb'){
    $this->DATA = array(
        'result' => 'success',
        'tstamp' => date('YmdHis'),
        'mentenanta' => false
    );
    
    $this->DATA['orase'] = array(
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
        'targoviste' => 'Târgoviște',
        'vaslui' => 'Vaslui'
    );
    /*
    $this->DATA['orase_reverse'] = array(
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
    $this->DATA['domenii'] = array(
        'it' => 'IT',
        'medical' => 'Medical',
        'callcenter' => 'Call center',
        'resurseumane' => 'Resurse umane',
        'asistentasociala' => 'Asistență socială',
        'jurnalism' => 'Jurnalism și relații publice',
        'radio' => 'Radio',
        'psihologie' => 'Psihologie, consiliere, coaching',
        'educatie' => 'Educație și training',
        'artistica' => 'Industria creativă și artistică',
        'administratie' => 'Administrație publică și instituții',
        'desk' => 'Desk office',
        'wellness' => 'Wellness și SPA',
        'traducator' => 'Traducător / translator',
        'diverse' => 'Diverse'
    );
    /*
    $this->DATA['domenii_reverse'] = array(
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

}else{
    $this->DATA = array(
        'result' => 'fail'
    );
    
    http_response_code(400);
}
