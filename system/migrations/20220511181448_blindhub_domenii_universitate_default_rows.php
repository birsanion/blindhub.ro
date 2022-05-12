<?php
/**
 * Migration Task class.
 */
class BlindhubDomeniiUniversitateDefaultRows
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
            INSERT INTO `qwf_domenii_universitate` (`idx`, `nume`) VALUES
            (1, 'Științe socio-umane'),
            (2, 'Management și Studii Economice'),
            (3, 'Matematică, informatică și fizică'),
            (4, 'Științe ale Naturii biologie și chimie'),
            (5, 'Știință politică și administrație publică'),
            (6, 'Muzică și Arte vizuale'),
            (7, 'Limbi străine și studii de literatură'),
            (8, 'Istorie și Studii Europene'),
            (9, 'Inginerie'),
            (10, 'Medicină și kinetoterapie'),
            (11, 'Arhitectură și urbanism');
        END;
    }

    /**
     * Return the SQL statements for the Down migration
     *
     * @return string The SQL string to execute for the Down migration.
     */
    public function getDownSQL()
    {
        return "DELETE FROM `qwf_domenii_universitate`";
    }
}