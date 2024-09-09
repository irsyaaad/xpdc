@extends('template.document')

@section('data')

<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
    @include('kepegawaian::filter.filter-collapse')
    @csrf
    <input type="hidden" name="_method" value="GET">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover table-responsive" id="html_table" width="100%">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No</th>
                        <th>Vendor </th>
                        <th>Asal</th>
                        <th>Tujuan</th>
                        <th>Harga</th>
                        <th>Keterangan</th>
                        <th>
                            <center>Action</center>
                        </th>
                    </tr>
                </thead>
                
            </table>
        </div>
    </div>
</form>

<div class="modal fade" id="modal-harga" tabindex="-1" role="dialog" aria-labelledby="modal-harga" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-money"> </i> Jumlah Perhitungan Harga</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-responsive">
                    <thead style="background-color: grey; color : #ffff">
                        <tr>
                            <th>Vendor </th>
                            <th>Asal</th>
                            <th>Tujuan</th>
                            <th>Harga</th>
                            <th>
                                <center>Action</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table-harga">
                        
                    </tbody>
                </table>
                <table class="table table-responsive" id="table-total">
                    
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function () {
        $('#html_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/getDataHargaVendor',
            "order": [[ 2, 'desc' ]],
            columns: [
            { data: 'nm_ven', name: 'nm_ven' },
            { data: 'nm_ven', name: 'nm_ven' },
            { data: 'wil_asal', name: 'wil_asal' },
            { data: 'wil_tujuan', name: 'wil_tujuan' },
            { data: 'harga', name: 'harga' },
            { data: 'keterangan', name: 'keterangan' },
            {"render": function ( data, type, row )
            {
                var xx = row.id_asuransi;
                var url_delete = `{{ url(Request::segment(1).'/'.'${row.id_harga}') }}`;
                var url_edit = `{{ url(Request::segment(1).'/'.'${row.id_harga}'."/edit") }}`;
                
                var html = '<div class="dropdown"><button style="background-color: #00c5dc; color:white; font-weight: bold; text-align: center; border-color:#00c5dc; border-radius:5px" class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    html += '<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">';
                        html += '<a class="dropdown-item" href="#" data-target="#modal-harga" data-toggle="modal" onclick="addHitung(`'+row.id_harga+'`, `'+row.nm_ven+'`, `'+row.wil_asal+'`, `'+row.wil_tujuan+'`, `'+row.harga+'`)"><i class="fa fa-dollar"></i> Hitung</a>';
                        html += '<a class="dropdown-item" href="'+url_edit+'"><i class="fa fa-pencil"></i> Edit</a>';
                        html += '<button class="dropdown-item" type="button" data-toggle="tooltip" data-placement="bottom" title="Hapus" onclick="CheckDelete(`'+url_delete+'`)"><span><i class="fa fa-times"></i></span> Hapus</button>';
                        html += '</div></div>'
                        return html;
                    }
                },
                ]
            });
        });
        
        $("#shareselect").on("change", function(e) {
            $("#form-select").submit();
        });
        
        $('#id_tujuan').select2({
            placeholder: 'Cari Kota Asal ....',
            ajax: {
                url: '{{ url('getwilayah') }}',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    $('#id_tujuan').empty();
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
        
        $('#id_asal').select2({
            placeholder: 'Cari Kota Tujuan ....',
            ajax: {
                url: '{{ url('getwilayah') }}',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    $('#id_asal').empty();
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
        
        $('#id_ven').select2();
        var total = 0;
        
        @if(isset($filter["page"]))
        $("#shareselect").val('{{ $filter["page"] }}');
        @endif	
        
        @if(isset($filter["id_vendor"]))$("#id_ven").select2().val("{{ $filter["id_vendor"] }}").trigger("change");@endif
        
        @if(isset($filter["asal"]->id_wil))
        $('#id_asal').append('<option value="{{ $filter["asal"]->id_wil }}">{{ $filter["asal"]->nama_wil }}</option>');
        @endif
        
        @if(isset($filter["tujuan"]->id_wil))
        $('#id_tujuan').append('<option value="{{ $filter["tujuan"]->id_wil }}">{{ $filter["tujuan"]->nama_wil }}</option>');
        @endif
        
        function addHitung(id, vendor, asal, tujuan, harga){
            total = parseInt(total)+parseInt(harga);
            var rtotal = total.toLocaleString();
            $("#table-harga").append('<tr><td>'+vendor+'</td><td>'+asal+'</td><td>'+tujuan+'</td><td>'+parseInt(harga).toLocaleString()+'</td><td> <button class="btn btn-sm btn-danger btnDelete" onclick="goDelete('+harga+')"><i class="fa fa-times"> </i></button></td></tr>');
            $("#table-total").empty();
            $("#table-total").append('<tr><td colspan="4" class="text-right"><b>Total : </b></td><td class="text-right"><b> <label id="lbl-total">Rp. '+rtotal+' </label></b></td></tr>');
        }
        
        $("#table-harga").on('click', '.btnDelete', function () {
            $(this).closest('tr').remove();
        });
        
        function goDelete(harga){
            total = parseInt(total)-parseInt(harga);
            var rtotal = total.toLocaleString();
            $("#table-total").empty();
            $("#table-total").append('<tr><td colspan="4" class="text-right"><b>Total : </b></td><td class="text-right"><b> <label id="lbl-total">Rp. '+rtotal+' </label></b></td></tr>');
        }
        
        function goFilter2(){
            var id_ven = $("#id_ven").val();
            var id_asal = $("#id_asal").val();
            var id_tujuan = $("#id_tujuan").val();
            var urls = '/getDataHargaVendor?id_ven='+id_ven+'&id_asal='+id_asal+'&id_tujuan='+id_tujuan;
            $("#html_table").DataTable(
            {
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: urls,
                "order": [[ 2, 'desc' ]],
                columns: [
                { data: 'nm_ven', name: 'nm_ven' },
                { data: 'nm_ven', name: 'nm_ven' },
                { data: 'wil_asal', name: 'wil_asal' },
                { data: 'wil_tujuan', name: 'wil_tujuan' },
                { data: 'harga', name: 'harga' },
                { data: 'keterangan', name: 'keterangan' },
                {"render": function ( data, type, row )
                {
                    var xx = row.id_asuransi;
                    var url_delete = `{{ url(Request::segment(1).'/'.'${row.id_harga}') }}`;
                    var url_edit = `{{ url(Request::segment(1).'/'.'${row.id_harga}'."/edit") }}`;
                    
                    var html = '<div class="dropdown"><button style="background-color: #00c5dc; color:white; font-weight: bold; text-align: center; border-color:#00c5dc; border-radius:5px" class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                        html += '<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">';
                            html += '<a class="dropdown-item" href="#" data-target="#modal-harga" data-toggle="modal" onclick="addHitung(`'+row.id_harga+'`, `'+row.nm_ven+'`, `'+row.wil_asal+'`, `'+row.wil_tujuan+'`, `'+row.harga+'`)"><i class="fa fa-dollar"></i> Hitung</a>';
                            html += '<a class="dropdown-item" href="'+url_edit+'"><i class="fa fa-pencil"></i> Edit</a>';
                            html += '<button class="dropdown-item" type="button" data-toggle="tooltip" data-placement="bottom" title="Hapus" onclick="CheckDelete(`'+url_delete+'`)"><span><i class="fa fa-times"></i></span> Hapus</button>';
                            html += '</div></div>'
                            return html;
                        }
                    },
                    ]
                }
                ).ajax.reload();
            }
            
            function goRefresh(){
                $("#id_ven").val("").trigger("change");
                $("#id_asal").val("").trigger("change");
                $("#id_tujuan").val("").trigger("change");

                var id_ven = $("#id_ven").val();
                var id_asal = $("#id_asal").val();
                var id_tujuan = $("#id_tujuan").val();
                var urls = '/getDataHargaVendor?id_ven='+id_ven+'&id_asal='+id_asal+'&id_tujuan='+id_tujuan;
                $("#html_table").DataTable(
                {
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: urls,
                    "order": [[ 2, 'desc' ]],
                    columns: [
                    { data: 'nm_ven', name: 'nm_ven' },
                    { data: 'nm_ven', name: 'nm_ven' },
                    { data: 'wil_asal', name: 'wil_asal' },
                    { data: 'wil_tujuan', name: 'wil_tujuan' },
                    { data: 'harga', name: 'harga' },
                    { data: 'keterangan', name: 'keterangan' },
                    {"render": function ( data, type, row )
                    {
                        var xx = row.id_asuransi;
                        var url_delete = `{{ url(Request::segment(1).'/'.'${row.id_harga}') }}`;
                        var url_edit = `{{ url(Request::segment(1).'/'.'${row.id_harga}'."/edit") }}`;
                        
                        var html = '<div class="dropdown"><button style="background-color: #00c5dc; color:white; font-weight: bold; text-align: center; border-color:#00c5dc; border-radius:5px" class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                            html += '<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">';
                                html += '<a class="dropdown-item" href="#" data-target="#modal-harga" data-toggle="modal" onclick="addHitung(`'+row.id_harga+'`, `'+row.nm_ven+'`, `'+row.wil_asal+'`, `'+row.wil_tujuan+'`, `'+row.harga+'`)"><i class="fa fa-dollar"></i> Hitung</a>';
                                html += '<a class="dropdown-item" href="'+url_edit+'"><i class="fa fa-pencil"></i> Edit</a>';
                                html += '<button class="dropdown-item" type="button" data-toggle="tooltip" data-placement="bottom" title="Hapus" onclick="CheckDelete(`'+url_delete+'`)"><span><i class="fa fa-times"></i></span> Hapus</button>';
                                html += '</div></div>'
                                return html;
                            }
                        },
                        ]
                    }
                    ).ajax.reload();
                }
            </script>
            @endsection