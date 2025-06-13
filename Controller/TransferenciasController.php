<?php
App::uses('AppController', 'Controller');
require_once(ROOT . DS . 'patrimonio' . DS . 'Vendor' . DS . 'mpdf60' . DS . 'mpdf.php');

class TransferenciasController extends AppController {

  public $components = array('Paginator');


  public function index($search = null) {

    $option = array();
    if ($search) {
      $option['OR']['Transferencia.id'] = "{$search}";
      $option['OR']['Transferencia.situacao LIKE'] = "%{$search}%";
      $option['OR']['Setor.descricao LIKE'] = "%{$search}%";
      $option['OR']['ItemTransferencia.materiais_id'] = "{$search}";
    }

    $this->loadModel('Transferencia');

    //$options = array('Setor.id IS NULL');
    //if($this->Session->read('Auth.User.setor') && in_array($this->Session->read('Perfil.id'), array('3'))) {
      //$options['Setor.id'] = $this->Session->read('Auth.User.setor');
      //unset($options[0]);
    //} else if(!in_array($this->Session->read('Perfil.id'), array('3'))) {
      //unset($options[0]);
    //}

    //pr($this->Session->read('Auth.User.id'));exit;
    if($this->Session->read('Auth.User.id') == 4546){
      $this->Transferencia->recursive = 0;
      $this->Paginator->settings = array(
        'conditions' => $option,
        'fields' => array('Transferencia.*','Usuario.nome', 'Setor.descricao'),
        'joins' => array(
          array(
            'table' => 'admin.usuarios',
            'alias' => 'Usuario',
            'type' => 'INNER',
            'conditions' => array('Usuario.id = Transferencia.usuarios_id')
          ),
          array(
            'table' => 'setores',
            'alias' => 'Setor',
            'type' => 'INNER',
            'conditions' => array('Setor.id = Transferencia.setores_id')
          ),
          array(
            'table' => 'transferencias_itens',
            'alias' => 'ItemTransferencia',
            'type' => 'LEFT',
            'conditions' => array('ItemTransferencia.transferencia_id = Transferencia.id')
          )
        ),
        'group' => 'Transferencia.id',
        'order' => "FIELD(Transferencia.situacao, '1','3','2'), Transferencia.data_hora_registro desc"
        //'order' => array('Transferencia.id' => 'DESC')
      );
    }elseif (!in_array($this->Session->read('Perfil.id'), array('2', '3'))) {
      $this->Transferencia->recursive = 0;
      $this->Paginator->settings = array(
        'conditions' => $option,
        'fields' => array('Transferencia.*','Usuario.nome', 'Setor.descricao'),
        'joins' => array(
          array(
            'table' => 'admin.usuarios',
            'alias' => 'Usuario',
            'type' => 'INNER',
            'conditions' => array('Usuario.id = Transferencia.usuarios_id')
          ),
          array(
            'table' => 'setores',
            'alias' => 'Setor',
            'type' => 'INNER',
            'conditions' => array('Setor.id = Transferencia.setores_id')
          ),
          array(
            'table' => 'transferencias_itens',
            'alias' => 'ItemTransferencia',
            'type' => 'LEFT',
            'conditions' => array('ItemTransferencia.transferencia_id = Transferencia.id')
          )
        ),
        'group' => 'Transferencia.id',
        'order' => "FIELD(Transferencia.situacao, '1','3','2'), Transferencia.data_hora_registro desc"
        //'order' => array('Transferencia.id' => 'DESC')
      );
    }else {
      $this->Transferencia->recursive = 0;
      $this->Paginator->settings = array(
        'conditions' => array('Transferencia.setores_id' => $this->Session->read('Auth.User.setor'), 'Transferencia.situacao' => 2),
        'fields' => array('Transferencia.*','Usuario.nome', 'Setor.descricao'),
        'joins' => array(
          array(
            'table' => 'admin.usuarios',
            'alias' => 'Usuario',
            'type' => 'INNER',
            'conditions' => array('Usuario.id = Transferencia.usuarios_id')
          ),
          array(
            'table' => 'setores',
            'alias' => 'Setor',
            'type' => 'INNER',
            'conditions' => array('Setor.id = Transferencia.setores_id')
          ),
          array(
            'table' => 'transferencias_itens',
            'alias' => 'ItemTransferencia',
            'type' => 'LEFT',
            'conditions' => array('ItemTransferencia.transferencia_id = Transferencia.id')
          )
        ),
        'group' => 'Transferencia.id',
        'order' => "FIELD(Transferencia.situacao, '1','3','2'), Transferencia.data_hora_registro desc"
        //'order' => array('Transferencia.id' => 'DESC')
      );

    }

    $this->set('transferencias', $this->Paginator->paginate('Transferencia'));
  }
  
  public function add($id = null) {
    $this->layout="ajax";

    $this->loadModel('Transferencia');
    if ($this->request->is('post')) {
      // pr($this->request->data);exit;
      $material = $this->request->data['material'];
      unset($this->request->data['material']);

      $this->Transferencia->create();
      if ($r = $this->Transferencia->save($this->request->data)) {
        if($material) {
          $this->loadModel('ItemTransferencia');
          foreach ($material['id'] as $key => $value) {
            if(!$material['id'][$key] && !$material['transferencia'][$key]) {
              continue;
            }

            $this->ItemTransferencia->create();
            $this->ItemTransferencia->save(array('ItemTransferencia'=>array(
              'quantidade_transferida' => $material['transferencia'][$key],
              'materiais_id' => $material['id'][$key],
              //'pedidos_id' => $material['id'][$key],
              //'orcamentos_id' => $material['id'][$key],
              'transferencia_id' => $r['Transferencia']['id']
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
  $this->loadModel('Transferencia');
  if (!$this->Transferencia->exists($id)) {
    throw new NotFoundException(__('Invalid transferencia'));
  }

  if ($this->request->is(array('post', 'put'))) {
    //pr($this->request->data);exit;
    $material = $this->request->data['material'];
    unset($this->request->data['material']);

    if ($r = $this->Transferencia->save($this->request->data)) {
      if($material) {
        $this->loadModel('ItemTransferencia');
        $this->ItemTransferencia->deleteAll(array('transferencia_id' => $r['Transferencia']['id']), false);
        foreach ($material['id'] as $key => $value) {
          if(!$material['id'][$key] && !$material['transferencia'][$key]) {
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

          $this->ItemTransferencia->create();
          // $this->ItemTransferencia->deleteAll(array('transferencia_id' => $r['Transferencia']['id'], 'materiais_id' => $material['id'][$key]), false);
          $this->ItemTransferencia->save(array('ItemTransferencia'=>array(
            'quantidade_transferida' => $material['transferencia'][$key],
            'materiais_id' => $material['id'][$key],
            'pedidos_id' => $material['pedidos_id'][$key],
            'orcamentos_id' => $material['orcamentos_id'][$key],
            'equipamentos_id' => $material['equipamentos_id'][$key],
            'transferencia_id' => $r['Transferencia']['id'],
            'realizar_ci' => $material['realizar_ci'][$key]
          )));
        }
      }

      $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
    } else {
      $this->Session->setFlash(__($this::MSG_ERRO));
    }

    return $this->redirect(array('action' => 'index'));
  } else {
    $this->request->data = $this->Transferencia->find('first', array('conditions' => array('Transferencia.' . $this->Transferencia->primaryKey => $id)));
  }

  $this->loadModel('ItemTransferencia');
  $this->set('itens', $this->ItemTransferencia->find('all', array('conditions' => array('transferencia_id' => $this->request->data['Transferencia']['id']))));

  $this->loadModel('Material');
  $this->set('materiais', $this->Material->find('list', array('fields' => array('id', 'nome'), 'order'=>array('nome'=>'asc'))));
  //$this->set('materiais', $this->Material->find('all', array('fields' => array('id', 'nome', 'barcode'), 'order'=>array('nome'=>'asc'))));

  $this->loadModel('Tipo');
  $this->set('tipos', $this->Tipo->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('id'=>'asc'))));

  $this->loadModel('Setor');
  $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));
  $this->set('id', $id);

}

public function imprimir($id = null) {
$this->loadModel('Transferencia');
if (!$this->Transferencia->exists($id)) {
    throw new NotFoundException(__('Invalid Transferencia'));
}

$tranferencia = $this->Transferencia->find('first', array(
  'conditions' => array(
    'Transferencia.' . $this->Transferencia->primaryKey => $id
  ),
  'fields' => array('Transferencia.*','Setor.id','Setor.descricao','Usuario.nome'),
  'joins' => array(
    array(
      'table' => 'setores',
      'alias' => 'Setor',
      'type' => 'INNER',
      'conditions' => array('Setor.id = Transferencia.setores_id')
    ),
    array(
      'table' => 'admin.usuarios',
      'alias' => 'Usuario',
      'type' => 'INNER',
      'conditions' => array('Usuario.id = Transferencia.usuarios_id')
    )
  ),
));

$setor_entrega = $this->Transferencia->find('first', array(
  'conditions' => array(
    'Transferencia.' . $this->Transferencia->primaryKey => $id
  ),
  'fields' => array('Transferencia.*','Setor.id','Setor.descricao'),
  'joins' => array(
    array(
      'table' => 'setores',
      'alias' => 'Setor',
      'type' => 'LEFT',
      'conditions' => array('Setor.id = Transferencia.setores_id_entrega')
    )
  ),
));

$this->loadModel('ItemTransferencia');
$itens = $this->ItemTransferencia->find('all', array(
  'conditions' => array(
    'transferencia_id' => $tranferencia['Transferencia']['id'], 'realizar_ci' => 1
  ),
  'fields' => array('ItemTransferencia.*','Material.*'),
  'joins' => array(
    array(
      'table' => 'materiais',
      'alias' => 'Material',
      'type' => 'INNER',
      'conditions' => array('Material.id = ItemTransferencia.materiais_id')
    )
  ),
));


$data = date('d/m/Y', strtotime($tranferencia['Transferencia']['data_hora_registro']));
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
$html .= "<td colspan='1' style='text-align: center;'><b>{$tranferencia['Transferencia']['id']}<b/></td>";
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
$html .= "<td colspan='9' style='text-align: right;'>Requisitante: <b>{$tranferencia['Setor']['descricao']}<b/></td>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td colspan='9' style='text-align: left;'> <b>Data: {$data}</td>";
$html .= "</tr>";
$html .= "<tr><td colspan='9'></td></tr>";
$html .= "<tr>";
$html .= "<td colspan='9' style='text-align: left;'>Unidade de Entrega: <b>{$setor_entrega['Setor']['descricao']}<b/></td>";
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
$html .= "<td colspan='1' style='text-align: center;'>N&ordm; Pedido</td>";
$html .= "<td colspan='6' style='text-align: center;'>Descri&ccedil;&atilde;o de Mercadorias</td>";
//$html .= "<td colspan='1' style='text-align: center;'>Codigo</td>";
//$html .= "<td colspan='1' style='text-align: center;'>Aplica&ccedil;&atilde;o</td>";
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
  $html .= "<td style='text-align: center;'>{$value['ItemTransferencia']['quantidade_transferida']}</td>";
  $html .= "<td style='text-align: center;'>{$value['ItemTransferencia']['pedidos_id']}</td>";
  // $html .= "<td style='text-align: center;'>{$value['Material']['id']}</td>";
  $html .= "<td colspan='6' style='text-align: center;'>{$value['Material']['nome']}</td>";
  //$html .= "<td style='text-align: center;'>{$value['Material']['barcode']}</td>";
  //$html .= "<td style='text-align: center;'></td>";
  $html .= "</tr>";
}

$html .= "<tr>";
$html .= "<td colspan='9' style='text-align: LEFT;'> <b>Observacao: {$tranferencia['Transferencia']['observacao']}</td>";
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

public function mapa_lubrificantes(){
  $this->loadModel('Saida');
  $this->loadModel('Material');
  $this->loadModel('MaterialFilial');

  //if ($this->request->is('post')) {


    $sql= "SELECT a.nome,
	   a.descricao,
       a.quantidade_central,
    (SELECT b.est_min
 		FROM materiais_filiais b
 		WHERE b.setor_id = 1 AND b.materiais_id = a.id ) as Minimo,
	(SELECT b.quantidade
 		FROM materiais_filiais b
 		WHERE b.setor_id = 2 AND b.materiais_id = a.id ) as Filial1,
	(SELECT b.quantidade
 		FROM materiais_filiais b
 		WHERE b.setor_id = 4 AND b.materiais_id = a.id ) as Filial4,
     (SELECT b.quantidade
 		FROM materiais_filiais b
 		WHERE b.setor_id = 5 AND b.materiais_id = a.id ) as Filial9,
	(SELECT b.quantidade
 		FROM materiais_filiais b
 		WHERE b.setor_id = 6 AND b.materiais_id = a.id ) as Emfol,
    (SELECT b.quantidade
 		FROM materiais_filiais b
 		WHERE b.setor_id = 7 AND b.materiais_id = a.id ) as Calta,
    (SELECT b.quantidade
 		FROM materiais_filiais b
 		WHERE b.setor_id = 8 AND b.materiais_id = a.id ) as Americal

FROM materiais a
WHERE a.classificacoes_id = 31";
$this->set('materiais',  $this->Material->query($sql));


  //}
}

public function entrada($id = null){

  $this->layout="ajax";
  $this->loadModel('Transferencia');
  if (!$this->Transferencia->exists($id)) {
    throw new NotFoundException(__('Invalid remessa'));
  }

  $this->loadModel('Entrada');
  // $this->request->data = $this->Pedido->find('first', array('conditions' => array('Pedido.' . $this->Pedido->primaryKey => $id)));
  // $compra = $this->Compra->find('first', array('conditions' => array('Compra.situacao' => '1')));
  // $this->request->data['Compra']['id'] = isset($compra['Compra']['id']) ? $compra['Compra']['id'] : null;

  $this->loadModel('ItemTransferencia');

  $item = $this->ItemTransferencia->find('first', array('conditions' => array('ItemTransferencia.transferencia_id' => $id)));

  $this->set('itens', $this->ItemTransferencia->find('all', array(
    'conditions' => array(
      'ItemTransferencia.transferencia_id' => $id
    ),
    'fields' => array('Material.id','Material.nome', 'Material.quantidade', 'Material.barcode', 'ItemTransferencia.*', 'Transferencia.*'),
    'joins' => array(
      array(
        'table' => 'transferencias',
        'alias' => 'Transferencia',
        'type' => 'INNER',
        'conditions' => array('Transferencia.id = ItemTransferencia.transferencia_id')
      ),
      array(
        'table' => 'materiais',
        'alias' => 'Material',
        'type' => 'INNER',
        'conditions' => array('Material.id = ItemTransferencia.materiais_id')
      )
    ),
    'order' => array('Material.nome' => 'ASC')
  )));

  //$pedido = $itens;
  $pedido = $item['ItemTransferencia']['transferencia_id'];
  $this->set(compact('id','pedido'));

  $this->loadModel('Entrada');
  $this->set('entradas', $this->Entrada->find('all', array(
    'conditions' => array('Entrada.transferencia_id' => $pedido),
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

public function entrada_filial() {
  $this->loadModel('ItemTransferencia');
  $this->loadModel('Entrada');
  $this->loadModel('MaterialFilial');
  if ($this->request->is('post', 'put')) {

    // pr($this->request->data['Entrada']);exit;

    $material = $this->request->data['Entrada'];
    // $material1 = $this->request->data['Material'];
    // pr($material);exit;

    $this->Entrada->create();
    if ($this->Entrada->save($this->request->data['Entrada'])) {


      if($material['materiais_id']) {
        $this->loadModel('Material');
        $num = 0;
        $m = $this->MaterialFilial->find('first', array('conditions' => array('materiais_id' => $material['materiais_id'], 'setor_id' => $material['setor_id'])));
        //pr($m);exit;
        //$mc = $this->Material->find('first', array('conditions' => array('id' => $material['materiais_id'])));

        $i = $this->ItemTransferencia->find('first', array('conditions' => array('pedidos_id' => $material['pedidos_id'])));
        // if(isset($i['ItemRemessa']['quantidade'])) {
        //   $num = $i['ItemRemessa']['quantidade'];
        // }

        $this->ItemTransferencia->updateAll(array('realizou_entrada' => 1), array('pedidos_id' => $material['pedidos_id'], 'materiais_id' =>$material['materiais_id']));
        $this->MaterialFilial->updateAll(array('quantidade' => (($m['MaterialFilial']['quantidade']) + $material['quantidade_entrada'])), array('materiais_id' => $m['MaterialFilial']['materiais_id'], 'setor_id' =>$this->Session->read('Auth.User.setor')));
        //$this->Material->updateAll(array('quantidade_central' => (($mc['Material']['quantidade_central']) + $material['quantidade_entrada'])), array('id' => $mc['Material']['id']));

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
  $this->loadModel('ItemTransferencia');
  //$this->Entrada->id = $id;

  if ($this->request->is('post', 'delete')) {
    // pr($this->request->data);exit;
    $material = $this->request->data['Material'];
    $material1 = $this->request->data['Entrada'];
    //pr($material);exit;

    if($material['id']) {
      $this->loadModel('Material');
      $num = 0;
      $m = $this->MaterialFilial->find('first', array('conditions' => array('materiais_id' => $material['id'], 'setor_id' => $this->Session->read('Auth.User.setor'))));
      //$mc = $this->Material->find('first', array('conditions' => array('id' => $material['id'])));
      $i = $this->Entrada->find('first', array('conditions' => array('id' => $material1['id'])));
      // if(isset($i['ItemOrcamento']['quantidade'])) {
      //   $num = $i['ItemOrcamento']['quantidade'];
      // }

      $this->ItemTransferencia->updateAll(array('realizou_entrada' => 0), array('transferencia_id' => $material['transferencia_id'], 'materiais_id' => $material['id']));
      $this->MaterialFilial->updateAll(array('quantidade' => (($m['MaterialFilial']['quantidade']) - $material['quantidade'])), array('materiais_id' => $m['MaterialFilial']['materiais_id'], 'setor_id' =>$this->Session->read('Auth.User.setor')));
      //$this->Material->updateAll(array('quantidade_central' => (($mc['Material']['quantidade_central']) - $material['quantidade'])), array('id' => $mc['Material']['id']));

      $this->Entrada->delete(array('id' => $material1['id']));
    }
  // $this->request->is('post', 'delete');
  if (!$this->Entrada->delete()) {
     $this->Session->setFlash(__($this::MSG_SUCESSO_DEL));
  } else {
      $this->Session->setFlash(__($this::MSG_ERRO));
  }
}
  return $this->redirect(array('action' => 'index'));
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
