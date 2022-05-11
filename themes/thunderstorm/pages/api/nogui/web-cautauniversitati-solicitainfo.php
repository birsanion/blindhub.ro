<?php
/*
$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis')
);

if ((int)$this->AUTH->GetAdvancedDetail('tiputilizator') == 0){
    if ($this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate',
        array(
            array('idxauthangajat', '=', $this->AUTH->GetUserId(), 'AND'),
            array('idxauthuniversitate', '=', (int)POST('idxauthuniversitate'), 'AND'),
            array('idxlocuniversitate', '=', (int)POST('idxloc'))
        )) <= 0)
    {
        $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate',
            'idxauthangajat, idxauthuniversitate, idxlocuniversitate',
            array(array(
                'idxauthangajat' => $this->AUTH->GetUserId(),
                'idxauthuniversitate' => (int)POST('idxauthuniversitate'),
                'idxlocuniversitate' => (int)POST('idxloc')
            ))
        );
    }else{
        $this->DATA['result'] = 'Ați aplicat deja pentru acest interviu în trecut !';
    }
}else $this->DATA['result'] = 'EROARE: acest utilizator nu este de tip angajat !';
*/
$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'idxauthuniversitate' => 'required|numeric',
        'idxloc'              => 'required|numeric',
    ]);

    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $error = array_values($errors->firstOfAll())[0];
        throw new Exception("EROARE: {$error}!", 400);
    }

    $res = $this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate', [
        ['idxauthangajat', '=', $this->AUTH->GetUserId(), 'AND'],
        ['idxauthuniversitate', '=', (int)$validation->getValue('idxauthuniversitate'), 'AND'],
        ['idxlocuniversitate', '=', (int)$validation->getValue('idxloc')]
    ]);
    if ($res === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if ($res) {
        throw new Exception("Ați aplicat deja pentru acest interviu în trecut !", 400);
    }

    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate', [
        'idxauthangajat',
        'idxauthuniversitate',
        'idxlocuniversitate',
    ], [[
        'idxauthangajat'      => $this->AUTH->GetUserId(),
        'idxauthuniversitate' => (int)$validation->getValue('idxauthuniversitate'),
        'idxlocuniversitate'  => (int)$validation->getValue('idxloc')
    ]]);
    if (!$res) {
        throw new Exception("EROARE INTERNA", 500);
    }
});
