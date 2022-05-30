<?php

try {
	$validation = $this->validator->make($_POST, [
        'amount'      => 'required|numeric',
        'curr'        => 'required|between:3,3',
        'invoice_id'  => 'required|between:1,27',
        'ep_id'       => 'required|between:40,40',
        'action'      => 'required|numeric|digits:1',
        'message'     => 'required',
        'approval' 	  => 'nullable',
        'nonce'   	  => 'required|between:16,64',
        'fp_hash'     => 'required|between:1,256',
    ]);

    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $error = array_values($errors->firstOfAll())[0];
        throw new Exception("EROARE: {$error}!", 400);
    }

    $data =  array (
		'amount'     => addslashes(trim(@$_POST['amount'])),
		'curr'       => addslashes(trim(@$_POST['curr'])),
		'invoice_id' => addslashes(trim(@$_POST['invoice_id'])),
		'ep_id'      => addslashes(trim(@$_POST['ep_id'])),
		'merch_id'   => addslashes(trim(@$_POST['merch_id'])),
		'action'     => addslashes(trim(@$_POST['action'])),
		'message'    => addslashes(trim(@$_POST['message'])),
		'approval'   => addslashes(trim(@$_POST['approval'])),
		'timestamp'  => addslashes(trim(@$_POST['timestamp'])),
		'nonce'      => addslashes(trim(@$_POST['nonce'])),
	);

	$data['fp_hash'] = $this->EpPay->hmac($data);
	$fp_hash = addslashes(trim(@$_POST['fp_hash']));
	if ($data['fp_hash'] !== $fp_hash) {
        throw new Exception("EROARE: semantura invalida!", 400);
	}

    $res = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'card_authorizations', [
        'invoice_id', '=', $validation->getValue('invoice_id')
    ]);
    if ($res === false) {
        throw new Exception($this->DATABASE->getError(), 500);
    }
    if (empty($res)) {
        throw new Exception("EROARE: acesta plata nu exista!", 400);
    }

    $payment = $res[0];
    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'ipn_messages', [
        'card_authorization_idx',
        'action',
        'message',
        'approval',
        'json_dump',
    ], [[
        'card_authorization_idx' => $payment['idx'],
        'action'                 => $validation->getValue('action'),
        'message'                => $validation->getValue('message'),
        'approval'               => $validation->getValue('approval'),
        'json_dump'              => json_encode($_POST),
    ]]);
    if ($res === false) {
        throw new Exception($this->DATABASE->getError(), 500);
    }

    $res = $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'card_authorizations', [
    	'ep_id',
    	'status',
    	'last_ipn_message_idx',
    ], [
        'ep_id'               => $validation->getValue('ep_id'),
        'status' 			  => $validation->getValue('action') ? 'failed' : 'approved',
        'last_ipn_message_idx' => $this->DATABASE->GetLastInsertID(),

    ], [
        'idx', '=', $payment['idx']
    ]);
    if ($res === false) {
        throw new Exception($this->DATABASE->getError(), 500);
    }

    echo "OK";
} catch (\Exception $e) {
    $this->logException($e);
	$errCode = 500;
	if ($e->getCode == 400) {
		$errCode = 400;
	}

 	http_response_code($errCode);
}