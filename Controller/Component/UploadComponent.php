<?php
App::uses('Component', 'Controller');

class UploadComponent extends Component{

  public $max_files = 2;

  public function upload($data = null){
    if(!empty($data)){
      if(count($data)>$this->max_files){
        throw new NotFoundException("Erro Processing Request, Max Number files accepted is {$this->max_files}",1);
      }
      foreach ($data as $file) {
        $filename =  $file['name'];
        $file_tmp_name = $file['tmp_name'];
        $dir = WWW_ROOT.'img'.DS.'uploads';
        $allowed = array('png', 'jpg', 'jpeg', 'bmp', 'pdf');
        if(!in_array(substr(strrchr($filename, '.'), 1), $allowed)){
          throw new NotFoundException("Erro Processing Request", 1);
        }elseif(is_uploaded_file($file_tmp_name)) {
          move_uploaded_file($file_tmp_name, $dir.DS.$filename);
          //move_uploaded_file($file_tmp_name, $dir.DS.CakeText::uuid().'-'.$filename);
        }
      }
    }
  }

}
