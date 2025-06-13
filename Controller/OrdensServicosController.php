<?php
App::uses('AppController', 'Controller');
require_once(ROOT . DS . 'patrimonio' . DS . 'Vendor' . DS . 'mpdf60' . DS . 'mpdf.php');

class OrdensServicosController extends AppController {

  public $components = array('Paginator');


  public function index($search = null) {

    $option = array();
    if ($search) {
      $option['OR']['OrdemServico.id'] = "{$search}";
    }

    // if(!in_array($this->Session->read('Perfil.id'), array('11'))) {
    $this->loadModel('OrdemServico');

    if(!in_array($this->Session->read('Perfil.id'), array('2', '3'))) {
      $this->OrdemServico->recursive = 0;
      $this->Paginator->settings = array(
        'conditions' => array('OrdemServico.aprovacao' => 1),
        'fields' => array('OrdemServico.*','Usuario.nome', 'Setor.descricao'),
        'joins' => array(
          array(
            'table' => 'admin.usuarios',
            'alias' => 'Usuario',
            'type' => 'INNER',
            'conditions' => array('Usuario.id = OrdemServico.usuarios_id')
          ),
          array(
            'table' => 'setores',
            'alias' => 'Setor',
            'type' => 'INNER',
            'conditions' => array('Setor.id = OrdemServico.setores_id')
          )
        ),
        'group' => 'OrdemServico.id',
        'order' => 'OrdemServico.id desc'
      );

    }else {

    $this->OrdemServico->recursive = 0;
    $this->Paginator->settings = array(
      'conditions' => array('OrdemServico.setores_id' => $this->Session->read('Auth.User.setor')),
      'fields' => array('OrdemServico.*','Usuario.nome', 'Setor.descricao'),
      'joins' => array(
        array(
          'table' => 'admin.usuarios',
          'alias' => 'Usuario',
          'type' => 'INNER',
          'conditions' => array('Usuario.id = OrdemServico.usuarios_id')
        ),
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'INNER',
          'conditions' => array('Setor.id = OrdemServico.setores_id')
        )
      ),
      'group' => 'OrdemServico.id',
      'order' => 'OrdemServico.id desc'
    );

   }

    $this->set('servicos', $this->Paginator->paginate('OrdemServico', $option));


  }

  public function add($id = null) {
    $this->layout="ajax";

    $this->loadModel('OrdemServico');
    if ($this->request->is('post')) {
      // pr($this->request->data);exit;


      $this->OrdemServico->create();
      if ($r = $this->OrdemServico->save($this->request->data)) {

        $this->loadModel('ServicoItem');
        $this->ServicoItem->create();
        $this->ServicoItem->save(array('ServicoItem'=>array(
          'ordens_servicos_id' => $r['OrdemServico']['id']
        )));

        $this->loadModel('ServicoPeca');
        $this->ServicoPeca->create();
        $this->ServicoPeca->save(array('ServicoPeca'=>array(
          'ordens_servicos_id' => $r['OrdemServico']['id']
        )));

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
    $this->loadModel('OrdemServico');
    if (!$this->OrdemServico->exists($id)) {
      throw new NotFoundException(__('Invalid o.s'));
    }

    if ($this->request->is(array('post', 'put'))) {
      //pr($this->request->data);exit;

      if ($this->OrdemServico->save($this->request->data)) {
        $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
        return $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__($this::MSG_ERRO));
      }
    } else {
      $this->request->data = $this->OrdemServico->find('first', array('conditions' => array('OrdemServico.' . $this->OrdemServico->primaryKey => $id)));
    }

    $this->loadModel('OrdemServico');
    $this->set('servicos', $this->OrdemServico->find('all', array('conditions' => array('id' => $id))));


  }

  public function servico_itens($id = null) {
    $this->layout="ajax";
    $this->loadModel('ServicoItem');
    // pr($id);exit;
    // if (!$this->OrdemServico->exists($id)) {
    //   throw new NotFoundException(__('Invalid o.s'));
    // }

    $r = $this->ServicoItem->find('first', array('conditions' => array('ordens_servicos_id' => $id)));

    if ($this->request->is(array('post', 'put'))) {
      // pr($this->request->data);exit;

      $servico = $this->request->data['servico'];
      unset($this->request->data['servico']);

      //pr($servico);exit;

      if($servico) {
        $this->loadModel('ServicoItem');
        $this->ServicoItem->deleteAll(array('ordens_servicos_id' => $this->request->data['ServicoItem']['ordens_servicos_id']));

        foreach ($servico['descricao'] as $key => $value) {
          $this->ServicoItem->create();
          $this->ServicoItem->save(array('ServicoItem'=>array(
            'ordens_servicos_id' => $r['ServicoItem']['ordens_servicos_id'],
            'descricao' => $servico['descricao'][$key],
            'precos' => $servico['preco'][$key],
          )));

        }
        $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
        return $this->redirect(array('action' => 'index'));
      }else {
        $this->Session->setFlash(__($this::MSG_ERRO));
      }

    }else {
      $this->request->data = $this->ServicoItem->find('first', array('conditions' => array('ServicoItem.' . $this->ServicoItem->primaryKey => $id)));
    }

    //
    $this->loadModel('ServicoItem');
    $this->set('servicos', $this->ServicoItem->find('all', array('conditions' => array('ordens_servicos_id' => $id))));


  }

  public function servico_pecas($id = null) {
    $this->layout="ajax";
    $this->loadModel('ServicoPeca');
    // pr($id);exit;
    // if (!$this->ServicoPeca->exists($id)) {
    //   throw new NotFoundException(__('Invalid'));
    // }

    $r = $this->ServicoPeca->find('first', array('conditions' => array('ordens_servicos_id' => $id)));

    if ($this->request->is(array('post', 'put'))) {
      // pr($this->request->data);exit;

      $peca = $this->request->data['peca'];
      unset($this->request->data['peca']);

      //pr($servico);exit;

      if($peca) {
        $this->loadModel('ServicoPeca');
        $this->ServicoPeca->deleteAll(array('ordens_servicos_id' => $this->request->data['ServicoPeca']['ordens_servicos_id']));

        foreach ($peca['quantidade'] as $key => $value) {
          $this->ServicoPeca->create();
          $this->ServicoPeca->save(array('ServicoPeca'=>array(
            'ordens_servicos_id' => $r['ServicoPeca']['ordens_servicos_id'],
            'quantidade' => $peca['quantidade'][$key],
            'pecas_acessorios' => $peca['pecas_acessorios'][$key],
            'precos' => $peca['precos'][$key],
          )));

        }
        $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
        return $this->redirect(array('action' => 'index'));
      }else {
        $this->Session->setFlash(__($this::MSG_ERRO));
      }

    }

    //
    $this->loadModel('ServicoPeca');
    $this->set('pecas', $this->ServicoPeca->find('all', array('conditions' => array('ordens_servicos_id' => $id))));


  }

  public function imprimir_OLD2($id = null) {
    $this->loadModel('OrdemServico');
    $this->loadModel('ServicoItem');
    // if (!$this->Transferencia->exists($id)) {
    //   throw new NotFoundException(__('Invalid Transferencia'));
    // }
    //
    $ordem = $this->OrdemServico->find('first', array(
      'conditions' => array(
        'OrdemServico.' . $this->OrdemServico->primaryKey => $id
      ),
      'fields' => array('OrdemServico.*','Setor.id','Setor.descricao','Usuario.nome'),
      'joins' => array(
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'INNER',
          'conditions' => array('Setor.id = OrdemServico.setores_id')
        ),
        array(
          'table' => 'admin.usuarios',
          'alias' => 'Usuario',
          'type' => 'INNER',
          'conditions' => array('Usuario.id = OrdemServico.usuarios_id')
        )
      ),
    ));

    // pr($ordem['OrdemServico']['situacao']);exit;

    $servico = $this->ServicoItem->find('first', array(
      'conditions' => array('ServicoItem.ordens_servicos_id' => $id),
      'fields' => array('ServicoItem.*','OrdemServico.id'),
      'joins' => array(
        array(
          'table' => 'ordens_servicos',
          'alias' => 'OrdemServico',
          'type' => 'LEFT',
          'conditions' => array('OrdemServico.id = ServicoItem.ordens_servicos_id')
        )
      ),
    ));

    $this->loadModel('ServicoPeca');
    $pecas = $this->ServicoPeca->find('all', array(
      'conditions' => array('ServicoPeca.ordens_servicos_id' => $id),
      'fields' => array('ServicoPeca.*','OrdemServico.id'),
      'joins' => array(
        array(
          'table' => 'ordens_servicos',
          'alias' => 'OrdemServico',
          'type' => 'LEFT',
          'conditions' => array('OrdemServico.id = ServicoPeca.ordens_servicos_id')
        )
      ),
    ));


    // $data = date('d/m/Y', strtotime($tranferencia['Transferencia']['data_hora_registro']));
    // echo <img src="logobritacal.png"/>;

    //$situacao = if(isset($ordem['OrdemServico']['situacao'] == 1)){ echo "Oficina";};

    $html = "";
    $html .= "<table cellspacing='0' cellpadding='0' style='width: 100%;' style='border-right: 1px solid #000;'>";

    $html .= "<tr>";
    $html .= "<td colspan='9' style='width: 125%; height: 150px; vertical-align: top; border-bottom: 1px solid #000;'>
    <p><img src='http://localhost/patrimonio/img/logobritacal.png' height='30' width='100';/></p>
    <p><LEFT>MINERADORA AMERICAL LTDA</center></p>
    <p>Rua Gersino Rodrigues da Silva n 456</p>
    <p>CEP 73.900-00 Posse GO</p>
    <p>Fone Escritorio:</p>
    <p align='rigth'>Fone Industria:</p></td>";
    $html .= "<td colspan='4' style='width: 125%; height: 150px; vertical-align: top; border-bottom: 1px solid #000;'>
    <p><b><font size='3'>SERVI&Ccedil;O EXECULTADO &#32;&#32;&#32; ORDEM DE SERVI&Ccedil;O</font></b></p>
    <p></p>
    <p><b><font size='3'>Equipamento:</b> {$ordem['OrdemServico']['equipamento']}</font></b></p></td>";
    $html .= "</tr>";

    $html .= "<tr>";
    $html .= "<td colspan='9' style='width: 100%; height: 110px; vertical-align: top; border-bottom: 1px solid #000;'>
    <p><b><font size='2'>Fornecedor:</font></b> <font size='2'>{$ordem['OrdemServico']['fornecedor']} </font></p><br><br>
    <p><b><font size='2'>Observacoes:</font></b> <font size='2'>{$ordem['OrdemServico']['observacao']}  </font></p>
    </td>";
    $html .= "<td colspan='4' style='width: 100%; height: 110px; vertical-align: top; border-bottom: 1px solid #000;'>
    <p><b><font size='2'>Especifica&ccedil;&atilde;o dos Servi&ccedil;os:</font></b><br> <font size='2'>{$ordem['OrdemServico']['especificacao_servico']} </font></p><br><br>
    </td>";
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

  public function imprimir_OLD($id = null) {
    $this->loadModel('OrdemServico');
    $this->loadModel('ServicoItem');
    // if (!$this->Transferencia->exists($id)) {
    //   throw new NotFoundException(__('Invalid Transferencia'));
    // }
    //
    $ordem = $this->OrdemServico->find('first', array(
      'conditions' => array(
        'OrdemServico.' . $this->OrdemServico->primaryKey => $id
      ),
      'fields' => array('OrdemServico.*','Setor.id','Setor.descricao','Usuario.nome'),
      'joins' => array(
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'INNER',
          'conditions' => array('Setor.id = OrdemServico.setores_id')
        ),
        array(
          'table' => 'admin.usuarios',
          'alias' => 'Usuario',
          'type' => 'INNER',
          'conditions' => array('Usuario.id = OrdemServico.usuarios_id')
        )
      ),
    ));

    $servico = $this->ServicoItem->find('first', array(
      'conditions' => array('ServicoItem.ordens_servicos_id' => $id),
      'fields' => array('ServicoItem.*','OrdemServico.id'),
      'joins' => array(
        array(
          'table' => 'ordens_servicos',
          'alias' => 'OrdemServico',
          'type' => 'LEFT',
          'conditions' => array('OrdemServico.id = ServicoItem.ordens_servicos_id')
        )
      ),
    ));

    $this->loadModel('ServicoPeca');
    $pecas = $this->ServicoPeca->find('all', array(
      'conditions' => array('ServicoPeca.ordens_servicos_id' => $id),
      'fields' => array('ServicoPeca.*','OrdemServico.id'),
      'joins' => array(
        array(
          'table' => 'ordens_servicos',
          'alias' => 'OrdemServico',
          'type' => 'LEFT',
          'conditions' => array('OrdemServico.id = ServicoPeca.ordens_servicos_id')
        )
      ),
    ));


    // $data = date('d/m/Y', strtotime($tranferencia['Transferencia']['data_hora_registro']));
    // echo <img src="logobritacal.png"/>;

    $html = "";
    //$html .= "<table cellspacing='0' cellpadding='0' style='width: 100%;' style='border-right: 1px solid #000;'>";


    $html .= "<div class='span12'>";
    $html .= "<div class='tabbable'>";
    //
    $html .= "<div class='row-fluid'>";
    $html .= "<div class='span6'>";
    $html .= "<p>Testando</p>";
    // $html .= "<div>";
    // $html .= "<p align='left'> <img src='http://localhost/patrimonio/img/logoamerical.jpeg' height='20' width='100';/ ><b>                     <u>MINERADORA AMERICAL LTDA</u></b></p>";
    // $html .= "<p align='center'>Rua Gersino Rodrigues da Silva- n 456</p>";
    // $html .= "<p align='center'>CEP:73900-000 - Posse GO</p>";
    // $html .= "<p align='left'><b>Fone Escritorio</b></p>";
    // $html .= "<p align='right'><b>Fone Industria</b></p>";
    // $html .= "</div>";
    // $html .= "<div>";
    // $html .= "<p align='left'><b>Fornecedor:</b></p>";
    // $html .= "<br><br>";
    // $html .= "<p align='left'><b>Observacoes:</b></p>";
    // $html .= "</div>";
    // $html .= "<table class='table table-striped table-bordered table-hover'>";
    // $html .= "<thead>";
    // $html .= "<tr>";
    // $html .= "<td>Descrimina&ccedil;&atilde;o dos Servi&ccedil;os</td>";
    // $html .= "<td>Pre&ccedil;os</td>";
    // $html .= "</tr>";
    // $html .= "</thead>";
    // $html .= "</table>";
    // $html .= "<p align='right'><b>TOTAL:</b></p>";
    $html .= "</div>";
    //
    $html .= "<div class='span6'>";
    $html .= "<p>FAzendo testes</p>";
    // $html .= "<div class='span6'>";
    // $html .= "<p align='left'><b>Servi&ccedil;o Execultado</b></p>";
    // $html .= "<p align='left'><b>Equipamento</b></p>";
    // $html .= "</div>";
    // $html .= "<div class='span6'>";
    // $html .= "<p align='center'><b>ORDEM DE SERVI&Ccedil;O</b></p>";
    // $html .= "<br>";
    // $html .= "<p align='center'><b>DATA DE ENTRADA</b></p>";
    // $html .= "</div>";
    //
    // $html .= "<table class='table table-striped table-bordered table-hover'>";
    // $html .= "<thead>";
    // $html .= "<tr>";
    // $html .= "<td>Quant.</td>";
    // $html .= "<td style='text-align: center;'>Pe&ccedil;as e Acessorios</td>";
    // $html .= "<td>Pre&ccedil;os</td>";
    // $html .= "</tr>";
    // $html .= "</thead>";
    // $html .= "</table>";
    //
    // $html .= "<p align='right'><b>TOTAL:</b></p>";
    // $html .= "<br>";
    // $html .= "<p align='left'><b>Conclus&atilde;o:</b></p>";
    // $html .= "<br><br>";
    // $html .= "<p align='right'><b>DATA</b></p>";
    // $html .= "<div class='span5'>";
    // $html .= "<p align='left'><b>___________________________</b></p>";
    // $html .= "<p align='left'><b>       GERENTE FILIAL</b></p>";
    // $html .= "</div>";
    // $html .= "<div class='span5'>";
    // $html .= "<p align='right'><b>___________________________</b></p>";
    // $html .= "<p align='right'><b>       EXECULTADO POR</b></p>";
    $html .= "</div>";
    // $html .= "</div>";
    // $html .= "</div>";
    $html .= "</div>";
    $html .= "</div>";
     $html .= "</div>";
    //$html .= '</table>';

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

  public function imprimir($id = null) {
    $this->loadModel('OrdemServico');
    $this->loadModel('ServicoItem');
    // if (!$this->Transferencia->exists($id)) {
    //   throw new NotFoundException(__('Invalid Transferencia'));
    // }
    //
  $this->set('ordem', $this->OrdemServico->find('first', array(
      'conditions' => array(
        'OrdemServico.' . $this->OrdemServico->primaryKey => $id
      ),
      'fields' => array('OrdemServico.*','Setor.*','Usuario.nome'),
      'joins' => array(
        array(
          'table' => 'setores',
          'alias' => 'Setor',
          'type' => 'INNER',
          'conditions' => array('Setor.id = OrdemServico.setores_id')
        ),
        array(
          'table' => 'admin.usuarios',
          'alias' => 'Usuario',
          'type' => 'INNER',
          'conditions' => array('Usuario.id = OrdemServico.usuarios_id')
        )
      ),
    )));

    $this->set('servico', $this->ServicoItem->find('all', array(
      'conditions' => array('ServicoItem.ordens_servicos_id' => $id),
      'fields' => array('ServicoItem.*','OrdemServico.id'),
      'joins' => array(
        array(
          'table' => 'ordens_servicos',
          'alias' => 'OrdemServico',
          'type' => 'LEFT',
          'conditions' => array('OrdemServico.id = ServicoItem.ordens_servicos_id')
        )
      ),
    )));

    $this->loadModel('ServicoPeca');
    $this->set('pecas', $this->ServicoPeca->find('all', array(
      'conditions' => array('ServicoPeca.ordens_servicos_id' => $id),
      'fields' => array('ServicoPeca.*','OrdemServico.id'),
      'joins' => array(
        array(
          'table' => 'ordens_servicos',
          'alias' => 'OrdemServico',
          'type' => 'LEFT',
          'conditions' => array('OrdemServico.id = ServicoPeca.ordens_servicos_id')
        )
      ),
    )));

    $sql = "SELECT sum(precos) as Total FROM servicos_itens WHERE ordens_servicos_id = $id";
    $this->set('totalservico',  $this->ServicoItem->query($sql));

    $sqlPecas = "SELECT SUM(precos) as Total FROM servicos_pecas WHERE ordens_servicos_id = $id";
    $this->set('totalpeca',  $this->ServicoPeca->query($sqlPecas));

    $sqlAssinatura = "SELECT b.nome FROM ordens_servicos a
                     LEFT JOIN admin.usuarios b on b.id = a.assinatura_gerente
                     WHERE a.id = $id";
    $this->set('assinatura',  $this->OrdemServico->query($sqlAssinatura));

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

  public function tabela() {



  }



}
