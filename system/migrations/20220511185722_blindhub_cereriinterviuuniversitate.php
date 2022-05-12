<?php
/**
 * Migration Task class.
 */
class BlindhubCereriinterviuuniversitate
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
            CREATE TABLE `qwf_cereriinterviuuniversitate` (
                `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `idxauthangajat` int(10) unsigned NOT NULL,
                `idxauthuniversitate` int(10) unsigned NOT NULL,
                `idxlocuniversitate` int(10) unsigned NOT NULL,
                PRIMARY KEY (`idx`),
                KEY `idxauthangajat` (`idxauthangajat`),
                KEY `idxauthangajator` (`idxauthuniversitate`),
                KEY `idxlocmunca` (`idxlocuniversitate`),
                CONSTRAINT `qwf_cereriinterviuuniversitate_fk_1` FOREIGN KEY (`idxauthangajat`) REFERENCES `qwf_auth_users` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_cereriinterviuuniversitate_fk_2` FOREIGN KEY (`idxauthuniversitate`) REFERENCES `qwf_auth_users` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE
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
        return "DROP TABLE `qwf_cereriinterviuuniversitate`";
    }

}