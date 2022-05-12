<?php
/**
 * Migration Task class.
 */
class BlindhubAuthUserpermissions
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
            CREATE TABLE IF NOT EXISTS `qwf_auth_userpermissions` (
                `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `usridx` int(10) unsigned NOT NULL,
                `target` text NOT NULL,
                `perm` tinyint(4) NOT NULL,
                `advanced` text NULL ,
                PRIMARY KEY (`idx`),
                KEY `usridx` (`usridx`),
                CONSTRAINT `qwf_auth_userpermissions_fk_1` FOREIGN KEY (`usridx`) REFERENCES `qwf_auth_users` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE
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
        return "DROP TABLE IF EXISTS `qwf_auth_userpermissions`";
    }
}