<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajax extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $bulan = array(1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        $hari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
        $this->get_today_date = $hari[(int)date("w")] . ', ' . date("j ") . $bulan[(int)date('m')] . date(" Y");
        $this->load->model('M_Auth');
        $this->load->model('M_Front');
        $this->load->model('M_Settings');
        $this->load->model('M_User');
        $this->load->model('M_Admin');
        $this->get_datasess = $this->db->get_where('user', ['username' =>
        $this->session->userdata('username')])->row_array();
        $this->appsetting = $this->db->get_where('db_setting', ['status_setting' => 1])->row_array();
        $timezone_all = $this->appsetting;
        date_default_timezone_set($timezone_all['timezone']);
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            exit('Opss you cannot access this [Hacking Attemp].');
        }
    }

    public function clear_rememberme()
    {
        $rmbtype = $this->input->get('rmbtype');
        $reponse = [
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash(),
        ];
        $this->M_User->clearremember($rmbtype);
        echo json_encode($reponse);
    }

    //Fitur Ajax Scan Absensi
    public function scandata()
    {
        $querydb = $this->db->get_where('user', ['id_pegawai' => htmlspecialchars($this->input->post('id_pgw'))])->row_array();
        $reponse = [
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        ];
        if ($this->db->get_where('user', ['id_pegawai' => htmlspecialchars($this->input->post('id_pgw'))])->row_array()) {
            $reponse = [
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
                'name_pgw' => $querydb['nama_lengkap'],
                'success' => true
            ];
        } elseif (empty($this->db->get_where('user', ['id_pegawai' => htmlspecialchars($this->input->post('id_pgw'))])->row_array())) {
            $reponse = [
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
                'success' => false,
                'msgscan' => '<div class="alert alert-danger text-center" role="alert">Data Pegawai Tidak Ada</div>'
            ];
        }
        echo json_encode($reponse);
    }

    //Fitur Ajax Tombol Absensi

    public function absenajax()
    {
        $clocknow = date("H:i:s");
        $today = $this->get_today_date;
        $appsettings = $this->appsetting;
        if (strtotime($clocknow) <= strtotime($appsettings['absen_mulai'])) {
            $reponse = [
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
                'success' => false,
                'msgabsen' => '<div class="alert alert-danger text-center" role="alert">Belum Waktunya Absen Datang</div>'
            ];
        } elseif (strtotime($clocknow) >= strtotime($appsettings['absen_mulai_to']) && strtotime($clocknow) <= strtotime($appsettings['absen_pulang']) && $this->db->get_where('db_absensi', ['tgl_absen' => $today, 'nama_pegawai' => htmlspecialchars($this->input->post('nama_pegawai', true))])->row_array()) {
            $reponse = [
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
                'success' => false,
                'msgabsen' => '<div class="alert alert-danger text-center" role="alert">Belum Waktunya Absen Pulang</div>'
            ];
        } else {
            $this->M_Front->do_absen();
            $reponse = [
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
                'success' => true
            ];
        }
        echo json_encode($reponse);
    }

    //Fitur Ajax Setelan User

    public function usersetting()
    {
        $data = [
            'user' => $this->get_datasess
        ];
        $usrsetting = $this->input->get('type');
        if ($usrsetting == 'chgpwd') {
            $reponse = [
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
                'success' => False,
                'messages' => []
            ];
            $validation = [
                [
                    'field' => 'pass_lama',
                    'label' => 'Password Lama',
                    'rules' => 'trim|required|xss_clean|min_length[8]',
                    'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.', 'min_length' => 'Password terlalu pendek, Minimal 8 Karakter!']
                ],
                [
                    'field' => 'pass_baru',
                    'label' => 'Password Baru',
                    'rules' => 'required|xss_clean|min_length[8]|matches[pass_baru_confirm]',
                    'errors' => [
                        'required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.', 'matches' => 'Password tidak sama!',
                        'min_length' => 'Password terlalu pendek!'
                    ]
                ],
                [
                    'field' => 'pass_baru_confirm',
                    'label' => 'Konfirmasi Password Baru',
                    'rules' => 'trim|required|xss_clean|min_length[8]|matches[pass_baru]',
                    'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
                ]
            ];
            $this->form_validation->set_rules($validation);
            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
            if ($this->form_validation->run() == FALSE) {
                foreach ($_POST as $key => $value) {
                    $reponse['messages'][$key] = form_error($key);
                }
            } else {
                $current_password = $this->input->post('pass_lama');
                $new_password = $this->input->post('pass_baru');
                if (!password_verify($current_password, $data['user']['password'])) {
                    $reponse = [
                        'csrfName' => $this->security->get_csrf_token_name(),
                        'csrfHash' => $this->security->get_csrf_hash(),
                        'success' => False,
                        'infopass' => '<div class="alert alert-danger text-center" role="alert">Password lama salah</div>'
                    ];
                } else {
                    if ($current_password == $new_password) {
                        $reponse = [
                            'csrfName' => $this->security->get_csrf_token_name(),
                            'csrfHash' => $this->security->get_csrf_hash(),
                            'success' => False,
                            'infopass' => '<div class="alert alert-danger text-center" role="alert">Password baru tidak boleh sama dengan password lama</div>'
                        ];
                    } else {
                        // password sudah benar
                        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                        $this->db->set('password', $password_hash); //set password yang sudah di hash ke database
                        $this->db->where('username', $this->session->userdata('username')); // mengambil data dari session
                        $this->db->update('user');
                        $reponse = [
                            'csrfName' => $this->security->get_csrf_token_name(),
                            'csrfHash' => $this->security->get_csrf_hash(),
                            'success' => true
                        ];
                    }
                }
            }
        } elseif ($usrsetting == 'basic') {
            $reponse = [
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
                'success' => False,
                'messages' => []
            ];
            $validation = [
                [
                    'field' => 'umur_pegawai',
                    'label' => 'Umur Pegawai',
                    'rules' => 'required|xss_clean|max_length[2]|numeric',
                    'errors' => [
                        'required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.', 'numeric' => 'Karakter harus angka tidak boleh huruf.', 'max_length' => 'Angka umur terlalu panjang, Max Karakter 2!'
                    ]
                ],
                [
                    'field' => 'npwp_pegawai',
                    'label' => 'NPWP Pegawai',
                    'rules' => 'trim|xss_clean',
                    'errors' => ['xss_clean' => 'Please check your form on %s.']
                ]
            ];
            $this->form_validation->set_rules($validation);
            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
            if ($this->form_validation->run() == FALSE) {
                foreach ($_POST as $key => $value) {
                    $reponse['messages'][$key] = form_error($key);
                }
            } else {
                $this->M_User->user_setting($usrsetting);
                $reponse = [
                    'csrfName' => $this->security->get_csrf_token_name(),
                    'csrfHash' => $this->security->get_csrf_hash(),
                    'success' => true
                ];
            }
        }
        echo json_encode($reponse);
    }

    //Fitur Ajax Logout
    public function logoutajax()
    {
        $reponse = [
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash(),
        ];
        $this->M_Auth->do_logout();
        echo json_encode($reponse);
    }

    //Fitur CRUD Absensi
    public function dataabs()
    {
        $typesend = $this->input->get('type');
        $reponse = [
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        ];
        if ($typesend == 'delabs') {
            $this->M_Front->crudabs($typesend);
        } elseif ($typesend == 'viewabs') {
            $data = [
                'dataabsensi' => $this->db->get_where('db_absensi', ['id_absen' => $this->input->post('absen_id')])->row_array(),
                'dataapp' => $this->appsetting
            ];
            $html = $this->load->view('layout/dataabsensi/viewabsensi', $data);
            $reponse = [
                'html' => $html,
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
                'success' => true
            ];
        } elseif ($typesend == 'delallabs') {
            $this->M_Front->crudabs($typesend);
        }
        echo json_encode($reponse);
    }

    //Fitur CRUD Data Pegawai

    public function datapgw()
    {
        $typesend = $this->input->get('type');
        $reponse = [
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        ];
        if ($typesend == 'addpgw') {
            $reponse = [
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
                'success' => False,
                'messages' => []
            ];
            $validation = [
                [
                    'field' => 'nama_pegawai',
                    'label' => 'Nama Pegawai',
                    'rules' => 'trim|required|xss_clean',
                    'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
                ],
                [
                    'field' => 'username_pegawai',
                    'label' => 'Username',
                    'rules' => 'trim|required|xss_clean|is_unique[user.username]',
                    'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.', 'is_unique' => 'Username ini telah terdaftar didatabase!']
                ],
                [
                    'field' => 'password_pegawai',
                    'label' => 'Password',
                    'rules' => 'trim|required|xss_clean|min_length[8]',
                    'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.', 'max_length' => 'Password terlalu pendek, Minimal 8 Karakter!']
                ],
                [
                    'field' => 'kode_pegawai',
                    'label' => 'Kode Pegawai',
                    'rules' => 'trim|required|xss_clean|is_unique[user.kode_pegawai]',
                    'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.', 'is_unique' => 'Kode Pegawai ini telah terdaftar didatabase!']
                ],
                [
                    'field' => 'jabatan_pegawai',
                    'label' => 'Jabatan',
                    'rules' => 'trim|required|xss_clean',
                    'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
                ],
                [
                    'field' => 'instansi_pegawai',
                    'label' => 'Nama Instansi',
                    'rules' => 'required|xss_clean',
                    'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
                ],
                [
                    'field' => 'npwp_pegawai',
                    'label' => 'NPWP',
                    'rules' => 'trim|xss_clean',
                    'errors' => ['xss_clean' => 'Please check your form on %s.']
                ],
                [
                    'field' => 'umur_pegawai',
                    'label' => 'Umur Pegawai',
                    'rules' => 'required|xss_clean|max_length[2]|numeric',
                    'errors' => [
                        'required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.', 'numeric' => 'Karakter harus angka tidak boleh huruf.', 'max_length' => 'Angka umur terlalu panjang, Max Karakter 2!'
                    ]
                ],
                [
                    'field' => 'tempat_lahir_pegawai',
                    'label' => 'Tempat Lahir',
                    'rules' => 'required|xss_clean',
                    'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
                ],
                [
                    'field' => 'tgl_lahir_pegawai',
                    'label' => 'Tanggal Lahir',
                    'rules' => 'required|xss_clean',
                    'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
                ],
                [
                    'field' => 'jenis_kelamin_pegawai',
                    'label' => 'Jenis Kelamin',
                    'rules' => 'required|xss_clean|in_list[Laki - Laki,Perempuan]',
                    'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
                ],
                [
                    'field' => 'shift_pegawai',
                    'label' => 'Bagian Shift',
                    'rules' => 'required|xss_clean|in_list[1,2,3]',
                    'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
                ],
                [
                    'field' => 'verifikasi_pegawai',
                    'label' => 'Verifikasi Pegawai',
                    'rules' => 'required|xss_clean|in_list[0,1]',
                    'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
                ],
            ];
            $this->form_validation->set_rules($validation);
            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
            if ($this->form_validation->run() == FALSE) {
                foreach ($_POST as $key => $value) {
                    $reponse['messages'][$key] = form_error($key);
                }
            } else {
                $this->M_Admin->crudpgw($typesend);
                $reponse = [
                    'csrfName' => $this->security->get_csrf_token_name(),
                    'csrfHash' => $this->security->get_csrf_hash(),
                    'success' => true
                ];
            }
        } elseif ($typesend == 'delpgw') {
            $this->M_Admin->crudpgw($typesend);
        } elseif ($typesend == 'actpgw') {
            if ($this->db->get_where('user', ['id_pegawai' => $this->input->post('pgw_id'), 'is_active' => 1])->row_array()) {
                $reponse = [
                    'csrfName' => $this->security->get_csrf_token_name(),
                    'csrfHash' => $this->security->get_csrf_hash(),
                    'success' => false
                ];
            } else {
                $reponse = [
                    'csrfName' => $this->security->get_csrf_token_name(),
                    'csrfHash' => $this->security->get_csrf_hash(),
                    'success' => true
                ];
                $this->M_Admin->crudpgw($typesend);
            }
        } elseif ($typesend == 'viewpgw') {
            $data['datapegawai'] =  $this->db->get_where('user', ['id_pegawai' => $this->input->post('pgw_id')])->row_array();
            $html = $this->load->view('layout/datapegawai/viewpegawai', $data);
            $reponse = [
                'html' => $html,
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
                'success' => true
            ];
        } elseif ($typesend == 'edtpgw') {
            $data['dataapp'] = $this->appsetting;
            $data['datapegawai'] =  $this->db->get_where('user', ['id_pegawai' => $this->input->post('pgw_id')])->row_array();
            $html = $this->load->view('layout/datapegawai/editpegawai', $data);
            $reponse = [
                'html' => $html,
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash()
            ];
        }
        echo json_encode($reponse);
    }

    public function editpgwbc()
    {
        $typesend = $this->input->get('type');
        $reponse = [
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash(),
            'success' => False,
            'messages' => []
        ];

        $oldusername = $this->db->get_where('user', ['id_pegawai' => htmlspecialchars($this->input->post('id_pegawai_edit', true))])->row_array();

        if ($oldusername['username'] == htmlspecialchars($this->input->post('username_pegawai_edit'))) {
            $rule_username = 'trim|required|xss_clean';
        } else {
            $rule_username = 'trim|required|xss_clean|is_unique[user.username]';
        }

        $validation = [
            [
                'field' => 'nama_pegawai_edit',
                'label' => 'Nama Pegawai',
                'rules' => 'trim|required|xss_clean',
                'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
            ],
            [
                'field' => 'username_pegawai_edit',
                'label' => 'Username',
                'rules' => $rule_username,
                'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.', 'is_unique' => 'Username ini telah terdaftar didatabase!']
            ],
            [
                'field' => 'password_pegawai_edit',
                'label' => 'Password',
                'rules' => 'trim|xss_clean|min_length[8]',
                'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.', 'max_length' => 'Password terlalu pendek, Minimal 8 Karakter!']
            ],
            [
                'field' => 'kode_pegawai_edit',
                'label' => 'Kode Pegawai',
                'rules' => 'trim|required|xss_clean',
                'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
            ],
            [
                'field' => 'jabatan_pegawai_edit',
                'label' => 'Jabatan',
                'rules' => 'trim|required|xss_clean',
                'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
            ],
            [
                'field' => 'instansi_pegawai_edit',
                'label' => 'Nama Instansi',
                'rules' => 'required|xss_clean',
                'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
            ],
            [
                'field' => 'npwp_pegawai_edit',
                'label' => 'NPWP',
                'rules' => 'trim|xss_clean',
                'errors' => ['xss_clean' => 'Please check your form on %s.']
            ],
            [
                'field' => 'umur_pegawai_edit',
                'label' => 'Umur Pegawai',
                'rules' => 'required|xss_clean|max_length[2]|numeric',
                'errors' => [
                    'required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.', 'numeric' => 'Karakter harus angka tidak boleh huruf.', 'max_length' => 'Angka umur terlalu panjang, Max Karakter 2!'
                ]
            ],
            [
                'field' => 'tempat_lahir_pegawai_edit',
                'label' => 'Tempat Lahir',
                'rules' => 'required|xss_clean',
                'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
            ],
            [
                'field' => 'tgl_lahir_pegawai_edit',
                'label' => 'Tanggal Lahir',
                'rules' => 'required|xss_clean',
                'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
            ],
            [
                'field' => 'jenis_kelamin_pegawai_edit',
                'label' => 'Jenis Kelamin',
                'rules' => 'required|xss_clean|in_list[Laki - Laki,Perempuan]',
                'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
            ],
            [
                'field' => 'shift_pegawai_edit',
                'label' => 'Bagian Shift',
                'rules' => 'required|xss_clean|in_list[1,2,3]',
                'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
            ],
            [
                'field' => 'verifikasi_pegawai_edit',
                'label' => 'Verifikasi Pegawai',
                'rules' => 'required|xss_clean|in_list[0,1]',
                'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
            ],
        ];
        $this->form_validation->set_rules($validation);
        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
        if ($this->form_validation->run() == FALSE) {
            foreach ($_POST as $key => $value) {
                $reponse['messages'][$key] = form_error($key);
            }
        } else {
            $this->M_Admin->crudpgw($typesend);
            $reponse = [
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
                'success' => true
            ];
        }
        echo json_encode($reponse);
    }

    //Fitur Ajax Tabel Absensi

    function get_datatbl()
    { //data absen by JSON object
        $dataabsen = $this->input->get('type');
        $datapegawai = $this->get_datasess;
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $bulan = array(1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        $hari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
        $nowday = $hari[(int)date("w")] . ', ' . date("j ") . $bulan[(int)date('m')] . date(" Y");
        $data = [];
        $no = 1;
        if ($dataabsen == 'datapgw') {
            $query = $this->db->get("user");
            foreach ($query->result() as $r) {
                $data[] = [
                    $no++,
                    $r->nama_lengkap,
                    $r->kode_pegawai,
                    $pasfoto = '<img class="img-thumbnail" src="' . $img_source = ($r->image == 'default.png' ? base_url('assets/img/default-profile.png') : base_url('storage/profile/' . $r->image)) . '" class="card-img" style="width: 100%;">',
                    $r->username,
                    $r->npwp,
                    $r->jenis_kelamin,
                    ($r->role_id == 1) ? '<span class="badge badge-danger ml-1">Administrator</span>' : (($r->role_id == 2) ? '<span class="badge badge-primary ml-1">Moderator</span>' : (($r->role_id == 3) ? '<span class="badge badge-success ml-1">Pegawai</span>' : '<span class="badge badge-secondary ml-1">Tidak Ada Role</span>')),
                    ($r->bagian_shift == 1) ? '<span class="badge badge-success ml-1">Full Time</span>' : (($r->bagian_shift == 2) ? '<span class="badge badge-warning">Part Time</span>' : '<span class="badge badge-primary">Shift Time</span>'),
                    ($r->is_online == 1) ? '<span class="badge badge-success ml-1">Online</span>' : '<span class="badge badge-danger ml-1">Offline</span>',
                    ($r->is_active == 1) ? '<span class="badge badge-success ml-1">Terverifikasi</span>' : '<span class="badge badge-danger ml-1">Belum Terverifikasi</span>',
                    '<div class="btn-group btn-small " style="text-align: right;">
                        <button id="detailpegawai" class="btn btn-primary view-pegawai" data-pegawai-id="' . $r->id_pegawai . '" title="Lihat Pegawai"><span class="fas fa-fw fa-address-card"></span></button>
                        <button class="btn btn-danger delete-pegawai" title="Hapus Pegawai" data-pegawai-id="' . $r->id_pegawai . '"><span class="fas fa-trash"></span></button>
                        <button class="btn btn-warning edit-pegawai" title="Edit Pegawai" data-pegawai-id="' . $r->id_pegawai . '"><span class="fas fa-user-edit"></span></button>
                        <button class="btn btn-secondary activate-pegawai" title="Verifikasi Pegawai" data-pegawai-id="' . $r->id_pegawai . '"><span class="fas fa-user-check"></span></button>
                    </div>'
                ];
            }
            $result = array(
                "draw" => $draw,
                "recordsTotal" => $query->num_rows(),
                "recordsFiltered" => $query->num_rows(),
                "data" => $data
            );
        } elseif ($dataabsen == 'all') {
            if ($this->session->userdata('role_id') == 1) {
                $query = $this->db->get("db_absensi");
                foreach ($query->result() as $r) {
                    $data[] = [
                        $no++,
                        $r->tgl_absen,
                        $r->nama_pegawai,
                        $r->jam_masuk,
                        $r->jam_pulang,
                        (empty($r->status_pegawai)) ? '<span class="badge badge-primary">Belum Absen</span>' : (($r->status_pegawai == 1) ? '<span class="badge badge-success">Sudah Absen</span>' : '<span class="badge badge-danger">Absen Terlambat</span>'),
                        '<div class="btn-group btn-small " style="text-align: right;">
                    <button class="btn btn-primary detail-absen" data-absen-id="' . $r->id_absen . '" title="Lihat Absensi"><span class="fas fa-fw fa-address-card"></span></button>
                    <button class="btn btn-danger delete-absen" title="Hapus Absensi" data-absen-id="' . $r->id_absen . '"><span class="fas fa-trash"></span></button>
                    <button class="btn btn-warning print-absen" title="Cetak Absensi" data-absen-id="' . $r->id_absen . '" data-toggle="modal" data-target="#printabsensimodal"><span class="fas fa-print"></span></button>
                    </div>'
                    ];
                }
            } elseif ($this->session->userdata('role_id') == 2) {
                $query = $this->db->get("db_absensi");
                foreach ($query->result() as $r) {
                    $data[] = [
                        $no++,
                        $r->tgl_absen,
                        $r->nama_pegawai,
                        $r->jam_masuk,
                        $r->jam_pulang,
                        (empty($r->status_pegawai)) ? '<span class="badge badge-primary">Belum Absen</span>' : (($r->status_pegawai == 1) ? '<span class="badge badge-success">Sudah Absen</span>' : '<span class="badge badge-danger">Absen Terlambat</span>'),
                        '<div class="btn-group btn-small " style="text-align: right;">
                    <button class="btn btn-primary detail-absen" data-absen-id="' . $r->id_absen . '" title="Lihat Absensi"><span class="fas fa-fw fa-address-card"></span></button>
                    <button class="btn btn-warning print-absen" title="Cetak Absensi" data-absen-id="' . $r->id_absen . '" data-toggle="modal" data-target="#printabsensimodal"><span class="fas fa-print"></span></button>
                    </div>'
                    ];
                }
            }
        } elseif ($dataabsen == 'allself') {
            $query = $this->db->get_where("db_absensi", ['nama_pegawai' => $datapegawai['nama_lengkap']]);
            foreach ($query->result() as $r) {
                $data[] = [
                    $no++,
                    $r->tgl_absen,
                    $r->nama_pegawai,
                    $r->jam_masuk,
                    $r->jam_pulang,
                    (empty($r->status_pegawai)) ? '<span class="badge badge-primary">Belum Absen</span>' : (($r->status_pegawai == 1) ? '<span class="badge badge-success">Sudah Absen</span>' : '<span class="badge badge-danger">Absen Terlambat</span>')
                ];
            }
        } elseif ($dataabsen == 'getallmsk') {
            $query = $this->db->get_where("db_absensi", ['tgl_absen' => $nowday, 'status_pegawai' => 1]);
            foreach ($query->result() as $r) {
                $data[] = [
                    $no++,
                    $r->jam_masuk,
                    $r->nama_pegawai,
                    (empty($r->status_pegawai)) ? '<span class="badge badge-primary">Belum Absen</span>' : (($r->status_pegawai == 1) ? '<span class="badge badge-success">Sudah Absen</span>' : '<span class="badge badge-danger">Absen Terlambat</span>')
                ];
            }
        } elseif ($dataabsen == 'getalltrl') {
            $query = $this->db->get_where("db_absensi", ['tgl_absen' => $nowday, 'status_pegawai' => 2]);
            foreach ($query->result() as $r) {
                $data[] = [
                    $no++,
                    $r->jam_masuk,
                    $r->nama_pegawai,
                    (empty($r->status_pegawai)) ? '<span class="badge badge-primary">Belum Absen</span>' : (($r->status_pegawai == 1) ? '<span class="badge badge-success">Sudah Absen</span>' : '<span class="badge badge-danger">Absen Terlambat</span>')
                ];
            }
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $query->num_rows(),
            "recordsFiltered" => $query->num_rows(),
            "data" => $data
        );
        echo json_encode($result);
    }

    // Fitur AJAX Settings Aplikasi

    public function initsettingapp()
    {
        $typeinit = $this->input->get('type');
        $reponse = [
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash(),
        ];
        $this->M_Settings->init_setting($typeinit);
        echo json_encode($reponse);
    }

    public function savingsettingapp()
    {
        $reponse = [
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash(),
            'success' => False,
            'messages' => []
        ];
        $validation = [
            [
                'field' => 'nama_instansi',
                'label' => 'Nama Instansi',
                'rules' => 'trim|required|xss_clean',
                'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
            ],
            [
                'field' => 'pesan_jumbotron',
                'label' => 'Pesan Jumbotron',
                'rules' => 'trim|required|xss_clean',
                'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
            ],
            [
                'field' => 'nama_app_absen',
                'label' => 'Nama Aplikasi Absen',
                'rules' => 'trim|required|xss_clean|max_length[20]',
                'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.', 'max_length' => 'Nama aplikasi terlalu panjang, Max Karakter 20!']
            ],
            [
                'field' => 'timezone_absen',
                'label' => 'Zona Waktu Absen',
                'rules' => 'trim|required|xss_clean',
                'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
            ],
            [
                'field' => 'absen_mulai',
                'label' => 'Absen Mulai',
                'rules' => 'trim|required|xss_clean',
                'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
            ],
            [
                'field' => 'absen_sampai',
                'label' => 'Batas Absen Masuk',
                'rules' => 'required|xss_clean',
                'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
            ],
            [
                'field' => 'absen_pulang_sampai',
                'label' => 'Absen Pulang',
                'rules' => 'trim|required|xss_clean',
                'errors' => ['required' => 'You must provide a %s.', 'xss_clean' => 'Please check your form on %s.']
            ]
        ];
        $this->form_validation->set_rules($validation);
        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
        if ($this->form_validation->run() == FALSE) {
            foreach ($_POST as $key => $value) {
                $reponse['messages'][$key] = form_error($key);
            }
        } else {
            $this->M_Settings->update_setting();
            $reponse = [
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
                'success' => true
            ];
        }
        echo json_encode($reponse);
    }
}
