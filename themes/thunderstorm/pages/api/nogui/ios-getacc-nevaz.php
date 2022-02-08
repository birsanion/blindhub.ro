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
        $arrDetails = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati',
            array('idxauth', '=', (int)$arrUser['idx']));

        if (is_array($arrDetails) && !empty($arrDetails)){
            $arrDetails = $arrDetails[0];

            $this->DATA['nume'] = $arrDetails['nume'];
            $this->DATA['prenume'] = $arrDetails['prenume'];
            $this->DATA['gradhandicap'] = $arrDetails['gradhandicap'];
            $this->DATA['nevoispecifice'] = $arrDetails['nevoispecifice'];
            $this->DATA['cv'] = (file_exists(qurl_serverfile('media/uploads/nevazator_cv_' . (int)$arrUser['idx'] . '.mp4')) ?
                qurl_file('media/uploads/nevazator_cv_' . (int)$arrUser['idx'] . '.mp4') : '');
            $this->DATA['email'] = $arrUser['username'];
        }else{
            $this->DATA['nume'] = '';
            $this->DATA['prenume'] = '';
            $this->DATA['gradhandicap'] = '';
            $this->DATA['nevoispecifice'] = '';
            $this->DATA['cv'] = '';
        }
    }else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu există !';

if ($this->DATA['result'] != 'success') http_response_code(400);
