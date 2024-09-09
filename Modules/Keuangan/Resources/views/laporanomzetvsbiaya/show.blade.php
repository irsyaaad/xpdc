@extends('template.document2')

@section('data')
<div class="row align-items-center">
    <div class="col-xl-12">
        <div class="form-group row">
            <div class="col-md-12 text-right">
                <a href="javascript:printDiv('print-js');" class="btn btn-md btn-info"><i class="fa fa-print"> Cetak</i></a>
                <a href="{{ $back }}" class="btn btn-md btn-warning" data-toggle="tooltip" data-placement="bottom" title="Kembali">
                    <span><i class="fa fa-reply"></i></span> Kembali
                </a>
            </div>
            
            <div class="col-md-12 mt-4" id="print-js">
                <center>
                    <b style="font-weight:bold">Biaya DM VENDOR </b><br>
                    <b style="font-weight:bold">No DM : {{ $data->kode_dm }}</b>
                </center>
                
                <div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap;">
                    <table class="table table-bordered" id="biaya-table">
                        <thead style="background-color: grey; color : #ffff">
                            <th>No</th>
                            <th>ID Biaya DM</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Biaya</th>
                            <th>Bayar</th>
                            <th>Akun Debit</th>
                            <th>Akun Kredit</th>
                            <th>User</th>
                            <th>Updated</th>
                        </thead>
                        <tbody>
                            @foreach($detail as $key => $value)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>
                                    {{ $value->id_pro_bi }}
                                </td>
                                <td>
                                    {{ $value->tgl_posting }}
                                </td>
                                <td>
                                    {{ $value->keterangan }}
                                </td>
                                <td>
                                    {{ $value->nominal }}
                                </td>
                                <td>
                                    {{ $value->n_bayar }}
                                </td>
                                <td>
                                    {{ $value->debit." ( ".$value->ac4_debit." )" }}
                                </td>
                                <td>
                                    {{ $value->kredit." ( ".$value->ac4_kredit." )" }}
                                </td>
                                <td>
                                    {{ $value->nm_user }}
                                </td>
                                <td>
                                    {{ $value->updated_at }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
</div>

<textarea id="printing-css" style="display:none;">
    @media print{
        @page
        {
            size: A4 landscape;
            color:black;
        }
    }
    body {
        font-family: sans-serif !important;
        line-height: 20px;
        font-size: 15px;
    }
    table {
        margin: auto;
        font-family: "Arial";
        font-size: 12px;
        border-collapse: collapse;
        font-size: 13px;
    }
    table th, 
    table td {
        border-top: 1px solid black;
        border-bottom: 1px solid  black;
        border-left: 1px solid  black;
        padding: 5px 14px;
    }
    table th, 
    table td:last-child {
        border-right: 1px solid  black;
    }
    table td:first-child {
        border-top: 1px solid  black;
    }
    
    table thead th {
        color: black;
    }
    
    table tbody td {
        color: black;
    }
</textarea>
<iframe id="printing-frame" name="print_frame" src="about:blank" style="display:none;"></iframe>

@endsection

@section('script')
<script>
    
    function printDiv(elementId) {
        var a = document.getElementById('printing-css').value;
        var b = document.getElementById(elementId).innerHTML;
        window.frames["print_frame"].document.title = document.title;
        window.frames["print_frame"].document.body.innerHTML = '<style>' + a + '</style>' + b;
        window.frames["print_frame"].window.focus();
        window.frames["print_frame"].window.print();
    }
</script>
@endsection