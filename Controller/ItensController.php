<?php

App::uses('AppController', 'Controller');

/**
 * Items Controller
 *
 * @property Item $Item
 * @property PaginatorComponent $Paginator
 */
class ItensController extends AppController {

    public function add() {
        $this->loadModel('ServicoItem');
        $this->loadModel('MaterialItem');
        $this->loadModel('PatrimonioItem');

        $data = null;
        $controller = null;
        if(isset($this->request->data['Item']['tipo'])) {
          $controller = $this->request->data['Item']['tipo'];
          unset($this->request->data['Item']['tipo']);
          $data[$controller] = $this->request->data['Item'];
        }

        if ($this->request->is(array('post','put')) && $controller) {
            $this->$controller->create();
            if ($this->$controller->save($data)) {
                $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
            } else {
                $this->Session->setFlash(__($this::MSG_ERRO));
            }
        }

        return $this->redirect(array('controller' => 'notas_fiscais', 'action' => 'edit', $data[$controller]['notas_fiscais_id'], '#' => 'item'));
    }

    public function delete($id = null, $controller = null, $nota = null) {
        $this->loadModel('ServicoItem');
        $this->loadModel('MaterialItem');
        $this->loadModel('PatrimonioItem');

        $this->$controller->id = $id;
        if (!$this->$controller->exists()) {
            throw new NotFoundException(__('Invalid id'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->$controller->delete()) {
            $this->Session->setFlash(__($this::MSG_SUCESSO_DEL));
        } else {
            $this->Session->setFlash(__($this::MSG_ERRO));
        }
        return $this->redirect(array('controller' => 'notas_fiscais', 'action' => 'edit', $nota, '#' => 'item'));
    }

    public function valor_nota() {
        $this->loadModel('Patrimonio');
        if(isset($this->request->data['Item']['nota_fiscal_id'])){
            $itens = $this->Patrimonio->find('all', array(
                'fields' => array('Patrimonio.valor'),
                'conditions' => array('Item.nota_fiscal_id' => $this->request->data['Item']['nota_fiscal_id']),
                'joins' => array(
                    array(
                        'table' => 'itens',
                        'alias' => 'Item',
                        'type' => 'INNER',
                        'conditions' => array('Patrimonio.id = Item.patrimonio_id')
                    )
                )
            ));
        }

        $valor = 0;
        if(sizeof($itens)) {
            foreach ($itens as $key => $value) {
                $valor = $valor + str_replace(',', '.', str_replace('.', '', $value['Patrimonio']['valor']));
            }
        }

        echo number_format($valor, 2, ',', '.');
        exit;
    }

}
