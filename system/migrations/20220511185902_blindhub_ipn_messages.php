<?php
/**
 * Migration Task class.
 */
class BlindhubIpnMessages
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
            CREATE TABLE `qwf_ipn_messages` (
                `idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `payment_idx` int(11) unsigned NOT NULL,
                `action` int(1) unsigned NOT NULL,
                `message` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `approval` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `json_dump` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`idx`),
                KEY `payment_idx` (`payment_idx`),
                CONSTRAINT `qwf_ipn_messages_fk_1` FOREIGN KEY (`payment_idx`) REFERENCES `qwf_payments` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE
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
        return "DROP TABLE `qwf_ipn_messages`";
    }

}