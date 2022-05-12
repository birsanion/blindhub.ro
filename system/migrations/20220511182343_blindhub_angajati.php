<?php
/**
 * Migration Task class.
 */
class BlindhubAngajati
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
            CREATE TABLE `qwf_angajati` (
                `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `idxauth` int(10) unsigned NOT NULL,
                `nume` text NOT NULL,
                `prenume` text NOT NULL,
                `idx_optiune_gradhandicap` int(10) unsigned DEFAULT NULL,
                `nevoispecifice` varchar(256) NOT NULL,
                `img` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                `cv_fisier_video` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                PRIMARY KEY (`idx`),
                UNIQUE KEY `idxauth` (`idxauth`),
                KEY `idx_optiune_gradhandicap` (`idx_optiune_gradhandicap`),
                CONSTRAINT `qwf_angajati_fk_1` FOREIGN KEY (`idxauth`) REFERENCES `qwf_auth_users` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_angajati_fk_2` FOREIGN KEY (`idx_optiune_gradhandicap`) REFERENCES `qwf_optiuni` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE
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
        return "DROP TABLE `qwf_angajati`";
    }
}