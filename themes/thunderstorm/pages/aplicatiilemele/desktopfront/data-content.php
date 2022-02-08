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

function GetTimeDifferenceFromNow($strPastDate)
{
    // 0123-56-89
    $nPast = mktime(0, 0, 0, (int)substr($strPastDate, 5, 2),
        (int)substr($strPastDate, 8, 2), (int)substr($strPastDate, 0, 4));
    
    return floor((time() - $nPast) / 86400);
}

if ((int)$this->AUTH->GetAdvancedDetail('tiputilizator') == 0){
    $arrLocuriSalvate = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati_locurisalvate',
        array('idxauthangajat', '=', $this->AUTH->GetUserId()));
    
    if (is_array($arrLocuriSalvate) && count($arrLocuriSalvate) > 0){
        $strAdvQuery = "SELECT * FROM `" . SYSCFG_DB_PREFIX . 'locurimunca` WHERE `idx` IN (';
        $arrTemp = array();
        
        foreach ($arrLocuriSalvate as $arrLoc)
            $arrTemp[] = (int)$arrLoc['idxlocmunca'];
        
        $strAdvQuery .= implode(',', $arrTemp) . ')';
        
        $arrRezultate = $this->DATABASE->RunQuery($strAdvQuery);
        
        if (is_array($arrRezultate) && count($arrRezultate) > 0){
            $this->DATA['nrlocuri'] = count($arrRezultate);
            $this->DATA['locuri'] = array();
            
            foreach ($arrRezultate as $arrRezultat){
                $arrAngajator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori',
                    array('idxauth', '=', (int)$arrRezultat['idxauth']));
                
                if (is_array($arrAngajator) && count($arrAngajator) > 0){
                    $arrAngajator = $arrAngajator[0];
                    $nTimeDiff = GetTimeDifferenceFromNow($arrRezultat['datapostare']);
                    
                    $this->DATA['locuri'][] = array(
                        'nume' => '<strong>' . htmlspecialchars($arrAngajator['companie']) .
                            '</strong> (' . $arrRezultat['titlu'] . ')',
                        'firmaprotejata' => ($arrAngajator['firmaprotejata'] == 'da' ?
                            'este firmă protejată' : 'nu este firmă protejată'),
                        
                        'dimensiunefirma' => ($arrAngajator['dimensiunefirma'] == 'peste50' ?
                            'are peste 50 de angajați' : 'are sub 50 de angajați'),
                        
                        'vechimeanunt' => 'anunț postat ' .
                            ($nTimeDiff <= 0 ? 'astăzi' :
                                ($nTimeDiff <= 1 ? ' acum o zi' :
                                    ($nTimeDiff <= 19 ? 'acum ' . $nTimeDiff . ' zile' :
                                        'acum ' . $nTimeDiff . ' de zile'))),
                        
                        'idxlocmunca' => (int)$arrRezultat['idx'],
                        'idxauth' => (int)$arrAngajator['idxauth']
                    );
                }else $this->DATA['nrlocuri']--;
            }
        }else{
            $this->DATA['nrlocuri'] = 0;
            $this->DATA['locuri'] = array();
        }
    }else{
        $this->DATA['nrlocuri'] = 0;
        $this->DATA['locuri'] = array();
    }
}else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';

