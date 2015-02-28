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
        'ExpenseSharer'
    );
            
    public function index() {
        $this->autoRender = false;
        $this->layout = false;
        $expenses = $this->Expense->find('all');
        //echo '<pre>'; print_r($expenses); die;
        echo json_encode($expenses);
    }

    public function view($id) {
        $this->autoRender = false;
        $this->layout = false;
        $expense = $this->Expense->findById($id);
        //echo '<pre>'; print_r($expense); die;
        echo json_encode($expense);
    }

    public function add() {   
        //echo '<pre>';        print_r($this->request->data); die;
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

    
    public function edit($id) {
        $this->autoRender = false;
        $this->layout = false;
        $message = array();
        $message['success'] = "false";
        $this->Expense->id = $id;
        if ($this->Expense->save($this->request->data)) {
            $message['success'] = 'true';
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
