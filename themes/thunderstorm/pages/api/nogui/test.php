<?php
header('Access-Control-Allow-Origin: *');

$this->DATA = array(
    'result' => 'whatever',
    'tstamp' => date('YmdHis')
);

if (is_array($_POST) && !empty($_POST))
    foreach ($_POST as $strKey => $strVal)
        $this->DATA[$strKey] = $strVal;

if (POST('cumsafie') == 'cueroare')
    http_response_code(400);
