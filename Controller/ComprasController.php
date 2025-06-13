<?php
App::uses('AppController', 'Controller');
require_once(ROOT . DS . 'patrimonio' . DS . 'Vendor' . DS . 'mpdf60' . DS . 'mpdf.php');

class ComprasController extends AppController {

  public $components = array('Paginator');

  public function index($search = null) {
    $option = array();
    if ($search) {
      $option['OR']['Compra.id'] = "{$search}";
      $option['OR']['ItemCompra.materiais_id'] = "{$search}";
    }

    $this->loadModel('Compra');

    $options = array('Setor.id IS NULL');
    if($this->Session->read('Auth.User.setor') && in_array($this->Session->read('Perfil.id'), array('3'))) {
      $options['Setor.id'] = $this->Session->read('Auth.User.setor');
      unset($options[0]);
    } else if(!in_array($this->Session->read('Perfil.id'), array('3'))) {
      unset($options[0]);
    }

    $this->Compra->recursive = 0;
    $this->Paginator->settings = array(
      'conditions' => $options,
      'fields' => array('Compra.*','Setor.descricao','Usuario.nome', 'Material.nome', 'ItemCompra.materiais_id'),
      'joins' => array(
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'INNER',
          'conditions' => array('Setor.id = Compra.setores_id')
        ),
        array(
          'table' => 'admin.usuarios',
          'alias' => 'Usuario',
          'type' => 'INNER',
          'conditions' => array('Usuario.id = Compra.usuarios_id')
        ),
        array(
          'table' => 'compras_itens',
          'alias' => 'ItemCompra',
          'type' => 'LEFT',
          'conditions' => array('ItemCompra.compras_id = Compra.id')
        ),
        array(
          'table' => 'materiais',
          'alias' => 'Material',
          'type' => 'LEFT',
          'conditions' => array('Material.id = ItemCompra.materiais_id')
        )
      ),
        //'order' => array('Compra.situacao' => 'ASC'),
        'group' => array('Compra.id'),
        'order' => "FIELD(Compra.situacao, '1','5','2','3','4'), Compra.data_hora_registro desc"
    );

    $this->set('compras', $this->Paginator->paginate('Compra', $option));
  }

  public function add($id = null) {
    $this->layout="ajax";

    $this->loadModel('Compra');
    if ($this->request->is('post')) {
      // pr($this->request->data);exit;
      $material = $this->request->data['material'];
      unset($this->request->data['material']);

      $this->Compra->create();
      if ($r = $this->Compra->save($this->request->data)) {
        if($material) {
          $this->loadModel('ItemCompra');
          foreach ($material['id'] as $key => $value) {
            if(!$material['id'][$key] && !$material['compra'][$key]) {
              continue;
            }

            $this->ItemCompra->create();
            $this->ItemCompra->save(array('ItemCompra'=>array(
              'quantidade_pedido' => $material['compra'][$key],
              'materiais_id' => $material['id'][$key],
              'compras_id' => $r['Compra']['id'],
              'aplicacao' => $material['aplicacao'][$key]
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
    $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'conditions' => array('Setor.id = 1'), 'order'=>array('descricao'=>'asc'))));
    $this->set('id', $id);
  }

  public function edit($id = null) {
    $this->layout="ajax";
    $this->loadModel('Compra');
    if (!$this->Compra->exists($id)) {
      throw new NotFoundException(__('Invalid compra'));
    }

    if ($this->request->is(array('post', 'put'))) {
      // pr($this->request->data);exit;
      $material = $this->request->data['material'];
      unset($this->request->data['material']);

      if ($r = $this->Compra->save($this->request->data)) {
        if($material) {
          $this->loadModel('ItemCompra');
          $this->ItemCompra->deleteAll(array('compras_id' => $r['Compra']['id']), false);
          foreach ($material['id'] as $key => $value) {
            if(!$material['id'][$key] && !$material['compra'][$key]) {
              continue;
            }

            if($material['fornecido'][$key]) {
              $this->loadModel('Material');
              $num = 0;
              $m = $this->Material->find('first', array('conditions' => array('id' => $material['id'][$key])));
              $i = $this->ItemCompra->find('first', array('conditions' => array('id' => $material['item'][$key])));
              if(isset($i['ItemCompra']['quantidade_fornecido'])) {
                $num = $i['ItemCompra']['quantidade_fornecido'];
              }

              $this->Material->updateAll(array('quantidade' => (($num + $m['Material']['quantidade']) - $material['fornecido'][$key])), array('id' => $m['Material']['id']));
            }

            $this->ItemCompra->create();
            //$this->ItemCompra->deleteAll(array('compras_id' => $r['Compra']['id'], 'materiais_id' => $material['id'][$key]), false);
            $this->ItemCompra->save(array('ItemCompra'=>array(
              'compras_id' => $r['Compra']['id'],
              'materiais_id' => $material['id'][$key],
              'quantidade_pedido' => $material['compra'][$key],
              'quantidade_fornecido' => $material['fornecido'][$key],
              'aplicacao' => $material['aplicacao'][$key],
              'equipamentos_id' => $material['equipamentos_id'][$key],
              'pedidos_id' => $material['pedidos_id'][$key],
              'comprou' => $material['comprou'][$key]
            )));
          }
        }

        $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
      } else {
        $this->Session->setFlash(__($this::MSG_ERRO));
      }

      return $this->redirect(array('action' => 'index'));
    } else {
      $this->request->data = $this->Compra->find('first', array('conditions' => array('Compra.' . $this->Compra->primaryKey => $id)));
    }

    $this->loadModel('ItemCompra');
    $this->set('itens', $this->ItemCompra->find('all', array('conditions' => array('compras_id' => $this->request->data['Compra']['id']))));

    $this->loadModel('Material');
    $this->set('materiais', $this->Material->find('all', array('fields' => array('id', 'nome', 'barcode'), 'order'=>array('nome'=>'asc'))));

    $this->loadModel('Setor');
    $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));
    $this->set('id', $id);

  }

  public function compras($id = null) {

    // pr($compra);exit;

    $this->loadModel('Orcamento');
    // pr($this->request->data);exit;


    $this->loadModel('Compra');
    $compra = $this->Compra->find('all', array(
      'conditions' =>array('ItemCompra.compras_id'=> $id),

      'fields' => array('Compra.*','ItemCompra.*', 'Pedido.id', 'Setor.descricao', 'Material.nome'),
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
    ));

    // pr($compra);exit;

    if ($this->request->is(array('post', 'put'))) {
      // pr($this->request->data['Teste']);exit;
      // pr($compra);exit;

      $mat = $this->request->data['Teste'];
      unset($this->request->data['Teste']);

      foreach ($compra as $compra) {
        $material[] = $compra['ItemCompra'];
        // pr($compra);exit;
      }
      unset($compra['ItemCompra']);
      pr($material);exit;
      // pr($this->request->data);exit;
      $this->Orcamento->create();
      if ($r = $this->Orcamento->save($this->request->data)) {
        if($material) {
          $this->loadModel('ItemOrcamento');
          foreach ($material as $key => $value) {

            // pr($material);exit;
            // pr($mat['prazo_entrega'][$key]);


            $this->ItemOrcamento->create();
            $this->ItemOrcamento->save(array('ItemOrcamento'=>array(
              'quantidade' => $material[$key]['quantidade_pedido'],
              'materiais_id' => $material[$key]['materiais_id'],
              // 'orcamentos_id' => $r['Orcamento']['id'],
              // 'fornecedores_id' => $mat['fornecedores_id'][$key],
              // 'prazo_entrega' => $mat['prazo_entrega'][$key],
              // 'cod_pagamento' => $mat['cod_pagamento'][$key],
              // 'trasnportadora' => $mat['trasnportadora'][$key],
              // // 'unitario' => $$mat['unitario'][$key],
              // 'total' => $mat['total'][$key],
            )));
          }
        }

        $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
      } else {
        $this->Session->setFlash(__($this::MSG_ERRO));
      }

      return $this->redirect(array('action' => 'index'));
    }


    $this->loadModel('Fornecedor');
    $fornecedores = $this->Fornecedor->find('list', array('fields' => array('id', 'nome_fantasia'), 'order' => array('nome_fantasia')));
    //pr($fornecedores);exit;

    //pr($compra);exit;

    $this->set(compact('compra', 'fornecedores'));


  }

  public function pesquisar($search = null){
    // pr($search);exit;
    $option = array();
    if ($search) {
      $option['Fornecedor.nome_fantasia LIKE'] = "%{$search}%";
    }

    // pr($search);exit;

    $this->loadModel('Fornecedor');
    $this->Fornecedor->recursive = 0;
    $this->Paginator->settings = array('limit' => '20', 'order' => 'nome_fantasia asc');
    $this->set('fornecedores', $this->Paginator->paginate('Fornecedor', $option));

  }

  public function cotacao($id = null){


    $this->loadModel('Compra');
    $this->set('compras', $this->Compra->find('all', array(
      'fields' => array('Classificacao.*', 'Compra.*', 'Material.*', 'ItemCompra.quantidade_pedido'),
      'conditions' =>array('ItemCompra.compras_id' => $id),
      'joins' => array(
        array(
            'table' => 'compras_itens',
            'alias' => 'ItemCompra',
            'type' => 'LEFT',
            'conditions' => array('ItemCompra.compras_id = Compra.id')
        ),
        array(
            'table' => 'materiais',
            'alias' => 'Material',
            'type' => 'LEFT',
            'conditions' => array('Material.id = ItemCompra.materiais_id')
        ),
          array(
              'table' => 'materiais_classificacoes',
              'alias' => 'Classificacao',
              'type' => 'LEFT',
              'conditions' => array('Classificacao.id = Material.classificacoes_id')
          )
      ),
      'order' => 'Material.nome asc'
    )));

  }

  public function imprimir($id = null) {
    $this->loadModel('Compra');
    if (!$this->Compra->exists($id)) {
      throw new NotFoundException(__('Invalid compra'));
    }

    $compra = $this->Compra->find('first', array(
      'conditions' => array(
        'Compra.' . $this->Compra->primaryKey => $id
      ),
      'fields' => array('Compra.*','Setor.id','Setor.descricao','Usuario.nome'),
      'joins' => array(
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'INNER',
          'conditions' => array('Setor.id = Compra.setores_id')
        ),
        array(
          'table' => 'admin.usuarios',
          'alias' => 'Usuario',
          'type' => 'INNER',
          'conditions' => array('Usuario.id = Compra.usuarios_id')
        )
      ),
    ));

    $this->loadModel('ItemCompra');
    $itens = $this->ItemCompra->find('all', array(
      'conditions' => array(
        'compras_id' => $compra['Compra']['id']
      ),
      'fields' => array('ItemCompra.*','Material.*','Pedido.id', 'Setor.descricao', 'Equipamento.descricao'),
      'joins' => array(
        array(
          'table' => 'materiais',
          'alias' => 'Material',
          'type' => 'INNER',
          'conditions' => array('Material.id = ItemCompra.materiais_id')
        ),
        array(
          'table' => 'pedidos',
          'alias' => 'Pedido',
          'type' => 'INNER',
          'conditions' => array('Pedido.id = ItemCompra.pedidos_id')
        ),
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'INNER',
          'conditions' => array('Setor.id = Pedido.setores_id')
        ),
        array(
          'table' => 'equipamentos',
          'alias' => 'Equipamento',
          'type' => 'LEFT',
          'conditions' => array('Equipamento.id = ItemCompra.equipamentos_id')
        )
      ),
      'order' => array('ItemCompra.pedidos_id' => 'DESC')
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
    $html .= "<td colspan='7' style='text-align: center;'><b>REQUISI&Ccedil;&Atilde;O DE COMPRA<b/></td>";
    $html .= "<td colspan='1' style='text-align: center;'><b>N&ordm;<b/></td>";
    $html .= "<td colspan='1' style='text-align: center;'><b>{$compra['Compra']['id']}<b/></td>";
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
    $html .= "<td colspan='7'>Unidade Requisitante: <b>{$compra['Setor']['descricao']}<b/></td>";
    $html .= "<td colspan='2'>C&oacute;digo setor: <b>{$compra['Setor']['id']}</b></td>";
    $html .= "</tr>";

    // $html .= "<tr>";
    // $html .= "<td></td>";
    // $html .= "<td colspan='6' style='text-align: center;'>MATERIAL</td>";
    // $html .= "<td colspan='2' style='text-align: center;'>QUANTIDADE</td>";
    // $html .= "</tr>";

    $html .= "<tr>";
    $html .= "<td colspan='1' style='text-align: center;'>Item</td>";
    $html .= "<td colspan='1' style='text-align: center;'>QNT</td>";
    $html .= "<td colspan='4' style='text-align: center;'>Descri&ccedil;&atilde;o de Mercadorias</td>";
    //$html .= "<td colspan='1' style='text-align: center;'>Codigo</td>";
    $html .= "<td colspan='1' style='text-align: center;'>Aplica&ccedil;&atilde;o</td>";
    $html .= "<td colspan='1' style='text-align: center;'>Solic.</td>";
    $html .= "<td colspan='1' style='text-align: center;'>Filial</td>";
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
      $html .= "<td colspan='1' style='text-align: center;'>".($key+1)."</td>";
      $html .= "<td colspan='1' style='text-align: center;'>{$value['ItemCompra']['quantidade_pedido']}</td>";
      // $html .= "<td style='text-align: center;'>{$value['Material']['id']}</td>";
      $html .= "<td colspan='4' style='text-align: center;'>{$value['Material']['nome']}</td>";
      //$html .= "<td colspan='1' style='text-align: center;'>{$value['Material']['barcode']}</td>";
      $html .= "<td colspan='1' style='text-align: center;'>{$value['Equipamento']['descricao']}</td>";
      $html .= "<td colspan='1' style='text-align: center;'>{$value['ItemCompra']['pedidos_id']}</td>";
      $html .= "<td colspan='1' style='text-align: center;'>{$value['Setor']['descricao']}</td>";
      $html .= "</tr>";
    }

    $html .= "<tr>";
    $html .= "<td colspan='9' style='text-align: LEFT;'> <b>Observa&ccedil;&atilde;o: {$compra['Compra']['observacao']}</td>";
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
    $mpdf=new mPDF('utf-8', 'A4-P');
    // $mpdf->SetHTMLFooter('<div>' . date('d/m/Y H:i') . '<div style="margin-top: -20px;" align="right">P&aacute;gina {PAGENO}/{nb}</div><br/></div>');
    // $mpdf->AddPage('','','','','',null,null,25,15,0,0);
    $mpdf->WriteHTML("<style> th, td { border: 1px solid #000; padding: 2px; font-size: 14px; padding: 10px; text-transform: uppercase;} td { border-bottom: 0; border-right: 0;} </style>");
    $mpdf->WriteHTML($html);
    //$mpdf->Output('REQUISICAO DE MATERIAL DE CONSUMO.pdf', 'D');
    $mpdf->Output();
    exit;
  }

  public function orcamentos($id = null) {
    $this->layout="ajax";
    $this->loadModel('Orcamento');
    $this->loadModel('ItemOrcamento');

    if ($this->request->is('post')) {

        // pr($this->request->data);exit;
      foreach ($this->request->data as $data) {
        //foreach ($data['Item'] as $i) {
        // pr($data['Item']);exit;
        $r = $this->Orcamento->find('first', array('conditions' => array('compras_id' => $data['Orcamento']['compras_id'])));
        //if($i['unitario'] != '') {
          $this->Orcamento->create();
          $r = $this->Orcamento->save(array(
            'compras_id' => $data['Orcamento']['compras_id'],
            'usuarios_id' => $data['Orcamento']['usuarios_id'],
            'data_hora_registro' => $data['Orcamento']['data_hora_registro'],
            'situacao' => $data['Orcamento']['situacao']
          ));
        //}

        //if($i['unitario'] != '') {
          foreach ($data['Item'] as $i) {
            if($i['unitario'] !='') {
            $this->ItemOrcamento->create();
            $this->ItemOrcamento->save(array(
              'orcamentos_id' => $r['Orcamento']['id'],
              'fornecedores_id' => $data['Orcamento']['fornecedores_id'],
              'prazo_entrega' => $data['Orcamento']['prazo_entrega'],
              'pagamento' => $data['Orcamento']['pagamento'],
              'transportadora' => $data['Orcamento']['transportadora'],
              'realizou_remessa' => $data['Orcamento']['realizou_remessa'],
              'materiais_id' => $i['materiais_id'],
              'pedidos_id' => $i['pedidos_id'],
              'setor_id' => $i['setor_id'],
              'quantidade' => $i['quantidade'],
              'equipamentos_id' => $i['equipamentos_id'],
              'unitario' => $i['unitario'],
              'total' => $i['total']
            ));

            $this->loadModel('ItemCompra');

            $sql = "UPDATE compras_itens SET compras_itens.comprou = 1 WHERE compras_itens.id = '{$i['id']}'";
            $this->ItemCompra->query($sql);


          }
        }
      }
    //}

      $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
      return $this->redirect(array('action' => 'index'));
    }

    $this->loadModel('Compra');
    $this->set('compras',$this->Compra->find('all', array(
      'conditions' =>array('ItemCompra.compras_id'=> $id),
      'fields' => array('Compra.*','ItemCompra.*', 'Pedido.id', 'Setor.*', 'Material.nome', 'Equipamento.descricao'),
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
        ),
        array(
          'table' => 'equipamentos',
          'alias' => 'Equipamento',
          'type' => 'LEFT',
          'conditions' => array('Equipamento.id = ItemCompra.equipamentos_id')
        )
      ),
      'order' => array('ItemCompra.pedidos_id')
    )));

    $this->loadModel('Fornecedor');
    $this->set('fornecedores', $this->Fornecedor->find('list', array('fields' => array('id', 'nome_fantasia'), 'order'=>array('nome_fantasia'=>'asc'))));

    $this->loadModel('Setor');
    $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'conditions' => array('Setor.id = 1'), 'order'=>array('descricao'=>'asc'))));
    $this->set('id', $id);
  }

}
