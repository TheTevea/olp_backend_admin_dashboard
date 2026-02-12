<?php 
echo $this->element('prevent_multiple_submit'); 
?>
<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $("#MainBranchTDestinationId").chosen({width: 250});
        $("#MainBranchTDestinationId_chosen").removeAttr("style");
        $(".btnSaveMainBranch").unbind("click").click(function(event){
            $("#MainBranchEditForm").submit();
        });
        $("#MainBranchEditForm").validationEngine('attach', {
            isOverflown: true,
            overflownDIV: ".ui-tabs-panel"
        });
        $("#MainBranchEditForm").ajaxForm({
            beforeSerialize: function($form, options) {
                listbox_selectall('userMainBranchSelected', true);
            },
            beforeSubmit: function(arr, $form, options) {
                $(".txtSaveMainBranch").html("<?php echo ACTION_LOADING; ?>");
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
            },
            success: function(result) {
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                // alert message
                if(result != '<?php echo MESSAGE_DATA_INVALID; ?>' && result != '<?php echo MESSAGE_DATA_HAS_BEEN_SAVED; ?>' && result != '<?php echo MESSAGE_DATA_COULD_NOT_BE_SAVED; ?>' && result != '<?php echo MESSAGE_DATA_ALREADY_EXISTS_IN_THE_SYSTEM; ?>'){
                    $(".btnBackMainBranch").click();
                    createSysAct('Main Branch', 'Edit', 2, result);
                    $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_PROBLEM; ?></p>');
                }else {
                    createSysAct('Main Branch', 'Edit', 1, '');
                    if(result == '<?php echo MESSAGE_DATA_COULD_NOT_BE_SAVED; ?>' || result == '<?php echo MESSAGE_DATA_ALREADY_EXISTS_IN_THE_SYSTEM; ?>'){
                        $(".btnSaveMainBranch").removeAttr("disabled");
                        $(".txtSaveMainBranch").html("<?php echo ACTION_SAVE; ?>");
                    } else {
                        $(".btnBackMainBranch").click();
                    }
                    // alert message
                    $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span>'+result+'</p>');
                }
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
        $(".btnBackMainBranch").unbind('click').click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableMainBranch.fnDraw(false);
            var rightPanel=$(this).parent().parent().parent().parent();
            var leftPanel=rightPanel.parent().find(".leftPanel");
            rightPanel.hide();rightPanel.html("");
            leftPanel.show("slide", { direction: "left" }, 500);
        });

        // Set Form Container Height
        var windowHeight  = $(window).height();
        var headerHeight  = $('.ui-layout-north').outerHeight(true);
        var tabsNavHeight = $('.ui-tabs-nav').outerHeight(true);
        var formHeight    = $('#mainBranchFormHeader').outerHeight(true);
        var formContainer = windowHeight - headerHeight - tabsNavHeight - formHeight - 100;
        $('#mainBranchFormContainer').css({
            'height': formContainer + 'px',
            'max-height': formContainer + 'px'
        });
    });
</script>
<div class="filter-container" id="mainBranchFormHeader">
    <div class="filter-row">
        <div class="filter-form-group">
            <button type="button" class="form-btn btnBackMainBranch">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
        <div class="filter-form-group">
            <button type="button" class="form-btn btnSaveMainBranch">
                <i class="fas fa-save"></i>
                <span class="txtSaveMainBranch"><?php echo ACTION_SAVE; ?></span>
            </button>
        </div>
    </div>
</div>
<br />
<?php 
echo $this->Form->create('MainBranch'); 
echo $this->Form->input('id');
?>
<div class="form-container" id="mainBranchFormContainer">
    <fieldset>
        <legend><?php __(MENU_MAIN_BRANCH_INFO); ?></legend>
        <table class="form-table">
            <tr class="form-row">
                <td class="form-label-cell"><label for="MainBranchName"><?php echo TABLE_NAME; ?> <span class="red">*</span> :</label></td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('name', array('class'=>'form-input validate[required]', 'label' => false, 'div' => false)); ?>
                    </div>
                </td>
            </tr>
            <tr class="form-row">
                <td class="form-label-cell"><label for="MainBranchTDestinationId"><?php echo TABLE_ORIGIN; ?> <span class="red">*</span> :</label></td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->input('t_destination_id', array('class'=>'form-select validate[required]', 'label' => false, 'empty' => INPUT_SELECT)); ?>
                    </div>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset>
        <legend><?php __(USER_USER_INFO); ?></legend>
        <table class="form-table">
            <tr>
                <th>Available:</th>
                <th></th>
                <th>Members:</th>
            </tr>
            <tr class="form-row" style="border-bottom: none;">
                <td class="form-input-cell" style="vertical-align: top; width: 300px;">
                    <select id="userMainBranch" multiple="multiple" style="width: 300px; height: 200px;">
                        <?php
                        if($user['User']['type'] == 2){
                            if(!empty($user['User']['offline_project_id'])){
                                $userCon = " AND offline_project_id = ".$user['User']['offline_project_id'];
                            } else {
                                $userCon = " AND offline_project_id = 0";
                            }
                        } else {
                            $userCon = "";
                        }
                        $querySource=mysql_query("SELECT id,CONCAT(first_name,' ',last_name) AS full_name FROM users WHERE is_active=1 AND (main_branch_id IS NULL OR main_branch_id = '')".$userCon);
                        while($dataSource=mysql_fetch_array($querySource)){
                        ?>
                        <option value="<?php echo $dataSource['id']; ?>"><?php echo $dataSource['full_name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td class="form-input-cell" style="vertical-align: middle; text-align: center;">
                    <img alt="" src="<?php echo $this->webroot; ?>img/button/right.png" style="cursor: pointer;" onclick="listbox_moveacross('userMainBranch', 'userMainBranchSelected')" />
                    <br /><br />
                    <img alt="" src="<?php echo $this->webroot; ?>img/button/left.png" style="cursor: pointer;" src="" style="cursor: pointer;" onclick="listbox_moveacross('userMainBranchSelected', 'userMainBranch')" />
                </td>
                <td class="form-input-cell" style="vertical-align: top;">
                    <select id="userMainBranchSelected" name="data[MainBranch][user_id][]" multiple="multiple" style="width: 300px; height: 200px;">
                        <?php
                        $queryDestination=mysql_query("SELECT id, CONCAT(first_name,' ',last_name) AS full_name FROM users WHERE main_branch_id = ".$this->data['MainBranch']['id'].$userCon);
                        while($dataDestination=mysql_fetch_array($queryDestination)){
                        ?>
                        <option value="<?php echo $dataDestination['id']; ?>"><?php echo $dataDestination['full_name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
        </table>
    </fieldset>
</div>
<div style="clear: both;"></div>
<?php echo $this->Form->end(); ?>