<?php
App::uses('AppModel', 'Model');
/**
 * Conservacao Model
 *
 * @property Patrimonio $Patrimonio
 */
class Conservacao extends AppModel {
    
    public $useTable = "conservacoes";


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Patrimonio' => array(
			'className' => 'Patrimonio',
			'foreignKey' => 'conservacao_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
