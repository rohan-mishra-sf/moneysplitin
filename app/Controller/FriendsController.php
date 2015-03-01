<?php
App::uses('AppController', 'Controller');
App::import('Utility', 'Sanitize');
/**
 * Events Controller
 *
 */
class FriendsController extends AppController {

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
        'EventsHasUser',
        'Event',
        'User'
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
        $friends = $this->EventsHasUser->getEventFriends($id);
        foreach ($friends as $key => $val){
            $userContribution = $this->Event->getUserEventContributions($val['users']['id'],$id);
            $userShare = $this->Event->getUserEventShares($val['users']['id'],$id);
            $usersArray[$val['users']['id']]['id'] = $val['users']['id'];
            $usersArray[$val['users']['id']]['name'] = $val['users']['first_name'].' '.$val['users']['last_name'];
            $usersArray[$val['users']['id']]['email'] = $val['users']['email'];
            $usersArray[$val['users']['id']]['fb_id'] = $val['users']['fb_id'];
            $usersArray[$val['users']['id']]['amountdiff'] = $userContribution[0][0]['contri_amount'] - $userShare[0][0]['share_amount'];
        }
        $result = array();
        $result['count'] = count($usersArray);
        $result['data'] = $usersArray;
        echo json_encode($result);
    }

    public function add() {
        $this->autoRender = false;
        $this->layout = false;
        $message = array();
        $message['success'] = "false";       
        foreach($this->request->data['friends'] as $key => $val){
            $saveDataArray = array();
            $User = $this->User->findByFBid($val['email']);
            $userId = $User[0]['users']['id'];
            $saveDataArray['users_id'] = $userId;
            $saveDataArray['events_id'] = $val['events_id'];            
            $result =  $this->EventsHasUser->checkUserExists($saveDataArray);
            if(!count($result)){
                $this->EventsHasUser->create();
                if ($this->EventsHasUser->save($saveDataArray)) {
                    $message['success'] = 'true';
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
