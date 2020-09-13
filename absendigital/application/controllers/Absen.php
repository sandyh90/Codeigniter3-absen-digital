<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Absen extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Front');
        $this->get_datasetupapp = $this->M_Front->fetchsetupapp();
        $timezone_all = $this->get_datasetupapp;
        date_default_timezone_set($timezone_all['timezone']);
    }


    public function instant()
    {
        $data = [
            'title' => 'Instant Absen',
            'dataapp' => $this->get_datasetupapp
        ];
        $this->load->view('layout/header', $data);
        $this->load->view('front/instantscan', $data);
        $this->load->view('layout/footerabsen', $data);
    }

    public function confirmabsen()
    {
        $getid = htmlspecialchars($this->input->post('id_pegawai', true));
        $querydata = $this->db->get_where('user', ['kode_pegawai' => $getid, 'is_active' => 1])->row_array();
        if ($querydata) {
            $data = [
                'title' => 'Confirm Instant Absen',
                'dataapp' => $this->get_datasetupapp,
                'cfmabs' => $querydata,
                'dbabsensi' => $this->M_Front->fetchdbabsen($querydata['nama_lengkap'])
            ];
            $this->load->view('layout/header', $data);
            $this->load->view('front/confirminstantscan', $data);
            $this->load->view('layout/footerabsen', $data);
        } else {
            $this->session->set_flashdata('absenmsg', '<div class="alert alert-danger" role="alert">Akun Belum Terdaftar atau QR Code salah!</div>');
            redirect('instantabsen');
        }
    }
}
