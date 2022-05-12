<?php
/**
 * Migration Task class.
 */
class BlindhubAngajatori
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
            CREATE TABLE `qwf_angajatori` (
                `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `idxauth` int(10) unsigned NOT NULL,
                `companie` varchar(256) NOT NULL DEFAULT '',
                `adresa` varchar(256) NOT NULL DEFAULT '',
                `cui` varchar(16) NOT NULL DEFAULT '',
                `firmaprotejata` tinyint(1) NOT NULL DEFAULT 0,
                `idx_optiune_dimensiunefirma` int(10) unsigned DEFAULT NULL,
                `img` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                PRIMARY KEY (`idx`),
                UNIQUE KEY `idxauth` (`idxauth`),
                KEY `idx_optiune_dimeniunefirma` (`idx_optiune_dimensiunefirma`),
                CONSTRAINT `qwf_angajatori_fk_1` FOREIGN KEY (`idxauth`) REFERENCES `qwf_auth_users` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_angajatori_fk_2` FOREIGN KEY (`idx_optiune_dimensiunefirma`) REFERENCES `qwf_optiuni` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE
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
        return "DROP TABLE `qwf_angajatori`";
    }
}