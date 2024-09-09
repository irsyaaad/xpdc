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

    }

    

    var addDetail = function () {
        $("#id_ac_debet").select2();
        $("#id_ac_kredit").select2();
    }

    return {
        init: function () {
            SetFilter();
            handleDeleteRows();
            addDetail();
        },
    };
})();

KTUtil.onDOMContentLoaded(function () {
    Stt.init();
});
