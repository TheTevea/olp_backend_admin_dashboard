<?php echo $this->element('prevent_multiple_submit'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $("#UserMainBranchId").chosen({width: 250});
        $("#UserGroupId").chosen({width: 424});
        $("#UserMainBranchId_chosen, #UserGroupId_chosen").removeAttr("style");
        $(".btnSaveUser").unbind("click").click(function(event){
            $("#UserAddForm").submit();
        });
        $("#UserAddForm").validationEngine('attach', {
            isOverflown: true,
            overflownDIV: ".ui-tabs-panel"
        });
        $("#UserAddForm").ajaxForm({
            beforeSerialize: function($form, options) {
                listbox_selectall('userBranchSelected', true);
                listbox_selectall('userCompanySelected', true);
            },
            beforeSubmit: function(arr, $form, options) {
                $(".txtUserSave").html("<?php echo ACTION_LOADING; ?>");
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
            },
            success: function(result) {
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                if(result == "<?php echo MESSAGE_DATA_ALREADY_EXISTS_IN_THE_SYSTEM; ?>"){
                    $(".txtUserSave").html("<?php echo ACTION_SAVE; ?>");
                    $(".btnSaveUser").removeAttr("disabled");
                } else {
                    $(".btnBackUser").click();
                }
                // alert message
                $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span>'+result+'</p>');
                $("#dialog").dialog({
                    title: '<?php echo DIALOG_INFORMATION; ?>',
                    resizable: false,
                    modal: true,
                    width: 'auto',
                    height: 'auto',
                    open: function(event, ui){
                        $(".ui-dialog-buttonpane").show();
                    },
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
        <?php
        if($user['User']['type'] == 1){
        ?>
        $("#divUserRight").hide();
        $("#UserType").unbind("click").click(function(){
            $("#divProject, #divIsAdmin, #divUserRight").hide();
            $("#UserOfflineProjectId, #UserIsAdmin").removeClass("validate[required]");
            if($(this).val() == "2"){
                $("#divProject, #divIsAdmin").show();
                $("#UserOfflineProjectId, #UserIsAdmin").addClass("validate[required]");
            } else if ($(this).val() == "1"){
                $("#divUserRight").show();
            }
        });
        <?php
        }
        ?>
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
echo $this->Form->hidden('type', array('value' => 2));
?>
<div class="form-container" id="userFormContainer">
    <fieldset>
        <legend><?php __(USER_USER_INFO); ?></legend>
        <table class="form-table">
            <?php if($user['User']['type'] != 1): ?>
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="UserMainBranchId" class="form-label">
                        <?php echo MENU_MAIN_BRANCH; ?>
                    </label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->input('main_branch_id', array('empty' => INPUT_SELECT, 'div' => false, 'label' => false, 'class' => 'form-select')); ?>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
            
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="UserFirstName" class="form-label">
                        <?php echo TABLE_FIRST_NAME; ?>
                        <span class="form-required">*</span>
                    </label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('first_name', array('class' => 'form-input validate[required]', 'id' => 'UserFirstName')); ?>
                    </div>
                </td>
            </tr>
            
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="UserLastName" class="form-label">
                        <?php echo TABLE_LAST_NAME; ?>
                        <span class="form-required">*</span>
                    </label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('last_name', array('class' => 'form-input validate[required]', 'id' => 'UserLastName')); ?>
                    </div>
                </td>
            </tr>
            
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="UserTelephone" class="form-label">
                        <?php echo TABLE_TELEPHONE; ?>
                    </label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('telephone', array('class' => 'form-input', 'id' => 'UserTelephone')); ?>
                    </div>
                </td>
            </tr>
            
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="UserEmail" class="form-label">
                        <?php echo TABLE_EMAIL; ?>
                    </label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('email', array('class' => 'form-input validate[optional,custom[email]]', 'id' => 'UserEmail')); ?>
                    </div>
                </td>
            </tr>
        </table>
    </fieldset>
    <br />
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
                        <span class="form-required">*</span>
                    </label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->password('password', array(
                            'class' => 'form-input validate[required]',
                            'id' => 'UserPassword'
                        )); ?>
                    </div>
                </td>
            </tr>

            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="UserConfirmPassword" class="form-label">
                        <?php echo USER_CONFIRM_PASSWORD; ?>
                        <span class="form-required">*</span>
                    </label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->password('confirm_password', array(
                            'class' => 'form-input validate[required,equals[UserPassword]]',
                            'id' => 'UserConfirmPassword',
                            'name' => 'data[confirm_password]'
                        )); ?>
                    </div>
                </td>
            </tr>

            <?php if($user['User']['type'] == 1): ?>
            <tr id="divProject" class="form-row" style="display: none;">
                <td class="form-label-cell">
                    <label for="UserOfflineProjectId" class="form-label">
                        <?php echo 'Project'; ?>
                        <span class="form-required">*</span>
                    </label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->input('offline_project_id', array(
                            'label' => false,
                            'empty' => INPUT_SELECT,
                            'div' => false,
                            'class' => 'form-select',
                            'id' => 'UserOfflineProjectId'
                        )); ?>
                    </div>
                </td>
            </tr>
            <?php endif; ?>

            <tr class="form-row" <?php if($user['User']['type'] == 1){ ?>id="divUserRight"<?php } ?>>
                <td class="form-label-cell">
                    <label for="UserGroupId" class="form-label">
                        <?php echo USER_GROUP; ?>
                        <span class="form-required">*</span>
                    </label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->input('group_id', array(
                            'label' => false,
                            'multiple' => 'multiple',
                            'data-placeholder' => INPUT_SELECT,
                            'class' => 'form-select chzn-select',
                            'style' => 'width: 424px;',
                            'id' => 'UserGroupId'
                        )); ?>
                    </div>
                </td>
            </tr>
        </table>
    </fieldset>
    <br />
    <fieldset style="width: 48%; float: left;">
        <legend><?php __(MENU_COMPANY_MANAGEMENT); ?></legend>
        <table class="form-table">
            <tr>
                <th>Available:</th>
                <th></th>
                <th>Member of:</th>
            </tr>
            <tr class="form-row" style="border-bottom: none;">
                <td class="form-input-cell" style="vertical-align: top;">
                    <select id="userCompany" class="form-select-multi" multiple="multiple" style="width: 280px; height: 200px;">
                        <?php
                        if($user['User']['type'] != 1){
                            $queryCom = mysql_query("SELECT id, name FROM companies WHERE is_active = 1 AND offline_project_id = ".$user['User']['offline_project_id'].";");
                        } else {
                            $queryCom = mysql_query("SELECT id, name FROM companies WHERE is_active = 1;");
                        }
                        while($dataCom=mysql_fetch_array($queryCom)){
                        ?>
                        <option value="<?php echo $dataCom['id']; ?>"><?php echo $dataCom['name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td class="form-input-cell" style="vertical-align: middle; text-align: center;">
                    <img alt="" src="<?php echo $this->webroot; ?>img/button/right.png" style="cursor: pointer;" onclick="listbox_moveacross('userCompany', 'userCompanySelected')" />
                    <br /><br />
                    <img alt="" src="<?php echo $this->webroot; ?>img/button/left.png" style="cursor: pointer;" src="" style="cursor: pointer;" onclick="listbox_moveacross('userCompanySelected', 'userCompany')" />
                </td>
                <td class="form-input-cell" style="vertical-align: top;">
                    <select id="userCompanySelected" class="form-select-multi" name="data[User][company_id][]" multiple="multiple" style="width: 280px; height: 200px;"></select>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset style="width: 48%; float: left;">
        <legend><?php __(MENU_BRANCH_INFO); ?></legend>
        <table class="form-table">
            <tr>
                <th>Available:</th>
                <th></th>
                <th>Member of:</th>
            </tr>
            <tr class="form-row" style="border-bottom: none;">
                <td class="form-input-cell" style="vertical-align: top;">
                    <select id="userBranch" class="form-select-multi" multiple="multiple" style="width: 280px; height: 200px;">
                        <?php
                        if($user['User']['type'] != 1){
                            $queryBranch = mysql_query("SELECT id,name FROM branches WHERE is_active = 1 AND offline_project_id = ".$user['User']['offline_project_id'].";");
                        } else {
                            $queryBranch = mysql_query("SELECT id,name FROM branches WHERE is_active=1");
                        }
                        while($dataBranch = mysql_fetch_array($queryBranch)){
                        ?>
                        <option value="<?php echo $dataBranch['id']; ?>"><?php echo $dataBranch['name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td class="form-input-cell" style="vertical-align: middle; text-align: center;">
                    <img alt="" src="<?php echo $this->webroot; ?>img/button/right.png" style="cursor: pointer;" onclick="listbox_moveacross('userBranch', 'userBranchSelected')" />
                    <br /><br />
                    <img alt="" src="<?php echo $this->webroot; ?>img/button/left.png" style="cursor: pointer;" src="" style="cursor: pointer;" onclick="listbox_moveacross('userBranchSelected', 'userBranch')" />
                </td>
                <td class="form-input-cell" style="vertical-align: top;">
                    <select id="userBranchSelected" class="form-select-multi" name="data[User][branch_id][]" multiple="multiple" style="width: 280px; height: 200px;"></select>
                </td>
            </tr>
        </table>
    </fieldset>
    <div style="clear: both;"></div>
</div>
<?php echo $this->Form->end(); ?>