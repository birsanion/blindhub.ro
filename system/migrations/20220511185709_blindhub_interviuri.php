<?php
/**
 * Migration Task class.
 */
class BlindhubInterviuri
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
            CREATE TABLE `qwf_interviuri` (
                `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `idxauthangajat` int(10) unsigned NOT NULL,
                `idxauthangajator` int(10) unsigned DEFAULT NULL,
                `idxauthuniversitate` int(10) unsigned DEFAULT NULL,
                `idxobject` int(10) unsigned NOT NULL COMMENT 'indexul locului de munca sau a ofertei de universitate',
                `tstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `initvideo` tinyint(4) NOT NULL DEFAULT 0,
                `vonagesessid` text NOT NULL,
                `vonagenevaztoken` text NOT NULL,
                `vonageinterlocutortoken` text NOT NULL,
                PRIMARY KEY (`idx`),
                KEY `idxauthangajat` (`idxauthangajat`),
                KEY `idxauthangajator` (`idxauthangajator`),
                KEY `idxauthuniversitate` (`idxauthuniversitate`),
                CONSTRAINT `qwf_interviuri_fk_1` FOREIGN KEY (`idxauthangajat`) REFERENCES `qwf_auth_users` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_interviuri_fk_2` FOREIGN KEY (`idxauthangajator`) REFERENCES `qwf_auth_users` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_interviuri_fk_3` FOREIGN KEY (`idxauthuniversitate`) REFERENCES `qwf_auth_users` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE
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
        return "DROP TABLE `qwf_interviuri`";
    }

}