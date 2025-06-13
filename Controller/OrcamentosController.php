<?php
App::uses('AppController', 'Controller');
require_once(ROOT . DS . 'patrimonio' . DS . 'Vendor' . DS . 'mpdf60' . DS . 'mpdf.php');

class OrcamentosController extends AppController {

  public $components = array('Paginator');

  public function index($search = null) {

    $option = array();
    if ($search) {
      $option['OR']['Orcamento.id'] = "{$search}";
    }

    $this->loadModel('Orcamento');

    $options = array('Setor.id IS NULL');
    if($this->Session->read('Auth.User.setor') && in_array($this->Session->read('Perfil.id'), array('3'))) {
      $options['Setor.id'] = $this->Session->read('Auth.User.setor');
      unset($options[0]);
    } else if(!in_array($this->Session->read('Perfil.id'), array('3'))) {
      unset($options[0]);
    }

    $this->Orcamento->recursive = 0;
    $this->Paginator->settings = array(
      'conditions' => $options,
      'fields' => array('Orcamento.*','Usuario.nome'),
      'joins' => array(
        array(
          'table' => 'admin.usuarios',
          'alias' => 'Usuario',
          'type' => 'INNER',
          'conditions' => array('Usuario.id = Orcamento.usuarios_id')
        )
      ),
      'order' => array('Orcamento.id' => 'desc')
    );

    $this->set('orcamentos', $this->Paginator->paginate('Orcamento', $option));

  }

  public function edit($id = null) {
    $this->layout="ajax";
    $this->loadModel('Orcamento');
    $this->loadModel('ItemOrcamento');

    if ($this->request->is('post' , 'put')) {
      $situacao = $this->request->data['Orcamento']['situacao'];
      unset($this->request->data['Orcamento']);

      $r = $this->Orcamento->find('first', array('conditions' => array('id' => $id)));
      $this->ItemOrcamento->deleteAll(array('orcamentos_id' => $r['Orcamento']['id']), false);
      $this->Orcamento->updateAll(array('situacao' => $situacao), array('id' => $r['Orcamento']['id']));

      if ($r) {
        foreach ($this->request->data as $data) {
          foreach ($data['Item'] as $i) {
            $this->ItemOrcamento->create();
            $this->ItemOrcamento->save(array(
              'orcamentos_id' => $r['Orcamento']['id'],
              'fornecedores_id' => $data['Orcamento']['fornecedores_id'],
              'prazo_entrega' => $data['Orcamento']['prazo_entrega'],
              'pagamento' => $data['Orcamento']['pagamento'],
              'transportadora' => $data['Orcamento']['transportadora'],
              'materiais_id' => $i['materiais_id'],
              'pedidos_id' => $i['pedidos_id'],
              'setor_id' => $i['setor_id'],
              'quantidade' => $i['quantidade'],
              'equipamentos_id' => $i['equipamentos_id'],
              'unitario' => $i['unitario'],
              'total' => $i['total'],
              'comprar' => isset($i['comprar']) ? $i['comprar'] : 0,
              'realizou_remessa' => isset($i['realizou_remessa']) ? $i['realizou_remessa'] : 0,
            ));
          }
        }
      }

      $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
      return $this->redirect(array('action' => 'index'));
    }

    $orcamento = $this->Orcamento->find('first', array('conditions' => array('id' => $id)));

    $this->set('orcamentos',$this->Orcamento->find('all', array(
      'conditions' =>array('ItemOrcamento.orcamentos_id'=> $id, 'ItemOrcamento.unitario !=' =>"null"),
      'fields' => array('Orcamento.*','ItemOrcamento.*', 'Setor.descricao','Material.nome','Equipamento.descricao'),
      'joins' => array(
        array(
          'table' => 'orcamentos_itens',
          'alias' => 'ItemOrcamento',
          'type' => 'LEFT',
          'conditions' => array('ItemOrcamento.orcamentos_id = Orcamento.id')
        ),
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'LEFT',
          'conditions' => array('Setor.id = ItemOrcamento.setor_id')
        ),
        array(
          'table' => 'materiais',
          'alias' => 'Material',
          'type' => 'INNER',
          'conditions' => array('Material.id = ItemOrcamento.materiais_id')
        ),
        array(
          'table' => 'equipamentos',
          'alias' => 'Equipamento',
          'type' => 'LEFT',
          'conditions' => array('Equipamento.id = ItemOrcamento.equipamentos_id')
        )
      )
    )));


    $this->loadModel('Compra');
    $this->set('compras',$this->Compra->find('all', array(
      'conditions' =>array('ItemCompra.compras_id'=> $orcamento['Orcamento']['compras_id']),
      'fields' => array('Compra.*','ItemCompra.*', 'Pedido.id', 'Setor.*', 'Material.nome'),
      'joins' => array(
        array(
          'table' => 'compras_itens',
          'alias' => 'ItemCompra',
          'type' => 'LEFT',
          'conditions' => array('ItemCompra.compras_id = Compra.id')
        ),
        array(
          'table' => 'pedidos',
          'alias' => 'Pedido',
          'type' => 'LEFT',
          'conditions' => array('Pedido.id = ItemCompra.pedidos_id')
        ),
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'LEFT',
          'conditions' => array('Setor.id = Pedido.setores_id')
        ),
        array(
          'table' => 'materiais',
          'alias' => 'Material',
          'type' => 'INNER',
          'conditions' => array('Material.id = ItemCompra.materiais_id')
        )
      ),
      'order' => array('ItemCompra.pedidos_id')
    )));

    $this->loadModel('Fornecedor');
    $this->set('fornecedores', $this->Fornecedor->find('list', array('fields' => array('id', 'nome_fantasia'), 'order'=>array('nome_fantasia'=>'asc'))));

    $this->loadModel('Setor');
    $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'conditions' => array('Setor.id = 1'), 'order'=>array('descricao'=>'asc'))));

    $situacao = $orcamento['Orcamento']['situacao'];
    $this->set(compact('id','situacao'));
  }

  public function protocolos() {

    $this->loadModel('Orcamento');

    // $options = array('Setor.id IS NULL');
    // if($this->Session->read('Auth.User.setor') && in_array($this->Session->read('Perfil.id'), array('3'))) {
    //   $options['Setor.id'] = $this->Session->read('Auth.User.setor');
    //   unset($options[0]);
    // } else if(!in_array($this->Session->read('Perfil.id'), array('3'))) {
    //   unset($options[0]);
    // }

    $this->Orcamento->recursive = 0;
    $this->Paginator->settings = array(
      'conditions' => array('Orcamento.situacao' => '2'),
      'fields' => array('Orcamento.*','Usuario.nome'),
      'joins' => array(
        array(
          'table' => 'admin.usuarios',
          'alias' => 'Usuario',
          'type' => 'INNER',
          'conditions' => array('Usuario.id = Orcamento.usuarios_id')
        )
      ),
      'order' => array('Orcamento.id' => 'ASC')
    );

    $this->set('orcamentos', $this->Paginator->paginate('Orcamento'));

  }

  public function abrir($id = null) {
    $this->layout="ajax";
    $this->loadModel('Orcamento');

    $this->Orcamento->recursive = 0;
    $this->Paginator->settings = array(
      'conditions' => array('Orcamento.id' => $id, 'ItemOrcamento.comprar' => '1'),
      'fields' => array('Orcamento.*', 'ItemOrcamento.*', 'Pedido.id', 'Pedido.data_hora_registro', 'Setor.*', 'Material.id', 'Material.nome'),
      'joins' => array(
        array(
          'table' => 'orcamentos_itens',
          'alias' => 'ItemOrcamento',
          'type' => 'INNER',
          'conditions' => array('ItemOrcamento.orcamentos_id = Orcamento.id')
        ),
        array(
          'table' => 'pedidos',
          'alias' => 'Pedido',
          'type' => 'INNER',
          'conditions' => array('Pedido.id = ItemOrcamento.pedidos_id')
        ),
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'LEFT',
          'conditions' => array('Setor.id = ItemOrcamento.setor_id')
        ),
        array(
          'table' => 'materiais',
          'alias' => 'Material',
          'type' => 'LEFT',
          'conditions' => array('Material.id = ItemOrcamento.materiais_id')
        )
      ),
      'order' => array('Orcamento.id' => 'ASC')
    );

    $this->set('orcamentos', $this->Paginator->paginate('Orcamento'));

    // pr($orcamentos);exit;
  }

  public function separar($id = null){

    $this->layout="ajax";
    $this->loadModel('Orcamento');
    if (!$this->Orcamento->exists($id)) {
      throw new NotFoundException(__('Invalid orcamento'));
    }

    $this->loadModel('Entrada');
    // $this->request->data = $this->Pedido->find('first', array('conditions' => array('Pedido.' . $this->Pedido->primaryKey => $id)));
    // $compra = $this->Compra->find('first', array('conditions' => array('Compra.situacao' => '1')));
    // $this->request->data['Compra']['id'] = isset($compra['Compra']['id']) ? $compra['Compra']['id'] : null;

    $this->loadModel('ItemOrcamento');

    $item = $this->ItemOrcamento->find('first', array('conditions' => array('ItemOrcamento.orcamentos_id' => $id, 'ItemOrcamento.comprar' => '1')));

    $this->set('itens', $this->ItemOrcamento->find('all', array(
      'conditions' => array(
        'ItemOrcamento.orcamentos_id' => $id,
        'ItemOrcamento.comprar' => '1'
      ),
      'fields' => array('Material.id','Material.nome', 'Material.quantidade', 'Material.barcode', 'ItemOrcamento.*', 'Orcamento.*'),
      'joins' => array(
        array(
          'table' => 'orcamentos',
          'alias' => 'Orcamento',
          'type' => 'INNER',
          'conditions' => array('Orcamento.id = ItemOrcamento.orcamentos_id')
        ),
        array(
          'table' => 'materiais',
          'alias' => 'Material',
          'type' => 'INNER',
          'conditions' => array('Material.id = ItemOrcamento.materiais_id')
        )
      ),
      'order' => array('Material.nome' => 'ASC')
    )));

    //$pedido = $itens;
    $pedido = $item['ItemOrcamento']['orcamentos_id'];
    $this->set(compact('id','pedido'));

    $this->loadModel('Entrada');
    $this->set('entradas', $this->Entrada->find('all', array(
      'conditions' => array('Entrada.orcamentos_id' => $pedido),
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

  public function pedido() {
    $this->loadModel('ItemOrcamento');
    $this->loadModel('Entrada');
    if ($this->request->is('post', 'put')) {

      $material = $this->request->data['Entrada'];
      // $material1 = $this->request->data['Material'];

      //pr($material);exit;

      $this->Entrada->create();
      if ($this->Entrada->save($this->request->data['Entrada'])) {;

        if($material['materiais_id']) {
          $this->loadModel('Material');
          $num = 0;
          $m = $this->Material->find('first', array('conditions' => array('id' => $material['materiais_id'])));
          $i = $this->ItemOrcamento->find('first', array('conditions' => array('pedidos_id' => $material['pedidos_id'])));
          // if(isset($i['ItemOrcamento']['quantidade'])) {
          //   $num = $i['ItemOrcamento']['quantidade'];
          // }

          $this->Material->updateAll(array('quantidade' => (($m['Material']['quantidade']) + $material['quantidade_entrada'])), array('id' => $m['Material']['id']));
        }
        //$this->ItemOrcamento->pedidos_id = $this->request->data['ItemOrcamento']['pedidos_id'];
        if (!$this->ItemOrcamento->delete()) {
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

  public function delete($id = null) {
    // $this->loadModel('Material');
    $this->loadModel('Entrada');
    //$this->Entrada->id = $id;

    if ($this->request->is('post', 'delete')) {
      // pr($this->request->data);exit;
      $material = $this->request->data['Material'];
      $material1 = $this->request->data['Entrada'];
      // pr($material);exit;

      if($material['id']) {
        $this->loadModel('Material');
        $num = 0;
        $m = $this->Material->find('first', array('conditions' => array('id' => $material['id'])));
        $i = $this->Entrada->find('first', array('conditions' => array('id' => $material1['id'])));
        // if(isset($i['ItemOrcamento']['quantidade'])) {
        //   $num = $i['ItemOrcamento']['quantidade'];
        // }

        $this->Material->updateAll(array('quantidade' => (($m['Material']['quantidade']) - $material['quantidade'])), array('id' => $m['Material']['id']));
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

  public function visualizar($id = null){

    $this->loadModel('Orcamento');

    $this->Orcamento->recursive = 0;
    $this->Paginator->settings = array(
      'conditions' => array('Orcamento.id' => $id),
      'fields' => array('Orcamento.*','ItemOrcamento.orcamentos_id', 'ItemOrcamento.pedidos_id', 'Setor.descricao'),
      'joins' => array(
        array(
          'table' => 'orcamentos_itens',
          'alias' => 'ItemOrcamento',
          'type' => 'INNER',
          'conditions' => array('ItemOrcamento.orcamentos_id = Orcamento.id')
        ),
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'INNER',
          'conditions' => array('Setor.id = ItemOrcamento.setor_id')
        )
      ),
      'group' => array('ItemOrcamento.pedidos_id'),
      'order' => array('Orcamento.id' => 'ASC')
    );

    $this->set('orcamentos', $this->Paginator->paginate('Orcamento'));

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

    $this->loadModel('ItemOrcamento');

    // $item = $this->ItemOrcamento->find('first', array('conditions' => array('ItemOrcamento.pedidos_id' => $id, 'ItemOrcamento.comprar' => '1')));
    // pr($item);exit;
    $this->loadModel('Transferencia');
    $transf = $this->Transferencia->find('first', array('conditions' => array('Transferencia.situacao' => '1')));

    $this->set('itens', $this->ItemOrcamento->find('all', array(
      'conditions' => array(
        'ItemOrcamento.pedidos_id' => $id,
        'ItemOrcamento.comprar' => '1'
      ),
      'fields' => array('Material.id','Material.nome', 'Material.quantidade', 'Material.barcode',  'ItemOrcamento.*'),
      'joins' => array(
        array(
          'table' => 'pedidos',
          'alias' => 'Pedido',
          'type' => 'INNER',
          'conditions' => array('Pedido.id = ItemOrcamento.pedidos_id')
        ),
        array(
          'table' => 'materiais',
          'alias' => 'Material',
          'type' => 'INNER',
          'conditions' => array('Material.id = ItemOrcamento.materiais_id')
        )
      ),
      'order' => array('Material.nome' => 'ASC')
    )));

    $this->loadModel('ItemTransferencia');
    $this->set('transferencias', $this->ItemTransferencia->find('all', array(
      'conditions' => array('Transferencia.situacao' => '1'),
      'fields' => array('Material.id','Material.nome','ItemTransferencia.*'),
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

  public function delete_transferencia($id = null) {
    $this->loadModel('ItemTransferencia');

    if ($this->request->is('post', 'delete')) {
      // pr($this->request->data);exit;

      $material = $this->request->data['ItemTransferencia'];

      $this->ItemTransferencia->delete(array('id' => $material['id']));

    }

  }

  public function transferencia_filial(){
    $this->loadModel('ItemOrcamento');
    $this->loadModel('ItemTransferencia');
    if ($this->request->is('post')) {
      $this->ItemTransferencia->create();
      if ($this->ItemTransferencia->save($this->request->data['ItemTransferencia'])) {
        $this->ItemOrcamento->id = $this->request->data['ItemOrcamento']['id'];
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

  public function remessa($id = null){

    $this->layout="ajax";
    // $this->loadModel('Orcamento');
    // if (!$this->Orcamento->exists($id)) {
    //   throw new NotFoundException(__('Invalid orcamento'));
    // }

    // $this->loadModel('ItemTransferencia');
    // $this->request->data = $this->Transferencia->find('first', array('conditions' => array('Transferencia.' . $this->Transferencia->primaryKey => $id)));
    // $transferencia = $this->Transferencia->find('first', array('conditions' => array('Transferencia.situacao' => '1')));
    // $this->request->data['Transferencia']['id'] = isset($transferencia['Transferencia']['id']) ? $transferencia['Transferencia']['id'] : null;

    $this->loadModel('ItemOrcamento');

    // $item = $this->ItemOrcamento->find('first', array('conditions' => array('ItemOrcamento.pedidos_id' => $id, 'ItemOrcamento.comprar' => '1')));
    // pr($item);exit;
    $this->loadModel('Remessa');
    $transf = $this->Remessa->find('first', array('conditions' => array('Remessa.situacao' => '1')));

    $this->set('itens', $this->ItemOrcamento->find('all', array(
      'conditions' => array(
        'ItemOrcamento.orcamentos_id' => $id,
        'ItemOrcamento.comprar' => '1',
        'Orcamento.situacao' => '2'
      ),
      'fields' => array('Material.id','Material.nome', 'Material.quantidade', 'Material.barcode', 'Orcamento.situacao', 'ItemOrcamento.*'),
      'joins' => array(
        array(
          'table' => 'orcamentos',
          'alias' => 'Orcamento',
          'type' => 'INNER',
          'conditions' => array('Orcamento.id = ItemOrcamento.orcamentos_id')
        ),
        array(
          'table' => 'pedidos',
          'alias' => 'Pedido',
          'type' => 'INNER',
          'conditions' => array('Pedido.id = ItemOrcamento.pedidos_id')
        ),
        array(
          'table' => 'materiais',
          'alias' => 'Material',
          'type' => 'INNER',
          'conditions' => array('Material.id = ItemOrcamento.materiais_id')
        )
      ),
      'order' => array('Material.nome' => 'ASC')
    )));

    $this->loadModel('ItemRemessa');
    $this->set('remessas', $this->ItemRemessa->find('all', array(
      'conditions' => array('Remessa.situacao' => '1'),
      'fields' => array('Material.id','Material.nome', 'Material.barcode','ItemRemessa.*'),
      'joins' => array(
        array(
          'table' => 'materiais',
          'alias' => 'Material',
          'type' => 'INNER',
          'conditions' => array('Material.id = ItemRemessa.materiais_id')
        ),
        array(
          'table' => 'remessas',
          'alias' => 'Remessa',
          'type' => 'INNER',
          'conditions' => array('Remessa.id = ItemRemessa.remessas_id')
        )
      ),
      'order' => array('Material.nome' => 'ASC')
    )));

    $this->set(compact('id', $id, 'transf' ));

  }

  public function remessa_central(){
    $this->loadModel('ItemOrcamento');
    $this->loadModel('ItemRemessa');
    if ($this->request->is('post')) {
      //pr($this->request->data);exit;
      $this->ItemRemessa->create();
      if ($this->ItemRemessa->save($this->request->data['ItemRemessa'])) {
        $this->ItemOrcamento->id = $this->request->data['ItemOrcamento']['id'];
        $this->ItemOrcamento->updateAll(array('realizou_remessa' => 1), array('orcamentos_id' => $this->request->data['ItemRemessa']['orcamentos_id'], 'materiais_id' =>$this->request->data['ItemRemessa']['materiais_id']));
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
  public function delete_remessa($id = null) {
    $this->loadModel('ItemRemessa');
    $this->loadModel('ItemOrcamento');

    if ($this->request->is('post', 'delete')) {
      // pr($this->request->data);exit;

      $material = $this->request->data['ItemRemessa'];

      $this->ItemOrcamento->updateAll(array('realizou_remessa' => 0), array('orcamentos_id' => $this->request->data['ItemRemessa']['orcamentos_id'], 'materiais_id' =>$this->request->data['ItemRemessa']['materiais_id']));

      $this->ItemRemessa->delete(array('id' => $material['id']));

    }

  }

  public function imprimir($id = null){

    $this->loadModel('Orcamento');

    $orcamentos = $this->Orcamento->find('all', array(
      'conditions' => array('Orcamento.id' => $id, 'ItemOrcamento.comprar' => '1'),
      'fields' => array('Orcamento.*', 'ItemOrcamento.*', 'Pedido.id', 'Pedido.data_hora_registro', 'Setor.*', 'Material.id', 'Material.nome'),
      'joins' => array(
        array(
          'table' => 'orcamentos_itens',
          'alias' => 'ItemOrcamento',
          'type' => 'INNER',
          'conditions' => array('ItemOrcamento.orcamentos_id = Orcamento.id')
        ),
        array(
          'table' => 'pedidos',
          'alias' => 'Pedido',
          'type' => 'INNER',
          'conditions' => array('Pedido.id = ItemOrcamento.pedidos_id')
        ),
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'LEFT',
          'conditions' => array('Setor.id = ItemOrcamento.setor_id')
        ),
        array(
          'table' => 'materiais',
          'alias' => 'Material',
          'type' => 'LEFT',
          'conditions' => array('Material.id = ItemOrcamento.materiais_id')
        )
      ),
      'order' => array('Orcamento.id' => 'ASC')
    ));

    // pr($orcamentos[0]['Orcamento']['id']);exit;


    $html = "";


      $html .= "<table cellspacing='0' cellpadding='0' style='width: 100%;' style='border-right: 1px solid #000;'>";

      $html .= "<tr>";
      $html .= "<td colspan='10' style='text-align: center;'><b>REMESSA DE MERCADORIAS PARA FILIAIS <br> DEPARTAMENTO DE COMPRAS - 2019<b/></td>";
      // $html .= "<td colspan='1' style='text-align: center;'><b>N&ordm;<b/></td>";
      // $html .= "<td colspan='2' style='text-align: center;'><b><b/></td>";
      $html .= "</tr>";

      $html .= "<tr>";
      $html .= "<td colspan='2' style='text-align: left;'><b>Protocolo N&ordm; {$orcamentos[0]['Orcamento']['id']} <br> Filial 02/AC<b/></td>";
      $html .= "<td colspan='7' style='text-align: left;'><b>Data: 16/07/2019 <br> A/C: Ronaldo/ AC<b/></td>";
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


      foreach ($orcamentos as $key => $value) {
        $html .= "<tr>";
        $html .= "  <td colspan='1' style='text-align: center;'>{$value['Pedido']['id']}</td>";
        $html .= "  <td colspan='1' style='text-align: center;'>{$value['Pedido']['data_hora_registro']} </td>";
        $html .= "  <td colspan='1' style='text-align: center;'>{$value['ItemOrcamento']['quantidade']} </td>";
        $html .= "  <td colspan='6' style='text-align: center;'>{$value['Material']['nome']} </td>";
        $html .= "  <td colspan='1' style='text-align: center;'>{$value['Setor']['descricao']}</td>";
        $html .= "</tr>";

       }

       $html .= "<tr>";
       $html .= "<td colspan='10'></td>";
       $html .= "</tr>";

       $html .= "</table>";


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

  public function relatorio_orcamento() {

    $this->loadModel('Orcamento');
    $this->loadModel('ItemOrcamento');


    if ($this->request->is('post')) {
      //pr($this->request->data);exit;

      $inicio = $this->date($this->request->data['orcamento']['inicio']);
      $final = $this->date($this->request->data['orcamento']['final']);
      //$fornecedor = $this->request->data['orcamento']['fornecedor'];

      // pr($inicio);exit;

      $this->set('orcamentos',$this->Orcamento->find('all', array(
        'conditions' =>array("Orcamento.data_hora_registro BETWEEN '$inicio' AND '$final'"),
        'fields' => array('Orcamento.*','ItemOrcamento.*', 'Fornecedor.*', 'Material.nome'),
        'joins' => array(
          array(
            'table' => 'orcamentos_itens',
            'alias' => 'ItemOrcamento',
            'type' => 'LEFT',
            'conditions' => array('ItemOrcamento.orcamentos_id = Orcamento.id')
          ),
          array(
            'table' => 'fornecedores',
            'alias' => 'Fornecedor',
            'type' => 'LEFT',
            'conditions' => array('Fornecedor.id = ItemOrcamento.fornecedores_id')
          ),
          array(
            'table' => 'materiais',
            'alias' => 'Material',
            'type' => 'LEFT',
            'conditions' => array('Material.id = ItemOrcamento.materiais_id')
          )
        ),
        'order' => array('ItemOrcamento.orcamentos_id')
      )));


    }

    $this->loadModel('Fornecedor');
    $this->set('fornecedores', $this->Fornecedor->find('list', array('fields' => array('id', 'nome_fantasia'), 'order'=>array('nome_fantasia'=>'asc'))));

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



}
