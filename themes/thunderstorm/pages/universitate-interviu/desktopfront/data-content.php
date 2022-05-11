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
/*
function MySQLDate_to_RomanianDate($strDate)
{
    // 0123-56-89 11:14:17
    return substr($strDate, 8, 2) . '/' . substr($strDate, 5, 2) . '/' .
        substr($strDate, 0, 4) . substr($strDate, 10, 6);
}

$this->DATA['locuri'] = array();

if ((int)$this->AUTH->GetAdvancedDetail('tiputilizator') == 2){
    $arrInterviuri = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'interviuri',
        array(
            array('idxauthuniversitate', '=', $this->AUTH->GetUserId(), 'AND'),
            array('tstamp', '>=', date('Y-m-d') . ' 00:00:00')
        ),
        'tstamp'
    );

    if (is_array($arrInterviuri) && !empty($arrInterviuri)){
        foreach ($arrInterviuri as $arrInterviu){
            $arrAngajat = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati',
                array('idxauth', '=', (int)$arrInterviu['idxauthangajat']));

            if (is_array($arrAngajat) && !empty($arrAngajat)){
                $arrAngajat = $arrAngajat[0];

                $strNumeSiPrenume = $arrAngajat['nume'] . ' ' . $arrAngajat['prenume'];
                $strGradHandicap = $arrAngajat['gradhandicap'];
                $strNevoi = $arrAngajat['nevoispecifice'];

                $this->DATA['locuri'][] = array(
                    'nume' => $strNumeSiPrenume,
                    'gradhandicap' => 'grad de handicap ' . $strGradHandicap,
                    'nevoispecifice' => 'nevoi specifice: ' . $strNevoi,
                    'dataora' => MySQLDate_to_RomanianDate($arrInterviu['tstamp']),
                    'idxauthnevazator' => (int)$arrInterviu['idxauthangajat']
                );
            }
        }
    }
}else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip universitate !';
*/

function MySQLDate_to_RomanianDate($strDate)
{
    // 0123-56-89 11:14:17
    return substr($strDate, 8, 2) . '/' . substr($strDate, 5, 2) . '/' .
        substr($strDate, 0, 4) . substr($strDate, 10, 6);
}

function MySQLDate_to_Seconds($strDate)
{
    // 0123-56-89 11:14:17
    return mktime(
        (int)substr($strDate, 11, 2),
        (int)substr($strDate, 14, 2),
        (int)substr($strDate, 17, 2),
        (int)substr($strDate, 5, 2),
        (int)substr($strDate, 8, 2),
        (int)substr($strDate, 0, 4)
    );
}

try {
    $arrInterviuri = $this->DATABASE->RunQuery(sprintf(
        "SELECT interviuri.*, " .
        "       angajati.nume, " .
        "       angajati.prenume, " .
        "       locuriuniversitate.facultate " .
        "FROM `%s` interviuri " .
        "INNER JOIN `%s` angajati " .
        "ON (interviuri.idxauthangajat = angajati.idxauth) " .
        "LEFT JOIN `%s` locuriuniversitate " .
        "ON (interviuri.idxobject = locuriuniversitate.idx) " .
        "WHERE interviuri.idxauthuniversitate = %d " .
        "AND interviuri.tstamp >= CURDATE() " .
        "ORDER BY interviuri.tstamp",
        SYSCFG_DB_PREFIX . 'interviuri',
        SYSCFG_DB_PREFIX . 'angajati',
        SYSCFG_DB_PREFIX . 'locuriuniversitate',
        $this->AUTH->GetUserId()
    ));
    if ($arrInterviuri === false) {
        throw new Exception("Eroare internă", 500);
    }

    $this->DATA['locuri'] = [];
    foreach ($arrInterviuri as $arrInterviu) {
        $this->DATA['locuri'][] = [
            'idx'                => $arrInterviu['idx'],
            'nume'               => $arrInterviu['nume'] . ' ' . $arrInterviu['prenume'],
            'dataora'            => MySQLDate_to_RomanianDate($arrInterviu['tstamp']),
            'idxauthnevazator'   => (int)$arrInterviu['idxauthangajat'],
            'tstampsecunde'      => MySQLDate_to_Seconds($arrInterviu['tstamp']),
            'existaloc'          => $arrInterviu['idxobject'] ? 1 : 0,
            'facultate'          => $arrInterviu['facultate'],
            'idxlocuniversitate' => (int)$arrInterviu['idxobject'],
            'vonagesessid'       => $arrInterviu['vonagesessid'],
            'token'              => $arrInterviu['vonageinterlocutortoken']
        ];
    }
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}
