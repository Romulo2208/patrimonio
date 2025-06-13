<?php
App::uses('AppModel', 'Model');
/**
 * Localizacao Model
 *
 * @property Patrimonio $Patrimonio
 */
class Localizacao extends AppModel {
    
    public $useTable = "localizacoes";


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Patrimonio' => array(
			'className' => 'Patrimonio',
			'foreignKey' => 'localizacao_id',
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
