<?php
////////////////////////////////////////////////////////////////////////////////
// Part of theme Thunderstorm, of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.

call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'loadingoverlay.min.js',
    )
);

try {
    $validation = $this->validator->make($_POST, [
        'fname'         => 'required',
        'lname'         => 'required',
        'address'       => 'required',
        'city'          => 'required',
        'country'       => 'required',
        'zip'           => 'required',
        'phone'         => 'required',
        'email'         => 'required|email',
        'amount_fixed'  => 'numeric',
        'amount'        => 'required_without:amount_fixed|numeric',

    ]);

    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $error = array_values($errors->firstOfAll())[0];
        throw new Exception("EROARE: {$error}!", 400);
    }

    $currency = 'RON';
    $desc = 'Donation';
    $recurentFreq = '28';
    $recurentExp = strtotime("+1 year");
    $amount = $validation->getValue('amount');
    if (!$amount) {
        $amount = $validation->getValue('amount_fixed');
    }
    $data = $this->EpPay->initBaseTransactionPayload($amount, $currency, $desc, $recurentFreq, date('Ymd', $recurentExp));
    $data['fname'] = $validation->getValue('fname');
    $data['lname'] = $validation->getValue('lname');
    $data['email'] = $validation->getValue('email');
    $data['phone'] = $validation->getValue('phone');
    $data['address'] = $validation->getValue('address');
    $data['city'] = $validation->getValue('city');
    $data['country'] = $validation->getValue('country');
    $data['zip'] = $validation->getValue('zip');
    $data['ExtraData'] = [
        'silenturl'         => qurl_s('api/ipn-euplatesc'),
        'successurl'        => qurl_l('payment-confirmation'),
        'failedurl'         => qurl_l('payment-confirmation'),
        'backtosite'        => qurl_l(''),
    ];

    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'card_authorizations', [
        'payment_processor',
        'invoice_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'zip',
        'amount',
        'currency',
        'order_desc',
        'recurent_exp',
        'recurent_freq',
    ], [[
        'payment_processor' => 'euplatesc',
        'invoice_id'        => $data['invoice_id'],
        'first_name'        => $data['fname'],
        'last_name'         => $data['lname'],
        'email'             => $data['email'],
        'phone'             => $data['phone'],
        'address'           => $data['address'],
        'city'              => $data['city'],
        'country'           => $data['country'],
        'zip'               => $data['zip'],
        'amount'            => $data['amount'],
        'currency'          => $data['curr'],
        'order_desc'        => $data['order_desc'],
        'recurent_exp'      => date('Y-m-d', $recurentExp),
        'recurent_freq'     => $recurentFreq,
    ]]);

    if (!$res) {
        throw new Exception($this->DATABASE->GetError(), 500);
    }

    $this->DATA = $data;
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}
