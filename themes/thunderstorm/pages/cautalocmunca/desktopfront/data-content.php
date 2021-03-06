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

function GetTimeDifferenceFromNow($strPastDate)
{
    // 0123-56-89
    $nPast = mktime(0, 0, 0, (int)substr($strPastDate, 5, 2),
        (int)substr($strPastDate, 8, 2), (int)substr($strPastDate, 0, 4));
    
    return floor((time() - $nPast) / 86400);
}

//$this->GLOBAL['infomsg'] = 'info message';
//$this->GLOBAL['errormsg'] = (string)$this->AUTH->GetLastActionResult();

$arrCVNevazator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati_cv',
    array('idxauth', '=', $this->AUTH->GetUserId()));

if (is_array($arrCVNevazator) && count($arrCVNevazator) > 0){
    $arrCVNevazator = $arrCVNevazator[0];
    
    $strAdvQuery = "SELECT * FROM `" . SYSCFG_DB_PREFIX . 'locurimunca` WHERE (';
    
    $arrOrase = explode('|', $arrCVNevazator['domenii']);
    $nOrase = count($arrOrase);
    
    for ($i=0; $i < $nOrase; $i++)
        $arrOrase[$i] = '`domeniu` = \'' . $this->DATABASE->CleanString($arrOrase[$i]) . '\'';
        
    $strAdvQuery .= implode(' OR ', $arrOrase);
    $strAdvQuery .= ') AND `oras` = \'' . $arrCVNevazator['oras'] . '\' AND `expirare` >= \'' .
        date('Y-m-d') . '\'';
        
    //$this->DATA['debug'] = $strAdvQuery;
    
    $arrRezultate = $this->DATABASE->RunQuery($strAdvQuery);
    
    if (is_array($arrRezultate) && count($arrRezultate) > 0){
        $this->DATA['locuri'] = array();
        
        foreach ($arrRezultate as $arrRezultat){
            $arrAngajator = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori',
                array('idxauth', '=', (int)$arrRezultat['idxauth']));
            
            if (is_array($arrAngajator) && count($arrAngajator) > 0){
                $arrAngajator = $arrAngajator[0];
                $nTimeDiff = GetTimeDifferenceFromNow($arrRezultat['datapostare']);
                
                $this->DATA['locuri'][] = array(
                    'nume' => $arrAngajator['companie'],
                    'firmaprotejata' => ($arrAngajator['firmaprotejata'] == 'da' ?
                        'este firm?? protejat??' : 'nu este firm?? protejat??'),
                    
                    'dimensiunefirma' => ($arrAngajator['dimensiunefirma'] == 'peste50' ?
                        'are peste 50 de angaja??i' : 'are sub 50 de angaja??i'),
                    
                    'vechimeanunt' => 'anun?? postat ' .
                        ($nTimeDiff <= 0 ? 'ast??zi' :
                            ($nTimeDiff <= 1 ? ' acum o zi' :
                                ($nTimeDiff <= 19 ? 'acum ' . $nTimeDiff . ' zile' :
                                    'acum ' . $nTimeDiff . ' de zile'))),
                    
                    'idxlocmunca' => (int)$arrRezultat['idx'],
                    'idxauth' => (int)$arrAngajator['idxauth']
                );
            }else $this->DATA['nrlocuri']--;
        }
    }else{
        $this->DATA['locuri'] = array();
    }
}else $this->GLOBAL['errormsg'] = 'EROARE: nu ave??i completat CV-ul !<br />' .
    '??nt??i de toate trebuie s?? v?? completa??i CV-ul fiindc?? rezultatele depind de criteriile selectate de dumneavoastr??.';
