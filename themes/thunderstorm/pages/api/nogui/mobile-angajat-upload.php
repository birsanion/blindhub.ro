<?php

require_once('system/thirdparty/vendor/autoload.php');

use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use FFMpeg\Coordinate\Dimension;

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_FILES, [
        'uploaded_file' => 'required',
    ]);

    $validation->validate();
    if ($validation->fails()) {
        throw new Exception("EROARE: cerere invalida !", 400);
    }

    $strUserKey = POST('userkey', GET('userkey', PARAM(2)));

    $arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users', [
        'apploginid', '=', $strUserKey
    ]);

    if ($arrUser === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    if (empty($arrUser)) {
        throw new Exception("EROARE: acest utilizator nu există !", 400);
    }

    $arrUser = $arrUser[0];
    if ($arrUser['tiputilizator'] != 0) {
        throw new Exception("EROARE: acest utilizator nu este de tip nevăzător !", 400);
    }

    switch ($validation->getValue('uploaded_file')['error']) {
        case UPLOAD_ERR_OK:
            $mime = mime_content_type($validation->getValue('uploaded_file')['tmp_name']);
            if (!preg_match('/video\/*/', $mime)) {
                throw new Exception("EROARE: format fisier invalid", 400);
            }
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new Exception("EROARE: limita dimensiune depasita a fisierului", 400);
        default:
            throw new Exception("EROARE: nu s-a încărcat fișierul", 500);
    }

    $filename = 'nevazator_cv_' . $arrUser['idx'] . '.mp4';
    $ffmpeg = FFMpeg::create();
    $video = $ffmpeg->open($validation->getValue('uploaded_file')['tmp_name']);
    // $video->filters()->resize(new Dimension(420, 420));
    $video->save(new X264(),  'media/uploads/' . $filename);

    $res = $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'angajati', 'cv_fisier_video', [
        'cv_fisier_video' => $filename,
    ], [
        'idxauth', '=', $arrUser['idx']
    ]);
    if ($res === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
});
