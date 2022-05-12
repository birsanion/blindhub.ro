<?php
/**
 * Migration Task class.
 */
class BlindhubCereriinterviu
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
            CREATE TABLE `qwf_cereriinterviu` (
                `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `idxauthangajat` int(10) unsigned NOT NULL,
                `idxauthangajator` int(10) unsigned NOT NULL,
                `idxlocmunca` int(10) unsigned NOT NULL,
                PRIMARY KEY (`idx`),
                KEY `idxauthangajat` (`idxauthangajat`),
                KEY `idxauthangajator` (`idxauthangajator`),
                KEY `idxlocmunca` (`idxlocmunca`),
                CONSTRAINT `qwf_cereriinterviu_fk_1` FOREIGN KEY (`idxauthangajat`) REFERENCES `qwf_auth_users` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_cereriinterviu_fk_2` FOREIGN KEY (`idxauthangajator`) REFERENCES `qwf_auth_users` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE
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
        return "DROP TABLE `qwf_cereriinterviu`";
    }

}