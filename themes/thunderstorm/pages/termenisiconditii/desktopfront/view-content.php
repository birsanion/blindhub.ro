<?php
    switch ($this->ROUTE->GetFlagsLanguage()) {
        case 'ro':
            require_once('view-content-ro.php');
            break;

        case 'fr':
            require_once('view-content-fr.php');
            break;

        case 'en':
        case 'de':
            require_once('view-content-en.php');
            break;
    }
