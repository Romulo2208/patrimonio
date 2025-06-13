<?php

App::uses('AppController', 'Controller');

/**
 * patrimonios Controller
 *
 * @property patrimonio $patrimonio
 * @property PaginatorComponent $Paginator
 */
class ProdutosController extends AppController {
    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');
    /**
     * index method
     *
     * @return void
     */
    public function index($search = null) {

      $option = array();
      if ($search) {
        $option['OR']['Produto.descricao LIKE'] = "%{$search}%";

      }

      $this->loadModel('Produto');
      $this->Produto->recursive = 0;
      $this->Paginator->settings = array('limit' => '20', 'order' => 'id asc');
      $this->set('produtos', $this->Paginator->paginate('Produto', $option));

    }

      public function add() {
        $this->loadModel('Produto');
          if ($this->request->is('post')) {
              //$this->request->data['patrimonio']['data_registro'] = date('d/m/Y');

              $this->Produto->create();
              if ($result = $this->Produto->save($this->request->data)) {
                  $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
                  return $this->redirect(array('action' => 'add'));
              } else {
                  $this->Session->setFlash(__($this::MSG_ERRO));
              }
          }
      }

      public function edit($id = null) {

        $this->loadModel('Produto');
        // $this->MaterialClassificacao->id = $id;
        if (!$this->Produto->exists($id)) {
            throw new NotFoundException(__('Invalid Produto'));
        }
        if ($this->request->is(array('post', 'put'))) {
          // pr($this->request->data);exit;
          if ($this->Produto->save($this->request->data)) {
              $this->Session->setFlash(__($this::MSG_SUCESSO_EDT));
              return $this->redirect(array('action' => 'index'));
          } else {
              $this->Session->setFlash(__($this::MSG_ERRO));
          }
      } else {
          $options = array('conditions' => array('Produto.' . $this->Produto->primaryKey => $id));
          $this->request->data = $this->Produto->find('first',$options);
      }
  }


      public function delete($id = null) {
          // $this->MaterialClassificacao->id = $id;
          $this->loadModel('Produto');
          $this->Produto->id = $id;
          if (!$this->Produto->exists()) {
              throw new NotFoundException(__('Invalid patrimonio'));
          }
          $this->request->is('post', 'delete');
          if ($this->Produto->delete()) {
             $this->Session->setFlash(__($this::MSG_SUCESSO_DEL));
          } else {
              $this->Session->setFlash(__($this::MSG_ERRO));
          }
          return $this->redirect(array('action' => 'index'));
      }
}
