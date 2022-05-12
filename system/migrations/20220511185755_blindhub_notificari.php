<?php
/**
 * Migration Task class.
 */
class BlindhubNotificari
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
            CREATE TABLE `qwf_notificari` (
                `idxauth` int(10) unsigned NOT NULL,
                `idxmesaj` int(10) unsigned DEFAULT NULL,
                `idxinterviu` int(10) unsigned DEFAULT NULL,
                `titlu` varchar(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `mesaj` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                UNIQUE KEY `idxauth` (`idxauth`,`idxmesaj`),
                UNIQUE KEY `idxauth_2` (`idxauth`,`idxinterviu`),
                KEY `idxmesaj` (`idxmesaj`),
                KEY `idxinterviu` (`idxinterviu`),
                CONSTRAINT `qwf_notificari_fk_1` FOREIGN KEY (`idxauth`) REFERENCES `qwf_auth_users` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_notificari_fk_2` FOREIGN KEY (`idxmesaj`) REFERENCES `qwf_mesaje` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `qwf_notificari_fk_3` FOREIGN KEY (`idxinterviu`) REFERENCES `qwf_interviuri` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE
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
        return "DROP TABLE `qwf_notificari`";
    }

}