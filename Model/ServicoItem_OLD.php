<?php

App::uses('AppModel', 'Model');
App::import('Model','DBMongo');


class ServicoItem extends AppModel {

    public $useTable = "servicos_itens";

    public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {
            if(isset($results[$key]['ServicoItem'])) {
                //if(isset($results[$key]['ServicoItem']['valor_total'])) $results[$key]['ServicoItem']['valor_total'] = $this->moeda($val['ServicoItem']['valor_total'], false);
            }
        }
        return $results;
    }

    public function beforeSave($options = array()) {
        parent::beforeSave($options);
        if(isset($this->data['ServicoItem'])) {
            if(isset($this->data['ServicoItem']['valor_total'])) $this->data['ServicoItem']['valor_total'] = $this->moeda($this->data['ServicoItem']['valor_total']);
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
