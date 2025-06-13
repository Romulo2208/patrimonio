<?php
App::uses('AppController', 'Controller');
require_once(ROOT . DS . 'patrimonio' . DS . 'Vendor' . DS . 'mpdf60' . DS . 'mpdf.php');

class EquipamentosController extends AppController {

  public $components = array('Paginator');

  public function index($search = null) {
    $option = array();
    if ($search) {
      $option['OR']['Equipamento.descricao LIKE'] = "%{$search}%";

    }

    $this->loadModel('Equipamento');
    $this->Equipamento->recursive = 0;
    $this->Paginator->settings = array('limit' => '20', 'order' => 'descricao asc');
    $this->set('equipamentos', $this->Paginator->paginate('Equipamento', $option));
  }

  public function add($id = null) {
    $this->layout="ajax";
    $this->loadModel('Equipamento');
    if ($this->request->is('post')) {
      //$this->request->data['Patrimonio']['data_registro'] = date('d/m/Y');

      $this->Equipamento->create();
      if ($result = $this->Equipamento->save($this->request->data)) {
        $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
        return $this->redirect(array('action' => 'add'));
      } else {
        $this->Session->setFlash(__($this::MSG_ERRO));
      }
    }

  }

  public function edit($id = null) {
    $this->layout="ajax";
    $this->loadModel('Equipamento');
    // $this->MaterialClassificacao->id = $id;
    if (!$this->Equipamento->exists($id)) {
      throw new NotFoundException(__('Invalid Categoria'));
    }
    if ($this->request->is(array('post', 'put'))) {
      // pr($this->request->data);exit;
      if ($this->Equipamento->save($this->request->data)) {
        $this->Session->setFlash(__($this::MSG_SUCESSO_EDT));
        return $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__($this::MSG_ERRO));
      }
    } else {
      $options = array('conditions' => array('Equipamento.' . $this->Equipamento->primaryKey => $id));
      $this->request->data = $this->Equipamento->find('first',$options);
    }

  }

  public function delete($id = null) {
    // $this->MaterialClassificacao->id = $id;
    $this->loadModel('Equipamento');
    $this->Equipamento->id = $id;
    if (!$this->Equipamento->exists()) {
      throw new NotFoundException(__('Invalid equipamento'));
    }
    $this->request->is('post', 'delete');
    if ($this->Equipamento->delete()) {
      $this->Session->setFlash(__($this::MSG_SUCESSO_DEL));
    } else {
      $this->Session->setFlash(__($this::MSG_ERRO));
    }
    return $this->redirect(array('action' => 'index'));
  }

  public function relatorio_oleos($id = null) {

    $this->loadModel('Saida');
    $this->loadModel('Material');
    $this->loadModel('Entrada');
    $this->loadModel('MaterialFilial');
    $this->loadModel('SaldoAnterior');

    if ($this->request->is('post')) {
      //pr($this->request->data);exit;

      $inicio = $this->date($this->request->data['material']['inicio'][0]);
      $final = $this->date($this->request->data['material']['final'][0]);
      $setor = $this->request->data['material']['setor'][0];
      // pr($mat);exit;

      $this->set('saidas', $this->Saida->find('all', array(
        'conditions' => array("Saida.data_saida BETWEEN '$inicio' AND '$final' AND Saida.setor_id = '$setor' AND Material.classificacoes_id in('31','3')"),
        'fields' => array('Localizacao.*', 'Saida.*','Material.*','MaterialClassificacao.*', 'Equipamento.descricao', 'Setor.descricao'),
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
          ),array(
            'table' => 'equipamentos',
            'alias' => 'Equipamento',
            'type' => 'LEFT',
            'conditions' => array('Equipamento.id = Saida.equipamentos_id')
          ),array(
            'table' => 'setores',
            'alias' => 'Setor',
            'type' => 'LEFT',
            'conditions' => array('Setor.id = Saida.setor_id')
          )
        ),
        'order' => 'Material.id asc'
      )));

      $sql = "SELECT sum(quantidade_saida) as Soma FROM saidas WHERE data_saida BETWEEN '$inicio' AND '$final' AND setor_id = '$setor' AND localizacoes_id in (2,3,4,5,6,7,8,9) and materiais_id = 1241";
      $this->set('somas',  $this->Saida->query($sql));

      $sqlTotal = "SELECT sum(a.quantidade_saida) as Total
      FROM saidas a
      INNER JOIN materiais b on b.id = a.materiais_id
      WHERE a.data_saida BETWEEN '$inicio' AND '$final' AND a.setor_id = '$setor' AND b.classificacoes_id IN (3,31)";
      $this->set('total',  $this->Saida->query($sqlTotal));

      $sqlTotalAnterior = "SELECT t.id,
      t.materiais_id,
      t.setor_id,
      t.data,
      t.quantidade
      FROM saldos_anteriores t
      WHERE t.data BETWEEN '$inicio' AND '$final' AND t.setor_id = '$setor' AND t.materiais_id = 1241";
      //$itens = $this->Material->query($sqlTotalAnterior);
      $this->set('totalgeral',  $this->SaldoAnterior->query($sqlTotalAnterior));
      // pr($itens[0]['materiais']['quantidade_central']);exit;

      $this->set('entradas', $this->Entrada->find('all', array(
        'conditions' => array("Entrada.data_entrada BETWEEN '$inicio' AND '$final' AND Entrada.materiais_id = '1241' AND Entrada.setor_id = '$setor'"),
        'fields' => array('Entrada.*','Material.*'),
        'joins' => array(
          array(
            'table' => 'materiais',
            'alias' => 'Material',
            'type' => 'LEFT',
            'conditions' => array('Material.id = Entrada.materiais_id')
          )
        ),
        'order' => 'Entrada.data_entrada asc',
        'limit' => 6
      )));
      // }
      $sqlTotalEntrada = "SELECT SUM(quantidade_entrada) as TotalEntrada  FROM entradas WHERE data_entrada BETWEEN '$inicio' AND '$final' AND materiais_id = 1241 AND setor_id = '$setor'";
      $this->set('totalentrada',  $this->Entrada->query($sqlTotalEntrada));

      $this->set('saldodiesel', $this->MaterialFilial->find('first', array('conditions' => array('materiais_id' => 1241, 'setor_id'=>$setor))));

      $sqlOleosEntrada = "SELECT t.id,
      t.nome,
      t.quantidade_central,
      f.quantidade,
      (SELECT SUM(x.quantidade_entrada)
      FROM entradas x
      WHERE x.data_entrada BETWEEN '$inicio' AND '$final' AND x.setor_id = '$setor' AND x.materiais_id = t.id) AS Total_Entrada,
      (SELECT SUM(s.quantidade_saida)
      FROM saidas s
      WHERE s.data_saida BETWEEN '$inicio' AND '$final' AND s.setor_id = '$setor' AND s.materiais_id = t.id) AS Total_Saida
      FROM materiais t
      LEFT JOIN materiais_filiais f on f.materiais_id = t.id
      WHERE t.classificacoes_id in (3,31) AND f.setor_id = '$setor'
      ORDER BY t.id ASC";
      $this->set('oleoentradas',  $this->Material->query($sqlOleosEntrada));

    }

    $this->loadModel('Setor');
    $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));

  }

  public function relatorio_oleos_excel($id = null) {

    $this->loadModel('Saida');
    $this->loadModel('Material');
    $this->loadModel('Entrada');
    $this->loadModel('MaterialFilial');
    $this->loadModel('SaldoAnterior');

    if ($this->request->is('post')) {
      //pr($this->request->data);exit;

      $inicio = $this->date($this->request->data['material']['inicio'][0]);
      $final = $this->date($this->request->data['material']['final'][0]);
      $setor = $this->request->data['material']['setor'][0];
      // pr($mat);exit;

      $this->set('saidas', $this->Saida->find('all', array(
        'conditions' => array("Saida.data_saida BETWEEN '$inicio' AND '$final' AND Saida.setor_id = '$setor' AND Material.classificacoes_id in('31','3')"),
        'fields' => array('Localizacao.*', 'Saida.*','Material.*','MaterialClassificacao.*', 'Equipamento.descricao', 'Setor.descricao'),
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
          ),array(
            'table' => 'equipamentos',
            'alias' => 'Equipamento',
            'type' => 'LEFT',
            'conditions' => array('Equipamento.id = Saida.equipamentos_id')
          ),array(
            'table' => 'setores',
            'alias' => 'Setor',
            'type' => 'LEFT',
            'conditions' => array('Setor.id = Saida.setor_id')
          )
        ),
        'order' => 'Material.id asc'
      )));

      $sql = "SELECT sum(quantidade_saida) as Soma FROM saidas WHERE data_saida BETWEEN '$inicio' AND '$final' AND setor_id = '$setor' AND localizacoes_id in (2,3,4,5,6,7,8,9) and materiais_id = 1241";
      $this->set('somas',  $this->Saida->query($sql));

      $sqlTotal = "SELECT sum(a.quantidade_saida) as Total
      FROM saidas a
      INNER JOIN materiais b on b.id = a.materiais_id
      WHERE a.data_saida BETWEEN '$inicio' AND '$final' AND a.setor_id = '$setor' AND b.classificacoes_id IN (3,31)";
      $this->set('total',  $this->Saida->query($sqlTotal));

      $sqlTotalAnterior = "SELECT t.id,
      t.materiais_id,
      t.setor_id,
      t.data,
      t.quantidade
      FROM saldos_anteriores t
      WHERE t.data BETWEEN '$inicio' AND '$final' AND t.setor_id = '$setor' AND t.materiais_id = 1241";
      //$itens = $this->Material->query($sqlTotalAnterior);
      $this->set('totalgeral',  $this->SaldoAnterior->query($sqlTotalAnterior));
      // pr($itens[0]['materiais']['quantidade_central']);exit;

      $this->set('entradas', $this->Entrada->find('all', array(
        'conditions' => array("Entrada.data_entrada BETWEEN '$inicio' AND '$final' AND Entrada.materiais_id = '1241' AND Entrada.setor_id = '$setor'"),
        'fields' => array('Entrada.*','Material.*'),
        'joins' => array(
          array(
            'table' => 'materiais',
            'alias' => 'Material',
            'type' => 'LEFT',
            'conditions' => array('Material.id = Entrada.materiais_id')
          )
        ),
        'order' => 'Entrada.data_entrada asc',
        'limit' => 6
      )));
      // }
      $sqlTotalEntrada = "SELECT SUM(quantidade_entrada) as TotalEntrada  FROM entradas WHERE data_entrada BETWEEN '$inicio' AND '$final' AND materiais_id = 1241 AND setor_id = '$setor'";
      $this->set('totalentrada',  $this->Entrada->query($sqlTotalEntrada));

      $this->set('saldodiesel', $this->MaterialFilial->find('first', array('conditions' => array('materiais_id' => 1241, 'setor_id'=>$setor))));

      $sqlOleosEntrada = "SELECT t.id,
      t.nome,
      t.quantidade_central,
      f.quantidade,
      (SELECT SUM(x.quantidade_entrada)
      FROM entradas x
      WHERE x.data_entrada BETWEEN '$inicio' AND '$final' AND x.setor_id = '$setor' AND x.materiais_id = t.id) AS Total_Entrada,
      (SELECT SUM(s.quantidade_saida)
      FROM saidas s
      WHERE s.data_saida BETWEEN '$inicio' AND '$final' AND s.setor_id = '$setor' AND s.materiais_id = t.id) AS Total_Saida
      FROM materiais t
      LEFT JOIN materiais_filiais f on f.materiais_id = t.id
      WHERE t.classificacoes_id in (3,31) AND f.setor_id = '$setor'
      ORDER BY t.id ASC";
      $this->set('oleoentradas',  $this->Material->query($sqlOleosEntrada));

    }

    $this->loadModel('Setor');
    $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));

  }

  public function relatorio_equipamentos(){
    $this->loadModel('Saida');
    $this->loadModel('Material');
    $this->loadModel('Entrada');

    if ($this->request->is('post')) {
      //pr($this->request->data);exit;

      $inicio = $this->date($this->request->data['material']['inicio'][0]);
      $final = $this->date($this->request->data['material']['final'][0]);
      $setor = $this->request->data['material']['setor'][0];
      // pr($mat);exit;

      $this->set('saidas', $this->Saida->find('all', array(
        'conditions' => array("Saida.data_saida BETWEEN '$inicio' AND '$final' AND Saida.setor_id = '$setor' AND Material.classificacoes_id in('31','3')"),
        'fields' => array('Localizacao.*', 'Saida.*','Material.*','MaterialClassificacao.*','Equipamento.descricao'),
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
          ),array(
            'table' => 'equipamentos',
            'alias' => 'Equipamento',
            'type' => 'LEFT',
            'conditions' => array('Equipamento.id = Saida.equipamentos_id')
          )
        ),
        'order' => 'Material.id asc'
      )));

    }

    $this->loadModel('Setor');
    $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));
  }

  public function relatorio_equipamentos_saldo(){
    $this->loadModel('Saida');
    $this->loadModel('Material');
    $this->loadModel('Entrada');

    if ($this->request->is('post')) {
      //pr($this->request->data);exit;

      $inicio = $this->date($this->request->data['material']['inicio'][0]);
      $final = $this->date($this->request->data['material']['final'][0]);
      $setor = $this->request->data['material']['setor'][0];
      // pr($mat);exit;

      $this->set('saidas', $this->Saida->find('all', array(
        'conditions' => array("Saida.data_saida BETWEEN '$inicio' AND '$final' AND Saida.setor_id = '$setor' AND Material.classificacoes_id in('31','3')"),
        'fields' => array('Localizacao.*', 'Saida.*','Material.*','MaterialClassificacao.*','Equipamento.descricao'),
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
          ),array(
            'table' => 'equipamentos',
            'alias' => 'Equipamento',
            'type' => 'LEFT',
            'conditions' => array('Equipamento.id = Saida.equipamentos_id')
          )
        ),
        'order' => 'Material.id asc'
      )));

    }

    $this->loadModel('Setor');
    $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));
  }

  public function relatorio_oleo_equipamentos(){
    $this->loadModel('Saida');
    $this->loadModel('Material');
    $this->loadModel('Entrada');

    if ($this->request->is('post')) {
      //pr($this->request->data);exit;

      $inicio = $this->date($this->request->data['material']['inicio'][0]);
      $final = $this->date($this->request->data['material']['final'][0]);
      $setor = $this->request->data['material']['setor'][0];
      $equipamento = $this->request->data['material']['equipamentos_id'];
      // pr($mat);exit;

      $this->set('saidas', $this->Saida->find('all', array(
        'conditions' => array("Saida.data_saida BETWEEN '$inicio' AND '$final' AND Saida.setor_id = '$setor' AND Saida.equipamentos_id ='$equipamento' AND Material.classificacoes_id in('31','3')"),
        'fields' => array('Localizacao.*', 'Saida.*','Material.*','MaterialClassificacao.*','Equipamento.descricao'),
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
          ),array(
            'table' => 'equipamentos',
            'alias' => 'Equipamento',
            'type' => 'LEFT',
            'conditions' => array('Equipamento.id = Saida.equipamentos_id')
          )
        ),
        'order' => 'Material.id asc'
      )));

    }

    $this->loadModel('Setor');
    $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));

    $this->loadModel('Equipamento');
    $this->set('equipamentos', $this->Equipamento->find('list', array('fields' => array('id', 'descricao'), 'order' => 'id ASC')));
  }

  public function relatorio_oleo_equipamentos_saldo(){
    $this->loadModel('Saida');
    $this->loadModel('Material');
    $this->loadModel('Entrada');

    if ($this->request->is('post')) {
      //pr($this->request->data);exit;

      $inicio = $this->date($this->request->data['material']['inicio'][0]);
      $final = $this->date($this->request->data['material']['final'][0]);
      $setor = $this->request->data['material']['setor'][0];
      $equipamento = $this->request->data['material']['equipamentos_id'];
      // pr($mat);exit;

      $this->set('saidas', $this->Saida->find('all', array(
        'conditions' => array("Saida.data_saida BETWEEN '$inicio' AND '$final' AND Saida.setor_id = '$setor' AND Saida.equipamentos_id ='$equipamento' AND Material.classificacoes_id in('31','3')"),
        'fields' => array('Localizacao.*', 'Saida.*','Material.*','MaterialClassificacao.*','Equipamento.descricao'),
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
          ),array(
            'table' => 'equipamentos',
            'alias' => 'Equipamento',
            'type' => 'LEFT',
            'conditions' => array('Equipamento.id = Saida.equipamentos_id')
          )
        ),
        'order' => 'Material.id asc'
      )));

    }

    $this->loadModel('Setor');
    $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));

    $this->loadModel('Equipamento');
    $this->set('equipamentos', $this->Equipamento->find('list', array('fields' => array('id', 'descricao'), 'order' => 'id ASC')));
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
