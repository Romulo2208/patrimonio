<?php

App::uses('AppController', 'Controller');
App::import('Vendor', 'fpdf16/fpdf');

/**
 * NotaFiscals Controller
 *
 * @property NotaFiscal $NotaFiscal
 * @property PaginatorComponent $Paginator
 */
class NotasFiscaisController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');

    /**
     * index method
     *
     * @return void
     */
    public function index($Search=null) {
        $option = array('OR'=> array());
        if($Search!='0'){
            $option['OR']['NotaFiscal.numero_nota LIKE'] = "%{$Search}%";
            $Search=str_replace(',', '.', str_replace('.','',$Search));
            $option['OR']['NotaFiscal.valor_nota LIKE'] = "%{$Search}%";
        }
       /* if($valor!='0'){
            $valor=str_replace(',', '.', str_replace('.','',$valor));
            $option['OR']['NotaFiscal.valor_nota LIKE'] = "%{$valor}%";
        }*/

        //pr($option); exit;

        $this->loadModel('NotaFiscal');
        $this->NotaFiscal->recursive = 0;
        $this->Paginator->settings = array('limit' => '20');
        $this->set('notasFiscais', $this->Paginator->paginate('NotaFiscal', $option));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        $this->loadModel('NotaFiscal');
        if ($this->request->is('post')) {
            if(!$this->NotaFiscal->verificaData($this->request->data['NotaFiscal']['data_emissao'])){
               $this->Session->setFlash('Data emissão invalida');
                return false;
           }

            $this->NotaFiscal->create();
            if ($result = $this->NotaFiscal->save($this->request->data)) {
                $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
                return $this->redirect(array('action' => 'edit', $result['NotaFiscal']['id']));
            } else {
                $this->Session->setFlash(__($this::MSG_ERRO));
            }
        }
    }

    public function edit($id = null) {
        $this->loadModel('NotaFiscal');

        if (!$this->NotaFiscal->exists($id)) {
            throw new NotFoundException(__('Invalid nota fiscal'));
        }
        if ($this->request->is(array('post', 'put'))) {

            if (!$this->NotaFiscal->verificaData($this->request->data['NotaFiscal']['data_emissao'])) {
                $this->Session->setFlash('Data emissão invalida');
                return $this->redirect(array('action' => 'edit', $id));
            }

            if ($this->NotaFiscal->save($this->request->data)) {
                $this->Session->setFlash(__($this::MSG_SUCESSO_EDT));
                return $this->redirect(array('action' => 'edit', $id));
            } else {
                $this->Session->setFlash(__($this::MSG_ERRO));
            }
        } else {
            $options = array('conditions' => array('NotaFiscal.' . $this->NotaFiscal->primaryKey => $id));
            $this->request->data = $this->NotaFiscal->find('first', $options);

            $this->loadModel('Pagamento');
            $this->request->data = array_merge($this->request->data, $this->Pagamento->find('first', array('conditions' => array('Pagamento.notas_fiscais_id' => $id))));
        }

        $this->loadModel('Fornecedor');
        $fornecedores = $this->Fornecedor->find('list', array('fields' => array('id', 'nome_fantasia'), 'order' => array('nome_fantasia')));

        $this->loadModel('Material');
        $materiais = $this->Material->find('all', array('fields' => array('id', 'nome'), 'order' => array('nome')));

        $this->loadModel('Patrimonio');
        $patrimonios = $this->Patrimonio->find('all', array(
            'fields' => array('Patrimonio.id', 'Patrimonio.codigo', 'Patrimonio.firma', 'Patrimonio.descricao'),
            'conditions' => array('Item.notas_fiscais_id IS NULL', 'Item.patrimonios_id IS NULL'),
            'joins' => array(
                array(
                    'table' => 'patrimonios_itens',
                    'alias' => 'Item',
                    'type' => 'LEFT',
                    'conditions' => array('Patrimonio.id = Item.patrimonios_id')
                )
            )
        ));

        $itens = $this->Patrimonio->find('all', array(
            'fields' => array('Patrimonio.id', 'Patrimonio.codigo', 'Patrimonio.firma', 'Patrimonio.descricao', 'Item.id', 'Item.valor_unitario', 'Item.valor_total'),
            'conditions' => array('Item.notas_fiscais_id' => $id),
            'joins' => array(
                array(
                    'table' => 'patrimonios_itens',
                    'alias' => 'Item',
                    'type' => 'INNER',
                    'conditions' => array('Patrimonio.id = Item.patrimonios_id')
                )
            )
        ));

        $itens = array_merge($itens, $this->Material->find('all', array(
            'fields' => array('Material.id', 'Material.nome', 'Material.descricao', 'Item.id', 'Item.valor_unitario', 'Item.valor_total'),
            'conditions' => array('Item.notas_fiscais_id' => $id),
            'joins' => array(
                array(
                    'table' => 'materiais_itens',
                    'alias' => 'Item',
                    'type' => 'INNER',
                    'conditions' => array('Material.id = Item.materiais_id')
                )
            )
        )));

        $this->loadModel('ServicoItem');
        $servicos = $this->ServicoItem->find('all', array('conditions' => array('notas_fiscais_id' => $id)));
        foreach ($servicos as $key => $value) {
          $itens[] = array(
            'Servico' => array(
              'id' => $value['ServicoItem']['id'],
              'descricao' => $value['ServicoItem']['descricao']
            ),
            'Item' => $value['ServicoItem']
          );
        }

        $this->set(compact('patrimonios', 'materiais', 'fornecedores', 'itens'));
    }

    public function pagamento($id = null) {
        $this->loadModel('Pagamento');
        if ($this->request->is(array('post', 'put'))) {
            $this->Pagamento->create();
            if ($result = $this->Pagamento->save($this->request->data)) {
                $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
            } else {
                $this->Session->setFlash(__($this::MSG_ERRO));
            }
        }

        return $this->redirect(array('controller' => 'notas_fiscais', 'action' => 'edit', $this->request->data['Pagamento']['notas_fiscais_id'], '#' => 'financeiro'));
    }

    public function delete($id = null) {
        $this->loadModel('NotaFiscal');

        $this->NotaFiscal->id = $id;
        if (!$this->NotaFiscal->exists()) {
            throw new NotFoundException(__('Invalid nota fiscal'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->NotaFiscal->delete()) {
            $this->Session->setFlash(__($this::MSG_SUCESSO_DEL));
        } else {
            $this->Session->setFlash(__($this::MSG_ERRO));
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function valor_nota($id = null) {
        $this->loadModel('Patrimonio');
        $itens = $this->Patrimonio->find('all', array(
            'fields' => array('Patrimonio.valor'),
            'conditions' => array('Item.nota_fiscal_id' => $id),
            'joins' => array(
                array(
                    'table' => 'itens',
                    'alias' => 'Item',
                    'type' => 'INNER',
                    'conditions' => array('Patrimonio.id = Item.patrimonio_id')
                )
            )
        ));

        $valor = 0;
        if (sizeof($itens)) {
            foreach ($itens as $key => $value) {
                $valor = $valor + str_replace(',', '.', str_replace('.', '', $value['Patrimonio']['valor']));
            }
        }

        return number_format($valor, 2, ',', '.');
    }

    public function imprimir($id = null) {
        $this->loadModel('NotaFiscal');
        $notaFiscal = $this->NotaFiscal->find('first', array('conditions' => array('id' => $id)));

        $valor = $notaFiscal['NotaFiscal']['valor_nota'];
        if(!$valor || $valor == '0,00'){
            $valor = $this->valor_nota($id);
        }

        $this->loadModel('Patrimonio');
        $itens = $this->Patrimonio->find('all', array(
            'fields' => array('Patrimonio.*, Fornecedor.nome_fantasia, Localizacao.descricao, Conservacao.descricao'),
            'conditions' => array('Item.nota_fiscal_id' => $id),
            'joins' => array(
                array(
                    'table' => 'itens',
                    'alias' => 'Item',
                    'type' => 'INNER',
                    'conditions' => array('Patrimonio.id = Item.patrimonio_id')
                )
            )
        ));

        $pdf = new PDF("P", "mm", "A4");

        $pdf->Open();
        $pdf->AddPage();

        $pdf->SetFont("arial", "B", 25);
        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);

        $pdf->Cell(190, 10, utf8_decode('PATRIMÔNIO SINPRO-DF'), 0, 1, 'C', 1);

        foreach ($itens as $key => $item) {
            if ($key % 3 == 0 && $key > 0) {
                $pdf->AddPage();
            }

            $pdf->Ln(4);
            $pdf->SetFillColor(0);
            $pdf->Cell(190, 1, '', 0, 1, 'C', 1);

            $pdf->SetFont("arial", "", 10);
            $pdf->SetFillColor(255);
            $pdf->SetTextColor(0);
            $pdf->Ln(5);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(40, 7, utf8_decode('PATRIMÔNIO'), 0, 0, 'R', 1);
            $pdf->SetFont("arial", "");
            $pdf->Cell(30, 7, $item['Patrimonio']['firma'], 1, 0, 'C', 1);
            $pdf->Cell(30, 7, $item['Patrimonio']['codigo'], 1, 1, 'C', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(40, 7, utf8_decode('DESCRIÇÃO'), 0, 0, 'R', 1);
            $pdf->SetFont("arial", "");
            $pdf->MultiCell(150, 7, utf8_decode($item['Patrimonio']['descricao']), 1, 1, 'L', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(40, 7, utf8_decode('LOCALIZAÇÃO'), 0, 0, 'R', 1);
            $pdf->SetFont("arial", "");
            $pdf->Cell(150, 7, utf8_decode($item['Localizacao']['descricao']), 1, 1, 'L', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(40, 7, 'VALOR DE COMPRA', 0, 0, 'R', 1);
            $pdf->SetFont("arial", "");
            $pdf->Cell(35, 7, $valor, 1, 0, 'L', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(25, 7, 'N DA NOTA', 1, 0, 'L', 1);
            $pdf->SetFont("arial", "");
            $pdf->Cell(35, 7, $notaFiscal['NotaFiscal']['numero_nota'], 1, 0, 'L', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(30, 7, 'DATA DA NOTA', 1, 0, 'R', 1);
            $pdf->SetFont("arial", "");
            $pdf->Cell(25, 7, $notaFiscal['NotaFiscal']['data_emissao'], 1, 1, 'L', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(40, 7, 'FORNECEDOR', 0, 0, 'R', 1);
            $pdf->SetFont("arial", "");
            $pdf->Cell(150, 7, utf8_decode($item['Fornecedor']['nome_fantasia']), 1, 1, 'L', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(40, 7, 'VALOR ATUAL', 0, 0, 'R', 1);
            $pdf->SetFont("arial", "");
            $pdf->Cell(35, 7, $item['Patrimonio']['valor'], 1, 0, 'L', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(40, 7, utf8_decode('CONSERVAÇÃO'), 1, 0, 'C', 1);
            $pdf->SetFont("arial", "");
            $pdf->Cell(35, 7, $item['Conservacao']['descricao'], 1, 1, 'L', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(40, 7, 'DATA DA BAIXA', 0, 0, 'R', 1);
            $pdf->SetFont("arial", "");
            $pdf->Cell(35, 7, $item['Patrimonio']['data_baixa'], 1, 1, 'L', 1);

            $pdf->SetFont("arial", "B");
            $pdf->Cell(40, 7, 'MOTIVO DA BAIXA', 0, 0, 'R', 1);
            $pdf->SetFont("arial", "");
            $pdf->MultiCell(150, 7, utf8_decode($item['Patrimonio']['motivo_baixa']), 1, 1, 'L', 1);
        }

        $pdf->Output();
        exit;
    }

    public function digitalizar($id = null) {
        $this->loadModel('DBMongo');
        if($this->DBMongo->error) {
            $this->Session->setFlash(__($this->DBMongo->error));
            return $this->redirect(array('action' => 'edit', $id));
        }

        if ($_FILES) {
/*
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-áàâãäªéèêëíìîïóòôõöºúùûüçñ]/i', $_FILES['filedata']['name'])) {
                $this->Session->setFlash(__("O arquivo não pode conter acento!"));
                return $this->redirect(array('action' => 'digitalizar', $id));
            }
*/

            if($_FILES['filedata']['error'] != 0) {
                $this->Session->setFlash(__($this::MSG_ERRO));
                return $this->redirect(array('action' => 'digitalizar', $id));
            }

            $this->DBMongo->grid->storeUpload('filedata', array(
                'nota_fiscal_id' => $id,
                'content_type' => $_FILES['filedata']['type']
            ));
        }

        $this->loadModel('NotaFiscal');
        $this->request->data = $this->NotaFiscal->find('first', array('conditions' => array('id' => $id)));

        $imagens = $this->DBMongo->listar($id);
        $this->set(compact('imagens'));
    }

    public function view($id = null) {
        $this->loadModel('DBMongo');
        $obj = $this->DBMongo->grid->findOne(array('_id' => new MongoId($id)));

        header("Content-type: {$obj->file['content_type']}");
        echo $obj->getBytes();
        exit;
    }

    public function excluir($id = null, $_id = null) {
        $this->loadModel('DBMongo');
        $this->DBMongo->grid->remove(array('_id' => new MongoId($_id)));
        return $this->redirect(array('action' => 'digitalizar', $id));
    }
}

class PDF extends FPDF {

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(100, 10, date('d/m/Y'), 0, 0, 'L');
        $this->Cell(90, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'R');
    }

}
