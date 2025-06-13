<?php

App::uses('AppModel', 'Model');
App::import('Model','DBMongo');

/**
 * Item Model
 *
 * @property NotaFiscal $NotaFiscal
 * @property Patrimonio $Patrimonio
 */
class PatrimonioItem extends AppModel {

    public $useTable = "patrimonios_itens";

    public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {
            if(isset($results[$key]['PatrimonioItem'])) {
                if(isset($results[$key]['PatrimonioItem']['valor_unitario'])) $results[$key]['PatrimonioItem']['valor_unitario'] = $this->moeda($val['PatrimonioItem']['valor_unitario'], false);
                if(isset($results[$key]['PatrimonioItem']['valor_total'])) $results[$key]['PatrimonioItem']['valor_total'] = $this->moeda($val['PatrimonioItem']['valor_total'], false);
            }
        }
        return $results;
    }

    public function beforeSave($options = array()) {
        parent::beforeSave($options);
        if(isset($this->data['PatrimonioItem'])) {
            if(isset($this->data['PatrimonioItem']['valor_unitario'])) $this->data['PatrimonioItem']['valor_unitario'] = $this->moeda($this->data['PatrimonioItem']['valor_unitario']);
            if(isset($this->data['PatrimonioItem']['valor_total'])) $this->data['PatrimonioItem']['valor_total'] = $this->moeda($this->data['PatrimonioItem']['valor_total']);
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
