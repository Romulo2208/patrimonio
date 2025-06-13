<?php

App::uses('AppController', 'Controller');

/**
 * Patrimonios Controller
 *
 * @property Patrimonio $Patrimonio
 * @property PaginatorComponent $Paginator
 */
class PatrimoniosController extends AppController {
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
        $option = array('OR' => array());
        if ($search) {
            $option['OR']['Patrimonio.descricao LIKE'] = "%{$search}%";
            $option['OR']['Patrimonio.codigo'] = "{$search}";
        }

        $this->Patrimonio->recursive = 0;
        $this->Paginator->settings = array('limit' => '20');
        $this->set('patrimonios', $this->Paginator->paginate('Patrimonio', $option));
    }
    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->request->data['Patrimonio']['data_registro'] = date('d/m/Y');

            $this->Patrimonio->create();
            if ($result = $this->Patrimonio->save($this->request->data)) {
                $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
                return $this->redirect(array('action' => 'add'));
            } else {
                $this->Session->setFlash(__($this::MSG_ERRO));
            }
        }

        $this->loadModel('Localizacao');
        $localizacaos = $this->Localizacao->find('list', array('fields' => array('id', 'descricao'), 'order' => 'descricao ASC'));

        $this->loadModel('Fornecedor');
        $fornecedors = $this->Fornecedor->find('list', array('fields' => array('id', 'nome_fantasia'), 'order' => 'nome_fantasia ASC'));

        $this->loadModel('Conservacao');
        $conservacaos = $this->Conservacao->find('list', array('fields' => array('id', 'descricao')));

        $firmas = array(null => null, '0101' => '0101', '0202' => '0202');
        $this->set(compact('localizacaos', 'fornecedors', 'conservacaos', 'firmas'));
    }
    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->Patrimonio->exists($id)) {
            throw new NotFoundException(__('Invalid patrimonio'));
        }
        if ($this->request->is(array('post', 'put'))) {

            if(!$this->Patrimonio->verificaData($this->request->data['Patrimonio']['data_baixa'])){
               $this->Session->setFlash('Data baixa invalida');
                return $this->redirect(array('action' => 'edit', $id));
           }

           if(!$this->Patrimonio->verificaData($this->request->data['Patrimonio']['data_registro'])){
               $this->Session->setFlash('Data registro invalida');
                return $this->redirect(array('action' => 'edit', $id));
           }

            if ($this->Patrimonio->save($this->request->data)) {
                $this->Session->setFlash(__($this::MSG_SUCESSO_EDT));
                return $this->redirect(array('action' => 'edit', $id));
            } else {
                $this->Session->setFlash(__($this::MSG_ERRO));
            }
        } else {
            $options = array('conditions' => array('Patrimonio.' . $this->Patrimonio->primaryKey => $id));
            $this->request->data = $this->Patrimonio->find('first', $options);
        }

        $this->loadModel('NotaFiscal');
        $notafiscal = $this->NotaFiscal->find('first', array(
            'fields' => array('NotaFiscal.id', 'Item.*'),
            'conditions' => array('Item.patrimonios_id' => $id),
            'joins' => array(
                array(
                    'table' => 'patrimonios_itens',
                    'alias' => 'Item',
                    'type' => 'LEFT',
                    'conditions' => array('NotaFiscal.id = Item.notas_fiscais_id')
                )
            )
        ));

        $this->loadModel('Localizacao');
        $localizacaos = $this->Localizacao->find('list', array('fields' => array('id', 'descricao'), 'order' => 'descricao ASC'));

        $this->loadModel('Fornecedor');
        $fornecedors = $this->Fornecedor->find('list', array('fields' => array('id', 'nome_fantasia'), 'order' => 'nome_fantasia ASC'));

        $this->loadModel('Conservacao');
        $conservacaos = $this->Conservacao->find('list', array('fields' => array('id', 'descricao')));

        $this->set(compact('localizacaos', 'fornecedors', 'conservacaos', 'notafiscal'));
    }
    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        $this->Patrimonio->id = $id;
        if (!$this->Patrimonio->exists()) {
            throw new NotFoundException(__('Invalid patrimonio'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Patrimonio->delete()) {
           $this->Session->setFlash(__($this::MSG_SUCESSO_DEL));
        } else {
            $this->Session->setFlash(__($this::MSG_ERRO));
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function getCodigoByFirma($id = null) {
        $codigo = $this->Patrimonio->find('first', array( 'fields' => array('MAX(Patrimonio.codigo) as codigo'), 'conditions' => array('firma' => $id)));
        echo json_encode($codigo);
        exit;
    }

    public function duplicar($id = null) {

        $options = array('conditions' => array('Patrimonio.' . $this->Patrimonio->primaryKey => $id));
        $this->request->data = $this->Patrimonio->find('first', $options);
        $codigo = $this->Patrimonio->find('first', array('fields' => array('MAX(Patrimonio.codigo +1) as codigo'), 'conditions' => array('firma' => $this->request->data['Patrimonio']['firma'])));
        $this->request->data['Patrimonio']['codigo'] = $codigo['0']['codigo'];
        unset($this->request->data['Patrimonio']['id']);
        $this->request->data['Patrimonio']['data_registro'] = date('d/m/Y');
        $this->request->data['Patrimonio']['data_baixa'] = null;
//        pr($this->request->data);exit;

        $this->Patrimonio->create();
        if ($result = $this->Patrimonio->save($this->request->data)) {
            $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
            return $this->redirect(array('action' => 'edit', $result['Patrimonio']['id']));
        } else {
            $this->Session->setFlash(__($this::MSG_ERRO));
            return $this->redirect(array('action' => 'edit', $id));
        }
        exit;
    }

}
