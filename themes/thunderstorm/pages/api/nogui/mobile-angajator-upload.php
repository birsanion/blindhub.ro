<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_FILES, [
        'uploaded_file' => 'required',
    ]);

    $validation->validate();
    if ($validation->fails()) {
        throw new Exception("EROARE: cerere invalida!", 400);
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
    if ($arrUser['tiputilizator'] != 1) {
        throw new Exception("EROARE: acest utilizator nu este de tip angajator!", 400);
    }

    switch ($validation->getValue('uploaded_file')['error']) {
        case UPLOAD_ERR_OK:
            $mime = mime_content_type($validation->getValue('uploaded_file')['tmp_name']);
            if (!preg_match('/image\/*/', $mime)) {
                throw new Exception("EROARE: format fisier invalid", 400);
            }
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new Exception("EROARE: limita dimensiune depasita a fisierului", 400);
        default:
            throw new Exception("EROARE: nu s-a încărcat fișierul", 500);
    }

    $arrAngajator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori', [
        'idxauth', '=', $arrUser['idx']
    ]);
    if ($arrAngajator === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrAngajator)) {
        throw new Exception("EROARE: acest angajator nu există !", 400);
    }

    $filePath = 'media/uploads/';
    $arrAngajator = $arrAngajator[0];
    if ($arrAngajator['img']) {
        if (!unlink($filePath . $arrAngajator['img'])) {
            throw new Exception("EROARE INTERNA", 500);
        }
    }

    $fileName = 'angajator_' . (int)$arrUser['idx'] . substr($_FILES['uploaded_file']['name'], strrpos($_FILES['uploaded_file']['name'], '.'));
    if (!move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $filePath . $fileName)) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $res = $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'angajatori', 'img', [
        'img' => $fileName,
    ], [
        'idxauth', '=', $arrUser['idx']
    ]);
    if ($res === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
});
