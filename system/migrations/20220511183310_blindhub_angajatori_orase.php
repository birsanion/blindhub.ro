<?php
/**
 * Migration Task class.
 */
class BlindhubAngajatoriOrase
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
            CREATE TABLE `qwf_angajatori_orase` (
                `idx_angajator` int(10) unsigned NOT NULL,
                `idx_oras` int(10) unsigned NOT NULL,
                UNIQUE KEY `idx_angajator` (`idx_angajator`,`idx_oras`),
                KEY `idx_oras` (`idx_oras`),
                CONSTRAINT `qwf_angajatori_orase_fk_1` FOREIGN KEY (`idx_angajator`) REFERENCES `qwf_angajatori` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_angajatori_orase_fk_2` FOREIGN KEY (`idx_oras`) REFERENCES `qwf_orase` (`idx`)
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
        return "DROP TABLE `qwf_angajatori_orase`";
    }
}