<?php
// Authentication
$this->element('check_access');
$allowAdd = checkAccess($user['User']['id'], $this->params['controller'], 'add');
?>
<style>
    .boxViewLeftTTransportationType {
        width: 47%;
        margin-right: 10px;
    }

    .boxViewRightTTransportationType {
        width: 47%;
    }

    .boxViewLeftTTransportationType, .boxViewRightTTransportationType {
        border: 1px solid #000;
        min-height: 300px;
        padding: 5px;
        margin-bottom: 15px;
        box-sizing: border-box;
    }

    /* Desktop layout */
    @media (min-width: 768px) {
        .boxViewLeftTTransportationType {
            width: 47%;
            float: left;
            margin-right: 10px;
        }
        
        .boxViewRightTTransportationType {
            width: 47%;
            float: left;
        }
    }

    /* Mobile layout */
    @media (max-width: 767px) {
        .boxViewLeftTTransportationType, 
        .boxViewRightTTransportationType {
            width: 100%;
            float: none;
            margin-right: 0;
        }
    }
    
    .level-tab-view {
        display: inline-block;
        padding: 8px 15px;
        cursor: pointer;
        background-color: #f1f1f1;
        border: 1px solid #ccc;
        border-radius: 4px 4px 0 0;
        margin-right: 2px;
        font-weight: bold;
    }
    .level-tab-view.active {
        background-color: #003366;
        color: white;
        border-color: #003366;
    }
</style>
<script type="text/javascript">
    var rowTableSeatProtect  = $("#rowListSeatProtect");
    $(document).ready(function(){
        $("#rowListSeatProtect").remove();
        $(".btnBackTTransportationType").click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableTTransportationType.fnDraw(false);
            var rightPanel=$(this).parent().parent().parent().parent();
            var leftPanel=rightPanel.parent().find(".leftPanel");
            rightPanel.hide();rightPanel.html("");
            leftPanel.show("slide", { direction: "left" }, 500);
        });
        
        // Handle level tab switching
        $('.level-tab-view').on('click', function(e) {
            e.preventDefault();
            var level = $(this).data('level');
            $('.level-tab-view').removeClass('active');
            $(this).addClass('active');
            $('.layout-view-container').hide();
            $('#layout-view-level-' + level).show();
        });

        // Set Form Container Height
        var windowHeight  = $(window).height();
        var headerHeight  = $('.ui-layout-north').outerHeight(true);
        var tabsNavHeight = $('.ui-tabs-nav').outerHeight(true);
        var formHeight    = $('#tTransportationTypeFormHeader').outerHeight(true);
        var formContainer = windowHeight - headerHeight - tabsNavHeight - formHeight - 100;
        $('#tTransportationTypeFormContainer').css({
            'height': formContainer + 'px',
            'max-height': formContainer + 'px'
        });
        <?php
        if($allowAdd){
        ?>
        $(".btnAddSeatProtect").unbind("click").click(function(event){
            event.preventDefault();
            var lblSeat1 = "";
            var valSeat1 = "";
            var lblSeat2 = "";
            var valSeat2 = "";
            var i = 0;
            $(".transportationSeatCheck").each(function(){
                if($(this).is(":checked")){
                    if($(this).attr("disabled") == undefined){
                        if(i == 0){
                            lblSeat1 = $(this).attr("lbl");
                            valSeat1 = $(this).attr("value");
                        } else {
                            lblSeat2 = $(this).attr("lbl");
                            valSeat2 = $(this).attr("value");
                        }
                        i++;
                    }
                }
            });
            if(i == 2){
                if(lblSeat1 != "" && valSeat1 != "" && lblSeat2 != "" && valSeat2 != ""){
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "<?php echo $this->base; ?>/<?php echo $this->params['controller']; ?>/saveSeatProtectGender",
                        data: "data[t_transportation_type_id]=<?php echo $this->data['TTransportationType']['id']; ?>&data[seat1_number]="+valSeat1+"&data[seat1_lbl]="+lblSeat1+"&data[seat2_number]="+valSeat2+"&data[seat2_lbl]="+lblSeat2,
                        beforeSend: function(){
                            $(".btnAddSeatProtect").attr("disabled", true);
                            $("#lblAddSeatProtect").text("<?php echo ACTION_LOADING; ?>");
                        },
                        success: function(result){
                            $(".btnAddSeatProtect").attr("disabled", false);
                            $("#lblAddSeatProtect").text("Add Seat Protect");
                            if(result.status == "1"){
                                cloneSeatProtect(result.id, lblSeat1, lblSeat2);
                            } else {
                                alert("Add failed");
                            }
                        }
                    });
                }
            } else {
                alert("Please select two seats to protect");
            }
        });
        eventKeySeatProtect()
        <?php
        }
        ?>
    });
    <?php
    if($allowAdd){
    ?>
    function cloneSeatProtect(seatProtectId, seatLbl1, seatLbl2){
        var tr = rowTableSeatProtect.clone(true);
        tr.removeAttr("style").removeAttr("id");
        tr.find("td .seatProtectId").val(seatProtectId);
        tr.find("td .lblSeat1").text(seatLbl1);
        tr.find("td .lblSeat2").text(seatLbl2);
        $("#tbSeatProtect").append(tr);
        $(".transportationSeatCheck[lbl='"+seatLbl1+"']").attr("disabled", true);
        $(".transportationSeatCheck[lbl='"+seatLbl2+"']").attr("disabled", true);
        eventKeySeatProtect();
    }

    function eventKeySeatProtect(){
        $(".btnRemoveRowSeatProtect").unbind('click').unbind('change');
        $(".btnRemoveRowSeatProtect").click(function(){
            var obj = $(this);
            $("#dialog").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to delete the selected item(s)?</p>');
            $("#dialog").dialog({
                title: '<?php echo DIALOG_CONFIRMATION; ?>',
                resizable: false,
                modal: true,
                width: 'auto',
                height: 'auto',
                open: function(event, ui){
                    $(".ui-dialog-buttonpane").show();
                },
                buttons: {
                    '<?php echo ACTION_CANCEL; ?>': function() {
                        $(this).dialog("close");
                    },
                    '<?php echo ACTION_OK; ?>': function() {
                        var id = obj.closest("tr").find(".seatProtectId").val();
                        var lblSeat1 = obj.closest("tr").find(".lblSeat1").text();
                        var lblSeat2 = obj.closest("tr").find(".lblSeat2").text();
                        $(".transportationSeatCheck[lbl='"+lblSeat1+"']").attr("disabled", false);
                        $(".transportationSeatCheck[lbl='"+lblSeat2+"']").attr("disabled", false);
                        $(".transportationSeatCheck[lbl='"+lblSeat1+"']").attr("checked", false);
                        $(".transportationSeatCheck[lbl='"+lblSeat2+"']").attr("checked", false);
                        $.ajax({
                            dataType: "json",
                            type: "POST",
                            url: "<?php echo $this->base; ?>/<?php echo $this->params['controller']; ?>/deleteSeatProtectGender/"+id,
                            beforeSend: function(){
                                obj.closest("tr").hide();
                            },
                            success: function(result){
                                if(result.status == "1"){
                                    obj.closest("tr").remove();
                                } else {
                                    obj.closest("tr").show();
                                    alert("Remove seat failed");
                                }
                            }
                        });
                        $(this).dialog("close");
                    }
                }
            });
        });
    }
    <?php
    }
    ?>
</script>
<div class="filter-container" id="tTransportationTypeFormHeader">
    <div class="filter-row">
        <div class="filter-form-group">
            <button type="button" class="form-btn btnBackTTransportationType">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
    </div>
</div>
<div class="form-container" id="tTransportationTypeFormContainer">
    <fieldset class="boxViewLeftTTransportationType">
        <legend><?php __(MENU_TRANSPORTATION_TYPE_INFO); ?></legend>
        <table class="form-table">
            <tr class="form-row">
                <td class="form-label-cell" rowspan="4">
                    <?php
                    $img = "";
                    if(!empty($this->data['TTransportationType']['photo'])){
                        $img = $this->data['TTransportationType']['photo_path'].$this->webroot."public/transportation_type/".$this->data['TTransportationType']['photo'];
                    }
                    ?>
                    <img src="<?php echo $img; ?>" style="width: 150px; height: 100px;" />
                </td>
                <td class="form-label-cell"><?php __(TABLE_NAME); ?> :</th>
                <td class="form-input-cell"><?php echo $this->data['TTransportationType']['name']; ?></td>
            </tr>
            <tr class="form-row">
                <td class="form-label-cell"><?php __(TABLE_TOTAL_SEAT); ?> :</th>
                <td class="form-input-cell"><?php echo $this->data['TTransportationType']['number_of_seat']; ?></td>
            </tr>
            <tr class="form-row">
                <td class="form-label-cell"><?php __("Seat Type"); ?> :</th>
                <td class="form-input-cell">
                    <?php 
                    if($this->data['TTransportationType']['seat_type'] == 1) {
                        echo "Sitting";
                    } else if($this->data['TTransportationType']['seat_type'] == 2) {
                        echo "Sleeping";
                    }
                    ?>
                </td>
            </tr>
            <tr class="form-row">
                <td class="form-label-cell"><?php __(GENERAL_DESCRIPTION); ?> :</th>
                <td class="form-input-cell"><?php echo nl2br($this->data['TTransportationType']['description']); ?></td>
            </tr>
        </table>
    </fieldset>
    <fieldset class="boxViewRightTTransportationType">
        <legend><?php __("Other Photo"); ?></legend>
        <table width="100%" cellpadding="5">
            <tr>
                <td valign="top">
                    <?php
                    $sqlOtherPhoto = mysql_query("SELECT * FROM t_transportation_type_photos WHERE t_transportation_type_id = ".$this->data['TTransportationType']['id']);
                    while($rowOtherPhoto = mysql_fetch_array($sqlOtherPhoto)){
                    ?>
                    <div style="float: left; width: 100px; margin-left: 3px; margin-bottom: 3px;">
                        <img src="<?php echo $rowOtherPhoto['photo_path'].$this->webroot; ?>public/transportation_type/<?php echo $rowOtherPhoto['photo']; ?>" style="width: 100px; height: 65px;" />
                    </div>
                    <?php
                    }
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <div style="clear: both;"></div>
    <br/>
    <fieldset class="boxViewLeftTTransportationType">
        <legend><?php __('Seat Protect Gender'); ?></legend>
        <table cellpadding="0" cellspacing="0" class="table">
            <tr>
                <th class="first" style="width: 33%;">Seat1 #</th>
                <th>Seat2 #</th>
                <th>Action</th>
            </tr>
            <tbody id="tbSeatProtect">
                <tr id="rowListSeatProtect" class="rowListSeatProtect">
                    <td class="first">
                        <input type="hidden" class="seatProtectId" />
                        <span class="lblSeat1"></span>
                    </td>
                    <td>
                        <span class="lblSeat2"></span>
                    </td>
                    <td style="height: 30px;">
                        <img alt="Remove" src="<?php echo $this->webroot . 'img/button/cross.png'; ?>" class="btnRemoveRowSeatProtect" align="absmiddle" style="cursor: pointer;" onmouseover="Tip('Remove')" />
                    </td>
                </tr>
                <?php
                $seatProtect = array();
                $sqlSeatProtect = mysql_query("SELECT * FROM seat_protect_genders WHERE t_transportation_type_id = ".$this->data['TTransportationType']['id']);
                while($rowSeatProtect = mysql_fetch_array($sqlSeatProtect)){
                    $seatProtect[$rowSeatProtect['seat1_number']] = 1;
                    $seatProtect[$rowSeatProtect['seat2_number']] = 1;
                ?>
                <tr class="rowListSeatProtect">
                    <td class="first">
                        <input type="hidden" class="seatProtectId" value="<?php echo $rowSeatProtect['id']; ?>" />
                        <span class="lblSeat1"><?php echo $rowSeatProtect['seat1_lbl']; ?></span>
                    </td>
                    <td>
                        <span class="lblSeat2"><?php echo $rowSeatProtect['seat2_lbl']; ?></span>
                    </td>
                    <td style="height: 30px;">
                        <?php
                        if($allowAdd){
                        ?>
                        <img alt="Remove" src="<?php echo $this->webroot . 'img/button/cross.png'; ?>" class="btnRemoveRowSeatProtect" align="absmiddle" style="cursor: pointer;" onmouseover="Tip('Remove')" />
                        <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </fieldset> 
    <fieldset class="boxViewRightTTransportationType">
        <legend>Layout</legend>
        <?php
        $layouts = json_decode($this->data['TTransportationType']['layout'], true);
        if (is_array($layouts) && !empty($layouts)) {
            $totalLevels = isset($layouts['total_levels']) ? (int)$layouts['total_levels'] : 1;
            $levelNames = isset($layouts['level_names']) ? $layouts['level_names'] : [];
        ?>
            <div id="level-tabs-container-view" style="padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #ccc;">
                <?php for ($l = 1; $l <= $totalLevels; $l++) : ?>
                    <?php $levelName = isset($levelNames[$l - 1]) && !empty($levelNames[$l - 1]) ? $levelNames[$l - 1] : "Level " . $l; ?>
                    <a href="#" class="level-tab-view <?php echo $l === 1 ? 'active' : ''; ?>" data-level="<?php echo $l; ?>"><?php echo h($levelName); ?></a>
                <?php endfor; ?>
            </div>
            <?php
            for ($levelNum = 1; $levelNum <= $totalLevels; $levelNum++) :
                $displayStyle = $levelNum === 1 ? '' : 'display:none;';
                echo '<div id="layout-view-level-' . $levelNum . '" class="layout-view-container" style="' . $displayStyle . '">';

                $tableLayout = '';
                $seatImg = 'seat-sitting-32.png';
                $tableWidth = 32;
                $tableHeight = 32;
                $seatChkMargin = 10;
                if ($this->data['TTransportationType']['seat_type'] == 2) {
                    $seatImg = 'seat-sleeper-32.png';
                    $tableHeight = 60;
                    $seatChkMargin = 25;
                }

                if (!empty($layouts['seats'])) {
                    $rows = $layouts['rows'];
                    $columns = $layouts['columns'];
                    $seats = $layouts['seats'];

                    $levelSeats = array_filter($seats, function ($seat) use ($levelNum) {
                        return (isset($seat['level']) && $seat['level'] == $levelNum) || (!isset($seat['level']) && $levelNum == 1);
                    });

                    $seatGrid = array_fill(0, $rows, array_fill(0, $columns, null));
                    $rowspans = array_fill(0, $columns, 0);

                    foreach ($levelSeats as $seat) {
                        $r = $seat['row']-1; $c = $seat['column']-1;
                        if(isset($seatGrid[$r][$c])) continue; // Should not happen with well-formed data
                        
                        $seatGrid[$r][$c] = $seat;

                        if (isset($seat['rowspan']) && $seat['rowspan'] > 1) {
                            for ($i = 1; $i < $seat['rowspan']; $i++) {
                                 if(isset($seatGrid[$r+$i])){
                                      $seatGrid[$r+$i][$c] = 'ROW_SPAN_PLACEHOLDER';
                                 }
                            }
                        }
                         if (isset($seat['colspan']) && $seat['colspan'] > 1) {
                            for ($i = 1; $i < $seat['colspan']; $i++) {
                                $seatGrid[$r][$c+$i] = 'COL_SPAN_PLACEHOLDER';
                                if(isset($seat['rowspan']) && $seat['rowspan'] > 1){
                                    for ($j = 1; $j < $seat['rowspan']; $j++) {
                                        if(isset($seatGrid[$r+$j])){
                                             $seatGrid[$r+$j][$c+$i] = 'COL_SPAN_PLACEHOLDER';
                                        }
                                    }
                                }
                            }
                        }
                    }

                    for ($r = 0; $r < $rows; $r++) {
                        $tableLayout .= '<tr>';
                        for ($c = 0; $c < $columns; $c++) {
                            $seat = $seatGrid[$r][$c];
                            if ($seat === 'ROW_SPAN_PLACEHOLDER' || $seat === 'COL_SPAN_PLACEHOLDER') {
                                continue;
                            }
                            if (is_array($seat)) {
                                $colspan = isset($seat['colspan']) ? $seat['colspan'] : 1;
                                $rowspan = isset($seat['rowspan']) ? $seat['rowspan'] : 1;
                                $value = $seat['value'];
                                $label = isset($seat['label']) ? $seat['label'] : $value;
                                
                                $attrCol = '';
                                if ($colspan > 1) $attrCol .= 'colspan="' . $colspan . '" ';
                                if ($rowspan > 1) $attrCol .= 'rowspan="' . $rowspan . '" ';

                                if (is_numeric($value)) {
                                    $tableLayout .= '<td ' . $attrCol . ' style="height: ' . $tableHeight . 'px; width: ' . $tableWidth . 'px; text-align: center; vertical-align: middle; font-size: 10px;">';
                                    if (array_key_exists($value, $seatProtect)) {
                                        $tableLayout .= '<div style="width: ' . $tableWidth . 'px; height: ' . $tableHeight . 'px; background: url(../img/button/' . $seatImg . ') center no-repeat;"><input type="checkbox" disabled="" checked="" lbl="' . h($label) . '" class="transportationSeatCheck" value="' . h($value) . '" style="cursor: pointer; margin-top: ' . $seatChkMargin . 'px;" /></div>' . h($label);
                                    } else {
                                        $tableLayout .= '<div style="width: ' . $tableWidth . 'px; height: ' . $tableHeight . 'px; background: url(../img/button/' . $seatImg . ') center no-repeat;"><input type="checkbox" lbl="' . h($label) . '" class="transportationSeatCheck" value="' . h($value) . '" style="cursor: pointer; margin-top: ' . $seatChkMargin . 'px;" /></div>' . h($label);
                                    }
                                } else {
                                    $tableLayout .= '<td ' . $attrCol . ' style="height: ' . $tableHeight . 'px; width: ' . $tableWidth . 'px; text-align: center; vertical-align: middle;">';
                                    switch ($label) {
                                        case 'Capitain': $tableLayout .= '<img src="' . $this->webroot . 'img/button/captain.png" alt="" style="width: 24px;" />'; break;
                                        case 'Hostess': $tableLayout .= '<img src="' . $this->webroot . 'img/button/hostess.png" alt="" style="width: 32px;" />'; break;
                                        case 'Toilet': $tableLayout .= '<i class="fas fa-toilet" style="font-size: 24px;"></i>'; break;
                                        default: $tableLayout .= '<span style="font-size: 11px;">' . h($label) . '</span>';
                                    }
                                }
                                $tableLayout .= '</td>';
                            } else {
                                $tableLayout .= '<td style="height: ' . $tableHeight . 'px; width: ' . $tableWidth . 'px;"></td>';
                            }
                        }
                        $tableLayout .= '</tr>';
                    }
                    $totalTableWeight = isset($columns) ? $tableWidth * $columns : 0;
                }
            ?>
                <div style="clear: both;"></div>
                <div style="width: auto; margin-top: 10px; float: left; overflow-x: auto;">
                    <table cellpadding="0" cellspacing="0" style="width: 100%;">
                        <tr>
                            <td style="vertical-align: top;">
                                <table cellpadding="5" cellspacing="0" style="width: <?php echo isset($totalTableWeight) ? $totalTableWeight : 'auto'; ?>px;">
                                    <?php echo $tableLayout; ?>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php
                echo '</div>'; // End layout-view-level-X
            endfor;
            if ($allowAdd) {
            ?>
                <div class="buttons" style="clear:both; padding-top:15px;">
                    <a href="" class="positive btnAddSeatProtect">
                        <img src="<?php echo $this->webroot; ?>img/button/plus.png" alt="" />
                        <span id="lblAddSeatProtect"><?php echo 'Add Seat Protect'; ?></span>
                    </a>
                </div>
            <?php
            }
        }
        ?>
    </fieldset>
    <div style="clear: both;"></div>
</div>