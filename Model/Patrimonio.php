<?php
App::uses('AppModel', 'Model');
App::import('Model','DBMongo');
/**
 * Patrimonio Model
 *
 * @property Localizacao $Localizacao
 * @property Fornecedor $Fornecedor
 * @property Conservacao $Conservacao
 * @property Item $Item
 */
class Patrimonio extends AppModel {
    
    public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {
            if(isset($results[$key]['Patrimonio'])) {
                foreach ($results[$key]['Patrimonio'] as $key2 => $value2) {
                    $results[$key]['Patrimonio'][$key2] = mb_convert_case($results[$key]['Patrimonio'][$key2], MB_CASE_UPPER, "ISO-8859-1");
                }
                
                if(isset($results[$key]['Patrimonio']['valor'])) $results[$key]['Patrimonio']['valor'] = $this->moeda($val['Patrimonio']['valor'], false);
                if(isset($results[$key]['Patrimonio']['data_registro'])) $results[$key]['Patrimonio']['data_registro'] = $this->date($val['Patrimonio']['data_registro'], false);
                if(isset($results[$key]['Patrimonio']['data_baixa'])) $results[$key]['Patrimonio']['data_baixa'] = $this->date($val['Patrimonio']['data_baixa'], false);
            }
        }
        return $results;
    }
    
    public function beforeSave($options = array()) {
        parent::beforeSave($options);
        if(isset($this->data['Patrimonio'])) {
            if(isset($this->data['Patrimonio']['valor'])) $this->data['Patrimonio']['valor'] = $this->moeda($this->data['Patrimonio']['valor']);
            if(isset($this->data['Patrimonio']['data_registro'])) $this->data['Patrimonio']['data_registro'] = $this->date($this->data['Patrimonio']['data_registro']);
            if(isset($this->data['Patrimonio']['data_baixa'])) $this->data['Patrimonio']['data_baixa'] = $this->date($this->data['Patrimonio']['data_baixa']);
        }
        return true;
    }
    public function verificaData($data){
      if(empty($data)){
          return true;
      }
      
        $data = explode( '/', $data);
        return checkdate($data[1], $data[0], $data[2]);
    }
           
    public function afterSave($created, $options = Array()) {
        $mongo = new DBMongo();
        $array = $this->data;
        $array[key($array)]['auth'] = CakeSession::read('Auth.User');
        $array[key($array)]['log_type'] = 'add/edit';
        $mongo->add($array);
    }
    
    public function beforeDelete($cascade = true) {
        $mongo = new DBMongo();
        $array = $this->find('first', array('conditions' => array('id' => $this->id)));
        $array[key($array)]['auth'] = CakeSession::read('Auth.User');
        $array[key($array)]['log_type'] = 'delete';
        $mongo->add($array);
    }
    
    public function moeda($valor, $bol = true) {
        if(!$valor){
            return;
        }
        
        if ($bol) {
            return str_replace(',', '.', str_replace('.', '', $valor));
        } else {
            return number_format($valor, 2, ',', '.');
        }
    }
    
    public function date($date, $bol = true) {
        if(!$date){
            return;
        }
        
        if ($bol) {
            return implode('-', array_reverse(explode('/', $date)));
        } else {
            return implode('/', array_reverse(explode('-', $date)));
        }
    }

	public $belongsTo = array(
		'Localizacao' => array(
			'className' => 'Localizacao',
			'foreignKey' => 'localizacao_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Fornecedor' => array(
			'className' => 'Fornecedor',
			'foreignKey' => 'fornecedor_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Conservacao' => array(
			'className' => 'Conservacao',
			'foreignKey' => 'conservacao_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}
