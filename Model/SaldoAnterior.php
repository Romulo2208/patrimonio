<?php

App::uses('AppModel', 'Model');

/**
 * Solicitaco Model
 *
 */
class SaldoAnterior extends AppModel {

    public $useTable = "saldos_anteriores";


    public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {
            if(isset($results[$key]['SaldoAnterior'])) {
                foreach ($results[$key]['SaldoAnterior'] as $key2 => $value2) {
                    $results[$key]['SaldoAnterior'][$key2] = mb_convert_case($results[$key]['SaldoAnterior'][$key2], MB_CASE_UPPER, "ISO-8859-1");
                }

//                if(isset($results[$key]['Material']['valor'])) $results[$key]['estoque']['valor'] = $this->moeda($val['Material']['valor'], false);
                if(isset($results[$key]['SaldoAnterior']['data'])) $results[$key]['SaldoAnterior']['data'] = $this->date($val['SaldoAnterior']['data'], false);
//                if(isset($results[$key]['Material']['data_baixa'])) $results[$key]['Material']['data_baixa'] = $this->date($val['Material']['data_baixa'], false);
            }
        }
        return $results;
    }


    public function beforeSave($options = array()) {
        parent::beforeSave($options);
        if(isset($this->data['SaldoAnterior'])) {
//            if(isset($this->data['Material']['valor'])) $this->data['Material']['valor'] = $this->moeda($this->data['Material']['valor']);
            if(isset($this->data['SaldoAnterior']['data'])) $this->data['SaldoAnterior']['data'] = $this->date($this->data['SaldoAnterior']['data']);
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
