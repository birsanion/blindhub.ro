<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['idx'] > 1){
        switch ((int)$arrUser['tiputilizator'] == 0)
        {
            case 0:{        // nevazator
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'angajati', array('idxauth', '=', (int)$arrUser['idx']));
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'angajati_cv', array('idxauth', '=', (int)$arrUser['idx']));
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'angajati_locurisalvate', array('idxauthangajat', '=', (int)$arrUser['idx']));
                
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'cereriinterviu', array('idxauthangajat', '=', (int)$arrUser['idx']));
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate', array('idxauthangajat', '=', (int)$arrUser['idx']));
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'interviuri', array('idxauthangajat', '=', (int)$arrUser['idx']));
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'mesaje', array('idxauthangajat', '=', (int)$arrUser['idx']));
                
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'auth_userpermissions', array('usridx', '=', (int)$arrUser['idx']));
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'auth_users', array('idx', '=', (int)$arrUser['idx']));
            }break;
                
            case 1:{        // angajator
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'angajatori', array('idxauth', '=', (int)$arrUser['idx']));
                
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'cereriinterviu', array('idxauthangajator', '=', (int)$arrUser['idx']));
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'interviuri', array('idxauthangajator', '=', (int)$arrUser['idx']));
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'mesaje', array('idxauthinterlocutor', '=', (int)$arrUser['idx']));
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'locurimunca', array('idxauth', '=', (int)$arrUser['idx']));
                
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'auth_userpermissions', array('usridx', '=', (int)$arrUser['idx']));
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'auth_users', array('idx', '=', (int)$arrUser['idx']));
            }break;
                
            case 2:{        // universitate
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'universitati', array('idxauth', '=', (int)$arrUser['idx']));
            
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate', array('idxauthuniversitate', '=', (int)$arrUser['idx']));
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'interviuri', array('idxauthuniversitate', '=', (int)$arrUser['idx']));
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'mesaje', array('idxauthinterlocutor', '=', (int)$arrUser['idx']));
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'locuriuniversitate', array('idxauth', '=', (int)$arrUser['idx']));
                
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'auth_userpermissions', array('usridx', '=', (int)$arrUser['idx']));
                $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'auth_users', array('idx', '=', (int)$arrUser['idx']));
            }break;
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
