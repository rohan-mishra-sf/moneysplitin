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
            
    public function index() {
        $this->autoRender = false;
        $this->layout = false;
        $users = $this->User->find('all');
        echo json_encode($users);
    }

    public function view($id) {
        $this->autoRender = false;
        $this->layout = false;
        $user = $this->User->findById($id);
        echo json_encode($user);
    }

    public function add() {   
        $this->autoRender = false;
        $this->layout = false;
        $message = array();
        $message['success'] = "false";
        $user = $this->User->find('first', array('conditions' => array('User.email' => $this->request->data['email'])));
        if(isset($user['User']['email']) && $user['User']['email'] != '' && $user['User']['email'] == $this->request->data['email']){
            echo $user['User']['id'];
        } else {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $user = $this->User->find('first', array('conditions' => array('User.email' => $this->request->data['email'])));
                if(isset($user['User']['email']) && $user['User']['email'] != '' && $user['User']['email'] == $this->request->data['email']){
                    echo $user['User']['id'];
                } else {
                    echo json_encode($message);
                }
            } else {
                $errors = $this->User->validationErrors;
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
