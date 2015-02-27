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
        'Event'
    );
            
    public function index() {
        $this->autoRender = false;
        $this->layout = false;
        $events = $this->Event->find('all');
        echo json_encode($events);
    }

    public function view($id) {
        $this->autoRender = false;
        $this->layout = false;
        $event = $this->Event->findById($id);
        echo json_encode($event);
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
