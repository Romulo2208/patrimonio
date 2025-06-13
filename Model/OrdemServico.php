<?php

App::uses('AppModel', 'Model');

/**
 * Solicitaco Model
 *
 */
class OrdemServico extends AppModel {

    public $useTable = "ordens_servicos";

    public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {
            if(isset($results[$key]['OrdemServico'])) {
                foreach ($results[$key]['OrdemServico'] as $key2 => $value2) {
                    $results[$key]['OrdemServico'][$key2] = mb_convert_case($results[$key]['OrdemServico'][$key2], MB_CASE_UPPER, "ISO-8859-1");
                }

                if(isset($results[$key]['OrdemServico']['data'])) $results[$key]['OrdemServico']['data'] = $this->date($val['OrdemServico']['data'], false);
                if(isset($results[$key]['OrdemServico']['data_conclusao'])) $results[$key]['OrdemServico']['data_conclusao'] = $this->date($val['OrdemServico']['data_conclusao'], false);
                if(isset($results[$key]['OrdemServico']['data_assinatura'])) $results[$key]['OrdemServico']['data_assinatura'] = $this->date($val['OrdemServico']['data_assinatura'], false);
            }
        }
        return $results;
    }

    public function beforeSave($options = array()) {
        parent::beforeSave($options);
        if(isset($this->data['OrdemServico'])) {
            if(isset($this->data['OrdemServico']['data'])) $this->data['OrdemServico']['data'] = $this->date($this->data['OrdemServico']['data']);
            if(isset($this->data['OrdemServico']['data_conclusao'])) $this->data['OrdemServico']['data_conclusao'] = $this->date($this->data['OrdemServico']['data_conclusao']);
            if(isset($this->data['OrdemServico']['data_assinatura'])) $this->data['OrdemServico']['data_assinatura'] = $this->date($this->data['OrdemServico']['data_assinatura']);
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
