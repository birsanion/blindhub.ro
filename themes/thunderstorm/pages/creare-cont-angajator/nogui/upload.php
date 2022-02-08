<?php

include qurl_si('creare-cont-angajator/uploadhandler');

$kUpload = new UploadHandler(array(
        'upload_dir' => qurl_serverfile('media/uploads/'),
        'upload_url' => '',
        'image_versions' => array(),
        'rename_file' => 'angajator_'. (int)POST('hStaticIdxAngajator') . '.jpg',
        'accept_file_types' => '/\.[jpg]+$/i',
        'overwrite' => true
    ), false
);

$mxResponse = $kUpload->initialize(false);

if (isset($mxResponse['files'][0]->error))
    $this->DATA = array('result' => 'EROARE: ' . $mxResponse['files'][0]->error);
else{
    $this->DATA = array('result' => 'success');
}

