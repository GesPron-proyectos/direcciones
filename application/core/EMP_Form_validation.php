<?php
class EMP_Form_validation extends CI_Form_validation {

    function run($obj = '', $group = '')
    { 
        (is_object($obj)) AND $this->CI =& $obj;
        return parent::run($group);
    }
} 
?>