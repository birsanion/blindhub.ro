<?php
/**
 * Migration Task class.
 */
class BlindhubAngajatoriDomeniiCv
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
            CREATE TABLE `qwf_angajatori_domenii_cv` (
                `idx_angajator` int(10) unsigned NOT NULL,
                `idx_domeniu_cv` int(10) unsigned NOT NULL,
                UNIQUE KEY `idx_angajator` (`idx_angajator`,`idx_domeniu_cv`),
                KEY `idx_domeniu_cv` (`idx_domeniu_cv`),
                CONSTRAINT `qwf_angajatori_domenii_cv_fk_1` FOREIGN KEY (`idx_angajator`) REFERENCES `qwf_angajatori` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_angajatori_domenii_cv_fk_2` FOREIGN KEY (`idx_domeniu_cv`) REFERENCES `qwf_domenii_cv` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE
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
        return "DROP TABLE `qwf_angajatori_domenii_cv`";
    }

}