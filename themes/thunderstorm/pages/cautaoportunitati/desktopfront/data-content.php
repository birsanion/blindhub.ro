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
    
    $strAdvQuery = "SELECT * FROM `" . SYSCFG_DB_PREFIX . 'angajatori` WHERE (';
    
    $arrDomenii = explode('|', $arrCVNevazator['domenii']);
    $nDomenii = count($arrDomenii);
    
    for ($i=0; $i < $nDomenii; $i++)
        $arrDomenii[$i] = '`domenii` LIKE \'%' . $this->DATABASE->CleanString($arrDomenii[$i]) . '%\'';
        
    $strAdvQuery .= implode(' OR ', $arrDomenii);
    $strAdvQuery .= ') AND `orase` LIKE \'%' . $this->DATABASE->CleanString($arrCVNevazator['oras']) . '%\'';
    
    $arrRezultate = $this->DATABASE->RunQuery($strAdvQuery);
    
    if (is_array($arrRezultate) && count($arrRezultate) > 0){
        $this->DATA['nrlocuri'] = count($arrRezultate);
        $this->DATA['locuri'] = array();
        
        foreach ($arrRezultate as $arrRezultat){
            $this->DATA['locuri'][] = array(
                'nume' => $arrRezultat['companie'],
                'firmaprotejata' => ($arrRezultat['firmaprotejata'] == 'da' ?
                    'este firmă protejată' : 'nu este firmă protejată'),
                
                'dimensiunefirma' => ($arrRezultat['dimensiunefirma'] == 'peste50' ?
                    'are peste 50 de angajați' : 'are sub 50 de angajați'),
                
                'tipslujba' => ($arrRezultat['tipslujba'] == 'fulltime' ? 'Full-time' : 'Part-time'),
                
                'idxangajator' => (int)$arrRezultat['idx']
            );
        }
    }else{
        $this->DATA['nrlocuri'] = 0;
        $this->DATA['locuri'] = array();
    }
}else $this->GLOBAL['errormsg'] = 'EROARE: nu aveți completat CV-ul !<br />' .
    'Întâi de toate trebuie să vă completați CV-ul fiindcă rezultatele depind de criteriile selectate de dumneavoastră.';
