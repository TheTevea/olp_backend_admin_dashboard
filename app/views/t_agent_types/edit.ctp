<?php echo $this->element('prevent_multiple_submit'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $(".btnSaveTAgentType").unbind("click").click(function(event){
            $("#TAgentTypeEditForm").submit();
        });
        $("#TAgentTypeEditForm").validationEngine('attach', {
            isOverflown: true,
            overflownDIV: ".ui-tabs-panel"
        });
        $("#TAgentTypeEditForm").ajaxForm({
            beforeSubmit: function(arr, $form, options) {
                $(".txtSaveTAgentType").html("<?php echo ACTION_LOADING; ?>");
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
            },
            success: function(result) {
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                $(".btnBackTAgentType").click();
                // alert message
                if(result != '<?php echo MESSAGE_DATA_HAS_BEEN_SAVED; ?>' && result != '<?php echo MESSAGE_DATA_COULD_NOT_BE_SAVED; ?>'){
                    createSysAct('TAgentType', 'Edit', 2, result);
                    $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_PROBLEM; ?></p>');
                }else {
                    createSysAct('TAgentType', 'Edit', 1, '');
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
        $(".btnBackTAgentType").unbind('click').click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableTAgentType.fnDraw(false);
            var rightPanel=$(this).parent().parent().parent().parent();
            var leftPanel=rightPanel.parent().find(".leftPanel");
            rightPanel.hide();rightPanel.html("");
            leftPanel.show("slide", { direction: "left" }, 500);
        });
    });
</script>
<!-- Button Header (Format#4) -->
<div class="filter-container" id="tAgentTypeFormHeader">
    <div class="filter-row">
        <div class="filter-form-group">
            <button type="button" class="form-btn btnBackTAgentType">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
        <div class="filter-form-group">
            <button type="submit" class="form-btn btnSaveTAgentType">
                <i class="fas fa-save"></i>
                <span class="txtSaveTAgentType"><?php echo ACTION_SAVE; ?></span>
            </button>
        </div>
    </div>
</div>

<!-- Form Container (Format#4) -->
<?php 
echo $this->Form->create('TAgentType', array('class' => 'form-table-container'));
echo $this->Form->input('id');  ?>
<div class="form-container" id="tAgentTypeFormContainer">
    <fieldset>
        <legend><?php __(MENU_AGENT_TYPE_INFO); ?></legend>
        <table class="form-table">
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TAgentTypeName"><?php echo TABLE_NAME; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('name', array(
                            'class' => 'form-input validate[required]',
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