<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_FILES, [
        'uploaded_file' => 'required',
    ]);

    $validation->validate();
    if ($validation->fails()) {
        throw new Exception("Cerere invalidă", 400);
    }

    $conds = [];
    $strUserKey = POST('userkey', GET('userkey', PARAM(2)));
    if ($strUserKey) {
        $conds = [ 'apploginid', '=', $strUserKey ];
    } else if ($this->AUTH->IsAuthenticated()) {
        $conds = [ 'idx', '=', $this->AUTH->GetUserId() ];
    } else {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users', $conds);
    if ($arrUser === false) {
        throw new Exception("Eroare internă", 500);
    }

    if (empty($arrUser)) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrUser = $arrUser[0];
    if ($arrUser['tiputilizator'] != 2) {
        throw new Exception("Cerere invalidă", 400);
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

    $arrUniversitate = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati', [
        'idxauth', '=', $arrUser['idx']
    ]);
    if ($arrUniversitate === false) {
        throw new Exception("Eroare internă", 500);

    }
    if (empty($arrUniversitate)) {
        throw new Exception("Cerere invalidă", 400);
    }

    $filePath = 'media/uploads/';
    $fileName = 'universitate_'.(int)$arrUser['idx'].substr($_FILES['uploaded_file']['name'], strrpos($_FILES['uploaded_file']['name'], '.'));
    if (!move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $filePath . $fileName)) {
        throw new Exception("Eroare internă", 500);
    }

    $res = $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'universitati', 'img', [
        'img' => $fileName,
    ], [
        'idxauth', '=', $arrUser['idx']
    ]);
    if ($res === false) {
        throw new Exception("Eroare internă", 500);
    }
});
