<?php

header('Access-Control-Allow-Origin: *');
App::uses('AppController', 'Controller');
App::import('Vendor', 'autoload');

use Namshi\JOSE\SimpleJWS;

class ItensController extends AppController {

    public $components = array('RequestHandler');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('get', 'post', 'delete', 'processo', 'recursos');
    }

    private function checkToken() {
      if(!isset($this->request->query['token'])) {
        return 'token ausente!';
      }

      try {
        $jws  = SimpleJWS::load($this->request->query['token']);
      } catch (Exception $e) {
        return 'token invalido!';
      }

      $this->loadModel('Usuario');
      $payload = $jws->getPayload();
      $user = $this->Usuario->find('first', array('fields'=>array('senha'),'conditions'=>array('id'=>$payload['usuario']['id'])));
      if(!$jws->isValid($user['Usuario']['senha'], 'HS256')) {
        return 'token incompativel!';
      }

      return null;
    }

    public function get() {
      $rest = array('error' => null, 'response' => null);
      $rest['error'] = $this->checkToken();
      $id = $this->request->query['id'];

      if(!$rest['error']) {
        $this->loadModel('Item');
        $sql = "SELECT item.*, recurso.descricao FROM gestao.itens item
                INNER JOIN recursos recurso ON recurso.id = item.recursos_id
                WHERE item.processos_id = {$id}
                ORDER BY item.id DESC";

        $itens = $this->Item->query($sql);

        $json = array();
        foreach ($itens as $key => $value) {
            $value['item']['data'] = date('d/m H:i', strtotime($value['item']['data_registro']));
            $value['item']['valor'] = number_format($value['item']['valor'],2,',','.');
            $value['item']['recurso'] = $value['recurso']['descricao'];
            $rest['response']['itens'][] = $value['item'];
        }

        $sql = "SELECT * FROM tipo_respostas";
        $respostas = $this->Item->query($sql);
        foreach ($respostas as $key => $value) {
          if($value['tipo_respostas']['tipo'] == '1') {
            $rest['response']['respostas']['confirmar'][] = $value['tipo_respostas']['descricao'];
          } else if($value['tipo_respostas']['tipo'] == '2') {
            $rest['response']['respostas']['recusar'][] = $value['tipo_respostas']['descricao'];
          }
        }
      }

      $this->set(array(
          'rest' => $rest,
          '_serialize' => array('rest')
      ));
    }

    public function post() {
        $rest = array('error' => null, 'success' => null, 'response' => null);
        $rest['error'] = $this->checkToken();
        $this->loadModel('Item');

        if($this->request->is('post')) {
          $request = json_decode(file_get_contents("php://input"));

          if ($request) {
            if (isset($request->id)) {
                $item = $this->Item->find('first', array('conditions' => array('id' => $request->id)));
                $this->request->data = $item;
            } else {
                $this->request->data['Item'] = array(
                  'data_registro' => date('Y-m-d H:i:s'),
                  'usuarios_id' => '', 'processos_id' => '',
                  'tipo_itens_id' => '', 'recursos_id' => '',
                  'descricao' => '', 'valor' => ''
                );
            }

            if (isset($request->usuarios_id)) {
                $this->request->data['Item']['usuarios_id'] = (int) $request->usuarios_id;
            }

            if (isset($request->tipo_itens_id)) {
                $this->request->data['Item']['tipo_itens_id'] = (int) $request->tipo_itens_id;
            }

            if (isset($request->processos_id)) {
                $this->request->data['Item']['processos_id'] = (int) $request->processos_id;
            }

            if (isset($request->recursos_id)) {
                $this->request->data['Item']['recursos_id'] = (int) $request->recursos_id;
            }

            if (isset($request->descricao)) {
                $this->request->data['Item']['descricao'] = $request->descricao;
            }

            if (isset($request->valor)) {
                $this->request->data['Item']['valor'] = $request->valor;
            }
        }

        foreach ($this->request->data['Item'] as $key => $value) {
          if ($value == '' && !isset($this->request->query['id'])) {
            $rest['error'] = '(*) campos obrigatórios!';
          }
        }

        if (!$rest['error']) {
            $this->Item->create();
            if ($returnInsert = $this->Item->save($this->request->data)) {
                $sql = "SELECT item.* FROM gestao.itens item
                        WHERE item.id = {$returnInsert['Item']['id']}";

                $item = $this->Item->query($sql);
                if ($item) {
                    $item[0]['item']['data_com_mascara'] = date('d/m H:i', strtotime($item[0]['item']['data_registro']));
                }

                $rest['success'] = $this::MSG_SUCESSO_ADD;
                $rest['response'] = $item[0];
            } else {
                $rest['error'] = $this::MSG_ERRO;
            }
        }
      } else {
        $rest['error'] = $this::MSG_ERRO;
      }

      $this->set(array(
          'rest' => $rest,
          '_serialize' => array('rest')
      ));
    }

    public function delete($id = null) {
        $rest = array('error' => null, 'success' => null);
        $rest['error'] = $this->checkToken();
        $id = $this->request->query['id'];
        $this->loadModel('Item');

        $this->Item->id = $id;
        if (!$this->Item->exists()) {
            $json['erro'] = "Registro não existe ou já foi excluido";
        }

        if (!$rest['error']) {
            if ($this->Item->delete()) {
                $rest['success'] = $this::MSG_SUCESSO_DEL;
            } else {
                $rest['error'] = $this::MSG_ERRO;
            }
        }

        $this->set(array(
            'rest' => $rest,
            '_serialize' => array('rest')
        ));
    }

    public function processo() {
      $rest = array('error' => null, 'response' => null);
      $rest['error'] = $this->checkToken();
      $id = $this->request->query['id'];

      if(!$rest['error']) {
        $this->loadModel('Processo');
        $sql = "SELECT processo.id, processo.finalidade, processo.descricao, processo.data_acao, setor.descricao, situacao.descricao,
                (SELECT count(id) FROM observacoes WHERE processos_id = processo.id) as observacoes,
                (SELECT SUM(valor) as total FROM itens WHERE processos_id = processo.id) as total
                FROM processos processo
                INNER JOIN setores setor ON setor.id = processo.setor_id
                INNER JOIN situacoes situacao On situacao.id = processo.situacoes_id
                WHERE processo.id = {$id}";

        $processo = $this->Processo->query($sql);
        foreach ($processo as $key => $value) {
            $rest['response'] = array(
              'id' => $value['processo']['id'],
              'finalidade' =>  $value['processo']['finalidade'],
              'descricao' =>  $value['processo']['descricao'],
              'situacao' =>  $value['situacao']['descricao'],
              'setor' => $value['setor']['descricao'],
              'data' => date('d/m/y H:i', strtotime($value['processo']['data_acao'])),
              'valor' => number_format($value['0']['total'],2,',','.'),
              'observacoes' => $value['0']['observacoes']
            );
        }
      }

      $this->set(array(
          'rest' => $rest,
          '_serialize' => array('rest')
      ));
    }

    public function recursos() {
      $rest = array('error' => null, 'response' => null);
      $rest['error'] = $this->checkToken();

      if(!$rest['error']) {
        $this->loadModel('Recurso');
        $recursos = $this->Recurso->find('all', array(
          'fields' => array(
            'Recurso.id',
            'Recurso.descricao',
            'Tipo.descricao'
          ),
          'joins' => array(
              array(
                  'table' => 'tipo_recusos',
                  'alias' => 'Tipo',
                  'type' => 'INNER',
                  'conditions' => array('Tipo.id = Recurso.tipo_recusos_id')
              ),
          ),
          'order' => array(
            'Tipo.descricao' => 'ASC',
            'Recurso.descricao' => 'ASC'
          )
        ));

        foreach ($recursos as $key => $value) {
            $rest['response'][] = array(
              'id' => $value['Recurso']['id'],
              'descricao' =>  $value['Recurso']['descricao'],
              'tipo' =>  $value['Tipo']['descricao']
            );
        }
      }

      $this->set(array(
          'rest' => $rest,
          '_serialize' => array('rest')
      ));
    }
}
