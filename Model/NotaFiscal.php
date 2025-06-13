<?php
App::uses('AppModel', 'Model');
App::import('Model','DBMongo');
/**
 * NotaFiscal Model
 *
 * @property Item $Item
 */
class NotaFiscal extends AppModel {
    
    public $useTable = "notas_fiscais";

    public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {
            if(isset($results[$key]['NotaFiscal'])) {
                if(isset($results[$key]['NotaFiscal']['data_emissao'])) $results[$key]['NotaFiscal']['data_emissao'] = $this->date($val['NotaFiscal']['data_emissao'], false);
                if(isset($results[$key]['NotaFiscal']['valor_nota'])) $results[$key]['NotaFiscal']['valor_nota'] = $this->moeda($val['NotaFiscal']['valor_nota'], false);
            }
        }
        return $results;
    }
    
    public function beforeSave($options = array()) {
        parent::beforeSave($options);
        if(isset($this->data['NotaFiscal'])) {
            if(isset($this->data['NotaFiscal']['data_emissao'])) $this->data['NotaFiscal']['data_emissao'] = $this->date($this->data['NotaFiscal']['data_emissao']);
            if(isset($this->data['NotaFiscal']['valor_nota'])) $this->data['NotaFiscal']['valor_nota'] = $this->moeda($this->data['NotaFiscal']['valor_nota']);
        }
        return true;
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
    
     public function verificaData($data){
      if(empty($data)){
          return false;
      }
      
        $data = explode( '/', $data);
        return checkdate($data[1], $data[0], $data[2]);
    }
}
