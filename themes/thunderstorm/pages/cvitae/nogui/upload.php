<?php

include qurl_si('cvitae/uploadhandler');

$kUpload = new UploadHandler(array(
        'upload_dir' => qurl_serverfile('media/uploads/'),
        'upload_url' => '',
        'image_versions' => array(),
        'rename_file' => 'nevazator_cv_'. $this->AUTH->GetUserId() .'.mp4',
        'accept_file_types' => '/\.[mp4]+$/i',
        'overwrite' => true
    ), false
);

$mxResponse = $kUpload->initialize(false);

if (isset($mxResponse['files'][0]->error))
    $this->DATA = array('result' => 'EROARE: ' . $mxResponse['files'][0]->error);
else{
    $this->DATA = array('result' => 'success');
}

