<?php
/**
 * Migration Task class.
 */
class BlindhubDomeniiCvDefaultRows
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
            INSERT INTO `qwf_domenii_cv` (`idx`, `nume`) VALUES
                (1, 'IT'),
                (2, 'Medical'),
                (3, 'Call center'),
                (4, 'Resurse umane'),
                (5, 'Asistență socială'),
                (6, 'Jurnalism și relații publice'),
                (7, 'Radio'),
                (8, 'Psihologie, consiliere, coaching'),
                (9, 'Educație și training'),
                (10, 'Industria creativă și artistică'),
                (11, 'Administrație publică și instituții'),
                (12, 'Desk office'),
                (13, 'Wellness și SPA'),
                (14, 'Traducător / translator'),
                (15, 'Diverse');
        END;
    }

    /**
     * Return the SQL statements for the Down migration
     *
     * @return string The SQL string to execute for the Down migration.
     */
    public function getDownSQL()
    {
        return "";
    }
}