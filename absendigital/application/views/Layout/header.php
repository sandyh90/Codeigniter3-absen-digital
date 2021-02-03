<?php if ($this->session->userdata('logged_in') == true) : ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('assets'); ?>/assets/img/apple-icon.png">
        <link rel="icon" type="image/png" href="<?= (empty($dataapp['logo_instansi'])) ? base_url('assets/img/clock-image.png') : (($dataapp['logo_instansi'] == 'default-logo.png') ? base_url('assets/img/clock-image.png') : base_url('storage/setting/' . $dataapp['logo_instansi'])); ?>">
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?= $title; ?></title>
        <link href="<?= base_url('assets'); ?>/css/styles.css" rel="stylesheet" />
        <link href="<?= base_url('assets'); ?>/css/bootstrap.css" rel="stylesheet" />
        <link href="<?= base_url('assets'); ?>/vendor/sweetalert2/sweetalert2.min.css" rel="stylesheet" />
        <link href="<?= base_url('assets'); ?>/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" />
        <link href="<?= base_url('assets'); ?>/vendor/jonthornton-jquery-timepicker/jquery.timepicker.min.css" rel="stylesheet" />
        <link href="<?= base_url('assets'); ?>/vendor/bootstrap-toggle-master/css/bootstrap4-toggle.min.css" rel="stylesheet" />
        <link href="<?= base_url('assets'); ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" />
        <link href="<?= base_url('assets'); ?>/vendor/leaflet/leaflet.css" rel="stylesheet" />
        <link href="<?= base_url('assets'); ?>/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
        <script src="<?= base_url('assets'); ?>/vendor/leaflet/leaflet.js"></script>

    </head>

    <body class="sb-nav-fixed">
    <?php else : ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="utf-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
            <meta name="description" content="" />
            <link rel="icon" type="image/png" href="<?= (empty($dataapp['logo_instansi'])) ? base_url('assets/img/clock-image.png') : (($dataapp['logo_instansi'] == 'default-logo.png') ? base_url('assets/img/clock-image.png') : base_url('storage/setting/' . $dataapp['logo_instansi'])); ?>">
            <meta name="author" content="" />
            <title><?= $title; ?></title>
            <link href="<?= base_url('assets'); ?>/css/styles.css" rel="stylesheet" />
            <link href="<?= base_url('assets'); ?>/css/bootstrap.css" rel="stylesheet" />
            <link href="<?= base_url('assets'); ?>/vendor/sweetalert2/sweetalert2.min.css" rel="stylesheet" />
            <link href="<?= base_url('assets'); ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" />
        </head>

        <body class="bg-auth">
            <div id="layoutAuthentication">
                <div id="layoutAuthentication_content">
                    <div class="overlay-auth">
                    </div>
                    <main>
                    <?php endif; ?>