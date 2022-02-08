<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 0){
        $bResult = false;
        
        $bResult = $this->DATABASE->RunQuickDelete(SYSCFG_DB_PREFIX . 'angajati_cv',
            array('idxauth', '=', (int)$arrUser['idx'])
        );
        
        if (file_exists(qurl_serverfile('media/uploads/nevazator_cv_'. (int)$arrUser['idx'] .'.mp4')))
            unlink(qurl_serverfile('media/uploads/nevazator_cv_'. (int)$arrUser['idx'] .'.mp4'));
        
        if (!$bResult){
            $this->DATA['result'] = 'EROARE: baza de date nu funcționează momentan !';
            $this->DATA['debug'] = $this->DATABASE->GetError() . ' - ' . $this->DATABASE->GetLastQuery();
            //$this->DATA['debug2'] = $_POST;
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
