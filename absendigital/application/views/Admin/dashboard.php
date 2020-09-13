<div class="container-fluid">
    <div class="my-4 d-sm-flex align-items-center justify-content-between">
        <h1>Dashboard</h1>
        <div class="btn btn-primary" id="sync-data-dashboard"><span class="fas fa-sync-alt mr-1"></span>Refresh Data</div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4><span class="fas fa-user-tie mr-2"></span>Jumlah Pegawai</h4>
                    <h6 class="mt-3"><?= $jmlpegawai ?><div class="d-inline ml-1">Pegawai</div>
                    </h6>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('datapegawai'); ?>">Lihat Selengkapnya</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <h4><span class="fas fa-user-clock mr-2"></span>Terlambat</h4>
                    <h6 class="mt-3"><?= $pegawaitelat ?><div class="d-inline ml-1">Pegawai
                        </div>
                    </h6>
                </div>
                <div class="card-footer small">
                    <div class="text-white">Data Hari Ini</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h4><span class="fas fa-user-check mr-2"></span>Hadir</h4>
                    <h6 class="mt-3"><?= $pegawaimasuk ?><div class="d-inline ml-1">Pegawai</div>
                    </h6>
                </div>
                <div class="card-footer small">
                    <div class="text-white">Data Hari Ini</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-secondary text-white mb-4">
                <div class="card-body">
                    <h4><span class="fas fa-clock mr-2"></span>Hari Ini</h4>
                    <div id="date-and-clock mt-3">
                        <h5 id="clocknow"></h5>
                        <h5 id="datenow"></h5>
                    </div>
                    </h6>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url(''); ?>">Lihat Selengkapnya</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header"><span class="fas fa-user-clock mr-1"></span>Daftar Pegawai Terlambat [Hari Ini]
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered dashboard" id="list-absensi-terlambat" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jam Masuk</th>
                                    <th>Nama Pegawai</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Jam Masuk</th>
                                    <th>Nama Pegawai</th>
                                    <th>Status</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header"><span class="fas fa-user-check mr-1"></span>Daftar Pegawai Hadir [Hari Ini]
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered dashboard" id="list-absensi-masuk" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Waktu Datang</th>
                                    <th>Nama Pegawai</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Waktu Datang</th>
                                    <th>Nama Pegawai</th>
                                    <th>Status</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>