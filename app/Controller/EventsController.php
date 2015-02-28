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
        $events = $this->Event->getUserParticipatingEvents($this->loggedinUser);
        $eventsArray = $this->stripArrayIndex($events,'event');
        echo json_encode($eventsArray);
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

}
