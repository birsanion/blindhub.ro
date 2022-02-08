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
        
        <link rel="stylesheet" href="<?php echo qurl_f('styles/reset.css'); ?>" />
        <link rel="stylesheet" href="<?php echo qurl_f('styles/standard.css'); ?>" />
        <link rel="stylesheet" href="<?php echo qurl_f('styles/main.css?v=2'); ?>" />
        
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
        <script src="<?php echo qurl_f('scripts/main.js'); ?>"></script>
    </head>

    <body class="size-1">
