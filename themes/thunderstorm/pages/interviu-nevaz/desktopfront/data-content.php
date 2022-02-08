<?php
////////////////////////////////////////////////////////////////////////////////
// Part of theme Thunderstorm, of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.

call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'jquery-ui-1-10-3-custom-min.js',
        'jq-file-upload/jquery.iframe-transport.js',
        'jq-file-upload/jquery.fileupload.js'
    )
);

call_user_func($this->fncCallback, 'htmlheader', 'structure-styles', MANOP_SET,
    array(
        'jquery-ui-1-10-3-custom.css',
        'jquery-fileupload-ui.css'
    )
);

if (!$this->AUTH->IsAuthenticated()) $this->ROUTE->Redirect(qurl_l(''));

//$this->GLOBAL['infomsg'] = 'info message';
//$this->GLOBAL['errormsg'] = (string)$this->AUTH->GetLastActionResult();

function MySQLDate_to_RomanianDate($strDate)
{
    // 0123-56-89 11:14:17
    return substr($strDate, 8, 2) . '/' . substr($strDate, 5, 2) . '/' .
        substr($strDate, 0, 4) . substr($strDate, 10, 6);
}

$this->DATA['locuri'] = array();

if ((int)$this->AUTH->GetAdvancedDetail('tiputilizator') == 0){
    $arrInterviuri = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'interviuri',
        array(
            array('idxauthangajat', '=', $this->AUTH->GetUserId(), 'AND'),
            array('tstamp', '>=', date('Y-m-d') . ' 00:00:00')
        ),
        'tstamp'
    );
    
    if (is_array($arrInterviuri) && !empty($arrInterviuri)){
        foreach ($arrInterviuri as $arrInterviu){
            $arrAngajat = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori',
                array('idxauth', '=', (int)$arrInterviu['idxauthangajator']));
                
            if (is_array($arrAngajat) && !empty($arrAngajat)){
                $arrAngajat = $arrAngajat[0];
                
                $strNumeSiPrenume = $arrAngajat['companie'];
                $strFirmaProtejata = ($arrAngajat['firmaprotejata'] == 'nu' ? 'nu este firmă protejată' : 'este firmă protejată');
                $strDimensiune = ($arrAngajat['dimensiunefirma'] == 'peste50' ? 'firmă peste 50 de angajați' : 'firmă sub 50 de angajați');
                
                $this->DATA['locuri'][] = array(
                    'nume' => $strNumeSiPrenume,
                    'firmaprotejata' => $strFirmaProtejata,
                    'dimensiune' => $strDimensiune,
                    'dataora' => MySQLDate_to_RomanianDate($arrInterviu['tstamp'])
                );
            }
        }
    }
}else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajator !';
