<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Admin extends CI_Model
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

    public function hitungjumlahdata($typehitung)
    {
        $today = $this->get_today_date;
        if ($typehitung == 'jmlpgw') {

            $query = $this->db->get('user');
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            } else {
                return 0;
            }
        } elseif ($typehitung == 'pgwtrl') {
            $query = $this->db->get_where('db_absensi', ['status_pegawai' => 2, 'tgl_absen' => $today]);
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            } else {
                return 0;
            }
        } elseif ($typehitung == 'pgwmsk') {
            $query = $this->db->get_where('db_absensi', ['status_pegawai' => 1, 'tgl_absen' => $today]);
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            } else {
                return 0;
            }
        }
    }

    public function fetchlistpegawai()
    {
        return $this->db->get_where('user')->result();
    }

    public function crudpgw($typesend)
    {
        if ($typesend == 'addpgw') {

            $kd_pegawai = random_string('numeric', 15);

            if (empty(htmlspecialchars($this->input->post('npwp_pegawai')))) {
                $rownpwp = 'Tidak Ada';
            } else {
                $rownpwp = $this->input->post('npwp_pegawai');
            }

            $upload_image = $_FILES['foto_pegawai']['name'];

            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
                $config['max_size']      = '2048';
                $config['encrypt_name'] = TRUE;
                $config['upload_path'] = $this->config->item('SAVE_FOLDER_PROFILE');

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto_pegawai')) {
                    $gbr = $this->upload->data();
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $this->config->item('SAVE_FOLDER_PROFILE') . $gbr['file_name'];
                    $config['create_thumb'] = FALSE;
                    $config['maintain_ratio'] = FALSE;
                    $config['width'] = 300;
                    $config['height'] = 300;
                    $config['new_image'] = $this->config->item('SAVE_FOLDER_PROFILE') . $gbr['file_name'];
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();

                    $new_image = $this->upload->data('file_name');
                    $this->db->set('image', $new_image);
                } else {
                    return "default.png";
                }
            } else {
                $this->db->set('image', 'default.png');
            }

            if (!empty(htmlspecialchars($this->input->post('barcode_pegawai')))) {
                $this->load->library('ciqrcode'); //pemanggilan library QR CODE

                $config['cacheable']    = true; //boolean, the default is true
                $config['cachedir']     = $this->config->item('MISC_SAVE_FOLDER') . 'sys/cache/'; //string, the default is application/cache/
                $config['errorlog']     = $this->config->item('MISC_SAVE_FOLDER') . 'sys/log/'; //string, the default is application/logs/
                $config['imagedir']     = $this->config->item('SAVE_FOLDER_QRCODE'); //direktori penyimpanan qr code
                $config['quality']      = true; //boolean, the default is true
                $config['size']         = '1024'; //interger, the default is 1024
                $config['black']        = array(224, 255, 255); // array, default is array(255,255,255)
                $config['white']        = array(70, 130, 180); // array, default is array(0,0,0)
                $this->ciqrcode->initialize($config);

                $image_name = 'qr_code_' . $kd_pegawai . '.png'; //buat name dari qr code sesuai dengan nim

                $params['data'] = $kd_pegawai; //data yang akan di jadikan QR CODE
                $params['level'] = 'H'; //H=High
                $params['size'] = 10;
                $params['savename'] = $config['imagedir'] . $image_name; //simpan image QR CODE
                $this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
                $senddata = [
                    'qr_code_image' => $image_name,
                    'qr_code_use' => 1
                ];
                $this->db->set($senddata);
            } elseif (empty(htmlspecialchars($this->input->post('barcode_pegawai')))) {
                $senddata = [
                    'qr_code_image' => 'no-qrcode.png',
                    'qr_code_use' => 0
                ];
                $this->db->set($senddata);
            }
            $sendsave = [
                'nama_lengkap' => htmlspecialchars($this->input->post('nama_pegawai')),
                'username' => htmlspecialchars($this->input->post('username_pegawai')),
                'password' => password_hash($this->input->post('password_pegawai'), PASSWORD_DEFAULT),
                'kode_pegawai' => $kd_pegawai,
                'jabatan' => htmlspecialchars($this->input->post('jabatan_pegawai')),
                'instansi' => $this->appsetting['nama_instansi'],
                'npwp' => $rownpwp,
                'umur' => htmlspecialchars($this->input->post('umur_pegawai')),
                'tempat_lahir' => htmlspecialchars($this->input->post('tempat_lahir_pegawai')),
                'tgl_lahir' => htmlspecialchars($this->input->post('tgl_lahir_pegawai')),
                'jenis_kelamin' => htmlspecialchars($this->input->post('jenis_kelamin_pegawai')),
                'bagian_shift' => htmlspecialchars($this->input->post('shift_pegawai')),
                'is_active' => htmlspecialchars($this->input->post('verifikasi_pegawai')),
                'role_id' => htmlspecialchars($this->input->post('role_pegawai')),
                'date_created' => time()
            ];
            $this->db->insert('user', $sendsave);
        } elseif ($typesend == 'delpgw') {
            $query = $this->db->get_where('user', ['id_pegawai' => htmlspecialchars($this->input->post('pgw_id', true))])->row_array();

            $old_image = $query['image'];
            if ($old_image != 'default-profile.png') {
                unlink($this->config->item('SAVE_FOLDER_PROFILE') . $old_image);
            }
            $old_qrcode = $query['qr_code_image'];
            if ($old_qrcode != 'no-qrcode.png') {
                unlink($this->config->item('SAVE_FOLDER_QRCODE') . $old_qrcode);
            }
            $this->db->delete('user', ['id_pegawai' => htmlspecialchars($this->input->post('pgw_id', true))]);
        } elseif ($typesend == 'actpgw') {
            $this->db->set('is_active', 1);
            $this->db->where('id_pegawai', htmlspecialchars($this->input->post('pgw_id', true)));
            $this->db->update('user');
        } elseif ($typesend == 'edtpgwalt') {
            $query_user = $this->db->get_where('user', ['id_pegawai' => htmlspecialchars($this->input->post('id_pegawai_edit', true))])->row_array();
            $kd_pegawai = $query_user['kode_pegawai'];
            $queryimage = $query_user;
            if (empty(htmlspecialchars($this->input->post('npwp_pegawai_edit')))) {
                $rownpwp = 'Tidak Ada';
            } else {
                $rownpwp = $this->input->post('npwp_pegawai_edit');
            }

            if (!empty(htmlspecialchars($this->input->post('password_pegawai_edit')))) {
                $this->db->set('password', password_hash($this->input->post('password_pegawai_edit'), PASSWORD_DEFAULT));
            }

            if (empty($this->input->post('barcode_pegawai_edit'))) {
                $old_qrcode = $queryimage['qr_code_image'];
                if ($old_qrcode != 'no-qrcode.png') {
                    unlink($this->config->item('SAVE_FOLDER_QRCODE') . $old_qrcode);
                }
                $senddata = [
                    'qr_code_image' => 'no-qrcode.png',
                    'qr_code_use' => 0
                ];
                $this->db->set($senddata);
            } elseif (!empty($this->input->post('barcode_pegawai_edit')) && $queryimage['qr_code_image'] == 'no-qrcode.png') {
                $this->load->library('ciqrcode'); //pemanggilan library QR CODE

                $config['cacheable']    = true; //boolean, the default is true
                $config['cachedir']     = $this->config->item('MISC_SAVE_FOLDER') . 'sys/cache/'; //string, the default is application/cache/
                $config['errorlog']     = $this->config->item('MISC_SAVE_FOLDER') . 'sys/log/'; //string, the default is application/logs/
                $config['imagedir']     = $this->config->item('SAVE_FOLDER_QRCODE'); //direktori penyimpanan qr code
                $config['quality']      = true; //boolean, the default is true
                $config['size']         = '1024'; //interger, the default is 1024
                $config['black']        = array(224, 255, 255); // array, default is array(255,255,255)
                $config['white']        = array(70, 130, 180); // array, default is array(0,0,0)
                $this->ciqrcode->initialize($config);

                $image_name = 'qr_code_' . $kd_pegawai . '.png'; //buat name dari qr code sesuai dengan nim

                $params['data'] = $kd_pegawai; //data yang akan di jadikan QR CODE
                $params['level'] = 'H'; //H=High
                $params['size'] = 10;
                $params['savename'] = $config['imagedir'] . $image_name; //simpan image QR CODE ke folder assets/images/
                $this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
                $senddata = [
                    'qr_code_image' => $image_name,
                    'qr_code_use' => 1
                ];
                $this->db->set($senddata);
            }

            $upload_image = $_FILES['foto_pegawai_edit']['name'];

            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
                $config['max_size']      = '2048';
                $config['encrypt_name'] = TRUE;
                $config['upload_path'] = $this->config->item('SAVE_FOLDER_PROFILE');

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto_pegawai_edit')) {
                    $gbr = $this->upload->data();
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $this->config->item('SAVE_FOLDER_PROFILE') . $gbr['file_name'];
                    $config['create_thumb'] = FALSE;
                    $config['maintain_ratio'] = FALSE;
                    $config['width'] = 300;
                    $config['height'] = 300;
                    $config['new_image'] = $this->config->item('SAVE_FOLDER_PROFILE') . $gbr['file_name'];
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();

                    $old_image = $queryimage['image'];
                    if ($old_image != 'default.png') {
                        unlink($this->config->item('SAVE_FOLDER_PROFILE') . $old_image);
                    }
                    $new_image = $this->upload->data('file_name');
                    $this->db->set('image', $new_image);
                } else {
                    return "default.png";
                }
            }

            $sendsave = [
                'nama_lengkap' => htmlspecialchars($this->input->post('nama_pegawai_edit')),
                'username' => htmlspecialchars($this->input->post('username_pegawai_edit')),
                'jabatan' => htmlspecialchars($this->input->post('jabatan_pegawai_edit')),
                'instansi' => $this->appsetting['nama_instansi'],
                'npwp' => $rownpwp,
                'umur' => htmlspecialchars($this->input->post('umur_pegawai_edit')),
                'tempat_lahir' => htmlspecialchars($this->input->post('tempat_lahir_pegawai_edit')),
                'tgl_lahir' => htmlspecialchars($this->input->post('tgl_lahir_pegawai_edit')),
                'jenis_kelamin' => htmlspecialchars($this->input->post('jenis_kelamin_pegawai_edit')),
                'bagian_shift' => htmlspecialchars($this->input->post('shift_pegawai_edit')),
                'is_active' => htmlspecialchars($this->input->post('verifikasi_pegawai_edit')),
                'role_id' => htmlspecialchars($this->input->post('role_pegawai_edit')),
            ];
            $this->db->set($sendsave);
            $this->db->where('id_pegawai', htmlspecialchars($this->input->post('id_pegawai_edit', true)));
            $this->db->update('user');
        }
    }
}
