<?php
/**
 * [PART OF QUICK WEB FRAME]
 * theme / desktopfront / view / common / htmlheader.php
 */
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />

        <title>BlindHUB</title>
        <meta name="description" content="" />

        <meta name="viewport" content="width=device-width; initial-scale=1.0" />

        <link rel="shortcut icon" href="<?php echo qurl_f('images/favicon.ico'); ?>" />
        <link rel="apple-touch-icon" href="<?php echo qurl_f('images/apple-touch-icon.png'); ?>" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo qurl_f('vendor/bootstrap/css/bootstrap.min.css'); ?>" />
        <link rel="stylesheet" href="<?php echo qurl_f('vendor/bootstrap-icons/bootstrap-icons.css'); ?>" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

        <!--<link rel="stylesheet" href="<?php echo qurl_f('styles/reset.css'); ?>" />-->
        <link rel="stylesheet" href="<?php echo qurl_f('styles/standard.css'); ?>" />
        <link rel="stylesheet" href="<?php echo qurl_f('styles/main.css?v=' . date('YmdHis')); ?>" />
        <link rel="stylesheet" href="<?php echo qurl_f('styles/custom.css?v=1'); ?>" />

        <?php
            if (isset($this->DATA['structure-styles'])){
                foreach ($this->DATA['structure-styles'] as $pcVal){
                    echo '<link rel="stylesheet" href="' . qurl_f("styles/$pcVal") .'" />'."\r\n";
                }
            }
        ?>

        <script src="<?php echo qurl_f('scripts/jquery-1-9-1-min.js'); ?>"></script>
        <?php
            if (isset($this->DATA['structure-javascript'])){
                foreach ($this->DATA['structure-javascript'] as $pcVal){
                    echo '<script src="'. qurl_f("scripts/$pcVal") .'"></script>'."\r\n";
                }
            }
        ?>
        <!--[if lt IE 9]>
        <script src="<?php echo qurl_f('scripts/html5.js'); ?>" type="text/javascript"></script>
        <![endif]-->
        <script src="<?php echo qurl_f('scripts/jquery.validate.min.js'); ?>"></script>
        <script src="<?php echo qurl_f('vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
        <script src="<?php echo qurl_f('scripts/main.js?v=1'); ?>"></script>
    </head>

    <body class="size-1">
        <div id="wrapper">
            <div id="content-wrapper" class="d-flex flex-column">
