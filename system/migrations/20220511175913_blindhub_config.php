<?php
/**
 * Migration Task class.
 */
class BlindhubConfig
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
            CREATE TABLE `qwf_config` (
                `idx` int(10) UNSIGNED NOT NULL,
                `shortname` varchar(255) NOT NULL,
                `longname` text NOT NULL,
                `type` text NOT NULL,
                `value` text NOT NULL,
                PRIMARY KEY (`idx`)
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
        return "DROP TABLE `qwf_config`";
    }
}