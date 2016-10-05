<?php 

class Trs_notes_files_model extends MY_Model {
  public $_table = 'trs_notes_files';
  public $primary_key = 'id_notes_files';
  public $protected_attributes = array( 'id_notes_files' );
  
  private function filter($arrWhere,$arrLimit=null) {
    if (isset($arrWhere) && !empty($arrWhere)) $this->db->where($arrWhere);
    if (isset($arrLimit) && !empty($arrLimit)) {
      $this->db->limit($arrLimit[0], $arrLimit[1]);
    }
  }
  
  private function joins($arrJoins) {
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
  
  private function selects($selects) {
    if (isset($selects) && !empty($selects)) {
      $this->db->select($selects);
    }
  }
  
  public function get_custom($arrWhere,$arrLimit=null,$arrJoins=null,$selects=null) {
    $this->selects($selects);
    $this->joins($arrJoins);
    $this->filter($arrWhere,$arrLimit);
    
    $query = $this->db->get($this->_table);
    return $query->result();
  }
  
}