<?php
////////////////////////////////////////////////////////////////////////////////
// Part of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.
// -- For history see the end of this file.

////////////////////////////////////////////////////////////////////////////////
// This file contains various static configurations that are valid across
// the entire application.

if (!defined('SYSCAP_SYSCONFIG')){
// this tells the whole framework that this system file has been included
define('SYSCAP_SYSCONFIG', true);

//// DATABASE ==================================================================
// database type; accepted values: mysql
define('SYSCFG_DB_TYPE',			'mysqli');

// address of database server
define('SYSCFG_DB_ADDRESS',			'localhost');

// name of database
define('SYSCFG_DB_NAME',			'blindhub');

// table prefix; useful when having more websites in one database
define('SYSCFG_DB_PREFIX',		    'qwf_');

// username
define('SYSCFG_DB_USER',			'blindhub');

// password
define('SYSCFG_DB_PASS',			'BLINDhub2021@');

//// OTHER SYSTEM SETTINGS =====================================================
// default timezone, the 'date_default_timezone_set()' function will be called
// if you wish to disable this, set it to blank string ('')
define('SYSCFG_DEFAULT_TIMEZONE',   'Europe/Bucharest');

//// WEBSITE CRITICAL SETTINGS =================================================
// list of application-wide capabilities; read help for more info
define('SYSCFG_APPLICATION_CAPABILITIES',
    'log, database, config, auth'
);

// session name; useful when having more websites on the same domain
// if you change this here, make sure you also change it in .htaccess file
// if you wish to disable sessions, set this to blank string ('')
define('SYSCFG_HTTP_SESSIONNAME',	'blindsessid');

}

////////////////////////////////////////////////////////////////////////////////
// History:
//  -- 21/06/2019 - v1 created;
