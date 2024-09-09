"use strict";

// Class definition
var Stt = (function () {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const id_stt = urlParams.get('filterstt')

    var SetFilter = function () {
        console.log('js');
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

        $('#id_asal').select2({
            placeholder: 'Cari Wilayah ....',
            allowClear: true,
            ajax: {
                url: APP_URL + '/getwilayah',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    $('#id_asal').empty();
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

        $('#id_tujuan').select2({
            placeholder: 'Cari Wilayah ....',
            allowClear: true,
            ajax: {
                url: APP_URL + '/getwilayah',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    $('#id_tujuan').empty();
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

        $('#id_tipe_barang').select2({
            placeholder: 'Cari Tipe Kirim ....',
            ajax: {
                url: APP_URL + '/getTipeKirim',
                minimumInputLength: 0,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    $('#id_tipe_kirim').empty();
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

    var actionIndex = function () {
        const tableDm = $('#table-stt');
        tableDm.on('click', '.btn-bayar', function (e) {
            e.preventDefault();
            const parent = e.target.closest('tr');

            var urlnya = $(this).data('url');
            var token = TOKEN;
            const piutang = $(this).data('sisa');
            const kode_stt = $(this).data('kode-invoice');
            const nm_pelanggan = $(this).data('nm-pelanggan');;
            const id_pelanggan = $(this).data('id-pelanggan');

            var today = new Date().toISOString().split('T')[0]
            $("#modal-bayar").modal('show')
            $("#tgl_bayar").val(today)
            $("#nama_bayar").val(nm_pelanggan)
            $("#n_bayar").val(piutang)
            $("#info").val("Pembayaran Invoice No. " + kode_stt + " Atas Nama " + nm_pelanggan);
            $("#id_pelanggan").val(id_pelanggan);
            $("#bayar_invoice").attr("action", urlnya)

            $(".btn-submit").click(function () {
                $(this).prop("disabled", true);
                $(this).html(
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
                );
                $("#bayar_invoice").submit();
            });
        });

        tableDm.on('click', '.btn-set-ppn', function (e) {
            e.preventDefault();
            const parent = e.target.closest('tr');

            var urlnya = $(this).data('url');
            var datanya = $(this).data('encode');
            var token = TOKEN;
            console.log(datanya);
            const id_stt = datanya['id_stt'];
            const n_ppn = datanya['n_ppn'];
            const kode_stt = datanya['kode_stt'];

            var today = new Date().toISOString().split('T')[0]
            $("#modal-stt").modal('show')
            $("#stt").val(id_stt)
            $("#kode_stt").val(kode_stt)
            $("#n_ppn").val(n_ppn)
        });
    }


    var handleDeleteRows = function () {
        const tableData = $('#table-stt');
        tableData.on('click', '.btn-delete', function (e) {
            e.preventDefault();
            const parent = e.target.closest('tr');

            var urlnya = $(this).data('url');
            var token = TOKEN;
            const kodeDm = parent.querySelectorAll('td')[0].innerText + ' Milik ' + parent.querySelectorAll('td')[1].innerText;

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
                            success: function (err) {
                                // console.log(err);
                                location.reload();
                            },
                            error: function (err) {
                                // console.log(err);
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
    }

    return {
        init: function () {
            SetFilter();
            handleDeleteRows();
            actionIndex();
        },
    };
})();

KTUtil.onDOMContentLoaded(function () {
    Stt.init();
});
