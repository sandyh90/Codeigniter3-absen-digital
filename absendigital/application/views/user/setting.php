<div class="container-fluid">
    <h1 class="my-4"><span class="fas fa-cog mr-2"></span>Setelan</h1>
    <div class="row mb-4">
        <div class="col-xl-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="setting-list" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#setelan" role="tab"><span class="fas fa-user-cog mr-1"></span>Setelan Dasar</h1></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#gantipass" role="tab"><span class="fas fa-lock mr-1"></span>Ganti Password</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#loggeddvc" role="tab"><span class="fas fa-key mr-1"></span>Remember Me</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content mt-3">
                        <div class="tab-pane active" id="setelan" role="tabpanel">
                            <?= form_open_multipart('#', ['id' => 'settinguser']) ?>
                            <div class="form-group row">
                                <label for="nama_lengkap" class="col-sm-4 col-form-label">Nama Lengkap</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= $user['nama_lengkap']; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="username_pegawai" class="col-sm-4 col-form-label">Username</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="username_pegawai" name="username_pegawai" value="<?= $user['username']; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="jabatan_pegawai" class="col-sm-4 col-form-label">Jabatan</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="jabatan_pegawai" name="jabatan_pegawai" value="<?= $user['jabatan']; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="instansi_pegawai" class="col-sm-4 col-form-label">Instansi</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="instansi_pegawai" name="instansi_pegawai" value="<?= $user['instansi']; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="umur_pegawai" class="col-sm-4 col-form-label">Umur</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="umur_pegawai" name="umur_pegawai" value="<?= $user['umur']; ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text">Tahun</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="npwp_pegawai" class="col-sm-4 col-form-label">NPWP</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="npwp_pegawai" name="npwp_pegawai" value="<?= $user['npwp']; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-2">Pas Foto</div>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <img src="<?= (empty($user['image'])) ? base_url('assets/img/default-profile.png') : (($user['image'] == 'default.png') ? base_url('assets/img/default-profile.png') : base_url('storage/profile/' . $user['image'])); ?>" class="img-thumbnail">
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="pas_foto" name="pas_foto">
                                                <label class="custom-file-label" for="pas_foto">Choose file. Max 2 MB</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row justify-content-end">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary" id="usrsetting-btn"><span class="fas fa-pen mr-1"></span>Edit</button>
                                </div>
                            </div>
                            </form>
                        </div>

                        <div class="tab-pane" id="gantipass" role="tabpanel">
                            <div id="infopass"></div>
                            <?= form_open_multipart('#', ['id' => 'chgpassuser']) ?>
                            <div class="form-group row">
                                <label for="pass_lama" class="col-sm-4 col-form-label">Password Lama</label>
                                <div class="col-sm-8">
                                    <div class="input-group" id="show_hide_password">
                                        <input class="form-control py-4" name="pass_lama" id="pass_lama" type="password" placeholder="Masukan Password Lama" />
                                        <div class="input-group-append">
                                            <button class="input-group-text" type="button" tabindex="-1"><span class="fas fa-eye-slash" aria-hidden="false"></span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" id="show_hide_password">
                                <label for="pass_baru" class="col-sm-4 col-form-label">Password Baru</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="pass_baru" name="pass_baru" placeholder="Masukan Password Baru">
                                </div>
                            </div>
                            <div class="form-group row" id="show_hide_password">
                                <label for="pass_baru_confirm" class="col-sm-4 col-form-label">Konfirmasi Password Baru</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="pass_baru_confirm" name="pass_baru_confirm" placeholder="Konfirmasi Password Baru">
                                </div>
                            </div>
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary" id="chgpass-btn"><span class="fas fa-key mr-1"></span>Ubah Password</button>
                            </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="loggeddvc" role="tabpanel">
                            <div class="card text-white bg-dark mb-3">
                                <div class="card-header">Perangkat Ini</div>
                                <div class="card-body">
                                    <span class="<?= $icon_agent = ($this->agent->is_browser() ? 'fas fa-fw fa-laptop' : ($this->agent->is_mobile() ? 'fas fa-fw fa-mobile-alt' : 'fas fa-fw fa-desktop')) ?> fa-2x mr-1 float-left"></span>
                                    <h5 class="card-title"><?= $type_agent = ($this->agent->is_browser() ? $this->agent->browser() . ' ' . $this->agent->version() : ($this->agent->is_mobile() ? $this->agent->mobile() : 'Unknown')) ?></h5>
                                    <br>
                                    <p class="card-text"><?= $this->agent->agent_string(); ?></p>
                                    <button class="btn btn-primary logout">Logout</button>
                                </div>
                            </div>
                            <h5 class="my-4"><span class="fas fa-fw fa-user-lock mr-1"></span>Remember Me Session</h5>
                            <div class="card text-white bg-dark mb-3">
                                <div class="card-header">
                                    <div class="float-left">Daftar Remember Me</div>
                                    <div class="float-right"><button class="btn btn-danger" id="clear_rememberme">Clear Remember Me</button></div>
                                </div>
                                <div class="card-body">
                                    <div id="remembersesslist">
                                        <!--This section for list all remember me-->
                                        <?php foreach ($listremember as $rmblist) : ?>
                                            <div class="card text-white shadow bg-dark mb-3">
                                                <div class="card-body">
                                                    <span class="fas fa-fw fa-desktop fa-2x mr-1 float-left"></span>
                                                    <h5 class="card-title"><?= $rmblist->user_agent; ?></h5>
                                                    <br>
                                                    <p class="card-text"><?= $rmblist->agent_string; ?></p>
                                                    <p class="card-text"><small class="text-muted">Created on <?= date('d F Y', $rmblist->date_created); ?></small></p>
                                                    <button type="button" class="btn btn-primary sess_rememberme" data-sess-id="<?= $rmblist->id_session; ?>">Hapus</button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>