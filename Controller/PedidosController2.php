<?php

App::uses('AppController', 'Controller');
require_once(ROOT . DS . 'patrimonio' . DS . 'Vendor' . DS . 'mpdf60' . DS . 'mpdf.php');

class PedidosController extends AppController {

  public $components = array('Paginator');

  public function index() {
    $this->loadModel('Pedido');

    $options = array('Setor.id IS NULL');
    if($this->Session->read('Auth.User.setor') && in_array($this->Session->read('Perfil.id'), array('3'))) {
      $options['Setor.id'] = $this->Session->read('Auth.User.setor');
      unset($options[0]);
    } else if(!in_array($this->Session->read('Perfil.id'), array('3'))) {
      unset($options[0]);
    }

    $this->Pedido->recursive = 0;
    $this->Paginator->settings = array(
      'conditions' => $options,
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
        )
      ),
      'order' => array('Pedido.id' => 'DESC')
    );

    $this->set('pedidos', $this->Paginator->paginate('Pedido'));
  }

  public function add($id = null) {
    $this->layout="ajax";

    $this->loadModel('Pedido');
    if ($this->request->is('post')) {
      $material = $this->request->data['material'];
      unset($this->request->data['material']);

      //pr($material);exit;

      $this->Pedido->create();
      if ($r = $this->Pedido->save($this->request->data)) {
        if($material) {
          $this->loadModel('ItemPedido');
          foreach ($material['id'] as $key => $value) {
            if(!$material['id'][$key] && !$material['pedido'][$key]) {
                  continue;
            }

            //pr($material);exit;

            $this->ItemPedido->create();
            $this->ItemPedido->save(array('ItemPedido'=>array(
              'quantidade_pedido' => $material['pedido'][$key],
              'materiais_id' => $material['id'][$key],
              'pedidos_id' => $r['Pedido']['id'],
              'aplicacao' => $material['aplicacao'][$key],
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
    $this->set('materiais', $this->Material->find('list', array('fields' => array('id', 'nome'), 'order'=>array('nome'=>'asc'))));

    $this->loadModel('Setor');
    $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));
    $this->set('id', $id);
  }

  public function edit($id = null) {
    $this->layout="ajax";
    $this->loadModel('Pedido');
    if (!$this->Pedido->exists($id)) {
        throw new NotFoundException(__('Invalid pedido'));
    }

    if ($this->request->is(array('post', 'put'))) {
      // pr($this->request->data); exit;
      $material = $this->request->data['material'];
      unset($this->request->data['material']);

      if ($r = $this->Pedido->save($this->request->data)) {
        if($material) {
          $this->loadModel('ItemPedido');
          foreach ($material['id'] as $key => $value) {
            if(!$material['id'][$key] && !$material['pedido'][$key]) {
                  continue;
            }

            if($material['fornecido'][$key]) {
              $this->loadModel('Material');
              $num = 0;
              $m = $this->Material->find('first', array('conditions' => array('id' => $material['id'][$key])));
              $i = $this->ItemPedido->find('first', array('conditions' => array('id' => $material['item'][$key])));
              if(isset($i['ItemPedido']['quantidade_fornecido'])) {
                $num = $i['ItemPedido']['quantidade_fornecido'];
              }

              $this->Material->updateAll(array('quantidade' => (($num + $m['Material']['quantidade']) - $material['fornecido'][$key])), array('id' => $m['Material']['id']));
            }

            $this->ItemPedido->create();
            $this->ItemPedido->deleteAll(array('pedidos_id' => $r['Pedido']['id'], 'materiais_id' => $material['id'][$key]), false);
            $this->ItemPedido->save(array('ItemPedido'=>array(
              'pedidos_id' => $r['Pedido']['id'],
              'materiais_id' => $material['id'][$key],
              'quantidade_pedido' => $material['pedido'][$key],
              'quantidade_fornecido' => $material['fornecido'][$key],
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
    $this->set('materiais', $this->Material->find('list', array('fields' => array('id', 'nome'), 'order'=>array('nome'=>'asc'))));

    $this->loadModel('Setor');
    $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));
    $this->set('id', $id);

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
      'conditions' => array('ItemPedido.pedidos_id' => $this->request->data['Pedido']['id']),
      'fields' => array('Material.id','Material.nome','ItemPedido.*'),
      'joins' => array(
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
      'fields' => array('Material.id','Material.nome','ItemCompra.*'),
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
          'conditions' => array('Compra.id = ItemCompra.pedidos_id')
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
        // echo <img src="logobritacal.png"/>;

    $html = "";
    // $html .= "<img src='http://localhost/patrimonio/img/logobritacal.png'/ >";

    // <div style="width: 200px; float: right;"> echo $this->Html->image('logobritacal.png', array('class' => 'logo')); </div>

    // $html .= "<div style='text-align: left; position: absolute; width: 20%; font-size: 16px;'>EMPRESA</div>";
    // $html .= "<div style='text-align: center; position: absolute; width: 80%; font-size: 16px;'>REQUISI&Ccedil;&Atilde;O DE MATERIAL DE CONSUMO</div>";
    // $html .= "<div style='text-align: right; font-size: 16px; margin-bottom: 15px;'>Pedido n&ordm; {$pedido['Pedido']['id']}</div>";



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
    // $html .= "<td colspan='2' style='text-align: left;'><b>  <b/></td>";
    $html .= "<td colspan='9' style='text-align: right;'><img src='http://localhost/patrimonio/img/logobritacal.png' height='30' width='200';/ ></td>";
    // $html .= "<td colspan='3' style='text-align: left;'><b>  <b/></td>";
    $html .= "</tr>";
    $html .= "<tr>";
    $html .= "<td colspan='9' style='text-align: center;'> Aplica&ccedil;&atilde;o</td>";
    $html .= "</tr>";
    $html .= "<tr><td colspan='9'></td></tr>";
    $html .= "<tr>";
    $html .= "<td colspan='7'>Unidade Requisitante: <b>{$pedido['Setor']['descricao']}<b/></td>";
    $html .= "<td colspan='2'>C&oacute;digo setor: <b>{$pedido['Setor']['id']}</b></td>";
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
      //pr($value);exit;
      $html .= "<tr>";
      $html .= "<td style='text-align: center;'>".($key+1)."</td>";
      $html .= "<td style='text-align: center;'>{$value['ItemPedido']['quantidade_pedido']}</td>";
      // $html .= "<td style='text-align: center;'>{$value['Material']['id']}</td>";
      $html .= "<td colspan='5' style='text-align: center;'>{$value['Material']['nome']}</td>";
      $html .= "<td style='text-align: center;'>{$value['Material']['barcode']}</td>";
      $html .= "<td style='text-align: center;'>{$value['ItemPedido']['quantidade_fornecido']}</td>";
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

}
