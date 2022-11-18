<?php

if ($this->AUTH->IsAuthenticated()) {
    switch ((int)$this->AUTH->GetAdvancedDetail('tiputilizator')) {
        case 0:
            $this->ROUTE->Redirect(qurl_l('home-nevaz'));
            break;

        case 1:
            $this->ROUTE->Redirect(qurl_l('home-angajator'));
            break;

        case 2:
            $this->ROUTE->Redirect(qurl_l('home-universitate'));
            break;

        case -1:
            $this->ROUTE->Redirect(qurl_l('statistici'));
            break;
    }
}
