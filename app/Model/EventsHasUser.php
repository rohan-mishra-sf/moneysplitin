<?php
App::uses('AppModel', 'Model');
/**
 * EventsHasUser Model
 *
 */
class EventsHasUser extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'events_id' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'users_id' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'id' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
        
        public function checkUserExists($params ){
            $eventid = $params['events_id'];
            $usersid = $params['users_id'];
            $query="SELECT id from events_has_users where events_id = $eventid and users_id = $usersid "; 
            return $this->query($query);            
        }
        
}
