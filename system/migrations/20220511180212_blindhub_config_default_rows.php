<?php
/**
 * Migration Task class.
 */
class BlindhubConfigDefaultRows
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
            INSERT INTO `qwf_config` (`idx`, `shortname`, `longname`, `type`, `value`) VALUES
                (1, 'default-language-code', 'Character code for default language (like \'en\')', 'string', 'en'),
                (2, 'theme', 'The name of the user interface theme', 'string', 'thunderstorm'),
                (3, 'force-desktop', 'Force displaying of desktop theme, even if the user\'s device is mobile or otherwise', 'bool', 'true'),
                (4, 'auth-binduserdevice', 'Whether or not to bind the user device to the logged in user', 'bool', 'false'),
                (5, 'auth-sessionexpiration', 'Whether to terminate sessions sooner than what is written in php.ini.', 'int', '0'),
                (6, 'auth-rejectfailed', 'Whether to enforce failed login bans for users and IPs, and how many retries to allow before that.', 'int', '0');
        END;
    }

    /**
    * Return the SQL statements for the Down migration
    *
    * @return string The SQL string to execute for the Down migration.
    */
    public function getDownSQL()
    {
        return "DELETE FROM `qwf_config`";
    }
}