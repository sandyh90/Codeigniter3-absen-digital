<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center mt-4">
                <img src="<?= $logo_source = (empty($dataapp['logo_instansi'])) ? base_url('assets/img/clock-image.png') : (($dataapp['logo_instansi'] == 'default-logo.png') ? base_url('assets/img/clock-image.png') : base_url('storage/setting/' . $dataapp['logo_instansi'])); ?>" class="card-img" style="width:15%;">
                <h1 class="font-weight-light">Access Denied</h1>
                <p class="lead">Anda tidak memiliki izin untuk mengakses konten ini.</p>
                <a href="<?= base_url(); ?>"><i class="fas fa-arrow-left mr-1"></i>Return to Dashboard</a>
            </div>
        </div>
    </div>
</div>