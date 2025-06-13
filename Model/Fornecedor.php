<?php
App::uses('AppModel', 'Model');
App::import('Model','DBMongo');
/**
 * Fornecedor Model
 *
 * @property Patrimonio $Patrimonio
 */
class Fornecedor extends AppModel {
    
    public $useTable = "fornecedores";

    public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {
            if(isset($results[$key]['Fornecedor'])) {
                foreach ($results[$key]['Fornecedor'] as $key2 => $value2) {
                    $results[$key]['Fornecedor'][$key2] = mb_convert_case($results[$key]['Fornecedor'][$key2], MB_CASE_UPPER, "ISO-8859-1");
                }
                
                if(isset($results[$key]['Fornecedor']['cep'])) $results[$key]['Fornecedor']['cep'] = $this->cep($val['Fornecedor']['cep'], false);
                if(isset($results[$key]['Fornecedor']['cnpj'])) $results[$key]['Fornecedor']['cnpj'] = $this->cnpj($val['Fornecedor']['cnpj'], false);
                if(isset($results[$key]['Fornecedor']['telefone'])) $results[$key]['Fornecedor']['telefone'] = $this->fone($val['Fornecedor']['telefone'], false);
                if(isset($results[$key]['Fornecedor']['fax'])) $results[$key]['Fornecedor']['fax'] = $this->fone($val['Fornecedor']['fax'], false);
            }
        }
        return $results;
    }
    
    public function beforeSave($options = array()) {
        parent::beforeSave($options);
        if(isset($this->data['Fornecedor'])) {
            if(isset($this->data['Fornecedor']['cep'])) $this->data['Fornecedor']['cep'] = $this->cep($this->data['Fornecedor']['cep']);
            if(isset($this->data['Fornecedor']['cnpj'])) $this->data['Fornecedor']['cnpj'] = $this->cnpj($this->data['Fornecedor']['cnpj']);
            if(isset($this->data['Fornecedor']['telefone'])) $this->data['Fornecedor']['telefone'] = $this->fone($this->data['Fornecedor']['telefone']);
            if(isset($this->data['Fornecedor']['fax'])) $this->data['Fornecedor']['fax'] = $this->fone($this->data['Fornecedor']['fax']);
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
    
    public function cnpj($cnpj, $bol = true) {
        if(!$cnpj){
            return;
        }
        
        if ($bol) {
            return str_replace('/', '', str_replace('.', '', str_replace('-', '', $cnpj)));
        } else {
            return substr($cnpj, 0, 2).'.'.substr($cnpj, 2, 3).'.'.substr($cnpj, 5, 3).'/'.substr($cnpj, 8, 4).'-'.substr($cnpj, 12, 2);
        }
    }
    
    public function cep($cep, $bol = true) {
        if(!$cep){
            return;
        }
        
        if ($bol) {
            return str_replace('.', '', str_replace('-', '', $cep));
        } else {
            return substr($cep, 0, 2).'.'.substr($cep, 2, 3).'-'.substr($cep, 5, 3);
        }
    }
    
    public function fone($fone, $bol = true) {
        if(!$fone){
            return;
        }
        
        if ($bol) {
            return str_replace('(', '', str_replace(')', '', str_replace('-', '', $fone)));
        } else {
            return '('.substr($fone, 0, 2).')'.substr($fone, 2, 9);
        }
    }
    
}
