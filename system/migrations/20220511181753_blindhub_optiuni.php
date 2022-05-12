<?php
/**
 * Migration Task class.
 */
class BlindhubOptiuni
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
            CREATE TABLE `qwf_optiuni` (
                `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `categorie` enum('gradhandicap','gradacces','gradechipare','cazare','costuri','tipslujba','dimensiuneslujba','accesibilizare_clasa') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `nume` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                PRIMARY KEY (`idx`)
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
        return "DROP TABLE `qwf_optiuni`";
    }
}