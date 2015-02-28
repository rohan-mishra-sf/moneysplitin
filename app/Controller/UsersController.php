<?php
App::uses('AppController', 'Controller');
App::import('Utility', 'Sanitize');
/**
 * Users Controller
 *
 */
class UsersController extends AppController {

    public $components = array('RequestHandler');
    public $uses = array(
        'User'
    );
            
    public function __construct($request = null, $response = null) {        
        $method = $request->method();    
        if($method != 'POST'){
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
        }
        parent::__construct($request, $response);
    }

    public function index() {
        $this->autoRender = false;
        $this->layout = false;
        $users = $this->User->find('all');        
        $usersArray = $this->stripArrayIndex($users,'User');
        echo json_encode($usersArray);
    }

    public function view($id) {
        $this->autoRender = false;
        $this->layout = false;
        $user = $this->User->findById($id);
        echo json_encode($user['User']);
    }

    public function add() {
        $this->autoRender = false;
        $this->layout = false;
        $message = array();
        $message['success'] = "false";
        $user = $this->User->find('first', array('conditions' => array('User.email' => $this->request->data['email'])));
        if(isset($user['User']['email']) && $user['User']['email'] != '' && $user['User']['email'] == $this->request->data['email']){
            $sessionCode = base64_encode(rand());
            $sessionArray = array();
            $sessionArray['id'] = $user['User']['id'];
            $sessionArray['session_token'] = $sessionCode;
            if ($this->User->save($sessionArray)) {
                echo $sessionCode;                
            } else {
                    echo json_encode($message);
            }
        } else {
            $this->User->create();
            $this->request->data['created_at'] = date('Y-m-d h:i:s');
            if ($result = $this->User->save($this->request->data)) {
                $user = $this->User->find('first', array('conditions' => array('User.email' => $this->request->data['email'])));
                if(isset($user['User']['email']) && $user['User']['email'] != '' && $user['User']['email'] == $this->request->data['email']){
                    $sessionCode = base64_encode(rand());
                    $sessionArray = array();
                    $sessionArray['id'] = $result['User']['id'];
                    $sessionArray['session_token'] = $sessionCode;
                    if ($this->User->save($sessionArray)) {
                        echo $sessionCode;                
                    } else {
                        echo json_encode($message);
                    }
                } else {
                    echo json_encode($message);
                }
            } else {
                echo json_encode($message);
            }
        }
    }

    
    public function edit($id) {
        $this->autoRender = false;
        $this->layout = false;
        $message = array();
        $message['success'] = "false";
        $this->User->id = $id;
        if ($this->User->save($this->request->data)) {
            $message['success'] = 'true';
        }
        echo json_encode($message);
    }

    public function delete($id) {
        $this->autoRender = false;
        $this->layout = false;
        $message = array();
        $message['success'] = "false";
        if ($this->User->delete($id)) {
            $message['success'] = 'true';
        }
        echo json_encode($message);
    }

}
