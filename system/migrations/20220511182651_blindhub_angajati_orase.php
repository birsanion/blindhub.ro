<?php
/**
 * Migration Task class.
 */
class BlindhubAngajatiOrase
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
            CREATE TABLE `qwf_angajati_orase` (
                `idx_angajat` int(10) unsigned NOT NULL,
                `idx_oras` int(10) unsigned NOT NULL,
                UNIQUE KEY `idx_angajat` (`idx_angajat`,`idx_oras`),
                KEY `qwf_angajati_orase_fk_2` (`idx_oras`),
                CONSTRAINT `qwf_angajati_orase_fk_1` FOREIGN KEY (`idx_angajat`) REFERENCES `qwf_angajati` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_angajati_orase_fk_2` FOREIGN KEY (`idx_oras`) REFERENCES `qwf_orase` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE
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
        return "DROP TABLE `qwf_angajati_orase`";
    }
}