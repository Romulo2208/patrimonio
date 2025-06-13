<?php

App::uses('AppController', 'Controller');

class MainsController extends AppController {

    public function index() {
        
    }
    
    public function historico() {
        $this->loadModel('DBMongo');
        $log = $this->DBMongo->view($this->request->params['named']);
        $this->set('log', $log);
    }

    public function cep($cep = null) {
        $cep = str_replace('.', '', $cep);
        $cep = str_replace('-', '', $cep);

        $homepage = file_get_contents("https://viacep.com.br/ws/{$cep}/json/");
        echo $homepage;
        exit;
    }
}
