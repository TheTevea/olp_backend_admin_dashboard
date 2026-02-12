<?php
/**
 * Copyright UDAYA Technology Co,.LTD (http://www.udaya-tech.com)
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
include("includes/function.php");
$start = 2025;
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>
            <?php __('Olongpich Transport'); ?>
        </title>

        <!-- icon -->
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->webroot; ?>favicon.ico" />
        <!-- Style Sheet -->
        <!-- General Style Sheet -->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>css/login.css?key=<?php echo time(); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>css/button.css" />
        <!-- Validator -->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>js/validateEngine/css/validationEngine.jquery.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>js/validateEngine/css/template.css" />
        <!-- Jquery UI -->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>js/jquery-ui-1.8.14.custom/development-bundle/themes/base/jquery.ui.all.css?323jj" />
        
        <!-- Jquery Script -->
        <!-- Jquery -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery-1.7.min.js"></script>
        <!-- Validator -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/validateEngine/js/jquery.validationEngine-en.js"></script>
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/validateEngine/js/jquery.validationEngine.js"></script>
    </head>
    <body>
        <div class="login-container" id="loginForm" style="background-color: #fff;">
            <div class="login-wrapper">
                <div class="login-content">
                    <?php echo $content_for_layout; ?>
                </div>
            </div>
        </div>
    </body>
</html>