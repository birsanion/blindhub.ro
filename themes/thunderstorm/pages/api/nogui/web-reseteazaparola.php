<?php

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'hEditPass'  => 'required',
        'hStaticUserIdx'  => 'required|numeric',
        'hStaticUserKey' => 'required',
    ]);

    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $error = array_values($errors->firstOfAll())[0];
        throw new Exception("EROARE: {$error}!", 400);
    }

    $arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users', [
        ['idx', '=', $validation->getValue('hStaticUserIdx'), 'AND']
        ['recoverhash', '=', $validation->getValue('hStaticUserKey'), 'AND']

    ]);
    if ($arrUser === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrUser)) {
        throw new Exception("EROARE: acest utilizator nu există !", 400);
    }

    $arrUser = $arrUser[0];
    $strNewSalt = $this->AUTH->GetNewSalt();
    $strNewHash = $this->AUTH->GetPasswordHash($validation->getValue('password'), $arrUser['username'], $strNewSalt);
    $res = $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX.'auth_users', 'passhash, salt, recoverhash', [
        'passhash' => $strNewHash,
        'salt' => $strNewSalt,
        'recoverhash' => ''
    ], [
        'idx', '=', $arrUser['idx']
    ]);
    if ($res === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $this->DATA['result'] = 'success';
});
/*
$strParola = POST('hEditPass');
$nUserIdx = (int)POST('hStaticUserIdx');
$strUserKey = POST('hStaticUserKey');

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users', array('idx', '=', $nUserIdx));
$strResult = '';

if (is_array($arrUser) && !empty($arrUser)){
    $arrUser = $arrUser[0];

    if ($arrUser['recoverhash'] == $strUserKey){
        // set password
        $strNewSalt = $this->AUTH->GetNewSalt();
        $strNewHash = $this->AUTH->GetPasswordHash($strParola, $arrUser['username'], $strNewSalt);

        if ($this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX.'auth_users',
            'passhash, salt, recoverhash',
            array(
                'passhash' => $strNewHash,
                'salt' => $strNewSalt,
                'recoverhash' => ''
            ),
            array('idx', '=', $nUserIdx)))
        {
            $strResult = 'success';
        }else $strResult = 'EROARE: Momentan nu se poate procesa resetarea contului (1).';
    }else $strResult = 'EROARE: Momentan nu se poate procesa resetarea contului (2).';
}else $strResult = 'EROARE: Momentan nu se poate procesa resetarea contului (3).';

$this->DATA = array(
    'result' => $strResult
);
*/