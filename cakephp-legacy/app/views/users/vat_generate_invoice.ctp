<?php

$dateFrom = '2025-02-01';
$dateTo   = '2025-02-28';
// Reset VAT CODE
mysql_query("UPDATE t_tickets SET vat_code = '', is_vat = 0 WHERE is_vat = 1 AND date >= '".$dateFrom."' AND date <= '".$dateTo."' AND offline_project_id = 1;");
// Generate Invoice VAT BUS
$totalAmount = 0;
$totalByDay  = (223500 / 28); // 223,451.80
$dateCount   = "";
$sqlInv = mysql_query("SELECT * FROM t_tickets WHERE date >= '".$dateFrom."' AND date <= '".$dateTo."' AND offline_project_id = 1 AND company_id != 6 AND company_id != 7 AND company_id != 12 AND company_id != 13 AND company_id != 14 AND company_id != 17 AND is_open_date = 0 AND `type` = 1 AND status = 2 ORDER BY created");
while($rowInv = mysql_fetch_array($sqlInv)){
    $checkGenerate = true;
    if($dateCount != $rowInv['date']){
        $dateCount    = $rowInv['date'];
        $totalAmount  = $rowInv['total_amount'] + $rowInv['total_vat'];
    } else {
        $totalAmount += $rowInv['total_amount'] + $rowInv['total_vat'];
    }
    if($totalAmount > $totalByDay){
        $totalAmount  -= $rowInv['total_amount'] + $rowInv['total_vat'];
        $checkGenerate = false;
    }
    if($checkGenerate == true){
        $ticketVatCode = date("y")."-";
        // $ticketVatCode = "24-";
        mysql_query("INSERT INTO `ticket_vat_codes` (`offline_project_id`, `code`) VALUES (1, '".$ticketVatCode."');");
        $ticketCodeId = mysql_insert_id();
        $sqlCount = mysql_query("SELECT COUNT(id) FROM ticket_vat_codes WHERE code LIKE '".$ticketVatCode."%' AND offline_project_id = 1 AND id < ".$ticketCodeId.";");
        $rowCount = mysql_fetch_array($sqlCount);
        $modCode  = $ticketVatCode.str_pad(($rowCount[0] + 1),6,"0",STR_PAD_LEFT);
        mysql_query("UPDATE t_tickets SET vat_code = '".$modCode."', is_vat = 1 WHERE id = ".$rowInv['id']);
    }
}
// $invCount    = 0;
// $sqlInv = mysql_query("SELECT * FROM t_tickets WHERE date >= '".$dateFrom."' AND date <= '".$dateTo."' AND offline_project_id = 1 AND company_id != 6 AND company_id != 7 AND company_id != 12 AND is_open_date = 0 AND `type` = 1 AND status = 2 ORDER BY created");
// while($rowInv = mysql_fetch_array($sqlInv)){
//     if($invCount >= 7 && $invCount <= 11){
//         $ticketVatCode = date("y")."-";
//         mysql_query("INSERT INTO `ticket_vat_codes` (`offline_project_id`, `code`) VALUES (1, '".$ticketVatCode."');");
//         $ticketCodeId = mysql_insert_id();
//         $sqlCount = mysql_query("SELECT COUNT(id) FROM ticket_vat_codes WHERE code LIKE '".$ticketVatCode."%' AND offline_project_id = 1 AND id < ".$ticketCodeId.";");
//         $rowCount = mysql_fetch_array($sqlCount);
//         $modCode  = $ticketVatCode.str_pad(($rowCount[0] + 1),6,"0",STR_PAD_LEFT);
//         mysql_query("UPDATE t_tickets SET vat_code = '".$modCode."', is_vat = 1 WHERE id = ".$rowInv['id']);
//     }
//     if($invCount == 15){ // Reset Count
//         $invCount = 0;
//     } else {
//         $invCount++;
//     }
// }
// Generate Invoice VAT Air Bus (Web, App, Terminal, Mini app)
$totalAmount = 0;
$totalByDay  = (60850 / 28); // 60723.60
$dateCount   = "";
$sqlInv = mysql_query("SELECT * FROM t_tickets WHERE date >= '".$dateFrom."' AND date <= '".$dateTo."' AND offline_project_id = 1 AND company_id IN (7, 12, 13, 14) AND is_open_date = 0 AND (t_tickets.type = 5 OR t_tickets.type = 10 OR t_tickets.type = 11 OR (t_tickets.type = 2 AND t_tickets.api_bank_ref != '')) AND status = 2 AND ((t_tickets.t_agent_id IS NULL AND t_tickets.terminal_id IS NULL) OR (t_tickets.t_agent_id = 55) OR (t_tickets.t_agent_id = 106) AND (t_tickets.terminal_id IS NOT NULL)) ORDER BY created");
while($rowInv = mysql_fetch_array($sqlInv)){
    $checkGenerate = true;
    if($dateCount != $rowInv['date']){
        $dateCount    = $rowInv['date'];
        $totalAmount  = $rowInv['total_amount'] + $rowInv['total_vat'];
    } else {
        $totalAmount += $rowInv['total_amount'] + $rowInv['total_vat'];
    }
    if($totalAmount > $totalByDay){
        $totalAmount  -= $rowInv['total_amount'] + $rowInv['total_vat'];
        $checkGenerate = false;
    }
    if($checkGenerate == true){
        $ticketVatCode = date("y")."A-";
        // $ticketVatCode = "24A-";
        mysql_query("INSERT INTO `ticket_vat_codes` (`offline_project_id`, `code`) VALUES (1, '".$ticketVatCode."');");
        $ticketCodeId = mysql_insert_id();
        $sqlCount = mysql_query("SELECT COUNT(id) FROM ticket_vat_codes WHERE code LIKE '".$ticketVatCode."%' AND offline_project_id = 1 AND id < ".$ticketCodeId.";");
        $rowCount = mysql_fetch_array($sqlCount);
        $modCode  = $ticketVatCode.str_pad(($rowCount[0] + 1),6,"0",STR_PAD_LEFT);
        mysql_query("UPDATE t_tickets SET vat_code = '".$modCode."', is_vat = 1 WHERE id = ".$rowInv['id']);
    }
}

// $invCount = 0;
// $sqlInv = mysql_query("SELECT * FROM t_tickets WHERE date >= '".$dateFrom."' AND date <= '".$dateTo."' AND offline_project_id = 1 AND company_id IN (7,12) AND is_open_date = 0 AND `type` = 1 AND status = 2 ORDER BY created");
// while($rowInv = mysql_fetch_array($sqlInv)){
//     if($invCount >= 7 && $invCount <= 10){
//         $ticketVatCode = date("y")."A-";
//         mysql_query("INSERT INTO `ticket_vat_codes` (`offline_project_id`, `code`) VALUES (1, '".$ticketVatCode."');");
//         $ticketCodeId = mysql_insert_id();
//         $sqlCount = mysql_query("SELECT COUNT(id) FROM ticket_vat_codes WHERE code LIKE '".$ticketVatCode."%' AND offline_project_id = 1 AND id < ".$ticketCodeId.";");
//         $rowCount = mysql_fetch_array($sqlCount);
//         $modCode  = $ticketVatCode.str_pad(($rowCount[0] + 1),6,"0",STR_PAD_LEFT);
//         mysql_query("UPDATE t_tickets SET vat_code = '".$modCode."', is_vat = 1 WHERE id = ".$rowInv['id']);
//     }
//     if($invCount == 9){ // Reset Count
//         $invCount = 0;
//     } else {
//         $invCount++;
//     }
// }
echo "Done Generate.";