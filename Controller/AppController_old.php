<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    const MSG_SUCESSO_ADD = 'Dados gravados com sucesso!';
    const MSG_SUCESSO_EDT = 'Dados alterados com sucesso!';
    const MSG_SUCESSO_DEL = 'Dados deletados com sucesso!';
    const MSG_ERRO = 'Erro ao realizar a operação, tente novamente!';

    public $idSistema = '5';
    public $components = array(
        'Session',
        'Auth' => array(
            'authorize' => array('Controller'),
            'loginAction' => array('controller' => 'usuarios', 'action' => 'login'),
            'loginRedirect' => array('controller' => 'mains', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'usuarios', 'action' => 'login'),
            'authenticate' => array('Form' => array('userModel' => 'Usuario',
                'fields' => array('username' => 'login', 'password' => 'senha'),
                'scope' => array('status' => '1')))
        ),
    );

    public function isAuthorized($user) {
        $controllers = $this->perfilAcesso($this->Session->read('Perfil.id'));
        $this->Session->write('Menu', $controllers);
        if (in_array($this->params['controller'], array_keys($controllers))) {

            $actions = in_array($this->params['action'], $controllers[$this->params['controller']]['actions']);
            if($actions || end($controllers[$this->params['controller']]['actions']) == 'all') {
                return true;
            }
            $this->Session->setFlash(__('Usu&aacute;rio n&atilde;o tem permiss&atilde;o para executar est&aacute; a&ccedil;&atilde;o.'));
            $this->redirect(array('controller' => 'mains'));
            return false;
        }
        $this->Session->setFlash(__('Usu&aacute;rio n&atilde;o tem permiss&atilde;o para acessar este m&oacute;dulo.'));
        $this->redirect(array('controller' => 'mains'));
        return false;
    }

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('logout', 'login');
    }

    public function perfilAcesso($id){
        $acessos = array();
        switch ($id) {
            case '1': #Administrador
            $acessos['mains'] = array('view' => false, 'name' => 'Principal', 'icon' => '', 'params' => array('controller' => 'mains', 'action' => 'index'), 'actions' => array('all'));
            $acessos['usuarios'] = array('view' => true, 'name' => 'Usuários', 'icon' => 'icon-group', 'params' => array('controller' => 'usuarios', 'action' => 'index'), 'actions' => array('all'));
            $acessos['itens'] = array('view' => false, 'name' => 'itens', 'icon' => '', 'params' => array('controller' => 'itens', 'action' => 'index'), 'actions' => array('all'));
            $acessos['fornecedores'] = array('view' => true, 'name' => 'Fornecedor', 'icon' => 'icon-briefcase', 'params' => array('controller' => 'fornecedores', 'action' => 'index'), 'actions' => array('all'));
            //$acessos['patrimonios'] = array('view' => true, 'name' => 'Patrim&ocirc;nio', 'icon' => 'icon-book', 'params' => array('controller' => 'patrimonios', 'action' => 'index'), 'actions' => array('all'));
            //$acessos['notas_fiscais'] = array('view' => true, 'name' => 'Nota Fiscal', 'icon' => 'icon-edit', 'params' => array('controller' => 'notas_fiscais', 'action' => 'index'), 'actions' => array('all'));
            $acessos['materiais'] = array('view' => true, 'name' => 'Materiais', 'icon' => 'icon-external-link', 'params' => array('controller' => 'materiais', 'action' => 'index'), 'actions' => array('all'));
            $acessos['materiais_filiais'] = array('name' => 'Estoque Filiais', 'icon' => 'icon-bar-chart', 'params' => array('controller' => 'materiais_filiais'), 'actions' => array('all'), 'submenu' => array(
            array('view' => true, 'name' => 'Filial 01', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial01')),
            //array('view' => true, 'name' => 'Filial 02', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial02')),
            array('view' => true, 'name' => 'Filial 04', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial04')),
            array('view' => true, 'name' => 'Filial 09', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial09')),
            array('view' => true, 'name' => 'EMFOL', 'params' => array('controller' => 'materiais_filiais', 'action' => 'emfol')),
            array('view' => true, 'name' => 'CALTA', 'params' => array('controller' => 'materiais_filiais', 'action' => 'calta')),
            array('view' => true, 'name' => 'AMERICAL', 'params' => array('controller' => 'materiais_filiais', 'action' => 'americal')),
            array('view' => true, 'name' => 'Filial 05', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial05'))
             ));
            $acessos['pedidos'] = array('view' => false, 'name' => 'Pedidos', 'icon' => 'icon-external-link', 'params' => array('controller' => 'pedidos', 'action' => 'index'), 'actions' => array('all'));
            $acessos['compras'] = array('view' => false, 'name' => 'Compras', 'icon' => 'icon-external-link', 'params' => array('controller' => 'compras', 'action' => 'index'), 'actions' => array('all'));
            $acessos['orcamentos'] = array('view' => false, 'name' => 'Orcamentos', 'icon' => 'icon-external-link', 'params' => array('controller' => 'orcamentos', 'action' => 'index'), 'actions' => array('all'));
            $acessos['materiais_classificacoes'] = array('view' => true, 'name' => 'Categoria', 'icon' => 'icon-book', 'params' => array('controller' => 'materiais_classificacoes', 'action' => 'index'), 'actions' => array('all'));
            $acessos['equipamentos'] = array('view' => true, 'name' => 'Equipamentos', 'icon' => 'blue icon-truck', 'params' => array('controller' => 'equipamentos', 'action' => 'index'), 'actions' => array('all'));
            $acessos['transferencias'] = array('view' => true, 'name' => 'Transferencia', 'icon' => 'icon-exchange', 'params' => array('controller' => 'transferencias', 'action' => 'index'), 'actions' => array('all'));
            $acessos['remessas'] = array('view' => true, 'name' => 'Remessa', 'icon' => 'icon-book', 'params' => array('controller' => 'remessas', 'action' => 'index'), 'actions' => array('all'));
            $acessos['relatorios'] = array('view' => true, 'name' => 'Relat&oacute;rio', 'icon' => 'icon-list', 'params' => array('controller' => 'relatorios', 'action' => 'relatorio'), 'actions' => array('all'));

            $acessos['relatorios'] = array('name' => 'Relat&oacute;rios', 'icon' => 'icon-bar-chart', 'params' => array('controller' => 'relatorios'), 'actions' => array('all'), 'submenu' => array(
            array('view' => true, 'name' => 'Materiais', 'params' => array('controller' => 'relatorios', 'action' => 'materiais')),
            array('view' => true, 'name' => 'Saidas', 'params' => array('controller' => 'relatorios', 'action' => 'saida')),
            array('view' => true, 'name' => 'Entradas', 'params' => array('controller' => 'relatorios', 'action' => 'entrada')),
            array('view' => true, 'name' => 'Saidas Central', 'params' => array('controller' => 'materiais', 'action' => 'saida_central')),
            array('view' => true, 'name' => 'Saidas por Filiais', 'params' => array('controller' => 'materiais', 'action' => 'saida_relatorio')),
            array('view' => true, 'name' => 'Entradas Central', 'params' => array('controller' => 'materiais', 'action' => 'entrada_central')),
            array('view' => true, 'name' => 'Orcamentos', 'params' => array('controller' => 'orcamentos', 'action' => 'relatorio_orcamento')),
            array('view' => true, 'name' => 'Oleos', 'params' => array('controller' => 'equipamentos', 'action' => 'relatorio_oleos')),
            array('view' => true, 'name' => 'Equipamentos', 'params' => array('controller' => 'equipamentos', 'action' => 'relatorio_equipamentos')),
            array('view' => true, 'name' => 'Mapa de Lubrificantes', 'params' => array('controller' => 'relatorios', 'action' => 'mapa_lubrificantes'))
            //array('view' => true, 'name' => 'Geral', 'params' => array('controller' => 'relatorios', 'action' => 'relatorio', 'geral')),
            //array('view' => true, 'name' => 'Baixa', 'params' => array('controller' => 'relatorios', 'action' => 'relatorio', 'baixa')),
            //array('view' => true, 'name' => 'Filtros', 'params' => array('controller' => 'relatorios', 'action' => 'filtro'))
             ));
                break;
            case '2': #Gerente Filial
            $acessos['mains'] = array('view' => false, 'name' => 'Principal', 'icon' => '', 'params' => array('controller' => 'mains', 'action' => 'index'), 'actions' => array('all'));
            $acessos['usuarios'] = array('view' => false, 'name' => 'UsuÃ¡rios', 'icon' => '', 'params' => array('controller' => 'usuarios', 'action' => 'index'), 'actions' => array('login','alterarSenha'));
            $acessos['materiais'] = array('view' => true, 'name' => 'Materiais', 'icon' => 'icon-external-link', 'params' => array('controller' => 'materiais', 'action' => 'index'), 'actions' => array('all'));
            $acessos['pedidos'] = array('view' => false, 'name' => 'Pedidos', 'icon' => 'icon-external-link', 'params' => array('controller' => 'pedidos', 'action' => 'index'), 'actions' => array('all'));

            $acessos['relatorios'] = array('name' => 'Relat&oacute;rios', 'icon' => 'icon-bar-chart', 'params' => array('controller' => 'relatorios'), 'actions' => array('all'), 'submenu' => array(
            array('view' => true, 'name' => 'Saidas', 'params' => array('controller' => 'materiais', 'action' => 'relatorio_filialsaida')),
            array('view' => true, 'name' => 'Entradas', 'params' => array('controller' => 'materiais', 'action' => 'relatorio_filialentrada'))
            ));

                break;
            case '3': #Almoxarife Filial
            $acessos['mains'] = array('view' => false, 'name' => 'Principal', 'icon' => '', 'params' => array('controller' => 'mains', 'action' => 'index'), 'actions' => array('all'));
            $acessos['usuarios'] = array('view' => false, 'name' => 'UsuÃ¡rios', 'icon' => '', 'params' => array('controller' => 'usuarios', 'action' => 'index'), 'actions' => array('login','alterarSenha'));
            $acessos['materiais'] = array('view' => true, 'name' => 'Materiais', 'icon' => 'icon-external-link', 'params' => array('controller' => 'materiais', 'action' => 'index'), 'actions' => array('all'));
            $acessos['pedidos'] = array('view' => false, 'name' => 'Pedidos', 'icon' => 'icon-external-link', 'params' => array('controller' => 'pedidos', 'action' => 'index'), 'actions' => array('all'));

            $acessos['relatorios'] = array('name' => 'Relat&oacute;rios', 'icon' => 'icon-bar-chart', 'params' => array('controller' => 'relatorios'), 'actions' => array('all'), 'submenu' => array(
            array('view' => true, 'name' => 'Saidas', 'params' => array('controller' => 'materiais', 'action' => 'relatorio_filialsaida')),
            array('view' => true, 'name' => 'Entradas', 'params' => array('controller' => 'materiais', 'action' => 'relatorio_filialentrada'))
            ));

                break;
            case '4': #Gestor Almoxarifado
            $acessos['mains'] = array('view' => false, 'name' => 'Principal', 'icon' => '', 'params' => array('controller' => 'mains', 'action' => 'index'), 'actions' => array('all'));
            $acessos['usuarios'] = array('view' => false, 'name' => 'Usuários', 'icon' => '', 'params' => array('controller' => 'usuarios', 'action' => 'index'), 'actions' => array('login','alterarSenha'));
            $acessos['itens'] = array('view' => false, 'name' => 'itens', 'icon' => '', 'params' => array('controller' => 'itens', 'action' => 'index'), 'actions' => array('all'));
            $acessos['fornecedores'] = array('view' => true, 'name' => 'Fornecedor', 'icon' => 'icon-briefcase', 'params' => array('controller' => 'fornecedores', 'action' => 'index'), 'actions' => array('all'));
            $acessos['patrimonios'] = array('view' => true, 'name' => 'Patrim&ocirc;nio', 'icon' => 'icon-book', 'params' => array('controller' => 'patrimonios', 'action' => 'index'), 'actions' => array('all'));
            //$acessos['notas_fiscais'] = array('view' => true, 'name' => 'Nota Fiscal', 'icon' => 'icon-edit', 'params' => array('controller' => 'notas_fiscais', 'action' => 'index'), 'actions' => array('all'));
            $acessos['materiais'] = array('view' => true, 'name' => 'Materiais', 'icon' => 'icon-external-link', 'params' => array('controller' => 'materiais', 'action' => 'index'), 'actions' => array('all'));
            $acessos['materiais_filiais'] = array('name' => 'Estoque Filiais', 'icon' => 'icon-bar-chart', 'params' => array('controller' => 'materiais_filiais'), 'actions' => array('all'), 'submenu' => array(
            array('view' => true, 'name' => 'Filial 01', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial01')),
            //array('view' => true, 'name' => 'Filial 02', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial02')),
            array('view' => true, 'name' => 'Filial 04', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial04')),
            array('view' => true, 'name' => 'Filial 09', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial09')),
            array('view' => true, 'name' => 'EMFOL', 'params' => array('controller' => 'materiais_filiais', 'action' => 'emfol')),
            array('view' => true, 'name' => 'CALTA', 'params' => array('controller' => 'materiais_filiais', 'action' => 'calta')),
            array('view' => true, 'name' => 'AMERICAL', 'params' => array('controller' => 'materiais_filiais', 'action' => 'americal')),
            array('view' => true, 'name' => 'Filial 05', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial05'))
             ));
            $acessos['pedidos'] = array('view' => false, 'name' => 'Pedidos', 'icon' => 'icon-external-link', 'params' => array('controller' => 'pedidos', 'action' => 'index'), 'actions' => array('all'));
            $acessos['compras'] = array('view' => false, 'name' => 'Compras', 'icon' => 'icon-external-link', 'params' => array('controller' => 'compras', 'action' => 'index'), 'actions' => array('all'));
            $acessos['orcamentos'] = array('view' => false, 'name' => 'Orcamentos', 'icon' => 'icon-external-link', 'params' => array('controller' => 'orcamentos', 'action' => 'index'), 'actions' => array('all'));
            $acessos['materiais_classificacoes'] = array('view' => true, 'name' => 'Categoria', 'icon' => 'icon-book', 'params' => array('controller' => 'materiais_classificacoes', 'action' => 'index'), 'actions' => array('all'));
            $acessos['transferencias'] = array('view' => true, 'name' => 'Transferencia', 'icon' => 'icon-book', 'params' => array('controller' => 'transferencias', 'action' => 'index'), 'actions' => array('all'));
            $acessos['remessas'] = array('view' => true, 'name' => 'Remessa', 'icon' => 'icon-book', 'params' => array('controller' => 'remessas', 'action' => 'index'), 'actions' => array('all'));
            $acessos['relatorios'] = array('view' => true, 'name' => 'Relat&oacute;rio', 'icon' => 'icon-list', 'params' => array('controller' => 'relatorios', 'action' => 'relatorio'), 'actions' => array('all'));

            $acessos['relatorios'] = array('name' => 'Relat&oacute;rios', 'icon' => 'icon-bar-chart', 'params' => array('controller' => 'relatorios'), 'actions' => array('all'), 'submenu' => array(
            array('view' => true, 'name' => 'Materiais', 'params' => array('controller' => 'relatorios', 'action' => 'materiais')),
            array('view' => true, 'name' => 'Saidas', 'params' => array('controller' => 'relatorios', 'action' => 'saida')),
            array('view' => true, 'name' => 'Entradas', 'params' => array('controller' => 'relatorios', 'action' => 'entrada')),
            array('view' => true, 'name' => 'Saidas Central', 'params' => array('controller' => 'materiais', 'action' => 'saida_central')),
            array('view' => true, 'name' => 'Saidas por Filiais', 'params' => array('controller' => 'materiais', 'action' => 'saida_relatorio')),
            array('view' => true, 'name' => 'Entradas Central', 'params' => array('controller' => 'materiais', 'action' => 'entrada_central'))
            //array('view' => true, 'name' => 'Geral', 'params' => array('controller' => 'relatorios', 'action' => 'relatorio', 'geral')),
            //array('view' => true, 'name' => 'Baixa', 'params' => array('controller' => 'relatorios', 'action' => 'relatorio', 'baixa')),
            // array('view' => true, 'name' => 'Filtros', 'params' => array('controller' => 'relatorios', 'action' => 'filtro'))
             ));

                break;

            case '5': #Gerente Geral
            $acessos['mains'] = array('view' => false, 'name' => 'Principal', 'icon' => '', 'params' => array('controller' => 'mains', 'action' => 'index'), 'actions' => array('all'));
            $acessos['usuarios'] = array('view' => false, 'name' => 'Usuários', 'icon' => '', 'params' => array('controller' => 'usuarios', 'action' => 'index'), 'actions' => array('login','alterarSenha'));
            $acessos['itens'] = array('view' => false, 'name' => 'itens', 'icon' => '', 'params' => array('controller' => 'itens', 'action' => 'index'), 'actions' => array('all'));
            $acessos['fornecedores'] = array('view' => true, 'name' => 'Fornecedor', 'icon' => 'icon-briefcase', 'params' => array('controller' => 'fornecedores', 'action' => 'index'), 'actions' => array('all'));
            $acessos['patrimonios'] = array('view' => true, 'name' => 'Patrim&ocirc;nio', 'icon' => 'icon-book', 'params' => array('controller' => 'patrimonios', 'action' => 'index'), 'actions' => array('all'));
            //$acessos['notas_fiscais'] = array('view' => true, 'name' => 'Nota Fiscal', 'icon' => 'icon-edit', 'params' => array('controller' => 'notas_fiscais', 'action' => 'index'), 'actions' => array('all'));
            $acessos['materiais'] = array('view' => true, 'name' => 'Materiais', 'icon' => 'icon-external-link', 'params' => array('controller' => 'materiais', 'action' => 'index'), 'actions' => array('all'));
            $acessos['materiais_filiais'] = array('name' => 'Estoque Filiais', 'icon' => 'icon-bar-chart', 'params' => array('controller' => 'materiais_filiais'), 'actions' => array('all'), 'submenu' => array(
            array('view' => true, 'name' => 'Filial 01', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial01')),
            //array('view' => true, 'name' => 'Filial 02', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial02')),
            array('view' => true, 'name' => 'Filial 04', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial04')),
            array('view' => true, 'name' => 'Filial 09', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial09')),
            array('view' => true, 'name' => 'EMFOL', 'params' => array('controller' => 'materiais_filiais', 'action' => 'emfol')),
            array('view' => true, 'name' => 'CALTA', 'params' => array('controller' => 'materiais_filiais', 'action' => 'calta')),
            array('view' => true, 'name' => 'AMERICAL', 'params' => array('controller' => 'materiais_filiais', 'action' => 'americal')),
            array('view' => true, 'name' => 'Filial 05', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial05'))
             ));
            $acessos['pedidos'] = array('view' => false, 'name' => 'Pedidos', 'icon' => 'icon-external-link', 'params' => array('controller' => 'pedidos', 'action' => 'index'), 'actions' => array('all'));
            $acessos['compras'] = array('view' => false, 'name' => 'Compras', 'icon' => 'icon-external-link', 'params' => array('controller' => 'compras', 'action' => 'index'), 'actions' => array('all'));
            $acessos['orcamentos'] = array('view' => false, 'name' => 'Orcamentos', 'icon' => 'icon-external-link', 'params' => array('controller' => 'orcamentos', 'action' => 'index'), 'actions' => array('all'));
            $acessos['materiais_classificacoes'] = array('view' => true, 'name' => 'Categoria', 'icon' => 'icon-book', 'params' => array('controller' => 'materiais_classificacoes', 'action' => 'index'), 'actions' => array('all'));
            $acessos['transferencias'] = array('view' => true, 'name' => 'Transferencia', 'icon' => 'icon-book', 'params' => array('controller' => 'transferencias', 'action' => 'index'), 'actions' => array('all'));
            $acessos['remessas'] = array('view' => true, 'name' => 'Remessa', 'icon' => 'icon-book', 'params' => array('controller' => 'remessas', 'action' => 'index'), 'actions' => array('all'));
            $acessos['relatorios'] = array('view' => true, 'name' => 'Relat&oacute;rio', 'icon' => 'icon-list', 'params' => array('controller' => 'relatorios', 'action' => 'relatorio'), 'actions' => array('all'));

            $acessos['relatorios'] = array('name' => 'Relat&oacute;rios', 'icon' => 'icon-bar-chart', 'params' => array('controller' => 'relatorios'), 'actions' => array('all'), 'submenu' => array(
            array('view' => true, 'name' => 'Materiais', 'params' => array('controller' => 'relatorios', 'action' => 'materiais')),
            array('view' => true, 'name' => 'Saidas', 'params' => array('controller' => 'relatorios', 'action' => 'saida')),
            array('view' => true, 'name' => 'Entradas', 'params' => array('controller' => 'relatorios', 'action' => 'entrada')),
            array('view' => true, 'name' => 'Saidas Central', 'params' => array('controller' => 'materiais', 'action' => 'saida_central')),
            array('view' => true, 'name' => 'Saidas por Filiais', 'params' => array('controller' => 'materiais', 'action' => 'saida_relatorio')),
            array('view' => true, 'name' => 'Entradas Central', 'params' => array('controller' => 'materiais', 'action' => 'entrada_central'))
            //array('view' => true, 'name' => 'Geral', 'params' => array('controller' => 'relatorios', 'action' => 'relatorio', 'geral')),
            //array('view' => true, 'name' => 'Baixa', 'params' => array('controller' => 'relatorios', 'action' => 'relatorio', 'baixa')),
            // array('view' => true, 'name' => 'Filtros', 'params' => array('controller' => 'relatorios', 'action' => 'filtro'))
             ));

                break;

            case '6': #Diretor
            $acessos['mains'] = array('view' => false, 'name' => 'Principal', 'icon' => '', 'params' => array('controller' => 'mains', 'action' => 'index'), 'actions' => array('all'));
            $acessos['usuarios'] = array('view' => false, 'name' => 'Usuários', 'icon' => '', 'params' => array('controller' => 'usuarios', 'action' => 'index'), 'actions' => array('login','alterarSenha'));
            $acessos['itens'] = array('view' => false, 'name' => 'itens', 'icon' => '', 'params' => array('controller' => 'itens', 'action' => 'index'), 'actions' => array('all'));
            $acessos['fornecedores'] = array('view' => true, 'name' => 'Fornecedor', 'icon' => 'icon-briefcase', 'params' => array('controller' => 'fornecedores', 'action' => 'index'), 'actions' => array('all'));
            $acessos['patrimonios'] = array('view' => true, 'name' => 'Patrim&ocirc;nio', 'icon' => 'icon-book', 'params' => array('controller' => 'patrimonios', 'action' => 'index'), 'actions' => array('all'));
            //$acessos['notas_fiscais'] = array('view' => true, 'name' => 'Nota Fiscal', 'icon' => 'icon-edit', 'params' => array('controller' => 'notas_fiscais', 'action' => 'index'), 'actions' => array('all'));
            $acessos['materiais'] = array('view' => true, 'name' => 'Materiais', 'icon' => 'icon-external-link', 'params' => array('controller' => 'materiais', 'action' => 'index'), 'actions' => array('all'));
            $acessos['materiais_filiais'] = array('name' => 'Estoque Filiais', 'icon' => 'icon-bar-chart', 'params' => array('controller' => 'materiais_filiais'), 'actions' => array('all'), 'submenu' => array(
            array('view' => true, 'name' => 'Filial 01', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial01')),
            //array('view' => true, 'name' => 'Filial 02', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial02')),
            array('view' => true, 'name' => 'Filial 04', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial04')),
            array('view' => true, 'name' => 'Filial 09', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial09')),
            array('view' => true, 'name' => 'EMFOL', 'params' => array('controller' => 'materiais_filiais', 'action' => 'emfol')),
            array('view' => true, 'name' => 'CALTA', 'params' => array('controller' => 'materiais_filiais', 'action' => 'calta')),
            array('view' => true, 'name' => 'AMERICAL', 'params' => array('controller' => 'materiais_filiais', 'action' => 'americal')),
            array('view' => true, 'name' => 'Filial 05', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial05'))
             ));
            $acessos['pedidos'] = array('view' => false, 'name' => 'Pedidos', 'icon' => 'icon-external-link', 'params' => array('controller' => 'pedidos', 'action' => 'index'), 'actions' => array('all'));
            $acessos['compras'] = array('view' => false, 'name' => 'Compras', 'icon' => 'icon-external-link', 'params' => array('controller' => 'compras', 'action' => 'index'), 'actions' => array('all'));
            $acessos['orcamentos'] = array('view' => false, 'name' => 'Orcamentos', 'icon' => 'icon-external-link', 'params' => array('controller' => 'orcamentos', 'action' => 'index'), 'actions' => array('all'));
            $acessos['materiais_classificacoes'] = array('view' => true, 'name' => 'Categoria', 'icon' => 'icon-book', 'params' => array('controller' => 'materiais_classificacoes', 'action' => 'index'), 'actions' => array('all'));
            $acessos['transferencias'] = array('view' => true, 'name' => 'Transferencia', 'icon' => 'icon-book', 'params' => array('controller' => 'transferencias', 'action' => 'index'), 'actions' => array('all'));
            $acessos['remessas'] = array('view' => true, 'name' => 'Remessa', 'icon' => 'icon-book', 'params' => array('controller' => 'remessas', 'action' => 'index'), 'actions' => array('all'));
            $acessos['relatorios'] = array('view' => true, 'name' => 'Relat&oacute;rio', 'icon' => 'icon-list', 'params' => array('controller' => 'relatorios', 'action' => 'relatorio'), 'actions' => array('all'));

            $acessos['relatorios'] = array('name' => 'Relat&oacute;rios', 'icon' => 'icon-bar-chart', 'params' => array('controller' => 'relatorios'), 'actions' => array('all'), 'submenu' => array(
            array('view' => true, 'name' => 'Materiais', 'params' => array('controller' => 'relatorios', 'action' => 'materiais')),
            array('view' => true, 'name' => 'Saidas', 'params' => array('controller' => 'relatorios', 'action' => 'saida')),
            array('view' => true, 'name' => 'Entradas', 'params' => array('controller' => 'relatorios', 'action' => 'entrada')),
            array('view' => true, 'name' => 'Saidas Central', 'params' => array('controller' => 'materiais', 'action' => 'saida_central')),
            array('view' => true, 'name' => 'Saidas por Filiais', 'params' => array('controller' => 'materiais', 'action' => 'saida_relatorio')),
            array('view' => true, 'name' => 'Entradas Central', 'params' => array('controller' => 'materiais', 'action' => 'entrada_central'))
            //array('view' => true, 'name' => 'Geral', 'params' => array('controller' => 'relatorios', 'action' => 'relatorio', 'geral')),
            //array('view' => true, 'name' => 'Baixa', 'params' => array('controller' => 'relatorios', 'action' => 'relatorio', 'baixa')),
            // array('view' => true, 'name' => 'Filtros', 'params' => array('controller' => 'relatorios', 'action' => 'filtro'))
            ));

                  break;

            case '7': #Gestor Financeiro
            $acessos['mains'] = array('view' => false, 'name' => 'Principal', 'icon' => '', 'params' => array('controller' => 'mains', 'action' => 'index'), 'actions' => array('all'));
            $acessos['usuarios'] = array('view' => false, 'name' => 'Usuários', 'icon' => '', 'params' => array('controller' => 'usuarios', 'action' => 'index'), 'actions' => array('login','alterarSenha'));
            $acessos['itens'] = array('view' => false, 'name' => 'itens', 'icon' => '', 'params' => array('controller' => 'itens', 'action' => 'index'), 'actions' => array('all'));
            $acessos['fornecedores'] = array('view' => true, 'name' => 'Fornecedor', 'icon' => 'icon-briefcase', 'params' => array('controller' => 'fornecedores', 'action' => 'index'), 'actions' => array('all'));
            $acessos['patrimonios'] = array('view' => true, 'name' => 'Patrim&ocirc;nio', 'icon' => 'icon-book', 'params' => array('controller' => 'patrimonios', 'action' => 'index'), 'actions' => array('all'));
            //$acessos['notas_fiscais'] = array('view' => true, 'name' => 'Nota Fiscal', 'icon' => 'icon-edit', 'params' => array('controller' => 'notas_fiscais', 'action' => 'index'), 'actions' => array('all'));
            $acessos['materiais'] = array('view' => true, 'name' => 'Materiais', 'icon' => 'icon-external-link', 'params' => array('controller' => 'materiais', 'action' => 'index'), 'actions' => array('all'));
            $acessos['materiais_filiais'] = array('name' => 'Estoque Filiais', 'icon' => 'icon-bar-chart', 'params' => array('controller' => 'materiais_filiais'), 'actions' => array('all'), 'submenu' => array(
            array('view' => true, 'name' => 'Filial 01', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial01')),
            //array('view' => true, 'name' => 'Filial 02', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial02')),
            array('view' => true, 'name' => 'Filial 04', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial04')),
            array('view' => true, 'name' => 'Filial 09', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial09')),
            array('view' => true, 'name' => 'EMFOL', 'params' => array('controller' => 'materiais_filiais', 'action' => 'emfol')),
            array('view' => true, 'name' => 'CALTA', 'params' => array('controller' => 'materiais_filiais', 'action' => 'calta')),
            array('view' => true, 'name' => 'AMERICAL', 'params' => array('controller' => 'materiais_filiais', 'action' => 'americal')),
            array('view' => true, 'name' => 'Filial 05', 'params' => array('controller' => 'materiais_filiais', 'action' => 'filial05'))
             ));
            $acessos['pedidos'] = array('view' => false, 'name' => 'Pedidos', 'icon' => 'icon-external-link', 'params' => array('controller' => 'pedidos', 'action' => 'index'), 'actions' => array('all'));
            $acessos['compras'] = array('view' => false, 'name' => 'Compras', 'icon' => 'icon-external-link', 'params' => array('controller' => 'compras', 'action' => 'index'), 'actions' => array('all'));
            $acessos['orcamentos'] = array('view' => false, 'name' => 'Orcamentos', 'icon' => 'icon-external-link', 'params' => array('controller' => 'orcamentos', 'action' => 'index'), 'actions' => array('all'));
            $acessos['materiais_classificacoes'] = array('view' => true, 'name' => 'Categoria', 'icon' => 'icon-book', 'params' => array('controller' => 'materiais_classificacoes', 'action' => 'index'), 'actions' => array('all'));
            $acessos['transferencias'] = array('view' => true, 'name' => 'Transferencia', 'icon' => 'icon-book', 'params' => array('controller' => 'transferencias', 'action' => 'index'), 'actions' => array('all'));
            $acessos['remessas'] = array('view' => true, 'name' => 'Remessa', 'icon' => 'icon-book', 'params' => array('controller' => 'remessas', 'action' => 'index'), 'actions' => array('all'));
            $acessos['relatorios'] = array('view' => true, 'name' => 'Relat&oacute;rio', 'icon' => 'icon-list', 'params' => array('controller' => 'relatorios', 'action' => 'relatorio'), 'actions' => array('all'));

            $acessos['relatorios'] = array('name' => 'Relat&oacute;rios', 'icon' => 'icon-bar-chart', 'params' => array('controller' => 'relatorios'), 'actions' => array('all'), 'submenu' => array(
            array('view' => true, 'name' => 'Materiais', 'params' => array('controller' => 'relatorios', 'action' => 'materiais')),
            array('view' => true, 'name' => 'Saidas', 'params' => array('controller' => 'relatorios', 'action' => 'saida')),
            array('view' => true, 'name' => 'Entradas', 'params' => array('controller' => 'relatorios', 'action' => 'entrada')),
            array('view' => true, 'name' => 'Saidas Central', 'params' => array('controller' => 'materiais', 'action' => 'saida_central')),
            array('view' => true, 'name' => 'Saidas por Filiais', 'params' => array('controller' => 'materiais', 'action' => 'saida_relatorio')),
            array('view' => true, 'name' => 'Entradas Central', 'params' => array('controller' => 'materiais', 'action' => 'entrada_central')),
            array('view' => true, 'name' => 'Orcamentos', 'params' => array('controller' => 'orcamentos', 'action' => 'relatorio_orcamento')),
	    array('view' => true, 'name' => 'Mapa de Lubrificantes', 'params' => array('controller' => 'relatorios', 'action' => 'mapa_lubrificantes'))
            //array('view' => true, 'name' => 'Geral', 'params' => array('controller' => 'relatorios', 'action' => 'relatorio', 'geral')),
            //array('view' => true, 'name' => 'Baixa', 'params' => array('controller' => 'relatorios', 'action' => 'relatorio', 'baixa')),
            // array('view' => true, 'name' => 'Filtros', 'params' => array('controller' => 'relatorios', 'action' => 'filtro'))
            ));

            break;

            case '8': #Gestor de Manuten��o
            $acessos['mains'] = array('view' => false, 'name' => 'Principal', 'icon' => '', 'params' => array('controller' => 'mains', 'action' => 'index'), 'actions' => array('all'));
            $acessos['usuarios'] = array('view' => false, 'name' => 'UsuÃ¡rios', 'icon' => '', 'params' => array('controller' => 'usuarios', 'action' => 'index'), 'actions' => array('login','alterarSenha'));
            $acessos['equipamentos'] = array('view' => true, 'name' => 'Equipamentos', 'icon' => 'blue icon-truck', 'params' => array('controller' => 'equipamentos', 'action' => 'index'), 'actions' => array('all'));
            //$acessos['materiais'] = array('view' => true, 'name' => 'Materiais', 'icon' => 'icon-external-link', 'params' => array('controller' => 'materiais', 'action' => 'index'), 'actions' => array('all'));
            //$acessos['pedidos'] = array('view' => false, 'name' => 'Pedidos', 'icon' => 'icon-external-link', 'params' => array('controller' => 'pedidos', 'action' => 'index'), 'actions' => array('all'));

            $acessos['relatorios'] = array('name' => 'Relat&oacute;rios', 'icon' => 'icon-bar-chart', 'params' => array('controller' => 'relatorios'), 'actions' => array('all'), 'submenu' => array(
              array('view' => true, 'name' => 'Oleos', 'params' => array('controller' => 'equipamentos', 'action' => 'relatorio_oleos')),
              array('view' => true, 'name' => 'Equipamentos', 'params' => array('controller' => 'equipamentos', 'action' => 'relatorio_equipamentos'))
            ));

                break;

            default :
                $acessos['usuarios'] = array('view' => true, 'name' => 'Usu&aacute;rios', 'icon' => '', 'params' => array('controller' => 'usuarios', 'action' => 'index'), 'actions' => $this->acessoLivre());
                break;
        }
        return $acessos;
    }
}
