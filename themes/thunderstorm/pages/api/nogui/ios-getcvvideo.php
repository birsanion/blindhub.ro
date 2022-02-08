<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey')));

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    if ((int)$arrUser['tiputilizator'] == 1 || (int)$arrUser['tiputilizator'] == 2){
        if (file_exists(qurl_serverfile('media/uploads/nevazator_cv_' . (int)POST('idxnevaz') . '.mp4'))){
            $this->DATA['file'] = qurl_file('media/uploads/nevazator_cv_' . (int)POST('idxnevaz') . '.mp4');
        }elseif (file_exists(qurl_serverfile('media/uploads/nevazator_cv_' . (int)POST('idxnevaz') . '.mov'))){
            $this->DATA['file'] = qurl_file('media/uploads/nevazator_cv_' . (int)POST('idxnevaz') . '.mov');
        }else{
            $this->DATA['file'] = '';
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator sau universitate !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
