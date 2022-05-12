<?php
/**
 * Migration Task class.
 */
class BlindhubAngajatiDomeniiCv
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
            CREATE TABLE `qwf_angajati_domenii_cv` (
                `idx_angajat` int(10) unsigned NOT NULL,
                `idx_domeniu_cv` int(10) unsigned NOT NULL,
                UNIQUE KEY `idx_angajat` (`idx_angajat`,`idx_domeniu_cv`),
                KEY `idx_domeniu_cv` (`idx_domeniu_cv`),
                CONSTRAINT `qwf_angajati_domenii_cv_fk_1` FOREIGN KEY (`idx_domeniu_cv`) REFERENCES `qwf_domenii_cv` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_angajati_domenii_cv_fk_2` FOREIGN KEY (`idx_angajat`) REFERENCES `qwf_angajati` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE
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
        return "DROP TABLE `qwf_angajati_domenii_cv`";
    }

}