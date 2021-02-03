<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_User extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $bulan = array(1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        $hari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
        $this->get_today_date = $hari[(int)date("w")] . ', ' . date("j ") . $bulan[(int)date('m')] . date(" Y");
        $this->get_datasess = $this->db->get_where('user', ['username' =>
        $this->session->userdata('username')])->row_array();
        $this->appsetting = $this->db->get_where('db_setting', ['status_setting' => 1])->row_array();
    }

    public function clearremember($rmbtype)
    {
        if ($rmbtype == 'all') {
            $this->db->delete('db_rememberme', ['username' => $this->get_datasess['username']]);
        } elseif ($rmbtype == 'self') {
            $this->db->delete('db_rememberme', ['id_session' => htmlspecialchars($this->input->post('sess_id', true))]);
        }
    }

    public function fetchrememberme()
    {
        return $this->db->get_where('db_rememberme', ['username' => $this->get_datasess['username']])->result();
    }

    public function user_setting($usrsetting)
    {
        if ($usrsetting == 'basic') {

            if (empty($this->input->post('npwp_pegawai'))) {
                $rownpwp = 'Tidak Ada';
            } else {
                $rownpwp = $this->input->post('npwp_pegawai');
            }
            $sendsave = [
                'umur' => $this->input->post('umur_pegawai'),
                'npwp' => $rownpwp
            ];
            $upload_image = $_FILES['pas_foto']['name'];

            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
                $config['max_size']      = '2048';
                $config['encrypt_name'] = TRUE;
                $config['upload_path'] = '../public/storage/profile/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('pas_foto')) {
                    $gbr = $this->upload->data();
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = base_url('storage/profile/') . $gbr['file_name'];
                    $config['create_thumb'] = FALSE;
                    $config['maintain_ratio'] = FALSE;
                    $config['width'] = 300;
                    $config['height'] = 300;
                    $config['new_image'] = '../public/storage/profile/' . $gbr['file_name'];
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();

                    $old_image = $this->get_datasess['image'];
                    if ($old_image != 'default.png') {
                        unlink(FCPATH . 'storage/profile/' . $old_image);
                    }
                    $new_image = $this->upload->data('file_name');
                    $this->db->set('image', $new_image);
                } else {
                    return "default.png";
                }
            }
            $this->db->set($sendsave);
            $this->db->where('username', htmlspecialchars($this->input->post('username_pegawai', true))); // mengambil data dari session
            $this->db->update('user');
        }
    }
}
