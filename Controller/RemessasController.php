<?php
App::uses('AppController', 'Controller');
require_once(ROOT . DS . 'patrimonio' . DS . 'Vendor' . DS . 'mpdf60' . DS . 'mpdf.php');

class RemessasController extends AppController {

  public $components = array('Paginator', 'Upload');


  public function index($search = null) {

    $option = array();
    if ($search) {
      $option['OR']['Remessa.id LIKE'] = "%{$search}%";
      $option['OR']['ItemRemessa.materiais_id'] = "{$search}";
      //$option['OR']['Remessa.situacao LIKE'] = "%{$search}%";
    }

    $this->loadModel('Remessa');

    // $options = array('Setor.id IS NULL');
    // if($this->Session->read('Auth.User.setor') && in_array($this->Session->read('Perfil.id'), array('3'))) {
    //   $options['Setor.id'] = $this->Session->read('Auth.User.setor');
    //   unset($options[0]);
    // } else if(!in_array($this->Session->read('Perfil.id'), array('3'))) {
    //   unset($options[0]);
    // }

    $this->Remessa->recursive = 0;
    $this->Paginator->settings = array(
      'conditions' => $option,
      'fields' => array('Remessa.*','Usuario.nome', 'ItemRemessa.materiais_id'),
      'joins' => array(
        array(
          'table' => 'admin.usuarios',
          'alias' => 'Usuario',
          'type' => 'INNER',
          'conditions' => array('Usuario.id = Remessa.usuarios_id')
        ),
        array(
          'table' => 'remessas_itens',
          'alias' => 'ItemRemessa',
          'type' => 'LEFT',
          'conditions' => array('ItemRemessa.remessas_id = Remessa.id')
        )
      ),
        'group' => 'Remessa.id',
        'order' => "FIELD(Remessa.situacao, '1','5','3','2','4'), Remessa.id desc"
      //'order' => array('Remessa.id' => 'DESC')
    );

    $this->set('remessas', $this->Paginator->paginate('Remessa', $option));
  }

  public function add($id = null) {
    $this->layout="ajax";

    $this->loadModel('Remessa');
    if ($this->request->is('post')) {
      //pr($this->request->data);exit;
      $material = $this->request->data['material'];
      unset($this->request->data['material']);

      $this->Remessa->create();
      if ($r = $this->Remessa->save($this->request->data)) {
        if($material) {
          $this->loadModel('ItemRemessa');
          foreach ($material['id'] as $key => $value) {
            if(!$material['id'][$key] && !$material['remessa'][$key]) {
              continue;
            }

            $this->ItemRemessa->create();
            $this->ItemRemessa->save(array('ItemRemessa'=>array(
              'quantidade' => $material['remessa'][$key],
              'materiais_id' => $material['id'][$key],
              //'pedidos_id' => $material['id'][$key],
              //'orcamentos_id' => $material['id'][$key],
              'remessas_id' => $r['Remessa']['id']
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
    $this->set('materiais', $this->Material->find('all', array('fields' => array('id', 'nome', 'barcode'), 'order'=>array('nome'=>'asc'))));

    $this->loadModel('Setor');
    $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));
    $this->set('id', $id);


}


public function edit($id = null) {
  $this->layout="ajax";
  $this->loadModel('Remessa');
  if (!$this->Remessa->exists($id)) {
    throw new NotFoundException(__('Invalid remessa'));
  }

  if ($this->request->is(array('post', 'put'))) {
    // pr($this->request->data);exit;
    $material = $this->request->data['material'];
    unset($this->request->data['material']);

    if ($r = $this->Remessa->save($this->request->data)) {
      if($material) {
        $this->loadModel('ItemRemessa');
        $this->ItemRemessa->deleteAll(array('remessas_id' => $r['Remessa']['id']), false);
        foreach ($material['id'] as $key => $value) {
          if(!$material['id'][$key] && !$material['remessa'][$key]) {
            continue;
          }

          // if($material['fornecido'][$key]) {
          //   $this->loadModel('Material');
          //   $num = 0;
          //   $m = $this->Material->find('first', array('conditions' => array('id' => $material['id'][$key])));
          //   $i = $this->ItemCompra->find('first', array('conditions' => array('id' => $material['item'][$key])));
          //   if(isset($i['ItemCompra']['quantidade_fornecido'])) {
          //     $num = $i['ItemCompra']['quantidade_fornecido'];
          //   }
          //
          //   $this->Material->updateAll(array('quantidade' => (($num + $m['Material']['quantidade']) - $material['fornecido'][$key])), array('id' => $m['Material']['id']));
          // }

          $this->ItemRemessa->create();
          //$this->ItemRemessa->deleteAll(array('remessas_id' => $r['Remessa']['id'], 'materiais_id' => $material['id'][$key]), false);
          $this->ItemRemessa->save(array('ItemRemessa'=>array(
            'quantidade' => $material['remessa'][$key],
            'materiais_id' => $material['id'][$key],
            'pedidos_id' => $material['pedidos_id'][$key],
            'orcamentos_id' => $material['orcamentos_id'][$key],
            'equipamentos_id' => $material['equipamentos_id'][$key],
            'setor_id' => $material['setor_id'][$key],
            'remessas_id' => $r['Remessa']['id']
          )));
        }
      }

      $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
    } else {
      $this->Session->setFlash(__($this::MSG_ERRO));
    }

    return $this->redirect(array('action' => 'index'));
  } else {
    $this->request->data = $this->Remessa->find('first', array('conditions' => array('Remessa.' . $this->Remessa->primaryKey => $id)));
  }

  $this->loadModel('ItemRemessa');
  $this->set('itens', $this->ItemRemessa->find('all', array('conditions' => array('remessas_id' => $this->request->data['Remessa']['id']))));

  $this->loadModel('Material');
  $this->set('materiais', $this->Material->find('list', array('fields' => array('id', 'nome'), 'order'=>array('nome'=>'asc'))));

  $this->loadModel('Setor');
  $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));
  $this->set('id', $id);

}

public function protocolos($search = null) {

    $option = array();
  if ($search) {
    $option['OR']['Remessa.id'] = "{$search}";
  }

  $this->loadModel('Remessa');

  // $options = array('Setor.id IS NULL');
  // if($this->Session->read('Auth.User.setor') && in_array($this->Session->read('Perfil.id'), array('3'))) {
  //   $options['Setor.id'] = $this->Session->read('Auth.User.setor');
  //   unset($options[0]);
  // } else if(!in_array($this->Session->read('Perfil.id'), array('3'))) {
  //   unset($options[0]);
  // }

  $this->Remessa->recursive = 0;
  $this->Paginator->settings = array(
    'conditions' => array('Remessa.situacao' => '2', $option),
    'fields' => array('Remessa.*','Usuario.nome'),
    'joins' => array(
      array(
        'table' => 'admin.usuarios',
        'alias' => 'Usuario',
        'type' => 'INNER',
        'conditions' => array('Usuario.id = Remessa.usuarios_id')
      )
    ),
      'order' => "FIELD(Remessa.situacao_protocolo, '1','3','2'), Remessa.data_hora_registro desc"
    //'order' => array('Remessa.id' => 'DESC')
  );

  $this->set('remessas', $this->Paginator->paginate('Remessa', $option));

}

public function abrir($id = null) {
  $this->layout="ajax";
  $this->loadModel('Remessa');

  $this->Remessa->recursive = 0;
  $this->Paginator->settings = array(
    'conditions' => array('Remessa.id' => $id, 'Remessa.situacao' => '2'),
    'fields' => array('Remessa.*', 'ItemRemessa.*', 'Pedido.id', 'Pedido.data_hora_registro', 'Setor.*', 'Material.id', 'Material.nome'),
    'joins' => array(
      array(
        'table' => 'remessas_itens',
        'alias' => 'ItemRemessa',
        'type' => 'INNER',
        'conditions' => array('ItemRemessa.remessas_id = Remessa.id')
      ),
      array(
        'table' => 'pedidos',
        'alias' => 'Pedido',
        'type' => 'INNER',
        'conditions' => array('Pedido.id = ItemRemessa.pedidos_id')
      ),
      array(
        'table' => 'setores',
        'alias' => 'Setor',
        'type' => 'LEFT',
        'conditions' => array('Setor.id = ItemRemessa.setor_id')
      ),
      array(
        'table' => 'materiais',
        'alias' => 'Material',
        'type' => 'LEFT',
        'conditions' => array('Material.id = ItemRemessa.materiais_id')
      )
    ),
    'order' => array('ItemRemessa.setor_id' => 'ASC')
  );

  $this->set('remessas', $this->Paginator->paginate('Remessa'));

  // pr($orcamentos);exit;
}

public function separar($id = null){

  $this->layout="ajax";
  $this->loadModel('Remessa');
  if (!$this->Remessa->exists($id)) {
    throw new NotFoundException(__('Invalid remessa'));
  }

  $this->loadModel('Entrada');
  // $this->request->data = $this->Pedido->find('first', array('conditions' => array('Pedido.' . $this->Pedido->primaryKey => $id)));
  // $compra = $this->Compra->find('first', array('conditions' => array('Compra.situacao' => '1')));
  // $this->request->data['Compra']['id'] = isset($compra['Compra']['id']) ? $compra['Compra']['id'] : null;

  $this->loadModel('ItemRemessa');

  $item = $this->ItemRemessa->find('first', array('conditions' => array('ItemRemessa.remessas_id' => $id)));

  $this->set('itens', $this->ItemRemessa->find('all', array(
    'conditions' => array(
      'ItemRemessa.remessas_id' => $id
    ),
    'fields' => array('Material.id','Material.nome', 'Material.quantidade', 'Material.barcode', 'ItemRemessa.*', 'Remessa.*'),
    'joins' => array(
      array(
        'table' => 'remessas',
        'alias' => 'Remessa',
        'type' => 'INNER',
        'conditions' => array('Remessa.id = ItemRemessa.remessas_id')
      ),
      array(
        'table' => 'materiais',
        'alias' => 'Material',
        'type' => 'INNER',
        'conditions' => array('Material.id = ItemRemessa.materiais_id')
      )
    ),
    'order' => array('Material.nome' => 'ASC')
  )));

  //$pedido = $itens;
  $pedido = $item['ItemRemessa']['remessas_id'];
  $this->set(compact('id','pedido'));

  $this->loadModel('Entrada');
  $this->set('entradas', $this->Entrada->find('all', array(
    'conditions' => array('Entrada.remessas_id' => $pedido),
    'fields' => array('Material.id','Material.nome', 'Material.barcode','Entrada.*'),
    'joins' => array(
      array(
        'table' => 'materiais',
        'alias' => 'Material',
        'type' => 'LEFT',
        'conditions' => array('Material.id = Entrada.materiais_id')
      )
    ),
    'order' => array('Material.nome' => 'ASC')
  )));

  $this->set('id', $id);

}

public function visualizar($id = null){

  $this->loadModel('Remessa');

  $this->Remessa->recursive = 0;
  $this->Paginator->settings = array(
    'conditions' => array('Remessa.id' => $id),
    'fields' => array('Remessa.*','ItemRemessa.remessas_id', 'ItemRemessa.pedidos_id', 'Setor.descricao'),
    'joins' => array(
      array(
        'table' => 'remessas_itens',
        'alias' => 'ItemRemessa',
        'type' => 'INNER',
        'conditions' => array('ItemRemessa.remessas_id = Remessa.id')
      ),
      array(
        'table' => 'setores',
        'alias' => 'Setor',
        'type' => 'INNER',
        'conditions' => array('Setor.id = ItemRemessa.setor_id')
      )
    ),
    'group' => array('ItemRemessa.pedidos_id'),
    'order' => array('Remessa.id' => 'ASC')
  );

  $this->set('remessas', $this->Paginator->paginate('Remessa'));

}

public function entrada() {
  $this->loadModel('ItemRemessa');
  $this->loadModel('Entrada');
  $this->loadModel('MaterialFilial');
  if ($this->request->is('post', 'put')) {

    $material = $this->request->data['Entrada'];
    // $material1 = $this->request->data['Material'];

    //pr($material);exit;

    $this->Entrada->create();
    if ($this->Entrada->save($this->request->data['Entrada'])) {;

      if($material['materiais_id']) {
        $this->loadModel('Material');
        $num = 0;
        $m = $this->MaterialFilial->find('first', array('conditions' => array('materiais_id' => $material['materiais_id'], $this->Session->read('Auth.User.setor'))));
        $mc = $this->Material->find('first', array('conditions' => array('id' => $material['materiais_id'])));

        $i = $this->ItemRemessa->find('first', array('conditions' => array('pedidos_id' => $material['pedidos_id'])));
        // if(isset($i['ItemRemessa']['quantidade'])) {
        //   $num = $i['ItemRemessa']['quantidade'];
        // }

        $this->ItemRemessa->updateAll(array('realizou_entrada' => 1), array('pedidos_id' => $material['pedidos_id'], 'materiais_id' =>$material['materiais_id']));
        $this->MaterialFilial->updateAll(array('quantidade' => (($m['MaterialFilial']['quantidade']) + $material['quantidade_entrada'])), array('materiais_id' => $m['MaterialFilial']['materiais_id'], 'setor_id' =>$this->Session->read('Auth.User.setor')));
        $this->Material->updateAll(array('quantidade_central' => (($mc['Material']['quantidade_central']) + $material['quantidade_entrada'])), array('id' => $mc['Material']['id']));

        echo $this::MSG_SUCESSO_EDT;
      }
          else {
        echo $this::MSG_ERRO;
      }
    }
  }
  exit;
}

public function delete_entrada($id = null) {
  // $this->loadModel('Material');
  $this->loadModel('Entrada');
  $this->loadModel('MaterialFilial');
  $this->loadModel('ItemRemessa');
  //$this->Entrada->id = $id;

  if ($this->request->is('post', 'delete')) {
    // pr($this->request->data);exit;
    $material = $this->request->data['Material'];
    $material1 = $this->request->data['Entrada'];
    // pr($material);exit;

    if($material['id']) {
      $this->loadModel('Material');
      $num = 0;
      $m = $this->MaterialFilial->find('first', array('conditions' => array('materiais_id' => $material['id'], $this->Session->read('Auth.User.setor'))));
      $mc = $this->Material->find('first', array('conditions' => array('id' => $material['id'])));
      $i = $this->Entrada->find('first', array('conditions' => array('id' => $material1['id'])));
      // if(isset($i['ItemOrcamento']['quantidade'])) {
      //   $num = $i['ItemOrcamento']['quantidade'];
      // }

      $this->ItemRemessa->updateAll(array('realizou_entrada' => 0), array('pedidos_id' => $material['pedidos_id'], 'materiais_id' =>$material['id']));
      $this->MaterialFilial->updateAll(array('quantidade' => (($m['MaterialFilial']['quantidade']) - $material['quantidade'])), array('materiais_id' => $m['MaterialFilial']['materiais_id'], 'setor_id' =>$this->Session->read('Auth.User.setor')));
      $this->Material->updateAll(array('quantidade_central' => (($mc['Material']['quantidade_central']) - $material['quantidade'])), array('id' => $mc['Material']['id']));

      $this->Entrada->delete(array('id' => $material1['id']));
    }
  // $this->request->is('post', 'delete');
  if (!$this->Entrada->delete()) {
     $this->Session->setFlash(__($this::MSG_SUCESSO_DEL));
  } else {
      $this->Session->setFlash(__($this::MSG_ERRO));
  }
}
  return $this->redirect(array('action' => 'protocolos'));
}

public function transferencia($id = null){

  $this->layout="ajax";
  // $this->loadModel('Orcamento');
  // if (!$this->Orcamento->exists($id)) {
  //   throw new NotFoundException(__('Invalid orcamento'));
  // }

  // $this->loadModel('ItemTransferencia');
  // $this->request->data = $this->Transferencia->find('first', array('conditions' => array('Transferencia.' . $this->Transferencia->primaryKey => $id)));
  // $transferencia = $this->Transferencia->find('first', array('conditions' => array('Transferencia.situacao' => '1')));
  // $this->request->data['Transferencia']['id'] = isset($transferencia['Transferencia']['id']) ? $transferencia['Transferencia']['id'] : null;

  $this->loadModel('ItemRemessa');

  // $item = $this->ItemOrcamento->find('first', array('conditions' => array('ItemOrcamento.pedidos_id' => $id, 'ItemOrcamento.comprar' => '1')));
  // pr($item);exit;
  $this->loadModel('Transferencia');
  $transf = $this->Transferencia->find('first', array('conditions' => array('Transferencia.situacao' => '1')));

  $this->set('itens', $this->ItemRemessa->find('all', array(
    'conditions' => array(
      'ItemRemessa.pedidos_id' => $id
    ),
    'fields' => array('Material.id','Material.nome', 'Material.quantidade', 'Material.barcode',  'ItemRemessa.*'),
    'joins' => array(
      array(
        'table' => 'pedidos',
        'alias' => 'Pedido',
        'type' => 'INNER',
        'conditions' => array('Pedido.id = ItemRemessa.pedidos_id')
      ),
      array(
        'table' => 'materiais',
        'alias' => 'Material',
        'type' => 'INNER',
        'conditions' => array('Material.id = ItemRemessa.materiais_id')
      )
    ),
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
  $this->loadModel('ItemRemessa');
  $this->loadModel('ItemTransferencia');
  $this->loadModel('Transferencia');
  $this->loadModel('MaterialFilial');

  if ($this->request->is('post')) {
    $material = $this->request->data['ItemTransferencia'];

    $t = $this->Transferencia->find('first', array('conditions' => array('id' => $material['transferencia_id'])));

    $ma = $this->MaterialFilial->find('first', array('conditions' => array('materiais_id' => $material['materiais_id'], $this->Session->read('Auth.User.setor'))));

    if($ma['MaterialFilial']['quantidade'] - $material['quantidade_transferida'] < 0 ){
      $this->Session->setFlash('Quantidade insuficiente em estoque!!!');
      return $this->redirect(array('action' => 'protocolos'));
    }
    $this->ItemRemessa->updateAll(array('realizou_saida' => 1), array('pedidos_id' => $material['pedidos_id'], 'materiais_id' =>$material['materiais_id']));
    //pr($this->request->data);exit;
    $this->ItemTransferencia->create();
    if ($this->ItemTransferencia->save($this->request->data['ItemTransferencia'])) {
      $this->ItemRemessa->id = $this->request->data['ItemRemessa']['id'];

      //if($material['fornecido'][$key]) {
        $this->loadModel('Material');
        //$num = 0;
        $m = $this->MaterialFilial->find('first', array('conditions' => array('materiais_id' => $material['materiais_id'], $this->Session->read('Auth.User.setor'))));
        $mc = $this->Material->find('first', array('conditions' => array('id' => $material['materiais_id'])));
        $i = $this->ItemTransferencia->find('first', array('conditions' => array('id' => $material['transferencia_id'])));
        if(isset($i['ItemTransferencia']['quantidade_transferida'])) {
          $num = $i['ItemTransferencia']['quantidade_transferida'];
        }

        $this->MaterialFilial->updateAll(array('quantidade' => (($m['MaterialFilial']['quantidade']) - $material['quantidade_transferida'])), array('materiais_id' => $m['MaterialFilial']['materiais_id'], 'setor_id' =>$this->Session->read('Auth.User.setor')));
        $this->Material->updateAll(array('quantidade_central' => (($mc['Material']['quantidade_central']) - $material['quantidade_transferida'])), array('id' => $mc['Material']['id']));

        $this->loadModel('Saida');
        $this->Saida->create();
        //$this->Saida->deleteAll(array('pedidos_id' => $r['Pedido']['id'], 'materiais_id' => $material['id'][$key]), false);
        $this->Saida->save(array('Saida'=>array(
          'data_saida' => date('d/m/Y'),
          'materiais_id' => $material['materiais_id'],
          'quantidade_saida' => $material['quantidade_transferida'],
          'usuarios_id' => $t['Transferencia']['usuarios_id'],
          'localizacoes_id' => $t['Transferencia']['setores_id'],
          'equipamentos_id' => $material['equipamentos_id'],
          'pedidos_id' => $material['pedidos_id'],
          'transferencia_id' => $material['transferencia_id'],
          'setor_id' => $this->Session->read('Auth.User.setor'),
        )));
      //}

      // if ($this->ItemOrcamento->delete()) {
      //   echo $this::MSG_ERRO;
      // } else {
      //   echo $this::MSG_SUCESSO_EDT;
      // }
    } else {
      echo $this::MSG_SUCESSO_EDT;
    }
  }
  exit;
}

public function delete_transferencia($id = null) {
  $this->loadModel('ItemTransferencia');
  $this->loadModel('MaterialFilial');
  $this->loadModel('ItemRemessa');

  if ($this->request->is('post', 'delete')) {
    // pr($this->request->data);exit;

    $material = $this->request->data['ItemTransferencia'];
    $this->ItemRemessa->updateAll(array('realizou_saida' => 0), array('pedidos_id' => $material['pedidos_id'], 'materiais_id' =>$material['materiais_id']));
    // pr($material);exit;

    $this->loadModel('Material');
    $this->loadModel('Saida');

    $s = $this->Saida->find('first', array('conditions' => array('pedidos_id' => $material['pedidos_id'], 'materiais_id'=>$material['materiais_id'])));
    // pr($s);exit;
    //$num = 0;
    $m = $this->MaterialFilial->find('first', array('conditions' => array('materiais_id' => $material['materiais_id'], $this->Session->read('Auth.User.setor'))));
    $mc = $this->Material->find('first', array('conditions' => array('id' => $material['materiais_id'])));

    // $m = $this->Material->find('first', array('conditions' => array('id' => $material['materiais_id'])));
    $i = $this->ItemTransferencia->find('first', array('conditions' => array('id' => $material['transferencia_id'])));
    if(isset($i['ItemTransferencia']['quantidade_transferida'])) {
      $num = $i['ItemTransferencia']['quantidade_transferida'];
    }

    $this->MaterialFilial->updateAll(array('quantidade' => (($m['MaterialFilial']['quantidade']) + $material['quantidade_transferida'])), array('materiais_id' => $m['MaterialFilial']['materiais_id'], 'setor_id' =>$this->Session->read('Auth.User.setor')));
    $this->Material->updateAll(array('quantidade_central' => (($mc['Material']['quantidade_central']) + $material['quantidade_transferida'])), array('id' => $mc['Material']['id']));
    //$this->Saida->deleteAll(array('transferencia_id' => $material['transferencia_id'], 'materiais_id' => $material['materiais_id']), false);
    $this->Saida->delete(array('id' => $s['Saida']['id']));

    $this->ItemTransferencia->delete(array('id' => $material['id']));

  }

}

public function situacao_protocolo($id = null){

  $this->layout="ajax";
  $this->loadModel('Remessa');

  if ($this->request->is('post' , 'put')) {
    //pr($this->request->data);exit;

    //$this->loadModel('ItemCompra');

    $sql = "UPDATE remessas SET remessas.situacao_protocolo = {$this->request->data['Remessa']['situacao_protocolo']} WHERE remessas.id = '{$this->request->data['Remessa']['id']}'";
    $this->Remessa->query($sql);


    $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
    return $this->redirect(array('action' => 'protocolos'));
  }

  $this->set('remessas', $this->Remessa->find('first', array('conditions' => array('Remessa.id' => $id))));

}

public function imprimir($id = null){

  $this->loadModel('Remessa');

  $remessas = $this->Remessa->find('all', array(
    'conditions' => array('Remessa.id' => $id),
    'fields' => array('Remessa.*', 'ItemRemessa.*', 'Pedido.id', 'Pedido.data_hora_registro', 'Setor.*', 'Material.id', 'Material.nome', 'Material.barcode'),
    'joins' => array(
      array(
        'table' => 'remessas_itens',
        'alias' => 'ItemRemessa',
        'type' => 'INNER',
        'conditions' => array('ItemRemessa.remessas_id = Remessa.id')
      ),
      array(
        'table' => 'pedidos',
        'alias' => 'Pedido',
        'type' => 'INNER',
        'conditions' => array('Pedido.id = ItemRemessa.pedidos_id')
      ),
      array(
        'table' => 'setores',
        'alias' => 'Setor',
        'type' => 'LEFT',
        'conditions' => array('Setor.id = ItemRemessa.setor_id')
      ),
      array(
        'table' => 'materiais',
        'alias' => 'Material',
        'type' => 'LEFT',
        'conditions' => array('Material.id = ItemRemessa.materiais_id')
      )
    ),
    'order' => array('ItemRemessa.setor_id' => 'ASC')
  ));

  // pr($orcamentos[0]['Orcamento']['id']);exit;

    $data = date('d/m/Y', strtotime($remessas[0]['Remessa']['data_hora_registro']));


  $html = "";

  if(isset($remessas[0]['Remessa']['id'])){
    $html .= "<table cellspacing='0' cellpadding='0' style='width: 100%;' style='border-right: 1px solid #000;'>";

    $html .= "<tr>";
    $html .= "<td colspan='10' style='text-align: center;'><b>REMESSA DE MERCADORIAS PARA FILIAIS <br> DEPARTAMENTO DE COMPRAS - 2019<b/></td>";
    // $html .= "<td colspan='1' style='text-align: center;'><b>N&ordm;<b/></td>";
    // $html .= "<td colspan='2' style='text-align: center;'><b><b/></td>";
    $html .= "</tr>";

    $html .= "<tr>";
    $html .= "<td colspan='2' style='text-align: left;'><b>Protocolo N&ordm; {$remessas[0]['Remessa']['id']} <br> Filial 02/AC<b/></td>";
    $html .= "<td colspan='7' style='text-align: left;'><b>Data: {$data} <br> A/C: Ronaldo/ AC<b/></td>";
    $html .= "<td colspan='1' style='text-align: left;'><b>Volumes:<br><b/></td>";
    $html .= "</tr>";

    // $html .= "<tr>";
    // // $html .= "<td colspan='1' style='text-align: left;'><b>Filial 02/AC<b/></td>";
    // // $html .= "<td colspan='8' style='text-align: left;'><b>A/C: Ronaldo/ AC<b/></td>";
    // $html .= "<td colspan='1' style='text-align: left;'><b/></td>";
    // $html .= "</tr>";

    $html .= "  <tr>";
    $html .= "    <td colspan='1' style='text-align: center;'>Pedido</td>";
    $html .= "    <td colspan='1' style='text-align: center;'>Data do <br>Pedido</td>";
    $html .= "    <td colspan='1' style='text-align: center;'>Quantidade</td>";
    $html .= "    <td colspan='6' style='text-align: center;'>Descri&ccedil;&atilde;o</td>";
    $html .= "    <td colspan='1' style='text-align: center;'>Filial</td>";
    //$html .= "    <td></td>";
    $html .= "  </tr>";


    foreach ($remessas as $key => $value) {
      $html .= "<tr>";
      $html .= "  <td colspan='1' style='text-align: center;'>{$value['Pedido']['id']}</td>";
      $html .= "  <td colspan='1' style='text-align: center;'>{$value['Pedido']['data_hora_registro']} </td>";
      $html .= "  <td colspan='1' style='text-align: center;'>{$value['ItemRemessa']['quantidade']} </td>";
      $html .= "  <td colspan='6' style='text-align: center;'>{$value['Material']['nome']} </td>";
      $html .= "  <td colspan='1' style='text-align: center;'>{$value['Setor']['descricao']}</td>";
      $html .= "</tr>";

     }

     $html .= "<tr>";
     $html .= "<td colspan='10' style='text-align: left;'> Observa&ccedil;&atilde;o: {$remessas[0]['Remessa']['observacao']}</td>";
     $html .= "</tr>";

     $html .= "<tr>";
     $html .= "<td colspan='10'></td>";
     $html .= "</tr>";

     $html .= "</table>";
   }


     $mpdf = new mPDF();
     $mpdf->SetTitle('REQUISICAO DE MATERIAL DE CONSUMO');
     $mpdf->SetDisplayMode('fullpage');
     $mpdf->Image('logobritacal.png', 0, 0, 210, 297, 'png', '', true, false);
     $mpdf=new mPDF('utf-8', 'A4-P');
     // $mpdf->SetHTMLFooter('<div>' . date('d/m/Y H:i') . '<div style="margin-top: -20px;" align="right">P&aacute;gina {PAGENO}/{nb}</div><br/></div>');
     // $mpdf->AddPage('','','','','',null,null,25,15,0,0);
     $mpdf->WriteHTML("<style> th, td { border: 1px solid #000; padding: 2px; font-size: 14px; padding: 10px; text-transform: uppercase;} td { border-bottom: 0; border-right: 0;} </style>");
     $mpdf->WriteHTML($html);
     //$mpdf->Output('REQUISICAO DE MATERIAL DE CONSUMO.pdf', 'D');
     $mpdf->Output();
     exit;


}

public function upload() {
  $this->layout="ajax";
  $this->loadModel('Remessa');
    if (!empty($this->request->data)) {
      // pr($this->request->data['Remessa']['uploadfile'][0]['name']);exit;
      $nome = $this->request->data['Remessa']['uploadfile'][0]['name'];
      $indice = explode('.',$nome)[0];
      //pr($indice);exit;
      $this->Upload->upload($this->request->data['Remessa']['uploadfile']);

      $sql = "UPDATE remessas SET remessas.pdf = 1 WHERE remessas.id = '{$indice}'";
      $this->Remessa->query($sql);
      
      return $this->redirect(array('action' => 'index'));
    }
}



}
