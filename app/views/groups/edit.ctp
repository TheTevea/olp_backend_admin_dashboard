<?php echo $this->element('prevent_multiple_submit'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $(".btnSaveGroup").unbind("click").click(function(event){
            $("#GroupEditForm").submit();
        });
        $("#GroupEditForm").validationEngine('attach', {
            isOverflown: true,
            overflownDIV: ".ui-tabs-panel"
        });
        $("#GroupEditForm").ajaxForm({
            beforeSerialize: function($form, options) {
                listbox_selectall('d', true);
            },
            beforeSubmit: function(arr, $form, options) {
                $(".txtSave").html("<?php echo ACTION_LOADING; ?>");
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
            },
            success: function(result) {
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                $(".btnBackGroup").click();
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
        $(".moduleTypeBody").slideDown();
        $(".moduleType").prepend("<img alt='' src='<?php echo $this->webroot; ?>img/minus.gif' class='btnPlusMinus' /> ");
        $("#btnShowAll").click(function(event){
            event.preventDefault();
            $("img.btnPlusMinus").attr("src", "<?php echo $this->webroot; ?>img/minus.gif");
            $(".moduleTypeBody").slideDown();
        });
        $("#btnHideAll").click(function(event){
            event.preventDefault();
            $("img.btnPlusMinus").attr("src", "<?php echo $this->webroot; ?>img/plus.gif");
            $(".moduleTypeBody").slideUp();
        });
        $(".moduleType").click(function(){
            if($(".moduleTypeBody[title=" + $(this).attr("title") + "]").is(':visible')==false){
                $("img.btnPlusMinus", this).attr("src", "<?php echo $this->webroot; ?>img/minus.gif");
            }else{
                $("img.btnPlusMinus", this).attr("src", "<?php echo $this->webroot; ?>img/plus.gif");
            }
            $(".moduleTypeBody[title=" + $(this).attr("title") + "]").slideToggle();
        });
        $(".module").mouseover(function(){
            $(this).css("background", "#f4ffab");
        });
        $(".module").mouseout(function(){
            $(this).css("background", "none");
        });
        $(".btnAllRights").change(function(){
            if($(this).is(":checked")){
                $(':checkbox').attr('checked', true);
            } else {
                $(':checkbox[name!="module1"]').attr('checked', false);
            }
        });
        $(".btnFullRights").change(function(){
            if($(this).is(":checked")){
                $(".moduleType" + $(this).attr("alt")).attr('checked', true);
            } else {
                $(".moduleType" + $(this).attr("alt")+"[name!='module1']").attr('checked', false);
            }
        });
        $(".btnBackGroup").unbind("click").click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableGroup.fnDraw(false);
            var rightPanel = $(this).parent().parent().parent().parent();
            var leftPanel  = rightPanel.parent().find(".leftPanel");
            rightPanel.hide();rightPanel.html("");
            leftPanel.show("slide", { direction: "left" }, 500);
        });

        // Set Form Container Height
        var windowHeight  = $(window).height();
        var headerHeight  = $('.ui-layout-north').outerHeight(true);
        var tabsNavHeight = $('.ui-tabs-nav').outerHeight(true);
        var formHeight    = $('#groupFormHeader').outerHeight(true);
        var formContainer = windowHeight - headerHeight - tabsNavHeight - formHeight - 100;
        $('#groupFormContainer').css({
            'height': formContainer + 'px',
            'max-height': formContainer + 'px'
        });
    });
</script>
<div class="filter-container" id="groupFormHeader">
    <div class="filter-row">
        <div class="filter-form-group">
            <button type="button" class="form-btn btnBackGroup">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
        <div class="filter-form-group">
            <button type="button" class="form-btn btnSaveGroup">
                <i class="fas fa-save"></i>
                <span class="txtSaveGroup"><?php echo ACTION_SAVE; ?></span>
            </button>
        </div>
    </div>
</div>
<?php 
echo $this->Form->create('Group', array('class' => 'form-table-container')); 
echo $this->Form->input('id'); 
?>
<div class="form-container" id="groupFormContainer">
    <fieldset>
        <legend><?php __(MENU_GROUP_MANAGEMENT_INFO); ?></legend>
        <table class="form-table">
            <tr class="form-row">
                <td class="form-label-cell"><label for="GroupName"><?php echo TABLE_NAME; ?> <span class="red">*</span> :</label></td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('name', array('class' => 'form-input validate[required]')); ?>
                    </div>
                </td>
            </tr>
        </table>
    </fieldset>
    <br />
    <fieldset>
        <legend><?php __(GENERAL_MEMBER); ?></legend>
        <table class="form-table">
            <tr>
                <th>Available Users:</th>
                <th></th>
                <th>Member of Group:</th>
            </tr>
            <tr class="form-row" style="border-bottom: none;">
                <td class="form-input-cell" style="vertical-align: top; width: 300px;">
                    <select id="s" multiple="multiple" style="width: 300px; height: 200px;">
                        <?php
                        if($user['User']['type'] != 1){
                            $querySource = mysql_query("SELECT id,CONCAT(first_name,' ',last_name) AS full_name FROM users WHERE is_active=1 AND offline_project_id = ".$user['User']['offline_project_id']." AND id NOT IN (SELECT user_id FROM user_groups WHERE group_id=" . $this->data['Group']['id'].")");
                        } else {
                            $querySource = mysql_query("SELECT id,CONCAT(first_name,' ',last_name) AS full_name FROM users WHERE is_active=1 AND id NOT IN (SELECT user_id FROM user_groups WHERE group_id=" . $this->data['Group']['id'].")");
                        }
                        while ($dataSource = mysql_fetch_array($querySource)) {
                        ?>
                            <option value="<?php echo $dataSource['id']; ?>"><?php echo $dataSource['full_name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td class="form-input-cell" style="vertical-align: middle; text-align: center;">
                    <img alt="" src="<?php echo $this->webroot; ?>img/button/right.png" style="cursor: pointer;" onclick="listbox_moveacross('s', 'd')" />
                    <br /><br />
                    <img alt="" src="<?php echo $this->webroot; ?>img/button/left.png" style="cursor: pointer;" src="" style="cursor: pointer;" onclick="listbox_moveacross('d', 's')" />
                </td>
                <td class="form-input-cell" style="vertical-align: top;">
                    <select id="d" name="data[Group][user_id][]" multiple="multiple" style="width: 300px; height: 200px;">
                        <?php
                        $queryDestination = mysql_query("SELECT id,CONCAT(first_name,' ',last_name) AS full_name FROM users WHERE is_active=1 AND id IN (SELECT user_id FROM user_groups WHERE group_id=" . $this->data['Group']['id'].")");
                        while ($dataDestination = mysql_fetch_array($queryDestination)) {
                        ?>
                        <option value="<?php echo $dataDestination['id']; ?>"><?php echo $dataDestination['full_name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
        </table>
    </fieldset>
    <br />
    <fieldset>
        <legend><?php __(GENERAL_PERMISSION); ?> (<a href="" id="btnShowAll">show all</a> | <a href="" id="btnHideAll">hide all</a>)</legend>
        <div class="moduleType" title="AllRights">All rights</div>
        <div class="moduleTypeBody" title="AllRights">
            <div class="module">
                <div style="float: left;">All rights</div>
                <div style="float: right;"><input type="checkbox" class="btnAllRights" /></div>
                <div style="clear: both;"></div>
            </div>
        </div>
        <?php
        if($user['User']['type'] != 1){ // Customer
            $modTypeCon = " AND type IN (1,2)";
            $modCon = " AND type IN (1,2)";
        } else {
            $modTypeCon = " AND type IN (1,3)";
            $modCon = " AND type IN (1,3)";
        }
        $queryType = mysql_query("SELECT id,name FROM module_types WHERE status = 1".$modTypeCon." ORDER BY ordering");
        while ($dataType = mysql_fetch_array($queryType)) {
            $rand = rand();
        ?>
        <div class="moduleType" title="<?php echo $rand; ?>"><?php echo $dataType['name']; ?></div>
        <div class="moduleTypeBody" title="<?php echo $rand; ?>">
            <div class="module">
                <div style="float: left;">Full rights</div>
                <div style="float: right;"><input type="checkbox" class="btnFullRights" alt="<?php echo $dataType['id']; ?>" /></div>
                <div style="clear: both;"></div>
            </div>
            <?php
            $queryModule = mysql_query("SELECT id,name,(SELECT COUNT(module_id) FROM permissions WHERE module_id = m.id AND group_id=" . $this->data['Group']['id'] . ") AS chk FROM modules m WHERE module_type_id=" . $dataType['id'] . " AND status = 1".$modCon." ORDER BY ordering");
            while ($dataModule = mysql_fetch_array($queryModule)) {
            ?>
            <div class="module">
                <div style="float: left;"><?php echo $dataModule['name']; ?></div>
                <div style="float: right;"><input type="checkbox" name="module<?php echo $dataModule['id']; ?>" class="moduleType<?php echo $dataType['id']; ?>" <?php echo $dataModule['id']==1 || $dataModule['chk'] != 0 ? 'checked="checked"' : '' ?> <?php echo $dataModule['id']==1?'onclick="this.checked=!this.checked;"':''; ?> /></div>
                <div style="clear: both;"></div>
            </div>
            <?php } ?>
        </div>
        <?php 
        }
        ?>
    </fieldset>
</div>
<div style="clear: both;"></div>
<?php echo $this->Form->end(); ?>