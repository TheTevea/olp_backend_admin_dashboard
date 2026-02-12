<?php

class OnlineOrdersController extends AppController {

    var $uses = 'OnlineOrders';
    var $components = array('Helper');

    function index(){
        $this->layout = 'ajax';  
    }

    function ajax($type = 'all', $status = 'all', $date = 'all', $payment = 'all'){
        $this->layout = 'ajax';
        $this->set(compact('type', 'status', 'date', 'payment'));
    }

    function viewApiResponse($id){
        $this->layout = 'ajax';  
        if(empty($id)){
            echo "Invalid Id";
            exit;
        }
        $this->set(compact('id'));
    }
}

?>