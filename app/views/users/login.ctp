<?php
echo $this->element('prevent_multiple_submit');
?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#UserLoginForm").validationEngine();
        
        // Enhanced focus management
        setTimeout(function() {
            var $username = $("#UserUsername");
            var $password = $("#UserPassword");
            
            if ($username.val().trim() === '') {
                $username.trigger('focus');
            } else {
                $password.trigger('focus');
                
                // Select the password text if it exists (for easy clearing)
                if ($password.val().trim() !== '') {
                    $password.trigger('select');
                }
            }
            console.log("1");
        }, 100); // Small delay to ensure all elements are ready
        
        // Improved login button handling
        $(".btnLogin").on('click', function(e) {
            e.preventDefault();
            var $form = $("#UserLoginForm");
            var $submitText = $(".txtLogin");
            
            if ($form.validationEngine("validate")) {
                $submitText.text('Loading....');
                $(this).prop('disabled', true); // Disable button during submission
                $form.trigger('submit');
            }
        });
        
        // Handle Enter key submission
        $("#UserLoginForm input").on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                $(".btnLogin").trigger('click');
                return false;
            }
        });
    });
</script>

<div class="login-card">
    <?php echo $this->Form->create('User', array('action' => 'login', 'class' => 'modern-form')); ?>
    <input type="hidden" id="lat" name="data[User][lat]" />
    <input type="hidden" id="long" name="data[User][long]" />
    <input type="hidden" id="accuracy" name="data[User][accuracy]" />
    
    <div class="login-header">
        <img src="<?php echo $this->webroot; ?>img/logo-1.png" class="login-logo" alt="Logo" />
        <h1 class="system-title">Login</h1>
    </div> 
    <?php echo $this->Session->flash(); ?> 
    <div class="form-group">
        <label for="UserUsername"><?php echo USER_USER_NAME; ?></label>
        <input id="UserUsername" class="form-input validate[required]" type="text" name="data[User][username]" placeholder="Enter your username" />
    </div>
    
    <div class="form-group">
        <label for="UserPassword"><?php echo USER_PASSWORD; ?></label>
        <input id="UserPassword" class="form-input validate[required]" type="password" name="data[User][password]" placeholder="Enter your password" />
    </div>
    
    <?php if ($log >= 3) { ?>
    <div class="form-group captcha-group">
        <div class="captcha-container">
            <img alt="" id="secret" class="captcha-image" src="captcha/securimage_show_example.php?sid=<?php echo md5(time()) ?>" />
            <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="19" height="19" id="SecurImage_as3">
                <param name="allowScriptAccess" value="sameDomain" />
                <param name="allowFullScreen" value="false" />
                <param name="movie" value="captcha/securimage_play.swf?audio=captcha/securimage_play.php&bgColor1=#777&bgColor2=#fff&iconColor=#000&roundedCorner=5" />
                <param name="quality" value="high" />
                <param name="bgcolor" value="#ffffff" />
                <param name="wmode" value="transparent" />
                <embed src="captcha/securimage_play.swf?audio=captcha/securimage_play.php&bgColor1=#777&bgColor2=#fff&iconColor=#000&roundedCorner=5" quality="high" bgcolor="#ffffff" width="19" height="19" name="SecurImage_as3" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="transparent" />
            </object>
            <a href="#" title="Refresh Image" onclick="document.getElementById('secret').src = 'captcha/securimage_show_example.php?sid=' + Math.random(); return false" class="refresh-captcha">
                <img src="<?php $this->webroot; ?>captcha/images/refresh.png" alt="Reload Image" />
            </a>
        </div>
        <input type="text" id="UserCode" name="data[User][code]" class="form-input" placeholder="<?php echo TABLE_CODE; ?>" />
    </div>
    <?php } ?>
    
    <button type="submit" class="login-button btnLogin">
        <span class="txtLogin"><?php echo ACTION_LOGIN; ?></span>
    </button>
    
    <?php echo $this->Form->end(); ?>
</div>