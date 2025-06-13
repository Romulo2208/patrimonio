<?php
App::uses('AppModel', 'Model');
/**
 * Localizacao Model
 *
 * @property Patrimonio $Patrimonio
 */
class Entrada extends AppModel {
    
    public $useTable = "entradas";
    
    public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {
            if(isset($results[$key]['Entrada'])) {
                foreach ($results[$key]['Entrada'] as $key2 => $value2) {
                    $results[$key]['Entrada'][$key2] = mb_convert_case($results[$key]['Entrada'][$key2], MB_CASE_UPPER, "ISO-8859-1");
                }
                
//                if(isset($results[$key]['Material']['valor'])) $results[$key]['Patrimonio']['valor'] = $this->moeda($val['Material']['valor'], false);
                if(isset($results[$key]['Entrada']['data_entrada'])) $results[$key]['Entrada']['data_entrada'] = $this->date($val['Entrada']['data_entrada'], false);
//                if(isset($results[$key]['Material']['data_baixa'])) $results[$key]['Material']['data_baixa'] = $this->date($val['Material']['data_baixa'], false);
            }
        }
        return $results;
    }
    
    public function beforeSave($options = array()) {
        parent::beforeSave($options);
        if(isset($this->data['Entrada'])) {
//            if(isset($this->data['Material']['valor'])) $this->data['Material']['valor'] = $this->moeda($this->data['Material']['valor']);
            if(isset($this->data['Entrada']['data_entrada'])) $this->data['Entrada']['data_entrada'] = $this->date($this->data['Entrada']['data_entrada']);
//            if(isset($this->data['Material']['data_baixa'])) $this->data['Material']['data_baixa'] = $this->date($this->data['Material']['data_baixa']);
        }
        return true;
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
}
