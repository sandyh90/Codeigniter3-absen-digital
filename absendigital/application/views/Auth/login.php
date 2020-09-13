<div class="container auth-card">
    <div class="row justify-content-center">
        <div class="col-lg-6 align-self-center">
            <div class="text-center my-2">
                <img src="<?= $logo_source = (empty($dataapp['logo_instansi'])) ? base_url('assets/img/clock-image.png') : (($dataapp['logo_instansi'] == 'default-logo.png') ? base_url('assets/img/clock-image.png') : base_url('storage/setting/' . $dataapp['logo_instansi'])); ?>" class="card-img" style="width:50%;">
                <h1 class="text-white"><?= $appname = (empty($dataapp['nama_app_absensi'])) ? 'Absensi Online' : $dataapp['nama_app_absensi']; ?></h1>
                <h3 class="text-white" id="dateclocknow"></h3>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-lg border-0 rounded-lg p-2">
                <div class="card-header">
                    <h3 class="text-center font-weight-light">Login</h3>
                </div>
                <div class="card-body">
                    <?= $this->session->flashdata('authmsg'); ?>
                    <?= form_open('login'); ?>
                    <div class="form-group row">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><span class="fas fa-user"></span></div>
                            </div>
                            <input class="form-control py-4" name="username" id="username" type="text" placeholder="Enter username" value="<?= set_value('username') ?>" />
                        </div>
                    </div>
                    <?= form_error('username', '<small class="text-danger pl-3">', '</small>'); ?>
                    <div class="form-group row">
                        <div class="input-group" id="show_hide_password">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><span class="fas fa-key"></span></div>
                            </div>
                            <input class="form-control py-4" name="password" id="password" type="password" placeholder="Enter password" />
                            <div class="input-group-append">
                                <button class="input-group-text" type="button" tabindex="-1"><span class="fas fa-eye-slash" aria-hidden="false"></span></button>
                            </div>
                        </div>
                    </div>
                    <?= form_error('password', '<small class="text-danger pl-3">', '</small>'); ?>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox"><input class="custom-control-input" id="rememberme" type="checkbox" name="rememberme" /><label class="custom-control-label" for="rememberme">Remember Me</label></div>
                    </div>
                    <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0"><button type="submit" class="btn btn-primary"><span class="fas fa-fw fa-sign-in-alt mr-2"></span>Login</button></div>
                    </form>
                    <hr>
                    <div class="small mb-3 text-muted">Anda bisa absen tanpa harus login dengan fitur scan menggunakan QR Code yang telah disediakan</div>
                    <a class="btn btn-success btn-block" href="<?= base_url('instantabsen'); ?>" target="_blank"><span class="fas fa-fw fa-bolt mr-2"></span>Instant Absen [Beta]</a></a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>