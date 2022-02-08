<?php

$this->LOG->Log('unauthorized-nogui',
    'COOKIES: ' . print_r($_COOKIE, true) . "\r\n" .
    'SESSION: ' . print_r($_SESSION, true) . "\r\n" .
    'SERVER: ' . print_r($_SERVER, true) . "\r\n" .
    'POST: ' . print_r($_POST, true) . "\r\n" .
    'GET: ' . print_r($_GET, true) . "\r\n"
);
