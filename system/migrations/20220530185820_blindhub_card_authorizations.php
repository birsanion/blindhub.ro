<?php
/**
 * Migration Task class.
 */
class BlindhubCardAuthorizations
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
            CREATE TABLE IF NOT EXISTS `qwf_card_authorizations` (
                `idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `payment_processor` enum('euplatesc') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `invoice_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `ep_id` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `first_name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `last_name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `email` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `city` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `zip` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `amount` decimal(10,2) NOT NULL,
                `currency` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `order_desc` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `status` enum('approved','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `recurent_exp` DATE NULL DEFAULT NULL,
                `recurent_freq` int(5) NULL DEFAULT NULL,
                `last_ipn_message_idx` int(11) unsigned DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`idx`),
                UNIQUE KEY `invoice_id` (`invoice_id`),
                UNIQUE KEY `ep_id` (`ep_id`),
                UNIQUE KEY `last_ipn_message_id` (`last_ipn_message_idx`)
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
        return "DROP TABLE IF EXISTS `qwf_card_authorizations`";
    }
}