"use strict";

// Class definition
var Stt = (function () {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const id_stt = urlParams.get('filterstt')

    var SetFilter = function () {
        console.log('jurnal-asuransi');
        $('#f_id_tipe_kirim').select2({
            placeholder: 'Cari Tipe Kirim ....',
            ajax: {
                url: APP_URL + '/getTipeKirim',
                minimumInputLength: 3,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    $('#f_id_tipe_kirim').empty();
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

        var $rows = $('#table-data tr');
        $('#search').keyup(function () {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

            $rows.show().filter(function () {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
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
                        $.ajax({
                            type: "DELETE",
                            url: urlnya,
                            data: { _token: token },
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

        tableData.on('click', '.btn-edit', function (e) {
            e.preventDefault();
            var urlnya = $(this).data('url');
            var data = $(this).data('temp');
            $("#kt_modal_create_app_form").attr("action", urlnya);
            $("#_method").val("PUT");
            $("#id_perush_asuransi").val(data.id_perush_asuransi).trigger('change');
            $("#harga_beli").val(data.harga_beli);
            $("#harga_jual").val(data.harga_jual);
            $("#charge_min").val(data.charge_min);
            if (data.is_aktif) {
                console.log('okde');
                $("#is_aktif").prop("checked", true);
            } else {
                $("#is_aktif").prop("checked", false);
            }
            console.log(data);
            $("#kt_modal_create_app").modal('show');
        });
    }

    var cariSTT = function () {
        console.log('js');
        $("#btn-search").click(function () {
            const id_stt_dm = $("#id_stt_dm").val();
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
                    console.log(formattedLocalDate);
                },
                error: function (err) {
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

    return {
        init: function () {
            SetFilter();
            handleDeleteRows();
            cariSTT();
        },
    };
})();

KTUtil.onDOMContentLoaded(function () {
    Stt.init();
});
