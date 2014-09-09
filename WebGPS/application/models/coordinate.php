<?php
class Coordinate extends CI_Model {
    // table name
    private $tbl_coordinates = 'tbl_coordinates';
 
    function __construct(){
      parent::__construct();
    }
    // get coordinates
    function get_list_by_device_id($device_id, $from, $to){
	$this->db->where(array('device_id' => $device_id, 'datetime >=' => $from, 'datetime <=' => $to));
        $this->db->order_by('datetime','asc');
        return $this->db->get($this->tbl_coordinates);
    }
    // get distinct devices
    function get_distinct_devices(){
	$this->db->distinct();
        $this->db->select('device_id');
        return $this->db->get($this->tbl_coordinates);
    }
    // add new coordinate
    function save($coordinate){
        $this->db->insert($this->tbl_coordinates, $coordinate);
        return $this->db->insert_id();
    }
}
?>
