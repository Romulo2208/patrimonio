<?php
App::uses('AppController', 'Controller');
require_once(ROOT . DS . 'patrimonio' . DS . 'Vendor' . DS . 'mpdf60' . DS . 'mpdf.php');

class CarregamentosController extends AppController {

  public $components = array('Paginator');


  public function index($search = null) {
    //pr($this->Session->confirm());exit;

    $option = array();
    if ($search) {
      $option['OR']['Carregamento.id'] = "{$search}";
    }

    $data = date('Y-m-d');

    if(!in_array($this->Session->read('Perfil.id'), array('11'))) {
    $this->Carregamento->recursive = 0;
    $this->Paginator->settings = array(
      'conditions' => array('Carregamento.data_hora_registro like' => "%{$data}%"),
      'fields' => array('Carregamento.*','Usuario.nome', 'Setor.descricao', 'Produto.descricao'),
      'joins' => array(
        array(
          'table' => 'admin.usuarios',
          'alias' => 'Usuario',
          'type' => 'INNER',
          'conditions' => array('Usuario.id = Carregamento.usuarios_id')
        ),
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'INNER',
          'conditions' => array('Setor.id = Carregamento.setores_id')
        ),
        array(
          'table' => 'produtos',
          'alias' => 'Produto',
          'type' => 'LEFT',
          'conditions' => array('Produto.id = Carregamento.produtos_id')
        )
      ),
      'group' => 'Carregamento.id'
    );

  }else{
    $this->Carregamento->recursive = 0;
    $this->Paginator->settings = array(
      'conditions' => array('Carregamento.situacao' => 0),
      'fields' => array('Carregamento.*','Usuario.nome', 'Setor.descricao', 'Produto.descricao'),
      'joins' => array(
        array(
          'table' => 'admin.usuarios',
          'alias' => 'Usuario',
          'type' => 'INNER',
          'conditions' => array('Usuario.id = Carregamento.usuarios_id')
        ),
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'INNER',
          'conditions' => array('Setor.id = Carregamento.setores_id')
        ),
        array(
          'table' => 'produtos',
          'alias' => 'Produto',
          'type' => 'LEFT',
          'conditions' => array('Produto.id = Carregamento.produtos_id')
        )
      ),
      'group' => 'Carregamento.id'
    );
  }

    $this->set('carregamentos', $this->Paginator->paginate('Carregamento', $option));
  }

  public function add($id = null) {
    $this->layout="ajax";

    $this->loadModel('Carregamento');
    if ($this->request->is('post')) {
      //pr($this->request->data);exit;


      $this->Carregamento->create();
      if ($r = $this->Carregamento->save($this->request->data)) {
        $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
        return $this->redirect(array('action' => 'index'));
      }else {
        $this->Session->setFlash(__($this::MSG_ERRO));
      }

      //return $this->redirect(array('action' => 'index'));
    }

    $this->loadModel('Produto');
    $this->set('produtos', $this->Produto->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('id'=>'asc'))));

    $this->loadModel('Setor');
    $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));
    $this->set('id', $id);


  }


  public function edit($id = null) {
    $this->layout="ajax";
    $this->loadModel('Carregamento');
    if (!$this->Carregamento->exists($id)) {
      throw new NotFoundException(__('Invalid transferencia'));
    }

    if ($this->request->is(array('post', 'put'))) {
      //pr($this->request->data);exit;

      if ($this->Carregamento->save($this->request->data)) {
        $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
        return $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__($this::MSG_ERRO));
      }
    } else {
      $this->request->data = $this->Carregamento->find('first', array('conditions' => array('Carregamento.' . $this->Carregamento->primaryKey => $id)));
    }

    $this->loadModel('Carregamento');
    $this->set('carregamentos', $this->Carregamento->find('all', array('conditions' => array('id' => $id))));

    $this->loadModel('Produto');
    $this->set('produtos', $this->Produto->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('id'=>'asc'))));
    //$this->set('materiais', $this->Material->find('all', array('fields' => array('id', 'nome', 'barcode'), 'order'=>array('nome'=>'asc'))));

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
    // $html .= "<img src='http://localhost/estoque/img/logobritacal.png'/ >";

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
    $html .= "<td colspan='9' style='text-align: right;'><img src='http://localhost/estoque/img/logobritacal.png' height='30' width='200';/ ></td>";
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

  public function relatorio_carregamento(){

    $this->loadModel('Carregamento');

    $data = date('Y-m-d');

      $this->set('carregamentos', $this->Carregamento->find('all', array(
      'conditions' => array('Carregamento.data_hora_registro like' => "%{$data}%"),
      'fields' => array('Carregamento.*','Usuario.nome', 'Setor.descricao', 'Produto.descricao'),
      'joins' => array(
        array(
          'table' => 'admin.usuarios',
          'alias' => 'Usuario',
          'type' => 'INNER',
          'conditions' => array('Usuario.id = Carregamento.usuarios_id')
        ),
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'INNER',
          'conditions' => array('Setor.id = Carregamento.setores_id')
        ),
        array(
          'table' => 'produtos',
          'alias' => 'Produto',
          'type' => 'LEFT',
          'conditions' => array('Produto.id = Carregamento.produtos_id')
        )
      ),
      'group' => 'Carregamento.id'
    )));


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

  public function carregou($id = null) {
    $this->loadModel('Carregamento');

    $this->request->onlyAllow('post', 'put');
    if ($id <>'') {
      $this->Carregamento->updateAll(array('situacao' => 1, 'carregador_id'=> $this->Session->read('Auth.User.id')), array('id' => $id, 'setores_id' =>$this->Session->read('Auth.User.setor')));
      $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
      return $this->redirect(array('action' => 'index'));
    } else {
      $this->Session->setFlash(__($this::MSG_ERRO));
    }



  }



}
