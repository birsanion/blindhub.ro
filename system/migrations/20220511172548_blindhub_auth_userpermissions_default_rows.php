<?php
/**
 * Migration Task class.
 */
class BlindhubAuthUserpermissionsDefaultRows
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
            INSERT INTO `qwf_auth_userpermissions` (`usridx`, `target`, `perm`) VALUES
                (1, '*', 0),
                (1, '*/index', 1),
                (1, '*/nogui', 1);
        END;
    }

    /**
    * Return the SQL statements for the Down migration
    *
    * @return string The SQL string to execute for the Down migration.
    */
    public function getDownSQL()
    {
        return "DELETE FROM `qwf_auth_userpermissions`";
    }
}