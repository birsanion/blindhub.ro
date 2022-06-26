<?php
    switch ($this->ROUTE->GetFlagsLanguage()) {
    	case 'fr':
            require_once('view-content-fr.php');
            break;

        case 'default':
            require_once('view-content-en.php');
            break;
    }
