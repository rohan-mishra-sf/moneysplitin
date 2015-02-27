<?php
App::uses('AppController', 'Controller');
App::import('Utility', 'Sanitize');
/**
 * Events Controller
 *
 */
class FriendsController extends AppController {

    public $components = array('RequestHandler');
            
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
