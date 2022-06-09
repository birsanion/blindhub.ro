<?php
    switch ($this->ROUTE->GetFlagsLanguage()) {
        case 'en':
        case 'de':
            require_once('view-content-en.php');
            break;
    }
