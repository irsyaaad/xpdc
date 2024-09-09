@extends('template.document')

@section('data')
<div class="text-right">
    <button class="btn" style="background-color: #00c5dc; color:white; border-radius:10px" onclick="cetak()"><i class="fa fa-print"></i>  Cetak Data</button>
</div>
<br>
<div class="table-responsive">
    <table class="table table-sm table-striped table-bordered" id="html_table" width="100%">
        <thead style="background-color: grey; color:white; font-weight: bold; text-align: center" class="text-center">
            <tr>
                <th>No</th>
                <th>No. STT</th>
                <th>Tgl Masuk</th>
                <th>Pelanggan</th>
                <th>Harga Pertanggungan (Barang)</th>
                <th>Harga Asuransi</th>
                <th>Broker</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
    </table>
</div>

<div class="modal fade" id="modal-pelanggan" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h5><b id="text-auth"> Silahkan Masukan data yang ingin dicetak </b></h5>
            </div>
            <div class="modal-body" style="margin-top: -7%">
                <form action="{{route('cetakdataasuransi')}}" method="get" id="formInput">
                    <label for="id_plgn_group">
                        <b>Nama Broker </b>
                    </label>
                    <select class="form-control m-input m-input--square" name="broker" id="broker">
                        <option value="">-- Pilih Broker --</option>
                        @foreach($perush as $key => $value)
                        <option value="{{ $value->id_perush_asuransi }}">{{ strtoupper($value->nm_perush_asuransi) }}</option>
                        @endforeach
                    </select>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="id_plgn_group">
                                <b>Dari Tanggal </b>
                            </label>
                            <input type="date" name="dr_tgl" id="dr_tgl" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="id_plgn_group">
                                <b>Sampai Tanggal </b>
                            </label>
                            <input type="date" name="sp_tgl" id="sp_tgl" class="form-control">
                        </div>
                    </div>
                </form>

                <div style="margin-top: 1%; font-weight: bold;">
                    <label class="span-required" id="error-code"></label>
                    <label class="span-success" id="success-code"></label>
                </div>


                <div class="text-right">
                    <button type="submit" class="btn btn-sm btn-success" onclick="savedata()"><i class="fa fa-save"></i> Submit</button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                </div>
            </div>
        </div>
    </div>
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
        ajax: '/getDataAsuransi',
        "order": [[ 2, 'desc' ]],
        columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
        { data: 'id_stt', name: 'id_stt' },
        { data: 'tgl_masuk', name: 'tgl_masuk' },
        { data: 'pelanggan', name: 'pelanggan' },
        { data: 'n_pertanggungan', name: 'n_pertanggungan' },
        { data: 'n_nominal', name: 'n_nominal' },
        { data: 'broker', name: 'broker' },
        {"render": function ( data, type, row )
        {
            var xx = row.id_asuransi;
            var html = '<div class="dropdown"><button style="background-color: #00c5dc; color:white; font-weight: bold; text-align: center; border-color:#00c5dc; border-radius:5px" class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                html += '<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">';
                    html += '<a class="dropdown-item" href="{{ url(Request::segment(1)) }}/'+row.id_asuransi+'/show"><i class="fa fa-eye"></i> Lihat</a>';
                    html += '<a class="dropdown-item" href="{{ url(Request::segment(1)) }}/'+row.id_asuransi+'/edit"><i class="fa fa-pencil"></i> Edit</a>';
                    html += '</div></div>'
                    return html;
                }
            },
            ]
        });
    });
</script>
    @endsection
