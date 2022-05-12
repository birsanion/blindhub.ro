<?php
/**
 * Migration Task class.
 */
class BlindhubOptiuniDefaultRows
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
            INSERT INTO `qwf_optiuni` (`idx`, `categorie`, `nume`) VALUES
                (1, 'gradhandicap', 'Grav'),
                (2, 'gradhandicap', 'Accentuat'),
                (3, 'gradhandicap', 'Mediu'),
                (4, 'gradhandicap', 'Ușor'),
                (5, 'gradacces', 'Fara accesibilizare'),
                (6, 'gradacces', 'Parțial'),
                (7, 'gradacces', 'Total'),
                (8, 'gradechipare', 'Parțial'),
                (9, 'gradechipare', 'Total'),
                (15, 'tipslujba', 'Part-time'),
                (16, 'tipslujba', 'Full-time'),
                (17, 'dimensiuneslujba', 'Sub 50'),
                (18, 'dimensiuneslujba', 'Peste 50'),
                (20, 'accesibilizare_clasa', 'Fară accesibilizare'),
                (21, 'accesibilizare_clasa', 'Parțial'),
                (22, 'accesibilizare_clasa', 'Total');
        END;
    }

    /**
     * Return the SQL statements for the Down migration
     *
     * @return string The SQL string to execute for the Down migration.
     */
    public function getDownSQL()
    {
        return "DELETE FROM `qwf_optiuni`";
    }
}