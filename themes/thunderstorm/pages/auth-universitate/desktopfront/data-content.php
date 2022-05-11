<?php
////////////////////////////////////////////////////////////////////////////////
// Part of theme Thunderstorm, of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.

call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'jquery-ui-1-10-3-custom-min.js',
        'jq-file-upload/jquery.iframe-transport.js',
        'jq-file-upload/jquery.fileupload.js'
    )
);

call_user_func($this->fncCallback, 'htmlheader', 'structure-styles', MANOP_SET,
    array(
        'jquery-ui-1-10-3-custom.css',
        'jquery-fileupload-ui.css'
    )
);

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
    }
}