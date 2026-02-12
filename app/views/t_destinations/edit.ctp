<?php 
echo $this->element('prevent_multiple_submit'); 
?>
<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        // $("#TDestinationProvinceId, #TDestinationCountryId").chosen({width: 265});
        // $("#TDestinationProvinceId_chosen, #TDestinationCountryId_chosen").removeAttr("style");
        $(".btnSaveTDestination").unbind("click").click(function(event){
            $("#TDestinationEditForm").submit();
        });
        $("#TDestinationEditForm").validationEngine('attach', {
            isOverflown: true,
            overflownDIV: ".ui-tabs-panel"
        });
        $("#TDestinationEditForm").ajaxForm({
            beforeSerialize: function($form, options) {
                listbox_selectall('TDestinationAvbSelected', true);
            },
            beforeSubmit: function(arr, $form, options) {
                $(".txtSaveTDestination").html("<?php echo ACTION_LOADING; ?>");
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
            },
            success: function(result) {
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                $(".btnBackTDestination").click();
                // alert message
                if(result != '<?php echo MESSAGE_DATA_HAS_BEEN_SAVED; ?>' && result != '<?php echo MESSAGE_DATA_COULD_NOT_BE_SAVED; ?>' && result != '<?php echo MESSAGE_DATA_ALREADY_EXISTS_IN_THE_SYSTEM; ?>'){
                    createSysAct('TDestination', 'Edit', 2, result);
                    $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_PROBLEM; ?></p>');
                }else {
                    createSysAct('TDestination', 'Edit', 1, '');
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
        $(".btnBackTDestination").unbind('click').click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableTDestination.fnDraw(false);
            var rightPanel=$(this).parent().parent().parent().parent();
            var leftPanel=rightPanel.parent().find(".leftPanel");
            rightPanel.hide();rightPanel.html("");
            leftPanel.show("slide", { direction: "left" }, 500);
        });

        // Set Form Container Height
        var windowHeight  = $(window).height();
        var headerHeight  = $('.ui-layout-north').outerHeight(true);
        var tabsNavHeight = $('.ui-tabs-nav').outerHeight(true);
        var formHeight    = $('#tDestinationFormHeader').outerHeight(true);
        var formContainer = windowHeight - headerHeight - tabsNavHeight - formHeight - 100;
        $('#tDestinationFormContainer').css({
            'height': formContainer + 'px',
            'max-height': formContainer + 'px'
        });
    });
</script>
<!-- Button Header (Format#4) -->
<div class="filter-container" id="tDestinationFormHeader">
    <div class="filter-row">
        <div class="filter-form-group">
            <button type="button" class="form-btn btnBackTDestination">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
        <div class="filter-form-group">
            <button type="submit" class="form-btn btnSaveTDestination">
                <i class="fas fa-save"></i>
                <span class="txtSaveTDestination"><?php echo ACTION_SAVE; ?></span>
            </button>
        </div>
    </div>
</div>

<!-- Form Container (Format#4) -->
<?php 
echo $this->Form->create('TDestination', array('class' => 'form-table-container'));
echo $this->Form->input('id'); 
 ?>
<div class="form-container" id="tDestinationFormContainer">
    <!-- Destination Info Fieldset -->
    <fieldset class="form-fieldset">
        <legend><?php __(MENU_DESTINATION_INFO); ?></legend>
        <table class="form-table">
            <!-- <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TDestinationCountryId"><?php //echo TABLE_COUNTRY; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php 
                        // echo $this->Form->input('country_id', array(
                        //     'class' => 'form-input validate[required]',
                        //     'label' => false,
                        //     'empty' => INPUT_SELECT,
                        //     'div' => false
                        // )); 
                        ?>
                    </div>
                </td>
            </tr> -->

            <!-- <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TDestinationProvinceId"><?php //echo MENU_PROVINCE_MANAGEMENT; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php 
                        // echo $this->Form->input('province_id', array(
                        //     'class' => 'form-input validate[required]',
                        //     'label' => false,
                        //     'empty' => INPUT_SELECT,
                        //     'div' => false
                        // )); 
                        ?>
                    </div>
                </td>
            </tr> -->

            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TDestinationCode"><?php echo TABLE_CODE; ?> <span class="red">*</span> :</label>
                </td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('code', array(
                            'class' => 'form-input validate[required]',
                            'label' => false,
                            'div' => false
                        )); ?>
                    </div>
                </td>
            </tr>

            <tr class="form-row">
                <td class="form-label-cell">
                    <label for="TDestinationNameKh"><?php echo TABLE_NAME; ?> (Khmer) <span class="red">*</span> :</label>
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
                    <label for="TDestinationName"><?php echo TABLE_NAME; ?> (English) <span class="red">*</span> :</label>
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

    <!-- Report To Fieldset -->
    <fieldset class="form-fieldset">
        <legend><?php __(REPORT_TO); ?></legend>
        <table class="form-table">
            <tr>
                <th>Available:</th>
                <th></th>
                <th style="text-align: left; padding-left: 100px;">Members:</th>
            </tr>
            <tr class="form-row" style="border-bottom: none;">
                <td class="form-input-cell" style="vertical-align: top; width: 300px;">
                    <select id="TDestinationAvb" multiple="multiple" style="width: 280px; height: 200px;">
                        <?php
                        if($user['User']['type'] == 2){
                            if(!empty($user['User']['offline_project_id'])){
                                $condition = " AND offline_project_id = ".$user['User']['offline_project_id'];
                            } else {
                                $condition = " AND offline_project_id = 0";
                            }
                        } else {
                            $condition = "";
                        }
                        $querySource=mysql_query("SELECT id,name AS full_name FROM t_destinations WHERE is_active=1 AND id != ".$this->data['TDestination']['id']." AND id NOT IN (SELECT t_destination_to_id FROM t_destination_tos WHERE t_destination_from_id = ".$this->data['TDestination']['id']." AND is_active = 1)".$condition);
                        while($dataSource=mysql_fetch_array($querySource)){
                        ?>
                        <option value="<?php echo $dataSource['id']; ?>"><?php echo $dataSource['full_name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td class="form-input-cell" style="vertical-align: middle; text-align: center;">
                    <img alt="" src="<?php echo $this->webroot; ?>img/button/right.png" style="cursor: pointer;" onclick="listbox_moveacross('TDestinationAvb', 'TDestinationAvbSelected')" />
                    <br /><br />
                    <img alt="" src="<?php echo $this->webroot; ?>img/button/left.png" style="cursor: pointer;" src="" style="cursor: pointer;" onclick="listbox_moveacross('TDestinationAvbSelected', 'TDestinationAvb')" />
                </td>
                <td class="form-input-cell" style="vertical-align: top;">
                    <select id="TDestinationAvbSelected" name="data[TDestination][t_destination_to_id][]" multiple="multiple" style="width: 280px; height: 200px;">
                        <?php
                        $sqlUserDes = mysql_query("SELECT id,name AS full_name FROM t_destinations WHERE is_active = 1 AND id IN (SELECT t_destination_to_id FROM t_destination_tos WHERE t_destination_from_id = ".$this->data['TDestination']['id']." AND is_active = 1)");
                        while($rowUserDes = mysql_fetch_array($sqlUserDes)) {
                        ?>
                        <option value="<?php echo $rowUserDes['id']; ?>"><?php echo $rowUserDes['full_name']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
    </fieldset>
</div>
<?php echo $this->Form->end(); ?>