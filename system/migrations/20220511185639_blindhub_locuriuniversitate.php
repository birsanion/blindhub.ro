<?php
/**
 * Migration Task class.
 */
class BlindhubLocuriuniversitate
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
            CREATE TABLE `qwf_locuriuniversitate` (
                `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `idxauth` int(10) unsigned NOT NULL,
                `facultate` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `idx_domeniu_universitate` int(10) unsigned DEFAULT NULL,
                `numarlocuri` int(10) unsigned NOT NULL,
                `idx_oras` int(10) unsigned DEFAULT NULL,
                PRIMARY KEY (`idx`),
                KEY `idxauth` (`idxauth`),
                KEY `numarlocuri` (`numarlocuri`),
                KEY `idx_domeniu_universitate` (`idx_domeniu_universitate`),
                KEY `idx_oras` (`idx_oras`),
                CONSTRAINT `qwf_locuriuniversitate_fk_1` FOREIGN KEY (`idxauth`) REFERENCES `qwf_auth_users` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_locuriuniversitate_fk_2` FOREIGN KEY (`idx_domeniu_universitate`) REFERENCES `qwf_domenii_universitate` (`idx`),
                CONSTRAINT `qwf_locuriuniversitate_fk_3` FOREIGN KEY (`idx_oras`) REFERENCES `qwf_orase` (`idx`)
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
        return "DROP TABLE `qwf_locuriuniversitate`";
    }

}