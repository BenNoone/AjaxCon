<?php

class AjaxCon{
    private $init = false;
    private $functionArray = array();
    private $Message = false;
    private $function = null;
    private $arguments = null;
    private $post = null;

    public function __construct(){
        if(!isset($_POST['ajaxRequest'], $_POST['ajaxFunction'])) return;
        $this->init = true;
        ob_start();
        $this->post = $this->sanitizeArr($_POST);
        $this->arguments = $this->sanitizeArr($_POST['arguments']);
        $this->function = $_POST['ajaxFunction'];
    }

    public function addFunction($functionName, $functionCode){
        if(!$this->init || !isset($functionName, $functionCode) || $functionName != $this->function) return $this;
        $this->Message = call_user_func_array($functionCode, $this->arguments) ? 'success' : 'fail' ;
        unset($this->init, $this->functionArray, $this->post);
        $returnVars = get_object_vars($this);
        ob_clean();
        echo json_encode($returnVars);
        ob_flush();
        exit();
    }

    private function sanitizeArr($returnArr, $sanitizer = 'sanitize_sql_string') {
        array_walk_recursive($returnArr, function (&$value) use ($sanitizer) {
            $value = $sanitizer($value);
        });

        return $returnArr;
    }
}

?>