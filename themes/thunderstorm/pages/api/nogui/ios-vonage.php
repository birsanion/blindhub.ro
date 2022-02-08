<?php

require 'system/thirdparty/vendor/autoload.php';
use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;

define('VONAGE_API_KEY', '47381251');
define('VONAGE_API_SECRET', 'b906f5b041ad14c221930bd5230305abee699c5e');

$kOpentok = new OpenTok(VONAGE_API_KEY, VONAGE_API_SECRET);

switch (POST('command'))
{
    case 'newsession':{
        $kSession = $kOpentok->createSession();
        $strSessionId = $kSession->getSessionId();
        
        $this->DATA['sessionid'] = $strSessionId;
    }break;
        
    case 'newtoken':{
        $strToken = $kOpentok->generateToken(POST('sessionid'));
        $this->DATA['token'] = $strToken;
    }break;
}
