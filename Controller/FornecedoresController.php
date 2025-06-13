<?php

App::uses('AppController', 'Controller');

/**
 * Fornecedors Controller
 *
 * @property Fornecedor $Fornecedor
 * @property PaginatorComponent $Paginator
 */
class FornecedoresController extends AppController {

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
            $option['Fornecedor.nome_fantasia LIKE'] = "%{$search}%";
        }

        $this->loadModel('Fornecedor');
        $this->Fornecedor->recursive = 0;
        $this->Paginator->settings = array('limit' => '20', 'order' => 'nome_fantasia asc');
        $this->set('fornecedores', $this->Paginator->paginate('Fornecedor', $option));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {

        $this->loadModel('Fornecedor');

        if ($this->request->is('post')) {
           $resultado= $this->Fornecedor->find('first', array('conditions'=>array('cnpj'=>$this->Fornecedor->cnpj($this->request->data['Fornecedor']['cnpj']))));
           if($resultado){
               $this->Session->setFlash('Cnpj já Existe');
                return $this->redirect(array('action' => 'add'));
           }
            $this->Fornecedor->create();
            if ($result = $this->Fornecedor->save($this->request->data)) {
                $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
                return $this->redirect(array('action' => 'add'));
            } else {
                $this->Session->setFlash(__($this::MSG_ERRO));
            }
        }

        $this->loadModel('Estado');
        $ufs = $this->Estado->find('list', array('fields' => array('id', 'sigla')));

        $this->loadModel('Banco');
        $bancos = $this->Banco->find('list', array('fields' => array('id', 'banco')));

        $this->set(compact('ufs', 'bancos'));

    }



    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        $this->loadModel('Fornecedor');

        if (!$this->Fornecedor->exists($id)) {
            throw new NotFoundException(__('Invalid fornecedor'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Fornecedor->save($this->request->data)) {
                $this->Session->setFlash(__($this::MSG_SUCESSO_EDT));
                return $this->redirect(array('action' => 'edit', $id));
            } else {
                $this->Session->setFlash(__($this::MSG_ERRO));
            }
        } else {
            $options = array('conditions' => array('Fornecedor.' . $this->Fornecedor->primaryKey => $id));
            $this->request->data = $this->Fornecedor->find('first', $options);
        }

        $this->loadModel('Estado');
        $ufs = $this->Estado->find('list', array('fields' => array('id', 'sigla')));

        $this->loadModel('Banco');
        $bancos = $this->Banco->find('list', array('fields' => array('id', 'banco')));

            $this->set(compact('ufs','bancos'));
    }
    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        $this->loadModel('Fornecedor');

        $this->Fornecedor->id = $id;
        if (!$this->Fornecedor->exists()) {
            throw new NotFoundException(__('Invalid fornecedor'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Fornecedor->delete()) {
            $this->Session->setFlash(__($this::MSG_SUCESSO_DEL));
        } else {
            $this->Session->setFlash(__($this::MSG_ERRO));
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function consultacnpj($cnpj){
        $this->loadModel('Fornecedor');
         $resultado= $this->Fornecedor->find('first', array('fields'=> array('id'),'conditions'=>array('cnpj'=>$this->Fornecedor->cnpj($cnpj))));

           echo json_encode($resultado);
           exit;
    }
}
