"use strict";

// Class definition
var Stt = (function () {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const id_stt = urlParams.get('filterstt')

    var SetFilter = function () {
        console.log('js');
        $('#f_id_pelanggan').select2({
            placeholder: 'Cari Tipe Kirim ....',
            ajax: {
                url: APP_URL + '/getPerusahaan',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    $('#f_id_pelanggan').empty();
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.value,
                                id: item.kode
                            }
                        })
                    };
                },
                cache: true
            }
        });
    }

    var handleDeleteRows = function () {
        const tableData = $('#table-data');
        tableData.on('click', '.btn-delete', function (e) {
            e.preventDefault();
            const parent = e.target.closest('tr');

            var urlnya = $(this).data('url');
            var token = TOKEN;
            const kodeDm = parent.querySelectorAll('td')[0].innerText;

            // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
            Swal.fire({
                text: "Are you sure you want to delete " + kodeDm + "?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, delete!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function (result) {
                if (result.value) {
                    // Simulate delete request -- for demo purpose only
                    Swal.fire({
                        text: "Deleting " + kodeDm,
                        icon: "info",
                        buttonsStyling: false,
                        showConfirmButton: false,
                        timer: 1000
                    }).then(function () {
                        console.log(urlnya);
                        $.ajax({
                            type: "DELETE",
                            url: urlnya,
                            data: { _token: token },
                            success: function (data) {
                                location.reload();
                            },
                            error: function (err) {
                                location.reload();
                            }
                        });
                    });
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: kodeDm + " was not deleted.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    });
                }
            });
        });

        $(".btn-submit").click(function () {
            $(this).prop("disabled", true);
            $(this).html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
            );
            $("#form-data").submit();
        });

        tableData.on('click', '.btn-edit', function (e) {
            e.preventDefault();
            var urlnya = $(this).data('url');
            var data = $(this).data('temp');
            $("#kt_modal_create_app_form").attr("action", urlnya);
            $("#_method").val("PUT");
            $("#nm_tipe_kirim").val(data.nm_tipe_kirim);
            if (data.is_aktif) {
                console.log('okde');
                $("#is_aktif").prop("checked", true);
            } else {
                $("#is_aktif").prop("checked", false);
            }
            console.log(data);
            $("#kt_modal_create_app").modal('show');
        });

        tableData.on('click', '.btn-bayar', function (e) {
            e.preventDefault();
            var urlnya = $(this).data('url');
            var nominal_beli = $(this).data('nominal-beli');
            var nm_broker = $(this).data('nm-broker');
            var id_asuransi = $(this).data('id-asuransi');
            var id_perush_asuransi = $(this).data('id-perush-asuransi');
            $("#bayar_invoice").attr("action", urlnya);
            var today = new Date().toISOString().split('T')[0];
            $("#tgl_bayar").val(today);
            $("#n_bayar").val(nominal_beli);
            $("#nm_broker").val(nm_broker);
            $("#id_asuransi").val(id_asuransi);
            $("#id_perush_asuransi").val(id_perush_asuransi);

            $("#modal-bayar").modal('show');
        });
    }

    var cariSTT = function () {
        console.log('button');
        $("#btnFetch").click(function () {
            // disable button
            $(this).prop("disabled", true);
            // add spinner to button
            $(this).html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
            );
        });

        function update_status(status = true) {
            const status_btn = status;
            return status_btn
        }
        $("#btn-search").click(function () {
            const id_stt_dm = $("#id_stt_dm").val();
            $(this).prop("disabled", true);
            // add spinner to button
            $(this).html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
            );
            console.log(id_stt_dm);
            $.ajax({
                url: APP_URL + '/asuransi/get-stt-asuransi/' + id_stt_dm,
                success: function (data) {
                    const stt = data;
                    console.log(stt);
                    $("#nm_pengirim").val(stt.PENGIRIM_NM);
                    $("#asal").val(stt.KOTA_ASAL);
                    $("#tujuan").val(stt.KOTA_TUJUAN);
                    $("#nm_kapal").val(stt.NM_KAPAL);
                    $("#nm_tipe_barang").val(stt.NM_TIPE_KIRIM);
                    $("#qty").val(stt.N_KOLI);
                    $("#tgl_berangkat").val(stt.TGL_BERANGKAT);
                    $("#tgl_sampai").val(stt.TGL_SAMPAI);
                    $("#id_pelanggan").val(stt.PELANGGAN).trigger("change");

                    $("#btn-search").prop("disabled", false);
                    $("#btn-search").html(
                        `Cari`
                    );
                },
                error: function (err) {
                    $("#btn-search").prop("disabled", false);
                    $("#btn-search").html(
                        `Cari`
                    );
                    console.log("error", err);
                    Swal.fire({
                        text: "Oppsss, Ada yang salah, silahkan hubungi Administrator",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    });
                }
            });
        });
    }

    var addDetail = function () {
        var max_fields = 10;
        var wrapper = $("#detail-pemasukan");
        var add_button = $("#add-detail");
        var akun = $("#id_ac_detail").data('akun');
        const obj = JSON.parse(akun);
        console.log("akun", akun, obj);
        var roles_str = '';

        // $.each(largeJSONobject.obj, function (index,value) {
        //     roles_str += '<option value="' + item.id_ac + '">' + item.nama + '</option>';
        // });

        $.each(obj, function (idx, obj) {
            roles_str += '<option value="' + obj.id_ac + '">' + obj.nama + '</option>';
            console.log(obj.id_ac + ": " + obj.nama);
        });
        console.log(roles_str);

        var x = 1;
        $(add_button).click(function (e) {
            e.preventDefault();
            if (x < max_fields) {
                x++;
                $(wrapper).append(`
                    <div class="row">
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-3">
                                    <span>Akun</span>
                                    <span class="ms-1" data-bs-toggle="tooltip" title="Pilih Pelanggan.">
                                        <i class="ki-outline ki-information fs-7"></i>
                                    </span>
                                </label>
                                <select id="id_ac_detail${x}" class="form-select form-select-solid" name="id_ac_detail[]" data-placeholder="Pilih Akun" data-kt-select2="true">
                                    ${roles_str}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-3">
                                    <span class="required">Nominal</span>
                                    <span class="ms-1" data-bs-toggle="tooltip" title="Diterima dari.">
                                        <i class="ki-outline ki-information fs-7"></i>
                                    </span>
                                </label>
                                <input type="number" class="form-control form-control-solid" name="harga[]" id="harga[]" value=""
                                    required="required" maxlength="100" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-3">
                                    <span class="required">Keterangan</span>
                                    <span class="ms-1" data-bs-toggle="tooltip" title="Diterima dari.">
                                        <i class="ki-outline ki-information fs-7"></i>
                                    </span>
                                </label>
                                <input type="text" class="form-control form-control-solid" name="detail_info[]" id="detail_info[]" value=""
                                    required="required" maxlength="100" />
                            </div>
                        </div>
                        <button type="button" id="remove-detail" class="btn btn-sm btn-danger col-md-2 mb-8 mt-11 delete">Delete</button>
                    </div>
                    <script>
                        $("#id_ac_detail${x}").select2();
                    </script>
                    `); //add input box
            } else {
                alert('You Reached the limits')
            }
        });

        $(wrapper).on("click", ".delete", function (e) {
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        })

        $(".id_ac_select").select2();
    }

    return {
        init: function () {
            SetFilter();
            handleDeleteRows();
            cariSTT();
            addDetail();
        },
    };
})();

KTUtil.onDOMContentLoaded(function () {
    Stt.init();
});
