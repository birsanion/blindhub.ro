<?php
/**
 * Migration Task class.
 */
class BlindhubAuthUsers
{
    public function preUp()
    {
        // add the pre-migration code here
    }

    public function postUp()
    {
        // add the post-migration code here
    }

    public function preDown()
    {
        // add the pre-migration code here
    }

    public function postDown()
    {
        // add the post-migration code here
    }

    /**
    * Return the SQL statements for the Up migration
    *
    * @return string The SQL string to execute for the Up migration.
    */
    public function getUpSQL()
    {
        return <<<END
            CREATE TABLE IF NOT EXISTS `qwf_auth_users` (
                `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `username` varchar(128) NOT NULL,
                `passhash` text NULL,
                `salt` text NULL,
                `recoverhash` text NULL,
                `enabled` tinyint(4) NOT NULL DEFAULT 1,
                `tiputilizator` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '0=angajat, 1=angajator, 2=universitate',
                `apploginid` varchar(64) NULL,
                PRIMARY KEY (`idx`),
                UNIQUE KEY `username` (`username`),
                KEY `tiputilizator` (`tiputilizator`),
                KEY `apploginid` (`apploginid`),
                KEY `enabled` (`enabled`)
            );
        END;
    }

    /**
    * Return the SQL statements for the Down migration
    *
    * @return string The SQL string to execute for the Down migration.
    */
    public function getDownSQL()
    {
        return "DROP TABLE IF EXISTS `qwf_auth_users`";
    }
}