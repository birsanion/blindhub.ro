<?php

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
