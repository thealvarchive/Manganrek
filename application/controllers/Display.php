<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Display extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Order_model');
    }

    public function index() {
        $data['title']  = 'Kitchen Display';
        $data['orders'] = $this->Order_model->get_orders_kitchen();

        $this->load->view('templates/header_admin', $data);
        $this->load->view('admin/dashboard_kitchen', $data);
        $this->load->view('templates/footer_admin');
    }

    public function selesai($id) {
        $this->Order_model->update_status_kitchen($id, 'selesai');
        redirect('display');
    }
}