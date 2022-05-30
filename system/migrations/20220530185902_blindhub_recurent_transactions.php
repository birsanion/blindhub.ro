<?php
/**
 * Migration Task class.
 */
class BlindhubRecurentTransactions
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
            CREATE TABLE IF NOT EXISTS `qwf_recurent_transactions` (
                `idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `payment_processor` enum('euplatesc') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `card_authorization_idx` int(11) unsigned NOT NULL,
                `invoice_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `ep_id` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `amount` decimal(10,2) NOT NULL,
                `currency` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `order_desc` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `status` enum('approved','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `last_ipn_message_idx` int(11) unsigned DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`idx`),
                KEY `card_authorization_idx` (`card_authorization_idx`),
                UNIQUE KEY `invoice_id` (`invoice_id`),
                UNIQUE KEY `ep_id` (`ep_id`),
                UNIQUE KEY `last_ipn_message_id` (`last_ipn_message_idx`),
                CONSTRAINT `qwf_recurent_transactions` FOREIGN KEY (`card_authorization_idx`) REFERENCES `qwf_card_authorizations` (`idx`) ON DELETE CASCADE ON UPDATE CASCADE
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
        return "DROP TABLE IF EXISTS `qwf_recurent_transactions`";
    }
}