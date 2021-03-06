<?php

require_once 'system/thirdparty/swiftmailer-master/lib/swift_required.php';

spl_autoload_register(function ($class)
{
    $load_deps = array(
        'Egulias\\EmailValidator\\' => 'system/thirdparty/EmailValidator-master/EmailValidator/',
        'Doctrine\\Common\\Lexer\\' => 'system/thirdparty/lexer-master/lib/Doctrine/Common/Lexer/',
    );

    foreach ($load_deps as $prefix => $base_dir){
        // does the class use the namespace prefix?
        $len = strlen($prefix);

        if (strncmp($prefix, $class, $len) == 0){

            // get the relative class name
            $relative_class = substr($class, $len);

            // replace the namespace prefix with the base directory, replace namespace
            // separators with directory separators in the relative class name, append
            // with .php
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

            // if the file exists, require it
            if (file_exists($file)) require $file;
        }
    }
});

$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'hEditEmail'  => 'required|email',
    ]);

    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $error = array_values($errors->firstOfAll())[0];
        throw new Exception("EROARE: {$error}!", 400);
    }

    $arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users', [
        'username', '=', $validation->getValue('hEditEmail')
    ]);
    if ($arrUser === false) {
        throw new Exception("EROARE INTERNA", 500);
    }
    if (empty($arrUser)) {
        $this->DATA['result'] = 'success';
        return;
    }

    $arrUser = $arrUser[0];
    $strNewSalt = $this->AUTH->GetNewSalt();

    $res = $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'auth_users', 'recoverhash', [
        'recoverhash' => $strNewSalt
    ], [
        'idx', '=', (int)$arrUser['idx']]
    );
    if ($res === false) {
        throw new Exception("EROARE INTERNA", 500);
    }

    $kTransport = new Swift_SmtpTransport('mail.blindhub.ro', 465, 'ssl');
    $kTransport->setUsername('noreply@blindhub.ro');
    $kTransport->setPassword('P6tfY*gq32zV');

    // Create the Mailer using your created Transport
    $kMailer = new Swift_Mailer($kTransport);

    // Create a message
    $kMessage = new Swift_Message('[AUTOMAT] Resetare parola cont blindhub.ro');
    $kMessage->setFrom(array('noreply@blindhub.ro' => 'BLINDHUB.RO'));
    $kMessage->setTo(array($arrUser['username'] => $arrUser['username']));
    $kMessage->setBody("Bun?? ziua !\r\n\r\n" .
        "Prin acest mesaj automat dorim s?? v?? inform??m c?? s-a efectuat o cere de " .
        "resetare a parolei contului dumneavoastr?? de utilizator ??n platforma BlindHub.ro." .
        "\r\nPentru a reseta parola v?? rug??m s?? accesa??i adresa de mai jos ??i" .
        " s?? urma??i pa??ii respectivi:\r\n\r\n" .
        qurl_l('reseteaza-parola/' . (int)$arrUser['idx'] . '/' . $strNewSalt, array('section' => 'index')) .
        "\r\n\r\nV?? mul??umim !\r\nEchipa BlindHub.ro");

    $arrFails = [];

    // Send the message
    $res = $kMailer->send($kMessage, $arrFails);
    $this->DATA['result'] = $res;
});

////////////////////////////////////////////////////////////////////////////////
/*
$strEmail = POST('email');

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users', array('username', '=', $strEmail));

$strResult = '';

if (is_array($arrUser) && !empty($arrUser)){
    $arrUser = $arrUser[0];

    // set hash
    $strNewSalt = $this->AUTH->GetNewSalt();

    if ($this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX.'auth_users',
        'recoverhash',
        array('recoverhash' => $strNewSalt),
        array('idx', '=', (int)$arrUser['idx'])))
    {
        $strResult = 'success';

        try {
            // Create the Transport
            $kTransport = new Swift_SmtpTransport('mail.blindhub.ro', 465, 'ssl');
            $kTransport->setUsername('noreply@blindhub.ro');
            $kTransport->setPassword('P6tfY*gq32zV');

            // Create the Mailer using your created Transport
            $kMailer = new Swift_Mailer($kTransport);

            // Create a message
            $kMessage = new Swift_Message('[AUTOMAT] Resetare parola cont blindhub.ro');
            $kMessage->setFrom(array('noreply@blindhub.ro' => 'BLINDHUB.RO'));
            $kMessage->setTo(array($arrUser['username'] => $arrUser['username']));
            $kMessage->setBody("Bun?? ziua !\r\n\r\n" .
                "Prin acest mesaj automat dorim s?? v?? inform??m c?? s-a efectuat o cere de " .
                "resetare a parolei contului dumneavoastr?? de utilizator ??n platforma BlindHub.ro." .
                "\r\nPentru a reseta parola v?? rug??m s?? accesa??i adresa de mai jos ??i" .
                " s?? urma??i pa??ii respectivi:\r\n\r\n" .
                qurl_l('reseteaza-parola/' . (int)$arrUser['idx'] . '/' . $strNewSalt, array('section' => 'index')) .
                "\r\n\r\nV?? mul??umim !\r\nEchipa BlindHub.ro");

            $arrFails = array();

            // Send the message
            $kMailer->send($kMessage, $arrFails);
        }catch (Exception $ex){
            $strResult = 'Ne pare rau, a intervenit o problema. Momentan datele dvs. nu pot fi procesate.<br />' . $ex->getMessage();
        }
    }else $strResult = 'EROARE: Momentan nu se poate procesa resetarea contului (1).';
}else $strResult = 'EROARE: Momentan nu se poate procesa resetarea contului (3).';


$this->DATA = array(
    'result' => $strResult,
    'tstamp' => date('YmdHis')
);
*/
// POST('email')