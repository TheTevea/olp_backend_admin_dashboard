<?php
// Authentication
$this->element('check_access');
$allowDiscount  = checkAccess($user['User']['id'], $this->params['controller'], 'discount');
$allowWalkIn    = checkAccess($user['User']['id'], $this->params['controller'], 'allowWalkIn');
$allowPhoneCall = checkAccess($user['User']['id'], $this->params['controller'], 'allowPhoneCall');
$allowSalesAgency  = checkAccess($user['User']['id'], $this->params['controller'], 'allowSalesAgency');
$allowChangeShift  = checkAccess($user['User']['id'], $this->params['controller'], 'changeShift');

include("includes/function.php");
echo $this->element('prevent_multiple_submit'); 
$priceType = 1;
$journeyPrice   = $journey['TJourney']['unit_price'];
$journeyDf      = $journey['TJourney']['unit_price'];
$journeyVip     = $journey['TJourney']['membership'];
$journeyFor     = $journey['TJourney']['unit_price'];
$journeyAgPrKh  = $journey['TJourney']['agent_price_amount'];
$journeyAgPrFg  = $journey['TJourney']['agetn_price_percent'];
$ticketNote     = '';
$customerName   = '';
$customerTel    = '';
$customerMail   = '';
$totalAmount    = '0';
$totalDiscount  = '0';
$totalDiscountP = '0';
$totalVat       = 0;
$totalLuckyDraw = 0;
$total = '0';
$allowVat = 1;
$agencyType = 0;
if($journey['Company']['id'] == 6){
    $allowVat = 0;
}
if(!empty($tTicket)){
    if($tTicket['TTicket']['status'] == 1 && $tTicket['TTicket']['type'] == 2){
        $priceType      = $tTicket['TTicket']['price_type'];
        $customerName   = $tTicket['TTicket']['customer_name'];
        $customerTel    = $tTicket['TTicket']['telephone'];
        $customerMail   = $tTicket['TTicket']['email'];
        $totalAmount    = number_format($tTicket['TTicket']['total_amount'], 2);
        $ticketNote     = $tTicket['TTicket']['note'];
        // $totalDiscount  = number_format($tTicket['TTicket']['discount_amount'], 2);
        // $totalDiscountP = number_format($tTicket['TTicket']['discount_percent'], 2);
        $totalVat       = number_format($tTicket['TTicket']['total_vat'], 2);
        $totalLuckyDraw = number_format($tTicket['TTicket']['lucky_draw_fee'], 2);
        $total = number_format($totalAmount - $tTicket['TTicket']['discount_amount'] + $totalVat + $totalLuckyDraw, 2);
    }
}

// Check Default Price
$sqlPD = mysql_query("SELECT * FROM t_journey_price_defaults WHERE offline_project_id = 1 AND destination_from_id = ".$journey['TJourney']['t_destination_from_id']." AND destination_to_id = ".$journey['TJourney']['t_destination_to_id']." AND t_transportation_type_id = ".$journey['TJourney']['t_transportation_type_id']." AND status = 1 AND main_branch_id = ".$user['User']['main_branch_id']." ORDER BY id DESC LIMIT 1");
if(mysql_num_rows($sqlPD)){
    $rowPD = mysql_fetch_array($sqlPD);
    $journeyPrice = $rowPD['price'];
    $journeyDf    = $rowPD['price'];
    $journeyVip   = $rowPD['membership'];
    $journeyFor   = $rowPD['price'];
    $journeyAgPrKh  = $rowPD['price'];
    $journeyAgPrFg  = $rowPD['price'];
} else {
    $sqlPDA = mysql_query("SELECT * FROM t_journey_price_defaults WHERE offline_project_id = 1 AND destination_from_id = ".$journey['TJourney']['t_destination_from_id']." AND destination_to_id = ".$journey['TJourney']['t_destination_to_id']." AND t_transportation_type_id = ".$journey['TJourney']['t_transportation_type_id']." AND status = 1 AND (main_branch_id IS NULL OR main_branch_id = '') ORDER BY id DESC LIMIT 1");
    if(mysql_num_rows($sqlPDA)){
        $rowPDA = mysql_fetch_array($sqlPDA);
        $journeyPrice = $rowPDA['price'];
        $journeyDf    = $rowPDA['price'];
        $journeyVip   = $rowPDA['membership'];
        $journeyFor   = $rowPDA['price'];
        $journeyAgPrKh  = $rowPDA['price'];
        $journeyAgPrFg  = $rowPDA['price'];
    }
}
// Check Price in Period
$checkPromoInternal = false;
if($user['User']['type'] == 2){ // User Internal
    // By Journey Internal
    $sqlPriceJourneyInternal = mysql_query("SELECT * FROM t_journey_price_periods WHERE offline_project_id = 1 AND start <= '".$date."' AND end >= '".$date."' AND status = 1 AND apply_type = 2 AND t_journey_id = ".$journey['TJourney']['id']." ORDER BY id DESC LIMIT 1");
    if(mysql_num_rows($sqlPriceJourneyInternal)){
        $rowPriceJourneyInternal = mysql_fetch_array($sqlPriceJourneyInternal);
        $journeyPrice    = $rowPriceJourneyInternal['price'];
        $journeyDf       = $rowPriceJourneyInternal['price'];
        $journeyFor      = $rowPriceJourneyInternal['price'];
        $checkPromoInternal = true;
    }
}
if($checkPromoInternal == false){
    // By Journey Public
    $sqlPriceJourney = mysql_query("SELECT * FROM t_journey_price_periods WHERE offline_project_id = 1 AND start <= '".$date."' AND end >= '".$date."' AND status = 1 AND apply_type = 1 AND t_journey_id = ".$journey['TJourney']['id']." ORDER BY id DESC LIMIT 1");
    if(mysql_num_rows($sqlPriceJourney)){
        $rowPriceJourney = mysql_fetch_array($sqlPriceJourney);
        if($checkPromoInternal == false){
            $journeyPrice    = $rowPriceJourney['price'];
            $journeyDf       = $rowPriceJourney['price'];
            $journeyFor      = $rowPriceJourney['price'];
        }
        $journeyVip      = $rowPriceJourney['membership'];
        $journeyAgPrKh   = $rowPriceJourney['agency_price'];
        $journeyAgPrFg   = $rowPriceJourney['agency_price_foreigner'];
    } else {
        // By Destination & Location Branch
        $sqlPrice = mysql_query("SELECT * FROM t_journey_price_periods WHERE offline_project_id = ".$user['User']['offline_project_id']." AND destination_from_id = ".$journey['TJourney']['t_destination_from_id']." AND destination_to_id = ".$journey['TJourney']['t_destination_to_id']." AND t_transportation_type_id = ".$journey['TJourney']['t_transportation_type_id']." AND start <= '".$date."' AND end >= '".$date."' AND status = 1 AND apply_type = 1 AND main_branch_id = ".$user['User']['main_branch_id']." ORDER BY id DESC LIMIT 1");
        if(mysql_num_rows($sqlPrice)){
            $rowPrice = mysql_fetch_array($sqlPrice);
            if($rowPrice['price_type'] == 1){
                if($checkPromoInternal == false){
                    $journeyPrice   = $rowPrice['price'];
                    $journeyDf      = $rowPrice['price'];
                    $journeyFor     = $rowPrice['price'];
                }
                $journeyVip     = $rowPrice['membership'];
                $journeyAgPrKh  = $rowPrice['agency_price'];
                $journeyAgPrFg  = $rowPrice['agency_price_foreigner'];
            } else {
                if($checkPromoInternal == false){
                    $journeyPrice   = $journey['TJourney']['unit_price'] + $rowPrice['price'];
                    $journeyDf      = $journey['TJourney']['unit_price'] + $rowPrice['price'];
                    $journeyFor     = $journey['TJourney']['unit_price'] + $rowPrice['price'];
                }   
                $journeyVip     = $journey['TJourney']['membership'] + $rowPrice['membership'];
                $journeyAgPrKh  = $journey['TJourney']['agent_price_amount'] + $rowPrice['agency_price'];
                $journeyAgPrFg  = $journey['TJourney']['agetn_price_percent'] + $rowPrice['agency_price_foreigner'];
            }
        } else {
            // By Destination
            $sqlPA = mysql_query("SELECT * FROM t_journey_price_periods WHERE offline_project_id = ".$user['User']['offline_project_id']." AND destination_from_id = ".$journey['TJourney']['t_destination_from_id']." AND destination_to_id = ".$journey['TJourney']['t_destination_to_id']." AND t_transportation_type_id = ".$journey['TJourney']['t_transportation_type_id']." AND start <= '".$date."' AND end >= '".$date."' AND status = 1 AND apply_type = 1 AND (main_branch_id IS NULL OR main_branch_id = '') ORDER BY id DESC LIMIT 1");
            if(mysql_num_rows($sqlPA)){
                $rowPAPrice = mysql_fetch_array($sqlPA);
                if($rowPAPrice['price_type'] == 1){
                    if($checkPromoInternal == false){
                        $journeyPrice   = $rowPAPrice['price'];
                        $journeyDf      = $rowPAPrice['price'];
                        $journeyFor     = $rowPAPrice['price'];
                    }
                    $journeyVip     = $rowPAPrice['membership'];
                    $journeyAgPrKh  = $rowPAPrice['agency_price'];
                    $journeyAgPrFg  = $rowPAPrice['agency_price_foreigner'];
                } else {
                    if($checkPromoInternal == false){
                        $journeyPrice   = $journey['TJourney']['unit_price'] + $rowPAPrice['price'];
                        $journeyDf      = $journey['TJourney']['unit_price'] + $rowPAPrice['price'];
                        $journeyFor     = $journey['TJourney']['unit_price'] + $rowPAPrice['price'];
                    }
                    $journeyVip     = $journey['TJourney']['membership'] + $rowPAPrice['membership'];
                    $journeyAgPrKh  = $journey['TJourney']['agent_price_amount'] + $rowPAPrice['agency_price'];
                    $journeyAgPrFg  = $journey['TJourney']['agetn_price_percent'] + $rowPAPrice['agency_price_foreigner'];
                }
            }
        }
    }
}

if($journey['TJourney']['allow_price_period'] == 1){ // Disable VAT Option    
    $allowVat = 0;
}

$returnTrip = false;
$sqlChR = mysql_query("SELECT id FROM t_journeys WHERE t_destination_from_id = ".$journey['TJourney']['t_destination_to_id']." AND t_destination_to_id = ".$journey['TJourney']['t_destination_from_id']." AND status = 1 AND offline_project_id = ".$user['User']['offline_project_id']." LIMIT 1");
if(mysql_num_rows($sqlChR)){
    $returnTrip = true;
}

$isAgency    = false;
$usePriceDef = 1;
$chkRefeReq  = "";
$sqlUAgen = mysql_query("SELECT * FROM t_agents WHERE user_id = ".$user['User']['id']." AND status = 1 LIMIT 1");
if(mysql_num_rows($sqlUAgen)){
    $rowUAgen = mysql_fetch_array($sqlUAgen);
    if($rowUAgen['id'] == 47 || $rowUAgen['id'] == 86 || $rowUAgen['id'] == 91){
        $chkRefeReq = "1";
    }
    $isAgency = true;
    $usePriceDef = $rowUAgen['use_default_price'];
}
?>
      
<style>

    .level-tab-booking {
        display: inline-block;
        padding: 8px 15px;
        cursor: pointer;
        background-color: #f1f1f1;
        border: 1px solid #ccc;
        border-radius: 4px 4px 0 0;
        margin-right: 2px;
        font-weight: bold;
    }
    .level-tab-booking.active {
        background-color: #003366;
        color: white;
        border-color: #003366;
    }

    .radio-button-group {
        display: inline-flex;
        border-radius: 4px;
        vertical-align: middle;
    }
    .radio-button-group input[type="radio"] {
        display: none;
    }
    .radio-button-group label {
        cursor: pointer;
        background-color: #f1f1f1;
        color: #333;
        transition: background-color 0.3s;
        border: 1px solid #ccc;
        margin-right: -1px;
        position: relative;
    }
    .radio-button-group label:first-of-type {
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }
    .radio-button-group label:last-of-type {
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
    }
    .radio-button-group label:hover {
        background-color: #e2e2e2;
    }
    .radio-button-group input[type="radio"]:checked + label {
        background-color: #003366;
        color: white;
        font-weight: bold;
        border-color: #003366;
        z-index: 2;
    }
    
    /* Specific styles for each button group */
    .ticket-type-selector label {
        padding: 6px 12px;
        font-size: 12px;
    }
    .price-type-selector label {
        padding: 3px 10px;
        font-size: 11px;
        line-height: 1.5; /* Ensure consistent height for smaller font */
    }
</style>

    
<script type="text/javascript">
    var rowIndexBooking = '';
    var rowTableBooking  = $("#tblTicketBookingSeatList");
    var rowTableBookingPassport = $("#tblTicketBookingSeatPassportList");
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $(".float").autoNumeric({mDec: 2, aSep: ','});
        // Remove Table Seat
        $("#tblTicketBookingSeatList, #tblTicketBookingSeatPassportList").remove();
        // Chosen
        // $("#ticketBookingBoardingPoint, #ticketBookingDropOff, #ticketBookingPickUp").chosen({width: 150});
        $("#ticketBookingBoardingPoint, #ticketBookingDropOff").chosen({width: 150});
        $("#ticketBookingAgency").chosen({width: 180});
        $(".TTicketType").unbind('click').click(function(){
            var val = $(".TTicketType:checked").val();
            $("#ticketBoookingMarkupItem").val(0);
            $(".ticketBookingMarkupDiv").hide();
            $(".ticketBookingAgencyDIv").hide();
            $(".divReturnTrip").css("visibility", "hidden");
            $(".seatDiscount").val("0");
            $(".seatDiscount").removeAttr("readonly");
            <?php
            if($chkRefeReq == "1"){
            ?>
            $("#ticketBookingTelephone, #ticketBookingAgency").removeClass("validate[required]");
            <?php
            } else {
            ?>
            $("#ticketBookingTelephone, #ticketBookingAgency, #ticketBoookingReference").removeClass("validate[required]");
            <?php
            }
            ?>
            $("#ticketBookingTelephone").removeAttr("class");
            // Change Shift
            <?php
            if($allowChangeShift && $user['User']['type'] == 2 && $editId == 0){
            ?>
            $("#btnChangeShiftBooked").hide();
            <?php
            }
            ?>
            resetChangeShift();
            var price = replaceNum($("input[name='data[TTicket][price_type]']:checked").attr("price"));
            $("#ticketBookingPriceTypeVip, #lalTicketBookingPriceTypeVip").show();
            if(val == '1'){ // Walk-IN
                <?php
                if($user['User']['type'] == 3 || $user['User']['type'] == 4){
                ?>
                $(".ticketBookingMarkupDiv").show();
                <?php
                }
                ?>
                $(".divReturnTrip").css("visibility", "visible");
                $("#ticketBookingTelephone").addClass("validate[custom[phone]]");
                <?php
                if($allowChangeShift && $user['User']['type'] == 2 && $editId == 0){
                ?>
                $("#btnChangeShiftBooked").show();
                <?php
                }
                ?>
            } else if(val == '2'){ // Phone Call
                $("#ticketBookingTelephone").addClass("validate[required, custom[phone]]");
                $(".seatDiscount").attr("readonly", true);
            } else if (val == '3'){ // Agency
                <?php
                if($user['User']['type'] == 2){ // User Internal
                ?>
                $(".ticketBookingAgencyDIv").show();
                $(".divReturnTrip").css("visibility", "visible");
                $("#ticketBoookingReference").addClass("validate[required]");
                // Set Price Type Default
                $("input[name='data[TTicket][price_type]']").removeAttr("checked");
                $("#ticketBookingPriceTypeDef").attr("checked", true);
                $("#ticketBookingPriceTypeVip, #lalTicketBookingPriceTypeVip").hide();
                price = replaceNum($("#ticketBookingPriceAgencyDef").val());
                <?php
                } else if($user['User']['type'] == 4) { // User Agency Online
                ?>
                $(".ticketBookingMarkupDiv").show();
                $(".divReturnTrip").css("visibility", "visible");
                <?php
                }
                ?>
                $("#ticketBookingTelephone").addClass("validate[custom[phone]]");
            }
            // Appy to Seat
            $(".tblTicketBookingSeatList").each(function(){
                $(this).find(".seatPrice").val(converDicemalJS(price).toFixed(2));
                $(this).find(".seatDiscount").val(0);
                $(this).find(".seatTotal").val(converDicemalJS(price).toFixed(2));
            });
            // Recheck Radio
            // $(".TTicketType").removeAttr("checked");
            // $(".TTicketType[value='"+val+"']").attr("checked", true);
            $("input[name='data[TTicket][price_type]']:checked").click();
            calTotalAmtBooking();
        });
        <?php
        if($user['User']['type'] == 3){ // Agency APi or Offline
        ?>
        $(".TTicketType").click();
        <?php
        } else {
            if($allowWalkIn == false && $allowPhoneCall == false && $allowSalesAgency == true){    
        ?>
        $(".TTicketType").click();
        <?php   
            }
        }
        if($isReturn != 1 && $editId == 0){
        ?>
        $("#ticketBookingCheckReturn").unbind("click").click(function(){
            $("#ticketBookingReturnDate").val("");
            $("#ticketBookingReturnOpenDate").attr("checked", false);
            // Change Shift
            <?php
            if($allowChangeShift && $user['User']['type'] == 2 && $editId == 0){
            ?>
            $("#btnChangeShiftBooked").hide();
            <?php
            }
            ?>
            resetChangeShift();
            if($(this).is(":checked")){
                $("#ticketBookingReturnDate").removeAttr("disabled");
                $("#ticketBookingReturnDate").addClass("validate[required]");
                $(".ticketBookingOpenDateDiv").show();
                $("#ticketIsReturn").val('2');
                $(".ui-dialog-buttonpane").find(".ui-dialog-buttonset").find(".ui-button").eq(0).find(".ui-button-text").text("<?php echo PAGE_NEXT; ?>");
            } else {
                $(".ticketBookingOpenDateDiv").hide();
                $("#ticketBookingReturnDate").attr("disabled", true);
                $("#ticketBookingReturnDate").removeClass("validate[required]");
                $("#ticketIsReturn").val('1');
                $(".ui-dialog-buttonpane").find(".ui-dialog-buttonset").find(".ui-button").eq(0).find(".ui-button-text").text("<?php echo ACTION_SAVE; ?>");
                <?php
                if($allowChangeShift && $user['User']['type'] == 2 && $editId == 0){
                ?>
                $("#btnChangeShiftBooked").show();
                <?php
                }
                ?>
            }
        });
        
        $("#ticketBookingReturnOpenDate").unbind("click").click(function(){
            if($(this).is(":checked")){
                $("#ticketBookingReturnDate").attr("disabled", true);
                $("#ticketIsOpenDate").val('1');
            } else {
                $("#ticketBookingReturnDate").removeAttr("disabled");
                $("#ticketIsOpenDate").val('0');
            }
        });
        
        $("#ticketBookingReturnDate").datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true
        });
        $("#ticketBookingReturnDate").datepicker( "option", "minDate", "<?php echo dateShort($date); ?>" );
        <?php
        }
        ?>

        $("input[name='data[TTicket][price_type]']").unbind("click").click(function(){
            var markup   = replaceNum($("#ticketBoookingMarkupItem").val());
            var price    = replaceNum($(this).attr("price")) + markup;
            <?php
            if($user['User']['type'] == 2){ // User Internal
            ?>
            if($(".TTicketType:checked").val() == '3'){ // Check Agency
                // Price Default
                var Agencyprice = replaceNum($("#ticketBookingPriceAgencyDef").val());
                if($(this).val() == '3'){ // Price Foreigner
                    Agencyprice = replaceNum($("#ticketBookingPriceAgencyFgn").val());
                }
                price = Agencyprice + markup;
            }
            <?php
            }
            ?>
            var discount = 0;
            $("#ticketBookingPrice").val(converDicemalJS(price).toFixed(2));
            // Check Discount Offer
            $("#ticketBookingDisOfferDiv, #ticketBookingDisOfferReferenceDiv").hide();
            $("#ticketBookingDisOfferReference").val('');
            $("#ticketBookingDiscountBy").find("option").attr("selected", false);
            $("#ticketBookingDiscountBy").find("option[value='']").attr("selected", true);
            $("input[name='data[TTicket][discount_type]']").removeAttr('checked');
            $("#discountTypeNone").attr('checked', true);
            if($(this).val() == '1'){
                $("#discountTypeCard, #discountTypeNone").show();
                $("label[for='discountTypeCard'], label[for='discountTypeNone']").show();
            } else {
                $("#discountTypeCard, #discountTypeNone").hide();
                $("label[for='discountTypeCard'], label[for='discountTypeNone']").hide();
            }
            // Appy to Seat
            $(".tblTicketBookingSeatList").each(function(){
                $(this).find(".seatPrice").val(converDicemalJS(price).toFixed(2));
                $(this).find(".seatDiscount").val(discount);
                $(this).find(".seatTotal").val(converDicemalJS(price - discount).toFixed(2));
            });
            calTotalAmtBooking();
        });

        $('.level-tab-booking').on('click', function(e) {
            e.preventDefault();
            var level = $(this).data('level');
            $('.level-tab-booking').removeClass('active');
            $(this).addClass('active');
            $('.layout-booking-container').hide();
            $('#booking-layout-level-' + level).show();
        });

        $(".ticketBookingSeatSelect").off("click").on("click", function(event) {
            if (event.target !== this) return;
            toggleSeatSelection($(this));
        });

        $(".btnDeleteBookingPhoneCall").off("dblclick").on("dblclick", function(event) {
            if (event.target !== this) return;
            var id   = $(this).attr('rel');
            var name = $(this).attr('data-name');
            var seatLayout = $(this);
            $("#dialog3").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_CONFIRM_CANCEL; ?> Seat # <b>' + name + '</b>?</p>');
            $("#dialog3").dialog({
                title: '<?php echo DIALOG_CONFIRMATION; ?>',
                resizable: false,
                modal: true,
                width: 'auto',
                height: 'auto',
                open: function(event, ui){
                    $(".ui-dialog-buttonpane").show();
                },
                buttons: {
                    '<?php echo ACTION_YES; ?>': function() {
                        $.ajax({
                            type: "GET",
                            url: "<?php echo $this->base.'/'.$this->params['controller']; ?>/voidSeat/" + id,
                            data: "",
                            beforeSend: function(){
                                $("#dialog3").dialog("close");
                                // modal box - open
                                $("#dialogModal").html('<p style="text-align: center;"><img alt="" src="<?php echo $this->webroot; ?>img/ajax-loader.gif" /></p>');
                                $("#dialogModal").dialog({
                                    title: '<?php echo DIALOG_LOADING; ?>',
                                    resizable: false,
                                    modal: true,
                                    closeOnEscape: false,
                                    width: 180,
                                    height: 80,
                                    open: function(event, ui){
                                        $(".ui-dialog-buttonpane").show();
                                        $(".ui-dialog-titlebar-close").hide();
                                    },
                                    close: function(event, ui){
                                        $(".ui-dialog-titlebar-close").show();
                                    }
                                });
                                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
                            },
                            success: function(result){
                                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                                $("#dialogModal").dialog("close");
                                // alert message
                                if(result != '<?php echo MESSAGE_DATA_HAS_BEEN_VOID; ?>'){
                                    createSysAct('Booking', 'Void Seat', 2, result);
                                    $("#dialog4").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_PROBLEM; ?></p>');
                                    $("#dialog4").dialog({
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
                                }else {
                                    createSysAct('Booking', 'Void Seat', 1, '');
                                    seatLayout.removeAttr("onmouseover");
                                    seatLayout.removeAttr("class");
                                    seatLayout.removeAttr("rel");
                                    seatLayout.removeAttr("data-name");
                                    seatLayout.removeAttr("style");
                                    var width = seatLayout.attr("wd");
                                    var height = seatLayout.attr("ht");
                                    seatLayout.attr("style", "cursor: pointer; border: 1px solid #ef4444; display: flex; align-items: center; justify-content: center; text-align: center; border-radius: 4px;box-sizing: border-box; width: "+width+"px; height: "+height+"px;");
                                    seatLayout.addClass("ticketBookingSeatSelect");
                                }
                                
                            }
                        });
                    },
                    '<?php echo ACTION_CANCEL; ?>': function() {
                        $(this).dialog("close");
                    }
                }
            });
        });
        
        // Discount
        $("#ticketBoookingMarkupItem").unbind("focus").focus(function(){
            if(replaceNum($(this).val()) == 0){
                $(this).val("");
            }
        });
        
        $("#ticketBoookingMarkupItem").unbind("blur").blur(function(){
            if($(this).val() == ""){
                $(this).val("0");
            }
            var price  = replaceNum($("#ticketBookingPrice").val());
            var markup = replaceNum($(this).val());
            $(".seatPrice").each(function(){
                $(this).val(price + markup);
                calTotalAmtBooking();
            });
        });
        <?php
        if($allowChangeShift && $user['User']['type'] == 2 && $editId == 0){
        ?>
        // $("#btnChangeShiftBooked").unbind("click").click(function(event){
        //     event.preventDefault();
        //     var isRoundTrip = 0;
        //     if($("#ticketBookingCheckReturn").is(':checked')){
        //         isRoundTrip = 1;
        //     }
        //     if(replaceNum($("input[name='data[TTicket][type]").val()) == 1 && isRoundTrip == 0){ // Allow Walk In & No Round Trip
        //         $.ajax({
        //             type:   "GET",
        //             url:    "<?php //echo $this->base . "/".$this->params['controller']."/changeShift/"; ?>",
        //             beforeSend: function(){
        //                 $(".loader").attr('src','<?php //echo $this->webroot; ?>img/layout/spinner.gif');
        //                 // modal box - open
        //                 $("#dialogModal").html('<p style="text-align: center;"><img alt="" src="<?php //echo $this->webroot; ?>img/ajax-loader.gif" /></p>');
        //                 $("#dialogModal").dialog({
        //                     title: '<?php //echo DIALOG_LOADING; ?>',
        //                     resizable: false,
        //                     modal: true,
        //                     closeOnEscape: false,
        //                     width: 180,
        //                     height: 100,
        //                     open: function(event, ui){
        //                         $(".ui-dialog-buttonpane").show();
        //                         $(".ui-dialog-titlebar-close").hide();
        //                     },
        //                     close: function(event, ui){
        //                         $(".ui-dialog-titlebar-close").show();
        //                     }
        //                 });
        //             },
        //             success: function(msg){
        //                 $(".loader").attr('src', '<?php //echo $this->webroot; ?>img/layout/spinner-placeholder.gif');
        //                 $("#dialogModal").dialog("close");
        //                 $("#dialog1").html(msg).dialog({
        //                     title: '<?php //echo "Change Shift"; ?> ',
        //                     resizable: false,
        //                     modal: true,
        //                     width: 480,
        //                     height: 550,
        //                     open: function(event, ui){
        //                         $(".ui-dialog-buttonpane").show();
        //                     },
        //                     buttons: {
        //                         '<?php //echo ACTION_SAVE; ?>': function() {
        //                             var checkSeat = "";
        //                             var totalSeat = 0;
        //                             var telephone = "";
        //                             $(".chkSeatChangeShift").each(function(index, value) {
        //                                 if($(this).is(':checked')){
        //                                     var id  = $(this).attr("t-id");
        //                                     var detailId  = $(this).attr("data");
        //                                     var seatLabel = $(this).attr("rel");
        //                                     var amount    = $(this).attr("amt");
        //                                     telephone     = $(this).attr("tel");
        //                                     // if(totalSeat > 0){
        //                                     //     checkSeat += ";";
        //                                     // }
        //                                     checkSeat += id+','+detailId+','+seatLabel+','+amount+';';
        //                                     totalSeat++;
        //                                 }
        //                             });
        //                             if(checkSeat != ''){
        //                                 resetChangeShift();
        //                                 $("#changeShiftData").val(checkSeat);
        //                                 $("#changeShiftTotalSeat").val(totalSeat);
        //                                 $("#ticketBookingTelephone").val(telephone);
        //                                 if(totalSeat > 0){
        //                                     $("#lblChangeShiftBooked").text("Shift ("+totalSeat+")");
        //                                 } else {
        //                                     $("#lblChangeShiftBooked").text("Shift");
        //                                 }
        //                                 // Reset Shift Selected
        //                                 $(".ticketBookingSeatSelect").each(function(){
        //                                     if($(this).attr("seatChecked") === "1"){
        //                                         // $(this).removeAttr("checked");
        //                                         $(this).click();
        //                                     }
        //                                 });
        //                                 $(this).dialog("close");
        //                             } else {
        //                                 $("#warningChageShift").show();
        //                             }
        //                         },
        //                         '<?php //echo "Reset"; ?>': function() {
        //                             resetChangeShift();
        //                             $(this).dialog("close");
        //                         },
        //                         '<?php //echo ACTION_CLOSE; ?>': function() {
        //                             $(this).dialog("close");
        //                         }
        //                     }
        //                 });
        //             }
        //         });
        //     } else {
        //         alert("This funcitonn allow only walk in and no round trip.");
        //     }
        // });
        <?php
        }
        if(!empty($tTicket)){
            if($tTicket['TTicket']['status'] == 1 && $tTicket['TTicket']['type'] == 2){
        ?>
        convertSeatToString();
        eventKeySeatBooking();
        calTotalAmtBooking();
        <?php
            }
        }
        if($isAgency == true && $usePriceDef == 2){  // Agency User Login
        ?>
        $("input[name='data[TTicket][price_type]']").removeAttr("checked");
        $("#ticketBookingPriceTypeFgn").attr("checked", true);
        var markup   = replaceNum($("#ticketBoookingMarkupItem").val());
        var price    = replaceNum($("input[name='data[TTicket][price_type]']:checked'").attr("price")) + markup;
        var discount = 0;
        $("#ticketBookingPrice").val(converDicemalJS(price).toFixed(2));
        // Check Discount Offer
        $("#ticketBookingDisOfferDiv, #ticketBookingDisOfferReferenceDiv").hide();
        $("#ticketBookingDisOfferReference").val('');
        $("#ticketBookingDiscountBy").find("option").attr("selected", false);
        $("#ticketBookingDiscountBy").find("option[value='']").attr("selected", true);
        $("input[name='data[TTicket][discount_type]']").removeAttr('checked');
        $("#discountTypeNone").attr('checked', true);
        if($(this).val() == '1'){
            $("#discountTypeCard, #discountTypeNone").show();
            $("label[for='discountTypeCard'], label[for='discountTypeNone']").show();
        } else {
            $("#discountTypeCard, #discountTypeNone").hide();
            $("label[for='discountTypeCard'], label[for='discountTypeNone']").hide();
        }
        // Appy to Seat
        $(".tblTicketBookingSeatList").each(function(){
            $(this).find(".seatPrice").val(converDicemalJS(price).toFixed(2));
            $(this).find(".seatDiscount").val(discount);
            $(this).find(".seatTotal").val(converDicemalJS(price - discount).toFixed(2));
        });
        calTotalAmtBooking();
        <?php
        }
        ?>
    });

    function toggleSeatSelection(seatElement) {
        const isSelected = seatElement.attr("seatChecked") === "1";
        seatElement.attr("seatChecked", isSelected ? "0" : "1")
                   .css("background-color", isSelected ? "" : "blue")
                   .css("color", isSelected ? "#000" : "#fff");
        
        handleSeatSelection(seatElement);
    }

    function handleSeatSelection(seatElement) {
        const isSelected = seatElement.attr("seatChecked") === "1";
        const seatValue = seatElement.attr("data");
        
        if (isSelected) {
            addSeatBooking(seatValue, seatElement.attr("lbl"));
        } else {
            removeSeatBooking(seatValue);
        }

        convertSeatToString();
    }

    function removeSeatBooking(seatValue) {
        $(".tblTicketBookingSeatList").find(".seatNumber").filter(function() {
            return $(this).val() == seatValue;
        }).closest("tr").remove();
        calTotalAmtBooking();
    }

    // function handleSeatSelection(seatCheckBox) {
    //     var row = seatCheckBox.closest("td");
    //     var val = seatCheckBox.val();
    //     var lbl = seatCheckBox.attr("lbl");
        
    //     console.log("Seat ID: " + val);
        
    //     if (seatCheckBox.is(":checked")) {
    //         row.find(".ticketBookingSeatColor").css('background', 'blue');
    //         addSeatBooking(val, lbl);
    //     } else {
    //         row.find(".ticketBookingSeatColor").css('background', 'none');
    //         $(".tblTicketBookingSeatList").find(".seatNumber").filter(function() {
    //             return $(this).val() == val;
    //         }).closest("tr").remove();
    //         calTotalAmtBooking();
    //     }
        
    //     convertSeatToString();
    // }

    function resetChangeShift(){
        <?php
        if($allowChangeShift && $user['User']['type'] == 2 && $editId == 0){
        ?>
        $("#changeShiftData").val("");
        $("#changeShiftTotalSeat").val("0");
        $("#lblChangeShiftBooked").text("Shift");
        <?php
        }
        ?>
        $(".tblTicketBookingSeatList").each(function(){
            $(this).find(".seatAmtChange").val("");
            $(this).find(".seatChangeTicketId").val("");
            $(this).find(".seatChangeTicketDetailId").val("");
        });
        calTotalAmtBooking();
    }
    
    function convertSeatToString(){
        var seatLabel = [];
        var totalSeat = 0;
        $(".ticketBookingSeatSelect").each(function(){
            if($(this).attr("seatChecked") === "1"){
                seatLabel.push($(this).attr('lbl'));
                totalSeat++;
            }
        });
        $("#ticketBookingSeatSelected").val(totalSeat);
        $("#ticketBookingSeatSpan").text(totalSeat);
    }
    
    function addSeatBooking(seatId, seatLabel){
        rowIndexBooking = Math.floor((Math.random() * 100000) + 1);
        var sysCode  = "<?php echo SERVER_ID; ?>SC"+randomString(15);
        var markup   = replaceNum($("#ticketBoookingMarkupItem").val());
        var price    = replaceNum($("#ticketBookingPrice").val()) + markup;
        var tr       = rowTableBooking.clone(true);
        var discount = 0;
        // Check Change Shift
        var seatShiftTicketId = "";
        var seatShiftDetailId = "";
        var seatShiftLbl      = "";
        var seatShiftAmount   = "";
        var seatShistLblAmt   = "";
        <?php
        if($allowChangeShift && $user['User']['type'] == 2 && $editId == 0){
        ?>
        if(replaceNum($("#changeShiftTotalSeat").val()) > 0){
            var shift = $("#changeShiftData").val().toString().split(";");
            $.each(shift, function(k, seatInfo) {
                if(seatInfo != ""){
                    var seatChangeInfo = seatInfo.toString().split(",");
                    var seatChangeTicketId = seatChangeInfo[0];
                    var seatChangeDetailId = seatChangeInfo[1];
                    var seatChangeLbl  = seatChangeInfo[2];
                    var seatChnageAmt  = seatChangeInfo[3];
                    var changeToken    = false;
                    $(".tblTicketBookingSeatList").each(function(){
                        if($(this).find(".seatChangeTicketDetailId").val() == seatChangeDetailId){
                            changeToken = true;
                        }
                    });
                    if(changeToken == false){
                        seatShiftTicketId = seatChangeTicketId;
                        seatShiftDetailId = seatChangeDetailId;
                        seatShiftLbl      = seatChangeLbl;
                        seatShiftAmount   = seatChnageAmt;
                        seatShistLblAmt   = " (-"+converDicemalJS(seatChnageAmt).toFixed(2)+")";
                        return false;
                    }
                }
            });
        }
        <?php
        }
        ?>
        tr.removeAttr("style").removeAttr("id");
        // Seat
        tr.find("td .seatSysCode").val(sysCode);
        tr.find("td .seatNumber").val(seatId);
        tr.find("td .seatLabel").val(seatLabel);
        tr.find("td .seatSelectedLabel").text(seatLabel);
        tr.find("td .seatShiftAmtLbl").text(seatShistLblAmt);
        // Lable Check
        tr.find("td .seatMale").attr("id", "seatMale"+rowIndexBooking);
        tr.find("td .seatFemale").attr("id", "seatFemale"+rowIndexBooking);
        tr.find("td .seatMale").attr("name", "chkGender"+rowIndexBooking);
        tr.find("td .seatFemale").attr("name", "chkGender"+rowIndexBooking);
        tr.find("td .seatPrice").attr("id", "seatPrice"+rowIndexBooking).val(price);
        tr.find("td .seatDiscount").attr("id", "seatDiscount"+rowIndexBooking).val(discount);
        tr.find("td .seatTotal").attr("id", "seatTotal"+rowIndexBooking).val(converDicemalJS(price - discount).toFixed(2));
        tr.find("td .seatAmtChange").attr("id", "seatAmtChange"+rowIndexBooking).val(seatShiftAmount);
        tr.find("td .seatChangeTicketId").attr("id", "seatChangeTicketId"+rowIndexBooking).val(seatShiftTicketId);
        tr.find("td .seatChangeTicketDetailId").attr("id", "seatChangeTicketDetailId"+rowIndexBooking).val(seatShiftDetailId);
        if($(".TTicketType:checked").val() == 2){ // Phone Call
            tr.find("td .seatDiscount").attr("readonly", true);
        }
        $("#tblTicketBookingSeat").append(tr);
        <?php
        if($journey['TJourney']['company_id'] == 17){ // Buva Sea Kampot Phú Quốc
        ?>
        var trPassport = rowTableBookingPassport.clone(true);
        trPassport.removeAttr("style").removeAttr("id");
        trPassport.find("td .seatName").attr("id", "seatName"+rowIndexBooking);
        trPassport.find("td .seatDob").attr("id", "seatDob"+rowIndexBooking);
        trPassport.find("td .seatPassport").attr("id", "seatPassport"+rowIndexBooking);
        trPassport.find("td .seatName").addClass("validate[required]");
        trPassport.find("td .seatPassport").addClass("validate[required]");
        trPassport.find("td .seatDob").addClass("validate[required]");
        $("#tblTicketBookingSeat").append(trPassport);
        <?php
        }
        ?>
        eventKeySeatBooking();
        calTotalAmtBooking();
    }
    
    function eventKeySeatBooking(){
        $(".seatDiscount, .chkSeatGender").unbind('click').unbind('blur').unbind('keyup');
        $(".float").autoNumeric({mDec: 0, aSep: ','});
        <?php
        if($journey['TJourney']['company_id'] == 17){ // Buva Sea Kampot Phú Quốc
        ?>
        $(".seatDob").datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true
        });
        <?php
        }
        ?>
        
        $('.float').unbind("cut copy paste").bind("cut copy paste",function() {
            return false;
        });
        
        $(".seatDiscount").focus(function(){
            if(replaceNum($(this).val()) == 0){
                $(this).val("");
            }
        });
        
        $(".seatDiscount").blur(function(){
            if($(this).val() == ''){
                $(this).val('0');
            }
        });
        
       $(".seatDiscount").keyup(function(){
           var dis   = replaceNum($(this).val());
           var price = replaceNum($(this).closest("tr").find(".seatPrice").val());
           if(dis > price){
               dis = price;
               $(this).val(converDicemalJS(price).toFixed(2));
           }
           calTotalAmtBooking();
       });
        
        $(".chkSeatGender").click(function(){
            $(this).closest("tr").find(".seatGender").val($(this).val());
        });
    }
    
    function calTotalAmtBooking(){
        var totalAmt     = 0;
        var totalDis     = replaceNum($("#ticketBookingTotalDiscount").val());
        // var disPercent   = replaceNum($("#ticketBookingDisPercent").val());
        var priceMarkup  = replaceNum($("#ticketBoookingMarkupItem").val());
        var luckyDrawFee = 0;
        var totalMarkup  = 0;
        var totalSeat    = 0;
        var totalVat     = 0;
        var totalChange  = 0;
        $(".tblTicketBookingSeatList").each(function(){
            var totalSeatPrice = replaceNum($(this).find(".seatPrice").val()) - replaceNum($(this).find(".seatDiscount").val());
            $(this).find(".seatTotal").val(converDicemalJS(totalSeatPrice).toFixed(2));
            totalMarkup += priceMarkup;
            totalAmt    += totalSeatPrice;
            totalChange += replaceNum($(this).find(".seatAmtChange").val());
            totalSeat++;
        });
        // if($("#chkTicketLuckyDraw").text() != ""){
        //     luckyDrawFee = converDicemalJS((0.25 * totalSeat));
        // }
        <?php
        if($allowVat == 1){
        ?>
        if(totalMarkup > 0){
            totalDis = (totalMarkup * 10) / 100;
        } else {
            totalDis = 0;
        }
        $("#ticketBookingTotalDiscount").val(totalDis);
        if(totalAmt > 0) {
            totalVat = converDicemalJS((totalAmt * 10) / 100);
        }
        <?php
        } else {
        ?>
        // if(disPercent > 0){
        //     totalDis = converDicemalJS(converDicemalJS(totalAmt * disPercent) / 100).toFixed(2);
        //     $("#ticketBookingTotalDiscount").val(totalDis);
        // }
        <?php
        }
        ?>
        var netAmt = totalAmt - replaceNum(totalDis) + totalVat + luckyDrawFee;
        if(replaceNum(totalChange) > replaceNum(netAmt)){
            totalChange = netAmt;
            netAmt = 0;
        } else {
            netAmt = netAmt - totalChange;
        }
        $("#ticketBookingTotalAmount").val(converDicemalJS(totalAmt).toFixed(2));
        $("#ticketBookingTotalVat").val(converDicemalJS(totalVat).toFixed(2));
        // $("#ticketBookingLuckyDrawFee").val(converDicemalJS(luckyDrawFee).toFixed(2));
        $("#ticketBookingTotalChange").val(converDicemalJS(totalChange).toFixed(2));
        $("#ticketBookingNetAmount").val(converDicemalJS(netAmt).toFixed(2));
    }
</script>
<br />
<div style="width: 995px;">
    <form id="ticketBookingForm" method="post" action="#">
        <input type="hidden" name="data[TTicket][sys_code]" value="<?php echo $sysCode; ?>" />
        <input type="hidden" id="ticketPriceSymbol" value="<?php echo $journey['CurrencyCenter']['symbol']; ?>" />
        <input type="hidden" name="data[TTicket][round_trip]" id="ticketIsReturn" value="1" />
        <input type="hidden" name="data[TTicket][return]" value="<?php echo $isReturn; ?>" />
        <input type="hidden" name="data[TTicket][is_open_date]" id="ticketIsOpenDate" value="0" />
        <?php
        if($allowChangeShift && $user['User']['type'] == 2 && $editId == 0){
        ?>
        <input type="hidden" id="changeShiftData" value="" />
        <input type="hidden" id="changeShiftTotalSeat" value="" />
        <?php
        }
        ?>
        <table cellpadding="5" cellspacing="0" style="width: 100%;">
            <tr>
                <td colspan="2">
                    <div class="radio-button-group ticket-type-selector">
                        <?php
                        $phoneCallChecked = "";
                        $agencyCheck = "";
                        if(!empty($tTicket) && $tTicket['TTicket']['status'] == 1 && $tTicket['TTicket']['type'] == 2){
                        ?>
                        <input type="radio" checked="" name="data[TTicket][type]" id="TTicketWalkIn" class="TTicketType" value="1" /><label for="TTicketWalkIn">Walk In</label>
                        <?php
                        } else {
                            if($user['User']['type'] == 3 || $user['User']['type'] == 4){
                        ?>
                        <input type="radio" name="data[TTicket][type]" id="TTicketAgency" class="TTicketType" value="3" checked="" /><label for="TTicketAgency">Agency</label>
                        <?php
                            } else {
                                if($allowWalkIn == false && $allowPhoneCall == false && $allowSalesAgency == false){    
                        ?>
                        <input type="radio" checked="" name="data[TTicket][type]" id="TTicketWalkIn" class="TTicketType" value="1" /><label for="TTicketWalkIn">Walk In</label>
                        <?php
                                } else {
                                    if($allowWalkIn){
                        ?>
                        <input type="radio" checked="" name="data[TTicket][type]" id="TTicketWalkIn" class="TTicketType" value="1" /><label for="TTicketWalkIn">Walk In</label>
                        <?php                
                                    }
                                    if($allowPhoneCall){
                                        if($allowWalkIn == false && $allowSalesAgency == false){
                                            $phoneCallChecked = ' checked=""';
                                        } else if($allowWalkIn == false && $allowSalesAgency == true){
                                            $phoneCallChecked = ' checked=""';
                                        }
                        ?>
                        <input type="radio"<?php echo $phoneCallChecked; ?> name="data[TTicket][type]" id="TTicketPhoneCall" class="TTicketType" value="2" /><label for="TTicketPhoneCall">Phone Call</label>
                        <?php
                                    }
                                    if($allowSalesAgency){
                                        if($allowWalkIn == false && $allowPhoneCall == false){
                                            $agencyCheck = ' checked=""';
                                        }
                        ?>
                        <input type="radio"<?php echo $agencyCheck; ?> name="data[TTicket][type]" id="TTicketAgency" class="TTicketType" value="3" /><label for="TTicketAgency">Agency</label>
                        <?php
                                    }
                        ?>
                        <?php
                                }
                            }
                        }
                        ?>
                    </div>
                </td>
                <td style="width: 9%;"><?php echo TABLE_TRAVEL_DATE; ?> :</td>
                <td style="width: 16%;">
                    <?php echo dateShort($date)." ".date("h:i A", strtotime($departure['TDepartureTime']['name'])); ?>
                </td>
                <td style="width: 11%;<?php if($phoneCallChecked != ""){ ?> visibility: hidden;<?php } ?>" class="divReturnTrip">
                    <?php if($returnTrip == false){ ?>
                    <div style="display: none;">
                    <?php 
                    }
                    if($isReturn != 1 && $editId == 0){
                    ?>
                    <label for="ticketBookingReturnDate"><?php echo TABLE_ROUND_TRIP; ?></label> <input type="checkbox" id="ticketBookingCheckReturn" />:
                    <?php
                    } 
                    ?>
                    <?php if($returnTrip == false){ ?>
                    </div>
                    <?php } ?>
                </td>
                <td style="width: 16%;<?php if($phoneCallChecked != ""){ ?> visibility: hidden;<?php } ?>" class="divReturnTrip">
                    <?php 
                    if($returnTrip == false){ ?>
                    <div style="display: none;">
                    <?php 
                    } 
                    if($isReturn != 1 && $editId == 0){
                    ?>
                    <input type="text" style="width: 80px; height: 20px;" id="ticketBookingReturnDate" autocomplete="off" placeholder="<?php echo TABLE_RETURN_DATE; ?>" disabled="" /> 
                    <?php
                        if($user['User']['type'] == 2){
                    ?>
                    <input type="checkbox" id="ticketBookingReturnOpenDate" class="ticketBookingOpenDateDiv" style="display: none;" /><label style="display: none;" for="ticketBookingReturnOpenDate" class="ticketBookingOpenDateDiv">Open</label>
                    <?php
                        } else {
                    ?>
                    <input type="checkbox" id="ticketBookingReturnOpenDate" style="display: none;" />
                    <?php
                        }
                    }
                    if($returnTrip == false){ ?>
                    </div>
                    <?php } ?>
                </td>
                <td style="width: 5%;">Note :</td>
                <td rowspan="3" style="vertical-align: top;">
                    <textarea style="height: 100px; width: 90%;" name="data[TTicket][note]"><?php echo $ticketNote; ?></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="font-size: 11px;">
                    <div class="radio-button-group price-type-selector">
                        <?php
                        $referRequired = "";
                        if($user['User']['type'] == 2){ // Staff Internal User
                        ?>
                        <!-- Price For Select Type Walk-In and Phone Call -->
                        <input type="radio" name="data[TTicket][price_type]" value="1" <?php if($priceType == 1){ ?>checked=""<?php } ?> price="<?php echo number_format($journeyDf, 2); ?>" id="ticketBookingPriceTypeDef" /> <label for="ticketBookingPriceTypeDef" style="font-size: 11px;">Khmer</label>
                        <input type="radio" name="data[TTicket][price_type]" value="3" <?php if($priceType == 3){ ?>checked=""<?php } ?> price="<?php echo number_format($journeyFor, 2); ?>" id="ticketBookingPriceTypeFgn" /> <label for="ticketBookingPriceTypeFgn" style="font-size: 11px;">Foreigner</label>
                        <input type="radio" name="data[TTicket][price_type]" value="2" <?php if($priceType == 2){ ?>checked=""<?php } ?> price="<?php echo number_format($journeyVip, 2); ?>" id="ticketBookingPriceTypeVip" /> <label for="ticketBookingPriceTypeVip" style="font-size: 11px;" id="lalTicketBookingPriceTypeVip">VIP</label>
                        <!-- Price For Select Type Agency -->
                        <?php
                        if($journeyAgPrFg == 0){
                            $journeyAgPrFg = $journeyAgPrKh;
                        }
                        ?>
                        <input type="hidden" value="<?php echo number_format($journeyAgPrKh, 2); ?>" id="ticketBookingPriceAgencyDef" />
                        <input type="hidden" value="<?php echo number_format($journeyAgPrFg, 2); ?>" id="ticketBookingPriceAgencyFgn" />
                        <?php 
                        } else { // Agency User
                            $sqlUAgen = mysql_query("SELECT * FROM t_agents WHERE user_id = ".$user['User']['id']." AND status = 1 LIMIT 1");
                            if(mysql_num_rows($sqlUAgen)){
                                $rowUAgen = mysql_fetch_array($sqlUAgen);
                                if($rowUAgen['id'] == 47 || $rowUAgen['id'] == 86 || $rowUAgen['id'] == 91){
                                    $referRequired = 'class="validate[required]"';
                                }
                            }
                            
                        ?>
                        <input type="radio" name="data[TTicket][price_type]" value="1" <?php if($priceType == 1){ ?>checked=""<?php } ?> price="<?php echo number_format($journeyDf, 2); ?>" net-price="<?php echo number_format($journeyAgPrKh, 2); ?>" id="ticketBookingPriceTypeDef" /> <label for="ticketBookingPriceTypeDef" style="font-size: 11px;">Khmer</label>
                        <input type="radio" name="data[TTicket][price_type]" value="3" <?php if($priceType == 3){ ?>checked=""<?php } ?> price="<?php echo number_format($journeyFor, 2); ?>" net-price="<?php echo number_format($journeyAgPrFg, 2); ?>" id="ticketBookingPriceTypeFgn" /> <label for="ticketBookingPriceTypeFgn" style="font-size: 11px;">Foreigner</label>
                        <?php
                        } ?>
                    </div>
                    <input type="text" name="data[TTicket][price]" style="width: 33px; height: 20px; font-size: 12px; margin-left: 5px;" id="ticketBookingPrice" class="float" value="<?php echo number_format($journeyPrice, 2); ?>" readonly="" /> <?php echo $journey['CurrencyCenter']['symbol']; ?>
                </td>
                <td>
                    <label for="ticketBookingDiscount" class="ticketBookingMarkupDiv" <?php if($user['User']['type'] != 3 && $user['User']['type'] != 4){ ?>style="display: none;"<?php } ?>><?php echo TABLE_MARKUP; ?> :</label>
                    <label for="ticketBookingAgency" class="ticketBookingAgencyDIv" style="display: none;"><?php echo MENU_AGENT; ?> :</label>
                </td>
                <td colspan="2">
                    <div class="inputContainer ticketBookingMarkupDiv" style="width: 100%; <?php if($user['User']['type'] != 3 && $user['User']['type'] != 4){ ?>display: none;<?php } ?>">
                        <input type="text" style="width: 50%; height: 20px;" class="float" maxlength="6" id="ticketBoookingMarkupItem" value="0" name="data[TTicket][total_markup]" /> <?php echo $journey['CurrencyCenter']['symbol']; ?>
                    </div>
                    <div class="inputContainer ticketBookingAgencyDIv" style="width: 100%; display: none;">
                        <?php
                        if($user['User']['type'] == 3 || $user['User']['type'] == 4){
                            $sqlAg = mysql_query("SELECT t_agents.id, t_agents.name, t_agents.type FROM t_agents WHERE t_agents.status = 1 AND t_agents.user_id = ".$user['User']['id']." AND t_agents.offline_project_id = 1");
                        } else {
                            $sqlAg = mysql_query("SELECT t_agents.id, t_agents.name, t_agents.type FROM t_agents WHERE t_agents.status = 1 AND t_agents.type = 2 AND t_agents.main_branch_id = ".$user['User']['main_branch_id']." AND offline_project_id = 1");
                        }
                        ?>
                        <select style="width: 95%; height: 25px;" id="ticketBookingAgency" name="data[TTicket][t_agent_id]">
                            <?php
                            if($user['User']['type'] == 2){
                            ?>
                            <option value=""><?php echo INPUT_SELECT; ?></option>
                            <?php
                            }
                            while($rowAg = mysql_fetch_array($sqlAg)){
                                if($user['User']['type'] == 3 || $user['User']['type'] == 4){
                                    $agencyType = $rowAg['type'];
                                }
                            ?>
                            <option value="<?php echo $rowAg['id']; ?>"><?php echo $rowAg['name']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </td>
                <td>
                    
                </td>
            </tr>
            <tr>
                <td style="width: 12%;"><?php echo TABLE_BOARDING_POINT; ?> :</td>
                <td style="width: 16%;">
                    <select style="width: 150px; height: 25px;" id="ticketBookingBoardingPoint" name="data[TTicket][t_boarding_point_id]">
                        <?php
                        $sqlBP = mysql_query("SELECT t_boarding_points.id, t_boarding_points.name, t_journey_boarding_points.time FROM t_journey_boarding_points INNER JOIN t_boarding_points ON t_boarding_points.id = t_journey_boarding_points.t_boarding_point_id WHERE t_journey_boarding_points.t_journey_id = ".$journey['TJourney']['id']);
                        while($rowBP = mysql_fetch_array($sqlBP)){
                            $time = explode(":", $rowBP['time']);
                        ?>
                        <option value="<?php echo $rowBP['id']; ?>"><?php echo $rowBP['name']; ?> (<?php echo $time[0].":".$time[1]; ?>)</option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td><?php echo TABLE_DROP_OFF; ?> :</td>
                <td>
                    <select style="width: 150px; height: 25px;" id="ticketBookingDropOff" name="data[TTicket][t_drop_off_id]">
                        <?php
                        $sqlDF = mysql_query("SELECT t_drop_offs.id, t_drop_offs.name FROM t_journey_drop_offs INNER JOIN t_drop_offs ON t_drop_offs.id = t_journey_drop_offs.t_drop_off_id WHERE t_journey_drop_offs.t_journey_id = ".$journey['TJourney']['id']);
                        while($rowDF = mysql_fetch_array($sqlDF)){
                        ?>
                        <option value="<?php echo $rowDF['id']; ?>"><?php echo $rowDF['name']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td colspan="2">
                    <div class="inputContainer" style="width: 100%;">
                        <input type="text" name="data[TTicket][agt_refer_code]" style="width: 95%; height: 25px;" id="ticketBoookingReference" placeholder="<?php echo "Ticket Reference"; ?>" <?php echo $referRequired; ?> /> 
                    </div>
                </td>
            </tr>
        </table>
        <hr style="width: 100%" />
        <br/>
        <div style="width: 50%; float: left;">
            <div style="height: 415px; overflow-x: scroll; overflow-y: scroll;">
                <div style="font-size: 14px; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #000;"><?php __(TABLE_SEAT_INFORMATION); ?></div>
                <input type="hidden" name="data[TTicket][total_seat]" id="ticketBookingSeatSelected" style="width: 50px; height: 20px; border: none; font-size: 14px;" value="0" readonly="" />
                <?php
                $chkChangeSeat = false;
                $totalSAvbl    = 0;
                $totalSBooked  = 0;
                $totalSSold    = 0;
                $totalSAgOff   = 0;
                $totalSAgOnl   = 0;
                $totalSBusy    = 0;
                $layouts       = json_decode($tTransportaion['TTransportationType']['layout'], true);
                $tableLayout   = '';
                $totalCol      = 0;
                $seatInactive  = array();
                $destBooked    = array();
                $seatImg       = 'seat-sitting-32.png';
                $tableWidth    = 32;
                $tableHeight   = 32;
                $seatChkMargin = 10;
                if($tTransportaion['TTransportationType']['seat_type'] == 2){
                    $seatImg = 'seat-sleeper-32.png';
                    $tableHeight = 60;
                    $seatChkMargin = 25;
                }
                // Get Seat In Active
                if($journey['TJourney']['type'] == 3){
                    $seatBooked = array();
                    $sqlTransit = mysql_query("SELECT t_transportation_type_id, t_route_id, t_journeys.id AS journey_id, t_journey_transits.is_next_day 
                                               FROM t_journeys 
                                               INNER JOIN t_journey_transits ON t_journey_transits.t_journey_departure_id = t_journeys.id
                                               WHERE t_journey_transits.t_journey_id = ".$journey['TJourney']['id']." 
                                               GROUP BY t_journey_departure_id");
                    while($rowTransit = mysql_fetch_array($sqlTransit)){
                        $travelDate   = $date;
                        if($rowTransit['is_next_day'] == 1){
                            $travelDate = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                        }
                        // Get Seat Booked
                        $sqlSeat = mysql_query("SELECT seat_number, t_ticket_id, t_ticket_api_tmp_id, t_ticket_detail_id, status, gender FROM t_seat_controls WHERE t_transportation_type_id = ".$rowTransit['t_transportation_type_id']." AND t_route_id = ".$rowTransit['t_route_id']." AND journey_date = '".$travelDate."' AND status IN (1,2,3,4)");
                        while($rowSeat = mysql_fetch_array($sqlSeat)){
                            if (!array_key_exists($rowSeat['seat_number'], $seatBooked)) {
                                $seatBooked[$rowSeat['seat_number']]['ticket_id'] = $rowSeat['t_ticket_id'];
                                $seatBooked[$rowSeat['seat_number']]['t_ticket_api_tmp_id'] = $rowSeat['t_ticket_api_tmp_id'];
                                $seatBooked[$rowSeat['seat_number']]['t_ticket_detail_id'] = $rowSeat['t_ticket_detail_id'];
                                $seatBooked[$rowSeat['seat_number']]['status'] = $rowSeat['status'];
                                $seatBooked[$rowSeat['seat_number']]['gender'] = $rowSeat['gender'];
                            }
                        }
                    }
                    foreach($seatBooked AS $key => $tSeatControll){
                        $destId     = '';
                        $destTo     = '';
                        $origin     = '';
                        $mainBranch = '';
                        $agencyId   = '';
                        $agencyName = '';
                        $username   = '';
                        $ticketCode = '';
                        $ticketTel  = '';
                        $agencyRef  = '';
                        $note = '';
                        if(!empty($tSeatControll['ticket_id']) || !empty($tSeatControll['t_ticket_api_tmp_id'])){
                            if(!empty($tSeatControll['ticket_id'])){
                                $bookingStatus = 1;
                                $sqlTicket = mysql_query("SELECT * FROM t_tickets WHERE id = ".$tSeatControll['ticket_id']."
                                                          UNION ALL
                                                          SELECT * FROM t_ticket_3months WHERE id = ".$tSeatControll['ticket_id']);
                            } else {
                                $bookingStatus = 2;
                                $sqlTicket = mysql_query("SELECT * FROM t_ticket_api_tmps WHERE id = ".$tSeatControll['t_ticket_api_tmp_id']);
                            }
                            $rowTicket  = mysql_fetch_array($sqlTicket);
                            $ticketCode = $rowTicket['code'];
                            $ticketTel  = $rowTicket['telephone'];
                            $agencyRef  = $rowTicket['agt_refer_code'];
                            $note       = $rowTicket['note'];
                            // Origin
                            if(!empty($rowTicket['t_destination_from_id'])){
                                $sqlDest = mysql_query("SELECT id, name FROM t_destinations WHERE id = ".$rowTicket['t_destination_from_id']);
                                $rowDest = mysql_fetch_array($sqlDest);
                                $origin  = $rowDest['name'];
                            }
                            // Destination To
                            if(!empty($rowTicket['t_destination_to_id'])){
                                $sqlDest = mysql_query("SELECT id, name FROM t_destinations WHERE id = ".$rowTicket['t_destination_to_id']);
                                $rowDest = mysql_fetch_array($sqlDest);
                                $destId  = $rowDest['id'];
                                $destTo  = $rowDest['name'];
                            }
                            // Main Branch
                            if(!empty($rowTicket['main_branch_id'])){
                                $sqlMain = mysql_query("SELECT name FROM main_branches WHERE id = ".$rowTicket['main_branch_id']);
                                $rowMain = mysql_fetch_array($sqlMain);
                                $mainBranch  = $rowMain[0];
                            }
                            if($bookingStatus == 1){ // Ticket Sold
                                // Agency
                                if(!empty($rowTicket['t_agent_id'])){
                                    $sqlAgt = mysql_query("SELECT id, name, type FROM t_agents WHERE id = ".$rowTicket['t_agent_id']);
                                    $rowAgt = mysql_fetch_array($sqlAgt);
                                    $agencyId   = $rowAgt['id'];
                                    $agencyName = $rowAgt['name'];
                                    if($rowAgt['type'] == 2){ // Offline
                                        $seatInactive[$key]['status'] = 6;
                                    } else {
                                        $seatInactive[$key]['status'] = 7;
                                    }
                                    $username = $rowAgt['name'];
                                } else {
                                    if($rowTicket['type'] == 5){ // APP
                                        $agencyName = "APP";
                                        $seatInactive[$key]['status'] = 7;
                                        $username = "App";
                                    } else {
                                        if($tSeatControll['status'] != 3){
                                            if($rowTicket['api_bank_ref'] != ""){
                                                $username = "Phone Call Paid on Terminal";
                                            } else {
                                                // User
                                                if($rowTicket['confirm_by'] != ''){
                                                    $createdBy = $rowTicket['confirm_by'];
                                                } else {
                                                    $createdBy = $rowTicket['created_by'];
                                                }
                                                if(!empty($createdBy)){
                                                    $sqlUser = mysql_query("SELECT first_name, last_name FROM users WHERE id = ".$createdBy);
                                                    $rowUser = mysql_fetch_array($sqlUser);
                                                    $username = $rowUser['first_name']." ".$rowUser['last_name'];
                                                } else {
                                                    $username = "";
                                                }
                                            }
                                        } else {
                                            $username = "";
                                        }
                                        $seatInactive[$key]['status'] = $tSeatControll['status'];
                                    }
                                }
                            } else { // Busy Online Booking
                                $seatInactive[$key]['status'] = 3; // Busy
                            }
                        }
                        if(!empty($tTicket)){
                            if($tTicket['TTicket']['status'] == 1 && $tTicket['TTicket']['type'] == 2){
                                if($tSeatControll['ticket_id'] == $tTicket['TTicket']['id']){
                                    $seatInactive[$key]['status'] = 4; // Phone Call (Checked)
                                }
                            }
                        } else {
                            if($tSeatControll['status'] == 4){ // Check Seat Staus Miss Travel
                                $seatInactive[$key]['status'] = 3;
                            }
                        }
                        if($tSeatControll['gender'] == 1){
                            $seatInactive[$key]['gender'] = '('.TABLE_M.')';
                        } else if ($tSeatControll['gender'] == 2){
                            $seatInactive[$key]['gender'] = '('.TABLE_F.')';
                        } else {
                            $seatInactive[$key]['gender'] = '';
                        }
                        $seatInactive[$key]['code']   = $ticketCode;
                        $seatInactive[$key]['tel']    = $ticketTel;
                        $seatInactive[$key]['user']   = $username;
                        $seatInactive[$key]['origin'] = $origin;
                        $seatInactive[$key]['dest']   = $destTo;
                        $seatInactive[$key]['branch'] = $mainBranch;
                        $seatInactive[$key]['agencyId'] = $agencyId;
                        $seatInactive[$key]['agency']   = $agencyName;
                        $seatInactive[$key]['reference'] = $agencyRef;
                        $seatInactive[$key]['note'] = $note;
                        $seatInactive[$key]['t_ticket_detail_id'] = $tSeatControll['t_ticket_detail_id'];
                        if($destId != ''){
                            if (!array_key_exists($destId, $destBooked)) {
                                $destBooked[$destId]['name']  = $destTo;
                                $destBooked[$destId]['total'] = 1;
                            } else {
                                $destBooked[$destId]['total'] += 1;
                            }
                        }
                    }
                } else {
                    foreach($tSeatControlls AS $tSeatControll){
                        $bookingStatus = 1;
                        if(!empty($tSeatControll['TSeatControl']['t_ticket_id']) || !empty($tSeatControll['TSeatControl']['t_ticket_api_tmp_id'])){
                            if(!empty($tSeatControll['TSeatControl']['t_ticket_id'])){
                                $sqlTck = mysql_query("SELECT * FROM t_tickets WHERE id = ".$tSeatControll['TSeatControl']['t_ticket_id']."
                                                       UNION ALL
                                                       SELECT * FROM t_ticket_3months WHERE id = ".$tSeatControll['TSeatControl']['t_ticket_id']);
                            } else {
                                $bookingStatus = 2;
                                $sqlTck = mysql_query("SELECT * FROM t_ticket_api_tmps WHERE id = ".$tSeatControll['TSeatControl']['t_ticket_api_tmp_id']);
                            }
                            $rowTck = mysql_fetch_array($sqlTck);
                            $tSeatControll['TTicket']['id'] = $rowTck['id'];
                            $tSeatControll['TTicket']['t_journey_id'] = $rowTck['t_journey_id'];
                            $tSeatControll['TTicket']['confirm_by']   = $rowTck['confirm_by'];
                            $tSeatControll['TTicket']['created_by']   = $rowTck['created_by'];
                            $tSeatControll['TTicket']['t_destination_to_id'] = $rowTck['t_destination_to_id'];
                            $tSeatControll['TTicket']['code']       = $rowTck['code'];
                            $tSeatControll['TTicket']['telephone']  = $rowTck['telephone'];
                            $tSeatControll['TTicket']['price_type'] = $rowTck['price_type'];
                            $tSeatControll['TTicket']['t_agent_id'] = $rowTck['t_agent_id'];
                            $tSeatControll['TTicket']['agt_refer_code'] = $rowTck['agt_refer_code'];
                            $tSeatControll['TTicket']['note']  = $rowTck['note'];
                            $tSeatControll['TTicket']['main_branch_id'] = $rowTck['main_branch_id'];
                            $tSeatControll['TTicket']['t_destination_from_id'] = $rowTck['t_destination_from_id'];
                            $tSeatControll['TTicket']['api_bank_ref'] = $rowTck['api_bank_ref'];
                            $tSeatControll['TTicket']['type'] = $rowTck['type'];
                        }
                        // Destination To
                        $destId     = '';
                        $ticketCode = "";
                        $ticketTel  = "";
                        $agencyRef  = "";
                        $note       = "";
                        $mainBranch = '';
                        $agencyId   = '0';
                        $agencyName = '';
                        $username   = '';
                        $origin     = '';
                        $destTo     = '';
                        // Check Journey Booked
                        if(!empty($tSeatControll['TTicket']['t_journey_id'])){
                            $ticketCode = $tSeatControll['TTicket']['code'];
                            $ticketTel  = $tSeatControll['TTicket']['telephone'];
                            $agencyRef  = $tSeatControll['TTicket']['agt_refer_code'];
                            $note       = $tSeatControll['TTicket']['note'];
                            $sqlJour = mysql_query("SELECT * FROM t_journeys WHERE id = ".$tSeatControll['TTicket']['t_journey_id']);
                            $rowJour = mysql_fetch_array($sqlJour);
                            if($rowJour['type'] == 3){
                                if(!empty($journey['TJourney']['t_destination_to_id'])){
                                    $sqlDest = mysql_query("SELECT id, name FROM t_destinations WHERE id = ".$journey['TJourney']['t_destination_to_id']);
                                    $rowDest = mysql_fetch_array($sqlDest);
                                    $destId  = $rowDest['id'];
                                    $destTo  = $rowDest['name'];
                                }
                            } else {
                                if(!empty($tSeatControll['TTicket']['t_destination_to_id'])){
                                    $sqlDest = mysql_query("SELECT id, name FROM t_destinations WHERE id = ".$tSeatControll['TTicket']['t_destination_to_id']);
                                    $rowDest = mysql_fetch_array($sqlDest);
                                    $destId  = $rowDest['id'];
                                    $destTo  = $rowDest['name'];
                                }
                            }
                            if(!empty($tSeatControll['TTicket']['main_branch_id'])){
                                $sqlMain = mysql_query("SELECT name FROM main_branches WHERE id = ".$tSeatControll['TTicket']['main_branch_id']);
                                $rowMain = mysql_fetch_array($sqlMain);
                                $mainBranch  = $rowMain[0];
                            }
                        }
                        if(!empty($tSeatControll['TTicket']['t_destination_from_id'])){
                            $sqlDest = mysql_query("SELECT id, name FROM t_destinations WHERE id = ".$tSeatControll['TTicket']['t_destination_from_id']);
                            $rowDest = mysql_fetch_array($sqlDest);
                            $origin  = $rowDest['name'];
                        }
                        if($bookingStatus == 1){ // Ticket Sold
                            if(!empty($tSeatControll['TTicket']['t_agent_id'])){
                                $sqlAgt = mysql_query("SELECT id, name, type FROM t_agents WHERE id = ".$tSeatControll['TTicket']['t_agent_id']);
                                $rowAgt = mysql_fetch_array($sqlAgt);
                                $agencyId   = $rowAgt['id'];
                                $agencyName = $rowAgt['name'];
                                if($rowAgt['type'] == 2){ // Offline
                                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['status'] = 6;
                                } else {
                                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['status'] = 7;
                                }
                                $username = $rowAgt['name'];
                            } else {
                                if($tSeatControll['TTicket']['type'] == 5){ // APP
                                    $agencyName = "APP";
                                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['status'] = 7;
                                    $username = "App";
                                } else {
                                    if($tSeatControll['TSeatControl']['status'] != 3){
                                        if($tSeatControll['TTicket']['api_bank_ref'] != ""){
                                            $username = "Phone Call Paid on Terminal";
                                        } else {
                                            // User
                                            if($tSeatControll['TTicket']['confirm_by'] != ''){
                                                $createdBy = $tSeatControll['TTicket']['confirm_by'];
                                            } else {
                                                $createdBy = $tSeatControll['TTicket']['created_by'];
                                            }
                                            if(!empty($createdBy)){
                                                $sqlUser = mysql_query("SELECT first_name, last_name FROM users WHERE id = ".$createdBy);
                                                $rowUser = mysql_fetch_array($sqlUser);
                                                $username = $rowUser['first_name']." ".$rowUser['last_name'];
                                            }
                                        }
                                    } else {
                                        $username = "";
                                    }
                                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['status'] = $tSeatControll['TSeatControl']['status'];
                                }
                            }
                        } else { // Busy Online Booking
                            $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['status'] = 3;
                        }
                        if(!empty($tTicket)){
                            if($tTicket['TTicket']['status'] == 1 && $tTicket['TTicket']['type'] == 2){
                                if($tSeatControll['TSeatControl']['t_ticket_id'] == $tTicket['TTicket']['id']){
                                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['status'] = 4; // Phone Call (Checked)
                                }
                            }
                        } else {
                            if($tSeatControll['TSeatControl']['status'] == 4){ // Check Seat Staus Miss Travel
                                $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['status'] = 3;
                            }
                        }
                        if($tSeatControll['TSeatControl']['gender'] == 1){
                            $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['gender'] = '('.TABLE_M.')';
                        } else if ($tSeatControll['TSeatControl']['gender'] == 2){
                            $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['gender'] = '('.TABLE_F.')';
                        } else {
                            $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['gender'] = '';
                        }
                        $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['code']   = $ticketCode;
                        $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['tel']    = $ticketTel;
                        $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['user']   = $username;
                        $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['origin'] = $origin;
                        $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['dest']   = $destTo;
                        $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['branch'] = $mainBranch;
                        $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['agencyId']  = $agencyId;
                        $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['agency']    = $agencyName;
                        $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['reference'] = $agencyRef;
                        $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['note'] = $note;
                        $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['t_ticket_detail_id'] = $tSeatControll['TSeatControl']['t_ticket_detail_id'];
                        if($destId != ''){
                            if (!array_key_exists($destId, $destBooked)) {
                                $destBooked[$destId]['name']  = $destTo;
                                $destBooked[$destId]['total'] = 1;
                            } else {
                                $destBooked[$destId]['total'] += 1;
                            }
                        }
                    }
                }
                // Check Seat Block
                $sqlBlock = mysql_query("SELECT t_journey_seat_blocks.id, t_journey_seat_blocks.type, t_journey_seat_blocks.start, t_journey_seat_blocks.end, t_journey_seat_block_details.seat_number 
                                         FROM t_journey_seat_blocks 
                                         INNER JOIN t_journey_seat_block_details ON t_journey_seat_block_details.t_journey_seat_block_id = t_journey_seat_blocks.id 
                                         WHERE t_journey_seat_blocks.start <= '".$date."' AND t_journey_seat_blocks.end >= '".$date."' AND t_journey_seat_blocks.t_journey_id = ".$journey['TJourney']['id']." AND t_journey_seat_blocks.t_departure_time_id = ".$journey['TJourney']['t_departure_time_id']." AND t_journey_seat_blocks.is_active = 1");
                while($rowBlock = mysql_fetch_array($sqlBlock)){
                    if($rowBlock['type'] == 2){
                        if(strtotime($rowBlock['end']) < strtotime(date("Y-m-d"))){
                            // Update Expired
                            mysql_query("UPDATE t_journey_seat_blocks SET is_active = 3 WHERE id = ".$rowBlock['id']);
                        } else {
                            $seatInactive[$rowBlock['seat_number']]['status'] = 3; // Busy (Block)
                            $seatInactive[$rowBlock['seat_number']]['gender'] = '';
                            $seatInactive[$rowBlock['seat_number']]['code']   = "";
                            $seatInactive[$rowBlock['seat_number']]['tel']    = "";
                            $seatInactive[$rowBlock['seat_number']]['user']   = "";
                            $seatInactive[$rowBlock['seat_number']]['origin'] = "";
                            $seatInactive[$rowBlock['seat_number']]['dest']   = "";
                            $seatInactive[$rowBlock['seat_number']]['branch'] = "";
                            $seatInactive[$rowBlock['seat_number']]['agencyId'] = "";
                            $seatInactive[$rowBlock['seat_number']]['agency'] = "";
                            $seatInactive[$rowBlock['seat_number']]['reference'] = "";
                            $seatInactive[$rowBlock['seat_number']]['note'] = "Seat Block By Branch";
                            $seatInactive[$rowBlock['seat_number']]['t_ticket_detail_id'] = "";
                        }
                    } else if($rowBlock['type'] == 3){
                        $seatInactive[$rowBlock['seat_number']]['status'] = 3; // Busy (Block)
                        $seatInactive[$rowBlock['seat_number']]['gender'] = '';
                        $seatInactive[$rowBlock['seat_number']]['code']   = "";
                        $seatInactive[$rowBlock['seat_number']]['tel']    = "";
                        $seatInactive[$rowBlock['seat_number']]['user']   = "";
                        $seatInactive[$rowBlock['seat_number']]['origin'] = "";
                        $seatInactive[$rowBlock['seat_number']]['dest']   = "";
                        $seatInactive[$rowBlock['seat_number']]['branch'] = "";
                        $seatInactive[$rowBlock['seat_number']]['agencyId']  = "";
                        $seatInactive[$rowBlock['seat_number']]['agency']    = "";
                        $seatInactive[$rowBlock['seat_number']]['reference'] = "";
                        $seatInactive[$rowBlock['seat_number']]['note'] = "";
                        $seatInactive[$rowBlock['seat_number']]['t_ticket_detail_id'] = "";
                    }
                }
                if (is_array($layouts) && !empty($layouts['seats'])) {
                    $totalLevels = isset($layouts['total_levels']) ? (int)$layouts['total_levels'] : 1;
                    $levelNames = isset($layouts['level_names']) ? $layouts['level_names'] : [];
                ?>
                    <div id="level-tabs-container-booking" style="padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #ccc; width: 99%;">
                        <?php for ($l = 1; $l <= $totalLevels; $l++) : ?>
                            <?php $levelName = isset($levelNames[$l - 1]) && !empty($levelNames[$l - 1]) ? $levelNames[$l - 1] : "Level " . $l; ?>
                            <a href="#" class="level-tab-booking <?php echo $l === 1 ? 'active' : ''; ?>" data-level="<?php echo $l; ?>"><?php echo h($levelName); ?></a>
                        <?php endfor; ?>
                    </div>
                <?php
                    for ($levelNum = 1; $levelNum <= $totalLevels; $levelNum++) :
                        $displayStyle = $levelNum === 1 ? '' : 'display:none;';
                        $levelTableLayout = '';
                        $rows = $layouts['rows'];
                        $columns = $layouts['columns'];
                        $levelSeats = array_filter($layouts['seats'], function ($seat) use ($levelNum) {
                            return (isset($seat['level']) && $seat['level'] == $levelNum) || (!isset($seat['level']) && $levelNum == 1);
                        });

                        // Create a grid to handle span placeholders
                        $grid = array_fill(0, $rows, array_fill(0, $columns, null));

                        foreach ($levelSeats as $seat) {
                            $r = $seat['row'] - 1;
                            $c = $seat['column'] - 1;
                            if ($grid[$r][$c] === null) {
                                $grid[$r][$c] = $seat;
                                for ($i = 0; $i < $seat['rowspan']; $i++) {
                                    for ($j = 0; $j < $seat['colspan']; $j++) {
                                        if ($i == 0 && $j == 0) continue;
                                        if (isset($grid[$r + $i][$c + $j])) {
                                            $grid[$r + $i][$c + $j] = 'SPAN_PLACEHOLDER';
                                        }
                                    }
                                }
                            }
                        }

                        for ($r = 0; $r < $rows; $r++) {
                            $levelTableLayout .= '<tr>';
                            for ($c = 0; $c < $columns; $c++) {
                                if ($grid[$r][$c] === 'SPAN_PLACEHOLDER') continue;

                                $seat = $grid[$r][$c];
                                if (is_array($seat)) {
                                    $colspan = isset($seat['colspan']) ? $seat['colspan'] : 1;
                                    $rowspan = isset($seat['rowspan']) ? $seat['rowspan'] : 1;
                                    $value = $seat['value'];
                                    $label = isset($seat['label']) ? h($seat['label']) : h($value);

                                    $attrCol = 'colspan="' . $colspan . '" rowspan="' . $rowspan . '"';

                                    if (is_numeric($value)) {
                                        $levelTableLayout .= '<td ' . $attrCol . ' style="height: ' . $tableHeight . 'px; width: ' . $tableWidth . 'px; text-align: center; vertical-align: middle; font-size: 10px; padding: 2px;">';

                                        $seatColor = '';
                                        $checked = '';
                                        $seatDelete = '';
                                        if (!empty($seatInactive[$value])) {
                                            $mouseOver = '';
                                            $hover = '';

                                            if ($user['User']['type'] == 2) {
                                                // Internal User view with colors from code 2
                                                switch ($seatInactive[$value]['status']) {
                                                    case 1: $seatColor = 'background: yellow; color: black;'; $seatDelete = ' class="btnDeleteBookingPhoneCall" rel="'.$seatInactive[$value]['t_ticket_detail_id'].'" data-name="'.$seatInactive[$value]['code'].'"'; $totalSBooked++; break; // Phone Call
                                                    case 2: $seatColor = 'background: green; color: #fff;'; $totalSSold++; break; // Sold
                                                    case 3: $seatColor = 'background: #f1f5f9; color: black;'; break; // Busy
                                                    case 4: $checked = '1'; break; // Selected
                                                    case 6: $seatColor = 'background: greenyellow; color: black;'; break; // Agency Offline
                                                    case 7: $seatColor = 'background: #F6921E; color: #fff;'; break; // Agency Online
                                                }
                                                // Build Tooltip for Internal Users
                                                $mouseOverParts = [
                                                    TABLE_TICKET_CODE => $seatInactive[$value]['code'],
                                                    TABLE_ORIGIN => $seatInactive[$value]['origin'],
                                                    TABLE_DESTINATION_TO => $seatInactive[$value]['dest'],
                                                    TABLE_TELEPHONE => $seatInactive[$value]['tel'],
                                                    TABLE_SOLD_BY => $seatInactive[$value]['user'],
                                                    TABLE_BRANCH => $seatInactive[$value]['branch']
                                                ];
                                                if (!empty($seatInactive[$value]['agency'])) {
                                                    $mouseOverParts[MENU_AGENT] = $seatInactive[$value]['agency'];
                                                    $mouseOverParts[TABLE_AGENT_REFERENCE] = $seatInactive[$value]['reference'];
                                                }
                                                $mouseOverParts[TABLE_NOTE] = $seatInactive[$value]['note'];

                                                foreach ($mouseOverParts as $key => $val) {
                                                    $mouseOver .= '<b>' . $key . ':</b><br/>' . h($val) . '<br/>';
                                                }
                                            } else { // Agency User view
                                                if (in_array($seatInactive[$value]['status'], [2, 6, 7])) {
                                                    $seatColor = 'background: green;';
                                                } else if (in_array($seatInactive[$value]['status'], [1, 3])) {
                                                    $seatColor = 'background: #f1f5f9; color: black;'; // Busy
                                                } else if ($seatInactive[$value]['status'] == 4) {
                                                    $checked = '1';
                                                    $seatColor = 'background: yellow; color: black;';
                                                }
                                            }

                                            if (!empty($mouseOver)) {
                                                $hover = ' onmouseover="Tip(\'' . addslashes($mouseOver) . '\')"';
                                            }

                                            if ($checked) {
                                                // Seat selected by another user
                                                $levelTableLayout .= '<div' . $hover . ' style="width: ' . $tableWidth . 'px; height: ' . $tableHeight . 'px; cursor: not-allowed; display: flex; align-items: center; justify-content: center; text-align: center; border: 1px solid #ef4444; border-radius: 4px; box-sizing: border-box; background-color: #ef4444; color: #fff;">';
                                                $levelTableLayout .= $label;
                                                $levelTableLayout .= '</div>';
                                            } else {
                                                // Seat taken (sold, busy, etc.)
                                                $levelTableLayout .= '<div' . $hover . ' lbl="' . $label . '" data="' . $value . '" wd="' . $tableWidth . '" ht="' . $tableHeight . '" style="width: ' . $tableWidth . 'px; height: ' . $tableHeight . 'px; cursor: not-allowed; border: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: center; text-align: center; border-radius: 4px; box-sizing: border-box; ' . $seatColor . '"'.$seatDelete.'>';
                                                $levelTableLayout .= $label;
                                                $levelTableLayout .= '</div>';
                                            }
                                        } else {
                                            // Available seat
                                            $levelTableLayout .= '<div class="ticketBookingSeatSelect" seatChecked="0" lbl="' . $label . '" data="' . $value . '" style="width: ' . $tableWidth . 'px; height: ' . $tableHeight . 'px; cursor: pointer; border: 1px solid #ef4444; display: flex; align-items: center; justify-content: center; text-align: center; border-radius: 4px;box-sizing: border-box;">';
                                            $levelTableLayout .= $label;
                                            $levelTableLayout .= '</div>';
                                            $totalSAvbl++;
                                        }
                                    } else { // Special Seats (Non-bookable)
                                        $levelTableLayout .= '<td ' . $attrCol . ' style="height: ' . $tableHeight . 'px; width: ' . $tableWidth . 'px; text-align: center; vertical-align: middle;">';
                                        switch ($label) {
                                            case 'Capitain':
                                                $levelTableLayout .= '<img src="' . $this->webroot . 'img/button/captain.png" alt="" style="width: 24px;" />';
                                                break;
                                            case 'Hostess':
                                                $levelTableLayout .= '<img src="' . $this->webroot . 'img/button/hostess.png" alt="" style="width: 32px;" />';
                                                break;
                                            case 'Toilet':
                                                $levelTableLayout .= '<span style="font-size: 10px;">WC</span>'; // Updated from icon to text
                                                break;
                                            default:
                                                $levelTableLayout .= '<span style="font-size: 11px;">' . $label . '</span>';
                                        }
                                    }
                                    $levelTableLayout .= '</td>';
                                } else { // Empty Cell
                                    $levelTableLayout .= '<td style="height: ' . $tableHeight . 'px; width: ' . $tableWidth . 'px;"></td>';
                                }
                            }
                            $levelTableLayout .= '</tr>';
                        }
                        $totalTableWeight = isset($columns) ? $tableWidth * $columns + ($columns * 4) : 0; // Account for padding
                        $tableLayout .= '<div id="booking-layout-level-' . $levelNum . '" class="layout-booking-container" style="' . $displayStyle . '">';
                        $tableLayout .= '<table cellpadding="0" cellspacing="0" style="width: ' . $totalTableWeight . 'px; border-collapse: separate; border-spacing: 2px;">' . $levelTableLayout . '</table>';
                        $tableLayout .= '</div>';
                    endfor; // End level loop
                } // End if layouts exist
                ?>
                <div style="clear: both;"></div>
                <div style="min-width: 64%; margin-top: 10px; float: left; overflow-y: scroll;">
                    <?php echo $tableLayout; ?>
                </div>
                <!-- Seat Status Color -->
                <div style="min-width: 25%; margin-top: 10px; float: right;">
                    <div style="display: flex; align-items: center; margin-bottom: 5px;">
                        <div style="width: 20px; height: 20px; border: 1px solid #ef4444; border-radius: 4px; margin-right: 5px;"></div>
                        <span><?php echo $totalSAvbl; ?> <?php echo TABLE_AVAILABLE; ?></span>
                    </div>
                     <div style="display: flex; align-items: center; margin-bottom: 5px;">
                        <div style="width: 20px; height: 20px; background-color: blue; border-radius: 4px; margin-right: 5px;"></div>
                        <span><span id="ticketBookingSeatSpan">0</span> <?php echo TABLE_SELECTED; ?></span>
                    </div>
                     <div style="display: flex; align-items: center; margin-bottom: 5px;">
                        <div style="width: 20px; height: 20px; background-color: green; border-radius: 4px; margin-right: 5px;"></div>
                        <span><?php echo $totalSSold; ?> <?php echo TABLE_SOLD; ?></span>
                    </div>
                    <?php
                    if($user['User']['type'] == 2){
                    ?>
                    <div style="display: flex; align-items: center; margin-bottom: 5px;">
                        <div style="width: 20px; height: 20px; background-color: greenyellow; border-radius: 4px; margin-right: 5px;"></div>
                        <span><?php echo $totalSAgOff; ?> <?php echo TABLE_AGENCY_OFFLINE; ?></span>
                    </div>
                    <div style="display: flex; align-items: center; margin-bottom: 5px;">
                        <div style="width: 20px; height: 20px; background-color: #F6921E; border-radius: 4px; margin-right: 5px;"></div>
                        <span><?php echo $totalSAgOnl; ?> <?php echo TABLE_AGENCY_ONLINE; ?></span>
                    </div>
                     <div style="display: flex; align-items: center; margin-bottom: 5px;">
                        <div style="width: 20px; height: 20px; background-color: yellow; border-radius: 4px; margin-right: 5px;"></div>
                        <span><?php echo $totalSBooked; ?> <?php echo TABLE_PHONE_CALL; ?></span>
                    </div>
                    <?php
                    }
                    ?>
                    <div style="display: flex; align-items: center; margin-bottom: 5px;">
                        <div style="width: 20px; height: 20px; background-color: red; border: 1px solid #ccc; border-radius: 4px; margin-right: 5px;"></div>
                        <span><?php echo $totalSBusy; ?> <?php echo TABLE_BUSY; ?></span>
                    </div>
                    <?php
                    if($user['User']['type'] == 2){
                        $sqlCT = mysql_query("SELECT main_branches.name, COUNT(t_seat_controls.id) AS total FROM
                                              (
                                                SELECT t_tickets.main_branch_id, t_seat_controls.id 
                                                FROM t_seat_controls 
                                                INNER JOIN t_tickets ON t_tickets.id = t_seat_controls.t_ticket_id 
                                                WHERE t_seat_controls.t_transportation_type_id = ".$trasportationId." AND t_seat_controls.t_route_id = ".$journey['TJourney']['t_route_id']." AND t_seat_controls.journey_date = '".$date."' AND t_seat_controls.status IN (1,2,3) 
                                                UNION ALL
                                                SELECT t_tickets.main_branch_id, t_seat_controls.id
                                                FROM t_seat_controls 
                                                INNER JOIN t_ticket_3months AS t_tickets ON t_tickets.id = t_seat_controls.t_ticket_id 
                                                WHERE t_seat_controls.t_transportation_type_id = ".$trasportationId." AND t_seat_controls.t_route_id = ".$journey['TJourney']['t_route_id']." AND t_seat_controls.journey_date = '".$date."' AND t_seat_controls.status IN (1,2,3) 
                                              ) AS t_seat_controls
                                              INNER JOIN main_branches ON main_branches.id = t_seat_controls.main_branch_id 
                                              GROUP BY t_seat_controls.main_branch_id");
                        if(mysql_num_rows($sqlCT)){
                    ?>
                    <hr style="width: 100%; border-top: 1px solid #A5A8B8;" />
                    <?php
                            while($rowCT = mysql_fetch_array($sqlCT)){
                    ?>
                    <div style="font-size: 12px; width: 100%;">
                        <span style="font-weight: bold;"><?php echo $rowCT['name']; ?></span><br /><?php echo $rowCT['total']; ?> <?php echo TABLE_TICKET; ?>
                    </div>
                    <?php
                            }
                        }
                        if(!empty($destBooked)){
                    ?>
                    <hr style="width: 95%; border-top: 1px solid #A5A8B8;" /> <span style="font-size: 14px; text-decoration: underline;">To :</span>
                    <?php
                            foreach($destBooked AS $dest){
                    ?>
                    <div style="font-size: 12px; width: 100%;">
                        <span style="font-weight: bold;"><?php echo $dest['name']; ?></span><br /><?php echo $dest['total']; ?> <?php echo TABLE_TICKET; ?>
                    </div>
                    <?php
                            }
                        }
                    }
                    ?>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
        <div style="width: 49%; float: right;">
            <div style="height: 400px;">
                <div style="font-size: 14px; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #000;"><?php __("Passenger Information"); ?></div>
                <table cellpadding='5' cellspacing='0' style="width: 100%;">
                    <tr>
                        <td style="width: 33%;"><input type="text" name="data[TTicket][customer_name]" value="<?php echo $customerName; ?>" style="width: 90%; height: 20px;" placeholder="<?php echo TABLE_NAME; ?>" /></td>
                        <td style="width: 33%;"><input type="text" name="data[TTicket][telephone]" value="<?php echo $customerTel; ?>" class="<?php if($phoneCallChecked == ""){ ?>validate[custom[phone]]<?php } else { ?>validate[required, custom[phone]]<?php } ?>" style="width: 90%; height: 20px;" id="ticketBookingTelephone" placeholder="<?php echo TABLE_TELEPHONE; ?>" /></td>
                        <td><input type="text" name="data[TTicket][email]" value="<?php echo $customerMail; ?>" class="validate[custom[email]]" style="width: 90%; height: 20px;" placeholder="<?php echo TABLE_EMAIL; ?>" /></td>
                    </tr>
                </table>
                <?php
                $tblSeatSelectedHeight = "240px";
                if($journey['TJourney']['company_id'] == 17){ 
                    $tblSeatSelectedHeight = "200px";
                }
                $tblSeatSelectedHeight = $journey['TJourney']['company_id'] == 17 ? "200px" : "240px"; // (17) Buva Sea Kampot Phú Quốc
                ?>
                <div id="divTblTicketBookingSeat" style="padding: 0px; width: 470px; height: 320px; overflow: auto;">
                    <table cellpadding='2' cellspacing='0' style="width: 470px; padding: 0px;" class="table">
                        <thead>
                            <tr>
                                <th class="first" style="width: 12%;"><?php echo "#"; ?></th>
                                <th style="width: 30%;"><?php echo TABLE_GENDER; ?></th>
                                <th style="width: 18%;"><?php echo TABLE_PRICE; ?></th>
                                <th style="width: 18%; <?php if($user['User']['type'] != 2){ ?>display: none;<?php } ?>"><?php echo TABLE_DIS; ?></th>
                                <th style="width: 22%;"><?php echo TABLE_TOTAL; ?></th>
                            </tr>
                        </thead>
                    </table>
                    <table id="tblTicketBookingSeat" class="table" cellspacing="0" style="padding: 0px; width: 468px;">
                        <tr id="tblTicketBookingSeatList" class="tblTicketBookingSeatList" style="visibility: hidden;">
                            <td class="first" style="width: 12%;">
                                <input type="hidden" name="data[sys_code][]" class="seatSysCode" />
                                <input type="hidden" name="data[seat_number][]" class="seatNumber" />
                                <input type="hidden" name="data[label_number][]" class="seatLabel" />
                                <input type="hidden" name="data[is_special][]" class="seatSpecial" value="0" />
                                <input type="hidden" name="data[gender][]" class="seatGender" value="0" />
                                <input type="hidden" name="data[is_sync][]" class="seatIsSync" value="0" />
                                <input type="hidden" name="data[amt_change][]" class="seatAmtChange" value="0" />
                                <input type="hidden" name="data[change_ticket_id][]" class="seatChangeTicketId" value="" />
                                <input type="hidden" name="data[change_detail_id][]" class="seatChangeTicketDetailId" value="" />
                                <span class="seatSelectedLabel"></span>
                                <span class="seatShiftAmtLbl" style="color: red;"></span>
                            </td>
                            <td style="width: 30%;">
                                <div class="inputContainer" style="width: 100%;">
                                    <input type="radio" value="1" class="seatMale chkSeatGender<?php if($journey['TJourney']['gender_require'] == 1){ ?> validate[required]<?php } ?>" /> <?php echo "M"; ?>
                                    <input type="radio" value="2" class="seatFemale chkSeatGender<?php if($journey['TJourney']['gender_require'] == 1){ ?> validate[required]<?php } ?>" /> <?php echo "F"; ?>
                                </div>
                                <div class="inputContainer" style="width: 100%; margin-top: 5px; <?php echo $journey['TJourney']['company_id'] != 17 ? 'display: none;' : ''; ?>">
                                    <input type="text" name="data[passprot][]" value="" class="seatPassport" style="width: 98%; height: 25px; font-size: 14px;" placeholder="<?php echo "Passport"; ?>" />
                                </div>
                            </td>
                            <td style="width: 18%;"> 
                                <div class="inputContainer" style="width: 100%;">
                                    <input type="text" name="data[price][]" value="0" class="seatPrice float" style="width: 90%; height: 20px;" readonly="" />
                                </div>
                                <div class="inputContainer" style="width: 100%; margin-top: 5px; <?php echo $journey['TJourney']['company_id'] != 17 ? 'display: none;' : ''; ?>">
                                    <input type="text" name="data[dob][]" value="" class="seatDob" style="width: 98%; height: 25px; font-size: 14px;" placeholder="<?php echo "DOB"; ?>" readonly="" />
                                </div>
                            </td>
                            <td style="width: 18%; <?php if($user['User']['type'] != 2){ ?>display: none;<?php } ?>">
                                <div class="inputContainer" style="width: 100%;">
                                    <input type="text" name="data[discount][]" value="0" class="seatDiscount float" style="width: 90%; height: 20px;" <?php if(!$allowDiscount){ ?>readonly=""<?php } ?> />
                                </div>
                            </td>
                            <td style="width: 22%;">
                                <div class="inputContainer" style="width: 100%;">
                                    <input type="text" name="data[total][]" value="0" class="seatTotal float" style="width: 90%; height: 20px;" readonly="" />
                                </div>
                            </td>
                        </tr>
                        <tr id="tblTicketBookingSeatPassportList" class="tblTicketBookingSeatList" style="visibility: hidden;">
                            <td class="first" colspan="2">
                                <div class="inputContainer" style="width: 100%;">
                                    <input type="text" name="data[name][]" value="" class="seatName" style="width: 98%; height: 20px; font-size: 14px;" placeholder="<?php echo "Name"; ?>" />
                                </div>
                            </td>
                            <td colspan="2"> 
                                <div class="inputContainer" style="width: 100%;">
                                    <input type="text" name="data[passprot][]" value="" class="seatPassport" style="width: 98%; height: 20px; font-size: 14px;" placeholder="<?php echo "Passport"; ?>" />
                                </div>
                            </td>
                            <td style="width: 18%; <?php if($user['User']['type'] != 2){ ?>display: none;<?php } ?>">
                                <div class="inputContainer" style="width: 100%;">
                                    <input type="text" name="data[dob][]" value="" class="seatDob" style="width: 98%; height: 20px; font-size: 14px;" placeholder="<?php echo "DOB"; ?>" readonly="" />
                                </div>
                            </td>
                        </tr>
                        <?php
                        if(!empty($tTicket)){
                            if($tTicket['TTicket']['status'] == 1 && $tTicket['TTicket']['type'] == 2){
                                if($journey['TJourney']['type'] == 3){
                                    $sqlSeat = mysql_query("SELECT t_ticket_details.*, t_seat_controls.sys_code AS seat_sys_code FROM t_ticket_details INNER JOIN t_seat_controls ON t_seat_controls.t_ticket_detail_id = t_ticket_details.id WHERE t_ticket_details.t_ticket_id = ".$tTicket['TTicket']['id']." GROUP BY t_seat_controls.sys_code");
                                } else {
                                    $sqlSeat = mysql_query("SELECT t_ticket_details.*, t_seat_controls.sys_code AS seat_sys_code FROM t_ticket_details INNER JOIN t_seat_controls ON t_seat_controls.t_ticket_detail_id = t_ticket_details.id WHERE t_ticket_details.t_ticket_id = ".$tTicket['TTicket']['id']);
                                }
                                while($rowSeat = mysql_fetch_array($sqlSeat)){
                                    $rnd = rand();
                        ?>
                        <tr class="tblTicketBookingSeatList">
                            <td class="first" style="width: 19%;">
                                <input type="hidden" name="data[sys_code][]" class="seatSysCode" value="<?php echo $rowSeat['seat_sys_code']; ?>" />
                                <input type="hidden" name="data[seat_number][]" class="seatNumber" value="<?php echo $rowSeat['seat_number']; ?>" />
                                <input type="hidden" name="data[label_number][]" class="seatLabel" value="<?php echo $rowSeat['label_number']; ?>" />
                                <input type="hidden" name="data[is_special][]" class="seatSpecial" value="<?php echo $rowSeat['is_special']; ?>" />
                                <input type="hidden" name="data[gender][]" class="seatGender" value="<?php echo $rowSeat['gender']; ?>" />
                                <input type="hidden" name="data[is_sync][]" class="seatIsSync" value="1" />
                                <input type="hidden" name="data[amt_change][]" class="seatAmtChange" value="0" />
                                <input type="hidden" name="data[change_ticket_id][]" class="seatChangeTicketId" value="" />
                                <input type="hidden" name="data[change_detail_id][]" class="seatChangeTicketDetailId" value="" />
                                <span class="seatSelectedLabel"><?php echo $rowSeat['label_number']; ?></span>
                                <span class="seatShiftAmtLbl" style="color: red;"></span>
                            </td>
                            <td style="width: 26%;">
                                <div class="inputContainer" style="width: 100%;">
                                    <input type="radio" value="1" <?php if($rowSeat['gender'] == 1){ ?>checked=""<?php } ?> name="chkGender<?php echo $rnd; ?>" id="seatMale<?php echo $rnd; ?>" class="seatMale chkSeatGender<?php if($journey['TJourney']['gender_require'] == 1){ ?> validate[required]<?php } ?>" /> <?php echo "M"; ?>
                                    <input type="radio" value="2" <?php if($rowSeat['gender'] == 2){ ?>checked=""<?php } ?> name="chkGender<?php echo $rnd; ?>" id="seatFemale<?php echo $rnd; ?>" class="seatFemale chkSeatGender<?php if($journey['TJourney']['gender_require'] == 1){ ?> validate[required]<?php } ?>" /> <?php echo "F"; ?>
                                </div>
                            </td>
                            <td style="width: 17%;"> 
                                <div class="inputContainer" style="width: 100%;">
                                    <input type="text" name="data[price][]" value="<?php echo number_format($rowSeat['unit_price'], 2); ?>" class="seatPrice float" style="width: 90%; height: 20px;" readonly="" />
                                </div>
                            </td>
                            <td style="width: 18%;">
                                <div class="inputContainer" style="width: 100%;">
                                    <input type="text" name="data[discount][]" value="<?php echo number_format($rowSeat['discount'], 2); ?>" class="seatDiscount float" style="width: 90%; height: 20px;" <?php if(!$allowDiscount){ ?>readonly=""<?php } ?> />
                                </div>
                            </td>
                            <td style="width: 20%;">
                                <div class="inputContainer" style="width: 100%;">
                                    <input type="text" name="data[total][]" value="<?php echo number_format($rowSeat['unit_price'] - $rowSeat['discount'], 2); ?>" class="seatTotal float" style="width: 90%; height: 20px;" readonly="" />
                                </div>
                            </td>
                        </tr>
                        <?php
                                }
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
            <table cellpadding='2' cellspacing='0' style="width: 100%;">
                <tr>
                    <td rowspan="5" style="vertical-align: top;">
                        <table cellpadding="1" cellspacing="0" style="width: 100%; display: none;">
                            <tr>
                                <td colspan="2">
                                    <input type="radio" name="data[TTicket][discount_type]" value="1" id="discountTypeNone" checked="" /> <label for="discountTypeNone"><?php echo INPUT_NONE; ?></label>
                                    <input type="radio" name="data[TTicket][discount_type]" value="3" id="discountTypeCard" /> <label for="discountTypeCard"><?php echo TABLE_DISCOUNT_OFFER; ?></label>
                                </td>
                            </tr>
                            <tr id="ticketBookingDisOfferDiv" style="display: none;">
                                <td><?php echo TABLE_DISCOUNT_BY; ?></td>
                                <td>
                                    <select style="height: 25px; width: 145px;" id="ticketBookingDiscountBy" name="data[TTicket][discount_offer_id]">
                                        <option value="" percent=""><?php echo INPUT_SELECT; ?></option>
                                        <?php
                                        $sqlDis = mysql_query("SELECT id, name, percent FROM discount_offers WHERE is_active = 1");
                                        while($rowDis = mysql_fetch_array($sqlDis)){
                                        ?>
                                        <option value="<?php echo $rowDis['id']; ?>" percent="<?php echo $rowDis['percent']; ?>"><?php echo $rowDis['name']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr id="ticketBookingDisOfferReferenceDiv" style="display: none;">
                                <td colspan="2">
                                    <input type="text" name="data[TTicket][discount_offer_refer]" style="width: 220px; height: 20px;" id="ticketBookingDisOfferReference" placeholder="<?php echo TABLE_DISCOUNT_REFERENCE; ?>" />
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 30%; font-size: 14px; font-weight: bold;"><?php echo TABLE_TOTAL_AMOUNT; ?> :</td>
                    <td style="width: 27%; font-size: 14px; font-weight: bold;">
                        <input type="text" name="data[TTicket][total_amount]" style="width: 80%; height: 20px; border: none; font-size: 14px; font-weight: bold;" value='<?php echo $totalAmount; ?>' id="ticketBookingTotalAmount" class="float" readonly="" /> <?php echo $journey['CurrencyCenter']['symbol']; ?>
                    </td>
                </tr>
                <tr <?php if($allowVat == 0){ echo 'style="display: none;"'; } ?>>
                    <td style="font-size: 14px; font-weight: bold;"><?php echo "Total VAT"; ?> :</td>
                    <td style="font-size: 14px; font-weight: bold;">
                        <input type="text" name="data[TTicket][total_vat]" style="width: 80%; height: 20px; border: none; font-size: 14px; font-weight: bold;" value='<?php echo $totalVat; ?>' id="ticketBookingTotalVat" class="float" readonly="" /> <?php echo $journey['CurrencyCenter']['symbol']; ?>
                    </td>
                </tr>
                <tr <?php if($user['User']['type'] == 2 || $allowVat == 0){ ?>style="display: none;" <?php } ?>>
                    <td style="font-size: 14px; font-weight: bold;"><?php echo GENERAL_DISCOUNT; ?> <span id="ticketBookingDisPercentLbl"></span> :</td>
                    <td style="font-size: 14px; font-weight: bold;">
                        <!-- <input type="hidden" name="data[TTicket][discount_percent]" id="ticketBookingDisPercent" class="float" value="<?php //echo $totalDiscountP; ?>" /> -->
                        <?php
                        if($user['User']['type'] == 2 || $allowVat == 0){
                        ?>
                        <input type="hidden" name="data[TTicket][discount_amount]" style="width: 80%; height: 20px; border: none; font-size: 14px; font-weight: bold;" value='0' id="ticketBookingTotalDiscount" class="float" readonly="" />
                        <?php
                        } else {
                        ?>
                        <input type="text" name="data[TTicket][discount_amount]" style="width: 80%; height: 20px; border: none; font-size: 14px; font-weight: bold;" value='0' id="ticketBookingTotalDiscount" class="float" readonly="" />
                        <?php
                        }
                        ?>
                        <?php echo $journey['CurrencyCenter']['symbol']; ?>
                    </td>
                </tr>
                <!-- <tr <?php if($agencyType == 3){ ?>style="display: none;" <?php } ?>>
                    <td style="font-size: 14px; font-weight: bold;"><div style="font-size: 15px; width: 16px; height: 16px; border: 1px solid #000; padding: 2px; cursor: pointer; color: red; float: left; margin-right: 5px;" id="chkTicketLuckyDraw"><?php if($totalLuckyDraw > 0){ ?>&#10004;<?php } ?></div><?php echo "Lucky Ticket"; ?> :</td>
                    <td style="font-size: 14px; font-weight: bold;">
                        <input type="text" style="width: 80%; height: 20px; border: none; font-size: 14px; font-weight: bold;" name="data[TTicket][lucky_draw_fee]" value="0" id="ticketBookingLuckyDrawFee" class="float" readonly="" /> <?php echo $journey['CurrencyCenter']['symbol']; ?>
                    </td>
                </tr> -->
                <tr <?php if($chkChangeSeat == false){ ?>style="display: none;"<?php } ?>>
                    <td style="font-size: 14px; font-weight: bold;"><?php echo "Amount Change"; ?> :</td>
                    <td style="font-size: 14px; font-weight: bold;">
                        <input type="text" style="width: 80%; height: 20px; border: none; font-size: 14px; font-weight: bold;" name="data[TTicket][total_change]" value="0" id="ticketBookingTotalChange" class="float" readonly="" /> <?php echo $journey['CurrencyCenter']['symbol']; ?>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 14px; font-weight: bold;"><?php echo TABLE_NET_AMOUNT; ?> :</td>
                    <td style="font-size: 14px; font-weight: bold;">
                        <input type="hidden" name="data[TTicket][lucky_draw_fee]" value="0" />
                        <input type="text" style="width: 80%; height: 20px; border: none; font-size: 14px; font-weight: bold;" value='<?php echo $total; ?>' id="ticketBookingNetAmount" class="float" readonly="" /> <?php echo $journey['CurrencyCenter']['symbol']; ?>
                    </td>
                </tr>
            </table>
        </div>
        <div style="clear: both;"></div>
    </form>
</div>
<div style="clear: both;"></div>