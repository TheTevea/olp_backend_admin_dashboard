<?php echo $this->element('prevent_multiple_submit'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $("#UserGroupId").chosen({width: 424});
        $("#UserGroupId_chosen").removeAttr("style");
        $(".btnSaveUser").unbind("click").click(function(event){
            $("#UserEditProfileForm").submit();
        });
        $("#UserEditProfileForm").validationEngine('attach', {
            isOverflown: true,
            overflownDIV: ".ui-tabs-panel"
        });
        $("#UserEditProfileForm").ajaxForm({
            beforeSubmit: function(arr, $form, options) {
                $(".txtSave").html("<?php echo ACTION_LOADING; ?>");
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
            },
            success: function(result) {
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                var self=$("#UserEditProfileForm").parent();
                var relative=self.parent().find(".leftPanel");
                self.hide();
                relative.show("slide", { direction: "left" }, 500);
                oCache.iCacheLower = -1;
                oTableUser.fnDraw(false);
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
        $(".btnBackUser").unbind("click").click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableUser.fnDraw(false);
            var rightPanel = $(this).parent().parent().parent().parent();
            var leftPanel  = rightPanel.parent().find(".leftPanel");
            rightPanel.hide();rightPanel.html("");
            leftPanel.show("slide", { direction: "left" }, 500);
        });

        // Set Form Container Height
        var windowHeight  = $(window).height();
        var headerHeight  = $('.ui-layout-north').outerHeight(true);
        var tabsNavHeight = $('.ui-tabs-nav').outerHeight(true);
        var formHeight    = $('#userFormHeader').outerHeight(true);
        var formContainer = windowHeight - headerHeight - tabsNavHeight - formHeight - 100;
        $('#userFormContainer').css({
            'height': formContainer + 'px',
            'max-height': formContainer + 'px'
        });
    });
</script>
<div class="filter-container" id="userFormHeader">
    <div class="filter-row">
        <div class="filter-form-group">
            <button type="button" class="form-btn btnBackUser">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
        <div class="filter-form-group">
            <button type="button" class="form-btn btnSaveUser">
                <i class="fas fa-save"></i>
                <span><?php echo ACTION_SAVE; ?></span>
            </button>
        </div>
    </div>
</div>
<?php 
echo $this->Form->create('User', array('class' => 'form-table-container'));
echo $this->Form->input('id'); 
?>
<div class="form-container" id="userFormContainer">
<fieldset>
        <legend><?php __(USER_LOGIN_INFO); ?></legend>
        <table class="form-table">
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="UserUsername" class="form-label">
                        <?php echo USER_USER_NAME; ?>
                        <span class="form-required">*</span>
                    </label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('username', array(
                            'class' => 'form-input validate[required]',
                            'id' => 'UserUsername'
                        )); ?>
                    </div>
                </td>
            </tr>

            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="UserPassword" class="form-label">
                        <?php echo USER_PASSWORD; ?>
                    </label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->password('password', array(
                            'class' => 'form-input',
                            'value'=>'',
                            'id' => 'UserPassword'
                        )); ?>
                    </div>
                </td>
            </tr>

            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="UserConfirmPassword" class="form-label">
                        <?php echo USER_CONFIRM_PASSWORD; ?>
                    </label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->password('confirm_password', array(
                            'class' => 'form-input validate[equals[UserPassword]]',
                            'id' => 'UserConfirmPassword',
                            'name' => 'data[confirm_password]'
                        )); ?>
                    </div>
                </td>
            </tr>
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="UserGroupId" class="form-label">
                        <?php echo USER_GROUP; ?>
                        <span class="form-required">*</span>
                    </label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php
                        $groupId=null;
                        $queryGroupId=mysql_query("SELECT group_id FROM user_groups WHERE user_id=".$this->data['User']['id']);
                        while($dataGroupId=mysql_fetch_array($queryGroupId)){
                            $groupId[]=$dataGroupId['group_id'];
                        }
                        echo $this->Form->input('group_id', array(
                            'label' => false,
                            'multiple' => 'multiple',
                            'data-placeholder' => INPUT_SELECT,
                            'class' => 'form-select',
                            'style' => 'width: 424px;',
                            'id' => 'UserGroupId',
                            'selected' => $groupId
                        )); ?>
                    </div>
                </td>
            </tr>
        </table>
    </fieldset>
</div>
<div style="clear: both;"></div>
<?php echo $this->Form->end(); ?>