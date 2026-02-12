<?php

class PaymentsController extends AppController {

    var $uses = 'Users';
    var $components = array('Helper', 'AutoId');

    function abaCheckStatus($transactionId = null){
        $this->layout = 'ajax';
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        header("Allow: POST, GET, OPTIONS, PUT, DELETE");
        if(empty($transactionId)){
            exit;
        }
        include('includes/PayWayApiCheckout.php');
        $response['status'] = 0;
        $sqlChk = mysql_query("SELECT * FROM online_orders WHERE code = '".$transactionId."' AND payment_method_id IN (5, 6, 7) LIMIT 1");
        if(mysql_num_rows($sqlChk)){
            $rowChk       = mysql_fetch_array($sqlChk);
            $apiKey       = ABA_PAYWAY_API_KEY;
            $merchant_id  = ABA_PAYWAY_MERCHANT_ID;
            $req_time   = time();
            $bodyReq    = $req_time.$merchant_id.$transactionId;
            $hash       = base64_encode(hash_hmac('sha512', $bodyReq, $apiKey, true));
            $postfields = array(
                'req_time' => $req_time,
                'merchant_id' => $merchant_id,
                'tran_id' => $transactionId,
                'hash' => $hash
            );
            $headers = array(
                'accept: */*',
                'Content-Type: multipart/form-data',
                'Referer: '.PAYMENT_URL_REF
            );
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, ABA_CHECK_TRANSACTION_API_URL);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $result      = curl_exec($curl); //Result Json
            $curl_errno  = curl_errno($curl);
            $curl_error  = curl_error($curl);
            curl_close ($curl);
            $response = array();
            if ($curl_errno > 0) {
                $response['status'] = 0;
            } else {
                $output = json_decode($result, true);
                if($output['status'] == 0 && $output['description'] == "approved"){
                    $response['status'] = 1;
                } else {
                    $response['status'] = 0;
                }
            }
        }
        echo json_encode($response);
        exit;
    }

    function abaProcess($transactionId = null, $token = null){
        $this->layout = 'ajax';
        $returnData['status'] = 0;
        $returnData['form'] = "";
        if(empty($transactionId) || empty($token)){
            echo json_encode($returnData);
            exit;
        }
        $sqlToken = mysql_query("SELECT * FROM payment_tokens WHERE token = '".$token."' LIMIT 1");
        if(mysql_num_rows($sqlToken)){
            // $this->set(compact('transactionId'));
            $sqlOrder = mysql_query("SELECT * FROM online_orders WHERE code = '".$transactionId."' AND status = 2 AND payment_method_id IN (5,6,7) LIMIT 1");
            if(mysql_num_rows($sqlOrder)){
                $rowOrder = mysql_fetch_array($sqlOrder);
                $dateCreated = strtotime($rowOrder['created'].' + 10 minute');
                $dateNow     = strtotime(date("Y-m-d H:i:s")); 
                if($dateCreated > $dateNow){
                    // Function
                    include('includes/PayWayApiCheckout.php');
                    $sqlTicket   = mysql_query("SELECT * FROM t_ticket_api_tmps WHERE online_order_id = ".$rowOrder['id']." LIMIT 1");
                    $rowTicket   = mysql_fetch_array($sqlTicket);
                    $req_time      = time();
                    $amount        = $rowOrder['total_amount'] + $rowOrder['total_vat'] + $rowOrder['lucky_draw_fee'] - $rowOrder['discount_amount'];
                    $apiKey        = ABA_PAYWAY_API_KEY;
                    $merchant_id   = ABA_PAYWAY_MERCHANT_ID;
                    $paymentOption = "";
                    $lifeTime      = 10; // 10 minute
                    $skipSuccessPage = 1;
                    if($rowOrder['payment_method_id'] == 5) {
                        $paymentOption = 'abapay_khqr';
                    } else if($rowOrder['payment_method_id'] == 6){
                        $paymentOption = 'cards';
                    } else if($rowOrder['payment_method_id'] == 7) {
                        $paymentOption = 'alipay';
                    }
                    // ABA payment success route to booking complete
                    $returnUrl = base64_encode(PAYMENT_URL."abaPayComplete/".$transactionId."/".$token);
                    // ABA route to payment success page
                    $continueSuccess = WEB_BUS_SUCCESS_PAGE."checkout/payment-success?transactionId=".$transactionId;
                    // Generate Hash
                    $hash = base64_encode(hash_hmac('sha512', $req_time .$merchant_id . $transactionId . $amount .$paymentOption. $returnUrl. $continueSuccess .$lifeTime .$skipSuccessPage, $apiKey, true));
                    $returnData['status'] = 1;
                    $returnData['form']  = '<form method="POST" target="aba_webservice" action="'.PayWayApiCheckout::getApiUrl().'" id="aba_merchant_request">';
                    $returnData['form'] .= '<input type="hidden" name="hash" value="'.$hash.'" id="hash"/>';
                    $returnData['form'] .= '<input type="hidden" name="tran_id" value="'.$transactionId.'" id="tran_id"/>';
                    $returnData['form'] .= '<input type="hidden" name="amount" value="'.$amount.'" id="amount"/>';
                    $returnData['form'] .= '<input type="hidden" name="req_time" value="'.$req_time.'"/>';
                    $returnData['form'] .= '<input type="hidden" name="merchant_id" value="'.$merchant_id.'"/>';
                    $returnData['form'] .= '<input type="hidden" name="payment_option" value="'.$paymentOption.'"/>';
                    $returnData['form'] .= '<input type="hidden" name="payment_gate" value="0"/>';
                    $returnData['form'] .= '<input type="hidden" name="lifetime" value="'.$lifeTime.'"/>';
                    $returnData['form'] .= '<input type="hidden" name="return_url" value="'.$returnUrl.'"/>';
                    $returnData['form'] .= '<input type="hidden" name="continue_success_url" value="'.$continueSuccess.'"/>';
                    $returnData['form'] .= '<input type="hidden" name="skip_success_page" value="'.$skipSuccessPage.'"/>';
                    $returnData['form'] .= '</form>';
                }
            }
            echo json_encode($returnData);
            exit;
        } else {
            echo json_encode($returnData);
            exit;
        }
    }

    function abaPayComplete($transactionId = null, $token = null){
        $this->layout = 'ajax';
        $response  = array();
        $response['transactionCode'] = $transactionId;
        $response['status'] = "0";
        if(empty($transactionId) || empty($token)){
            $response['error']  = "Invalid Data";
            echo json_encode($response);
            exit;
        }
        $sqlToken = mysql_query("SELECT * FROM payment_tokens WHERE token = '".$token."' LIMIT 1");
        if(mysql_num_rows($sqlToken)){
            $sqlChk = mysql_query("SELECT * FROM online_orders WHERE code = '".$transactionId."' AND status = 2 AND payment_method_id IN (5, 6, 7) LIMIT 1");
            if(mysql_num_rows($sqlChk)){
                // Process Complete
                include('includes/PayWayApiCheckout.php');
                $rowChk       = mysql_fetch_array($sqlChk);
                $apiKey       = ABA_PAYWAY_API_KEY;
                $merchant_id  = ABA_PAYWAY_MERCHANT_ID;
                $req_time   = time();
                $bodyReq    = $req_time.$merchant_id.$transactionId;
                $hash       = base64_encode(hash_hmac('sha512', $bodyReq, $apiKey, true));
                $postfields = array(
                    'req_time' => $req_time,
                    'merchant_id' => $merchant_id,
                    'tran_id' => $transactionId,
                    'hash' => $hash
                );
                $headers = array(
                    'accept: */*',
                    'Content-Type: multipart/form-data',
                    'Referer: '.PAYMENT_URL_REF
                );
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, ABA_CHECK_TRANSACTION_API_URL);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $result      = curl_exec($curl); //Result Json
                $curl_errno  = curl_errno($curl);
                $curl_error  = curl_error($curl);
                curl_close ($curl);
                if ($curl_errno > 0) {
                    $response['error']  = "Check Payment";
                    $response['status'] = 0;
                } else {
                    $output = json_decode($result, true);
                    if(isset($output['status']['code']) && $output['status']['code'] == '00' && isset($output['data']['payment_status']) && $output['data']['payment_status'] == 'APPROVED'){
                        // Delete Token
                        $rowToken = mysql_fetch_array($sqlToken);
                        mysql_query("DELETE FROM payment_tokens WHERE id = ".$rowToken['id']);
                        $sqlTmp = mysql_query("SELECT * FROM t_ticket_api_tmps WHERE online_order_id = ".$rowChk['id']." LIMIT 1");
                        if(mysql_num_rows($sqlTmp)){
                            // Update Order
                            mysql_query("UPDATE online_orders SET status = 4, modified = now() WHERE id = ".$rowChk['id'].";");
                            // Update Ticket Tmp
                            $sqlTicket = mysql_query("SELECT * FROM t_ticket_api_tmps WHERE online_order_id = ".$rowChk['id']);
                            while($rowTicket = mysql_fetch_array($sqlTicket)){
                                // Update Ticket Tmp
                                mysql_query("UPDATE t_ticket_api_tmps SET status = 2 WHERE id = ".$rowTicket['id'].";");
                                // Move Ticket Tmp to Ticket
                                mysql_query("INSERT INTO t_tickets (`sys_code`, `offline_project_id`, `online_order_id`, `payment_method_id`, `company_id`, `branch_id`, `date`, `t_agent_id`, `journey_date`, `journey_time`, `t_journey_id`, `t_journey_departure_id`, `t_destination_from_id`, `t_destination_to_id`, `t_boarding_point_id`, `t_drop_off_id`, `t_transportation_type_id`, `t_route_id`, `telephone`, `email`, `price`, `total_amount`, `discount_amount`, `total_vat`, `lucky_draw_fee`, `commission`, `commission_percent`, `balance`, `currency_center_id`, `note`, `total_seat`, `created`, `terminal_id`, `modified`, `price_type`, `type`, `status`, `agt_refer_code`) SELECT `sys_code`, `offline_project_id`, `online_order_id`, ".$rowChk['payment_method_id'].", `company_id`, `branch_id`, `date`, `t_agent_id`, `journey_date`, `journey_time`, `t_journey_id`, `t_journey_departure_id`, `t_destination_from_id`, `t_destination_to_id`, `t_boarding_point_id`, `t_drop_off_id`, `t_transportation_type_id`, `t_route_id`, `telephone`, `email`, `price`, `total_amount`, `discount_amount`, `total_vat`, `lucky_draw_fee`, `commission`, `commission_percent`, '0', `currency_center_id`, `note`, `total_seat`, `created`, `terminal_id`, `modified`, `price_type`, `type`, `status`, 'Website' FROM t_ticket_api_tmps WHERE id = ".$rowTicket['id'].";");
                                // Move Ticket Detail Tmp to Ticket Detail
                                mysql_query("INSERT INTO t_ticket_details (`sys_code`, `t_ticket_id`, `seat_number`, `label_number`, `gender`, `name`, `telephone`, `nationally`, `passport`, `dob`, `nationally_id`, `unit_price`, `discount`, `total_amount`) SELECT `sys_code`, (SELECT id FROM t_tickets WHERE sys_code = '".$rowTicket['sys_code']."' LIMIT 1), `seat_number`, `label_number`, `gender`, `name`, `telephone`, `nationally`, `passport`, `dob`, `nationally_id`, `unit_price`, `discount`, `total_amount` FROM t_ticket_detail_api_tmps WHERE t_ticket_api_tmp_id = (SELECT id FROM t_ticket_api_tmps WHERE id = ".$rowTicket['id']." LIMIT 1);"); 
                                mysql_query("UPDATE t_ticket_api_tmps SET status = -3 WHERE id = ".$rowTicket['id'].";");
                                // Update Seat Status
                                mysql_query("UPDATE t_seat_controls SET status = 2 WHERE t_ticket_api_tmp_id = ".$rowTicket['id'].";");
                            }
                            // Send Email
                            if(!empty($rowChk['email'])){
                                $this->Helper->ticketSendEmail($transactionId);
                            }
                            $response['status'] = "1";
                        }
                    } else {
                        $response['error'] = isset($output['status']['message']) ? $output['status']['message'] : "Payment Failed";
                    }
                }
            } else {
                $response['error']  = "Invalid Order ID";
            }
        } else {
            $response['error']  = "Invalid Token";
        }
        echo json_encode($response);
        exit;
    }

    function checkPaymentStatus($transactionId = null, $token = null){
        $this->layout = 'ajax';
        $result['status'] = 0;
        if(empty($transactionId) || empty($token)){
            echo json_encode($result);
            exit;
        }
        $sqlToken = mysql_query("SELECT * FROM payment_tokens WHERE token = '".$token."' LIMIT 1");
        if(mysql_num_rows($sqlToken)){
            $sqlChk = mysql_query("SELECT * FROM online_orders WHERE code = '".$transactionId."' AND status = 4 LIMIT 1");
            if(mysql_num_rows($sqlChk)){
                $result['status'] = 1;
            }
        }
        echo json_encode($result);
        exit;
    }

    function generateAbaHash(){
        $this->layout = 'ajax';
        $result['result'] = '';
        if(empty($_POST['key']) || empty($_POST['reqTime']) || empty($_POST['transactionId'])){
            echo json_encode($result);
            exit;
        }
        if($_POST['key'] == '23232987ihi884h3inou937ihble329jUydwj' && !empty($_POST['reqTime']) && !empty($_POST['transactionId'])){
            include('includes/PayWayApiCheckout.php');
            $apiKey        = ABA_PAYWAY_API_KEY;
            $merchantId    = ABA_PAYWAY_MERCHANT_ID;
            $reqTime       = $_POST['reqTime'];
            $transactionId = $_POST['transactionId'];
            $bodyReq       = $reqTime.$merchantId.$transactionId;
            $hash          = base64_encode(hash_hmac('sha512', $bodyReq, $apiKey, true));
            $result['result'] = $hash;
            echo json_encode($result);
        } else {
            echo json_encode($result);
        }
        exit;
    }

}

?>

