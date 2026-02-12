<?php echo $this->element('prevent_multiple_submit'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $("#TDropOffBranchId").chosen({width: 250});
        $("#TDropOffBranchId_chosen").removeAttr("style");
        $(".btnSaveTDropOff").unbind("click").click(function(event){
            $("#TDropOffAddForm").submit();
        });
        $("#TDropOffAddForm").validationEngine('attach', {
            isOverflown: true,
            overflownDIV: ".ui-tabs-panel"
        });
        $("#TDropOffAddForm").ajaxForm({
            beforeSubmit: function(arr, $form, options) {
                $(".txtSaveTDropOff").html("<?php echo ACTION_LOADING; ?>");
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
            },
            success: function(result) {
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                $(".btnBackTDropOff").click();
                // alert message
                if(result != '<?php echo MESSAGE_DATA_HAS_BEEN_SAVED; ?>' && result != '<?php echo MESSAGE_DATA_COULD_NOT_BE_SAVED; ?>' && result != '<?php echo MESSAGE_DATA_ALREADY_EXISTS_IN_THE_SYSTEM; ?>'){
                    createSysAct('TDropOff', 'Add', 2, result);
                    $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_PROBLEM; ?></p>');
                }else {
                    createSysAct('TDropOff', 'Add', 1, '');
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
        $(".btnBackTDropOff").unbind('click').click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableTDropOff.fnDraw(false);
            var rightPanel=$(this).parent().parent().parent().parent();
            var leftPanel=rightPanel.parent().find(".leftPanel");
            rightPanel.hide();rightPanel.html("");
            leftPanel.show("slide", { direction: "left" }, 500);
        });

        // Set Form Container Height
        var windowHeight  = $(window).height();
        var headerHeight  = $('.ui-layout-north').outerHeight(true);
        var tabsNavHeight = $('.ui-tabs-nav').outerHeight(true);
        var formHeight    = $('#tDropOffFormHeader').outerHeight(true);
        var formContainer = windowHeight - headerHeight - tabsNavHeight - formHeight - 100;
        $('#tDropOffFormContainer').css({
            'height': formContainer + 'px',
            'max-height': formContainer + 'px'
        });
    });
</script>
<!-- Button Header (Format#4) -->
<div class="filter-container" id="tDropOffFormHeader">
    <div class="filter-row">
        <div class="filter-form-group">
            <button type="button" class="form-btn btnBackTDropOff">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
        <div class="filter-form-group">
            <button type="submit" class="form-btn btnSaveTDropOff">
                <i class="fas fa-save"></i>
                <span class="txtSaveTDropOff"><?php echo ACTION_SAVE; ?></span>
            </button>
        </div>
    </div>
</div>

<!-- Form Container (Format#4) -->
<?php echo $this->Form->create('TDropOff', array('class' => 'form-table-container')); ?>
<div class="form-container" id="tDropOffFormContainer">
    <fieldset>
        <legend><?php __(MENU_ADD_NEW_DROP_OFF); ?></legend>
        <table class="form-table">
            <!-- Branch Selection -->
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TDropOffBranchId"><?php echo TABLE_BRANCH; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->input('branch_id', array(
                            'class' => 'form-input validate[required]',
                            'div' => false,
                            'label' => false,
                            'empty' => INPUT_SELECT
                        )); ?>
                    </div>
                </td>
            </tr>

            <!-- Name Fields -->
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TDropOffNameKh"><?php echo TABLE_NAME; ?> (Khmer) <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('name_kh', array(
                            'class' => 'form-input validate[required]',
                            'label' => false,
                            'div' => false
                        )); ?>
                    </div>
                </td>
            </tr>

            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TDropOffName"><?php echo TABLE_NAME; ?> (English) <span class="red">*</span> :</label>
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

            <!-- Contact Information -->
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TDropOffContact"><?php echo TABLE_CONTACT_NAME; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('contact', array(
                            'class' => 'form-input validate[required]',
                            'label' => false,
                            'div' => false
                        )); ?>
                    </div>
                </td>
            </tr>

            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TDropOffTelephone"><?php echo TABLE_TELEPHONE; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('telephone', array(
                            'class' => 'form-input validate[required]',
                            'label' => false,
                            'div' => false
                        )); ?>
                    </div>
                </td>
            </tr>

            <!-- Address Fields -->
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TDropOffAddressKh"><?php echo TABLE_ADDRESS; ?> (Khmer) <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->textarea('address_kh', array(
                            'class' => 'form-textarea validate[required]',
                            'label' => false,
                            'div' => false,
                            'rows' => 3
                        )); ?>
                    </div>
                </td>
            </tr>

            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TDropOffAddress"><?php echo TABLE_ADDRESS; ?> (English) <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->textarea('address', array(
                            'class' => 'form-textarea validate[required]',
                            'label' => false,
                            'div' => false,
                            'rows' => 3
                        )); ?>
                    </div>
                </td>
            </tr>

            <!-- Coordinates -->
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TDropOffLongs"><?php echo TABLE_LONG; ?> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('longs', array(
                            'class' => 'form-input',
                            'label' => false,
                            'div' => false
                        )); ?>
                    </div>
                </td>
            </tr>

            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TDropOffLats"><?php echo TABLE_LAT; ?> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('lats', array(
                            'class' => 'form-input',
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