<?php 
// Prevent Button Submit
echo $this->element('prevent_multiple_submit'); 
$sqlSym = mysql_query("SELECT symbol FROM currency_centers WHERE id = (SELECT currency_center_id FROM branches WHERE id IN (SELECT branch_id FROM user_branches WHERE user_id = ".$user['User']['id'].") LIMIT 1);");
$rowSym = mysql_fetch_array($sqlSym);
$symbol = $rowSym[0];
?>
<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $("#TJourneyPricePeriodDestinationFromId, #TJourneyPricePeriodDestinationToId").chosen({width: 260});
        $("#TJourneyPricePeriodTTransportationTypeId").chosen({width: 450});
        $(".float").autoNumeric({mDec: 2, aSep: ','});
        $("#TJourneyPricePeriodDestinationFromId_chosen, #TJourneyPricePeriodDestinationToId_chosen, #TJourneyPricePeriodTTransportationTypeId_chosen").removeAttr("style");
        $(".btnSaveTJourneyPricePeriod").unbind("click").click(function(event){
            $("#TJourneyPricePeriodAddForm").submit();
        });
        $("#TJourneyPricePeriodAddForm").validationEngine('attach', {
            isOverflown: true,
            overflownDIV: ".ui-tabs-panel"
        });
        $("#TJourneyPricePeriodAddForm").ajaxForm({
            beforeSerialize: function($form, options) {
                $("input[name='data[TJourneyPricePeriod][t_transportation_type_id]']").remove();
                $("#TJourneyPricePeriodStart, #TJourneyPricePeriodEnd").datepicker("option", "dateFormat", "yy-mm-dd");
                $(".float").each(function(){
                    $(this).val($(this).val().replace(/,/g,""));
                });
            },
            beforeSubmit: function(arr, $form, options) {
                $(".txtSaveTJourneyPricePeriod").html("<?php echo ACTION_LOADING; ?>");
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
            },
            success: function(result) {
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                $(".btnBackTJourneyPricePeriod").click();
                // alert message
//                if(result != "<?php echo MESSAGE_DATA_HAS_BEEN_SAVED; ?>" && result != "<?php echo MESSAGE_DATA_COULD_NOT_BE_SAVED; ?>" && result != "<?php echo MESSAGE_DATA_ALREADY_EXISTS_IN_THE_SYSTEM; ?>"){
//                    createSysAct('TJourneyPricePeriod', 'Add', 2, result);
//                    $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_PROBLEM; ?></p>');
//                }else {
                    createSysAct('TJourneyPricePeriod', 'Add', 1, '');
                    // alert message
                    $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span>'+result+'</p>');
//                }
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
        
        $(".btnBackTJourneyPricePeriod").unbind('click').click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableTJourneyPricePeriod.fnDraw(false);
            var rightPanel=$(this).parent().parent().parent().parent();
            var leftPanel=rightPanel.parent().find(".leftPanel");
            rightPanel.hide();rightPanel.html("");
            leftPanel.show("slide", { direction: "left" }, 500);
        });

        // Set Form Container Height
        var windowHeight  = $(window).height();
        var headerHeight  = $('.ui-layout-north').outerHeight(true);
        var tabsNavHeight = $('.ui-tabs-nav').outerHeight(true);
        var formHeight    = $('#tJourneyPricePeriodFormHeader').outerHeight(true);
        var formContainer = windowHeight - headerHeight - tabsNavHeight - formHeight - 100;
        $('#tJourneyPricePeriodFormContainer').css({
            'height': formContainer + 'px',
            'max-height': formContainer + 'px'
        });
        
        var dates = $("#TJourneyPricePeriodStart, #TJourneyPricePeriodEnd").datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            onSelect: function( selectedDate ) {
                var option = this.id == "TJourneyPricePeriodStart" ? "minDate" : "maxDate",
                    instance = $( this ).data( "datepicker" );
                    date = $.datepicker.parseDate(
                        instance.settings.dateFormat ||
                        $.datepicker._defaults.dateFormat,
                        selectedDate, instance.settings );
                dates.not( this ).datepicker( "option", option, date );
            }
        });
        
        $("#TJourneyPricePeriodPrice, #TJourneyPricePeriodForeignerPrice, #TJourneyPricePeriodMembership").unbind("focus").focus(function(){
            if(replaceNum($(this).val()) == 0){
                $(this).val("");
            }
        });
        
        $("#TJourneyPricePeriodApplyTo").unbind("change").change(function(){
            $("#divTJourneyPricePeriodMainBranchId").hide();
            $("#TJourneyPricePeriodMainBranchId").removeClass("validate[required]");
            if($(this).val() == "2"){
                $("#divTJourneyPricePeriodMainBranchId").show();
                $("#TJourneyPricePeriodMainBranchId").addClass("validate[required]");
            }
        });
    });
</script>
<!-- Button Header (Format#4) -->
<div class="filter-container" id="tJourneyPricePeriodFormHeader">
    <div class="filter-row">
        <div class="filter-form-group">
            <button type="button" class="form-btn btnBackTJourneyPricePeriod">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
        <div class="filter-form-group">
            <button type="submit" class="form-btn btnSaveTJourneyPricePeriod">
                <i class="fas fa-save"></i>
                <span class="txtSaveTJourneyPricePeriod"><?php echo ACTION_SAVE; ?></span>
            </button>
        </div>
    </div>
</div>

<!-- Form Container (Format#4) -->
<?php echo $this->Form->create('TJourneyPricePeriod', array('class' => 'form-table-container')); ?>
<div class="form-container" id="tJourneyPricePeriodFormContainer">
    <fieldset>
        <legend><?php __(MENU_SET_PRICE_PERIOD_INFO); ?></legend>
        <table class="form-table">
            <!-- Apply To Field -->
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TJourneyPricePeriodApplyTo"><?php echo TABLE_APPLY_TO; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <select name="data[TJourneyPricePeriod][apply_to]" id="TJourneyPricePeriodApplyTo" class="form-select validate[required]">
                            <option value=""><?php echo INPUT_SELECT; ?></option>
                            <option value="1"><?php echo TABLE_ALL; ?></option>
                            <option value="2"><?php echo MENU_MAIN_BRANCH; ?></option>
                        </select>
                    </div>
                </td>
            </tr>

            <!-- Main Branch Field (Conditional) -->
            <tr class="form-row" id="divTJourneyPricePeriodMainBranchId" style="display: none;">
                <td class="form-label-cell">
                    <label for="TJourneyPricePeriodMainBranchId"><?php echo MENU_MAIN_BRANCH; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->input('main_branch_id', array(
                            'class' => 'form-select',
                            'label' => false,
                            'empty' => INPUT_SELECT,
                            'div' => false
                        )); ?>
                    </div>
                </td>
            </tr>

            <!-- Description Field -->
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TJourneyPricePeriodName"><?php echo GENERAL_DESCRIPTION; ?> <span class="red">*</span> :</label>
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

            <!-- Destination Fields -->
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TJourneyPricePeriodDestinationFromId"><?php echo TABLE_DESTINATION_FROM; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->input('destination_from_id', array(
                            'class' => 'form-select validate[required]',
                            'label' => false,
                            'empty' => INPUT_SELECT,
                            'div' => false
                        )); ?>
                    </div>
                </td>
            </tr>

            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TJourneyPricePeriodDestinationToId"><?php echo TABLE_DESTINATION_TO; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->input('destination_to_id', array(
                            'class' => 'form-select validate[required]',
                            'label' => false,
                            'empty' => INPUT_SELECT,
                            'div' => false
                        )); ?>
                    </div>
                </td>
            </tr>

            <!-- Transportation Type Field -->
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TJourneyPricePeriodTTransportationTypeId"><?php echo MENU_TRANSPORTATION_TYPE; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->input('t_transportation_type_id', array(
                            'class' => 'form-select validate[required]',
                            'label' => false,
                            'multiple' => 'multiple',
                            'data-placeholder' => INPUT_SELECT,
                            'div' => false
                        )); ?>
                    </div>
                </td>
            </tr>

            <!-- Date Fields -->
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TJourneyPricePeriodStart"><?php echo TABLE_START_DATE; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <input type="text" name="data[TJourneyPricePeriod][start]" id="TJourneyPricePeriodStart" class="form-input validate[required]" />
                    </div>
                </td>
            </tr>

            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TJourneyPricePeriodEnd"><?php echo TABLE_END_DATE; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <input type="text" name="data[TJourneyPricePeriod][end]" id="TJourneyPricePeriodEnd" class="form-input validate[required]" />
                    </div>
                </td>
            </tr>

            <!-- Price Type Field -->
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TJourneyPricePeriodPriceType"><?php echo TABLE_PRICE_TYPE; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <select name="data[TJourneyPricePeriod][price_type]" id="TJourneyPricePeriodPriceType" class="form-select validate[required]">
                            <option value=""><?php echo INPUT_SELECT; ?></option>
                            <option value="1"><?php echo TABLE_FIX_AMOUNT; ?></option>
                            <option value="2"><?php echo TABLE_MARKUP_AMOUNT; ?></option>
                        </select>
                    </div>
                </td>
            </tr>

            <!-- Price Fields -->
            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TJourneyPricePeriodPrice"><?php echo "Selling Price ".TABLE_NORMAL; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('price', array(
                            'class' => 'form-input validate[required] float',
                            'autocomplete' => 'off',
                            'label' => false,
                            'div' => false
                        )); ?> <?php echo $symbol; ?>
                    </div>
                </td>
            </tr>

            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TJourneyPricePeriodForeignerPrice"><?php echo "Selling Price ".TABLE_FOREIGNER; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('foreigner_price', array(
                            'class' => 'form-input validate[required] float',
                            'autocomplete' => 'off',
                            'label' => false,
                            'div' => false
                        )); ?> <?php echo $symbol; ?>
                    </div>
                </td>
            </tr>

            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TJourneyPricePeriodMembership"><?php echo "Selling Price VIP Card"; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('membership', array(
                            'class' => 'form-input validate[required] float',
                            'autocomplete' => 'off',
                            'label' => false,
                            'div' => false
                        )); ?> <?php echo $symbol; ?>
                    </div>
                </td>
            </tr>

            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TJourneyPricePeriodAgencyPrice"><?php echo "Agency Price (Khmer)"; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('agency_price', array(
                            'class' => 'form-input validate[required] float',
                            'autocomplete' => 'off',
                            'label' => false,
                            'div' => false
                        )); ?> <?php echo $symbol; ?>
                    </div>
                </td>
            </tr>

            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TJourneyPricePeriodAgencyPriceForeigner"><?php echo "Agency Price (Foreigner)"; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('agency_price_foreigner', array(
                            'class' => 'form-input validate[required] float',
                            'autocomplete' => 'off',
                            'label' => false,
                            'div' => false
                        )); ?> <?php echo $symbol; ?>
                    </div>
                </td>
            </tr>
        </table>
    </fieldset>
</div>
<?php echo $this->Form->end(); ?>