<?php
////////////////////////////////////////////////////////////////////////////////
// Part of theme Thunderstorm, of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.

call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'loadingoverlay.min.js',
    )
);

function euplatesc_mac($data, $key) {
    $str = NULL;
    foreach($data as $d){
        if($d === NULL || strlen($d) == 0){
            $str .= '-';
        }else{
            $str .= strlen($d) . $d;
        }
    }

    return hash_hmac('MD5',$str, pack('H*', $key));
}

try {
    $validation = $this->validator->make($_POST, [
        'fname'   => 'required',
        'lname'   => 'required',
        'address' => 'required',
        'city'    => 'required',
        'country' => 'required',
        'zip'     => 'required',
        'phone'   => 'required',
        'email'   => 'required|email',
        'amount'  => 'required|numeric',
    ]);

    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $error = array_values($errors->firstOfAll())[0];
        throw new Exception("EROARE: {$error}!", 400);
    }

    $data = array(
        'amount'     => number_format($validation->getValue('amount'), 2, '.', ''),
        'curr'       => 'RON',
        'invoice_id' => uniqid("", true),
        'order_desc' => 'Donatie',
        'merch_id'   => $_ENV['EUPLATESC_MERCHANT_ID'],
        'timestamp'  => date('YmdHis'),
        'nonce'      => md5(mt_rand().time()),
    );

    $data['fp_hash'] = strtoupper(euplatesc_mac($data, $_ENV['EUPLATESC_KEY']));
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

    $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'payments', [
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
    ]]);

    if (!$res) {
        throw new Exception($this->DATABASE->GetError(), 500);
    }

    $this->DATA = $data;
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = $e->getMessage();
}

