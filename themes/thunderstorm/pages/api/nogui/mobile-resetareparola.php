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
        'email' => 'required|email',
    ]);
    $validation->validate();
    if ($validation->fails()) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users', [
        'username', '=', $validation->getValue('email')
    ]);
    if ($arrUser === false) {
        throw new Exception("Eroare internă", 500);
    }

    if (empty($arrUser)) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrUser = $arrUser[0];
    $strNewSalt = $this->AUTH->GetNewSalt();

    $res = $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX.'auth_users', 'recoverhash', [
        'recoverhash' => $strNewSalt
    ], [
        'idx', '=', (int)$arrUser['idx']
    ]);
    if (!$res) {
        throw new Exception("Eroare internă", 500);
    }

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
        $body = $this->LANG("Bună ziua!") . "\r\n\r\n";
        $body .= $this->LANG("Prin acest mesaj automat dorim să vă informăm că s-a efectuat o cere de resetare a parolei contului dumneavoastră de utilizator în platforma BlindHub.ro.") . "\r\n";
        $body .= sprintf($this->LANG("Pentru a reseta parola vă rugăm să accesați adresa de mai jos și să urmați pașii respectivi:"), qurl_l('reseteaza-parola/' . (int)$arrUser['idx'] . '/' . $strNewSalt, array('section' => 'index'))) . "\r\n\r\n";
        $body .= "\r\n\r\n" . $this->LANG("Vă mulțumim!");
        $body .= "\r\n" . $this->LANG("Echipa BlindHub.ro");
        $kMessage->setBody($body);
        $arrFails = array();

        // Send the message
        $kMailer->send($kMessage, $arrFails);
    } catch (Exception $ex) {
        throw new Exception("Eroare internă", 500);
    }
});
