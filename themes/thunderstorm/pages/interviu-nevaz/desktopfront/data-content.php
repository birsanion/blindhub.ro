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
*/

function MySQLDate_to_RomanianDate($strDate)
{
    // 0123-56-89 12:45:78
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
        "       angajatori.companie, " .
        "       locurimunca.idx AS idxlocmunca, " .
        "       locurimunca.titlu AS titlulocmunca, " .
        "       locurimunca.idx_oras AS idx_oras_locmunca, " .
        "       locurimunca.idx_domeniu_cv AS idx_domeniu_locmunca, " .
        "       universitati.nume AS numeuniversitate, " .
        "       locuriuniversitate.idx AS idxlocuniversitte, " .
        "       locuriuniversitate.facultate, " .
        "       locuriuniversitate.idx_oras AS idx_oras_locuniversitate, " .
        "       locuriuniversitate.idx_domeniu_universitate AS idx_domeniu_locuniversitate " .
        "FROM `%s` interviuri " .
        "LEFT JOIN `%s` angajatori " .
        "ON (interviuri.idxauthangajator = angajatori.idxauth) " .
        "LEFT JOIN `%s` locurimunca " .
        "ON (interviuri.idxobject = locurimunca.idx AND interviuri.idxauthangajator = angajatori.idxauth) " .
        "LEFT JOIN `%s` universitati " .
        "ON (interviuri.idxauthuniversitate = universitati.idxauth) " .
        "LEFT JOIN `%s` locuriuniversitate " .
        "ON (interviuri.idxobject = locuriuniversitate.idx AND locuriuniversitate.idxauth = universitati.idxauth) " .
        "WHERE interviuri.idxauthangajat = %d " .
        "AND interviuri.tstamp >= CURDATE() " .
        "ORDER BY interviuri.tstamp",
        SYSCFG_DB_PREFIX . 'interviuri',
        SYSCFG_DB_PREFIX . 'angajatori',
        SYSCFG_DB_PREFIX . 'locurimunca',
        SYSCFG_DB_PREFIX . 'universitati',
        SYSCFG_DB_PREFIX . 'locuriuniversitate',
        $this->AUTH->GetUserId()
    ));
    if ($arrInterviuri === false) {
        throw new Exception($this->DATABASE->GetError(), 500);
    }

    $this->DATA['locuri'] = [];
    foreach ($arrInterviuri as $arrInterviu) {
        $this->DATA['locuri'][] = [
            'nume'                       => $arrInterviu['idxauthangajator'] ? $arrInterviu['companie'] : $arrInterviu['numeuniversitate'],
            'dataora'                    => MySQLDate_to_RomanianDate($arrInterviu['tstamp']),
            'idxauthnevazator'           => (int)$arrInterviu['idxauthangajat'],
            'tstampsecunde'              => MySQLDate_to_Seconds($arrInterviu['tstamp']),
            'existalocmunca'             => $arrInterviu['idxlocmunca'] ? 1 : 0,
            'existalocuniversitate'      => $arrInterviu['idxlocuniversitte'] ? 1 : 0,
            'titlulocmunca'              => (string)$arrInterviu['titlulocmunca'],
            'idx_oras_locmunca'          => (int)$arrInterviu['idx_oras_locmunca'],
            'idx_domeniu_locmunca'       => (int)$arrInterviu['idx_domeniu_locmunca'],
            'facultate'                  => (string)$arrInterviu['facultate'],
            'idx_oras_locuniversitate'   => (int)$arrInterviu['idx_oras_locuniversitate'],
            'idx_domeniu_locuniversitate'=> (int)$arrInterviu['idx_domeniu_locuniversitate'],
            'vonagesessid'               => $arrInterviu['vonagesessid'],
            'token'                      => $arrInterviu['vonagenevaztoken']
        ];
    }
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}

