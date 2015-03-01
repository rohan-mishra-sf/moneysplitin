<?php
App::uses('AppController', 'Controller');
App::import('Utility', 'Sanitize');
/**
 * Events Controller
 *
 */
class EventsController extends AppController {

    public $components = array('RequestHandler');
    
    public $uses = array(
        'Event',
        'User',
        'EventsHasUser'
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
        $events = $this->Event->getUserParticipatingEvents($this->loggedinUser);
        $eventsArray = $this->stripArrayIndex($events,'event');
        $result = new Object();
        $result->count = count($eventsArray);
        $result->data = $eventsArray;
        echo json_encode($result);
    }

    public function view($id) {
        $this->autoRender = false;
        $this->layout = false;
        $event = $this->Event->findById($id);
        echo json_encode($event['Event']);
    }

    public function add() {
        $this->autoRender = false;
        $this->layout = false;
        $message = array();
        $message['success'] = "false";        
        $this->request->data['is_settled'] = 0;
        $this->request->data['users_id'] = $this->loggedinUser;
        $this->request->data['created_at'] = date('Y-m-d h:i:s');
        $this->Event->create();
        if ($result = $this->Event->save($this->request->data)) { 
            $saveDataArray = array();
            $userId = $result['Event']['users_id'];
            $saveDataArray['users_id'] = $userId;
            $saveDataArray['events_id'] = $result['Event']['id'];
            //print_r($saveDataArray); die;
            $this->EventsHasUser->create();
            if ($this->EventsHasUser->save($saveDataArray)) {
                $result = array();
                $result['event_id'] = $result['Event']['id'];
                echo json_encode($result); die;
            }
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

}
