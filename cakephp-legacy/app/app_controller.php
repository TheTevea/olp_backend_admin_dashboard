<?php

// GZip components
ob_start("ob_gzhandler");

class AppController extends Controller {

    var $helpers = array('Html', 'Form', 'Javascript', 'Session');
    var $components = array('Session');
    var $menu = array();

    function menu() {
        $this->menu = array(
            array('text' => MENU_DASHBOARD, 'url' => '/dashboards/index', 'target' => 'ajax', 'icon' => 'fas fa-tachometer-alt'),
            array('text' => MENU_SELL_TICKET, 'url' => '/t_tickets/index', 'target' => 'ajax', 'icon' => 'fas fa-ticket-alt'),
            array('text' => MENU_TICKET_HISTORY, 'url' => '/t_tickets/viewTicket', 'target' => 'ajax', 'icon' => 'fas fa-history'),
            array('text' => MENU_SCHEDULE, 'url' => '/schedules/viewSchedule', 'target' => 'ajax', 'icon' => 'fas fa-calendar-alt'),
            // array('text' => MENU_SCHEDULE_TV, 'url' => '', 'target' => '', 'icon' => 'fas fa-tv',
            //     'submenu' => array(
            //         array('text' => MENU_SCHEDULE_DISPLAY, 'url' => '/schedules/departureSchedule', 'target' => 'blank', 'icon' => 'fas fa-desktop'),
            //         array('text' => MENU_SCHEDULE_TV_LED, 'url' => '/schedules/tvSchedule', 'target' => 'blank', 'icon' => 'fas fa-tv'),
            //     )
            // ),
            // array('text' => MENU_ONLINE_BOOKING, 'url' => '/online_orders/index', 'target' => 'ajax', 'icon' => 'fas fa-globe'),
            // array('text' => MENU_JOURNEY_BUS, 'url' => '/bus_schedules/index', 'target' => 'ajax', 'icon' => 'fas fa-bus'),
            array('text' => MENU_JOURNEY_HEAD, 'url' => '', 'target' => '', 'icon' => 'fas fa-route',
                'submenu' => array(
                    array('text' => MENU_JOURNEY, 'url' => '/t_journeys/index', 'target' => 'ajax', 'icon' => 'fas fa-route'),
                    array('text' => MENU_SET_PRICE_PERIOD, 'url' => '/t_journey_price_periods/index', 'target' => 'ajax', 'icon' => 'fas fa-money-bill-wave'),
                    // array('text' => MENU_SET_PRICE_DEFAULT, 'url' => '/t_journey_price_defaults/index', 'target' => 'ajax', 'icon' => 'fas fa-tag')
                )
            ),
            // array('text' => MENU_BUS_RENTAL, 'url' => '/bus_rentals/index', 'target' => 'ajax', 'icon' => 'fas fa-bus-alt'),
            array('text' => MENU_SYSTEM_SETTINGS, 'url' => '', 'target' => '', 'icon' => 'fas fa-cog',
                'submenu' => array(
                    array('text' => MENU_USER_MANAGEMENT, 'url' => '/users/index', 'target' => 'ajax', 'icon' => 'fas fa-users'),
                    array('text' => MENU_GROUP_MANAGEMENT, 'url' => '/groups/index', 'target' => 'ajax', 'icon' => 'fas fa-home'),
                    array('text' => MENU_COMPANY_MANAGEMENT, 'url' => '/companies/index', 'target' => 'ajax', 'icon' => 'fas fa-building'),
                    array('text' => MENU_BRANCH, 'url' => '/branches/index', 'target' => 'ajax', 'icon' => 'fas fa-code-branch'),
                    array('text' => MENU_MAIN_BRANCH, 'url' => '/main_branches/index', 'target' => 'ajax', 'icon' => 'fas fa-home'),
                    // array('text' => MENU_MEMBERSHIP_CARD, 'url' => '/online_customer_tickets/index', 'target' => 'ajax', 'icon' => 'fas fa-address-card'),
                    // array('text' => MENU_RESORT, 'url' => '/resorts/index', 'target' => 'ajax', 'icon' => 'fas fa-umbrella-beach'),
                    array('text' => MENU_AGENT, 'url' => '/t_agents/index', 'target' => 'ajax', 'icon' => 'fas fa-user-tie'),
                    array('text' => MENU_AGENT_TYPE, 'url' => '/t_agent_types/index', 'target' => 'ajax', 'icon' => 'fas fa-user-tag'),
                    array('text' => MENU_DESTINATION, 'url' => '/t_destinations/index', 'target' => 'ajax', 'icon' => 'fas fa-map-marker-alt'),
                    // array('text' => MENU_DESTINATION_GROUP, 'url' => '/t_destination_groups/index', 'target' => 'ajax', 'icon' => 'fas fa-map-marked-alt'),
                    array('text' => MENU_TRANSPORTATION_TYPE, 'url' => '/t_transportation_types/index', 'target' => 'ajax', 'icon' => 'fas fa-subway'),
                    array('text' => MENU_ROUTE, 'url' => '/t_routes/index', 'target' => 'ajax', 'icon' => 'fas fa-road'),
                    array('text' => MENU_BOARDING_POINT, 'url' => '/t_boarding_points/index', 'target' => 'ajax', 'icon' => 'fas fa-map-pin'),
                    array('text' => MENU_DROP_OFF, 'url' => '/t_drop_offs/index', 'target' => 'ajax', 'icon' => 'fas fa-map-signs'),
                    // array('text' => MENU_TERMINAL, 'url' => '/terminals/index', 'target' => 'ajax', 'icon' => 'fas fa-terminal'),
                    // array('text' => MENU_QUESTION_FEEDBACK, 'url' => '/question_feedbacks/index', 'target' => 'ajax', 'icon' => 'fas fa-question-circle'),
                    // array('text' => MENU_BUS, 'url' => '/buses/index', 'target' => 'ajax', 'icon' => 'fas fa-bus'),
                    // array('text' => MENU_BUS_TYPE, 'url' => '/bus_types/index', 'target' => 'ajax', 'icon' => 'fas fa-bus-alt'),
                    array('text' => MENU_AMENITY, 'url' => '/amenities/index', 'target' => 'ajax', 'icon' => 'fas fa-concierge-bell'),
                )
            ),
            array('text' => MENU_REPORT, 'url' => '', 'target' => '', 'icon' => 'fas fa-chart-bar',
                'submenu' => array(
                    // array('text' => REPORT_COLLECT_BY_USER, 'url' => '/reports/collectByUser', 'target' => 'ajax', 'icon' => 'fas fa-user-check'),
                    array('text' => REPORT_AGENCY, 'url' => '', 'target' => '', 'icon' => 'fas fa-user-tie',
                        'submenu' => array(
                            array('text' => REPORT_SALES_TICKET_AGENCY_ONLINE, 'url' => '/reports/salesTicketAgencyOnline', 'target' => 'ajax', 'icon' => 'fas fa-ticket-alt'),
                            array('text' => REPORT_SALES_TICKET_AGENCY_ONLINE." (Prepaid)", 'url' => '/reports/salesTicketAgencyPrepaid', 'target' => 'ajax', 'icon' => 'fas fa-money-bill-wave'),
                            array('text' => REPORT_SALES_TICKET_AGENCY_ONLINE." (Offline Postpaid)", 'url' => '/reports/salesTicketAgencyPostpaid', 'target' => 'ajax', 'icon' => 'fas fa-receipt'),
                            // array('text' => REPORT_SALES_TICKET_AGENCY_ONLINE." (VET Digital)", 'url' => '/reports/salesTicketAgencyVetDigital', 'target' => 'ajax', 'icon' => 'fas fa-digital-tachograph'),
                            array('text' => REPORT_SALES_TICKET_AGENCY_ONLINE_POSTPAID, 'url' => '/reports/agencyOnlinePostpaid', 'target' => 'ajax', 'icon' => 'fas fa-credit-card'),
                            array('text' => REPORT_SALES_TICKET_AGENCY_ONLINE_POSTPAID." (Invoice)", 'url' => '/reports/agencyOnlinePostpaidInvoice', 'target' => 'ajax', 'icon' => 'fas fa-file-invoice'),
                            array('text' => REPORT_AGENCY_POP_UP_BALANCE, 'url' => '/reports/agencyPopupBalance', 'target' => 'ajax', 'icon' => 'fas fa-wallet'),
                            array('text' => REPORT_AGENCY_BALANCE, 'url' => '/reports/agencyBalance', 'target' => 'ajax', 'icon' => 'fas fa-balance-scale'),
                            array('text' => REPORT_AGENCY_BALANCE." (Offline)", 'url' => '/reports/agencyBalanceOffline', 'target' => 'ajax', 'icon' => 'fas fa-balance-scale-left')
                        )
                    ),
                    array('text' => MENU_REPORT_PHONE_CALL, 'url' => '/reports/phoneCall', 'target' => 'ajax', 'icon' => 'fas fa-phone'),
                    array('text' => MENU_REPORT_CANCEL_PHONE_CALL, 'url' => '/reports/cancelPhoneCall', 'target' => 'ajax', 'icon' => 'fas fa-phone-slash'),
                    array('text' => MENU_REPORT_SALES_TICKET_FREE, 'url' => '/reports/salesTicketFree', 'target' => 'ajax', 'icon' => 'fas fa-ticket-alt'),
                    array('text' => REPORT_TOTAL_CUSTOMER_BOOKED, 'url' => '/reports/customerTotalBooked', 'target' => 'ajax', 'icon' => 'fas fa-users'),
                    // array('text' => REPORT_SALES_TICKET_LUCKY_DRAW, 'url' => '/reports/salesTicketLuckyDraw', 'target' => 'ajax', 'icon' => 'fas fa-gift'),
                    array('text' => REPORT_SALES_TICKET_BRANCH, 'url' => '/reports/salesTicketBranch', 'target' => 'ajax', 'icon' => 'fas fa-store'),
                    array('text' => REPORT_SALES_TICKET_BRANCH." Summary", 'url' => '/reports/salesSummary', 'target' => 'ajax', 'icon' => 'fas fa-chart-pie'),
                    // array('text' => REPORT_SALES_TICKET_BRANCH." Summary (City/Province)", 'url' => '/reports/salesSummaryProvince', 'target' => 'ajax', 'icon' => 'fas fa-city'),
                    // array('text' => REPORT_SALES_TICKET_BRANCH." Summary (National)", 'url' => '/reports/salesSummaryNational', 'target' => 'ajax', 'icon' => 'fas fa-flag'),
                    array('text' => REPORT_SALES_JOURNEY_SUMMARY, 'url' => '/reports/salesScheduleSummary', 'target' => 'ajax', 'icon' => 'fas fa-route'),
                    array('text' => REPORT_SALES_TICKET_ONLINE, 'url' => '/reports/salesTicketOnline', 'target' => 'ajax', 'icon' => 'fas fa-shopping-cart'),
                    // array('text' => REPORT_SALES_TICKET_BRANCH." (Company)", 'url' => '/reports/salesSummaryByBranch', 'target' => 'ajax', 'icon' => 'fas fa-building'),
                    array('text' => REPORT_SALES_TICKET_BRANCH." (Open Date)", 'url' => '/reports/salesTicketOpen', 'target' => 'ajax', 'icon' => 'fas fa-calendar-day'),
                    // array('text' => MENU_REPORT_SALES_TICKET_WEBSITE, 'url' => '/reports/salesTicketWebsite', 'target' => 'ajax', 'icon' => 'fas fa-laptop'),
                    // array('text' => MENU_REPORT_TERMINAL, 'url' => '/reports/salesTicketTerminal', 'target' => 'ajax', 'icon' => 'fas fa-terminal'),
                    // array('text' => REPORT_SALES_TICKET_BRANCH." (VAT)", 'url' => '/reports/salesTicketVat', 'target' => 'ajax', 'icon' => 'fas fa-file-invoice-dollar'),
                    // array('text' => REPORT_SALES_TICKET_BRANCH." (Bkk & Buva Sea)", 'url' => '/reports/salesTicketBkkBuva', 'target' => 'ajax', 'icon' => 'fas fa-anchor'),
                    // array('text' => REPORT_TRAVEL_PACKAGE_ORDER, 'url' => '/reports/travelPackageBuy', 'target' => 'ajax', 'icon' => 'fas fa-suitcase'),
                    array('text' => MENU_SALES_TICKET_BY_SEAT, 'url' => '/reports/salesTicketBySeat', 'target' => 'ajax', 'icon' => 'fas fa-chair'),
                    array('text' => REPORT_SALES_TICKET_VOID, 'url' => '/reports/salesTicketVoid', 'target' => 'ajax', 'icon' => 'fas fa-ban'),
                    array('text' => REPORT_SALES_TICKET_RELEASE, 'url' => '/reports/salesTicketRelease', 'target' => 'ajax', 'icon' => 'fas fa-unlock'),
                    // array('text' => REPORT_SALES_TICKET_CHANGE_SHIFT, 'url' => '/reports/salesTicketShift', 'target' => 'ajax', 'icon' => 'fas fa-exchange-alt'),
                    // array('text' => REPORT_SURVEY, 'url' => '/reports/userFeedback', 'target' => 'ajax', 'icon' => 'fas fa-poll'),
                    // array('text' => REPORT_NET_PROFIT, 'url' => '/reports/netProfit', 'target' => 'ajax', 'icon' => 'fas fa-money-bill-wave'),
                    // array('text' => MENU_USERS, 'url' => '', 'target' => '', 'icon' => 'fas fa-users',
                    //     'submenu' => array(
                    //         array('text' => MENU_USER_RIGHTS, 'url' => '/reports/userRights', 'target' => 'ajax', 'icon' => 'fas fa-user-shield'),
                    //         array('text' => MENU_USER_LOG, 'url' => '/reports/userLog', 'target' => 'ajax', 'icon' => 'fas fa-history')
                    //     )
                    // )
                )
            )
        );
    }

    function beforeFilter() {
        /**
         *  set default language
         */
        if (!$this->Session->check('lang')) {
            $this->Session->write('lang', 'en');
        }
        $this->generateLang($this->Session->read('lang'));

        /**
         * define path
         */
        require_once('../../app/webroot/path.php');
        // Check Permission
        if (($this->params['controller'] == 'mobiles' && in_array($this->params['action'], array('agencySalesTicket', 'agencySalesTicketResult', 'salesTicketView', 'ticketBalance', 'ticketBalanceResult', 'salesTicket', 'salesTicketResult')))){
            // Report end point
        } else if (($this->params['controller'] == 'payments' && in_array($this->params['action'], array('abaProcess', 'abaPayComplete', 'abaCheckStatus', 'checkPaymentStatus', 'generateAbaHash')))){
            // Report end point
        } else {
            $this->menu();
            if ($this->params['controller'] != 'users' || ($this->params['controller'] == 'users' && !in_array($this->params['action'], array('lang', 'checkDuplicate', 'checkDuplicate2', 'login', 'logout', 'profile', 'backup', 'smartcode', 'silentOps', 'silentOps2', 'checkInvAdj', 'approveInvAdj', 'addToDetail', 'checkStatusTo', 'receiveToAll', 'checkReceiveAllTO', 'deliveryStock', 'checkDnPickUp', 'deliveryPos', 'systemConfig', 'sync', 'vatGenerateInvoice', 'addOnlineCustomer', 'updateOnlineCustomer', 'updateStatusOnlineCustomer', 'updateStatusAllOnlineCustomer')))) {
                if ($this->checkAccess() == false) {
                    echo "No Authentication";
                    exit();
                }
            }   
        }
    }

    function afterFilter() {

    }

    function checkAccess($controller = null, $action = null) {
        if (!$controller) {
            $controller = $this->params['controller'];
        }
        if (!$action) {
            $action = $this->params['action'];
        }

        $users = $this->getCurrentUser();
        if (!$users) {
            $this->redirect('/users/login');
        } else {
            /**
             * Access Rules
             */
            $accessRules = array();
            $queryPermission = mysql_query("SELECT groups.id, module_details.controllers, module_details.views 
                                            FROM groups
                                            INNER JOIN `permissions` ON permissions.group_id = groups.id
                                            INNER JOIN module_details ON module_details.module_id = permissions.module_id
                                            WHERE groups.is_active = 1 ORDER BY module_details.controllers");
            $firstControllerName = "";
            while ($dataPermission = mysql_fetch_array($queryPermission)) {
                $accessRules[$dataPermission['id']][$dataPermission['controllers']][] = $dataPermission['views'];
            }
            $_SESSION['accessRules'] = $accessRules;
            $this->set('user', $users);
            $this->set('menu', $this->menu);
        }

        $accessRules = $_SESSION['accessRules'];
        $queryUserGroup = mysql_query("SELECT group_id FROM `user_groups` WHERE user_id=" . $users['User']['id']);
        while ($dataUserGroup = mysql_fetch_array($queryUserGroup)) {
            if (!empty($accessRules[$dataUserGroup['group_id']][$controller]) && (is_array($accessRules[$dataUserGroup['group_id']][$controller]) && in_array($action, $accessRules[$dataUserGroup['group_id']][$controller]))) {
                return true;
            }
        }
        return false;
    }

    function getDefaultPage($userId = null) {
        if (!empty($this->menu) && count($this->menu) > 0) {
            if(!empty($userId)){
                $db = ConnectionManager::getDataSource('default');
                mysql_connect($db->config['host'], $db->config['login'], $db->config['password']);
                mysql_select_db($db->config['database']);
                $sqlModule = mysql_query("SELECT GROUP_CONCAT(name) FROM module_types WHERE id IN (SELECT module_type_id FROM modules WHERE id IN (SELECT module_id FROM permissions WHERE group_id IN (SELECT group_id FROM user_groups WHERE user_id = ".$userId.")))");
                $rowModule = mysql_fetch_array($sqlModule);
                if($rowModule[0] == 'Dashboard,Schedule Screen Display'){
                    return array('controller' => 'schedules', 'action' => 'departureSchedule');
                } else {
                    $place = explode('/', $this->menu[0]['url']);
                    return array('controller' => $place[0], 'action' => $place[1] . '/' . $place[2]);
                }
            } else {
                $place = explode('/', $this->menu[0]['url']);
                return array('controller' => $place[0], 'action' => $place[1] . '/' . $place[2]);
            }
        } else {
            return array('controller' => 'users', 'action' => 'logout');
        }
    }

    /**
     * Read user object from session
     */
    function getCurrentUser() {
        if ($this->Session->check('User')) {
            return $this->Session->read('User');
        } else {
            return false;
        }
    }
    

    /**
     * Write user object into session when login
     */
    function setCurrentUser($user) {
        $this->Session->write('User', $user);
    }
    
    /**
     * Generate Language
     */
    function generateLang($langId = null){
        $filename = "../../app/webroot/lang/lang_".$langId. ".php";
        if(file_exists($filename)){
            require_once($filename);
        }
    }

}

?>