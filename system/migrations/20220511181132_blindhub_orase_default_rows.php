<?php
/**
 * Migration Task class.
 */
class BlindhubOraseDefaultRows
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
            INSERT INTO `qwf_orase` (`idx`, `nume`) VALUES
                (1, 'Alba Iulia'),
                (2, 'Alexandria'),
                (3, 'Arad'),
                (4, 'Baia Mare'),
                (5, 'Bistrița Năsăud'),
                (6, 'Brăila'),
                (7, 'București'),
                (8, 'Botoșani'),
                (9, 'Brașov'),
                (10, 'Bacău'),
                (11, 'Buzău'),
                (12, 'Călărași'),
                (13, 'Cluj'),
                (14, 'Constanța'),
                (15, 'Craiova'),
                (16, 'Deva'),
                (17, 'Iași'),
                (18, 'Focșani'),
                (19, 'Galați'),
                (20, 'Giurgiu'),
                (21, 'Oradea'),
                (22, 'Ploiești'),
                (23, 'Pitești'),
                (24, 'Piatra Neamț'),
                (25, 'Reșița'),
                (26, 'Râmnicu Vâlcea'),
                (27, 'Timișoara'),
                (28, 'Târgu Mureș'),
                (29, 'Târgu Jiu'),
                (30, 'Slatina'),
                (31, 'Sibiu'),
                (32, 'Satu Mare'),
                (33, 'Suceava'),
                (34, 'Târgoviște');
        END;
    }

    /**
     * Return the SQL statements for the Down migration
     *
     * @return string The SQL string to execute for the Down migration.
     */
    public function getDownSQL()
    {
        return "DELETE FROM `qwf_orase`";
    }
}