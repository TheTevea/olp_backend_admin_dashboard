<?php
// schedule_data.php - Complete data generation for bus schedules
include("includes/function.php");

function generateScheduleData($user, $journeys, $date, $isReturn) {
    $scheduleData = [];
    $dateAllowBooking = date("Y-m-d");
    $time = date("H");
    
    // Adjust booking date for early morning hours
    if($time >= 0 && $time <= 4) {
        $dateNow = date("Y-m-d");
        $dateAllowBooking = date('Y-m-d', strtotime('-1 day', strtotime($dateNow)));
    }

    if(!empty($journeys)) {
        $travelDate    = $date;
        $checkMidNight = false;
        $showSchedule  = true;
        
        if(strtotime($date) >= strtotime("2021-12-07")) {
            $showSchedule = false;
        }

        // Process each journey
        foreach($journeys as $journey) {
            $depare = explode(":", $journey['TDepartureTime']['name']);
            $depatureTime = (int) $depare[0];
            
            if($depatureTime > 3 || $showSchedule == true) {
                $isActive = checkJourneyActive($journey, $date);
                
                if($isActive) {
                    $blockStatus = checkBlockStatus($journey, $date);
                    $seatAvailability = checkSeatAvailability($journey, $date, $user);
                    $pricing = getPricing($journey, $date, $user);
                    
                    if($journey['TJourney']['type'] == 2) { // Transit journey
                        $transitData  = processTransitJourney($journey, $travelDate, $user);
                        $scheduleData = array_merge($scheduleData, $transitData);
                    } else { // Direct journey
                        $scheduleData[] = buildScheduleItem(
                            $journey, 
                            $date, 
                            $blockStatus, 
                            $seatAvailability, 
                            $pricing,
                            $user
                        );
                    }
                }
            } else {
                $checkMidNight = true;
            }
        }

        // Process midnight departures
        if($checkMidNight && strtotime($date) >= strtotime("2021-12-07")) {
            $midnightData = processMidnightDepartures($journeys, $travelDate, $user);
            $scheduleData = array_merge($scheduleData, $midnightData);
        }
    }
    
    return $scheduleData;
}

// Helper functions
function checkJourneyActive($journey, $date) {
    if(!empty($journey['TJourney']['active_start']) && !empty($journey['TJourney']['active_end']) && 
       $journey['TJourney']['active_start'] != '0000-00-00' && $journey['TJourney']['active_end'] != '0000-00-00') {
        return (strtotime($journey['TJourney']['active_start']) <= strtotime($date) && 
                strtotime($journey['TJourney']['active_end']) >= strtotime($date));
    }
    return true;
}

function checkBlockStatus($journey, $date) {
    $block = false;
    $blockDeparture = false;
    
    // Check date range block
    if($journey['TJourney']['block_start'] != '0000-00-00' && $journey['TJourney']['block_start'] != '' && 
       $journey['TJourney']['block_end'] != '0000-00-00' && $journey['TJourney']['block_end'] != '') {
        $timeBlockStart = strtotime($journey['TJourney']['block_start']);
        $timeBlockEnd = strtotime($journey['TJourney']['block_end']);
        $departureTime = strtotime($date);
        
        if($departureTime >= $timeBlockStart && $departureTime <= $timeBlockEnd) {
            $block = true;
            $blockDeparture = true;
        }
    }
    
    // Check weekly schedule block
    $nameOfDay = date('D', strtotime($date));
    $sqlBW = mysql_query("SELECT * FROM t_journey_schedules WHERE t_journey_id = ".$journey['TJourney']['id']." AND `".strtolower($nameOfDay)."` = 1");
    if(!mysql_num_rows($sqlBW)) {
        $block = true;
    }
    
    // Check departure time block
    if($block == false) {
        $dateNow = strtotime(date("Y-m-d"));
        $departure = strtotime($date); 
        
        if($dateNow == $departure) {
            $delayTime = !empty($journey['TJourney']['delay_af_departure']) ? $journey['TJourney']['delay_af_departure'] * 60 : 0;
            $timeJourney = strtotime(date("Y-m-d", $departure)." ".$journey['TDepartureTime']['name']);
            $timeNow = strtotime(date("Y-m-d H:i:s")) - $delayTime;
            
            if($timeNow > $timeJourney) {
                $blockDeparture = true;
            }
        } else if($dateNow > $departure) {
            $blockDeparture = true;
        }
    }
    
    return [
        'block' => $block,
        'blockDeparture' => $blockDeparture,
        'blockReason' => $block ? 'block' : ($blockDeparture ? 'departure' : '')
    ];
}

function checkSeatAvailability($journey, $date, $user) {
    if($journey['TJourney']['type'] == 3) {
        $seatBooked = [];
        $sqlTransit = mysql_query("SELECT t_journeys.t_transportation_type_id, t_journeys.t_route_id 
                                 FROM t_journeys 
                                 INNER JOIN t_journey_transits ON t_journey_transits.t_journey_departure_id = t_journeys.id 
                                 WHERE t_journey_transits.t_journey_id = ".$journey['TJourney']['id']." 
                                 GROUP BY t_journey_transits.t_journey_departure_id");
        
        while($rowTransit = mysql_fetch_array($sqlTransit)) {
            $sqlSeat = mysql_query("SELECT seat_number FROM t_seat_controls 
                                  WHERE t_transportation_type_id = ".$rowTransit['t_transportation_type_id']." 
                                  AND t_route_id = ".$rowTransit['t_route_id']." 
                                  AND journey_date = '".$date."' 
                                  AND status IN (1,2,3)");
            
            while($rowSeat = mysql_fetch_array($sqlSeat)) {
                $seatBooked[$rowSeat['seat_number']] = 1;
            }
        }
        
        $bookedCount = count($seatBooked);
        $trasportationId = $rowTransit['t_transportation_type_id'];
    } else {
        // Check for transportation type changes
        $sqlCT = mysql_query("SELECT t_journey_change_transportations.t_transportation_type_id, 
                             t_transportation_types.number_of_seat 
                             FROM t_journey_change_transportations 
                             INNER JOIN t_transportation_types ON t_transportation_types.id = t_journey_change_transportations.t_transportation_type_id 
                             WHERE t_journey_change_transportations.offline_project_id = ".$user['User']['offline_project_id']." 
                             AND t_journey_change_transportations.status = 1 
                             AND t_journey_change_transportations.start >= '".$date."' 
                             AND t_journey_change_transportations.end <= '".$date."' 
                             AND t_journey_change_transportations.t_journey_id = ".$journey['TJourney']['id']." 
                             ORDER BY t_journey_change_transportations.id DESC LIMIT 1");
        
        if(mysql_num_rows($sqlCT)) {
            $rowCT = mysql_fetch_array($sqlCT);
            $trasportationId = $rowCT['t_transportation_type_id'];
            $totalSeat = $rowCT['number_of_seat'];
        } else {
            $trasportationId = $journey['TJourney']['t_transportation_type_id'];
            $totalSeat = $journey['TTransportationType']['number_of_seat'];
        }
        
        $sqlSeat = mysql_query("SELECT COUNT(*) as booked FROM t_seat_controls 
                              WHERE t_transportation_type_id = ".$trasportationId." 
                              AND t_route_id = ".$journey['TJourney']['t_route_id']." 
                              AND journey_date = '".$date."' 
                              AND status IN (1,2,3)");
        $rowSeat = mysql_fetch_array($sqlSeat);
        $bookedCount = $rowSeat['booked'];
    }
    
    // Get blocked seats count
    $totalBlock = 0;
    $sqlBlock = mysql_query("SELECT t_journey_seat_block_details.seat_number 
                            FROM t_journey_seat_blocks 
                            INNER JOIN t_journey_seat_block_details ON t_journey_seat_block_details.t_journey_seat_block_id = t_journey_seat_blocks.id 
                            WHERE t_journey_seat_blocks.start <= '".$date."' 
                            AND t_journey_seat_blocks.end >= '".$date."' 
                            AND t_journey_seat_blocks.t_journey_id = ".$journey['TJourney']['id']." 
                            AND t_journey_seat_blocks.t_departure_time_id = ".$journey['TJourney']['t_departure_time_id']." 
                            AND t_journey_seat_blocks.is_active = 1 
                            GROUP BY t_journey_seat_block_details.seat_number");
    
    while($rowBlock = mysql_fetch_array($sqlBlock)) {
        $totalBlock++;
    }
    
    return [
        'booked' => $bookedCount,
        'total' => $totalSeat,
        'blocked' => $totalBlock,
        'available' => $totalSeat - ($bookedCount + $totalBlock),
        'is_full' => ($bookedCount + $totalBlock) >= $totalSeat
    ];
}

function getPricing($journey, $date, $user) {
    $price = $priceForeigner = $priceOrg = $priceForOrg = $journey['TJourney']['unit_price'];
    $isPricePromo = 0;
    
    // Check default price
    $sqlPD = mysql_query("SELECT * FROM t_journey_price_defaults 
                         WHERE offline_project_id = ".$user['User']['offline_project_id']." 
                         AND destination_from_id = ".$journey['TJourney']['t_destination_from_id']." 
                         AND destination_to_id = ".$journey['TJourney']['t_destination_to_id']." 
                         AND t_transportation_type_id = ".$journey['TJourney']['t_transportation_type_id']." 
                         AND status = 1 
                         AND main_branch_id = ".$user['User']['main_branch_id']." 
                         ORDER BY id DESC LIMIT 1");
    
    if(mysql_num_rows($sqlPD)) {
        $rowPD = mysql_fetch_array($sqlPD);
        $price = $priceForeigner = $rowPD['price'];
    } else {
        $sqlPDA = mysql_query("SELECT * FROM t_journey_price_defaults 
                             WHERE offline_project_id = ".$user['User']['offline_project_id']." 
                             AND destination_from_id = ".$journey['TJourney']['t_destination_from_id']." 
                             AND destination_to_id = ".$journey['TJourney']['t_destination_to_id']." 
                             AND t_transportation_type_id = ".$journey['TJourney']['t_transportation_type_id']." 
                             AND status = 1 
                             AND (main_branch_id IS NULL OR main_branch_id = '') 
                             ORDER BY id DESC LIMIT 1");
        
        if(mysql_num_rows($sqlPDA)) {
            $rowPDA = mysql_fetch_array($sqlPDA);
            $price = $priceForeigner = $rowPDA['price'];
        }
    }
    
    // Check price periods
    $checkPromoInternal = false;
    
    if($user['User']['type'] == 2) { // Internal user
        $sqlPriceJourneyInternal = mysql_query("SELECT * FROM t_journey_price_periods 
                                              WHERE offline_project_id = 1 
                                              AND start <= '".$date."' 
                                              AND end >= '".$date."' 
                                              AND status = 1 
                                              AND apply_type = 2 
                                              AND t_journey_id = ".$journey['TJourney']['id']." 
                                              ORDER BY id DESC LIMIT 1");
        
        if(mysql_num_rows($sqlPriceJourneyInternal)) {
            $rowPriceJourneyInternal = mysql_fetch_array($sqlPriceJourneyInternal);
            $price = $priceForeigner = $rowPriceJourneyInternal['price'];
            $isPricePromo = 1;
            $checkPromoInternal = true;
        }
    }
    
    if(!$checkPromoInternal) {
        // Public price periods
        $sqlPriceJourney = mysql_query("SELECT * FROM t_journey_price_periods 
                                      WHERE offline_project_id = 1 
                                      AND start <= '".$date."' 
                                      AND end >= '".$date."' 
                                      AND status = 1 
                                      AND apply_type = 1 
                                      AND t_journey_id = ".$journey['TJourney']['id']." 
                                      ORDER BY id DESC LIMIT 1");
        
        if(mysql_num_rows($sqlPriceJourney)) {
            $rowPriceJourney = mysql_fetch_array($sqlPriceJourney);
            $price = $priceForeigner = $rowPriceJourney['price'];
            $isPricePromo = 1;
        } else {
            // Destination-based pricing
            $sqlPrice = mysql_query("SELECT * FROM t_journey_price_periods 
                                   WHERE offline_project_id = ".$user['User']['offline_project_id']." 
                                   AND destination_from_id = ".$journey['TJourney']['t_destination_from_id']." 
                                   AND destination_to_id = ".$journey['TJourney']['t_destination_to_id']." 
                                   AND t_transportation_type_id = ".$journey['TJourney']['t_transportation_type_id']." 
                                   AND start <= '".$date."' 
                                   AND end >= '".$date."' 
                                   AND status = 1 
                                   AND apply_type = 1 
                                   AND main_branch_id = ".$user['User']['main_branch_id']." 
                                   ORDER BY id DESC LIMIT 1");
            
            if(mysql_num_rows($sqlPrice)) {
                $rowPrice = mysql_fetch_array($sqlPrice);
                if($rowPrice['price_type'] == 1) {
                    $price = $priceForeigner = $rowPrice['price'];
                } else {
                    $price += $rowPrice['price'];
                    $priceForeigner += $rowPrice['price'];
                }
                $isPricePromo = 1;
            } else {
                // Default destination pricing
                $sqlPA = mysql_query("SELECT * FROM t_journey_price_periods 
                                    WHERE offline_project_id = ".$user['User']['offline_project_id']." 
                                    AND destination_from_id = ".$journey['TJourney']['t_destination_from_id']." 
                                    AND destination_to_id = ".$journey['TJourney']['t_destination_to_id']." 
                                    AND t_transportation_type_id = ".$journey['TJourney']['t_transportation_type_id']." 
                                    AND start <= '".$date."' 
                                    AND end >= '".$date."' 
                                    AND status = 1 
                                    AND apply_type = 1 
                                    AND (main_branch_id IS NULL OR main_branch_id = '') 
                                    ORDER BY id DESC LIMIT 1");
                
                if(mysql_num_rows($sqlPA)) {
                    $rowPAPrice = mysql_fetch_array($sqlPA);
                    if($rowPAPrice['price_type'] == 1) {
                        $price = $priceForeigner = $rowPAPrice['price'];
                    } else {
                        $price += $rowPAPrice['price'];
                        $priceForeigner += $rowPAPrice['price'];
                    }
                    $isPricePromo = 1;
                }
            }
        }
    }
    
    // Calculate VAT if applicable
    $vat = 0;
    if($journey['TJourney']['company_id'] != 6 && $journey['TJourney']['allow_price_period'] == 0 && $price > 0) {
        $vat = ($price * 10) / 100;
    }
    
    $vatOrg = 0;
    if($journey['TJourney']['company_id'] != 6 && $journey['TJourney']['allow_price_period'] == 0 && $priceOrg > 0) {
        $vatOrg = ($priceOrg * 10) / 100;
    }
    
    return [
        'price' => $price + $vat,
        'original_price' => $priceOrg + $vatOrg,
        'currency_symbol' => $journey['CurrencyCenter']['symbol'],
        'is_promo' => $isPricePromo == 1 && $user['User']['type'] == 2
    ];
}

function buildScheduleItem($journey, $date, $blockStatus, $seatAvailability, $pricing, $user) {
    // Get boarding points
    $boardingPoints = [];
    $sqlBoarding = mysql_query("SELECT t_boarding_points.* FROM t_boarding_points 
                              INNER JOIN t_journey_boarding_points ON t_journey_boarding_points.t_boarding_point_id = t_boarding_points.id
                              WHERE t_journey_boarding_points.t_journey_id = ".$journey['TJourney']['id']." 
                              GROUP BY t_boarding_points.id 
                              ORDER BY t_journey_boarding_points.id");
    
    while($rowBoarding = mysql_fetch_array($sqlBoarding)) {
        $boardingPoints[] = ['name' => $rowBoarding['name']];
    }
    
    // Get dropoff points
    $dropoffPoints = [];
    $sqlDropOff = mysql_query("SELECT * FROM t_drop_offs 
                             WHERE id IN (SELECT t_drop_off_id FROM t_journey_drop_offs WHERE t_journey_id = ".$journey['TJourney']['id'].")");
    
    while($rowDropOff = mysql_fetch_array($sqlDropOff)) {
        $dropoffPoints[] = ['name' => $rowDropOff['name']];
    }
    
    return [
        'id' => $journey['TJourney']['id'],
        'departure_time_id' => $journey['TDepartureTime']['id'],
        'departure_time' => $journey['TDepartureTime']['name'],
        'date' => $date,
        'description' => $journey['TJourney']['description'],
        'nation_road' => $journey['TJourney']['nation_road'],
        'transport_name' => $journey['TTransportationType']['name'],
        'journey_type' => $journey['TJourney']['transport_route_display'],
        'boarding_points' => $boardingPoints,
        'dropoff_points' => $dropoffPoints,
        'is_blocked' => $blockStatus['block'] || $blockStatus['blockDeparture'],
        'block_reason' => $blockStatus['blockReason'],
        'is_full' => $seatAvailability['is_full'],
        'booked_seats' => $seatAvailability['booked'],
        'total_seats' => $seatAvailability['total'],
        'price' => $pricing['price'],
        'original_price' => $pricing['original_price'],
        'currency_symbol' => $pricing['currency_symbol'],
        'is_promo' => $pricing['is_promo'],
        'type' => $journey['TJourney']['type']
    ];
}

function processTransitJourney($journey, $travelDate, $user) {
    $transitItems = [];
    
    $sqlTranJourney = mysql_query("SELECT t_journeys.*, t_journey_transits.is_next_day 
                                 FROM t_journeys 
                                 INNER JOIN t_journey_transits ON t_journey_transits.t_journey_departure_id = t_journeys.id 
                                 AND t_journey_transits.t_journey_id = ".$journey['TJourney']['id']." 
                                 WHERE t_journeys.status = 1 
                                 ORDER BY t_journey_transits.id");
    
    while($rowTranJourney = mysql_fetch_array($sqlTranJourney)) {
        $date = $rowTranJourney['is_next_day'] == 1 ? date("Y-m-d", strtotime("+1 day", strtotime($travelDate))) : $travelDate;
        
        // Get destinations
        $destFrom = $destTo = "";
        $sqlDest = mysql_query("SELECT * FROM t_destinations 
                              WHERE id IN (".$rowTranJourney['t_destination_from_id'].",".$rowTranJourney['t_destination_to_id'].")");
        
        while($rowDest = mysql_fetch_array($sqlDest)) {
            if($rowDest['id'] == $rowTranJourney['t_destination_from_id']) {
                $destFrom = $rowDest['name'];
            } else {
                $destTo = $rowDest['name'];
            }
        }
        
        // Process each transit journey segment
        $blockStatus = checkBlockStatus($rowTranJourney, $date);
        $seatAvailability = checkSeatAvailability($rowTranJourney, $date, $user);
        $pricing = getPricing($rowTranJourney, $date, $user);
        
        $transitItems[] = buildScheduleItem(
            $rowTranJourney,
            $date,
            $blockStatus,
            $seatAvailability,
            $pricing,
            $user
        );
    }
    
    return $transitItems;
}

function processMidnightDepartures($journeys, $travelDate, $user) {
    $midnightItems = [];
    
    foreach($journeys as $journey) {
        $depare = explode(":", $journey['TDepartureTime']['name']);
        $depatureTime = (int) $depare[0];
        
        if($depatureTime <= 3) {
            $date = date("Y-m-d", strtotime("+1 day", strtotime($travelDate)));
            $isActive = checkJourneyActive($journey, $date);
            
            if($isActive) {
                $blockStatus = checkBlockStatus($journey, $date);
                $seatAvailability = checkSeatAvailability($journey, $date, $user);
                $pricing = getPricing($journey, $date, $user);
                
                if($journey['TJourney']['type'] == 2) {
                    $transitData = processTransitJourney($journey, $travelDate, $user);
                    $midnightItems = array_merge($midnightItems, $transitData);
                } else {
                    $midnightItems[] = buildScheduleItem(
                        $journey, 
                        $date, 
                        $blockStatus, 
                        $seatAvailability, 
                        $pricing,
                        $user
                    );
                }
            }
        }
    }
    
    return $midnightItems;
}

// Generate the final schedule data
$scheduleData = generateScheduleData($user, $journeys, $date, $isReturn);
?>
<!-- schedule_display.php - Displays the schedule data -->
<style>
/* Base Styles */
.schedule-container {
    font-family: 'Segoe UI', Arial, sans-serif;
    max-width: 1400px;
    margin: 0 auto;
    padding: 15px;
}

.return-header {
    background-color: #FFF0F0;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    border-left: 4px solid #FFAAAA;
}

.return-header table {
    width: 100%;
}

.return-header td {
    padding: 5px 0;
    color: #807E8E;
}

.return-header td:nth-child(odd) {
    font-weight: 600;
}

/* Desktop Table Styles */
.schedule-list table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    box-shadow: 0 2px 12px rgba(255,170,170,0.15);
    border-radius: 8px;
    overflow: hidden;
}

.schedule-list th {
    background-color: #ef4444;
    color: white;
    padding: 14px 12px;
    text-align: left;
    font-size: 14px;
    font-weight: 600;
    border-bottom: 2px solid #FFC4C4;
}

.schedule-list td {
    padding: 14px 12px;
    border-bottom: 1px solid #FFE0E0;
    vertical-align: middle;
    color: #000;
    font-size: 14px;
}

.schedule-item:hover {
    background-color: #FFF8F8;
}

.departure-time {
    font-weight: 600;
}

.departure-date {
    font-size: 14px;
    color: #fff;
    margin-top: 4px;
}

.nation-road {
    font-size: 14px;
    color: #807E8E;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.original-price {
    text-decoration: line-through;
    color: #999;
    font-size: 14px;
    margin-top: 2px;
}

.book-btn, .full-btn, .blocked-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9em;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.book-btn {
    background-color: #ed0909;
    color: white;
}

.book-btn:hover {
    background-color: #ff7f7f;
    /* transform: translateY(-1px); */
    color: white;
}

.full-btn, .blocked-btn {
    background-color: #E0E0E0;
    color: #777;
}

.no-schedule {
    text-align: center;
    padding: 30px;
    background-color: #FFF8F8;
    border-radius: 8px;
    color: #D46A6A;
    border: 1px dashed #FFAAAA;
}

.mobile-schedule-cards{
    display: none;
}

/* Mobile Card Styles */
@media screen and (max-width: 768px) {
    .schedule-list table {
        display: none;
    }
    
    .mobile-schedule-cards {
        display: grid;
        gap: 16px;
    }
    
    .schedule-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(255,170,170,0.1);
        overflow: hidden;
        border: 1px solid #FFE0E0;
    }
    
    .card-header {
        background: linear-gradient(to right, #ef4444, #FFC4C4);
        color: #fff;
        padding: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .departure-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .departure-icon {
        background: rgba(255,255,255,0.3);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .departure-time {
        font-size: 1.2rem;
        font-weight: 700;
    }
    
    .price-tag {
        background: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 700;
        color: #FF5555;
        box-shadow: 0 2px 6px rgba(92,0,0,0.1);
    }
    
    .card-body {
        padding: 16px;
        display: grid;
        gap: 14px;
    }
    
    .info-row {
        display: flex;
        gap: 12px;
    }
    
    .info-label {
        min-width: 90px;
        font-weight: 600;
        color: #807E8E;
        font-size: 1.0rem;
    }
    
    .info-content {
        flex: 1;
        color: #333;
    }
    
    .point-list {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    
    .point-item {
        position: relative;
        padding-left: 18px;
    }
    
    .point-item:before {
        content: "";
        position: absolute;
        left: 6px;
        top: 8px;
        width: 6px;
        height: 6px;
        background: #FFAAAA;
        border-radius: 50%;
    }
    
    .card-footer {
        padding: 0 16px 16px;
    }
    
    .action-btn {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .return-header td {
        display: block;
        padding: 4px 0;
    }
    
    .return-header tr {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
}
</style>

<div class="schedule-container">
    <?php if($isReturn == 1): ?>
    <div class="return-header">
        <table>
            <tr>
                <td><?= TABLE_RETURN_DATE ?>:</td>
                <td><?= dateShort($date) ?></td>
                <td><?= REPORT_FROM ?>:</td>
                <td><?= $destFrom['TDestination']['name'] ?></td>
                <td><?= REPORT_TO ?>:</td>
                <td><?= $destTo['TDestination']['name'] ?></td>
            </tr>
        </table>
    </div>
    <?php endif; ?>
    
    <?php if(!empty($scheduleData)): ?>
    <!-- Desktop Table View -->
    <div class="schedule-list">
        <table>
            <thead>
                <tr>
                    <th>Departure</th>
                    <th>Description</th>
                    <th>Transport</th>
                    <th>Boarding</th>
                    <th>Drop Off</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($scheduleData as $item): ?>
                <tr class="schedule-item">
                    <td class="departure-time">
                        <i class="fa fa-clock-o"></i> <?= date("H:i", strtotime($item['departure_time'])) ?>
                        <div class="departure-date" style="color: #000 !important;"><?= dateShort($item['date']) ?></div>
                    </td>
                    <td class="description">
                        <?= $item['description'] ?>
                        <div class="nation-road">
                            <i class="fa fa-map-marker"></i> <?= $item['nation_road'] ?>
                        </div>
                    </td>
                    <td class="transport">
                        <div style="font-size: 14px;"><i class="fa fa-bus"></i> <?= $item['transport_name'] ?></div>
                        <div style="font-size: 14px;"><?= $item['journey_type'] ?></div>
                    </td>
                    <td class="boarding">
                        <?php foreach($item['boarding_points'] as $point): ?>
                        <div>- <?= $point['name'] ?></div>
                        <?php endforeach; ?>
                    </td>
                    <td class="dropoff">
                        <?php foreach($item['dropoff_points'] as $point): ?>
                        <div>- <?= $point['name'] ?></div>
                        <?php endforeach; ?>
                    </td>
                    <td class="price" style="font-size: 16px;">
                        <?= number_format($item['price'], 2) ?> <?= $item['currency_symbol'] ?>
                        <?php if($item['is_promo']): ?>
                        <div class="original-price">
                            <?= number_format($item['original_price'], 2) ?> <?= $item['currency_symbol'] ?>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <?php 
                        if(!$item['is_blocked'] && !$item['is_full']): 
                            if($editId != 0){
                                $btnBook = "btnEditOpen";
                            } else {
                                $btnBook = "btnTicketBooking";
                            }
                        ?>
                        <button class="book-btn <?php echo $btnBook; ?>" is-return="<?php echo $isReturn; ?>" j-id="<?php echo $item['id']; ?>" t-id="<?php echo $item['departure_time_id']; ?>" date="<?php echo $date; ?>" act="<?php echo $item['description'].' ('.dateShort($date).' '.date("h:i A", strtotime($item['departure_time'])).') - '.$item['transport_name']; ?>" 
                                data-journey="<?= $item['id'] ?>"
                                data-time="<?= $item['departure_time'] ?>">
                            <i class="fa fa-ticket"></i> Book
                        </button>
                        <?php elseif($item['is_full']): ?>
                        <button class="full-btn disabled">
                            <i class="fa fa-ban"></i> Full
                        </button>
                        <?php else: ?>
                        <button class="blocked-btn disabled">
                            <i class="fa fa-ban"></i> Blocked
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Mobile Card View -->
    <div class="mobile-schedule-cards">
        <?php foreach($scheduleData as $item): ?>
        <div class="schedule-card">
            <div class="card-header">
                <div class="departure-info">
                    <div class="departure-icon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <div>
                        <div class="departure-time"><?= date("H:i", strtotime($item['departure_time'])) ?></div>
                        <div class="departure-date"><?= dateShort($item['date']) ?></div>
                    </div>
                </div>
                <div class="price-tag">
                    <?= number_format($item['price'], 2) ?> <?= $item['currency_symbol'] ?>
                    <?php if($item['is_promo']): ?>
                    <div style="font-size:0.8em;text-decoration:line-through;">
                        <?= number_format($item['original_price'], 2) ?> <?= $item['currency_symbol'] ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card-body">
                <div class="info-row">
                    <div class="info-label">Route</div>
                    <div class="info-content">
                        <?= $item['description'] ?>
                        <div class="nation-road">
                            <i class="fa fa-map-marker"></i> <?= $item['nation_road'] ?>
                        </div>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Transport</div>
                    <div class="info-content">
                        <div><i class="fa fa-bus"></i> <?= $item['transport_name'] ?></div>
                        <div><?= $item['journey_type'] ?></div>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Boarding</div>
                    <div class="info-content point-list">
                        <?php foreach($item['boarding_points'] as $point): ?>
                        <div class="point-item"><?= $point['name'] ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Drop Off</div>
                    <div class="info-content point-list">
                        <?php foreach($item['dropoff_points'] as $point): ?>
                        <div class="point-item"><?= $point['name'] ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <?php 
                if(!$item['is_blocked'] && !$item['is_full']): 
                    if($editId != 0){
                        $btnBook = "btnEditOpen";
                    } else {
                        $btnBook = "btnTicketBooking";
                    }
                ?>
                <button class="action-btn book-btn <?php echo $btnBook; ?>" 
                        data-journey="<?= $item['id'] ?>"
                        data-time="<?= $item['departure_time'] ?>">
                    <i class="fa fa-ticket"></i> Book Now
                </button>
                <?php elseif($item['is_full']): ?>
                <button class="action-btn full-btn disabled">
                    <i class="fa fa-ban"></i> Fully Booked
                </button>
                <?php else: ?>
                <button class="action-btn blocked-btn disabled">
                    <i class="fa fa-ban"></i> Not Available
                </button>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="no-schedule">
        <?= TABLE_NO_SCHEDULE ?>
    </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    $(document).ready(function(){
        <?php
        if($isReturn == 1){
        ?>
        // Reinitialize if needed later
        $("#journeyScheduleListReturn").niceScroll({cursorborder:"", cursorcolor:"#0063dc", boxzoom:false});
        <?php
        }
        ?>
        ticketEvent();
    });
});
</script>