<?php

App::uses('AppController', 'Controller');

/**
 * Patrimonios Controller
 *
 * @property Patrimonio $Patrimonio
 * @property PaginatorComponent $Paginator
 */
class MateriaisClassificacoesController extends AppController {
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
        $option['OR']['MaterialClassificacao.descricao LIKE'] = "%{$search}%";

      }

      $this->loadModel('MaterialClassificacao');
      $this->MaterialClassificacao->recursive = 0;
      $this->Paginator->settings = array('limit' => '20', 'order' => 'descricao asc');
      $this->set('classificacoes', $this->Paginator->paginate('MaterialClassificacao', $option));

    }

      public function add() {
        $this->loadModel('MaterialClassificacao');
          if ($this->request->is('post')) {
              //$this->request->data['Patrimonio']['data_registro'] = date('d/m/Y');

              $this->MaterialClassificacao->create();
              if ($result = $this->MaterialClassificacao->save($this->request->data)) {
                  $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
                  return $this->redirect(array('action' => 'add'));
              } else {
                  $this->Session->setFlash(__($this::MSG_ERRO));
              }
          }
      }

      public function edit($id = null) {

        $this->loadModel('MaterialClassificacao');
        // $this->MaterialClassificacao->id = $id;
        if (!$this->MaterialClassificacao->exists($id)) {
            throw new NotFoundException(__('Invalid Categoria'));
        }
        if ($this->request->is(array('post', 'put'))) {
          // pr($this->request->data);exit;
          if ($this->MaterialClassificacao->save($this->request->data)) {
              $this->Session->setFlash(__($this::MSG_SUCESSO_EDT));
              return $this->redirect(array('action' => 'index'));
          } else {
              $this->Session->setFlash(__($this::MSG_ERRO));
          }
      } else {
          $options = array('conditions' => array('MaterialClassificacao.' . $this->MaterialClassificacao->primaryKey => $id));
          $this->request->data = $this->MaterialClassificacao->find('first',$options);
      }
  }


      public function delete($id = null) {
          // $this->MaterialClassificacao->id = $id;
          $this->loadModel('MaterialClassificacao');
          $this->MaterialClassificacao->id = $id;
          if (!$this->MaterialClassificacao->exists()) {
              throw new NotFoundException(__('Invalid patrimonio'));
          }
          $this->request->is('post', 'delete');
          if ($this->MaterialClassificacao->delete()) {
             $this->Session->setFlash(__($this::MSG_SUCESSO_DEL));
          } else {
              $this->Session->setFlash(__($this::MSG_ERRO));
          }
          return $this->redirect(array('action' => 'index'));
      }
}
