<?php
/**
 * Migration Task class.
 */
class BlindbubAuthUsersStatisticsPermission
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
            ALTER TABLE `qwf_auth_users`
            ADD COLUMN statistics_permission tinyint(1) DEFAULT 0 AFTER `tiputilizator`;
        END;
    }

    /**
     * Return the SQL statements for the Down migration
     *
     * @return string The SQL string to execute for the Down migration.
     */
    public function getDownSQL()
    {
        return "ALTER TABLE `qwf_auth_users` DROP COLUMN statistics_permission";
    }
}