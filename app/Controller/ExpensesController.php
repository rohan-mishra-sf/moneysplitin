<?php
App::uses('AppController', 'Controller');
App::import('Utility', 'Sanitize');
/**
 * Expenses Controller
 *
 */
class ExpensesController extends AppController {

    public $components = array('RequestHandler');
    public $uses = array(
        'Expense',
        'ExpenseContributor',
        'ExpenseSharer',
        'User'
    );
            
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
    
    public function index() {
        $this->autoRender = false;
        $this->layout = false;
        $expenses = $this->Expense->find('all');
        $expensesArray = $this->stripArrayIndex($expenses,'Expense');
        echo json_encode($expensesArray);
    }

    public function view($id) {
        $this->autoRender = false;
        $this->layout = false;
        $params = array( 'conditions' => array('Expense.events_id ' => $id) );
        $expense = $this->Expense->find('all', $params);
        //$expense = $this->Expense->find('first');
        if(!count($expense)){
            echo '0'; exit;
        }
        echo json_encode($expense);
    }

    public function add() {
        $this->autoRender = false;
        $this->layout = false;
        $message = array();
        $message['success'] = "false";        
        $expenseArray = array();
        $expenseArray['title'] = $this->request->data['title'];
        $expenseArray['amount'] = $this->request->data['amount'];
        $expenseArray['events_id'] = $this->request->data['events_id'];
        $expenseArray['created_at'] = $this->request->data['created_at'];
        $this->Expense->create();
        if ($result = $this->Expense->save($this->request->data)) {
            $contributorArray = array();
            $contributorArray['amount'] = $this->request->data['amount'];
            $contributorArray['created_at'] = $this->request->data['created_at'];            
            $contributorArray['expenses_id'] = $result['Expense']['id'];
            $contributorArray['users_id'] = $this->request->data['users_id'];
            if ($this->ExpenseContributor->save($contributorArray)) {
                $sharers = count($this->request->data['sharers']);
                $sharedAmount = $this->request->data['amount'] / $sharers;                
                foreach($this->request->data['sharers'] as $key => $val){
                    $sharersArray = array();
                    $sharersArray['amount'] = $sharedAmount;
                    $sharersArray['created_at'] = $this->request->data['created_at'];            
                    $sharersArray['expenses_id'] = $result['Expense']['id'];
                    $sharersArray['users_id'] = $val['users_id']; 
                    $this->ExpenseSharer->create();
                    if($this->ExpenseSharer->save($sharersArray)){                        
                        $message['success'] = 'true';
                    } else {
                        $message['success'] = 'false';
                    }
                }
            }
        }
        echo json_encode($message);
    }
    
    public function delete($id) {
        $this->autoRender = false;
        $this->layout = false;
        $message = array();
        $message['success'] = "false";
        if ($this->Expense->delete($id)) {
            $message['success'] = 'true';
        }
        echo json_encode($message);
    }

}
