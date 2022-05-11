<?php
////////////////////////////////////////////////////////////////////////////////
// Part of theme Thunderstorm, of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.

call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'loadingoverlay.min.js',
    )
);

try {
    $validation = $this->validator->make($_POST, [
        'invoice_id'  => 'required|between:1,27',
        'ep_id'       => 'required|between:40,40',
    ]);

    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $error = array_values($errors->firstOfAll())[0];
        throw new Exception("EROARE: {$error}!", 400);
    }
} catch (\Exception $e) {
    $this->GLOBAL['errormsg'] = "Cerere invalida";
}

