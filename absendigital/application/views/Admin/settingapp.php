<div class="container-fluid">
    <h1 class="my-4"><span class="fas fa-tools mr-2"></span>Setting Aplikasi</h1>
    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="float-right">
                        <?php if (empty($dataapp['status_setting'])) : ?>
                            <button id="initsettingapp" class="btn btn-primary"><span class="fas fa-wrench mr-1"></span>Initialize Setting App</button>
                        <?php elseif (!empty($dataapp['status_setting'])) : ?>
                            <button class="btn btn-success" disabled><span class="fas fa-wrench mr-1"></span>Telah Di Initialisasi</button>
                        <?php endif; ?>
                        <?php if (empty($dataapp['status_setting'])) : ?>
                            <button class="btn btn-danger" disabled><span class="fas fa-undo-alt mr-1"></span>Reset Setting App</button>
                        <?php elseif (!empty($dataapp['status_setting'])) : ?>
                            <button id="resetsettingapp" class="btn btn-danger"><span class="fas fa-undo-alt mr-1"></span>Reset Setting App</button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (empty($dataapp['status_setting'])) : ?>
                    <div class="card-body text-center">
                        <h3 class="mb-4"><span class="fas fa-fw fa-exclamation-triangle mr-1"></span>Fitur Setting Belum Ada</h3>
                        Silakan Klick Tombol <div class="d-inline font-weight-bold">[Initialize Setting App]</div> Untuk Menginstal Fitur Setting
                    </div>
                <?php else : ?>
                    <div class="card-body">
                        <?= form_open_multipart('#', ['id' => 'settingapp']) ?>
                        <div class="form-group row">
                            <label for="nama_instansi" class="col-sm-4 col-form-label">Nama Instansi</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nama_instansi" name="nama_instansi" value="<?= $nameinstansiset = (empty($dataapp['nama_instansi'])) ? '[Nama Instansi Belum Disetting]' : $dataapp['nama_instansi']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pesan_jumbotron" class="col-sm-4 col-form-label">Pesan Halaman Depan</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pesan_jumbotron" name="pesan_jumbotron" value="<?= $jumbotronset = (empty($dataapp['jumbotron_lead_set'])) ? '[Ubah Kalimat Pada Teks Ini Disetting Aplikasi]' : $dataapp['jumbotron_lead_set']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nama_app_absen" class="col-sm-4 col-form-label">Nama Aplikasi Absensi</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nama_app_absen" name="nama_app_absen" value="<?= $nameapp = (empty($dataapp['nama_app_absensi'])) ? 'Absensi Online' : $dataapp['nama_app_absensi']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="timezone_absen" class="col-sm-4 col-form-label">Zona Waktu Absensi</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="timezone_absen" name="timezone_absen" value="<?= $nameapp = (empty($dataapp['timezone'])) ? 'Asia/Jakarta' : $dataapp['timezone']; ?>">
                                    <div class="input-group-append">
                                        <a class="input-group-text" type="button" href="https://www.php.net/manual/en/timezones.php" target="_blank" data-toggle="tooltip" data-placement="top" title="Standar zona waktu untuk indonesia adalah Asia/Jakarta">Lihat Selengkapnya</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="absen_mulai" class="col-sm-4 col-form-label">Absen Dimulai Jam</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="absen_mulai" name="absen_mulai" value="<?= $nameapp = (empty($dataapp['absen_mulai'])) ? '06:00:00' : $dataapp['absen_mulai']; ?>">
                                    <div class="input-group-append">
                                        <button class="input-group-text" type="button" id="setTimebtn" tabindex="-1">Set Current Time</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="absen_sampai" class="col-sm-4 col-form-label">Batas Absen Masuk</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="absen_sampai" name="absen_sampai" value="<?= $nameapp = (empty($dataapp['absen_mulai_to'])) ? '11:00:00' : $dataapp['absen_mulai_to']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="absen_pulang_sampai" class="col-sm-4 col-form-label">Absen Pulang</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="absen_pulang_sampai" name="absen_pulang_sampai" value="<?= $nameapp = (empty($dataapp['absen_pulang'])) ? '16:00:00' : $dataapp['absen_pulang']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">Logo Instansi</div>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <img src="<?= (empty($dataapp['logo_instansi'])) ? base_url('assets/img/clock-image.png') : (($dataapp['logo_instansi'] == 'default-logo.png') ? base_url('assets/img/clock-image.png') : base_url('storage/setting/' . $dataapp['logo_instansi'])); ?>" class="img-thumbnail">
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="logo_instansi" name="logo_instansi">
                                            <label class="custom-file-label" for="logo_instansi">Choose file. Max 2 MB</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary" id="settingapp-btn"><span class="fas fa-pen mr-1"></span>Edit</button>
                            </div>
                        </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>