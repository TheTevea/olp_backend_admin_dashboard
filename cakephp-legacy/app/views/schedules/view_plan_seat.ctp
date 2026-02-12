<?php
include("includes/function.php");
$rnd = rand();
$printArea = "printArea" . $rnd;
$btnPrint = "btnPrint" . $rnd;
// Get Printer Name 
$printerName = '';
$printSilent = 0;
//$sqlPrinter = mysql_query("SELECT printer_name, silent FROM printers WHERE branch_id = ".$tJourney['TJourney']['branch_id']." AND type_id = 2 AND is_active = 1 ORDER BY id DESC LIMIT 1;");
//if(mysql_num_rows($sqlPrinter)){
//    $rowPrinter = mysql_fetch_array($sqlPrinter);
//    $printerName = $rowPrinter[0]; 
//    $printSilent = $rowPrinter[1];
//}
if($date == ''){
    $date = date("Y-m-d");
}
?>
<!-- Print -->
<script type="text/javascript" src="<?php echo $this->webroot; ?>js/print_setup.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $(".btnBackTTicket").click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableSchedule.fnDraw(false);
            var rightPanel=$(this).parent().parent().parent();
            var leftPanel=rightPanel.parent().find(".leftPanel");
            rightPanel.hide();rightPanel.html("");
            leftPanel.show("slide", { direction: "left" }, 500);
        });
        $("#<?php echo $btnPrint; ?>").click(function(){
            $(".dataTables_length").hide();
            $(".dataTables_filter").hide();
            $(".dataTables_paginate").hide();
            w=window.open();
            w.document.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">');
            w.document.write('<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>css/style.css" /><link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>css/table.css" /><link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>css/button.css" /><link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>css/print.css" media="print" />');
            w.document.write($("#<?php echo $printArea; ?>").html());
            w.document.close();
            try {
                jsPrintSetup.refreshOptions();
                var printer = '';
                var silent  = <?php echo $printSilent; ?>;
                <?php
                if($printerName != ''){
                ?>
                printer = getPrinterName('<?php echo $printerName; ?>');
                if(printer != ''){
                    jsPrintSetup.setPrinter(printer);
                }
                <?php
                }
                ?>
                jsPrintSetup.setOption('marginTop', 0);
                jsPrintSetup.setOption('marginBottom', 0);
                jsPrintSetup.setOption('marginLeft', 0);
                jsPrintSetup.setOption('marginRight', 0);
                jsPrintSetup.setSilentPrint(silent);
                jsPrintSetup.printWindow(w);
                ws.close();
            } catch (e) {
               w.print();
               w.close();
            }
            $(".dataTables_length").show();
            $(".dataTables_filter").show();
            $(".dataTables_paginate").show();
        });

        $(".updateTicketNote").unbind("click").click(function(event){
            event.preventDefault();
            $.ajax({
                type: "GET",
                url: "<?php echo $this->base.'/'.$this->params['controller']; ?>/updateNote/",
                data: "",
                beforeSend: function(){
                    $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
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
                },
                success: function(result){
                    $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                    $("#dialogModal").dialog("close");
                    $("#dialog").html(result);
                    $("#dialog").dialog({
                        title: 'Update Note',
                        resizable: false,
                        modal: true,
                        width: 1024,
                        height: 600,
                        buttons: {
                            '<?php echo ACTION_CLOSE; ?>': function() {
                                $(this).dialog("close");
                            },
                            '<?php echo ACTION_SAVE; ?>': function() {
                                
                                $(this).dialog("close");
                            }
                        }
                    });
                }
            });
        });

        $(".chkScheduleTravelPackage").unbind("click").click(function(event){
            event.preventDefault();
            var id = $(this).attr("data");
            $.ajax({
                type: "GET",
                url: "<?php echo $this->base.'/'.$this->params['controller']; ?>/viewTravelPackage/"+id,
                data: "",
                beforeSend: function(){
                    $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
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
                },
                success: function(result){
                    $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                    $("#dialogModal").dialog("close");
                    $("#dialog").html(result);
                    $("#dialog").dialog({
                        title: 'View Travel Package',
                        resizable: false,
                        modal: true,
                        width: 500,
                        height: 600,
                        buttons: {
                            '<?php echo ACTION_CLOSE; ?>': function() {
                                $(this).dialog("close");
                            }
                        }
                    });
                }
            });
        });
    });
</script>
<div style="padding: 5px;border: 1px dashed #bbbbbb;">
    <div class="buttons">
        <a href="" class="positive btnBackTTicket">
            <img src="<?php echo $this->webroot; ?>img/button/left.png" alt=""/>
            <?php echo ACTION_BACK; ?>
        </a>
    </div>
    <div class="buttons" style="float: right;">
        <button type="button" id="<?php echo $btnPrint; ?>" class="positive">
            <img src="<?php echo $this->webroot; ?>img/button/printer.png" alt=""/>
            <?php echo ACTION_PRINT; ?>
        </button>
    </div>
    <div style="clear: both;"></div>
</div>
<br />
<div id="<?php echo $printArea; ?>">
    <fieldset style="width: 32%; float: left;">
        <legend><?php __(MENU_JOURNEY_INFO); ?></legend>
        <?php
        $totalSAvbl    = 0;
        $totalSSold    = 0;
        $totalSAgOff   = 0;
        $totalSAgOnl   = 0;
        $totalSBusy    = 0;
        $totalPhone    = 0;
        $totalApp      = 0;
        $totalWeb      = 0;
        $totalMiniApp  = 0;
        $totalTerminal = 0;
        $totalPhoneCancel = 0;
        $layouts       = json_decode($tBoat['TTransportationType']['layout'], true);
        $tableLayout   = '';
        $totalCol      = 0;
        $seatInactive  = array();
        $destBooked    = array();
        $seatImg       = 'seat-sitting-32.png';
        $tableWidth    = 32;
        $tableHeight   = 24;
        $seatChkMargin = 10;
        $totalCheckedIn = 0; 
        $totalAmt       = 0;
        if($tBoat['TTransportationType']['seat_type'] == 2){
            $seatImg = 'seat-sleeper-32.png';
            $tableHeight = 60;
            $seatChkMargin = 25;
        }
        // Get Seat In Active
        if($tJourney['TJourney']['type'] == 3){
            $seatBooked = array();
            $sqlTransit = mysql_query("SELECT t_transportation_type_id, t_route_id, t_journeys.id AS journey_id, t_journey_transits.is_next_day 
                                       FROM t_journeys 
                                       INNER JOIN t_journey_transits ON t_journey_transits.t_journey_departure_id = t_journeys.id
                                       WHERE t_journey_transits.t_journey_id = ".$tJourney['TJourney']['id']." 
                                       GROUP BY t_journey_departure_id");
            while($rowTransit = mysql_fetch_array($sqlTransit)){
                $travelDate   = $date;
                if($rowTransit['is_next_day'] == 1){
                    $travelDate = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                }
                // Get Seat Booked
                $sqlSeat = mysql_query("SELECT seat_number, t_ticket_id, t_ticket_api_tmp_id, t_ticket_detail_id, t_ticket_detail_api_tmp_id, status, gender, is_pickup FROM t_seat_controls WHERE t_transportation_type_id = ".$rowTransit['t_transportation_type_id']." AND t_route_id = ".$rowTransit['t_route_id']." AND journey_date = '".$travelDate."' AND status IN (0,1,2,3)");
                while($rowSeat = mysql_fetch_array($sqlSeat)){
                    if (!array_key_exists($rowSeat['seat_number'], $seatBooked)) {
                        if($rowSeat['status'] == 0){
                            if(!empty($rowSeat['t_ticket_id'])){
                                $sqlChTicket = mysql_query("SELECT * FROM t_tickets WHERE id = ".$rowSeat['t_ticket_id']."
                                                            UNION ALL
                                                            SELECT * FROM t_ticket_3months WHERE id = ".$rowSeat['t_ticket_id']);
                                if(mysql_num_rows($sqlChTicket)){
                                    $rowChTicket  = mysql_fetch_array($sqlChTicket);
                                    if($rowChTicket['status'] == -1){
                                        $totalPhoneCancel++;
                                    }
                                }
                            }
                        } else {
                            $seatBooked[$rowSeat['seat_number']]['ticket_id'] = $rowSeat['t_ticket_id'];
                            $seatBooked[$rowSeat['seat_number']]['t_ticket_api_tmp_id'] = $rowSeat['t_ticket_api_tmp_id'];
                            $seatBooked[$rowSeat['seat_number']]['t_ticket_detail_id']  = $rowSeat['t_ticket_detail_id'];
                            $seatBooked[$rowSeat['seat_number']]['t_ticket_detail_api_tmp_id'] = $rowSeat['t_ticket_detail_api_tmp_id'];
                            $seatBooked[$rowSeat['seat_number']]['status'] = $rowSeat['status'];
                            $seatBooked[$rowSeat['seat_number']]['gender'] = $rowSeat['gender'];
                            $seatBooked[$rowSeat['seat_number']]['is_pickup'] = $rowSeat['is_pickup'];
                        }
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
                $note       = '';
                $boarding   = '';
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
                    $totalAmt  += $rowTicket['total_amount'] + $rowTicket['lucky_draw_fee'] - $rowTicket['discount_amount'];
                    $travelPackageId = "";
                    if(!empty($rowTicket['travel_package_order_id'])){
                        $travelPackageId = $rowTicket['travel_package_order_id'];
                    }
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
                    // Boarding Point
                    if(!empty($rowTicket['t_boarding_point_id'])){
                        $sqlBoarding = mysql_query("SELECT id, name FROM t_boarding_points WHERE id = ".$rowTicket['t_boarding_point_id']);
                        $rowBoarding = mysql_fetch_array($sqlBoarding);
                        $boarding  = $rowBoarding['name'];
                    }
                    // Main Branch
                    if(!empty($rowTicket['main_branch_id'])){
                        $sqlMain = mysql_query("SELECT name FROM main_branches WHERE id = ".$rowTicket['main_branch_id']);
                        $rowMain = mysql_fetch_array($sqlMain);
                        $mainBranch  = $rowMain[0];
                    }
                    if($bookingStatus == 1){
                        $sqlTckDetail = mysql_query("SELECT *
                                                     FROM t_ticket_details WHERE id = ".$tSeatControll['t_ticket_detail_id']."
                                                     UNION ALL
                                                     SELECT * FROM t_ticket_detail_3months WHERE id = ".$tSeatControll['t_ticket_detail_id']);
                    } else {
                        $sqlTckDetail = mysql_query("SELECT * FROM t_ticket_api_tmp_details WHERE id = ".$tSeatControll['t_ticket_api_tmp_detail_id']);
                    }
                    $passport = "";
                    $dob      = "";
                    if(mysql_num_rows($sqlTckDetail)){
                        $rowTckDetail = mysql_fetch_array($sqlTckDetail);
                        if(!empty($rowTckDetail['passport'])){
                            $passport = $rowTckDetail['passport'];
                        }
                        if(!empty($rowTckDetail['dob'])){
                            $dob = $rowTckDetail['dob'];
                        }
                    }
                    if($bookingStatus == 1){ // Ticket Sold
                        // Agency
                        if(!empty($rowTicket['t_agent_id'])){
                            if($rowTicket['t_agent_id'] == 55){
                                $agencyName = "Website";
                                $seatInactive[$key]['status'] = 5;
                                $username = "Website";
                            } else if($rowTicket['t_agent_id'] == 106){
                                $agencyName = "MiniApp";
                                $seatInactive[$key]['status'] = 8;
                                $username = "MiniApp";
                            } else {
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
                            }   
                        } else {
                            if($rowTicket['terminal_id'] != ''){ // Terminal
                                $agencyName = "Terminal";
                                $seatInactive[$key]['status'] = 9;
                                $username = "Terminal";
                            } else {
                                if($rowTicket['type'] == 5){ // APP
                                    $agencyName = "APP";
                                    $seatInactive[$key]['status'] = 4;
                                    $username  = "App";
                                    $agencyRef = "";
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
                        }
                    } else { // Busy Online Booking
                        $seatInactive[$key]['status'] = 3; // Busy
                    }
                    if($tSeatControll['gender'] == 1){
                        $seatInactive[$key]['gender'] = 'Male';
                    } else if ($tSeatControll['gender'] == 2){
                        $seatInactive[$key]['gender'] = 'Female';
                    } else {
                        $seatInactive[$key]['gender'] = '';
                    }
                    $seatInactive[$key]['code']   = $ticketCode;
                    $seatInactive[$key]['tel']    = $ticketTel;
                    $seatInactive[$key]['user']   = $username;
                    $seatInactive[$key]['origin'] = $origin;
                    $seatInactive[$key]['dest']   = $destTo;
                    $seatInactive[$key]['boarding']  = $boarding;
                    $seatInactive[$key]['branch']    = $mainBranch;
                    $seatInactive[$key]['agencyId']  = $agencyId;
                    $seatInactive[$key]['agency']    = $agencyName;
                    $seatInactive[$key]['reference'] = $agencyRef;
                    $seatInactive[$key]['note']      = $note;
                    $seatInactive[$key]['isPicked']  = $tSeatControll['is_pickup'];
                    $seatInactive[$key]['travelPackage']  = $travelPackageId;
                    $seatInactive[$key]['passport']  = $tSeatControll['passport'];
                    $seatInactive[$key]['dob']       = $tSeatControll['dob'];
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
                    if($bookingStatus == 1){
                        $sqlTckDetail = mysql_query("SELECT *
                                                     FROM t_ticket_details WHERE id = ".$tSeatControll['TSeatControl']['t_ticket_detail_id']."
                                                     UNION ALL
                                                     SELECT * FROM t_ticket_detail_3months WHERE id = ".$tSeatControll['TSeatControl']['t_ticket_detail_id']);
                    } else {
                        $sqlTckDetail = mysql_query("SELECT * FROM t_ticket_api_tmp_details WHERE id = ".$tSeatControll['TSeatControl']['t_ticket_api_tmp_detail_id']);
                    }
                    $passport = "";
                    $dob      = "";
                    if(mysql_num_rows($sqlTckDetail)){
                        $rowTckDetail = mysql_fetch_array($sqlTckDetail);
                        if(!empty($rowTckDetail['passport'])){
                            $passport = $rowTckDetail['passport'];
                        }
                        if(!empty($rowTckDetail['dob'])){
                            $dob = $rowTckDetail['dob'];
                        }
                    }
                    $rowTck = mysql_fetch_array($sqlTck);
                    $tSeatControll['TTicket']['id'] = $rowTck['id'];
                    $tSeatControll['TTicket']['t_journey_id'] = $rowTck['t_journey_id'];
                    $tSeatControll['TTicket']['confirm_by']   = $rowTck['confirm_by'];
                    $tSeatControll['TTicket']['created_by']   = $rowTck['created_by'];
                    $tSeatControll['TTicket']['t_destination_to_id'] = $rowTck['t_destination_to_id'];
                    $tSeatControll['TTicket']['t_boarding_point_id'] = $rowTck['t_boarding_point_id'];
                    $tSeatControll['TTicket']['code']           = $rowTck['code'];
                    $tSeatControll['TTicket']['telephone']      = $rowTck['telephone'];
                    $tSeatControll['TTicket']['price_type']     = $rowTck['price_type'];
                    $tSeatControll['TTicket']['t_agent_id']     = $rowTck['t_agent_id'];
                    $tSeatControll['TTicket']['agt_refer_code'] = $rowTck['agt_refer_code'];
                    $tSeatControll['TTicket']['note']           = $rowTck['note'];
                    $tSeatControll['TTicket']['main_branch_id'] = $rowTck['main_branch_id'];
                    $tSeatControll['TTicket']['t_destination_from_id'] = $rowTck['t_destination_from_id'];
                    $tSeatControll['TTicket']['api_bank_ref']    = $rowTck['api_bank_ref'];
                    $tSeatControll['TTicket']['type']            = $rowTck['type'];
                    $tSeatControll['TTicket']['status']          = $rowTck['status'];
                    $tSeatControll['TTicket']['total_amount']    = $rowTck['total_amount'];
                    $tSeatControll['TTicket']['lucky_draw_fee']  = $rowTck['lucky_draw_fee'];
                    $tSeatControll['TTicket']['discount_amount'] = $rowTck['discount_amount'];
                    $tSeatControll['TTicket']['travel_package_order_id'] = $rowTck['travel_package_order_id'];
                    $tSeatControll['TTicket']['passport'] = $passport;
                    $tSeatControll['TTicket']['dob'] = $dob;
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
                $boarding   = '';
                $totalAmt  += $tSeatControll['TTicket']['total_amount'] + $tSeatControll['TTicket']['lucky_draw_fee'] - $tSeatControll['TTicket']['discount_amount'];
                $travelPackageId = "";
                if(!empty($tSeatControll['TTicket']['travel_package_order_id'])){
                    $travelPackageId = $tSeatControll['TTicket']['travel_package_order_id'];
                }
                if($tSeatControll['TSeatControl']['status'] == 0){
                    if(!empty($tSeatControll['TSeatControl']['t_ticket_id'])){
                        if($tSeatControll['TTicket']['status'] == -1){
                            $totalPhoneCancel++;
                        }
                    }
                } else {
                    // Check Journey Booked
                    if(!empty($tSeatControll['TTicket']['t_journey_id'])){
                        $ticketCode = $tSeatControll['TTicket']['code'];
                        $ticketTel  = $tSeatControll['TTicket']['telephone'];
                        $agencyRef  = $tSeatControll['TTicket']['agt_refer_code'];
                        $note       = $tSeatControll['TTicket']['note'];
                        $sqlJour = mysql_query("SELECT * FROM t_journeys WHERE id = ".$tSeatControll['TTicket']['t_journey_id']);
                        $rowJour = mysql_fetch_array($sqlJour);
                        if($rowJour['type'] == 3){
                            if(!empty($tJourney['TJourney']['t_destination_to_id'])){
                                $sqlDest = mysql_query("SELECT id, name FROM t_destinations WHERE id = ".$tJourney['TJourney']['t_destination_to_id']);
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
                    if(!empty($tSeatControll['TTicket']['t_boarding_point_id'])){
                        $sqlBoarding = mysql_query("SELECT id, name FROM t_boarding_points WHERE id = ".$tSeatControll['TTicket']['t_boarding_point_id']);
                        $rowBoarding = mysql_fetch_array($sqlBoarding);
                        $boarding    = $rowBoarding['name'];
                    }
                    if($bookingStatus == 1){ // Ticket Sold
                        if(!empty($tSeatControll['TTicket']['t_agent_id'])){
                            if($tSeatControll['TTicket']['t_agent_id'] == 55){
                                $agencyName = "Website";
                                $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['status'] = 5;
                                $username  = "Website";
                                $agencyRef = "";
                            } else if($tSeatControll['TTicket']['t_agent_id'] == 106){
                                $agencyName = "MiniApp";
                                $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['status'] = 8;
                                $username = "MiniApp";
                            } else {
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
                            }
                        } else {
                            if($tSeatControll['TTicket']['terminal_id'] != ''){ // Terminal
                                $agencyName = "Terminal";
                                $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['status'] = 9;
                                $username = "Terminal";
                            } else {
                                if($tSeatControll['TTicket']['type'] == 5){ // APP
                                    $agencyName = "APP";
                                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['status'] = 4;
                                    $username  = "App";
                                    $agencyRef = "";
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
                        }
                    } else { // Busy Online Booking
                        $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['status'] = 3;
                    }   
                    if($tSeatControll['TSeatControl']['gender'] == 1){
                        $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['gender'] = 'Male';
                    } else if ($tSeatControll['TSeatControl']['gender'] == 2){
                        $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['gender'] = 'Female';
                    } else {
                        $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['gender'] = '';
                    }
                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['code']   = $ticketCode;
                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['tel']    = $ticketTel;
                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['user']   = $username;
                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['origin'] = $origin;
                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['dest']   = $destTo;
                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['boarding']  = $boarding;
                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['branch']    = $mainBranch;
                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['agencyId']  = $agencyId;
                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['agency']    = $agencyName;
                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['reference'] = $agencyRef;
                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['note']      = $note;
                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['travelPackage']  = $travelPackageId;
                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['isPicked']  = $tSeatControll['TSeatControl']['is_pickup'];
                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['passport']  = $tSeatControll['TTicket']['passport'];
                    $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['dob']       = $tSeatControll['TTicket']['dob'];
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
        }
        // Check Seat Block
        $sqlBlock = mysql_query("SELECT t_journey_seat_blocks.id, t_journey_seat_blocks.type, t_journey_seat_blocks.start, t_journey_seat_blocks.end, t_journey_seat_block_details.seat_number 
                                 FROM t_journey_seat_blocks 
                                 INNER JOIN t_journey_seat_block_details ON t_journey_seat_block_details.t_journey_seat_block_id = t_journey_seat_blocks.id 
                                 WHERE t_journey_seat_blocks.start <= '".$date."' AND t_journey_seat_blocks.end >= '".$date."' AND t_journey_seat_blocks.t_journey_id = ".$tJourney['TJourney']['id']." AND t_journey_seat_blocks.t_departure_time_id = ".$tJourney['TJourney']['t_departure_time_id']." AND t_journey_seat_blocks.is_active = 1");
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
                    $seatInactive[$rowBlock['seat_number']]['boarding'] = "";
                    $seatInactive[$rowBlock['seat_number']]['branch']   = "";
                    $seatInactive[$rowBlock['seat_number']]['agencyId'] = "";
                    $seatInactive[$rowBlock['seat_number']]['agency']   = "";
                    $seatInactive[$rowBlock['seat_number']]['reference'] = "";
                    $seatInactive[$rowBlock['seat_number']]['note']      = "Seat Block By Branch";
                    $seatInactive[$rowBlock['seat_number']]['travelPackage']  = "";
                    $seatInactive[$rowBlock['seat_number']]['isPicked'] = 0;
                    $seatInactive[$rowBlock['seat_number']]['passport'] = "";
                    $seatInactive[$rowBlock['seat_number']]['dob'] = "";
                }
            } else if($rowBlock['type'] == 3){
                $seatInactive[$rowBlock['seat_number']]['status'] = 3; // Busy (Block)
                $seatInactive[$rowBlock['seat_number']]['gender'] = '';
                $seatInactive[$rowBlock['seat_number']]['code']   = "";
                $seatInactive[$rowBlock['seat_number']]['tel']    = "";
                $seatInactive[$rowBlock['seat_number']]['user']   = "";
                $seatInactive[$rowBlock['seat_number']]['origin'] = "";
                $seatInactive[$rowBlock['seat_number']]['dest']   = "";
                $seatInactive[$rowBlock['seat_number']]['boarding']  = "";
                $seatInactive[$rowBlock['seat_number']]['branch']    = "";
                $seatInactive[$rowBlock['seat_number']]['agencyId']  = "";
                $seatInactive[$rowBlock['seat_number']]['agency']    = "";
                $seatInactive[$rowBlock['seat_number']]['reference'] = "";
                $seatInactive[$rowBlock['seat_number']]['note']      = "";
                $seatInactive[$rowBlock['seat_number']]['travelPackage'] = "";
                $seatInactive[$rowBlock['seat_number']]['isPicked'] = 0;
                $seatInactive[$rowBlock['seat_number']]['passport'] = "";
                $seatInactive[$rowBlock['seat_number']]['dob'] = "";
            }
        }
        // List Seat
        for ($row = 1; $row <= $layouts['rows']; $row++) {
            $tableLayout .= '<tr>';
            
            // Get all seats for this row
            $rowSeats = array_filter($layouts['seats'], function($seat) use ($row) {
                return $seat['row'] == $row;
            });
            
            // Sort by column number
            usort($rowSeats, function($a, $b) {
                return $a['column'] - $b['column'];
            });
            
            foreach ($rowSeats as $seat) {
                $colspan = isset($seat['colspan']) ? $seat['colspan'] : 1;
                $rowspan = isset($seat['rowspan']) ? $seat['rowspan'] : 1;
                $value = $seat['value'];
                $label = isset($seat['label']) ? $seat['label'] : $value;
                
                $attrCol = 'colspan="' . $colspan . '" rowspan="' . $rowspan . '"';
                
                if (is_numeric($value)) {
                    // Numeric seat (can be booked)
                    $tableLayout .= '<td ' . $attrCol . ' style="height: 30px; width: ' . $tableWidth . 'px; text-align: center; vertical-align: middle; font-size: 10px;">';
                    
                    $seatColor = '';
                    $seatChkColor = '';
                    $checked = '';
                    
                    if (!empty($seatInactive[$value])) {
                        if ($user['User']['type'] == 2) {
                            // Internal User view
                            switch ($seatInactive[$value]['status']) {
                                case 2: // Sold
                                    $seatColor = 'background: green;';
                                    $seatChkColor = 'color: green;';
                                    $totalSSold++;
                                    break;
                                case 1: // Phone Call
                                    $seatColor = 'background: yellow;';
                                    $seatChkColor = 'color: yellow;';
                                    $totalSBooked++;
                                    break;
                                case 3: // Busy
                                    $seatColor = 'background: red;';
                                    $seatChkColor = 'color: red;';
                                    $totalSBusy++;
                                    break;
                                case 4: // Selected
                                    $checked = '1';
                                    $seatColor = 'background: yellow;';
                                    $seatChkColor = 'color: yellow;';
                                    break;
                                case 6: // Agency Offline
                                    $seatColor = 'background: greenyellow;';
                                    $seatChkColor = 'color: greenyellow;';
                                    $totalSAgOff++;
                                    break;
                                case 7: // Agency Online
                                    $seatColor = 'background: #F6921E;';
                                    $seatChkColor = 'color: #F6921E;';
                                    $totalSAgOnl++;
                                    break;
                            }
                        } else {
                            // Agency User view
                            if (in_array($seatInactive[$value]['status'], [2, 6, 7])) {
                                $seatColor = 'background: green;';
                                $seatChkColor = 'color: green;';
                                $totalSSold++;
                            } elseif (in_array($seatInactive[$value]['status'], [1, 3])) {
                                $seatColor = 'background: red;';
                                $seatChkColor = 'color: red;';
                                $totalSBusy++;
                            } elseif ($seatInactive[$value]['status'] == 4) {
                                $checked = '1';
                                $seatColor = 'background: yellow;';
                                $seatChkColor = 'color: yellow;';
                            }
                        }
                        
                        if ($checked != '') {
                            $tableLayout .= '<div style="width: ' . $tableWidth . 'px; height: ' . $tableHeight . 'px; background: url(../img/button/' . $seatImg . ') center no-repeat;">';
                            $tableLayout .= '<input type="checkbox" checked="" disabled="" lbl="' . $label . '" value="' . $value . '" style="cursor: pointer; margin-top: ' . $seatChkMargin . 'px;" />';
                            $tableLayout .= '</div><hr style="width: ' . $tableWidth . 'px; height: 5px; padding: 0px; margin: 0px; border: 1px solid #000;' . $seatColor . '" />' . $label;
                        } else {
                            if ($user['User']['type'] == 2) {
                                // Internal User tooltip
                                $mouseOver = TABLE_TICKET_CODE . ':<br/>' . $seatInactive[$value]['code'] . '<br/>' . 
                                            TABLE_ORIGIN . ':<br/>' . $seatInactive[$value]['origin'] . '<br/>' . 
                                            TABLE_DESTINATION_TO . ':<br/>' . $seatInactive[$value]['dest'] . '<br/>' . 
                                            TABLE_TELEPHONE . ':<br/>' . $seatInactive[$value]['tel'] . '<br/>' . 
                                            TABLE_SOLD_BY . ':<br/>' . $seatInactive[$value]['user'] . '<br/>' . 
                                            TABLE_BRANCH . ':<br/>' . $seatInactive[$value]['branch'];
                                
                                if (!empty($seatInactive[$value]['agency'])) {
                                    $mouseOver .= '<br/>' . MENU_AGENT . ':<br/>' . $seatInactive[$value]['agency'] . 
                                                '<br/>' . TABLE_AGENT_REFERENCE . ':<br/>' . $seatInactive[$value]['reference'];
                                }
                                
                                $mouseOver .= '<br/>' . TABLE_NOTE . ':<br/>' . $seatInactive[$value]['note'];
                                
                                $tableLayout .= '<div style="width: ' . $tableWidth . 'px; height: ' . $tableHeight . 'px; background: url(../img/button/' . $seatImg . ') center no-repeat;" onmouseover="Tip(\'' . $mouseOver . '\')">';
                                $tableLayout .= '<i class="fa fa-check" style="margin-top: ' . $seatChkMargin . 'px; ' . $seatChkColor . '"></i>';
                                $tableLayout .= '</div><hr style="width: ' . $tableWidth . 'px; height: 5px; padding: 0px; margin: 0px; border: 1px solid #000;' . $seatColor . '" />' . $label . $seatInactive[$value]['gender'];
                            } elseif ($user['User']['type'] == 3) {
                                // Agency User tooltip (only show their own bookings)
                                $mouseOver = "";
                                $sqlUserAg = mysql_query("SELECT * FROM t_agents WHERE user_id = " . $user['User']['id'] . " AND status > 0 LIMIT 1");
                                if (mysql_num_rows($sqlUserAg)) {
                                    $rowUserAg = mysql_fetch_array($sqlUserAg);
                                    if ($seatInactive[$value]['agencyId'] == $rowUserAg['id']) {
                                        $mouseOver = TABLE_TICKET_CODE . ':<br/>' . $seatInactive[$value]['code'] . '<br/>' . 
                                                    TABLE_ORIGIN . ':<br/>' . $seatInactive[$value]['origin'] . '<br/>' . 
                                                    TABLE_DESTINATION_TO . ':<br/>' . $seatInactive[$value]['dest'] . '<br/>' . 
                                                    TABLE_TELEPHONE . ':<br/>' . $seatInactive[$value]['tel'] . '<br/>' . 
                                                    TABLE_SOLD_BY . ':<br/>' . $seatInactive[$value]['user'] . '<br/>' . 
                                                    TABLE_BRANCH . ':<br/>' . $seatInactive[$value]['branch'] . '<br/>' . 
                                                    MENU_AGENT . ':<br/>' . $seatInactive[$value]['agency'] . '<br/>' . 
                                                    TABLE_AGENT_REFERENCE . ':<br/>' . $seatInactive[$value]['reference'] . '<br/>' . 
                                                    TABLE_NOTE . ':<br/>' . $seatInactive[$value]['note'];
                                    }
                                }
                                
                                $tableLayout .= '<div style="width: ' . $tableWidth . 'px; height: ' . $tableHeight . 'px; background: url(../img/button/' . $seatImg . ') center no-repeat;" onmouseover="Tip(\'' . $mouseOver . '\')">';
                                $tableLayout .= '<i class="fa fa-check" style="margin-top: ' . $seatChkMargin . 'px; ' . $seatChkColor . '"></i>';
                                $tableLayout .= '</div><hr style="width: ' . $tableWidth . 'px; height: 5px; padding: 0px; margin: 0px; border: 1px solid #000;' . $seatColor . '" />' . $label . $seatInactive[$value]['gender'];
                            } else {
                                // Other users
                                $tableLayout .= '<div style="width: ' . $tableWidth . 'px; height: ' . $tableHeight . 'px; background: url(../img/button/' . $seatImg . ') center no-repeat;">';
                                $tableLayout .= '<i class="fa fa-check" style="margin-top: ' . $seatChkMargin . 'px; ' . $seatChkColor . '"></i>';
                                $tableLayout .= '</div><hr style="width: ' . $tableWidth . 'px; height: 5px; padding: 0px; margin: 0px; border: 1px solid #000;' . $seatColor . '" />' . $label . $seatInactive[$value]['gender'];
                            }
                        }
                    } else {
                        // Available seat
                        $tableLayout .= '<div class="ticketBookingSeatSelect" style="width: ' . $tableWidth . 'px; height: ' . $tableHeight . 'px; background: url(../img/button/' . $seatImg . ') center no-repeat; cursor: pointer;">';
                        $tableLayout .= '<input type="checkbox" lbl="' . $label . '" value="' . $value . '" style="cursor: pointer; margin-top: ' . $seatChkMargin . 'px;" />';
                        $tableLayout .= '</div><hr style="width: ' . $tableWidth . 'px; height: 5px; padding: 0px; margin: 0px; border: 1px solid #000;" class="ticketBookingSeatColor" />' . $label;
                        $totalSAvbl++;
                    }
                } else {
                    // Non-numeric seat (special seats)
                    $tableLayout .= '<td ' . $attrCol . ' style="height: ' . $tableHeight . 'px; width: ' . $tableWidth . 'px; text-align: center; vertical-align: middle;">';
                    
                    if ($label == 'Open1' || $label == 'Open2') {
                        $tableLayout .= '<span style="font-size: 11px;">Open Air Seat</span>';
                    } elseif ($label == 'Capitain') {
                        $tableLayout .= '<img src="' . $this->webroot . 'img/button/captain.png" alt="" style="width: 24px;" />';
                    } elseif ($label == 'Hostess') {
                        $tableLayout .= '<img src="' . $this->webroot . 'img/button/hostess.png" alt="" style="width: 32px;" />';
                    } elseif ($label == 'Toilet') {
                        $tableLayout .= '<span style="font-size: 10px;">WC</span>';
                    } else {
                        $tableLayout .= '<span style="font-size: 11px;">' . $label . '</span>';
                    }
                }
                
                $tableLayout .= '</td>';
            }
            
            $tableLayout .= '</tr>';
        }
        ?>
        <table style="width: 100%;" cellpadding="5">
            <tr>
                <th style="width:25%;"><?php __(TABLE_TRAVEL_DATE); ?></th>
                <td style="width:1%;">:</td>
                <td>
                    <?php echo dateShort($date); ?>
                </td>
            </tr>
            <tr>
                <th><?php __(GENERAL_DESCRIPTION); ?></th>
                <td>:</td>
                <td>
                    <?php echo $tJourney['TJourney']['description']; ?>
                </td>
            </tr>
            <tr>
                <th><?php __(TABLE_DIRECTION); ?></th>
                <td>:</td>
                <td>
                    <?php
                    $destFrom = '';
                    $destTo   = '';
                    $sqlDes = mysql_query("SELECT id, name FROM t_destinations WHERE id IN (".$tJourney['TJourney']['t_destination_from_id'].", ".$tJourney['TJourney']['t_destination_to_id'].")");
                    while($rowDes = mysql_fetch_array($sqlDes)){
                        if($rowDes['id'] == $tJourney['TJourney']['t_destination_from_id']){
                            $destFrom = $rowDes['name'];
                        } else {
                            $destTo   = $rowDes['name'];
                        }
                    }
                    echo $destFrom." to ".$destTo;
                    ?>
                </td>
            </tr>
            <tr>
                <th><?php __(MENU_DEPARTURE_TIME); ?></th>
                <td>:</td>
                <td>
                    <?php echo $tDepartureTime['TDepartureTime']['name']; ?>
                </td>
            </tr>
            <tr>
                <th><?php __(MENU_TRANSPORTATION_TYPE); ?></th>
                <td>:</td>
                <td>
                    <?php
                    echo $tJourney['TTransportationType']['name'];
                    ?>
                </td>
            </tr>
            <tr>
                <th><?php __(TABLE_TOTAL_SEAT); ?></th>
                <td>:</td>
                <td>
                    <?php 
                    echo $tJourney['TTransportationType']['number_of_seat'];
                    ?>
                </td>
            </tr>
            <tr>
                <th><?php __("Total Scanned"); ?></th>
                <td>:</td>
                <td>
                    <?php 
                    echo number_format($totalCheckedIn, 0);
                    ?>
                </td>
            </tr>
            <tr>
                <th><?php __("Total Un-Scan"); ?></th>
                <td>:</td>
                <td>
                    <?php 
                    echo $tJourney['TTransportationType']['number_of_seat'] - $totalCheckedIn;
                    ?>
                </td>
            </tr>
            <tr>
                <th><?php __(TABLE_TOTAL_CUSTOMER); ?></th>
                <td>:</td>
                <td>
                    <?php
                    $totalCus = 0;
                    foreach($destBooked AS $cus){
                        $totalCus += $cus['total'];
                    }
                    echo number_format($totalCus, 0);
                    ?>
                </td>
            </tr>
            <?php
            foreach($destBooked AS $dest){
            ?>
            <tr>
                <th><?php echo $dest['name']; ?></th>
                <td>:</td>
                <td>
                    <?php 
                    echo number_format($dest['total'], 0);
                    ?>
                </td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <th><?php __(TABLE_TOTAL_AMOUNT); ?></th>
                <td>:</td>
                <td>
                    <?php
                    echo number_format($totalAmt, 2)." $";
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset style="width: 64%; float: left;">
        <legend>
        <?php
        echo TABLE_SEAT_INFORMATION;
        ?>
        </legend>
        <?php
        $totalTableWeight = 180 * $totalCol;
        ?>
        <table cellpadding="0" cellspacing="0" style="width: 100%;">
            <?php
            if($tJourney['TJourney']['type'] == 2){
            ?>
            <tr>
                <td colspan="2" style="font-size: 14px; font-weight: bold;"><?php echo $tJourneyF['TJourney']['description'];  ?></td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <td style="width: 80%; vertical-align: top;">
                    <table cellpadding="5" cellspacing="0" style="width: <?php echo $totalTableWeight; ?>px;">
                        <?php echo $tableLayout; ?>
                    </table>
                </td>
                <td style="vertical-align: top;">
                    <table cellpadding="5" cellspacing="0" style="width: 100%;">
                        <tr>
                            <td style="text-align: left;">
                                <hr style="width: 20px; height: 5px; padding: 0px; margin: 0px; border: 1px solid #000;" /> <?php echo $totalSAvbl; ?> <?php echo TABLE_AVAILABLE; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">
                                <hr style="width: 20px; height: 5px; padding: 0px; margin: 0px; border: 1px solid #000; background: yellow; margin-top: 10px;" /> <?php echo $totalPhone; ?> <?php echo TABLE_PHONE_CALL; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">
                                <hr style="width: 20px; height: 5px; padding: 0px; margin: 0px; border: 1px solid #000; background: yellow; margin-top: 10px;" /> <?php echo $totalPhoneCancel; ?> <?php echo "Phone Call Cancel"; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">
                                <hr style="width: 20px; height: 5px; padding: 0px; margin: 0px; border: 1px solid #000; background: green; margin-top: 10px;" /> <?php echo $totalSSold; ?> <?php echo TABLE_SOLD; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <hr style="width: 20px; height: 5px; padding: 0px; margin: 0px; border: 1px solid #000; background: #1abef7; margin-top: 10px;" /> <?php echo $totalApp; ?> <?php echo "APP"; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <hr style="width: 20px; height: 5px; padding: 0px; margin: 0px; border: 1px solid #000; background: #1a6ef7; margin-top: 10px;" /> <?php echo $totalWeb; ?> <?php echo "Website"; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <hr style="width: 20px; height: 5px; padding: 0px; margin: 0px; border: 1px solid #000; background: #a806f9; margin-top: 10px;" /> <?php echo $totalMiniApp; ?> <?php echo "Mini App"; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <hr style="width: 20px; height: 5px; padding: 0px; margin: 0px; border: 1px solid #000; background: #f906a1; margin-top: 10px;" /> <?php echo $totalTerminal; ?> <?php echo "Terminal"; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <hr style="width: 20px; height: 5px; padding: 0px; margin: 0px; border: 1px solid #000; background: greenyellow; margin-top: 10px;" /> <?php echo $totalSAgOff; ?> <?php echo TABLE_AGENCY_OFFLINE; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <hr style="width: 20px; height: 5px; padding: 0px; margin: 0px; border: 1px solid #000; background: #F6921E; margin-top: 10px;" /> <?php echo $totalSAgOnl; ?> <?php echo TABLE_AGENCY_ONLINE; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <hr style="width: 20px; height: 5px; padding: 0px; margin: 0px; border: 1px solid #000; background: red; margin-top: 10px;" /> <?php echo $totalSBusy; ?> <?php echo TABLE_BUSY; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <?php
        if($tJourney['TJourney']['type'] == 2){
            $k = 0;
            foreach($tJourneyT AS $tJourneyS){
                if($k > 0){
                    $sqlJourney = mysql_query("SELECT * FROM t_journeys WHERE id = ".$tJourneyS['TJourneyTransit']['t_journey_departure_id']);
                    $rowJourney = mysql_fetch_array($sqlJourney);
                    // Check Transportation Type Change
                    $sqlCT = mysql_query("SELECT t_transportation_type_id FROM t_journey_change_transportations WHERE offline_project_id = ".$user['User']['offline_project_id']." AND status = 1 AND start >= '".$date."' AND end <= '".$date."' AND t_journey_id = ".$tJourneyS['TJourneyTransit']['t_journey_departure_id']." ORDER BY id DESC LIMIT 1");
                    if(mysql_num_rows($sqlCT)){
                        $rowCT = mysql_fetch_array($sqlCT);
                        $trasportationId = $rowCT['t_transportation_type_id'];
                    } else {
                        $trasportationId = $rowJourney['t_transportation_type_id'];
                    }
                    $sqlBoat = mysql_query("SELECT * FROM t_transportation_types WHERE id = ".$trasportationId);
                    $rowBoat = mysql_fetch_array($sqlBoat);
                    $layouts = json_decode($rowBoat['layout'], true);
                    $tableLayout  = '';
                    $tableWeight  = 105;
                    $totalCol     = 0;
                    $seatInactive = array();
                    $sqlSeatC = mysql_query("SELECT t_seat_controls.status, t_seat_controls.seat_number, t_tickets.code, t_tickets.price_type, t_tickets.t_agent_id, t_tickets.agt_refer_code, t_tickets.confirm_by, t_tickets.created_by, t_tickets.t_destination_to_id, t_tickets.t_journey_transit_id, t_tickets.t_boarding_point_id FROM t_seat_controls INNER JOIN t_tickets ON t_tickets.id = t_seat_controls.t_ticket_id WHERE t_seat_controls.t_transportation_type_id = ".$trasportationId." AND t_seat_controls.t_route_id = ".$rowJourney['t_route_id']." AND t_seat_controls.journey_date = '".$date."' AND t_seat_controls.status IN (1,2,3)");
                    while($rowSeatC = mysql_fetch_array($sqlSeatC)){
                        if(strtotime($date) == strtotime(date("Y-m-d"))){
                            $status = $rowSeatC['status'];
                        } else {
                            $status = $rowSeatC['status'];
                        }
                        if($status == 2){
                            $status = 1;
                        } else if($status == 1) {
                            $status = 2;
                        } else {
                            $status = 3;
                        }
                        if($rowSeatC['confirm_by'] != ''){
                            $createdBy = $rowSeatC['confirm_by'];
                        } else {
                            $createdBy = $rowSeatC['created_by'];
                        }
                        $sqlUser = mysql_query("SELECT first_name, last_name FROM users WHERE id = ".$createdBy);
                        $rowUser = mysql_fetch_array($sqlUser);
                        // Destination From
                        $destFrom  = '';
                        if(!empty($rowSeatC['t_destination_from_id'])){
                            $transit = '';
                            if($rowSeatC['t_journey_transit_id'] != ''){
                                $transit = '(Transit)';
                            }
                            $sqlDest = mysql_query("SELECT code FROM t_destinations WHERE id = ".$rowSeatC['t_destination_from_id']);
                            $rowDest = mysql_fetch_array($sqlDest);
                            $destFrom  = $rowDest[0].' '.$transit.'';
                        }
                        // Destination To
                        $destTo  = '';
                        if(!empty($rowSeatC['t_destination_to_id'])){
                            $transit = '';
                            if($rowSeatC['t_journey_transit_id'] != ''){
                                $transit = '(Transit)';
                            }
                            $sqlDest = mysql_query("SELECT code FROM t_destinations WHERE id = ".$rowSeatC['t_destination_to_id']);
                            $rowDest = mysql_fetch_array($sqlDest);
                            $destTo  = $rowDest[0].' '.$transit.'';
                        }
                        $seatInactive[$rowSeatC['seat_number']]['status'] = $status;
                        $seatInactive[$rowSeatC['seat_number']]['code'] = $rowSeatC['code'];
                        $seatInactive[$rowSeatC['seat_number']]['user'] = $rowUser['first_name']." ".$rowUser['last_name'];
                        $seatInactive[$rowSeatC['seat_number']]['dest_from'] = $destFrom;
                        $seatInactive[$rowSeatC['seat_number']]['dest'] = $destTo;
                        if($rowSeatC['price_type'] == 3){
                            $seatInactive[$rowSeatC['seat_number']]['type'] = '(VIP)';
                        } else {
                            $seatInactive[$rowSeatC['seat_number']]['type'] = '';
                        }
                        if($rowSeatC['t_agent_id'] != ''){
                            $sqlAg = mysql_query("SELECT code, name FROM t_agents WHERE id = ".$rowSeatC['t_agent_id']);
                            $rowAg = mysql_fetch_array($sqlAg);
                            $seatInactive[$rowSeatC['seat_number']]['agency'] = $rowAg['code']." - ".$rowAg['name'];
                            $seatInactive[$rowSeatC['seat_number']]['ref'] = $rowSeatC['agt_refer_code'];
                        } else {
                            $seatInactive[$rowSeatC['seat_number']]['agency'] = '';
                            $seatInactive[$rowSeatC['seat_number']]['ref'] = '';
                        }
                        $seatInactive[$tSeatControll['TSeatControl']['seat_number']]['isPicked']     = $tSeatControll['TSeatControl']['is_pickup'];
                    }
                    // List Seat
                    foreach($layouts AS $layout){
                        $cols = $layout['col'];
                        $tableLayout .= '<tr>';
                        $totalCol = 0;
                        foreach($cols AS $col){
                            $colspan = $col['attr']['colspan'];
                            $value   = $col['value'];
                            $label   = $value;
                            if (array_key_exists("label", $col)) {
                                $label = $col['label'];
                            }
                            $attrCol = '';
                            if($colspan != ''){
                                $attrCol = 'colspan="'.$colspan.'"';
                                $totalCol = $totalCol + $colspan;
                            } else {
                                $totalCol++;
                            }
                            if(is_numeric($value)){
                                $tableLayout .= '<td '.$attrCol.' style="height: 40px; width: '.$tableWeight.'px; text-align: left; vertical-align: middle; font-size: 10px;">';
                                $seatImg = 'seating-active-25.png';
                                $ticket  = '';
                                if(!empty($seatInactive[$value])){
                                    if($seatInactive[$value]['status'] == 1){
                                        $seatImg = 'seat-sold.png';
                                    } else if ($seatInactive[$value]['status'] == 2) {
                                        $seatImg = 'seat-booked.png';
                                    }
                                    $picked = "";
                                    if($seatInactive[$value]['isPicked'] == 1){
                                        $picked = '<br/><img src="'.$this->webroot.'img/button/active.png" style="width: 12px;" /> Checked In';
                                    }
                                    $ticket  = '<br/>'.$seatInactive[$value]['code'].' '.$seatInactive[$value]['type'].'<br/>DF:'.$seatInactive[$value]['dest_from'].'<br/>DT:'.$seatInactive[$value]['dest'].'<br/>'.$seatInactive[$value]['user'].'<br/>AG:'.$seatInactive[$value]['agency'].'<br/>Ref:'.$seatInactive[$value]['ref'].$picked;
                                }
                                $tableLayout .= '<img src="'.$this->webroot.'img/button/'.$seatImg.'" style="width: 12px;" /> '.$label.$ticket;
                            } else {
                                $tableLayout .= '<td '.$attrCol.' style="height: 20px; width: '.$tableWeight.'px; text-align: center; vertical-align: middle;">';
                                if($label == 'Open1' || $label == 'Open2') {
                                    $tableLayout .= 'Open Air Seat';
                                } else {
                                    $tableLayout .= $label;
                                }
                            }
                            $tableLayout .= '</td>';
                        }
                        $tableLayout .= '</tr>';
                    }
                    $totalTableWeight = $tableWidth * $totalCol;
        ?>
            <table cellpadding="0" cellspacing="0" style="width: 100%; margin-top: 10px;">
                <?php
                if($tJourney['TJourney']['type'] == 2){
                ?>
                <tr>
                    <td colspan="2" style="font-size: 14px; font-weight: bold;"><?php echo $rowJourney['description'];  ?></td>
                </tr>
                <?php
                }
                ?>
                <tr>
                    <td style="width: 80%; vertical-align: top;">
                        <table cellpadding="5" cellspacing="0" style="width: <?php echo $totalTableWeight; ?>px;">
                            <?php echo $tableLayout; ?>
                        </table>
                    </td>
                    <td style="vertical-align: top;">
                        <table cellpadding="5" cellspacing="0" style="width: 100%;">
                            <tr>
                                <td style="text-align: center;">
                                    <img src="<?php echo $this->webroot; ?>img/button/seating-active-25.png" style="width: 30px;" /> <br/>Available
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">
                                    <img src="<?php echo $this->webroot; ?>img/button/seat-booked.png" style="width: 30px;" /> <br/>Booked
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">
                                    <hr style="width: 20px; height: 5px; padding: 0px; margin: 0px; border: 1px solid #000; background: green; margin-top: 10px;" /><?php echo TABLE_SOLD; ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        <?php
                }
                $k++;
            }
        }
        ?>
    </fieldset>
    <div style="clear: both;"></div>
</div>