<?php
App::uses('AppController', 'Controller');
require_once(ROOT . DS . 'patrimonio' . DS . 'Vendor' . DS . 'mpdf60' . DS . 'mpdf.php');

class PedidosController extends AppController {

  public $components = array('Paginator');

  public function index($search = null) {
    $option = array();
    if ($search) {
      $option['OR']['Pedido.id'] = "{$search}";
      $option['OR']['Setor.descricao like'] = "%{$search}%";
      $option['OR']['ItemPedido.materiais_id'] = "{$search}";
    }

    $this->loadModel('Pedido');

    $options = array('Setor.id IS NULL');
    if($this->Session->read('Auth.User.setor') && in_array($this->Session->read('Perfil.id'), array('8'))) {
      $options['Setor.id'] = $this->Session->read('Auth.User.setor');
      unset($options[0]);
    } else if(!in_array($this->Session->read('Perfil.id'), array('8'))) {
      unset($options[0]);
    }

    if(in_array($this->Session->read('Perfil.id'), array('12'))) {
    $this->Pedido->recursive = 0;
    $this->Paginator->settings = array(
      'conditions' => array('Pedido.setores_id' => $this->Session->read('Auth.User.setor'), 'Pedido.solicitante' =>$this->Session->read('Auth.User.id')),
      'fields' => array('Pedido.*','Setor.descricao','Usuario.nome', 'Material.nome', 'ItemPedido.materiais_id'),
      'joins' => array(
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'INNER',
          'conditions' => array('Setor.id = Pedido.setores_id')
        ),
        array(
          'table' => 'admin.usuarios',
          'alias' => 'Usuario',
          'type' => 'INNER',
          'conditions' => array('Usuario.id = Pedido.usuarios_id')
        ),
        array(
          'table' => 'pedidos_itens',
          'alias' => 'ItemPedido',
          'type' => 'LEFT',
          'conditions' => array('ItemPedido.pedidos_id = Pedido.id')
        ),
        array(
          'table' => 'materiais',
          'alias' => 'Material',
          'type' => 'LEFT',
          'conditions' => array('Material.id = ItemPedido.materiais_id')
        )
      ),
      'group' => 'Pedido.id',
      'order' => "FIELD(Pedido.situacao, '1','3','2','4'), Pedido.data_hora_registro desc"
      //'order' => array('Pedido.id' => 'DESC')
    );

  }

    else if(!in_array($this->Session->read('Perfil.id'), array('2', '3'))) {
    $this->Pedido->recursive = 0;
    $this->Paginator->settings = array(
      'conditions' => array('Pedido.aprovacao' => 1),
      'fields' => array('Pedido.*','Setor.descricao','Usuario.nome', 'Material.nome', 'ItemPedido.materiais_id'),
      'joins' => array(
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'INNER',
          'conditions' => array('Setor.id = Pedido.setores_id')
        ),
        array(
          'table' => 'admin.usuarios',
          'alias' => 'Usuario',
          'type' => 'INNER',
          'conditions' => array('Usuario.id = Pedido.usuarios_id')
        ),
        array(
          'table' => 'pedidos_itens',
          'alias' => 'ItemPedido',
          'type' => 'LEFT',
          'conditions' => array('ItemPedido.pedidos_id = Pedido.id')
        ),
        array(
          'table' => 'materiais',
          'alias' => 'Material',
          'type' => 'LEFT',
          'conditions' => array('Material.id = ItemPedido.materiais_id')
        )
      ),
      'group' => 'Pedido.id',
      'order' => "FIELD(Pedido.situacao, '1','3','2','4'), Pedido.data_hora_registro desc"
      //'order' => array('Pedido.id' => 'DESC')
    );

  }else {
    $this->Pedido->recursive = 0;
    $this->Paginator->settings = array(
      'conditions' => array('Pedido.setores_id' => $this->Session->read('Auth.User.setor')),
      'fields' => array('Pedido.*','Setor.descricao','Usuario.nome'),
      'joins' => array(
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'INNER',
          'conditions' => array('Setor.id = Pedido.setores_id')
        ),
        array(
          'table' => 'admin.usuarios',
          'alias' => 'Usuario',
          'type' => 'INNER',
          'conditions' => array('Usuario.id = Pedido.usuarios_id')
        ),
        array(
          'table' => 'pedidos_itens',
          'alias' => 'ItemPedido',
          'type' => 'LEFT',
          'conditions' => array('ItemPedido.pedidos_id = Pedido.id')
        ),
        array(
          'table' => 'materiais',
          'alias' => 'Material',
          'type' => 'LEFT',
          'conditions' => array('Material.id = ItemPedido.materiais_id')
        )
      ),
      'group' => 'Pedido.id',
      'order' => "FIELD(Pedido.situacao, '1','3','2','4'), Pedido.data_hora_registro desc"
      //'order' => array('Pedido.id' => 'DESC')
    );
  }

    $this->set('pedidos', $this->Paginator->paginate('Pedido', $option));
  }

  public function add($id = null) {
    $this->layout="ajax";

    $this->loadModel('Pedido');
    if ($this->request->is('post')) {
      // pr($this->request->data); exit;
      $material = $this->request->data['material'];
      unset($this->request->data['material']);
      $this->Pedido->create();

      if ($r = $this->Pedido->save($this->request->data)) {
        if($material) {
          $this->loadModel('ItemPedido');
          foreach ($material['id'] as $key => $value) {
            if(!$material['id'][$key] && !$material['pedido'][$key]) {
              continue;
            }

            $this->ItemPedido->create();
            $this->ItemPedido->save(array('ItemPedido'=>array(
              'quantidade_pedido' => $material['pedido'][$key],
              'materiais_id' => $material['id'][$key],
              'pedidos_id' => $r['Pedido']['id'],
              'aplicacao' => $material['aplicacao'][$key],
              'equipamentos_id' => $material['equipamentos_id'][$key],
              'situacao' => '1',
            )));
          }
        }

        $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
      } else {
        $this->Session->setFlash(__($this::MSG_ERRO));
      }

      return $this->redirect(array('action' => 'index'));
    }

    $this->loadModel('Material');
    if(in_array($this->Session->read('Perfil.id'), array('12'))) {
      $this->set('materiais', $this->Material->find('all', array('conditions' => array('Material.classificacoes_id' => 13),'fields' => array('id', 'nome', 'barcode'), 'order'=>array('nome'=>'asc'))));
    }else {
      $this->set('materiais', $this->Material->find('all', array('fields' => array('id', 'nome', 'barcode'), 'order'=>array('nome'=>'asc'))));
    }


    $this->loadModel('Setor');
    $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));
    $this->set('id', $id);

    $this->loadModel('Equipamento');
    $this->set('equipamentos', $this->Equipamento->find('all', array('fields' => array('id', 'descricao'), 'order' => 'id ASC')));
  }

  public function edit_teste($id = null) {
    $this->layout="ajax";
    $this->loadModel('Pedido');
    $this->loadModel('MaterialFilial');
    if (!$this->Pedido->exists($id)) {
        throw new NotFoundException(__('Invalid pedido'));
    }

    if ($this->request->is(array('post', 'put'))) {
      //pr($this->request->data); exit;
      $material = $this->request->data['material'];
      unset($this->request->data['material']);

      //pr($material); exit;

      if ($r = $this->Pedido->save($this->request->data)) {
        if($material) {
          $this->loadModel('ItemPedido');
          $this->ItemPedido->deleteAll(array('pedidos_id' => $r['Pedido']['id']));
          foreach ($material['id'] as $key => $value) {
            if(!$material['id'][$key] && !$material['pedido'][$key]) {
                  continue;
            }

            // if($material['fornecido'][$key]) {
            //   $this->loadModel('Material');
            //   $num = 0;
            //   $m = $this->MaterialFilial->find('first', array('conditions' => array('materiais_id' => $material['id'][$key], $this->Session->read('Auth.User.setor'))));
            //
            //   //if($m['MaterialFilial']['quantidade'] - $material['fornecido'][$key] < 0 ){
            //     //$this->Session->setFlash('Quantidade insuficiente em estoque!!!');
            //     //return $this->redirect(array('action' => 'index'));
            //   //}
            //
            //   $mc = $this->Material->find('first', array('conditions' => array('id' => $material['id'][$key])));
            //   $i = $this->ItemPedido->find('first', array('conditions' => array('id' => $material['item'][$key])));
            //   if(isset($i['ItemPedido']['quantidade_fornecido'])) {
            //     $num = $i['ItemPedido']['quantidade_fornecido'];
            //   }
            //
            //
            //   $this->MaterialFilial->updateAll(array('quantidade' => (($num + $m['MaterialFilial']['quantidade']) - $material['fornecido'][$key])), array('materiais_id' => $m['MaterialFilial']['materiais_id'],'setor_id' => $this->Session->read('Auth.User.setor') ));
            //   $this->Material->updateAll(array('quantidade_central' => (($num + $mc['Material']['quantidade_central']) - $material['fornecido'][$key])), array('id' => $mc['Material']['id']));
            //
            //   $this->loadModel('Saida');
            //   $this->Saida->create();
            //   $this->Saida->deleteAll(array('pedidos_id' => $r['Pedido']['id'], 'materiais_id' => $material['id'][$key]), false);
            //   $this->Saida->save(array('Saida'=>array(
            //     'data_saida' => date('d/m/Y'),
            //     'materiais_id' => $material['id'][$key],
            //     'quantidade_saida' => $material['fornecido'][$key],
            //     'usuarios_id' => $r['Pedido']['usuarios_id'],
            //     'localizacoes_id' => $r['Pedido']['setores_id'],
            //     'aplicacao' => $material['aplicacao'][$key],
            //     'pedidos_id' => $r['Pedido']['id'],
            //     'setor_id' => $this->Session->read('Auth.User.setor'),
            //   )));
            // }

            $this->ItemPedido->create();
            // $this->ItemPedido->deleteAll(array('pedidos_id' => $r['Pedido']['id'], 'materiais_id' => $material['id'][$key]), false);
            $this->ItemPedido->save(array('ItemPedido'=>array(
              'pedidos_id' => $r['Pedido']['id'],
              'materiais_id' => $material['id'][$key],
              'aplicacao' => $material['aplicacao'][$key],
              'equipamentos_id' => $material['equipamentos_id'][$key],
              'quantidade_pedido' => $material['pedido'][$key],
              'quantidade_fornecido' => $material['fornecido'][$key],
              'material_transferido' => $material['transferido'][$key],
            )));
          }
        }

        $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
      } else {
        $this->Session->setFlash(__($this::MSG_ERRO));
      }

      return $this->redirect(array('action' => 'index'));
    } else {
        $this->request->data = $this->Pedido->find('first', array('conditions' => array('Pedido.' . $this->Pedido->primaryKey => $id)));
    }

    $this->loadModel('ItemPedido');
    $this->set('itens', $this->ItemPedido->find('all', array('conditions' => array('pedidos_id' => $this->request->data['Pedido']['id']))));

    $this->loadModel('Material');
    $this->set('materiais', $this->Material->find('all', array('fields' => array('id', 'nome', 'barcode', 'quantidade_central'), 'order'=>array('nome'=>'asc'))));
    //$this->set('matfilial', $this->MaterialFilial->find('all',  array('conditions' => array('MaterialFilial.setor_id' => 1))));
    // $this->set('material', $this->Material->find('all', array('conditions' => array('id' => $this->request->data['Pedido']['id']))));

    $this->set('materiais2',$this->Material->find('all', array(
      'conditions' => array('ItemPedido.pedidos_id' => $id),
      'fields' => array('Material.id','Material.nome','Material.quantidade_central','ItemPedido.materiais_id'),
      'joins' => array(
        array(
          'table' => 'pedidos_itens',
          'alias' => 'ItemPedido',
          'type' => 'LEFT',
          'conditions' => array('ItemPedido.materiais_id = Material.id')
        )
      ),
        'order'=>array('nome'=>'asc')
    )));

    $this->loadModel('Setor');
    $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));
    $this->set('id', $id);

    $this->loadModel('Equipamento');
    $this->set('equipamentos', $this->Equipamento->find('all', array('fields' => array('id', 'descricao'), 'order' => 'id ASC')));

  }

  public function edit($id = null) {
    $this->layout="ajax";
    $this->loadModel('Pedido');
    $this->loadModel('MaterialFilial');
    if (!$this->Pedido->exists($id)) {
        throw new NotFoundException(__('Invalid pedido'));
    }

    if ($this->request->is(array('post', 'put'))) {
      //pr($this->request->data); exit;
      $material = $this->request->data['material'];
      unset($this->request->data['material']);

      //pr($material); exit;

      if ($r = $this->Pedido->save($this->request->data)) {
        if($material) {
          $this->loadModel('ItemPedido');
          $this->ItemPedido->deleteAll(array('pedidos_id' => $r['Pedido']['id']));
          foreach ($material['id'] as $key => $value) {
            if(!$material['id'][$key] && !$material['pedido'][$key]) {
                  continue;
            }

            // if($material['fornecido'][$key]) {
            //   $this->loadModel('Material');
            //   $num = 0;
            //   $m = $this->MaterialFilial->find('first', array('conditions' => array('materiais_id' => $material['id'][$key], $this->Session->read('Auth.User.setor'))));
            //
            //   //if($m['MaterialFilial']['quantidade'] - $material['fornecido'][$key] < 0 ){
            //     //$this->Session->setFlash('Quantidade insuficiente em estoque!!!');
            //     //return $this->redirect(array('action' => 'index'));
            //   //}
            //
            //   $mc = $this->Material->find('first', array('conditions' => array('id' => $material['id'][$key])));
            //   $i = $this->ItemPedido->find('first', array('conditions' => array('id' => $material['item'][$key])));
            //   if(isset($i['ItemPedido']['quantidade_fornecido'])) {
            //     $num = $i['ItemPedido']['quantidade_fornecido'];
            //   }
            //
            //
            //   $this->MaterialFilial->updateAll(array('quantidade' => (($num + $m['MaterialFilial']['quantidade']) - $material['fornecido'][$key])), array('materiais_id' => $m['MaterialFilial']['materiais_id'],'setor_id' => $this->Session->read('Auth.User.setor') ));
            //   $this->Material->updateAll(array('quantidade_central' => (($num + $mc['Material']['quantidade_central']) - $material['fornecido'][$key])), array('id' => $mc['Material']['id']));
            //
            //   $this->loadModel('Saida');
            //   $this->Saida->create();
            //   $this->Saida->deleteAll(array('pedidos_id' => $r['Pedido']['id'], 'materiais_id' => $material['id'][$key]), false);
            //   $this->Saida->save(array('Saida'=>array(
            //     'data_saida' => date('d/m/Y'),
            //     'materiais_id' => $material['id'][$key],
            //     'quantidade_saida' => $material['fornecido'][$key],
            //     'usuarios_id' => $r['Pedido']['usuarios_id'],
            //     'localizacoes_id' => $r['Pedido']['setores_id'],
            //     'aplicacao' => $material['aplicacao'][$key],
            //     'pedidos_id' => $r['Pedido']['id'],
            //     'setor_id' => $this->Session->read('Auth.User.setor'),
            //   )));
            // }

            $this->ItemPedido->create();
            // $this->ItemPedido->deleteAll(array('pedidos_id' => $r['Pedido']['id'], 'materiais_id' => $material['id'][$key]), false);
            $this->ItemPedido->save(array('ItemPedido'=>array(
              'pedidos_id' => $r['Pedido']['id'],
              'materiais_id' => $material['id'][$key],
              'aplicacao' => $material['aplicacao'][$key],
              'equipamentos_id' => $material['equipamentos_id'][$key],
              'quantidade_pedido' => $material['pedido'][$key],
              'quantidade_fornecido' => $material['fornecido'][$key],
              'material_transferido' => $material['transferido'][$key],
            )));
          }
        }

        $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
      } else {
        $this->Session->setFlash(__($this::MSG_ERRO));
      }

      return $this->redirect(array('action' => 'index'));
    } else {
        $this->request->data = $this->Pedido->find('first', array('conditions' => array('Pedido.' . $this->Pedido->primaryKey => $id)));
    }

    $setorFilial = $this->Pedido->find('first', array('conditions' => array('id' => $id)));

    $this->loadModel('ItemPedido');
    $this->set('itens', $this->ItemPedido->find('all', array('conditions' => array('pedidos_id' => $this->request->data['Pedido']['id']))));

    $this->loadModel('Material');
    if(in_array($this->Session->read('Perfil.id'), array('12'))) {
      $this->set('materiais', $this->Material->find('all', array('conditions' => array('Material.classificacoes_id' => 13),'fields' => array('id', 'nome', 'barcode', 'quantidade_central'), 'order'=>array('nome'=>'asc'))));
    }else {
        $this->set('materiais', $this->Material->find('all', array('fields' => array('id', 'nome', 'barcode', 'quantidade_central'), 'order'=>array('nome'=>'asc'))));
    }
    //$this->set('matfilial', $this->MaterialFilial->find('all',  array('conditions' => array('MaterialFilial.setor_id' => 1))));
    // $this->set('material', $this->Material->find('all', array('conditions' => array('id' => $this->request->data['Pedido']['id']))));


    if ($setorFilial['Pedido']['setores_id'] == 11) {
      $this->set('materiais2',$this->Material->find('all', array(
        'conditions' => array('ItemPedido.pedidos_id' => $id, 'MaterialFilial.setor_id' => 1),
        'fields' => array('Material.id','Material.nome','Material.quantidade_central', 'MaterialFilial.quantidade', 'MaterialFilial.setor_id', 'ItemPedido.materiais_id'),
        'joins' => array(
          array(
            'table' => 'pedidos_itens',
            'alias' => 'ItemPedido',
            'type' => 'LEFT',
            'conditions' => array('ItemPedido.materiais_id = Material.id')
          ),
          array(
            'table' => 'materiais_filiais',
            'alias' => 'MaterialFilial',
            'type' => 'INNER',
            'conditions' => array('MaterialFilial.materiais_id = ItemPedido.materiais_id')
          )
        ),
          'order'=>array('nome'=>'asc')
      )));
    }else {
      $this->set('materiais2',$this->Material->find('all', array(
        'conditions' => array('ItemPedido.pedidos_id' => $id, 'MaterialFilial.setor_id' => $setorFilial['Pedido']['setores_id']),
        'fields' => array('Material.id','Material.nome','Material.quantidade_central', 'MaterialFilial.quantidade', 'MaterialFilial.setor_id', 'ItemPedido.materiais_id'),
        'joins' => array(
          array(
            'table' => 'pedidos_itens',
            'alias' => 'ItemPedido',
            'type' => 'LEFT',
            'conditions' => array('ItemPedido.materiais_id = Material.id')
          ),
          array(
            'table' => 'materiais_filiais',
            'alias' => 'MaterialFilial',
            'type' => 'INNER',
            'conditions' => array('MaterialFilial.materiais_id = ItemPedido.materiais_id')
          )
        ),
          'order'=>array('nome'=>'asc')
      )));
    }

    $this->loadModel('Setor');
    $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));
    $this->set('id', $id);

    $this->loadModel('Equipamento');
    $this->set('equipamentos', $this->Equipamento->find('all', array('fields' => array('id', 'descricao'), 'order' => 'id ASC')));

  }

  public function separar($id = null) {
    $this->layout="ajax";
    $this->loadModel('Pedido');
    if (!$this->Pedido->exists($id)) {
      throw new NotFoundException(__('Invalid pedido'));
    }

    $this->loadModel('Compra');
    $this->request->data = $this->Pedido->find('first', array('conditions' => array('Pedido.' . $this->Pedido->primaryKey => $id)));
    $compra = $this->Compra->find('first', array('conditions' => array('Compra.situacao' => '1')));
    $this->request->data['Compra']['id'] = isset($compra['Compra']['id']) ? $compra['Compra']['id'] : null;

    $this->loadModel('ItemPedido');
    $this->set('itens', $this->ItemPedido->find('all', array(
      'conditions' => array(
        'ItemPedido.pedidos_id' => $this->request->data['Pedido']['id'],
        'Pedido.situacao' => '1'
      ),
      'fields' => array('Material.id','Material.nome','Material.barcode','ItemPedido.*'),
      'joins' => array(
        array(
          'table' => 'pedidos',
          'alias' => 'Pedido',
          'type' => 'INNER',
          'conditions' => array('Pedido.id = ItemPedido.pedidos_id')
        ),
        array(
          'table' => 'materiais',
          'alias' => 'Material',
          'type' => 'INNER',
          'conditions' => array('Material.id = ItemPedido.materiais_id')
        )
      ),
      'order' => array('Material.nome' => 'ASC')
    )));

    $this->loadModel('ItemCompra');
    $this->set('compras', $this->ItemCompra->find('all', array(
      'conditions' => array('Compra.situacao' => '1'),
      'fields' => array('Material.id','Material.nome','Material.barcode','ItemCompra.*'),
      'joins' => array(
        array(
          'table' => 'materiais',
          'alias' => 'Material',
          'type' => 'INNER',
          'conditions' => array('Material.id = ItemCompra.materiais_id')
        ),
        array(
          'table' => 'compras',
          'alias' => 'Compra',
          'type' => 'INNER',
          'conditions' => array('Compra.id = ItemCompra.compras_id')
        )
      ),
      'order' => array('Material.nome' => 'ASC')
    )));

    $this->set('id', $id);
  }

  public function pedido() {
    $this->loadModel('ItemPedido');
    $this->loadModel('ItemCompra');
    if ($this->request->is('post')) {
      $this->ItemCompra->create();
      if ($this->ItemCompra->save($this->request->data['ItemCompra'])) {
        $this->ItemPedido->id = $this->request->data['ItemPedido']['id'];
        if (!$this->ItemPedido->delete()) {
          echo $this::MSG_ERRO;
        } else {
          echo $this::MSG_SUCESSO_EDT;
        }
      } else {
        echo $this::MSG_ERRO;
      }
    }
    exit;
  }

  public function compra($id = null) {
    $this->loadModel('ItemPedido');
    $this->loadModel('ItemCompra');
    if ($this->request->is('post')) {
      $this->ItemPedido->create();
      if ($this->ItemPedido->save($this->request->data['ItemPedido'])) {
        $this->ItemCompra->id = $this->request->data['ItemCompra']['id'];
        if (!$this->ItemCompra->delete()) {
          echo $this::MSG_ERRO;
        } else {
          echo $this::MSG_SUCESSO_EDT;
        }
      } else {
        echo $this::MSG_ERRO;
      }
    }
    exit;
  }

  public function imprimir($id = null) {
    $this->loadModel('Pedido');
    if (!$this->Pedido->exists($id)) {
        throw new NotFoundException(__('Invalid pedido'));
    }

    $pedido = $this->Pedido->find('first', array(
      'conditions' => array(
        'Pedido.' . $this->Pedido->primaryKey => $id
      ),
      'fields' => array('Pedido.*','Setor.id','Setor.descricao','Usuario.nome'),
      'joins' => array(
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'INNER',
          'conditions' => array('Setor.id = Pedido.setores_id')
        ),
        array(
          'table' => 'admin.usuarios',
          'alias' => 'Usuario',
          'type' => 'INNER',
          'conditions' => array('Usuario.id = Pedido.usuarios_id')
        )
      ),
    ));

    $this->loadModel('ItemPedido');

    $sql = "SELECT ItemPedido.*, Material.*
            FROM pedidos_itens ItemPedido
            LEFT JOIN materiais Material on Material.id = ItemPedido.materiais_id
            WHERE ItemPedido.pedidos_id = '{$pedido['Pedido']['id']}'
            UNION
            SELECT ItemCompra.id,ItemCompra.pedidos_id, ItemCompra.materiais_id, ItemCompra.quantidade_pedido,ItemCompra.quantidade_fornecido, ItemCompra.aplicacao, ItemCompra.comprou,ItemCompra.equipamentos_id, Material.*
            FROM compras_itens ItemCompra
            LEFT JOIN materiais Material on Material.id = ItemCompra.materiais_id
            WHERE ItemCompra.pedidos_id= '{$pedido['Pedido']['id']}'";

    $itens = $this->ItemPedido->query($sql);

      //pr($itens);exit;


    // $itens = $this->ItemPedido->find('all', array(
    //   'conditions' => array(
    //     'pedidos_id' => $pedido['Pedido']['id']
    //   ),
    //   'fields' => array('ItemPedido.*','Material.*'),
    //   'joins' => array(
    //     array(
    //       'table' => 'materiais',
    //       'alias' => 'Material',
    //       'type' => 'INNER',
    //       'conditions' => array('Material.id = ItemPedido.materiais_id')
    //     )
    //   ),
    // ));
        // echo <img src="logobritacal.png"/>;

    $html = "";
    // $html .= "<img src='http://localhost/patrimonio/img/logobritacal.png'/ >";

    // <div style="width: 200px; float: right;"> echo $this->Html->image('logobritacal.png', array('class' => 'logo')); </div>

    // $html .= "<div style='text-align: left; position: absolute; width: 20%; font-size: 16px;'>EMPRESA</div>";
    // $html .= "<div style='text-align: center; position: absolute; width: 80%; font-size: 16px;'>REQUISI&Ccedil;&Atilde;O DE MATERIAL DE CONSUMO</div>";
    // $html .= "<div style='text-align: right; font-size: 16px; margin-bottom: 15px;'>Pedido n&ordm; {$pedido['Pedido']['id']}</div>";


    $dataPedido = date('d/m/Y H:i', strtotime($pedido['Pedido']['data_hora_registro']));

    $html .= "<table cellspacing='0' cellpadding='0' style='width: 100%;' style='border-right: 1px solid #000;'>";
    $html .= "<tr>";
    $html .= "<td colspan='7' style='text-align: center;'><b>REQUISI&Ccedil;&Atilde;O DE MATERIAL<b/></td>";
    $html .= "<td colspan='1' style='text-align: center;'><b>N&ordm;<b/></td>";
    $html .= "<td colspan='1' style='text-align: center;'><b>{$pedido['Pedido']['id']}<b/></td>";
    $html .= "</tr>";
    $html .= "<tr>";
    $html .= "<td colspan='1' style='text-align: left;'><b>Dire&ccedil;&atilde;o:<b/></td>";
    $html .= "<td colspan='8' style='text-align: left;'><b>Cabeceiras - Go Central - Fazenda Brejinho 'Almoxarifado Central'<b/></td>";
    $html .= "</tr>";
    $html .= "<tr>";
    // $html .= "<td colspan='2' style='text-align: left;'><b> {$pedido['Pedido']['data_hora_registro']} <b/></td>";
    $html .= "<td colspan='9' style='text-align: right;'><img src='http://localhost/patrimonio/img/logobritacal.png' height='30' width='200';/ ></td>";
    // $html .= "<td colspan='3' style='text-align: left;'><b>  <b/></td>";
    $html .= "</tr>";
    $html .= "<tr>";
    $html .= "<td colspan='2' style='text-align: left;'><b> {$dataPedido} <b/></td>";
    $html .= "<td colspan='7' style='text-align: center;'> Aplica&ccedil;&atilde;o</td>";
    $html .= "</tr>";
    $html .= "<tr><td colspan='9'></td></tr>";
    $html .= "<tr>";
    $html .= "<td colspan='9'>Unidade Requisitante: <b>{$pedido['Setor']['descricao']}<b/></td>";
    //$html .= "<td colspan='2'>C&oacute;digo setor: <b>{$pedido['Setor']['id']}</b></td>";
    $html .= "</tr>";

    // $html .= "<tr>";
    // $html .= "<td></td>";
    // $html .= "<td colspan='6' style='text-align: center;'>MATERIAL</td>";
    // $html .= "<td colspan='2' style='text-align: center;'>QUANTIDADE</td>";
    // $html .= "</tr>";

    $html .= "<tr>";
    $html .= "<td colspan='1' style='text-align: center;'>Item</td>";
    $html .= "<td colspan='1' style='text-align: center;'>QNT</td>";
    $html .= "<td colspan='5' style='text-align: center;'>Descri&ccedil;&atilde;o de Mercadorias</td>";
    //$html .= "<td colspan='1' style='text-align: center;'>Codigo</td>";
    $html .= "<td colspan='2' style='text-align: center;'>Aplica&ccedil;&atilde;o</td>";
    $html .= "</tr>";

    // $html .= "<tr>";
    // $html .= "<td style='text-align: center;'>N&ordm;</td>";
    // $html .= "<td style='text-align: center;'>C&Oacute;DIGO</td>";
    // $html .= "<td style='text-align: center;' colspan='5' >ESPECIFICA&Ccedil;&Atilde;O</td>";
    // $html .= "<td style='text-align: center;'>PEDIDA</td>";
    // $html .= "<td style='text-align: center;'>FORNECIDA</td>";
    // $html .= "</tr>";

    foreach ($itens as $key => $value) {
      //pr($value);exit;
      $html .= "<tr>";
      $html .= "<td style='text-align: center;'>".($key+1)."</td>";
      $html .= "<td style='text-align: center;'>{$value[0]['quantidade_pedido']}</td>";
      // $html .= "<td style='text-align: center;'>{$value['Material']['id']}</td>";
      $html .= "<td colspan='5' style='text-align: center;'>{$value[0]['nome']}</td>";
      //$html .= "<td style='text-align: center;'>{$value[0]['barcode']}</td>";
      $html .= "<td colspan='2' style='text-align: center;'></td>";
      $html .= "</tr>";
    }

    $html .= "<tr>";
    $html .= "<td colspan='3' style='width: 33%; height: 100px; vertical-align: top; border-bottom: 1px solid #000;'>AUTORIZADO POR:</td>";
    $html .= "<td colspan='3' style='width: 33%; height: 100px; vertical-align: top; border-bottom: 1px solid #000;'>DESPACHADO POR:</td>";
    $html .= "<td colspan='3' style='width: 33%; height: 100px; vertical-align: top; border-bottom: 1px solid #000;'>RECEBIDO POR:</td>";
    $html .= "</tr>";

    $html .= '</table>';

    $mpdf = new mPDF();
    $mpdf->SetTitle('REQUISICAO DE MATERIAL DE CONSUMO');
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->Image('logobritacal.png', 0, 0, 210, 297, 'png', '', true, false);
    // $mpdf->SetHTMLFooter('<div>' . date('d/m/Y H:i') . '<div style="margin-top: -20px;" align="right">P&aacute;gina {PAGENO}/{nb}</div><br/></div>');
    // $mpdf->AddPage('','','','','',null,null,25,15,0,0);
    $mpdf->WriteHTML("<style> th, td { border: 1px solid #000; padding: 2px; font-size: 14px; padding: 10px; text-transform: uppercase;} td { border-bottom: 0; border-right: 0;} </style>");
    $mpdf->WriteHTML($html);
    //$mpdf->Output('REQUISICAO DE MATERIAL DE CONSUMO.pdf', 'D');
    $mpdf->Output();
    exit;
  }

  public function transferencia($id = null) {
  $this->loadModel('Pedido');
  if (!$this->Pedido->exists($id)) {
      throw new NotFoundException(__('Invalid pedido'));
  }

  $pedido = $this->Pedido->find('first', array(
    'conditions' => array(
      'Pedido.' . $this->Pedido->primaryKey => $id
    ),
    'fields' => array('Pedido.*','Setor.id','Setor.descricao','Usuario.nome'),
    'joins' => array(
      array(
        'table' => 'setores',
        'alias' => 'Setor',
        'type' => 'INNER',
        'conditions' => array('Setor.id = Pedido.setores_id')
      ),
      array(
        'table' => 'admin.usuarios',
        'alias' => 'Usuario',
        'type' => 'INNER',
        'conditions' => array('Usuario.id = Pedido.usuarios_id')
      )
    ),
  ));

  $this->loadModel('ItemPedido');
  $itens = $this->ItemPedido->find('all', array(
    'conditions' => array(
      'pedidos_id' => $pedido['Pedido']['id']
    ),
    'fields' => array('ItemPedido.*','Material.*'),
    'joins' => array(
      array(
        'table' => 'materiais',
        'alias' => 'Material',
        'type' => 'INNER',
        'conditions' => array('Material.id = ItemPedido.materiais_id')
      )
    ),
  ));

$data = date('d/m/Y', strtotime($pedido['Pedido']['data_hora_registro']));
      // echo <img src="logobritacal.png"/>;

  $html = "";
  // $html .= "<img src='http://localhost/patrimonio/img/logobritacal.png'/ >";

  // <div style="width: 200px; float: right;"> echo $this->Html->image('logobritacal.png', array('class' => 'logo')); </div>

  // $html .= "<div style='text-align: left; position: absolute; width: 20%; font-size: 16px;'>EMPRESA</div>";
  // $html .= "<div style='text-align: center; position: absolute; width: 80%; font-size: 16px;'>REQUISI&Ccedil;&Atilde;O DE MATERIAL DE CONSUMO</div>";
  // $html .= "<div style='text-align: right; font-size: 16px; margin-bottom: 15px;'>Pedido n&ordm; {$pedido['Pedido']['id']}</div>";



  $html .= "<table cellspacing='0' cellpadding='0' style='width: 100%;' style='border-right: 1px solid #000;'>";
  $html .= "<tr>";
  $html .= "<td colspan='7' style='text-align: center;'><b>CONTROLE DE TRANSFER&Ecirc;NCIA DE INSUMOS<b/></td>";
  $html .= "<td colspan='1' style='text-align: center;'><b>N&ordm;<b/></td>";
  $html .= "<td colspan='1' style='text-align: center;'><b>{$pedido['Pedido']['id']}<b/></td>";
  $html .= "</tr>";
  $html .= "<tr>";
  $html .= "<td colspan='1' style='text-align: left;'><b>Dire&ccedil;&atilde;o:<b/></td>";
  $html .= "<td colspan='8' style='text-align: left;'><b>Cabeceiras - Go Central - Fazenda Brejinho 'Almoxarifado Central'<b/></td>";
  $html .= "</tr>";
  $html .= "<tr>";
  $html .= "<td colspan='9' style='text-align: left;'> <b>Data: {$data}</td>";
  $html .= "</tr>";
  $html .= "<tr>";
  // $html .= "<td colspan='2' style='text-align: left;'><b>  <b/></td>";
  $html .= "<td colspan='9' style='text-align: right;'><img src='http://localhost/patrimonio/img/logobritacal.png' height='30' width='200';/ ></td>";
  // $html .= "<td colspan='3' style='text-align: left;'><b>  <b/></td>";
  $html .= "</tr>";
  // $html .= "<tr>";
  // $html .= "<td colspan='9' style='text-align: center;'> Aplica&ccedil;&atilde;o</td>";
  // $html .= "</tr>";
  // $html .= "<tr><td colspan='9'></td></tr>";
  $html .= "<tr>";
  $html .= "<td colspan='9'>Unidade Requisitante: <b>{$pedido['Setor']['descricao']}<b/></td>";
  // $html .= "<td colspan='2'>C&oacute;digo setor: <b>{$pedido['Setor']['id']}</b></td>";
  $html .= "</tr>";

  // $html .= "<tr>";
  // $html .= "<td></td>";
  // $html .= "<td colspan='6' style='text-align: center;'>MATERIAL</td>";
  // $html .= "<td colspan='2' style='text-align: center;'>QUANTIDADE</td>";
  // $html .= "</tr>";

  $html .= "<tr>";
  $html .= "<td colspan='1' style='text-align: center;'>Item</td>";
  $html .= "<td colspan='1' style='text-align: center;'>QNT</td>";
  $html .= "<td colspan='5' style='text-align: center;'>Descri&ccedil;&atilde;o de Mercadorias</td>";
  $html .= "<td colspan='1' style='text-align: center;'>Codigo</td>";
  $html .= "<td colspan='1' style='text-align: center;'>Aplica&ccedil;&atilde;o</td>";
  $html .= "</tr>";

  // $html .= "<tr>";
  // $html .= "<td style='text-align: center;'>N&ordm;</td>";
  // $html .= "<td style='text-align: center;'>C&Oacute;DIGO</td>";
  // $html .= "<td style='text-align: center;' colspan='5' >ESPECIFICA&Ccedil;&Atilde;O</td>";
  // $html .= "<td style='text-align: center;'>PEDIDA</td>";
  // $html .= "<td style='text-align: center;'>FORNECIDA</td>";
  // $html .= "</tr>";

  foreach ($itens as $key => $value) {
    if($value['ItemPedido']['quantidade_fornecido'] != ''){
    //pr($value);exit;
    $html .= "<tr>";
    $html .= "<td style='text-align: center;'>".($key+1)."</td>";
    $html .= "<td style='text-align: center;'>{$value['ItemPedido']['quantidade_fornecido']}</td>";
    // $html .= "<td style='text-align: center;'>{$value['Material']['id']}</td>";
    $html .= "<td colspan='5' style='text-align: center;'>{$value['Material']['nome']}</td>";
    $html .= "<td style='text-align: center;'>{$value['Material']['barcode']}</td>";
    $html .= "<td style='text-align: center;'>{$value['ItemPedido']['aplicacao']}</td>";
    $html .= "</tr>";
  }
  }

  $html .= "<tr>";
  $html .= "<td colspan='9' style='text-align: left;'> <b>Observa&ccedil;&atilde;o: {$pedido['Pedido']['observacao']}</b></td>";
  $html .= "</tr>";

  $html .= "<tr>";
  $html .= "<td colspan='3' style='width: 33%; height: 100px; vertical-align: top; border-bottom: 1px solid #000;'>AUTORIZADO POR:</td>";
  $html .= "<td colspan='3' style='width: 33%; height: 100px; vertical-align: top; border-bottom: 1px solid #000;'>DESPACHADO POR:</td>";
  $html .= "<td colspan='3' style='width: 33%; height: 100px; vertical-align: top; border-bottom: 1px solid #000;'>RECEBIDO POR:</td>";
  $html .= "</tr>";

  $html .= '</table>';

  $mpdf = new mPDF();
  $mpdf->SetTitle('REQUISICAO DE MATERIAL DE CONSUMO');
  $mpdf->SetDisplayMode('fullpage');
  $mpdf->Image('logobritacal.png', 0, 0, 210, 297, 'png', '', true, false);
  // $mpdf->SetHTMLFooter('<div>' . date('d/m/Y H:i') . '<div style="margin-top: -20px;" align="right">P&aacute;gina {PAGENO}/{nb}</div><br/></div>');
  // $mpdf->AddPage('','','','','',null,null,25,15,0,0);
  $mpdf->WriteHTML("<style> th, td { border: 1px solid #000; padding: 2px; font-size: 14px; padding: 10px; text-transform: uppercase;} td { border-bottom: 0; border-right: 0;} </style>");
  $mpdf->WriteHTML($html);
  //$mpdf->Output('REQUISICAO DE MATERIAL DE CONSUMO.pdf', 'D');
  $mpdf->Output();
  exit;
}
public function delete($id = null) {
    // $this->MaterialClassificacao->id = $id;
    $this->loadModel('Pedido');
    $this->Pedido->id = $id;
    if (!$this->Pedido->exists()) {
        throw new NotFoundException(__('Invalid pedido'));
    }
    $this->request->is('post', 'delete');
    if ($this->Pedido->delete()) {
       $this->Session->setFlash(__($this::MSG_SUCESSO_DEL));
    } else {
        $this->Session->setFlash(__($this::MSG_ERRO));
    }
    return $this->redirect(array('action' => 'index'));
}

public function transferencia_pedido($id = null){

  $this->layout="ajax";

  $this->loadModel('ItemPedido');

  // $item = $this->ItemOrcamento->find('first', array('conditions' => array('ItemOrcamento.pedidos_id' => $id, 'ItemOrcamento.comprar' => '1')));
  // pr($item);exit;
  $this->loadModel('Transferencia');
  $transf = $this->Transferencia->find('first', array('conditions' => array('Transferencia.situacao' => '1')));

  $this->set('itens', $this->ItemPedido->find('all', array(
    'conditions' => array(
      'ItemPedido.pedidos_id' => $id, 'ItemPedido.quantidade_fornecido' != ''
    ),
    'fields' => array('Material.id','Material.nome', 'Material.quantidade', 'Material.barcode', 'ItemPedido.*', 'Pedido.setores_id'),
    'joins' => array(
      array(
        'table' => 'pedidos',
        'alias' => 'Pedido',
        'type' => 'INNER',
        'conditions' => array('Pedido.id = ItemPedido.pedidos_id')
      ),
      array(
        'table' => 'materiais',
        'alias' => 'Material',
        'type' => 'INNER',
        'conditions' => array('Material.id = ItemPedido.materiais_id')
      )
    ),
    'group' => 'ItemPedido.id',
    'order' => array('Material.nome' => 'ASC')
  )));

  $this->loadModel('ItemTransferencia');
  $this->set('transferencias', $this->ItemTransferencia->find('all', array(
    'conditions' => array('Transferencia.situacao' => '1'),
    'fields' => array('Material.id','Material.nome', 'Material.barcode','ItemTransferencia.*'),
    'joins' => array(
      array(
        'table' => 'materiais',
        'alias' => 'Material',
        'type' => 'INNER',
        'conditions' => array('Material.id = ItemTransferencia.materiais_id')
      ),
      array(
        'table' => 'transferencias',
        'alias' => 'Transferencia',
        'type' => 'INNER',
        'conditions' => array('Transferencia.id = ItemTransferencia.transferencia_id')
      )
    ),
    'order' => array('Material.nome' => 'ASC')
  )));

  $this->set(compact('id', $id, 'transf' ));

}

public function transferencia_filial(){
  $this->loadModel('ItemPedido');
  $this->loadModel('Pedido');
  $this->loadModel('ItemTransferencia');
  $this->loadModel('Transferencia');
  $this->loadModel('MaterialFilial');

  if ($this->request->is('post')) {
    $material = $this->request->data['ItemTransferencia'];
    $t = $this->Pedido->find('first', array('conditions' => array('id' => $material['pedidos_id'])));
    //pr($this->request->data);exit;
    $this->ItemTransferencia->create();
    if ($this->ItemTransferencia->save($this->request->data['ItemTransferencia'])) {
      $this->ItemPedido->id = $this->request->data['ItemPedido']['id'];
      $this->ItemPedido->updateAll(array('material_transferido' => 1), array('pedidos_id' => $material['pedidos_id'], 'materiais_id' =>$material['materiais_id']));

      $this->loadModel('Material');
      $m = $this->MaterialFilial->find('first', array('conditions' => array('materiais_id' => $material['materiais_id'], $this->Session->read('Auth.User.setor'))));
      $mc = $this->Material->find('first', array('conditions' => array('id' => $material['materiais_id'])));

      $this->MaterialFilial->updateAll(array('quantidade' => (($m['MaterialFilial']['quantidade']) - $material['quantidade_transferida'])), array('materiais_id' => $m['MaterialFilial']['materiais_id'], 'setor_id' =>$this->Session->read('Auth.User.setor')));
      $this->Material->updateAll(array('quantidade_central' => (($mc['Material']['quantidade_central']) - $material['quantidade_transferida'])), array('id' => $mc['Material']['id']));

      $this->loadModel('Saida');
      $this->Saida->create();
      //$this->Saida->deleteAll(array('pedidos_id' => $r['Pedido']['id'], 'materiais_id' => $material['id'][$key]), false);
      $this->Saida->save(array('Saida'=>array(
        'data_saida' => date('d/m/Y'),
        'materiais_id' => $material['materiais_id'],
        'quantidade_saida' => $material['quantidade_transferida'],
        'usuarios_id' => $t['Pedido']['usuarios_id'],
        'localizacoes_id' => $t['Pedido']['setores_id'],
        'equipamentos_id' => $material['equipamentos_id'],
        'pedidos_id' => $material['pedidos_id'],
        'setor_id' => $this->Session->read('Auth.User.setor'),
      )));

    } else {
      echo $this::MSG_SUCESSO_EDT;
    }
  }
  exit;
}

public function delete_transferencia($id = null) {
  $this->loadModel('ItemTransferencia');
  $this->loadModel('ItemPedido');

  if ($this->request->is('post', 'delete')) {
    // pr($this->request->data);exit;

    $material = $this->request->data['ItemTransferencia'];
    $this->ItemPedido->updateAll(array('material_transferido' => 0), array('pedidos_id' => $material['pedidos_id'], 'materiais_id' =>$material['materiais_id']));

    $this->loadModel('Material');
    $this->loadModel('MaterialFilial');
    $this->loadModel('Saida');

    $s = $this->Saida->find('first', array('conditions' => array('pedidos_id' => $material['pedidos_id'], 'materiais_id'=>$material['materiais_id'])));
    // pr($s);exit;
    $m = $this->MaterialFilial->find('first', array('conditions' => array('materiais_id' => $material['materiais_id'], $this->Session->read('Auth.User.setor'))));
    $mc = $this->Material->find('first', array('conditions' => array('id' => $material['materiais_id'])));

    $this->MaterialFilial->updateAll(array('quantidade' => (($m['MaterialFilial']['quantidade']) + $material['quantidade_transferida'])), array('materiais_id' => $m['MaterialFilial']['materiais_id'], 'setor_id' =>$this->Session->read('Auth.User.setor')));
    $this->Material->updateAll(array('quantidade_central' => (($mc['Material']['quantidade_central']) + $material['quantidade_transferida'])), array('id' => $mc['Material']['id']));

    $this->Saida->delete(array('id' => $s['Saida']['id']));
    $this->ItemTransferencia->delete(array('id' => $material['id']));

  }

}

public function pedidos_pendente($id = null){


  $this->loadModel('Pedido');
  $this->set('pedidos', $this->Pedido->find('all', array(
    'fields' => array('Pedido.id', 'Pedido.data_hora_registro', 'Material.nome', 'ItemPedido.quantidade_pedido'),
    'conditions' =>array('Pedido.setores_id' => $this->Session->read('Auth.User.setor'), 'ItemPedido.material_transferido <>' =>1),
    'joins' => array(
      array(
          'table' => 'pedidos_itens',
          'alias' => 'ItemPedido',
          'type' => 'LEFT',
          'conditions' => array('ItemPedido.pedidos_id = Pedido.id')
      ),
      array(
          'table' => 'materiais',
          'alias' => 'Material',
          'type' => 'LEFT',
          'conditions' => array('Material.id = ItemPedido.materiais_id')
      )
    ),
    'order' => 'Material.nome asc'
  )));

}

}
