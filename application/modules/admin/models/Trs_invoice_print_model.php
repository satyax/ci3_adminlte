<?php 

class Trs_invoice_print_model extends MY_Model {
  public $_table = 'trs_invoice_print';
  public $primary_key = 'id_invoice_print';
  public $protected_attributes = array( 'id_invoice_print' );
  
  public function filter($arrWhere,$arrLimit) {
    if (isset($arrWhere) && !empty($arrWhere)) $this->db->where($arrWhere);
    if (isset($arrLimit) && !empty($arrLimit)) {
      $this->db->limit($arrLimit[0], $arrLimit[1]);
    }
  }
  
  public function joins($arrJoins) {
    if (isset($arrJoins) && !empty($arrJoins)) {
      foreach ($arrJoins as $joins) {
        if ( isset($joins['table']) && !empty($joins['table']) && isset($joins['fields']) && !empty($joins['fields']) ) {
          if ( isset($joins['type']) && !empty($joins['type'])) {
            $join_type = $joins['type'];
          } else {
            $join_type = null;
          }
          $this->db->join($joins['table'],$joins['fields'],$join_type);
        }
      }
    }
  }
  
  public function selects($selects) {
    if (isset($selects) && !empty($selects)) {
      $this->db->select($selects);
    }
  }
  
  public function get_custom($arrWhere,$arrLimit,$arrJoins,$selects) {
    $this->selects($selects);
    $this->joins($arrJoins);
    $this->filter($arrWhere,$arrLimit);
    
    $query = $this->db->get($this->_table);
    return $query->result();
  }
  
}