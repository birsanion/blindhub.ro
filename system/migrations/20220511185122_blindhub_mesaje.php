<?php
/**
 * Migration Task class.
 */
class BlindhubMesaje
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
            CREATE TABLE `qwf_mesaje` (
                `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `idxauthangajat` int(10) unsigned NOT NULL,
                `idxauthinterlocutor` int(10) unsigned NOT NULL,
                `idxauthmesaj` int(10) unsigned NOT NULL,
                `mesaj` text NOT NULL,
                `tstamp` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`idx`),
                KEY `idxauthangajat` (`idxauthangajat`),
                KEY `idxauthinterlocutor` (`idxauthinterlocutor`),
                KEY `idxauthmesaj` (`idxauthmesaj`),
                CONSTRAINT `qwf_mesaje_fk_1` FOREIGN KEY (`idxauthangajat`) REFERENCES `qwf_auth_users` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_mesaje_fk_2` FOREIGN KEY (`idxauthinterlocutor`) REFERENCES `qwf_auth_users` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_mesaje_fk_3` FOREIGN KEY (`idxauthmesaj`) REFERENCES `qwf_auth_users` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE
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
        return "DROP TABLE `qwf_mesaje`";
    }

}