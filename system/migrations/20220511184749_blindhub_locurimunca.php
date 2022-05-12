<?php
/**
 * Migration Task class.
 */
class BlindhubLocurimunca
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
            CREATE TABLE `qwf_locurimunca` (
                `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `idxauth` int(10) unsigned NOT NULL,
                `idx_oras` int(10) unsigned DEFAULT NULL,
                `idx_domeniu_cv` int(10) unsigned DEFAULT NULL,
                `competente` text NOT NULL,
                `titlu` text NOT NULL,
                `descriere` text NOT NULL,
                `idx_optiune_tipslujba` int(10) unsigned DEFAULT NULL,
                `datapostare` date NOT NULL DEFAULT (CURRENT_DATE),
                PRIMARY KEY (`idx`),
                KEY `datapostare` (`datapostare`),
                KEY `idx_oras` (`idx_oras`),
                KEY `idx_domeniu_cv` (`idx_domeniu_cv`),
                KEY `qwf_locurimunca_fk_3` (`idxauth`),
                KEY `idx_optiune_tipslujba` (`idx_optiune_tipslujba`),
                CONSTRAINT `qwf_locurimunca_fk_1` FOREIGN KEY (`idx_oras`) REFERENCES `qwf_orase` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_locurimunca_fk_2` FOREIGN KEY (`idx_domeniu_cv`) REFERENCES `qwf_domenii_cv` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_locurimunca_fk_3` FOREIGN KEY (`idxauth`) REFERENCES `qwf_auth_users` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_locurimunca_fk_4` FOREIGN KEY (`idx_optiune_tipslujba`) REFERENCES `qwf_optiuni` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ;
        END;
    }

    /**
     * Return the SQL statements for the Down migration
     *
     * @return string The SQL string to execute for the Down migration.
     */
    public function getDownSQL()
    {
        return "DROP TABLE `qwf_locurimunca`";
    }

}