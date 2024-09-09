@extends('template.document2')

@section('data')
<form method="GET" action="{{ url("omzetvsbiayadmvendor") }}" enctype="multipart/form-data" id="form-select">
    @csrf
    <div class="row align-items-center">
        <div class="col-xl-12">
            <div class="form-group row">
                <div class="col-md-4">
                    <label style="font-weight: bold;">
                        Dari Tanggal
                    </label>
                    <input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="{{ isset($filter['dr_tgl']) ? $filter['dr_tgl'] : '' }}">
                </div>
                <div class="col-md-4">
                    <label style="font-weight: bold;">
                        Sampai Tanggal
                    </label>
                    <input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="{{ isset($filter['sp_tgl']) ? $filter['sp_tgl'] : '' }}">
                </div>
                <div class="col-md-4" style="padding-top:28px;">
                    <button class="btn btn-md btn-primary" data-toggle="tooltip" data-placement="bottom" title="Cari Data" style="margin-right : 5px"><span><i class="fa fa-search"></i></span> Cari</button>
                    <a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip" data-placement="bottom" title="Refresh Data"><span><i class="fa fa-refresh"></i></span> Refresh</a>
                    <a href="javascript:printDiv('print-js');" class="btn btn-md btn-info"><i class="fa fa-print"> Cetak</i></a>
                </div>
                
                <div class="col-md-12 mt-4" id="print-js">
                    <center>
                        <b style="font-weight:bold">Rekap Omzet VS Biaya DM VENDOR</b><br>
                        <b style="font-weight:bold">Periode : {{ $filter['dr_tgl'] }} s/d {{ $filter['sp_tgl'] }}</b>
                    </center>
                    <div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap;">
                        <table class="table table-bordered" id="biaya-table">
                            <thead style="background-color: grey; color : #ffff">
                                <th>No</th>
                                <th>Kode DM</th>
                                <th>Tgl Berangkat</th>
                                <th>Nama Vendor</th>
                                <th>STT</th>
                                <th>Kg</th>
                                <th>Kgv</th>
                                <th>M3</th>
                                <th>Koli</th>
                                <th colspan="3" class="text-center">Biaya</th>
                                <th>Satuan</th>
                                <th colspan="5" class="text-center">Total</th>
                            </thead>
                            <thead style="background-color: rgb(163, 160, 160); color : #ffff">
                                <th colspan="9"></th>
                                <th>STT</th>
                                <th>Umum</th>
                                <th>Vendor</th>
                                <th></th>
                                <th>Omzet</th>
                                <th>Biaya</th>
                                <th>%</th>
                                <th>RL</th>
                                <th>%</th>                                
                            </thead>
                            <tbody>
                                @foreach($data as $key => $value)
                                    <tr>
                                        <td>
                                            {{ $key+1 }}
                                        </td>
                                        <td>
                                            <a href="{{ url('omzetvsbiayadmvendor/'.$value->kode_dm.'?dr_tgl='.$filter["dr_tgl"].'&sp_tgl='.$filter["sp_tgl"]) }}">
                                                {{ $value->kode_dm }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $value->tgl_berangkat }}
                                        </td>
                                        <td>
                                            {{ $value->nm_ven }}
                                        </td>
                                        <td>
                                            @if(isset($count[$value->id_dm]->stt))
                                            {{ $count[$value->id_dm]->stt }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($satuan[$value->id_dm]->k))
                                            {{ $satuan[$value->id_dm]->k }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($satuan[$value->id_dm]->v))
                                            {{ $satuan[$value->id_dm]->v }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($satuan[$value->id_dm]->m))
                                            {{ $satuan[$value->id_dm]->m }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($count[$value->id_dm]->n_koli))
                                            {{ $count[$value->id_dm]->n_koli }}
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if(isset($bstt[$value->id_dm]->biaya))
                                            {{ toNumber($bstt[$value->id_dm]->biaya) }}
                                            @else
                                            0
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if(isset($bumum[$value->id_dm]->biaya))
                                            {{ toNumber($bumum[$value->id_dm]->biaya) }}
                                            @else
                                            0
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if(isset($bvendor[$value->id_dm]->biaya))
                                            {{ toNumber($bvendor[$value->id_dm]->biaya) }}
                                            @else
                                            0
                                            @endif
                                        </td>
                                        <td>
                                            @if($value->cara=='1')
                                            Berat
                                            @elseif($value=='2')
                                            Kgv
                                            @elseif($value=='3')
                                            M3
                                            @else
                                            Borongan
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if(isset($value->c_total))
                                            {{ toNumber($value->c_total) }}
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if(isset($value->c_pro))
                                            {{ toNumber($value->c_pro) }}
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $dev = divnum($value->c_pro, $value->c_total)*100;
                                            @endphp
                                           {{ number_format($dev, 2, ',', '.') }}
                                        </td>
                                        <td class="text-right">
                                            @php
                                                $rl = 0;
                                            @endphp
                                            @if(isset($value->c_pro))
                                            @php
                                                $rl = $value->c_total-$value->c_pro;
                                            @endphp
                                            {{ toNumber($rl) }}
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $dev2 = divnum($rl, $value->c_total)*100;
                                            @endphp
                                           {{ number_format($dev2, 2, ',', '.') }}
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
</div>
</form>
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