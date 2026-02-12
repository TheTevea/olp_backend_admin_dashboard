<?php echo $this->element('prevent_multiple_submit'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $(".btnSaveUserProfile").unbind("click").click(function(event){
            $("#UserProfileForm").submit();
        });
        $("#UserProfileForm").validationEngine('attach', {
            isOverflown: true,
            overflownDIV: ".ui-tabs-panel"
        });
        $("#UserProfileForm").ajaxForm({
            beforeSubmit: function(arr, $form, options) {
                $(".txtSave").html("<?php echo ACTION_LOADING; ?>");
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
            },
            success: function(result) {
                $(".txtSave").html("<?php echo ACTION_SAVE; ?>");
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                // alert message
                $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span>'+result+'</p>');
                $("#dialog").dialog({
                    title: '<?php echo DIALOG_INFORMATION; ?>',
                    resizable: false,
                    modal: true,
                    width: 'auto',
                    height: 'auto',
                    buttons: {
                        '<?php echo ACTION_CLOSE; ?>': function() {
                            $(this).dialog("close");
                        }
                    }
                });
            }
        });
        // Set Form Container Height
        var windowHeight  = $(window).height();
        var headerHeight  = $('.ui-layout-north').outerHeight(true);
        var tabsNavHeight = $('.ui-tabs-nav').outerHeight(true);
        var formHeight    = $('#userProfileFormHeader').outerHeight(true);
        var formContainer = windowHeight - headerHeight - tabsNavHeight - formHeight - 100;
        $('#userProfileFormContainer').css({
            'height': formContainer + 'px',
            'max-height': formContainer + 'px'
        });
    });
</script>
<!-- Button Header (Format#4) -->
<div class="filter-container" id="userProfileFormHeader">
    <div class="filter-row">
        <div class="filter-form-group">
            <button type="submit" class="form-btn btnSaveUserProfile">
                <i class="fas fa-save"></i>
                <span class="txtSave"><?php echo ACTION_SAVE; ?></span>
            </button>
        </div>
    </div>
</div>

<!-- Form Container (Format#4) -->
<?php echo $this->Form->create('User', array('class' => 'form-table-container')); ?>
<?php echo $this->Form->hidden('sys_code'); ?>
<div class="form-container" id="userProfileFormContainer">
    <?php if($user['User']['type'] == 2): ?>
    <fieldset>
        <legend><?php __(GENERAL_MY_PROFILE); ?></legend>
        <table class="form-table">
            <?php if($user['User']['type'] != 1): ?>
                <?php echo $this->Form->hidden('offline_project_id', array('value' => $this->data['User']['offline_project_id'])); ?>
            <?php endif; ?>
            
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="UserFirstName"><?php echo TABLE_FIRST_NAME; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('first_name', array(
                            'class' => 'form-input validate[required]',
                            'label' => false,
                            'div' => false
                        )); ?>
                    </div>
                </td>
            </tr>
            
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="UserLastName"><?php echo TABLE_LAST_NAME; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('last_name', array(
                            'class' => 'form-input validate[required]',
                            'label' => false,
                            'div' => false
                        )); ?>
                    </div>
                </td>
            </tr>
            
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="UserTelephone"><?php echo TABLE_TELEPHONE; ?> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('telephone', array(
                            'class' => 'form-input',
                            'label' => false,
                            'div' => false
                        )); ?>
                    </div>
                </td>
            </tr>
            
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="UserEmail"><?php echo TABLE_EMAIL; ?> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('email', array(
                            'class' => 'form-input',
                            'label' => false,
                            'div' => false
                        )); ?>
                    </div>
                </td>
            </tr>
        </table>
    </fieldset>
    <?php endif; ?>
    <fieldset>
        <legend><?php __(USER_LOGIN_INFO); ?></legend>
        <table class="form-table">
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="UserUsername"><?php echo USER_USER_NAME; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('username', array(
                            'class' => 'form-input validate[required]',
                            'label' => false,
                            'div' => false
                        )); ?>
                    </div>
                </td>
            </tr>
            
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="UserOldPassword"><?php echo USER_OLD_PASSWORD; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->password('old_password', array(
                            'class' => 'form-input validate[required]',
                            'value' => '',
                            'name' => 'data[old_password]',
                            'label' => false,
                            'div' => false
                        )); ?>
                    </div>
                </td>
            </tr>
            
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="UserPassword"><?php echo USER_NEW_PASSWORD; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->password('password', array(
                            'class' => 'form-input validate[required]',
                            'value' => '',
                            'label' => false,
                            'div' => false
                        )); ?>
                    </div>
                </td>
            </tr>
            
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="UserConfirmPassword"><?php echo USER_CONFIRM_PASSWORD; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->password('confirm_password', array(
                            'class' => 'form-input validate[required,equals[UserPassword]]',
                            'value' => '',
                            'label' => false,
                            'div' => false
                        )); ?>
                    </div>
                </td>
            </tr>
        </table>
    </fieldset>
</div>
<?php echo $this->Form->end(); ?>