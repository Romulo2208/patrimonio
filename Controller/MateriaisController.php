<?php

App::uses('AppController', 'Controller');
require_once(ROOT . DS . 'patrimonio' . DS . 'Vendor' . DS . 'mpdf60' . DS . 'mpdf.php');

/**
* Materiais Controller
*
* @property Material $Material
* @property PaginatorComponent $Paginator
*/
class MateriaisController extends AppController {

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


  public function index($search = null) {
    $option = array();
    if ($search) {
      $option['OR']['Material.nome LIKE'] = "%{$search}%";
      $option['OR']['Material.barcode LIKE'] = "%{$search}%";
      $option['OR']['Material.id LIKE'] = "%{$search}%";
      $option['OR']['Classificacao.descricao LIKE'] = "%{$search}%";
      $option['OR']['Material.descricao LIKE'] = "%{$search}%";
      $option['OR']['MaterialFilial.prateleira LIKE'] = "%{$search}%";
    }

    $this->loadModel('Material');
    $this->Material->recursive = 0;


    $this->Paginator->settings = array(
        'fields' => array('Classificacao.*', 'Material.*', 'MaterialFilial.*', 'Setor.descricao'),
        'conditions' => array('MaterialFilial.setor_id' => $this->Session->read('Auth.User.setor')),
        'joins' => array(
          array(
            'table' => 'materiais_classificacoes',
            'alias' => 'Classificacao',
            'type' => 'LEFT',
            'conditions' => array('Classificacao.id = Material.classificacoes_id')
          ),
          array(
            'table' => 'materiais_filiais',
            'alias' => 'MaterialFilial',
            'type' => 'LEFT',
            'conditions' => array('MaterialFilial.materiais_id = Material.id')
          ),
          array(
            'table' => 'setores',
            'alias' => 'Setor',
            'type' => 'LEFT',
            'conditions' => array('Setor.id = MaterialFilial.setor_id')
          )
        ),
        'limit' => '20',
        'order' => 'Material.data_registro desc'
      );

      if(in_array($this->Session->read('Perfil.id'), array('12'))) {
        $this->Paginator->settings = array(
            'fields' => array('Classificacao.*', 'Material.*', 'MaterialFilial.*', 'Setor.descricao'),
            'conditions' => array('MaterialFilial.setor_id' => $this->Session->read('Auth.User.setor'), 'Classificacao.id' => 13),
            'joins' => array(
              array(
                'table' => 'materiais_classificacoes',
                'alias' => 'Classificacao',
                'type' => 'LEFT',
                'conditions' => array('Classificacao.id = Material.classificacoes_id')
              ),
              array(
                'table' => 'materiais_filiais',
                'alias' => 'MaterialFilial',
                'type' => 'LEFT',
                'conditions' => array('MaterialFilial.materiais_id = Material.id')
              ),
              array(
                'table' => 'setores',
                'alias' => 'Setor',
                'type' => 'LEFT',
                'conditions' => array('Setor.id = MaterialFilial.setor_id')
              )
            ),
            'limit' => '20',
            'order' => 'Material.data_registro desc'
          );
        }


    $this->set('materiais', $this->Paginator->paginate('Material', $option));
  }

    public function etiqueta($id = null) {

      $this->loadModel('Material');

      $this->set('materiais', $this->Material->find('first', array(
          'fields' => array('Classificacao.*', 'Material.*'),
          'conditions' => array('Material.id' => $id),
          'joins' => array(
            array(
              'table' => 'materiais_classificacoes',
              'alias' => 'Classificacao',
              'type' => 'LEFT',
              'conditions' => array('Classificacao.id = Material.classificacoes_id')
            )
          ),
          'order' => 'Material.data_registro desc'
        )));


      //$this->set('materiais', $this->Paginator->paginate('Material', $option));
    }

  public function estoque_minimo($search = null) {

      $option = array();
      if ($search) {
        $option['OR']['Material.nome LIKE'] = "%{$search}%";
        $option['OR']['Material.barcode LIKE'] = "%{$search}%";
        $option['OR']['Material.id LIKE'] = "%{$search}%";
        $option['OR']['Classificacao.descricao LIKE'] = "%{$search}%";
        $option['OR']['Material.descricao LIKE'] = "%{$search}%";
      }

      $this->loadModel('Material');
      $this->loadModel('MaterialFilial');
      $this->loadModel('Classificacao');
      $this->Material->recursive = 0;

      $sql = "SELECT Material.*, MaterialFilial.*, Classificacao.*
              FROM materiais Material
              LEFT JOIN materiais_filiais MaterialFilial on MaterialFilial.materiais_id = Material.id
              LEFT JOIN materiais_classificacoes Classificacao on Classificacao.id = Material.classificacoes_id
              WHERE MaterialFilial.est_min > MaterialFilial.quantidade and MaterialFilial.setor_id = '{$this->Session->read('Auth.User.setor')}' group by Material.id";

      if(in_array($this->Session->read('Perfil.id'), array('12'))) {
          $sql = "SELECT Material.*, MaterialFilial.*, Classificacao.*
          FROM materiais Material
          LEFT JOIN materiais_filiais MaterialFilial on MaterialFilial.materiais_id = Material.id
          LEFT JOIN materiais_classificacoes Classificacao on Classificacao.id = Material.classificacoes_id
          WHERE MaterialFilial.est_min > MaterialFilial.quantidade and MaterialFilial.setor_id = '{$this->Session->read('Auth.User.setor')}' and Classificacao.id = 13  group by Material.id";
      }

      // $this->set('observacoes',$this->Material->query($sql));
      $this->set('materiais',  $this->Material->query($sql));


      // $this->Paginator->settings = array(
      //   'fields' => array('Classificacao.descricao', 'Material.nome', 'Material.id', 'MaterialFilial.est_min', 'MaterialFilial.quantidade'),
      //   'conditions' => array('MaterialFilial.setor_id' => $this->Session->read('Auth.User.setor'), 'MaterialFilial.quantidade <' => "`MaterialFilial`.`est_min`"),
      //   'joins' => array(
      //     array(
      //       'table' => 'materiais_classificacoes',
      //       'alias' => 'Classificacaos',
      //       'type' => 'LEFT',
      //       'conditions' => array('Classificacao.id = Material.classificacoes_id')
      //     ),
      //     array(
      //       'table' => 'materiais_filiais',
      //       'alias' => 'MaterialFilial',
      //       'type' => 'LEFT',
      //       'conditions' => array('MaterialFilial.materiais_id = Material.id')
      //     )
      //   ),
      //   'limit' => '20',
      //   'order' => 'Material.data_registro desc'
      // );
      //
      // $this->set('materiais', $this->Paginator->paginate('Material', $option));

	  }

    public function separar($id = null){

      $this->layout="ajax";
      $this->loadModel('Pedido');
      // if (!$this->Remessa->exists($id)) {
      //   throw new NotFoundException(__('Invalid remessa'));
      // }


      $pedido = $this->Pedido->find('first', array('conditions' => array('Pedido.situacao' => 1),'order' => array('Pedido.id' => 'DESC')));
      $this->set('pedido', $this->Pedido->find('first', array('conditions' => array('Pedido.situacao' => 1),'order' => array('Pedido.id' => 'DESC'))));


      $this->loadModel('Material');
      $this->loadModel('Classificacao');

      // $item = $this->ItemRemessa->find('first', array('conditions' => array('ItemRemessa.remessas_id' => $id)));

      $sql = "SELECT Material.*, Classificacao.*, MaterialFilial.*
              FROM materiais Material
              LEFT JOIN materiais_filiais MaterialFilial on MaterialFilial.materiais_id = Material.id
              LEFT JOIN materiais_classificacoes Classificacao on Classificacao.id = Material.classificacoes_id
              WHERE MaterialFilial.est_min > MaterialFilial.quantidade and MaterialFilial.setor_id = {$this->Session->read('Auth.User.setor')}";


      // $this->set('observacoes',$this->Material->query($sql));
      $this->set('itens',  $this->Material->query($sql));


      $this->loadModel('ItemPedido');

      $sqlPedido = "SELECT ItemPedido.*, Material.*, MaterialFilial.*
              FROM pedidos_itens ItemPedido
              LEFT JOIN materiais Material on Material.id = ItemPedido.materiais_id
              LEFT JOIN materiais_filiais MaterialFilial on MaterialFilial.materiais_id = Material.id
              WHERE MaterialFilial.est_min > MaterialFilial.quantidade and ItemPedido.pedidos_id = {$pedido['Pedido']['id']}
              order by Material.nome ASC";

      $this->set('pedidos',  $this->ItemPedido->query($sqlPedido));




      $this->set('id', $id);

    }

    public function pedido() {
      $this->loadModel('ItemPedido');
      $this->loadModel('Material');
      if ($this->request->is('post', 'put')) {

        //pr($this->request->data);exit;

        $material = $this->request->data['ItemPedido'];

        // pr($this->request->data['Entrada']);exit;

        $this->ItemPedido->create();
        if ($this->ItemPedido->save($this->request->data['ItemPedido'])) {

            echo $this::MSG_SUCESSO_EDT;
          }
         else {
          echo $this::MSG_ERRO;
        }
      }
      exit;
    }

    public function delete_pedido($id = null) {
      // $this->loadModel('Material');
      $this->loadModel('ItemPedido');
      //$this->Entrada->id = $id;

      if ($this->request->is('post', 'delete')) {
        // pr($this->request->data);exit;
        $material = $this->request->data['ItemPedido'];
        // $material1 = $this->request->data['Entrada'];
        // pr($material);exit;


        $this->ItemPedido->delete(array('id' => $material['id']));

         $this->Session->setFlash(__($this::MSG_SUCESSO_DEL));
      } else {
          $this->Session->setFlash(__($this::MSG_ERRO));
      }

      return $this->redirect(array('action' => 'pedidos'));
    }

  public function add() {
    $this->layout="ajax";
    $this->loadModel('Material');
    $this->loadModel('MaterialFilial');

    if ($this->request->is('post')) {
      $resultado= $this->Material->find('first', array('conditions'=>array('barcode'=>$this->request->data['Material']['barcode'])));
      if($resultado){
          $this->Session->setFlash('Codigo já Existe');
           return $this->redirect(array('action' => 'index'));
      }
      $this->request->data['Material']['data_registro'] = date('d/m/Y');
      //           $resultado= $this->Material->find('first', array('conditions'=>array('barcode'=>$this->Material->barcode($this->request->data['Material']['barcode']))));
      //           if($resultado){
      //               $this->Session->setFlash('Codigo de barras já Existe');
      //                return $this->redirect(array('action' => 'add'));
      //           }
      $this->Material->create();
      if ($result = $this->Material->save($this->request->data)) {
        $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));

        //$this->Material->updateAll(array('quantidade_central' => $this->request->data['Material']['quantidade']), array('id' => $result['Material']['id']));

        if (!empty($this->request->data)) {
          $material = $this->request->data['Material'];
          $this->MaterialFilial->create();
          $this->MaterialFilial->save(array('MaterialFilial'=>array(
            'materiais_id' => $result['Material']['id'],
            'setor_id' => 1,
            'quantidade' => $material['quantidade'],
            'est_min' => $material['est_min'],
            'prateleira' => $material['prateleira'],
          )));
          // $this->MaterialFilial->create();
          for ($i = 2; ; $i++) {
            if ($i > 9) {

                break;
            }
          $this->MaterialFilial->create();
          $this->MaterialFilial->save(array('MaterialFilial'=>array(
            'materiais_id' => $result['Material']['id'],
            'setor_id' => $i,
            'quantidade' => 0,
            'est_min' => 0,
          )));
        }
          // $this->Upload->upload($this->request->data['Material']['uploadfile']);

        // return $this->redirect(array('action' => 'upload'));
        return $this->redirect(array('action' => 'index'));
        }
      } else {
        $this->Session->setFlash(__($this::MSG_ERRO));
      }
    }

    $this->loadModel('MaterialClassificacao');
    $classificacoes = $this->MaterialClassificacao->find('list', array('fields' => array('id', 'descricao'), 'order' => 'descricao ASC'));
    $this->loadModel('Localizacao');
    $localizacoes = $this->Localizacao->find('list', array('fields' => array('id', 'descricao'), 'order' => 'descricao ASC'));
    $this->set(compact('materiais', 'classificacoes', 'localizacoes'));

  }

  public function edit($id = null) {
    $this->layout="ajax";
    $this->loadModel('Material');
    $this->loadModel('MaterialFilial');

    if (!$this->Material->exists($id)) {
      throw new NotFoundException(__('Invalid fornecedor'));
    }

    if ($this->request->is(array('post', 'put'))) {
      $mat = $this->request->data['Material']['prateleira'];
      $this->MaterialFilial->updateAll(array('quantidade' => $this->request->data['Material']['quantidade']), array('materiais_id' => $this->request->data['Material']['id'], 'setor_id' =>$this->Session->read('Auth.User.setor')));
      $this->MaterialFilial->updateAll(array('est_min' => $this->request->data['Material']['est_min']), array('materiais_id' => $this->request->data['Material']['id'], 'setor_id' =>$this->Session->read('Auth.User.setor')));
      $this->MaterialFilial->updateAll(array('prateleira' => "'$mat'"), array('materiais_id' => $this->request->data['Material']['id'], 'setor_id' =>$this->Session->read('Auth.User.setor')));

      if(!in_array($this->Session->read('Perfil.id'), array('2', '3','12'))) {
        $this->Material->updateAll(array('quantidade_central' => $this->request->data['Material']['quantidade']), array('id' => $this->request->data['Material']['id']));
      }

      if ($this->Material->save($this->request->data)) {
        $this->Session->setFlash(__($this::MSG_SUCESSO_EDT));
        return $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__($this::MSG_ERRO));
      }
    } else {
      $options = array('conditions' => array('Material.' . $this->Material->primaryKey => $id));
      $this->request->data = $this->Material->find('first', $options);
    }

    $this->loadModel('MaterialClassificacao');
    $classificacoes = $this->MaterialClassificacao->find('list', array('fields' => array('id', 'descricao'), 'order' => 'descricao ASC'));
    $this->set('filiais',$this->MaterialFilial->find('first', array('conditions' => array('materiais_id' => $id, 'setor_id' => $this->Session->read('Auth.User.setor')))));
    $this->set('itens', $this->Material->find('all', array('conditions' => array('id' => $id))));
    $this->set(compact('materiais', 'classificacoes'));
  }

  public function entrada($id = null) {
    $this->loadModel('Material');
    $this->loadModel('Entrada');
    $this->loadModel('MaterialFilial');

     // pr($this->request->data);exit;
    if ($this->request->is('post')) {
       //pr($this->request->data);exit;
      $this->Entrada->create();
      if ($result = $this->Entrada->save($this->request->data)) {
        $quantidade = $this->request->data['Entrada']['quantidade_estoque'] + $this->request->data['Entrada']['quantidade_entrada'];
        $this->MaterialFilial->updateAll(array('quantidade' => $quantidade), array('materiais_id' => $this->request->data['Entrada']['materiais_id'], 'setor_id' =>$this->Session->read('Auth.User.setor')));
        if(!in_array($this->Session->read('Perfil.id'), array('2', '3', '12'))) {
            $this->Material->updateAll(array('quantidade_central' => $quantidade), array('id' => $this->request->data['Entrada']['materiais_id']));
        }
        if(in_array($this->Session->read('Auth.User.id'), array('4546'))) {
            $this->Material->updateAll(array('quantidade_central' => $quantidade), array('id' => $this->request->data['Entrada']['materiais_id']));
        }

        $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
        return $this->redirect(array('action' => 'entrada'));
      } else {
        $this->Session->setFlash(__($this::MSG_ERRO));
      }
    } else {
      $options['OR']['id'] = $id;
      // if($id) {
      //   $options['AND']['setor_id'] = $this->Session->read('Auth.User.setor');
      // }

      // pr($options);exit;

      // $material = $this->Material->find('first', array('conditions' => $options));
      $materialfilial = $this->MaterialFilial->find('first', array('conditions' => $options, 'MaterialFilial.setor_id'=>$this->Session->read('Auth.User.setor')));
      $material = $this->MaterialFilial->find('first', array('conditions' => array('MaterialFilial.materiais_id'=>$id,'MaterialFilial.setor_id' => $this->Session->read('Auth.User.setor'))));
      // pr($materialfilial);exit;

      if($materialfilial) {
                   // pr($material);exit;
        // $this->request->data['Entrada']['barcode']=$material['Material']['barcode'];
        $this->request->data['Entrada']['materiais_id']=$material['MaterialFilial']['materiais_id'];
        $this->request->data['Entrada']['quantidade_estoque']=$material['MaterialFilial']['quantidade'];
                   // pr($material);exit;
      }
    }

    // pr($material);exit;

    $this->loadModel('Material');
      // $this->Entrada->recursive = 0;
      // $this->Paginator->settings = array(
      //   'fields' => array('Entrada.*', 'Material.nome', 'Usuario.nome','MaterialFilial.*'),
      //   'conditions' => array('Entrada.setor_id' => $this->Session->read('Auth.User.setor')),
      //   'joins' => array(
      //     array(
      //       'table' => 'materiais',
      //       'alias' => 'Material',
      //       'type' => 'LEFT',
      //       'conditions' => array('Material.id = Entrada.materiais_id')
      //     ),
      //     array(
      //       'table' => 'admin.usuarios',
      //       'alias' => 'Usuario',
      //       'type' => 'LEFT',
      //       'conditions' => array('Usuario.id = Entrada.usuarios_id')
      //     ),
      //     array(
      //       'table' => 'materiais_filiais',
      //       'alias' => 'MaterialFilial',
      //       'type' => 'LEFT',
      //       'conditions' => array('MaterialFilial.materiais_id = Material.id')
      //     )
      //   ),
      //   'order' => array('Entrada.data_entrada' => 'DESC'),
      //   'group' => array('Entrada.id'),
      //   'limit' => '6'
      // );
    $this->set('entradas', $this->Paginator->paginate('Entrada'));
    if(in_array($this->Session->read('Perfil.id'), array('12'))) {
      $this->set('materiais', $this->Material->find('list', array('conditions' => array('Material.classificacoes_id' => 13),'fields' => array('id', 'nome'), 'order' => 'nome ASC')));
    }else{
      $this->set('materiais', $this->Material->find('list', array('fields' => array('id', 'nome'), 'order' => 'nome ASC')));
    }
    //$this->set('materiais1', $this->Material->find('all', array('fields' => array('id', 'nome', 'barcode'), 'order' => 'nome ASC')));

    // $this->set('material2', $this->Material->find('list', array(
    //   'fields' => array('Material.id','Material.nome', 'MaterialFilial.*'),
    //   'conditions' => array('MaterialFilial.setor_id' => '3'),
    //   'joins' => array(
    //     array(
    //       'table' => 'materiais_filiais',
    //       'alias' => 'MaterialFilial',
    //       'type' => 'LEFT',
    //
    //       'conditions' => array('MaterialFilial.materiais_id = Material.id')
    //     )
    //   ),
    //   'order' => 'nome ASC')));


    // pr($material2);exit;

    // $this->loadModel('Material');
    // $this->set('itens', $this->Material->find('all', array('conditions' => array('id' => $material['Material']['id']))));
           // pr($this->request->data);exit;
  }

  // public function entrada($id = null) {
  //   $this->loadModel('Material');
  //   $this->loadModel('Entrada');
  //    // pr($this->request->data);exit;
  //   if ($this->request->is('post')) {
  //      //pr($this->request->data);exit;
  //     $this->Entrada->create();
  //     if ($result = $this->Entrada->save($this->request->data)) {
  //       $quantidade = $this->request->data['Entrada']['quantidade_estoque'] + $this->request->data['Entrada']['quantidade_entrada'];
  //       $this->Material->updateAll(array('quantidade' => $quantidade), array('id' => $this->request->data['Entrada']['materiais_id']));
  //       $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
  //       return $this->redirect(array('action' => 'entrada'));
  //     } else {
  //       $this->Session->setFlash(__($this::MSG_ERRO));
  //     }
  //   } else {
  //     $options['OR']['id'] = $id;
  //     if($id) {
  //       $options['OR']['barcode'] = $id;
  //     }
  //     // pr($this->request->data);exit;
  //
  //     $material = $this->Material->find('first', array('conditions' => $options));
  //     if($material) {
  //                  // pr($material);exit;
  //       $this->request->data['Entrada']['barcode']=$material['Material']['barcode'];
  //       $this->request->data['Entrada']['materiais_id']=$material['Material']['id'];
  //       $this->request->data['Entrada']['quantidade_estoque']=$material['Material']['quantidade'];
  //                  // pr($material);exit;
  //     }
  //   }
  //
  //   // pr($material);exit;
  //
  //   $this->loadModel('Material');
  //   $this->Entrada->recursive = 0;
  //   $this->Paginator->settings = array(
  //     'fields' => array('Entrada.*', 'Material.nome', 'Usuario.nome'),
  //     'joins' => array(
  //       array(
  //         'table' => 'materiais',
  //         'alias' => 'Material',
  //         'type' => 'LEFT',
  //         'conditions' => array('Material.id = Entrada.materiais_id')
  //       ),
  //       array(
  //         'table' => 'admin.usuarios',
  //         'alias' => 'Usuario',
  //         'type' => 'LEFT',
  //         'conditions' => array('Usuario.id = Entrada.usuarios_id')
  //       )
  //     ),
  //     'order' => array('Entrada.data_entrada' => 'DESC'),
  //     'limit' => '6'
  //   );
  //   $this->set('entradas', $this->Paginator->paginate('Entrada'));
  //   $this->set('materiais', $this->Material->find('list', array('fields' => array('id', 'nome'), 'order' => 'nome ASC')));
  //   // pr($material);exit;
  //
  //   // $this->loadModel('Material');
  //   // $this->set('itens', $this->Material->find('all', array('conditions' => array('id' => $material['Material']['id']))));
  //          // pr($this->request->data);exit;
  // }

  public function saida($id = null) {
    $this->loadModel('Material');
    $this->loadModel('Saida');
    $this->loadModel('MaterialFilial');

    if ($this->request->is('post')) {
                 // pr($this->request->data);exit;
      $qs =  $this->request->data['Saida']['quantidade_saida'];
      $qe =  $this->request->data['Saida']['quantidade_estoque'];

      if($qe - $qs < 0 ){
        $this->Session->setFlash('Quantidade insuficiente em estoque!!!');
        return $this->redirect(array('action' => 'saida'));
      }elseif ($qs <= 0) {
        $this->Session->setFlash('Quantidade de saida nao deve ser menor ou igual a zero!!!');
        return $this->redirect(array('action' => 'saida'));
      }


      $this->Saida->create();
      if ($result = $this->Saida->save($this->request->data)) {

        $quantidade = $this->request->data['Saida']['quantidade_estoque'] - $this->request->data['Saida']['quantidade_saida'];
        $this->MaterialFilial->updateAll(array('quantidade' => $quantidade), array('materiais_id' => $this->request->data['Saida']['materiais_id'], 'setor_id' =>$this->Session->read('Auth.User.setor')));
        if(!in_array($this->Session->read('Perfil.id'), array('2', '3', '12'))) {
            $this->Material->updateAll(array('quantidade_central' => $quantidade), array('id' => $this->request->data['Saida']['materiais_id']));
        }
        if(in_array($this->Session->read('Auth.User.id'), array('4546'))) {
            $this->Material->updateAll(array('quantidade_central' => $quantidade), array('id' => $this->request->data['Saida']['materiais_id']));
        }

        $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
        return $this->redirect(array('action' => 'saida'));
      } else {
        $this->Session->setFlash(__($this::MSG_ERRO));
      }
    } else {
      $options['OR']['id'] = $id;
      // if($id) {
      //   $options['OR']['barcode'] = $id;
      // }

      // $material = $this->Material->find('first', array('conditions' => $options));
      // $materialfilial = $this->MaterialFilial->find('first', array('conditions' => $options, 'MaterialFilial.setor_id'=>$this->Session->read('Auth.User.setor')));
      $material = $this->MaterialFilial->find('first', array('conditions' => array('MaterialFilial.materiais_id'=>$id,'MaterialFilial.setor_id' => $this->Session->read('Auth.User.setor'))));
      if($material){
        //            pr($material);exit;
        //$this->request->data['Saida']['barcode']=$material['Material']['barcode'];
        $this->request->data['Saida']['materiais_id']=$material['MaterialFilial']['materiais_id'];
        $this->request->data['Saida']['quantidade_estoque']=$material['MaterialFilial']['quantidade'];
        //            pr($this->request->data);exit;
      }


    }

    $this->loadModel('Localizacao');
    $this->loadModel('Patrimonio');
    $this->loadModel('Material');
    $this->loadModel('Equipamento');
    // $this->Saida->recursive = 0;
    // $this->Paginator->settings = array(
    //   'fields' => array('Saida.*', 'Material.nome', 'Localizacao.descricao', 'Usuario.nome','MaterialFilial.*'),
    //   // 'conditions' => array("Saida.data_saida BETWEEN '2019-06-12' AND '2019-06-27'" ),
    //   'conditions' => array('Saida.setor_id' => $this->Session->read('Auth.User.setor')),
    //   'joins' => array(
    //     array(
    //       'table' => 'materiais',
    //       'alias' => 'Material',
    //       'type' => 'LEFT',
    //       'conditions' => array('Material.id = Saida.materiais_id')
    //     ),
    //     array(
    //       'table' => 'localizacoes',
    //       'alias' => 'Localizacao',
    //       'type' => 'LEFT',
    //       'conditions' => array('Localizacao.id = Saida.localizacoes_id')
    //     ),
    //     array(
    //       'table' => 'admin.usuarios',
    //       'alias' => 'Usuario',
    //       'type' => 'LEFT',
    //       'conditions' => array('Usuario.id = Saida.usuarios_id')
    //     ),
    //     array(
    //       'table' => 'materiais_filiais',
    //       'alias' => 'MaterialFilial',
    //       'type' => 'LEFT',
    //       'conditions' => array('MaterialFilial.materiais_id = Material.id')
    //     )
    //   ),
    //   'order' => array('Saida.data_saida' => 'DESC'),
    //   'group' => array('Saida.id'),
    //   'limit' => '6'
    // );
    $this->set('saidas', $this->Paginator->paginate('Saida'));
    if(in_array($this->Session->read('Perfil.id'), array('12'))) {
      $this->set('materiais', $this->Material->find('list', array('conditions' => array('Material.classificacoes_id' => 13),'fields' => array('id', 'nome'), 'order' => 'nome ASC')));
    }else{
      $this->set('materiais', $this->Material->find('list', array('fields' => array('id', 'nome'), 'order' => 'nome ASC')));
    }
    //$this->set('materiais1', $this->Material->find('first', array('fields' => array('id', 'nome'), 'conditions'=>array('id'=>($this->request->data['Saida']['materiais_id'])))));
    $this->set('localizacoes', $this->Localizacao->find('list', array('fields' => array('id', 'descricao'), 'order' => 'descricao ASC')));
    $this->set('equipamentos', $this->Equipamento->find('list', array('fields' => array('id', 'descricao'), 'order' => 'id ASC')));
    //$this->set('patrimonios', $this->Patrimonio->find('list', array('fields' => array('id', 'descricao'), 'order' => 'descricao ASC')));

  }

  public function pesquisarbarcode($barcode){
    //        pr('1');exit;
    $this->loadModel('Material');
    $resultado= $this->Material->find('first', array('fields'=> array('id','nome', 'quantidade'),'conditions'=>array('barcode'=>($barcode))));
    //           pr($barcode);exit;
    echo json_encode($resultado);
    exit;
  }

  public function pesquisarproduto($produto){
    //        pr('1');exit;
    $this->loadModel('Material');
    $resultado= $this->Material->find('first', array('fields'=> array('nome', 'quantidade'),'conditions'=>array('id'=>($produto))));
    //           pr($produto);exit;
    echo json_encode($resultado);
    exit;
  }

  public function importar() {
    $errors = array();
    $this->layout="ajax";
    $this->loadModel('Material');

    if ($this->request->is('post')) {
      if ($this->request->data['xml']['error']) {
        $this->Session->setFlash(__('Falha no upload'));
        return $this->redirect(array('action' => 'index'));
      }

      $path = '/var/www/html/uploads/patrimonio/xml';
      $file = $this->request->data['xml']['name'];

      if (!move_uploaded_file($this->request->data['xml']['tmp_name'], "{$path}/{$file}")) {
        $this->Session->setFlash(__('Falha no upload'));
        return $this->redirect(array('action' => 'index'));
      }

      if (!file_exists("{$path}/{$file}")) {
        $this->Session->setFlash(__('XML não localizado'));
        return $this->redirect(array('action' => 'index'));
      }

      $xml = "{$path}/{$file}";
      $xmldata = simplexml_load_file($xml) or die("Failed to load");
      $nota = json_decode(json_encode($xmldata->NFe->infNFe->ide->children()));
      $itens = json_decode(json_encode($xmldata->NFe->infNFe->children()));

      foreach ($itens->det as $key => $value) {
        $produto = $this->Material->find('first', array('fields'=> array('id', 'barcode', 'nome', 'quantidade'),'conditions'=>array('barcode'=>$value->prod->cEAN)));
        if(isset($produto['Material']['id'])) {
          $datetime = date('Y-m-d H:i:s', strtotime($nota->dhEmi));
          $this->Material->updateAll(array(
            'quantidade' => $produto['Material']['quantidade'] + (int) $value->prod->qCom,
            'descricao' => "'{$value->prod->xProd}'",
            'data_registro' => "'{$datetime}'"
          ), array(
            'id' => $produto['Material']['id'],
            "data_registro < '{$datetime}'"
          ));
        } else {
          $errors[] = $value->prod->xProd;
        }
      }

      if(!$errors) {
        $this->Session->setFlash(__('Produtos atualizados com sucesso!'));
      } else {
        $this->Session->setFlash(__('Produtos não encontrado<br/>!' . implode('<br/>', $errors)));
      }

      return $this->redirect(array('action' => 'index'));
    }
  }



  public function upload() {
      if (!empty($this->request->data)) {
        $this->Upload->upload($this->request->data['Material']['uploadfile']);
      }
  }

  public function consultabarcode($barcode){
      $this->loadModel('Material');
       $resultado= $this->Material->find('first', array('fields'=> array('id'),'conditions'=>array('barcode'=>$barcode)));

         echo json_encode($resultado);
         exit;
  }

  public function saida_central() {
    $this->loadModel('Saida');

    if ($this->request->is('post')) {

      $inicio = $this->date($this->request->data['material']['inicio'][0]);
      $final = $this->date($this->request->data['material']['final'][0]);
       // pr($mat);exit;

      $this->set('saidas', $this->Saida->find('all', array(
        'conditions' => array("Saida.data_saida BETWEEN '$inicio' AND '$final' AND Saida.setor_id = '1'"),
        'fields' => array('Localizacao.*', 'Saida.*','Material.*','MaterialClassificacao.*'),
        'joins' => array(
            array(
                'table' => 'localizacoes',
                'alias' => 'Localizacao',
                'type' => 'LEFT',
                'conditions' => array('Localizacao.id = Saida.localizacoes_id')
            ),array(
                'table' => 'materiais',
                'alias' => 'Material',
                'type' => 'LEFT',
                'conditions' => array('Material.id = Saida.materiais_id')
            ),array(
                'table' => 'materiais_classificacoes',
                'alias' => 'MaterialClassificacao',
                'type' => 'LEFT',
                'conditions' => array('MaterialClassificacao.id = Material.classificacoes_id')
            )
        ),
        'order' => 'Material.nome asc'
      )));

      // pr($this->request->data);exit;
    }
    // $this->set('saidas', $this->Saida->find('all', array(
    //   'conditions' => array("Saida.data_saida BETWEEN '2020-02-01' AND '2020-02-29'"),
    //   'fields' => array('Localizacao.*', 'Saida.*','Material.*','MaterialClassificacao.*'),
    //   'joins' => array(
    //       array(
    //           'table' => 'localizacoes',
    //           'alias' => 'Localizacao',
    //           'type' => 'LEFT',
    //           'conditions' => array('Localizacao.id = Saida.localizacoes_id')
    //       ),array(
    //           'table' => 'materiais',
    //           'alias' => 'Material',
    //           'type' => 'LEFT',
    //           'conditions' => array('Material.id = Saida.materiais_id')
    //       ),array(
    //           'table' => 'materiais_classificacoes',
    //           'alias' => 'MaterialClassificacao',
    //           'type' => 'LEFT',
    //           'conditions' => array('MaterialClassificacao.id = Material.classificacoes_id')
    //       )
    //   ),
    //   'order' => 'Material.nome asc'
    // )));
  }

  public function saida_relatorio() {

    $this->loadModel('Saida');

    if ($this->request->is('post')) {
      // pr($this->request->data);exit;

      $inicio = $this->date($this->request->data['material']['inicio'][0]);
      $final = $this->date($this->request->data['material']['final'][0]);
      $setor = $this->request->data['material']['setor'][0];
       // pr($mat);exit;

      $this->set('saidas', $this->Saida->find('all', array(
        'conditions' => array("Saida.data_saida BETWEEN '$inicio' AND '$final' AND Saida.setor_id = '1' AND Saida.localizacoes_id = $setor"),
        'fields' => array('Localizacao.*', 'Saida.*','Material.*','MaterialClassificacao.*'),
        'joins' => array(
            array(
                'table' => 'localizacoes',
                'alias' => 'Localizacao',
                'type' => 'LEFT',
                'conditions' => array('Localizacao.id = Saida.localizacoes_id')
            ),array(
                'table' => 'materiais',
                'alias' => 'Material',
                'type' => 'LEFT',
                'conditions' => array('Material.id = Saida.materiais_id')
            ),array(
                'table' => 'materiais_classificacoes',
                'alias' => 'MaterialClassificacao',
                'type' => 'LEFT',
                'conditions' => array('MaterialClassificacao.id = Material.classificacoes_id')
            )
        ),
        'order' => 'Material.nome asc'
      )));

      // pr($this->request->data);exit;
    }


      $this->loadModel('Setor');
      $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));

      // $this->loadModel('Setor');
      // $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), array('conditions' => array('id' => $this->Session->read('Auth.User.setor'))))));

  }

  public function entrada_central() {
    $this->loadModel('Entrada');

    if ($this->request->is('post')) {

      $inicio = $this->date($this->request->data['material']['inicio'][0]);
      $final = $this->date($this->request->data['material']['final'][0]);
       // pr($mat);exit;

       $this->set('entradas', $this->Entrada->find('all', array(
         'conditions' => array("Entrada.data_entrada BETWEEN '$inicio' AND '$final' AND Entrada.setor_id = '1'"),
         'fields' => array('Usuario.nome', 'Entrada.*','Material.*','MaterialClassificacao.*'),
         'joins' => array(
           array(
             'table' => 'admin.usuarios',
             'alias' => 'Usuario',
             'type' => 'LEFT',
             'conditions' => array('Usuario.id = Entrada.usuarios_id')
           ),array(
                 'table' => 'materiais',
                 'alias' => 'Material',
                 'type' => 'LEFT',
                 'conditions' => array('Material.id = Entrada.materiais_id')
             ),array(
                 'table' => 'materiais_classificacoes',
                 'alias' => 'MaterialClassificacao',
                 'type' => 'LEFT',
                 'conditions' => array('MaterialClassificacao.id = Material.classificacoes_id')
             )
         ),
         'order' => 'Material.nome asc'
       )));

      // $this->set('entradas', $this->Entrada->find('all', array(
      //   'conditions' => array("Entrada.data_entrada BETWEEN '$inicio' AND '$final' AND Entrada.setor_id = '1'"),
      //   'fields' => array('Localizacao.*', 'Entrada.*','Material.*','MaterialClassificacao.*'),
      //   'joins' => array(
      //       array(
      //           'table' => 'localizacoes',
      //           'alias' => 'Localizacao',
      //           'type' => 'LEFT',
      //           'conditions' => array('Localizacao.id = Entrada.localizacoes_id')
      //       ),array(
      //           'table' => 'materiais',
      //           'alias' => 'Material',
      //           'type' => 'LEFT',
      //           'conditions' => array('Material.id = Saida.materiais_id')
      //       ),array(
      //           'table' => 'materiais_classificacoes',
      //           'alias' => 'MaterialClassificacao',
      //           'type' => 'LEFT',
      //           'conditions' => array('MaterialClassificacao.id = Material.classificacoes_id')
      //       )
      //   ),
      //   'order' => 'Material.nome asc'
      // )));

      // pr($this->request->data);exit;
    }

  }

  public function relatorio_filialentrada() {
    $this->loadModel('Entrada');

    if ($this->request->is('post')) {

      $setor = $this->Session->read('Auth.User.setor');
      $inicio = $this->date($this->request->data['material']['inicio'][0]);
      $final = $this->date($this->request->data['material']['final'][0]);
       // pr($mat);exit;

       $this->set('entradas', $this->Entrada->find('all', array(
         'conditions' => array("Entrada.data_entrada BETWEEN '$inicio' AND '$final' AND Entrada.setor_id = '$setor'"),
         'fields' => array('Usuario.nome', 'Entrada.*','Material.*','MaterialClassificacao.*'),
         'joins' => array(
           array(
             'table' => 'admin.usuarios',
             'alias' => 'Usuario',
             'type' => 'LEFT',
             'conditions' => array('Usuario.id = Entrada.usuarios_id')
           ),array(
                 'table' => 'materiais',
                 'alias' => 'Material',
                 'type' => 'LEFT',
                 'conditions' => array('Material.id = Entrada.materiais_id')
             ),array(
                 'table' => 'materiais_classificacoes',
                 'alias' => 'MaterialClassificacao',
                 'type' => 'LEFT',
                 'conditions' => array('MaterialClassificacao.id = Material.classificacoes_id')
             )
         ),
         'order' => 'Material.nome asc'
       )));

    }

  }

  public function relatorio_filialsaida() {
    $this->loadModel('Saida');

    if ($this->request->is('post')) {

      $setor = $this->Session->read('Auth.User.setor');
      $inicio = $this->date($this->request->data['material']['inicio'][0]);
      $final = $this->date($this->request->data['material']['final'][0]);
       // pr($mat);exit;

      $this->set('saidas', $this->Saida->find('all', array(
        'conditions' => array("Saida.data_saida BETWEEN '$inicio' AND '$final' AND Saida.setor_id = '$setor'"),
        'fields' => array('Localizacao.*', 'Saida.*','Material.*','MaterialClassificacao.*'),
        'joins' => array(
            array(
                'table' => 'localizacoes',
                'alias' => 'Localizacao',
                'type' => 'LEFT',
                'conditions' => array('Localizacao.id = Saida.localizacoes_id')
            ),array(
                'table' => 'materiais',
                'alias' => 'Material',
                'type' => 'LEFT',
                'conditions' => array('Material.id = Saida.materiais_id')
            ),array(
                'table' => 'materiais_classificacoes',
                'alias' => 'MaterialClassificacao',
                'type' => 'LEFT',
                'conditions' => array('MaterialClassificacao.id = Material.classificacoes_id')
            )
        ),
        'order' => 'Material.nome asc'
      )));

      // pr($this->request->data);exit;
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

  public function saldo_anterior() {
    $this->layout="ajax";
    $this->loadModel('SaldoAnterior');

    if ($this->request->is('post')) {
      // pr($this->request->data);exit;
      $this->SaldoAnterior->create();
      if ($result = $this->SaldoAnterior->save($this->request->data)) {


        $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
        return $this->redirect(array('action' => 'index'));
      }else {
        $this->Session->setFlash(__($this::MSG_ERRO));
      }
    }

  }


}
