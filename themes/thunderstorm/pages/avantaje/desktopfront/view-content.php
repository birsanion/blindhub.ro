<?php
    switch ($this->ROUTE->GetFlagsLanguage()) {
        case 'ro':
            require_once('view-content-ro.php');
            break;

        case 'en':
            require_once('view-content-en.php');
            break;
    }
