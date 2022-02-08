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

////////////////////////////////////////////////////////////////////////////////

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
            $kMessage->setBody("Bună ziua !\r\n\r\n" .
                "Prin acest mesaj automat dorim să vă informăm că s-a efectuat o cere de " .
                "resetare a parolei contului dumneavoastră de utilizator în platforma BlindHub.ro." .
                "\r\nPentru a reseta parola vă rugăm să accesați adresa de mai jos și" .
                " să urmați pașii respectivi:\r\n\r\n" .
                qurl_l('reseteaza-parola/' . (int)$arrUser['idx'] . '/' . $strNewSalt, array('section' => 'index')) .
                "\r\n\r\nVă mulțumim !\r\nEchipa BlindHub.ro");
            
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

if ($this->DATA['result'] != 'success') http_response_code(400);
