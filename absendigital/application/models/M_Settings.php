<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Settings extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->appsetting = $this->db->get_where('db_setting', ['status_setting' => 1])->row_array();
    }

    public function init_setting($typeinit)
    {
        if ($typeinit == 1) {
            $data = [
                'status_setting' => 1,
                'nama_instansi' => '[Ubah Nama Instansi]',
                'jumbotron_lead_set' => '[Ubah Text Berjalan Halaman Depan Disini Pada Setting Aplikasi]',
                'nama_app_absensi' => 'Absensi Online',
                'logo_instansi' => 'default-logo.png',
                'timezone' => 'Asia/Jakarta',
                'absen_mulai' => '06:00:00',
                'absen_mulai_to' => '11:00:00',
                'absen_pulang' => '16:00:00',
                'maps_use' => 0
            ];
            $old_image = $this->appsetting['logo_instansi'];
            if ($old_image != 'default-logo.png') {
                unlink(FCPATH . 'storage/setting/' . $old_image);
            }
            $this->db->get_where('db_setting', ['status_setting' => 1])->row_array();
            $this->db->update('db_setting', $data);
            $this->db->update('user', ['instansi' => '[Ubah Nama Instansi]']);
        } elseif ($typeinit == 2) {
            $data = [
                'status_setting' => 1,
                'nama_instansi' => '[Ubah Nama Instansi]',
                'jumbotron_lead_set' => '[Ubah Text Berjalan Halaman Depan Disini Pada Setting Aplikasi]',
                'nama_app_absensi' => 'Absensi Online',
                'logo_instansi' => 'default-logo.png',
                'timezone' => 'Asia/Jakarta',
                'absen_mulai' => '06:00:00',
                'absen_mulai_to' => '11:00:00',
                'absen_pulang' => '16:00:00',
                'maps_use' => 0
            ];
            $this->db->insert('db_setting', $data);
        }
    }
    public function update_setting()
    {

        $sendsave = [
            'nama_instansi' => htmlspecialchars($this->input->post('nama_instansi')),
            'jumbotron_lead_set' =>  htmlspecialchars($this->input->post('pesan_jumbotron')),
            'nama_app_absensi' =>  htmlspecialchars($this->input->post('nama_app_absen')),
            'timezone' =>  htmlspecialchars($this->input->post('timezone_absen')),
            'absen_mulai' =>  htmlspecialchars($this->input->post('absen_mulai')),
            'absen_mulai_to' =>  htmlspecialchars($this->input->post('absen_sampai')),
            'absen_pulang' =>  htmlspecialchars($this->input->post('absen_pulang_sampai')),
            'maps_use' =>  htmlspecialchars($this->input->post('lokasi_absensi'))
        ];

        $upload_image = $_FILES['logo_instansi']['name'];

        if ($upload_image) {
            $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
            $config['max_size']      = '2048';
            $config['encrypt_name'] = TRUE;
            $config['upload_path'] = '../public/storage/setting/';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo_instansi')) {
                $gbr = $this->upload->data();
                $config['image_library'] = 'gd2';
                $config['source_image'] = '../public/storage/setting/' . $gbr['file_name'];
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = FALSE;
                $config['width'] = 300;
                $config['height'] = 300;
                $config['new_image'] = '../public/storage/setting/' . $gbr['file_name'];
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();

                $old_image = $this->appsetting['logo_instansi'];
                if ($old_image != 'default-logo.png') {
                    unlink(FCPATH . 'storage/setting/' . $old_image);
                }
                $new_image = $this->upload->data('file_name');
                $this->db->set('logo_instansi', $new_image);
            } else {
                return "default-logo.png";
            }
        }
        $this->db->set($sendsave);
        $this->db->where('status_setting', 1);
        $this->db->update('db_setting', $sendsave);
        $this->db->update('user', ['instansi' => htmlspecialchars($this->input->post('nama_instansi'))]);
    }
}
