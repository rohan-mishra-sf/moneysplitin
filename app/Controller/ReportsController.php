<?php
App::uses('AppController', 'Controller');
App::import('Utility', 'Sanitize');
/**
 * Events Controller
 *
 */
class ReportsController extends AppController {

    public function __construct($request = null, $response = null) {
        $sessionCode = $request->header('sessioncode');        
        if(!isset($sessionCode) || $sessionCode == ''){
            $message = array();
            $message['success'] = "false";
            $message['message'] = "not logged in";
            echo json_encode($message);exit;
        } else {
            $User = $this->User->findBySessionCodeId($sessionCode);
            $userId = $User[0]['users']['id'];
            if($userId == ''){
                $message = array();
                $message['success'] = "false";
                $message['message'] = "not logged in";
                echo json_encode($message);exit;
            } else {
                $this->loggedinUser = $userId;
            }
        }
        parent::__construct($request, $response);
    }

    
    public $components = array('RequestHandler');
    public $uses = array(
        'Event',
        'User',
        'Expense'
    );
            
    public function index() {
        $this->autoRender = false;
        $this->layout = false;                
        /*$userEvents = $this->Event->getUserEvents($this->loggedinUser);
        $userEventsArray = $this->stripArrayIndex($userEvents,'events_has_users');
        $eventsArray = array();
        foreach($userEventsArray as $key => $val){
            $eventsArray[] = $val['events_id'];
        }
        $expenseContributionsArray = array();
        $expenseSharesArray = array();
        foreach($eventsArray as $k => $v){
            $expenseContributionsArray[$v] = $this->Event->getUserEventsContributions($this->loggedinUser,$v);
            foreach
        }
        foreach($eventsArray as $k => $v){
            $expenseSharesArray[$v] = $this->Event->getUserEventsShares($this->loggedinUser,$v);
        }
        echo '<pre>'; 
        print_r($eventsArray);
        print_r($expenseContributionsArray);
        print_r($expenseSharesArray);
        die;*/
        $this->response->header('Content-Type: application/json');
        $userTotalContributionsArray = $this->Event->getUserTotalContributions($this->loggedinUser);
        $userTotalSharesArray = $this->Event->getUserTotalSharers($this->loggedinUser);
        $userTotalContributions = $userTotalContributionsArray[0][0]['sum(amount)'];
        $userTotalShares = $userTotalSharesArray[0][0]['sum(amount)'];
        $result = array();
        $result['paid'] = $userTotalContributions;
        $result['share'] = $userTotalShares;
        echo json_encode($result);
    }

    public function view($id) {
        $this->autoRender = false;
        $this->layout = false;
        $eventExpenses = $this->Event->geteventExpenses($id);
        $eventExpensesArray['amount'] = $eventExpenses[0][0]['sum(amount)'];
        echo json_encode($eventExpensesArray);
    }

    public function add() {   
        $this->autoRender = false;
        $this->layout = false;
        $message = array();
        $message['success'] = "false";        
        $this->Event->create();
        if ($this->Event->save($this->request->data)) {
            $message['success'] = 'true';
        }        
        echo json_encode($message);
    }

    
    public function edit($id) {
        $this->autoRender = false;
        $this->layout = false;
        $message = array();
        $message['success'] = "false";
        $this->Event->id = $id;
        if ($this->Event->save($this->request->data)) {
            $message['success'] = 'true';
        }
        echo json_encode($message);
    }

    public function delete($id) {
        $this->autoRender = false;
        $this->layout = false;
        $message = array();
        $message['success'] = "false";
        if ($this->Event->delete($id)) {
            $message['success'] = 'true';
        }
        echo json_encode($message);
    }
    
    public function getReports(){
        $this->autoRender = false;
        $this->layout = false;
        $message = array();
        $message['success'] = "false";
        
    }

}
