<?php echo $this->element('prevent_multiple_submit'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $(".btnSaveTRoute").unbind("click").click(function(event){
            $("#TRouteAddForm").submit();
        });
        $("#TRouteAddForm").validationEngine('attach', {
            isOverflown: true,
            overflownDIV: ".ui-tabs-panel"
        });
        $("#TRouteAddForm").ajaxForm({
            beforeSubmit: function(arr, $form, options) {
                $(".txtSaveTRoute").html("<?php echo ACTION_LOADING; ?>");
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
            },
            success: function(result) {
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                $(".btnBackTRoute").click();
                // alert message
                if(result != '<?php echo MESSAGE_DATA_HAS_BEEN_SAVED; ?>' && result != '<?php echo MESSAGE_DATA_COULD_NOT_BE_SAVED; ?>'){
                    createSysAct('TRoute', 'Add', 2, result);
                    $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_PROBLEM; ?></p>');
                }else {
                    createSysAct('TRoute', 'Add', 1, '');
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
        $(".btnBackTRoute").click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableTRoute.fnDraw(false);
            var rightPanel=$(this).parent().parent().parent().parent();
            var leftPanel=rightPanel.parent().find(".leftPanel");
            rightPanel.hide();rightPanel.html("");
            leftPanel.show("slide", { direction: "left" }, 500);
        });

        // Set Form Container Height
        var windowHeight  = $(window).height();
        var headerHeight  = $('.ui-layout-north').outerHeight(true);
        var tabsNavHeight = $('.ui-tabs-nav').outerHeight(true);
        var formHeight    = $('#tRouteFormHeader').outerHeight(true);
        var formContainer = windowHeight - headerHeight - tabsNavHeight - formHeight - 100;
        $('#tRouteFormContainer').css({
            'height': formContainer + 'px',
            'max-height': formContainer + 'px'
        });
    });
</script>
<div class="filter-container" id="tRouteFormHeader">
    <div class="filter-row">
        <div class="filter-form-group">
            <button type="button" class="form-btn btnBackTRoute">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
        <div class="filter-form-group">
            <button type="button" class="form-btn btnSaveTRoute">
                <i class="fas fa-save"></i>
                <span class="txtSaveTRoute"><?php echo ACTION_SAVE; ?></span>
            </button>
        </div>
    </div>
</div>
<?php echo $this->Form->create('TRoute', array('class' => 'form-table-container')); ?>
<div class="form-container" id="tRouteFormContainer">
    <fieldset>
        <legend><?php __(MENU_ROUTE_INFO); ?></legend>
        <table class="form-table">
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TRouteName"><?php echo TABLE_NAME; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('name', array('class'=>'form-input validate[required]', 'label' => false, 'div' => false)); ?>
                    </div>
                </td>
            </tr>
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TRouteDescription"><?php echo GENERAL_DESCRIPTION; ?> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->textarea('description', array('class' => 'form-input', 'style' => 'width: 390px; height: 40px;', 'label' => false, 'div' => false)); ?>
                    </div>
                </td>
            </tr>
        </table>
    </fieldset>
</div>
<div style="clear: both;"></div>
<?php echo $this->Form->end(); ?>