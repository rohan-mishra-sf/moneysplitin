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
        $friendContributions = $this->Event->getFriendContributions($id);
        $friendShares = $this->Event->getFriendShares($id);
        $friendContributionsArray = array();
        $friendSharesArray = array();
        $usersArray = array();
        foreach($friendShares as $k => $v){
            $amountArray = $v[0];
            $userArray = $v['users'];
            $friendSharesArray[$v['users']['id']]['first_name'] = $userArray['first_name'];
            $friendSharesArray[$v['users']['id']]['last_name'] = $userArray['last_name'];
            $friendSharesArray[$v['users']['id']]['fb_id'] = $userArray['fb_id'];
            $friendSharesArray[$v['users']['id']]['amount'] = $amountArray['amount'];            
        }
        foreach($friendContributions as $k => $v){
            $amountArray = $v[0];
            $userArray = $v['users'];
            $friendContributionsArray[$v['users']['id']]['first_name'] = $userArray['first_name'];
            $friendContributionsArray[$v['users']['id']]['last_name'] = $userArray['last_name'];
            $friendContributionsArray[$v['users']['id']]['amount'] = $amountArray['amount'];            
        }
        foreach($friendSharesArray as $key => $val){
            $usersArray[$key]['name'] = $val['first_name'].' '.$val['last_name'];
            $usersArray[$key]['fb_id'] = $val['fb_id'];
            $usersArray[$key]['amount'] =  $friendContributionsArray[$key]['amount'] - $val['amount'];
        }        
        $result = array();
        $result['count'] = count($usersArray);
        $result['data'] = $usersArray;
        //echo '<pre>';     print_r($friendContributionsArray);   print_r($friendSharesArray);   print_r($usersArray); die;        
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
