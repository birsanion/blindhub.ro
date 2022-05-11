<?php
/*
$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'html' => ''
);


if ((int)$this->AUTH->GetAdvancedDetail('tiputilizator') == 0){
    $arrUniversitati = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati',
        array(
            array('nume', '=', POST('hCombo_Universitate'), 'AND'),
            array('oras', '=', POST('hCombo_Oras'))
        )
    );

    if (is_array($arrUniversitati) && !empty($arrUniversitati)){
        foreach ($arrUniversitati as $arrUniversitate){
            $arrLocuri = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'locuriuniversitate',
                array(
                    array('domeniu', '=', POST('hCombo_Domeniu'), 'AND'),
                    array('idxauth', '=', (int)$arrUniversitate['idxauth'])
                )
            );

            if (is_array($arrLocuri) && !empty($arrLocuri)){
                foreach ($arrLocuri as $arrLoc){
                    $this->DATA['html'] .= '<tr>
                        <td style="width: 50%; padding: 5px;"><h1>' . htmlspecialchars($arrUniversitate['nume']) . '</h1></td>
                        <td style="text-align: left;">
                            <ol>
                                <li>grad de accesibilizare: '. $arrUniversitate['gradacces'] .'</li>
                                <li>grad de echipare cu tehnologie asistivă: '. $arrUniversitate['gradechipare'] .'</li>
                                <li>studenți cu dizabilități: '. $arrUniversitate['studdiz'] .'</li>
                                <li>centru de sprijin: '. $arrUniversitate['studcentru'] .'</li>
                                <li>camere în căminele studențești adaptate: '. $arrUniversitate['camerecamine'] .'</li>
                                <li>persoană sau un birou dedicat: '. $arrUniversitate['persdedic'] .'</li>
                            </ol>
                            <br />
                            <a href="#" class="block reference imgtextlink solicitinfo" data-idxuniv="' .
                                (int)$arrUniversitate['idxauth'] . '" data-idxloc="' .
                                (int)$arrLoc['idx'] . '">
                                <img src="' . qurl_f('images/icon_next_normal.png', 'index') . '" class="normal" />
                                <img src="' . qurl_f('images/icon_next_mouseover.png', 'index') . '" class="over" />
                                <span>Solicit informații</span>
                            </a>
                        </td>
                    </tr>
                    <tr><td colspan="2"><hr /></td></tr>';


                    $this->DATA['rezultate'][] = array(
                        'numeuniversitate' => $arrUniversitate['nume'],
                        'idxauth' => (int)$arrUniversitate['idxauth'],
                        'facultate' => $arrLoc['facultate'],
                        'nrlocuri' => $arrLoc['numarlocuri'],
                        'idxloc' => (int)$arrLoc['idx']
                    );
                }
            }
        }
    }

    if (strlen($this->DATA['html']) <= 0)
        $this->DATA['html'] = '<tr><td colspan="2">Nu există rezultate momentan !</td></tr>';
}else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';
*/

$this->handleAPIRequest(function() {
    $this->DATA['rezultate'] = [];

    $validation = $this->validator->make($_POST, [
        'idxauthuniversitate'      => 'required',
        'idx_domeniu_universitate' => 'required|numeric',
    ]);

    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $error = array_values($errors->firstOfAll())[0];
        throw new Exception("EROARE: {$error}!", 400);
    }

    $arrUniversitate = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati', [
        ['idxauth', '=', $validation->getValue('idxauthuniversitate')],
    ]);
    if ($arrUniversitate === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrUniversitate)) {
        throw new Exception("Eroare: aceasta universitate nu exista", 400);
    }

    $arrUniversitate = $arrUniversitate[0];

    $arrLocuri = $this->DATABASE->RunQuery(sprintf(
        "SELECT locuriuniversitate.*, orase.nume AS oras " .
        "FROM `%s` locuriuniversitate " .
        "INNER JOIN `%s` orase " .
        "ON (locuriuniversitate.idx_oras = orase.idx) " .
        "WHERE locuriuniversitate.idx_domeniu_universitate = %d " .
        "AND locuriuniversitate.idxauth = %d ",
        SYSCFG_DB_PREFIX . 'locuriuniversitate',
        SYSCFG_DB_PREFIX . 'orase',
        $validation->getValue('idx_domeniu_universitate'),
        (int)$arrUniversitate['idxauth']
    ));
    if ($arrLocuri === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $this->DATA['rezultate'] = [];
    foreach ($arrLocuri as $arrLoc) {
        $this->DATA['rezultate'][] = [
            'numeuniversitate'         => $arrUniversitate['nume'],
            'idxauth'                  => (int)$arrLoc['idxauth'],
            'facultate'                => $arrLoc['facultate'],
            'nrlocuri'                 => (int)$arrLoc['numarlocuri'],
            'idxloc'                   => (int)$arrLoc['idx'],
            'oras'                     => $arrLoc['oras'],
            'idx_domeniu_universitate' => (int)$arrLoc['idx_domeniu_universitate'],
        ];
    }
});
