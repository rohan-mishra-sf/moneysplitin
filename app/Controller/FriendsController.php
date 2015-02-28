<?php
App::uses('AppController', 'Controller');
App::import('Utility', 'Sanitize');
/**
 * Events Controller
 *
 */
class FriendsController extends AppController {

    public function __construct($request = null, $response = null) {
        $userId = $request->header('userid');        
        if(!isset($userId) || $userId == ''){
            $message = array();
            $message['success'] = "false";
            $message['message'] = "not logged in";
            echo json_encode($message);exit;
        } else {
            $this->loggedinUser = $userId;
        }
        parent::__construct($request, $response);
    }
    
    public $components = array('RequestHandler');
    public $uses = array(
        'EventsHasUser'
    );
    
    public function index() {
        $this->autoRender = false;
        $this->layout = false;        
        $friends = $this->findFaceBookFriends();
        echo json_encode($friends);
    }

    public function view($id) {
        $this->autoRender = false;
        $this->layout = false;
        $friends = $this->findFaceBookFriends($id);
        echo json_encode($friends);
    }

    public function add() {   
        $this->autoRender = false;
        $this->layout = false;
        $message = array();
        $message['success'] = "false";        
        $this->EventsHasUser->create();
        if ($this->EventsHasUser->save($this->request->data)) {
            $message['success'] = 'true';
        }        
        echo json_encode($message);
    }

    
    public function edit($id) {
        $this->autoRender = false;
        $this->layout = false;
        $message = array();
        $message['success'] = "false";
        echo json_encode($message);
    }

    public function delete($id) {
        $this->autoRender = false;
        $this->layout = false;
        $message = array();
        $message['success'] = "false";
        echo json_encode($message);
    }

}
