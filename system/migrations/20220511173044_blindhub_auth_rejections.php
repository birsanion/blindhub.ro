<?php
/**
 * Migration Task class.
 */
class BlindhubAuthRejections
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
            CREATE TABLE IF NOT EXISTS `qwf_auth_rejections` (
                `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `idxuser` int(10) unsigned NOT NULL,
                `ipaddr` varchar(100) NOT NULL,
                `numoftries` int(10) unsigned NOT NULL,
                `numofbans` int(10) unsigned NOT NULL,
                `tstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`idx`),
                KEY `idxuser` (`idxuser`),
                KEY `ipaddr` (`ipaddr`),
                KEY `numoftries` (`numoftries`),
                KEY `numofbans` (`numofbans`),
                KEY `tstamp` (`tstamp`)
            )
        END;
    }

    /**
    * Return the SQL statements for the Down migration
    *
    * @return string The SQL string to execute for the Down migration.
    */
    public function getDownSQL()
    {
        return "DROP TABLE IF EXISTS `qwf_auth_rejections`";
    }
}