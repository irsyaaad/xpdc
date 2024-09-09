"use strict";

// Class definition
var Stt = (function () {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const id_stt = urlParams.get('filterstt')

    var SetFilter = function () {
        console.log('invoice pay');
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
                        results:  $.map(data, function (item) {
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

        tableData.on('click', '.btn-edit', function (e) {
            e.preventDefault();
            const parent = e.target.closest('tr');

            var urlnya = $(this).data('url');
            var token = TOKEN;
            var today = new Date().toISOString().split('T')[0]
            $("#modal-bayar").modal('show');
            $("#_method").val("PUT");
            $("#tgl_bayar").val($(this).data('tgl-bayar'));
            $("#nm_bayar").val($(this).data('nm-bayar'));
            $("#no_referensi").val($(this).data('no-bayar'));
            $("#n_bayar").val($(this).data('n-bayar'));
            $("#info").val($(this).data('info'));
            $("#id_pelanggan").val($(this).data('id-pelanggan'));
            $("#id_asuransi").val($(this).data('id-asuransi'));
            $("#bayar_invoice").attr("action", urlnya);
        });
    }

    return {
        init: function () {
            SetFilter();
            handleDeleteRows();
        },
    };
})();

KTUtil.onDOMContentLoaded(function () {
    Stt.init();
});
