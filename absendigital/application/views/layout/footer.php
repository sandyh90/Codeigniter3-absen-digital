<?php if ($this->session->userdata('logged_in') == true) : ?>
    </main>
    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Copyright &copy; <?= date("Y"); ?><a href="<?= base_url(); ?>" class="ml-1"><?= $appname = (empty($dataapp['nama_app_absensi'])) ? 'Absensi Online' : $dataapp['nama_app_absensi']; ?></a>
                    <div class="d-inline">Powered By<a href="https://github.com/sandyh90" class="ml-1">Pickedianz</a></div>
                </div>
                <div class="text-muted">
                    Page rendered in <strong>{elapsed_time}</strong> seconds.
                </div>
            </div>
        </div>
    </footer>
    </div>
    </div>
    <script src="<?= base_url('assets'); ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets'); ?>/js/scripts.js"></script>
    <script src="<?= base_url('assets'); ?>/js/sb-admin-js.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/bootstrap-toggle-master/js/bootstrap4-toggle.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/jonthornton-jquery-timepicker/jquery.timepicker.min.js"></script>
    <script src="<?= base_url('assets/'); ?>vendor/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js" charset="UTF-8"></script>
    <script>
        $('#yearpicker,#absen_tahun').datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            orientation: "bottom auto"
        });

        $('#setting-list a').on('click', function(e) {
            e.preventDefault()
            $(this).tab('show')
        })

        $('#absen_bulan').datepicker({
            format: "MM",
            minViewMode: 'months',
            maxViewMode: 'months',
            startView: 'months',
            language: "id",
            orientation: "bottom auto"
        });

        $(document).ready(function() {
            $('#datatables').DataTable();
        });

        $(document).ready(function() {
            $('table.dashboard').DataTable();
        });
    </script>
    <script>
        function load_process() {
            swal.fire({
                imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                title: "Refresh Data",
                text: "Please wait",
                showConfirmButton: false,
                allowOutsideClick: false,
                timer: 1500
            });
        }

        $(".logout").click(function(event) {
            $.ajax({
                type: "POST",
                url: "<?= base_url('ajax/logoutajax'); ?>",
                beforeSend: function() {
                    swal.fire({
                        imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                        title: "Logging Out",
                        text: "Please wait",
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                },
                success: function(data) {
                    swal.fire({
                        icon: 'success',
                        title: 'Logout',
                        text: 'Anda Telah Keluar!',
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                    location.reload();
                }
            });
            event.preventDefault();
        });
        <?php if ($dataapp['maps_use'] == 1) : ?>
            if (document.getElementById("maps-absen")) {
                window.onload = function() {
                    var popup = L.popup();
                    var geolocationMap = L.map("maps-absen", {
                        center: [40.731701, -73.993411],
                        zoom: 15,
                    });

                    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    }).addTo(geolocationMap);

                    function geolocationErrorOccurred(geolocationSupported, popup, latLng) {
                        popup.setLatLng(latLng);
                        popup.setContent(
                            geolocationSupported ?
                            "<b>Error:</b> The Geolocation service failed." :
                            "<b>Error:</b> This browser doesn't support geolocation."
                        );
                        popup.openOn(geolocationMap);
                    }

                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                var latLng = {
                                    lat: position.coords.latitude,
                                    lng: position.coords.longitude,
                                };

                                var marker = L.marker(latLng).addTo(geolocationMap);
                                geolocationMap.setView(latLng);
                                document.getElementById("location-maps").innerHTML = position.coords.latitude + ", " + position.coords.longitude;
                            },
                            function() {
                                geolocationErrorOccurred(true, popup, geolocationMap.getCenter());
                            }
                        );
                    } else {
                        //No browser support geolocation service
                        geolocationErrorOccurred(false, popup, geolocationMap.getCenter());
                    }
                };
            }
        <?php else : ?>
            if (document.getElementById("location-maps")) {
                document.getElementById("location-maps").innerHTML = 'No Location';
            }
        <?php endif; ?>

        $("#btn-absensi").click(function(e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.

            var maps_absen = document.getElementById("location-maps").innerHTML;
            var ket_absen = $('#ket_absen').val();

            $.ajax({
                type: "POST",
                url: '<?= base_url('ajax/absenajax'); ?>',
                data: {
                    maps_absen: maps_absen,
                    ket_absen: ket_absen
                }, // serializes the form's elements.
                dataType: 'json',
                beforeSend: function() {
                    swal.fire({
                        imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                        title: "Proses Absensi",
                        text: "Please wait",
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                },
                success: function(response) {
                    if (response.success == true) {
                        swal.fire({
                            icon: 'success',
                            title: 'Absen Sukses',
                            text: 'Anda Telah Absen!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#func-absensi').load(location.href + " #func-absensi");
                    } else {
                        $("#infoabsensi").html(response.msgabsen).show().delay(3000).fadeOut();
                        swal.close()
                    }
                },
                error: function() {
                    swal.fire("Absen Gagal", "Ada Kesalahan Saat Absen!", "error");
                }
            });


        });
    </script>
    <!--Bagian CRUD Absen User-->
    <script>
        $("#listabsenku").on('click', '.detail-absen', function(e) {
            e.preventDefault();
            var absen_id = $(e.currentTarget).attr('data-absen-id');
            if (absen_id === '') return;
            $.ajax({
                type: "POST",
                url: '<?= base_url('ajax/dataabs?type=viewabs'); ?>',
                data: {
                    absen_id: absen_id
                },
                beforeSend: function() {
                    swal.fire({
                        imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                        title: "Mempersiapkan Preview Absensi",
                        text: "Please wait",
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                },
                success: function(data) {
                    swal.close();
                    $('#viewabsensimodal').modal('show');
                    $('#viewdataabsensi').html(data);

                },
                error: function() {
                    swal.fire("Preview Absensi Gagal", "Ada Kesalahan Saat menampilkan data absensi!", "error");
                }
            });
        });
    </script>

    <!--Bagian Setting User-->
    <script>
        $("#clear_rememberme").click(function(e) {
            Swal.fire({
                title: 'Hapus Semua Remember Me?',
                text: "Anda yakin ingin menghapus semua sesi remember me anda!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "<?= base_url('ajax/clear_rememberme?rmbtype=all'); ?>",
                        beforeSend: function() {
                            swal.fire({
                                imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                                title: "Menghapus Semua Remember Me Anda",
                                text: "Please wait",
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        },
                        success: function(data) {
                            swal.fire({
                                icon: 'success',
                                title: 'Menghapus Semua Remember Me Berhasil',
                                text: 'List remember me anda telah di hapus!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#remembersesslist').load(location.href + " #remembersesslist");
                        }
                    });
                }
            })
            e.preventDefault();
        });

        $(".sess_rememberme").click(function(e) {
            e.preventDefault();
            var sess_id = $(e.currentTarget).attr('data-sess-id');
            if (sess_id === '') return;
            Swal.fire({
                title: 'Hapus Sesi Remember Me Ini?',
                text: "Anda yakin ingin menghapus sesi di perangkat ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: '<?= base_url('ajax/clear_rememberme?rmbtype=self'); ?>',
                        data: {
                            sess_id: sess_id
                        },
                        beforeSend: function() {
                            swal.fire({
                                imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                                title: "Menghapus Sesi Perangkat Ini",
                                text: "Please wait",
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        },
                        success: function(data) {
                            swal.fire({
                                icon: 'success',
                                title: 'Menghapus Sesi Perangkat Ini Berhasil',
                                text: 'Anda telah menghapus sesi pada perangat ini!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $(e.currentTarget).parent().remove();
                        }
                    });
                }
            })
        });
    </script>
    <script>
        $('#chgpassuser').submit(function(e) {
            e.preventDefault();
            var form = this;
            $("#chgpass-btn").html("<span class='fas fa-spinner fa-pulse' aria-hidden='true' title=''></span> Mengganti Password").attr("disabled", true);
            var formdata = new FormData(form);
            $.ajax({
                url: "<?= base_url('ajax/usersetting?type=chgpwd'); ?>",
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    $('.text-danger').remove();
                    $("#infopass").hide();
                    swal.fire({
                        imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                        title: "Mengubah Password",
                        text: "Please wait",
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                },
                success: function(response) {
                    if (response.success == true) {
                        $('.text-danger').remove();
                        swal.fire({
                            icon: 'success',
                            title: 'Ubah Password Berhasil',
                            text: 'Password anda sudah diubah!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        form.reset();
                        $("#chgpass-btn").html("<span class='fas fa-key mr-1' aria-hidden='true' ></span>Ubah Password").attr("disabled", false);
                    } else {
                        swal.close()
                        $("#infopass").html(response.infopass).show();
                        swal.fire({
                            icon: 'error',
                            title: 'Ubah Password Gagal',
                            text: 'Password anda gagal diubah!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $("#chgpass-btn").html("<span class='fas fa-key mr-1' aria-hidden='true' ></span>Ubah Password").attr("disabled", false);
                        $.each(response.messages, function(key, value) {
                            var element = $('#' + key);
                            element.closest('div.form-group')
                                .find('.text-danger')
                                .remove();
                            if (element.parent('.input-group').length) {
                                element.parent().after(value);
                            } else {
                                element.after(value);
                            }
                        });
                    }
                },
                error: function() {
                    swal.fire("Ubah Password", "Ada Kesalahan Saat pengubahan password!", "error");
                    $("#chgpass-btn").html("<span class='fas fa-pen mr-1' aria-hidden='true' ></span>Edit").attr("disabled", false);
                }
            });

        });

        $('#settinguser').submit(function(e) {
            e.preventDefault();
            var form = this;
            $("#usrsetting-btn").html("<span class='fas fa-spinner fa-pulse' aria-hidden='true' title=''></span> Mengubah Data").attr("disabled", true);
            var formdata = new FormData(form);
            $.ajax({
                url: "<?= base_url('ajax/usersetting?type=basic'); ?>",
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    swal.fire({
                        imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                        title: "Mengubah Data",
                        text: "Please wait",
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                },
                success: function(response) {
                    if (response.success == true) {
                        $('.text-danger').remove();
                        swal.fire({
                            icon: 'success',
                            title: 'Ubah Profil Berhasil',
                            text: 'Profil anda sudah diubah!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        location.reload();
                        $("#usrsetting-btn").html("<span class='fas fa-pen mr-1' aria-hidden='true' ></span>Edit").attr("disabled", false);
                    } else {
                        swal.close()
                        $("#usrsetting-btn").html("<span class='fas fa-pen mr-1' aria-hidden='true' ></span>Edit").attr("disabled", false);
                        $.each(response.messages, function(key, value) {
                            var element = $('#' + key);
                            element.closest('div.form-group')
                                .find('.text-danger')
                                .remove();
                            if (element.parent('.input-group').length) {
                                element.parent().after(value);
                            } else {
                                element.after(value);
                            }
                        });
                    }
                },
                error: function() {
                    swal.fire("Mengubah Profil Gagal", "Ada Kesalahan Saat pengubahan profil!", "error");
                    $("#usrsetting-btn").html("<span class='fas fa-pen mr-1' aria-hidden='true' ></span>Edit").attr("disabled", false);
                }
            });

        });
    </script>
    <script>
        $("#refresh-tabel-absensi").click(function(e) {
            e.preventDefault();
            load_process();
            $('#listabsenku').DataTable().ajax.reload();
        });

        $('#listabsenku').DataTable({
            "ajax": {
                url: "<?= base_url('ajax/get_datatbl?type=allself'); ?>",
                type: 'get',
                async: true,
                "processing": true,
                "serverSide": true,
                dataType: 'json',
                "bDestroy": true
            },
            rowCallback: function(row, data, iDisplayIndex) {
                $('td:eq(0)', row).html();
            }
        });
    </script>
    <?php if ($this->session->userdata('role_id') == 1) : ?>
        <!-- Bagian Dashboard Admin-->
        <script>
            $("#sync-data-dashboard").click(function(e) {
                e.preventDefault();
                load_process();
                $('#list-absensi-masuk,#list-absensi-terlambat').DataTable().ajax.reload();
            });

            $("#refresh-tabel-absensi").click(function(e) {
                e.preventDefault();
                load_process();
                $('#list-absensi-all').DataTable().ajax.reload();
            });

            $("#refresh-tabel-pegawai").click(function(e) {
                e.preventDefault();
                load_process();
                $('#datapegawai').DataTable().ajax.reload();
            });

            $("#pgwadduser").click(function(e) {
                e.preventDefault();
                var acakkode = Math.random().toString().substr(2, 15)
                document.getElementById('kode_pegawai').value = acakkode
            });
        </script>
        <script>
            $('#list-absensi-masuk').DataTable({
                "ajax": {
                    url: "<?= base_url('ajax/get_datatbl?type=getallmsk'); ?>",
                    type: 'get',
                    async: true,
                    "processing": true,
                    "serverSide": true,
                    dataType: 'json',
                    "bDestroy": true
                },
                rowCallback: function(row, data, iDisplayIndex) {
                    $('td:eq(0)', row).html();
                }
            });
            $('#list-absensi-terlambat').DataTable({
                "ajax": {
                    url: "<?= base_url('ajax/get_datatbl?type=getalltrl'); ?>",
                    type: 'get',
                    async: true,
                    "processing": true,
                    "serverSide": true,
                    dataType: 'json',
                    "bDestroy": true
                },
                rowCallback: function(row, data, iDisplayIndex) {
                    $('td:eq(0)', row).html();
                }
            });
        </script>
        <script>
            $('#list-absensi-all').DataTable({
                "ajax": {
                    url: "<?= base_url('ajax/get_datatbl?type=all'); ?>",
                    type: 'get',
                    async: true,
                    "processing": true,
                    "serverSide": true,
                    dataType: 'json',
                    "bDestroy": true
                },
                rowCallback: function(row, data, iDisplayIndex) {
                    $('td:eq(0)', row).html();
                }
            });
        </script>
        <!--CRUD Absen-->
        <script>
            $("#clear-absensi").on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus Semua Absen?',
                    text: "Anda yakin ingin menghapus absensi ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "POST",
                            url: '<?= base_url('ajax/dataabs?type=delallabs'); ?>',
                            beforeSend: function() {
                                swal.fire({
                                    imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                                    title: "Menghapus Semua Absen",
                                    text: "Please wait",
                                    showConfirmButton: false,
                                    allowOutsideClick: false
                                });
                            },
                            success: function(data) {
                                swal.fire({
                                    icon: 'success',
                                    title: 'Menghapus Semua Absen Berhasil',
                                    text: 'Absen telah dihapus!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                $('#list-absensi-all').DataTable().ajax.reload();
                            },
                            error: function() {
                                swal.fire("Hapus Absensi Gagal", "Ada Kesalahan Saat menghapus semua absensi!", "error");
                            }
                        });
                    }
                })
            });


            $("#list-absensi-all").on('click', '.delete-absen', function(e) {
                e.preventDefault();
                var absen_id = $(e.currentTarget).attr('data-absen-id');
                if (absen_id === '') return;
                Swal.fire({
                    title: 'Hapus Absen Ini?',
                    text: "Anda yakin ingin menghapus absensi ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "POST",
                            url: '<?= base_url('ajax/dataabs?type=delabs'); ?>',
                            data: {
                                absen_id: absen_id
                            },
                            beforeSend: function() {
                                swal.fire({
                                    imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                                    title: "Menghapus Absen",
                                    text: "Please wait",
                                    showConfirmButton: false,
                                    allowOutsideClick: false
                                });
                            },
                            success: function(data) {
                                swal.fire({
                                    icon: 'success',
                                    title: 'Menghapus Absen Berhasil',
                                    text: 'Absen telah dihapus!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                $('#list-absensi-all').DataTable().ajax.reload();
                            },
                            error: function() {
                                swal.fire("Hapus Absensi Gagal", "Ada Kesalahan Saat menghapus absensi!", "error");
                            }
                        });
                    }
                })
            });

            $("#list-absensi-all").on('click', '.detail-absen', function(e) {
                e.preventDefault();
                var absen_id = $(e.currentTarget).attr('data-absen-id');
                if (absen_id === '') return;
                $.ajax({
                    type: "POST",
                    url: '<?= base_url('ajax/dataabs?type=viewabs'); ?>',
                    data: {
                        absen_id: absen_id
                    },
                    beforeSend: function() {
                        swal.fire({
                            imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                            title: "Mempersiapkan Preview Absensi",
                            text: "Please wait",
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                    },
                    success: function(data) {
                        swal.close();
                        $('#viewabsensimodal').modal('show');
                        $('#viewdataabsensi').html(data);

                    },
                    error: function() {
                        swal.fire("Preview Absensi Gagal", "Ada Kesalahan Saat menampilkan data absensi!", "error");
                    }
                });
            });

            $("#list-absensi-all").on('click', '.print-absen', function(e) {
                e.preventDefault();
                var absen_id = $(e.currentTarget).attr('data-absen-id');
                if (absen_id === '') return;
                $('#printabsensimodal').on('show.bs.modal', function(e) {
                    $(this).find('.btn-print-direct').attr('href', '<?= base_url('cetak?id_absen='); ?>' + absen_id + '');
                });
                $("#printdataabsensi").html('<object type="application/pdf" data="<?= base_url('cetak?id_absen='); ?>' + absen_id + '" height="850" style="width: 100%; display: block;">Your browser does not support object tag</object>');
            });
        </script>
        <!--CRUD Pegawai-->
        <script>
            $('#datapegawai').DataTable({
                "ajax": {
                    url: "<?= base_url('ajax/get_datatbl?type=datapgw'); ?>",
                    type: 'get',
                    async: true,
                    "processing": true,
                    "serverSide": true,
                    dataType: 'json',
                    "bDestroy": true
                },
                rowCallback: function(row, data, iDisplayIndex) {
                    $('td:eq(0)', row).html();
                }
            });

            $('#addpegawai').submit(function(e) {
                e.preventDefault();
                var form = this;
                $("#addpgw-btn").html("<span class='fas fa-spinner fa-pulse' aria-hidden='true' title=''></span> Proses Penambahan").attr("disabled", true);
                var formdata = new FormData(form);
                $.ajax({
                    url: "<?= base_url('ajax/datapgw?type=addpgw'); ?>",
                    type: 'POST',
                    data: formdata,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function() {
                        $("#info-data").hide();
                        swal.fire({
                            imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                            title: "Menambahkan Pegawai",
                            text: "Please wait",
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                    },
                    success: function(response) {
                        $("#info-data").html(response.messages).attr("disabled", false).show();
                        if (response.success == true) {
                            $('.text-danger').remove();
                            swal.fire({
                                icon: 'success',
                                title: 'Penambahan Pegawai Berhasil',
                                text: 'Penambahan pegawai sudah berhasil !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#datapegawai').DataTable().ajax.reload();
                            $('#addpegawaimodal').modal('hide');
                            form.reset();
                            $("#addpgw-btn").html("<span class='fas fa-plus mr-1' aria-hidden='true' ></span>Simpan").attr("disabled", false);
                        } else {
                            swal.close()
                            $("#addpgw-btn").html("<span class='fas fa-plus mr-1' aria-hidden='true' ></span>Simpan").attr("disabled", false);
                        }
                    },
                    error: function() {
                        swal.fire("Penambahan Pegawai Gagal", "Ada Kesalahan Saat penambahan pegawai!", "error");
                        $("#addpgw-btn").html("<span class='fas fa-pen mr-1' aria-hidden='true' ></span>Edit").attr("disabled", false);
                    }
                });

            });

            $("#datapegawai").on('click', '.delete-pegawai', function(e) {
                e.preventDefault();
                var pgw_id = $(e.currentTarget).attr('data-pegawai-id');
                if (pgw_id === '') return;
                Swal.fire({
                    title: 'Hapus User Ini?',
                    text: "Anda yakin ingin menghapus user ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "POST",
                            url: '<?= base_url('ajax/datapgw?type=delpgw'); ?>',
                            data: {
                                pgw_id: pgw_id
                            },
                            beforeSend: function() {
                                swal.fire({
                                    imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                                    title: "Menghapus User",
                                    text: "Please wait",
                                    showConfirmButton: false,
                                    allowOutsideClick: false
                                });
                            },
                            success: function(data) {
                                swal.fire({
                                    icon: 'success',
                                    title: 'Menghapus User Berhasil',
                                    text: 'Anda telah menghapus user!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                $('#datapegawai').DataTable().ajax.reload();
                            },
                            error: function() {
                                swal.fire("Penghapusan Pegawai Gagal", "Ada Kesalahan Saat menghapus pegawai!", "error");
                            }
                        });
                    }
                })
            });

            $("#datapegawai").on('click', '.activate-pegawai', function(e) {
                e.preventDefault();
                var pgw_id = $(e.currentTarget).attr('data-pegawai-id');
                if (pgw_id === '') return;
                $.ajax({
                    type: "POST",
                    url: '<?= base_url('ajax/datapgw?type=actpgw'); ?>',
                    data: {
                        pgw_id: pgw_id
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        swal.fire({
                            imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                            title: "Aktivasi User",
                            text: "Please wait",
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                    },
                    success: function(data) {
                        if (data.success) {
                            swal.fire({
                                icon: 'success',
                                title: 'Aktivasi User Berhasil',
                                text: 'Anda telah mengaktifkan user!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#datapegawai').DataTable().ajax.reload();
                        } else {
                            swal.fire({
                                icon: 'error',
                                title: 'User Sudah Diaktivasi',
                                text: 'User ini sudah diaktivasi!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#datapegawai').DataTable().ajax.reload();
                        }
                    },
                    error: function() {
                        swal.fire("Aktivasi Pegawai Gagal", "Ada Kesalahan Saat aktivasi pegawai!", "error");
                    }
                });
            });

            $("#datapegawai").on('click', '.view-pegawai', function(e) {
                e.preventDefault();
                var pgw_id = $(e.currentTarget).attr('data-pegawai-id');
                if (pgw_id === '') return;
                $.ajax({
                    type: "POST",
                    url: '<?= base_url('ajax/datapgw?type=viewpgw'); ?>',
                    data: {
                        pgw_id: pgw_id
                    },
                    beforeSend: function() {
                        swal.fire({
                            imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                            title: "Mempersiapkan Preview User",
                            text: "Please wait",
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                    },
                    success: function(data) {
                        swal.close();
                        $('#viewpegawaimodal').modal('show');
                        $('#viewdatapegawai').html(data);

                    },
                    error: function() {
                        swal.fire("Preview Pegawai Gagal", "Ada Kesalahan Saat menampilkan data pegawai!", "error");
                    }
                });
            });

            $("#datapegawai").on('click', '.edit-pegawai', function(e) {
                e.preventDefault();
                var pgw_id = $(e.currentTarget).attr('data-pegawai-id');
                if (pgw_id === '') return;
                $.ajax({
                    type: "POST",
                    url: '<?= base_url('ajax/datapgw?type=edtpgw'); ?>',
                    data: {
                        pgw_id: pgw_id
                    },
                    beforeSend: function() {
                        swal.fire({
                            imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                            title: "Mempersiapkan Edit User",
                            text: "Please wait",
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                    },
                    success: function(data) {
                        swal.close();
                        $('#editpegawaimodal').modal('show');
                        $('#editdatapegawai').html(data);

                        $('#editpegawai').submit(function(e) {
                            e.preventDefault();
                            var form = this;
                            $("#editpgw-btn").html("<span class='fas fa-spinner fa-pulse' aria-hidden='true' title=''></span> Menyimpan").attr("disabled", true);
                            var formdata = new FormData(form);
                            $.ajax({
                                url: "<?= base_url('ajax/editpgwbc?type=edtpgwalt'); ?>",
                                type: 'POST',
                                data: formdata,
                                processData: false,
                                contentType: false,
                                dataType: 'json',
                                beforeSend: function() {
                                    swal.fire({
                                        imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                                        title: "Menyimpan Data Pegawai",
                                        text: "Please wait",
                                        showConfirmButton: false,
                                        allowOutsideClick: false
                                    });
                                },
                                success: function(response) {
                                    if (response.success == true) {
                                        $('.text-danger').remove();
                                        swal.fire({
                                            icon: 'success',
                                            title: 'Edit Pegawai Berhasil',
                                            text: 'Edit pegawai sudah berhasil !',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                        $('#datapegawai').DataTable().ajax.reload();
                                        $('#editpegawaimodal').modal('hide');
                                        form.reset();
                                        $("#editpgw-btn").html("<span class='fas fa-pen mr-1' aria-hidden='true' ></span>Edit").attr("disabled", false);
                                    } else {
                                        swal.close()
                                        $("#editpgw-btn").html("<span class='fas fa-pen mr-1' aria-hidden='true' ></span>Edit").attr("disabled", false);
                                        $.each(response.messages, function(key, value) {
                                            var element = $('#' + key);
                                            element.closest('div.form-group')
                                                .find('.text-danger')
                                                .remove();
                                            if (element.parent('.input-group').length) {
                                                element.parent().after(value);
                                            } else if (element.parent('.form-row').length) {
                                                element.parent().after(value);
                                            } else {
                                                element.after(value);
                                            }
                                        });
                                    }
                                },
                                error: function() {
                                    swal.fire("Edit Pegawai Gagal", "Ada Kesalahan Saat pengeditan pegawai!", "error");
                                    $("#editpgw-btn").html("<span class='fas fa-pen mr-1' aria-hidden='true' ></span>Edit").attr("disabled", false);
                                }
                            });

                        });
                    },
                    error: function() {
                        swal.fire("Edit Pegawai Gagal", "Ada Kesalahan Saat pengeditan pegawai!", "error");
                    }
                });
            });
        </script>
        <!-- Bagian Setting Aplikasi-->
        <script>
            $("#absen_mulai,#absen_sampai, #absen_pulang_sampai").timepicker({
                'timeFormat': 'H:i:s'
            });
            $('#setTimebtn').on('click', function() {
                $('#absen_mulai').timepicker('setTime', new Date());
            });

            $("#resetsettingapp").click(function(event) {
                Swal.fire({
                    title: 'Reset Settings App',
                    text: "Anda yakin ingin reset ulang settingan ini ke awal!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Reset!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "POST",
                            url: "<?= base_url('ajax/initsettingapp?type=1'); ?>",
                            beforeSend: function() {
                                swal.fire({
                                    imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                                    title: "Resetting Setting App",
                                    text: "Please wait",
                                    showConfirmButton: false,
                                    allowOutsideClick: false
                                });
                            },
                            success: function(data) {
                                swal.fire("Reset!", "Settingan Telah Direset.", "success");
                                location.reload();
                            }
                        });
                    }
                })
                event.preventDefault();
            });

            $("#initsettingapp").click(function(event) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('ajax/initsettingapp?type=2'); ?>",
                    beforeSend: function() {
                        swal.fire({
                            imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                            title: "Initializing Setting App",
                            text: "Please wait",
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                    },
                    success: function(data) {
                        swal.fire("Initialize Setting App", "Initialisasi Setting Aplikasi Sukses!", "success");
                        location.reload();
                    }
                });
                event.preventDefault();
            });

            $('#settingapp').submit(function(e) {
                e.preventDefault();
                $("#settingapp-btn").html("<span class='fas fa-spinner fa-pulse' aria-hidden='true' title=''></span> Saving").attr("disabled", true);
                var formdata = new FormData(this);
                $.ajax({
                    url: "<?= base_url('ajax/savingsettingapp'); ?>",
                    type: 'POST',
                    data: formdata,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function() {
                        swal.fire({
                            imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                            title: "Editing Setting App",
                            text: "Please wait",
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                    },
                    success: function(response) {
                        if (response.success == true) {
                            $('.text-danger').remove();
                            swal.fire("Edit Setelan", "Edit Setelan Berhasil!", "success");
                            $("#settingapp-btn").html("<span class='fas fa-pen mr-1' aria-hidden='true' ></span>Edit").attr("disabled", false);
                            location.reload();
                        } else {
                            swal.close()
                            swal.fire({
                                icon: 'error',
                                title: "Edit Setelan",
                                text: "Edit Setelan Gagal!",
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                timer: 1500
                            });
                            $("#settingapp-btn").html("<span class='fas fa-pen mr-1' aria-hidden='true' ></span>Edit").attr("disabled", false);
                            $.each(response.messages, function(key, value) {
                                var element = $('#' + key);
                                element.closest('div.form-group')
                                    .find('.text-danger')
                                    .remove();
                                if (element.parent('.input-group').length) {
                                    element.parent().after(value);
                                } else {
                                    element.after(value);
                                }
                            });
                        }
                    },
                    error: function() {
                        swal.fire("Setelan Aplikasi Gagal", "Ada Kesalahan Saat Edit Setelan!", "error");
                        $("#settingapp-btn").html("<span class='fas fa-pen mr-1' aria-hidden='true' ></span>Edit").attr("disabled", false);
                    }
                });

            });
        </script>
    <?php elseif ($this->session->userdata('role_id') == 2) : ?>
        <script>
            $('#list-absensi-all').DataTable({
                "ajax": {
                    url: "<?= base_url('ajax/get_datatbl?type=all'); ?>",
                    type: 'get',
                    async: true,
                    "processing": true,
                    "serverSide": true,
                    dataType: 'json',
                    "bDestroy": true
                },
                rowCallback: function(row, data, iDisplayIndex) {
                    $('td:eq(0)', row).html();
                }
            });
        </script>
        <!--CRUD Absen-->
        <script>
            $("#list-absensi-all").on('click', '.detail-absen', function(e) {
                e.preventDefault();
                var absen_id = $(e.currentTarget).attr('data-absen-id');
                if (absen_id === '') return;
                $.ajax({
                    type: "POST",
                    url: '<?= base_url('ajax/dataabs?type=viewabs'); ?>',
                    data: {
                        absen_id: absen_id
                    },
                    beforeSend: function() {
                        swal.fire({
                            imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                            title: "Mempersiapkan Preview Absensi",
                            text: "Please wait",
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                    },
                    success: function(data) {
                        swal.close();
                        $('#viewabsensimodal').modal('show');
                        $('#viewdataabsensi').html(data);

                    },
                    error: function() {
                        swal.fire("Preview Absensi Gagal", "Ada Kesalahan Saat menampilkan data absensi!", "error");
                    }
                });
            });

            $("#list-absensi-all").on('click', '.print-absen', function(e) {
                e.preventDefault();
                var absen_id = $(e.currentTarget).attr('data-absen-id');
                if (absen_id === '') return;
                $('#printabsensimodal').on('show.bs.modal', function(e) {
                    $(this).find('.btn-print-direct').attr('href', '<?= base_url('cetak?id_absen='); ?>' + absen_id + '');
                });
                $("#printdataabsensi").html('<object type="application/pdf" data="<?= base_url('cetak?id_absen='); ?>' + absen_id + '" height="850" style="width: 100%; display: block;">Your browser does not support object tag</object>');
            });
        </script>
    <?php endif; ?>
    </body>

    </html>
<?php else : ?>
    </main>
    </div>
    </div>
    <script src="<?= base_url('assets'); ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets'); ?>/js/scripts.js"></script>
    <script src="<?= base_url('assets'); ?>/js/sb-admin-js.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/sweetalert2/sweetalert2.all.min.js"></script>

    </body>

    </html>
<?php endif; ?>