@extends('template.document')

@section('data')

<div class="table-responsive">
    <table class="table table-sm table-striped table-bordered" id="html_table" width="100%">
        <thead style="background-color: grey; color:white; font-weight: bold; text-align: center" class="text-center">
            <tr>
                <th>No</th>
                <th>Kode Invoice</th>
                <th>Tgl Masuk</th>
                <th>Tgl Jatuh Tempo</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
    </table>
</div>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
<script>
$("#shareselect").change(function(){
    $("#form-share").submit();
});
function savedata() {
    console.log('tes');
    $("#formInput").submit();
}
function cetak() {
    $("#modal-pelanggan").modal('show');
}
$(function() {
    $('#html_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/getDataInvoiceAsuransi',
        "order": [[ 2, 'desc' ]],
        columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
        { data: 'kode_invoice', name: 'kode_invoice' },
        { data: 'tgl_masuk', name: 'tgl_masuk' },
        { data: 'jatuh_tempo', name: 'jatuh_tempo' },
        { data: 'nm_pelanggan', name: 'nm_pelanggan' },
        { data: 'total', name: 'total' },
        { data: 'nm_status', name: 'nm_status' },
        {"render": function ( data, type, row )
        {
            var xx = row.id_asuransi;
            var html = '<div class="dropdown"><button style="background-color: #00c5dc; color:white; font-weight: bold; text-align: center; border-color:#00c5dc; border-radius:5px" class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                html += '<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">';
                    html += '<a class="dropdown-item" href="{{ url(Request::segment(1)) }}/'+row.id_invoice+'/show"><i class="fa fa-eye"></i> Lihat</a>';
                    html += '<a class="dropdown-item" href="{{ url(Request::segment(1)) }}/'+row.id_invoice+'/edit"><i class="fa fa-pencil"></i> Edit</a>';
                    html += '<a class="dropdown-item" href="{{ url(Request::segment(1)) }}/'+row.id_invoice+'/cetak"><i class="fa fa-print"></i> Cetak</a>';
                    html += '</div></div>'
                    return html;
                }
            },
            ]
        });
    });
</script>
    @endsection
