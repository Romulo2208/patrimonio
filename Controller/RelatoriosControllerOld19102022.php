<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'fpdf16/fpdf');

class RelatoriosController extends AppController {

    public function materiais() {
      $this->loadModel('Material');
      $this->set('materiais', $this->Material->find('all', array(
        'fields' => array('Classificacao.*', 'Material.*'),
        'joins' => array(
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

    public function saida() {
      $this->loadModel('Saida');
      $this->set('saidas', $this->Saida->find('all', array(
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
    }

    public function entrada() {
    $this->loadModel('Entrada');

    if ($this->request->is('post')) {

      $inicio = $this->date($this->request->data['material']['inicio'][0]);
      $final = $this->date($this->request->data['material']['final'][0]);

      $this->set('entradas', $this->Entrada->find('all', array(
        'conditions' => array("Entrada.data_entrada BETWEEN '$inicio' AND '$final'"),
        'fields' => array('Usuario.nome', 'Entrada.*','Material.*','MaterialClassificacao.*', 'Setor.*'),
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
          ),array(
            'table' => 'setores',
            'alias' => 'Setor',
            'type' => 'LEFT',
            'conditions' => array('Setor.id = Entrada.setor_id')
          )
        ),
        'order' => 'Material.nome asc'
      )));
    }
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
        WHERE b.setor_id = 3 AND b.materiais_id = a.id ) as Filial2,
        (SELECT b.quantidade
          FROM materiais_filiais b
          WHERE b.setor_id = 4 AND b.materiais_id = a.id ) as Filial4,
          (SELECT b.quantidade
            FROM materiais_filiais b
            WHERE b.setor_id = 9 AND b.materiais_id = a.id ) as Filial5,
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

   public function mapa_categorias(){
    $this->loadModel('Saida');
    $this->loadModel('Material');
    $this->loadModel('MaterialFilial');

    if ($this->request->is('post')) {
      //pr($this->request->data);exit;

      $categoria = $this->request->data['material']['categoria'][0];


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
          WHERE b.setor_id = 3 AND b.materiais_id = a.id ) as Filial2,
        (SELECT b.quantidade
          FROM materiais_filiais b
          WHERE b.setor_id = 4 AND b.materiais_id = a.id ) as Filial4,
			(SELECT b.quantidade
				FROM materiais_filiais b
				WHERE b.setor_id = 9 AND b.materiais_id = a.id ) as Filial5,
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
                  WHERE a.classificacoes_id = $categoria";
                  $this->set('materiais',  $this->Material->query($sql));


                  }

                  $this->loadModel('MaterialClassificacao');
                  $this->set('classificacoes', $this->MaterialClassificacao->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));
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

    public function entrada2(){

      $this->loadModel('Entrada');
      $this->set('entradas', $this->Entrada->find('all', array(
        'fields' => array('Usuario.nome', 'Entrada.*','Material.*'),
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
            )
        ),
        'order' => 'Material.nome asc'
      )));

      // $sql="SELECT *
      //       FROM entradas
      //       WHERE data_entrada BETWEEN '' AND '';"


    }

    public function relatorio($tipo = 'geral') {
        ini_set('memory_limit', '-1');

//        pr($this->request->query['localizacao_id']);
//        exit;

        $options = array();

        switch ($tipo) {
            case 'baixa':
                 $options = array("data_baixa >= '2016-02-01' && data_baixa <='2016-07-01'");
                break;
            case 'lancados':
                 $options = array('data_registro >= ' => '2015-09-24');
                break;
            case 'localizacao':
                 $options = array('localizacao_id = ' => $this->request->query['localizacao_id']);
                break;
            case 'data':
                 $options = array('data_registro = ' => $this->request->query['data_registro']=implode('-', array_reverse(explode('/', $this->request->query['data_registro']))));
                break;
            default:
                break;
        }


        $this->loadModel('Patrimonio');
        $itens = $this->Patrimonio->find('all', array(
            'fields' => array('Patrimonio.*, Fornecedor.nome_fantasia, Localizacao.descricao, Conservacao.descricao, NotaFiscal.*'),
            'order' => array('Patrimonio.firma ASC', 'Patrimonio.codigo ASC'),
            'conditions' => $options,
            'joins' => array(
                array(
                    'table' => 'patrimonios_itens',
                    'alias' => 'Item',
                    'type' => 'INNER',
                    'conditions' => array('Patrimonio.id = Item.patrimonios_id')
                ),
                array(
                    'table' => 'notas_fiscais',
                    'alias' => 'NotaFiscal',
                    'type' => 'INNER',
                    'conditions' => array('NotaFiscal.id = Item.notas_fiscais_id')
                )
            )
        ));

        $pdf = new PDF("P", "mm", "A4");


        $pdf->Open();
        $pdf->AddPage();

        $pdf->SetFont("arial", "B", 25);
        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);

        $pdf->Cell(190, 10, ('PATRIMÔNIO '), 0, 1, 'C', 1);

        foreach ($itens as $key => $item) {
            if ($key % 4 == 0 && $key > 0) {
                $pdf->AddPage();
            }

            $pdf->Ln(3);
            $pdf->SetFillColor(0);
            $pdf->Cell(190, 1, '', 0, 1, 'C', 1);
            $pdf->SetFont("arial", "", 10);
            $pdf->SetFillColor(255);
            $pdf->SetTextColor(0);
            $pdf->Ln(3);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(40, 7, ('PATRIMÔNIO'), 0, 0, 'R', 1);
            $pdf->SetFont("arial", "");
            $pdf->Cell(30, 7, $item['Patrimonio']['firma'], 1, 0, 'C', 1);
            $pdf->Cell(30, 7, str_pad($item['Patrimonio']['codigo'], 6, "0", STR_PAD_LEFT), 1, 1, 'C', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(40, 7, ('DESCRIÇÃO'), 0, 0, 'R', 1);
            $pdf->SetFont("arial", "");
            $pdf->MultiCell(150, 7, ($item['Patrimonio']['descricao']), 1, 1, 'L', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(40, 7, ('LOCALIZAÇÃO'), 0, 0, 'R', 1);
            $pdf->SetFont("arial", "");
            $pdf->Cell(150, 7, ($item['Localizacao']['descricao']), 1, 1, 'L', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(40, 7, 'VALOR DE COMPRA', 0, 0, 'R', 1);
            $pdf->SetFont("arial", "");
            $pdf->Cell(35, 7, $item['Patrimonio']['valor'], 1, 0, 'L', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(25, 7, 'N DA NOTA', 1, 0, 'L', 1);
            $pdf->SetFont("arial", "");
            $pdf->Cell(35, 7, $item['NotaFiscal']['numero_nota'], 1, 0, 'L', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(30, 7, 'DATA DA NOTA', 1, 0, 'R', 1);
            $pdf->SetFont("arial", "");
            $pdf->Cell(25, 7, $item['NotaFiscal']['data_emissao'], 1, 1, 'L', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(40, 7, 'FORNECEDOR', 0, 0, 'R', 1);
            $pdf->SetFont("arial", "");
            $pdf->Cell(150, 7, ($item['Fornecedor']['nome_fantasia']), 1, 1, 'L', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(40, 7, 'DATA DA BAIXA', 0, 0, 'R', 1);
            $pdf->SetFont("arial", "");
            $pdf->Cell(35, 7, $item['Patrimonio']['data_baixa'], 1, 0, 'L', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(40, 7, ('CONSERVAÇÃO'), 1, 0, 'C', 1);
            $pdf->SetFont("arial", "");
            $pdf->Cell(35, 7, $item['Conservacao']['descricao'], 1, 1, 'L', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(40, 7, 'MOTIVO DA BAIXA', 0, 0, 'R', 1);
            $pdf->SetFont("arial", "");
            $pdf->MultiCell(150, 7, ($item['Patrimonio']['motivo_baixa']), 1, 1, 'L', 1);
        }

        $pdf->Output();
        exit;
    }
    public function filtro(){

        // $this->loadModel('Localizacao');
        // $localizacaos = $this->Localizacao->find('list', array('fields' => array('id', 'descricao'), 'order' => 'descricao ASC'));
        // $this->set(compact('localizacaos'));

        // $query .= 'order_date BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'";

        $sql="SELECT * FROM entradas WHERE data_entrada >= " ."'" . $_POST["registro"] ."' AND data_entrada <= " ."'" . $_POST["data_final"] ."'";

        pr($sql);exit;
    }

}

class PDF extends FPDF {

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(100, 10, date('d/m/Y'), 0, 0, 'L');
        $this->Cell(90, 10, ('Página ') . $this->PageNo(), 0, 0, 'R');
    }

}
