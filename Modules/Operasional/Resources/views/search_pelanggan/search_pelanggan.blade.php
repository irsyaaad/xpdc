<div class="modal fade bd-example-modal-lg" id="modal-plgn" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cari Pelanggan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table" id="users-table">
                    <thead>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>No Telp</th>
                        <th>Alamat</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#</td>
                            <td>#</td>
                            <td>#</td>
                            <td>#</td>
                            <th>#</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script>
    $(function() {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/getPelangganDatatable',
            columns: [
            { data: 'id_pelanggan', name: 'id_pelanggan' },
            { data: 'nm_pelanggan', name: 'nm_pelanggan' },
            { data: 'telp', name: 'telp' },
            { data: 'alamat', name: 'alamat' },
            { "render": function ( data, type, row ) {
                        var html  = '<button type="button" class="btn btn-success btn-sm" onclick="setData('+row.id_pelanggan+')"><i class="fa fa-plus"></i> Add</button>'
                        return html
                    }
                },
            ]
        });
    });

    function setData(params) {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ url('getDetailPelanggan') }}/"+params,
            success: function(data) {
                var nama = data.nm_pelanggan;
                var nm_pelanggan = nama.toUpperCase();
                cekLimit(params);
                $("#id_pelanggan").append('<option value=' + params + '>'+nm_pelanggan+'</option>');
                $("#pengirim_nm").val(data.nm_pelanggan);
                $("#pengirim_alm").val(data.alamat);
                $("#pengirim_telp").val(data.telp);
                $("#text-asal").text($('#pengirim_id_region').text());
                $("#text-tujuan").text($('#penerima_id_region').text());
                status_member = data.is_member;
                $("#modal-plgn").modal("hide");
            },
        });
    }
</script>
