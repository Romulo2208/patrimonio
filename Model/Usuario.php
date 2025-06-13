<?php

App::uses('AppModel', 'Model');

/**
 * Usuario Model
 *
 */
class Usuario extends AppModel {

    public $useDbConfig="admin";
    public $validate = array(
        'nome' => array('notBlank' => array('rule' => array('notBlank'))),
        'login' => array('notBlank' => array('rule' => array('notBlank'))),
    );

    public function beforeSave($options = array()) {
        parent::beforeSave($options);
        if (isset($this->data['Usuario']['senha'])) {
            $this->data['Usuario']['senha'] = AuthComponent::password($this->data['Usuario']['senha']);
        }

        return true;
    }

}
