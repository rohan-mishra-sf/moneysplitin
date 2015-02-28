<?php
App::uses('AppModel', 'Model');
/**
 * Event Model
 *
 * @property Users $Users
 */
class Event extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'title' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'created_at' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'is_settled' => array(
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
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Users' => array(
			'className' => 'Users',
			'foreignKey' => 'users_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
        
        public function getUserParticipatingEvents($userId){
            $query="SELECT event.id,event.title, event.created_at, event.is_settled, event.users_id as ownerid  from events as event inner join events_has_users as events_has_users on events_has_users.events_id = event.id and events_has_users.users_id = $userId          "; 
            return $this->query($query);
	}
        
        public function getUsersReport($userId){
            $query="SELECT event.title, event.created_at, event.is_settled, event.users_id as ownerid  from events as event inner join events_has_users as events_has_users on events_has_users.events_id = event.id and events_has_users.users_id = $userId          "; 
            return $this->query($query);
	}
        
        public function getUserEvents($userId){
            $query="SELECT events_id from events_has_users where users_id = $userId "; 
            return $this->query($query);
	}
        
        public function getUserTotalContributions($userId){
            $query  = "SELECT sum(amount) FROM expense_contributors where expenses_id in (Select ex.id as expenses_id from expenses as ex left join events as e on ex.events_id=e.id ) and users_id=$userId ";            
            return $this->query($query);
	}
        
        public function getUserTotalSharers($userId){
            $query  = "SELECT sum(amount) FROM expense_sharers where expenses_id in (Select ex.id as expenses_id from expenses as ex left join events as e on ex.events_id=e.id ) and users_id=$userId ";            
            return $this->query($query);
	}

        public function geteventExpenses($eventId){
            $query  = "SELECT sum(amount) FROM expenses where events_id =$eventId ";                
            return $this->query($query);
        }
        
        public function getFriendContributions($eventId){
            $query  = "SELECT sum(amount) as amount, first_name, last_name,users.id FROM expense_contributors join users on expense_contributors.users_id = users.id  where expenses_id in (Select ex.id as expenses_id from expenses as ex left join events as e on ex.events_id=e.id where e.id = $eventId) GROUP BY users_id ";
            return $this->query($query);
        }
        
        public function getFriendShares($eventId){
            $query  = "SELECT sum(amount) as amount, first_name, last_name, users.id FROM expense_sharers join users on expense_sharers.users_id = users.id  where expenses_id in (Select ex.id as expenses_id from expenses as ex left join events as e on ex.events_id=e.id  where e.id = $eventId) GROUP BY users_id ";
            return $this->query($query);
        }
        
}
