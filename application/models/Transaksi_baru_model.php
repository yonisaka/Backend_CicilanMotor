<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_baru_model extends CI_Model {

    public function get_transaksi($limit, $offset, $orderby, $method, $search)
    {
        $this->db->select('*');
        $this->db->from('transaksi');

        if ($limit > -1) {
            $this->db->limit($limit, $offset);
        }

        if($search != '' && $search!= null){
            $this->db->like('id_transaksi', $search);
        }

        $this->db->order_by($orderby, $method);
        return $this->db->get();
    }
}