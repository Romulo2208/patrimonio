<?php

App::uses('AppModel', 'Model');
App::import('Model','DBMongo');


class MaterialItem extends AppModel {

    public $useTable = "materiais_itens";

    public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {
            if(isset($results[$key]['MaterialItem'])) {
                if(isset($results[$key]['MaterialItem']['valor_unitario'])) $results[$key]['MaterialItem']['valor_unitario'] = $this->moeda($val['MaterialItem']['valor_unitario'], false);
                if(isset($results[$key]['MaterialItem']['valor_total'])) $results[$key]['MaterialItem']['valor_total'] = $this->moeda($val['MaterialItem']['valor_total'], false);
            }
        }
        return $results;
    }

    public function beforeSave($options = array()) {
        parent::beforeSave($options);
        if(isset($this->data['MaterialItem'])) {
            if(isset($this->data['MaterialItem']['valor_unitario'])) $this->data['MaterialItem']['valor_unitario'] = $this->moeda($this->data['MaterialItem']['valor_unitario']);
            if(isset($this->data['MaterialItem']['valor_total'])) $this->data['MaterialItem']['valor_total'] = $this->moeda($this->data['MaterialItem']['valor_total']);
        }
        return true;
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

}
