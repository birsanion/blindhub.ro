<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ($this->AUTH->CompareHashes($arrUser['passhash'],
        $this->AUTH->GetPasswordHash(POST('parolaveche'), $arrUser['username'], $arrUser['salt'])))
    {
        if ($this->AUTH->ResetPassword((int)$arrUser['idx'], POST('parolanoua')) != AUTH_SUCCESS)
            $this->DATA['result'] = 'EROARE: Parola nu a putut fi modificată !';
    }else $this->DATA['result'] = 'EROARE: Parola veche este incorectă !';
}else $this->DATA['result'] = 'EROARE: Utilizatorul nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
