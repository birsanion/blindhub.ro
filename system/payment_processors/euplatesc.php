<?php

class EpPay
{
    private $key;
    private $merchantID;

    const TRANSACTION_TYPE_BASE = 'Base';
    const TRANSACTION_TYPE_RECURENT = 'Base';

    function __construct() {
        $this->key = $_ENV['EUPLATESC_KEY'];
        $this->merchantID = $_ENV['EUPLATESC_MERCHANT_ID'];
    }

    public function hmac($data) {
        $str = NULL;
        foreach ($data as $d) {
            if ($d === NULL || strlen($d) == 0) {
                $str .= '-';
            } else {
                $str .= strlen($d) . $d;
            }
        }

        return hash_hmac('MD5',$str, pack('H*', $this->key));
    }

    public function initTransactionPayload($amount, $currency, $desc, $recurentFreq = null, $recurentExp = null) {
        $data = [
            'amount'     => number_format($amount, 2, '.', ''),
            'curr'       => $currency,
            'invoice_id' => uniqid("", true),
            'order_desc' => $desc,
            'merch_id'   => $this->merchantID,
            'timestamp'  => date('YmdHis'),
            'nonce'      => md5(mt_rand().time()),
        ];
        if ($recurentFreq) {
            $data['recurent_freq'] = $recurentFreq;
        }
        if ($recurentExp) {
            $data['recurent_exp'] = $recurentExp;
        }

        $data['fp_hash'] = strtoupper($this->hmac($data, $this->key));
        return $data;
    }

    public function initBaseTransactionPayload($amount, $currency, $desc, $recurentFreq, $recurentExp) {
        $data = $this->initTransactionPayload($amount, $currency, $desc, $recurentFreq, $recurentExp);
        $data['recurent'] = self::TRANSACTION_TYPE_BASE;
        return $data;
    }
}