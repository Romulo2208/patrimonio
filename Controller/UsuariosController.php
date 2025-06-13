<?php

App::uses('AppController', 'Controller');
App::import('Vendor', 'phpmailer/PHPMailerAutoload');


class UsuariosController extends AppController {

    public $components = array('Paginator');

    public function index() {
        $this->Usuario->recursive = 0;
        $this->Paginator->settings = array('limit' => '20');
        $this->set('usuarios', $this->Paginator->paginate('Usuario'));
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->Usuario->create();
            if ($this->Usuario->save($this->request->data)) {
                $this->Session->setFlash(__($this::MSG_SUCESSO_ADD));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__($this::MSG_ERRO));
            }
        }
    }

    public function edit($id = null) {
        $this->loadModel('Profile');
        $this->loadModel('UsuarioAcesso');

        if (!$this->Usuario->exists($id)) {
            throw new NotFoundException(__('Invalid usuario'));
        }

        if ($this->request->is(array('post', 'put'))) {
            if($this->request->data['Usuario']['senha'] == ''){
                unset($this->request->data['Usuario']['senha']);
            }

            if ($this->Usuario->save($this->request->data)) {
                $this->Session->setFlash(__($this::MSG_SUCESSO_EDT));
            } else {
                $this->Session->setFlash(__($this::MSG_ERRO));
            }

            $user = $this->request->data['Usuario'];
            $this->UsuarioAcesso->deleteAll(array('usuario' => $user['id'],'sistema' => $this->idSistema));
            $this->UsuarioAcesso->save(array('usuario' => $user['id'],'perfil' => $user['perfil'],'sistema' => $this->idSistema));
            return $this->redirect(array('action' => 'index'));
        } else {
             $this->set('perfils', $this->Profile->find('list', array('fields' => array('id', 'nome'))));
             //$this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'))));
            $this->request->data = $this->Usuario->find('first', array('conditions' => array('Usuario.' . $this->Usuario->primaryKey => $id)));

            if(isset($this->request->data['Usuario']['id'])){
                $perfil = $this->UsuarioAcesso->find('first', array('conditions' => array('usuario' => $this->request->data['Usuario']['id'], 'sistema' => $this->idSistema)));
            }

            if(isset($perfil['UsuarioAcesso']['perfil'])){
                $this->request->data['Usuario']['perfil'] = $perfil['UsuarioAcesso']['perfil'];
            }

            if(isset($perfil['Usuario']['setor'])){
                $this->request->data['Usuario']['perfil'] = $perfil['UsuarioAcesso']['perfil'];
            }
        }

        $this->loadModel('Setor');
        $this->set('setores', $this->Setor->find('list', array('fields' => array('id', 'descricao'), 'order'=>array('descricao'=>'asc'))));
    }

    public function delete($id = null) {
        $this->Usuario->id = $id;
        if (!$this->Usuario->exists()) {
            throw new NotFoundException(__('Invalid usuario'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Usuario->delete()) {
            $this->Session->setFlash(__($this::MSG_SUCESSO_DEL));
        } else {
            $this->Session->setFlash(__($this::MSG_ERRO));
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function alterarSenha() {
        if (!$this->Usuario->exists($this->Session->read('Auth.User.id'))) {
            $this->redirect($this->Auth->redirect());
        }

        $usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.' . $this->Usuario->primaryKey => $this->Session->read('Auth.User.id'))));
        $this->set('usuario', $usuario);
        if ($this->request->is('post')) {
            $senha_atua = Security::hash($this->request->data['Usuario']['senha_atual'], null, true);
            $nova_senha = Security::hash($this->request->data['Usuario']['nova_senha'], null, true);
            $confirmar = Security::hash($this->request->data['Usuario']['confirmar_senha'], null, true);

            if ($senha_atua != $usuario['Usuario']['senha']) {
                $this->Session->setFlash("Senha atual n&atilde;o confere!");
                return $this->redirect(array('action' => 'alterarSenha'));
            }

            if ($nova_senha != $confirmar) {
                $this->Session->setFlash("Nova senha n&atilde;o confirmada corretamente!");
                return $this->redirect(array('action' => 'alterarSenha'));
            }

            if ($this->Usuario->updateAll(array('senha' => "'$nova_senha'"), array('id' => $this->Session->read('Auth.User.id')))) {
                $this->Session->setFlash(__('Senha alterada com sucesso!'));
                return $this->redirect(array('controller' => 'mains', 'action' => 'index'));
            } else {
                $this->Session->setFlash(__('Falha ao gravar novo senha!'));
            }
        }
    }

    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->Usuario->updateAll(array('ultimoAcesso' => 'NOW()'), array('id' => $this->Session->read('Auth.User.id')));

                $this->loadModel('UsuarioAcesso');
                $acesso = $this->UsuarioAcesso->find('first', array('conditions' => array('sistema' => $this->idSistema, 'usuario' => $this->Session->read('Auth.User.id'))));

                if ($acesso) {
                    $this->loadModel('Profile');
                    $perfil = $this->Profile->find('first', array('conditions' => array('id' => $acesso['UsuarioAcesso']['perfil'])));
                    $this->Session->write('Perfil', $perfil['Profile']);
                    $this->Session->write('Perfil.sistema', $this->idSistema);
                } else {
                    $this->Session->setFlash("Usu&aacute;rio n&atilde;o possui acesso a este sistema!");
                    $this->redirect($this->Auth->logout());
                }

                $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash("Usu&aacute;rio/senha inv&aacute;lidos");
            }
        }
    }

    public function logout() {
        $this->Session->write('Menu', null);
        $this->Session->write('Perfil', null);
        $this->redirect($this->Auth->logout());
    }

    public function altModulo(){
        $this->Session->write('Perfil.altModulo', true);
        $this->redirect(str_replace('cadastro','financeiro',Router::url('/', true)));
    }

        public function criar(){

    }

    public function esqueci(){
        if ($this->request->is('post')) {
            $usuario = $this->Usuario->find('first', array('fields' => array('id', 'login', 'nome'),'conditions' => array('Usuario.login' => $this->request->data['Usuario']['login'])));
            if(sizeof($usuario)){
                $nova_senha = (date('ymd').$usuario['Usuario']['id']);
                $nova_senha_hash = Security::hash($nova_senha, null, true);
                if ($this->Usuario->updateAll(array('senha' => "'$nova_senha_hash'"), array('id' => $usuario['Usuario']['id']))) {
                    $mail = new PHPMailer();
                    $mail->addAddress($usuario['Usuario']['login'], $usuario['Usuario']['nome']);
                    $mail->setFrom('cadastro@sinprodf.org.br', 'SINPRO-DF');
                    $mail->Subject = utf8_decode('Solicitação de nova senha');
                    $mail->msgHTML("Sua nova senha: $nova_senha");

                    if ($mail->send()) {
                        $this->Session->setFlash('Email enviado. Verifique, tamb&eacute;m, sua caixa de spam.');
                        return $this->redirect(array('action' => 'login'));
                    }else{
                        $this->Session->setFlash(__('Falha ao enviar email!'));
                    }
                } else {
                    $this->Session->setFlash(__('Falha ao gravar novo senha!'));
                }
            } else {
                $this->Session->setFlash('Email n&atilde;o localizado!');
            }
        }
    }

    public function criarAdmin(){
        $this->Usuario->create();
        $this->Usuario->save(array(
                'nome' => 'Administrador',
                'login' => 'admin@sinprodf.org.br',
                'senha' => $this->Auth->password('1234'),
                'status' => '1'
        ));
        exit;
    }

}
