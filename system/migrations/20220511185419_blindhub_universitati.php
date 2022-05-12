<?php
/**
 * Migration Task class.
 */
class BlindhubUniversitati
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
            CREATE TABLE `qwf_universitati` (
                `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `idxauth` int(10) unsigned NOT NULL,
                `nume` varchar(64) NOT NULL DEFAULT '',
                `reprezentant` varchar(64) NOT NULL DEFAULT '',
                `idx_optiune_gradacces` int(10) unsigned DEFAULT NULL,
                `idx_optiune_gradechipare` int(10) unsigned DEFAULT NULL,
                `studdiz` tinyint(1) NOT NULL DEFAULT 0,
                `studcentru` tinyint(1) NOT NULL DEFAULT 0,
                `camerecamine` tinyint(1) NOT NULL DEFAULT 0,
                `persdedic` tinyint(1) NOT NULL DEFAULT 0,
                `braille` tinyint(4) NOT NULL DEFAULT 0,
                `idx_optiune_accesibilizare_clasa` int(10) unsigned DEFAULT NULL,
                `img` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                PRIMARY KEY (`idx`),
                UNIQUE KEY `idxauth` (`idxauth`),
                KEY `idx_optiune_gradacces` (`idx_optiune_gradacces`),
                KEY `idx_optiune_gradechipare` (`idx_optiune_gradechipare`),
                KEY `idx_optine_accesibilizare_clasa` (`idx_optiune_accesibilizare_clasa`),
                CONSTRAINT `qwf_universitati_fk_1` FOREIGN KEY (`idxauth`) REFERENCES `qwf_auth_users` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_universitati_fk_3` FOREIGN KEY (`idx_optiune_gradacces`) REFERENCES `qwf_optiuni` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_universitati_fk_4` FOREIGN KEY (`idx_optiune_gradechipare`) REFERENCES `qwf_optiuni` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE
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
        return "DROP TABLE `qwf_universitati`";
    }

}