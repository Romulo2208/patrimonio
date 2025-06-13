<?php
App::uses('AppModel', 'Model');

class DBMongo extends AppModel {
    
    public $useTable = false;
    public $error = false;
    public $collection = null;
    public $mongo;
    public $grid;
    public $db;
    
    function __construct() {
//        try {
//            $this->mongo = new MongoClient('mongodb://192.168.1.233:27017');
//            $this->db = $this->mongo->patrimonio;
//            $this->collection = $this->db->log;
//            $this->grid = $this->db->getGridFS('documento');
//        } catch (Exception $e) {
//            $this->error = true;
//            CakeSession::Write('Message.error', array('message' => "MongoDB: {$e->getMessage()}", 'element' => 'default', 'params' => Array()));
//        }
    }
    
    public function listar($id=null) {
//        $files = array();
//        $cursor = $this->grid->find(array('nota_fiscal_id' => $id));
//        foreach ($cursor as $obj) {
//            $files[] = $obj;
//        }
//        return $files;
    }
    
    public function add($array = array()) {
//        if(sizeof($array)) {
//            $array[key($array)]['log_model'] = key($array);
//            $array[key($array)]['log_data_hora'] = new MongoDate(strtotime(date('Y-m-d H:i:s')));
//            if(!$this->error) {
//                $this->collection->insert($array[key($array)]);
//            }
//        }
    }
    
    public function view($array = array()) {
        return $this->collection->find($array)->limit(20)
                ->sort(array("_id" => -1));
    }
    
}