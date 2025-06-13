<?php

App::uses('AppController', 'Controller');

/**
* Materiais Controller
*

* @property PaginatorComponent $Paginator
*/
class MateriaisFiliaisController extends AppController {

  /**
  * Components
  *
  * @var array
  */
  public $uses = array();
  public $components = array('Paginator', 'Upload');

  /**
  * index method
  *
  * @return void
  */

  public function initialize(){
        parent::initialize();

        // Include the FlashComponent
        $this->loadComponent('Flash');

        // Load Files model
        $this->loadModel('Files');

        // Set the layout
        $this->layout = 'frontend';
    }


    public function filial01($search = null) {
      //pr($this->Session->read('Auth.User.setor'));exit;
      $option = array();
      if ($search) {
        $option['OR']['Material.nome LIKE'] = "%{$search}%";
        $option['OR']['Material.barcode LIKE'] = "%{$search}%";
        $option['OR']['Material.id LIKE'] = "%{$search}%";
        $option['OR']['Classificacao.descricao LIKE'] = "%{$search}%";
        $option['OR']['Material.descricao LIKE'] = "%{$search}%";
      }

      $this->loadModel('MaterialFilial');
      $this->MaterialFilial->recursive = 0;


      $this->Paginator->settings = array(
        'fields' => array('Classificacao.*', 'Material.*', 'MaterialFilial.*', 'Setor.descricao'),
        'conditions' => array('MaterialFilial.setor_id' => 2),
        'joins' => array(
          array(
            'table' => 'materiais',
            'alias' => 'Material',
            'type' => 'INNER',
            'conditions' => array('Material.id = MaterialFilial.materiais_id')
          ),
          array(
            'table' => 'materiais_classificacoes',
            'alias' => 'Classificacao',
            'type' => 'INNER',
            'conditions' => array('Classificacao.id = Material.classificacoes_id')
          ),
          array(
            'table' => 'setores',
            'alias' => 'Setor',
            'type' => 'INNER',
            'conditions' => array('Setor.id = MaterialFilial.setor_id')
          )
        ),
        'limit' => '20',
        'order' => 'Material.data_registro desc'
      );

      $this->set('materiais', $this->Paginator->paginate('MaterialFilial', $option));

      //pr($classificacoes);exit;
    }

    public function filial02($search = null) {
      //pr($this->Session->read('Auth.User.setor'));exit;
      $option = array();
      if ($search) {
        $option['OR']['Material.nome LIKE'] = "%{$search}%";
        $option['OR']['Material.barcode LIKE'] = "%{$search}%";
        $option['OR']['Material.id LIKE'] = "%{$search}%";
        $option['OR']['Classificacao.descricao LIKE'] = "%{$search}%";
        $option['OR']['Material.descricao LIKE'] = "%{$search}%";
      }

      $this->loadModel('MaterialFilial');
      $this->MaterialFilial->recursive = 0;


      $this->Paginator->settings = array(
        'fields' => array('Classificacao.*', 'Material.*', 'MaterialFilial.*', 'Setor.descricao'),
        'conditions' => array('MaterialFilial.setor_id' => 3),
        'joins' => array(
          array(
            'table' => 'materiais',
            'alias' => 'Material',
            'type' => 'INNER',
            'conditions' => array('Material.id = MaterialFilial.materiais_id')
          ),
          array(
            'table' => 'materiais_classificacoes',
            'alias' => 'Classificacao',
            'type' => 'INNER',
            'conditions' => array('Classificacao.id = Material.classificacoes_id')
          ),
          array(
            'table' => 'setores',
            'alias' => 'Setor',
            'type' => 'INNER',
            'conditions' => array('Setor.id = MaterialFilial.setor_id')
          )
        ),
        'limit' => '20',
        'order' => 'Material.data_registro desc'
      );

      $this->set('materiais', $this->Paginator->paginate('MaterialFilial', $option));

      //pr($classificacoes);exit;
    }

    public function filial04($search = null) {
      //pr($this->Session->read('Auth.User.setor'));exit;
      $option = array();
      if ($search) {
        $option['OR']['Material.nome LIKE'] = "%{$search}%";
        $option['OR']['Material.barcode LIKE'] = "%{$search}%";
        $option['OR']['Material.id LIKE'] = "%{$search}%";
        $option['OR']['Classificacao.descricao LIKE'] = "%{$search}%";
        $option['OR']['Material.descricao LIKE'] = "%{$search}%";
      }

      $this->loadModel('MaterialFilial');
      $this->MaterialFilial->recursive = 0;


      $this->Paginator->settings = array(
        'fields' => array('Classificacao.*', 'Material.*', 'MaterialFilial.*', 'Setor.descricao'),
        'conditions' => array('MaterialFilial.setor_id' => 4),
        'joins' => array(
          array(
            'table' => 'materiais',
            'alias' => 'Material',
            'type' => 'INNER',
            'conditions' => array('Material.id = MaterialFilial.materiais_id')
          ),
          array(
            'table' => 'materiais_classificacoes',
            'alias' => 'Classificacao',
            'type' => 'INNER',
            'conditions' => array('Classificacao.id = Material.classificacoes_id')
          ),
          array(
            'table' => 'setores',
            'alias' => 'Setor',
            'type' => 'INNER',
            'conditions' => array('Setor.id = MaterialFilial.setor_id')
          )
        ),
        'limit' => '20',
        'order' => 'Material.data_registro desc'
      );

      $this->set('materiais', $this->Paginator->paginate('MaterialFilial', $option));

      //pr($classificacoes);exit;
    }

    public function filial09($search = null) {
      //pr($this->Session->read('Auth.User.setor'));exit;
      $option = array();
      if ($search) {
        $option['OR']['Material.nome LIKE'] = "%{$search}%";
        $option['OR']['Material.barcode LIKE'] = "%{$search}%";
        $option['OR']['Material.id LIKE'] = "%{$search}%";
        $option['OR']['Classificacao.descricao LIKE'] = "%{$search}%";
        $option['OR']['Material.descricao LIKE'] = "%{$search}%";
      }

      $this->loadModel('MaterialFilial');
      $this->MaterialFilial->recursive = 0;


      $this->Paginator->settings = array(
        'fields' => array('Classificacao.*', 'Material.*', 'MaterialFilial.*', 'Setor.descricao'),
        'conditions' => array('MaterialFilial.setor_id' => 5),
        'joins' => array(
          array(
            'table' => 'materiais',
            'alias' => 'Material',
            'type' => 'INNER',
            'conditions' => array('Material.id = MaterialFilial.materiais_id')
          ),
          array(
            'table' => 'materiais_classificacoes',
            'alias' => 'Classificacao',
            'type' => 'INNER',
            'conditions' => array('Classificacao.id = Material.classificacoes_id')
          ),
          array(
            'table' => 'setores',
            'alias' => 'Setor',
            'type' => 'INNER',
            'conditions' => array('Setor.id = MaterialFilial.setor_id')
          )
        ),
        'limit' => '20',
        'order' => 'Material.data_registro desc'
      );

      $this->set('materiais', $this->Paginator->paginate('MaterialFilial', $option));

      //pr($classificacoes);exit;
    }

    public function emfol($search = null) {
      //pr($this->Session->read('Auth.User.setor'));exit;
      $option = array();
      if ($search) {
        $option['OR']['Material.nome LIKE'] = "%{$search}%";
        $option['OR']['Material.barcode LIKE'] = "%{$search}%";
        $option['OR']['Material.id LIKE'] = "%{$search}%";
        $option['OR']['Classificacao.descricao LIKE'] = "%{$search}%";
        $option['OR']['Material.descricao LIKE'] = "%{$search}%";
      }

      $this->loadModel('MaterialFilial');
      $this->MaterialFilial->recursive = 0;


      $this->Paginator->settings = array(
        'fields' => array('Classificacao.*', 'Material.*', 'MaterialFilial.*', 'Setor.descricao'),
        'conditions' => array('MaterialFilial.setor_id' => 6),
        'joins' => array(
          array(
            'table' => 'materiais',
            'alias' => 'Material',
            'type' => 'INNER',
            'conditions' => array('Material.id = MaterialFilial.materiais_id')
          ),
          array(
            'table' => 'materiais_classificacoes',
            'alias' => 'Classificacao',
            'type' => 'INNER',
            'conditions' => array('Classificacao.id = Material.classificacoes_id')
          ),
          array(
            'table' => 'setores',
            'alias' => 'Setor',
            'type' => 'INNER',
            'conditions' => array('Setor.id = MaterialFilial.setor_id')
          )
        ),
        'limit' => '20',
        'order' => 'Material.data_registro desc'
      );

      $this->set('materiais', $this->Paginator->paginate('MaterialFilial', $option));

      //pr($classificacoes);exit;
    }

    public function calta($search = null) {
      //pr($this->Session->read('Auth.User.setor'));exit;
      $option = array();
      if ($search) {
        $option['OR']['Material.nome LIKE'] = "%{$search}%";
        $option['OR']['Material.barcode LIKE'] = "%{$search}%";
        $option['OR']['Material.id LIKE'] = "%{$search}%";
        $option['OR']['Classificacao.descricao LIKE'] = "%{$search}%";
        $option['OR']['Material.descricao LIKE'] = "%{$search}%";
      }

      $this->loadModel('MaterialFilial');
      $this->MaterialFilial->recursive = 0;


      $this->Paginator->settings = array(
        'fields' => array('Classificacao.*', 'Material.*', 'MaterialFilial.*', 'Setor.descricao'),
        'conditions' => array('MaterialFilial.setor_id' => 7),
        'joins' => array(
          array(
            'table' => 'materiais',
            'alias' => 'Material',
            'type' => 'INNER',
            'conditions' => array('Material.id = MaterialFilial.materiais_id')
          ),
          array(
            'table' => 'materiais_classificacoes',
            'alias' => 'Classificacao',
            'type' => 'INNER',
            'conditions' => array('Classificacao.id = Material.classificacoes_id')
          ),
          array(
            'table' => 'setores',
            'alias' => 'Setor',
            'type' => 'INNER',
            'conditions' => array('Setor.id = MaterialFilial.setor_id')
          )
        ),
        'limit' => '20',
        'order' => 'Material.data_registro desc'
      );

      $this->set('materiais', $this->Paginator->paginate('MaterialFilial', $option));

      //pr($classificacoes);exit;
    }

    public function americal($search = null) {
      //pr($this->Session->read('Auth.User.setor'));exit;
      $option = array();
      if ($search) {
        $option['OR']['Material.nome LIKE'] = "%{$search}%";
        $option['OR']['Material.barcode LIKE'] = "%{$search}%";
        $option['OR']['Material.id LIKE'] = "%{$search}%";
        $option['OR']['Classificacao.descricao LIKE'] = "%{$search}%";
        $option['OR']['Material.descricao LIKE'] = "%{$search}%";
      }

      $this->loadModel('MaterialFilial');
      $this->MaterialFilial->recursive = 0;


      $this->Paginator->settings = array(
        'fields' => array('Classificacao.*', 'Material.*', 'MaterialFilial.*', 'Setor.descricao'),
        'conditions' => array('MaterialFilial.setor_id' => 8),
        'joins' => array(
          array(
            'table' => 'materiais',
            'alias' => 'Material',
            'type' => 'INNER',
            'conditions' => array('Material.id = MaterialFilial.materiais_id')
          ),
          array(
            'table' => 'materiais_classificacoes',
            'alias' => 'Classificacao',
            'type' => 'INNER',
            'conditions' => array('Classificacao.id = Material.classificacoes_id')
          ),
          array(
            'table' => 'setores',
            'alias' => 'Setor',
            'type' => 'INNER',
            'conditions' => array('Setor.id = MaterialFilial.setor_id')
          )
        ),
        'limit' => '20',
        'order' => 'Material.data_registro desc'
      );

      $this->set('materiais', $this->Paginator->paginate('MaterialFilial', $option));

      //pr($classificacoes);exit;
    }

    //
    public function Ferragensfl01($search = null) {
      //pr($this->Session->read('Auth.User.setor'));exit;
      $option = array();
      if ($search) {
        $option['OR']['Material.nome LIKE'] = "%{$search}%";
        $option['OR']['Material.barcode LIKE'] = "%{$search}%";
        $option['OR']['Material.id LIKE'] = "%{$search}%";
        $option['OR']['Classificacao.descricao LIKE'] = "%{$search}%";
        $option['OR']['Material.descricao LIKE'] = "%{$search}%";
      }

      $this->loadModel('MaterialFilial');
      $this->MaterialFilial->recursive = 0;


      $this->Paginator->settings = array(
        'fields' => array('Classificacao.*', 'Material.*', 'MaterialFilial.*', 'Setor.descricao'),
        'conditions' => array('MaterialFilial.setor_id' => 12),
        'joins' => array(
          array(
            'table' => 'materiais',
            'alias' => 'Material',
            'type' => 'INNER',
            'conditions' => array('Material.id = MaterialFilial.materiais_id')
          ),
          array(
            'table' => 'materiais_classificacoes',
            'alias' => 'Classificacao',
            'type' => 'INNER',
            'conditions' => array('Classificacao.id = Material.classificacoes_id')
          ),
          array(
            'table' => 'setores',
            'alias' => 'Setor',
            'type' => 'INNER',
            'conditions' => array('Setor.id = MaterialFilial.setor_id')
          )
        ),
        'limit' => '20',
        'order' => 'Material.data_registro desc'
      );

      $this->set('materiais', $this->Paginator->paginate('MaterialFilial', $option));

      //pr($classificacoes);exit;
    }
    //

    public function filial05($search = null) {
      //pr($this->Session->read('Auth.User.setor'));exit;
      $option = array();
      if ($search) {
        $option['OR']['Material.nome LIKE'] = "%{$search}%";
        $option['OR']['Material.barcode LIKE'] = "%{$search}%";
        $option['OR']['Material.id LIKE'] = "%{$search}%";
        $option['OR']['Classificacao.descricao LIKE'] = "%{$search}%";
        $option['OR']['Material.descricao LIKE'] = "%{$search}%";
      }

      $this->loadModel('MaterialFilial');
      $this->MaterialFilial->recursive = 0;


      $this->Paginator->settings = array(
        'fields' => array('Classificacao.*', 'Material.*', 'MaterialFilial.*', 'Setor.descricao'),
        'conditions' => array('MaterialFilial.setor_id' => 9),
        'joins' => array(
          array(
            'table' => 'materiais',
            'alias' => 'Material',
            'type' => 'INNER',
            'conditions' => array('Material.id = MaterialFilial.materiais_id')
          ),
          array(
            'table' => 'materiais_classificacoes',
            'alias' => 'Classificacao',
            'type' => 'INNER',
            'conditions' => array('Classificacao.id = Material.classificacoes_id')
          ),
          array(
            'table' => 'setores',
            'alias' => 'Setor',
            'type' => 'INNER',
            'conditions' => array('Setor.id = MaterialFilial.setor_id')
          )
        ),
        'limit' => '20',
        'order' => 'Material.data_registro desc'
      );

      $this->set('materiais', $this->Paginator->paginate('MaterialFilial', $option));

      //pr($classificacoes);exit;


      /*
    public function ferragens ($search = null) {
      //pr($this->Session->read('Auth.User.setor'));exit;
      $option = array();
      if ($search) {
        $option['OR']['Material.nome LIKE'] = "%{$search}%";
        $option['OR']['Material.barcode LIKE'] = "%{$search}%";
        $option['OR']['Material.id LIKE'] = "%{$search}%";
        $option['OR']['Classificacao.descricao LIKE'] = "%{$search}%";
        $option['OR']['Material.descricao LIKE'] = "%{$search}%";
      }

      $this->loadModel('MaterialFilial');
      $this->MaterialFilial->recursive = 0;


      $this->Paginator->settings = array(
        'fields' => array('Classificacao.*', 'Material.*', 'MaterialFilial.*', 'Setor.descricao'),
        'conditions' => array('MaterialFilial.setor_id' => 8),
        'joins' => array(
          array(
            'table' => 'materiais',
            'alias' => 'Material',
            'type' => 'INNER',
            'conditions' => array('Material.id = MaterialFilial.materiais_id')
          ),
          array(
            'table' => 'materiais_classificacoes',
            'alias' => 'Classificacao',
            'type' => 'INNER',
            'conditions' => array('Classificacao.id = Material.classificacoes_id')
          ),
          array(
            'table' => 'setores',
            'alias' => 'Setor',
            'type' => 'INNER',
            'conditions' => array('Setor.id = MaterialFilial.setor_id')
          )
        ),
        'limit' => '20',
        'order' => 'Material.data_registro desc'
      );

      $this->set('materiais', $this->Paginator->paginate('MaterialFilial', $option));

      //pr($classificacoes);exit;
    }

    }

}
*/
