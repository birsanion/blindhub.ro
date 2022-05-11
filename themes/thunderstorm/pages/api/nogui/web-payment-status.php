<?php

$this->handleAPIRequest(function() {
	$validation = $this->validator->make($_POST, [
        'invoice_id'  => 'required|between:1,27',
        'ep_id'       => 'required|between:40,40',
    ]);

    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $error = array_values($errors->firstOfAll())[0];
        throw new Exception("EROARE: {$error}!", 400);
    }


    $res = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'payments', [
        ['invoice_id', '=', $validation->getValue('invoice_id'), 'AND'],
        ['ep_id', '=', $validation->getValue('ep_id')],
    ]);
    if ($res === false) {
        throw new Exception($this->DATABASE->getError(), 500);
    }
    if (empty($res)) {
        throw new Exception("EROARE: acesta plata nu exista!", 400);
    }

    $payment = $res[0];
    $this->DATA['status'] = $payment['status'];
    $this->DATA['amount'] = $payment['amount'];
    $this->DATA['message'] = '';
    if ($payment['last_ipn_message_idx']) {
        $res = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'ipn_messages', [
            ['idx', '=', $payment['last_ipn_message_idx']],
        ]);
        if ($res === false) {
            throw new Exception($this->DATABASE->getError(), 500);
        }
        if (empty($res)) {
            throw new Exception("EROARE: acesta plata nu exista!", 400);
        }
        $this->DATA['message'] = $res[0]['message'];
    }
});
