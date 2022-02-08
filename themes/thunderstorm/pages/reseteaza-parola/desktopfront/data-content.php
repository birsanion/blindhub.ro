<?php

$this->DATA['valid'] = false;

if (PARAMS() > 0){
    $arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users', array('idx', '=', (int)PARAM(1)));
    
    if (is_array($arrUser) && !empty($arrUser)){
        $arrUser = $arrUser[0];
        
        if ($arrUser['recoverhash'] == PARAM(2)){
            $this->DATA['valid'] = true;
            $this->DATA['email'] = $arrUser['username'];
        }
    }
}

