@extends('template.document')
@section('data')
<form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
    @csrf
    <input type="hidden" name="_method" value="GET">
    <div class="row">
        <div class="form-group col-md-3">
            <label for="level">
                <b>Tahun</b>
            </label>
            <select class="form-control m-input m-input--square" name="tahun" id="tahun">
                @foreach($tahun as $key => $value)
                <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="level">
                <b>Bulan</b>
            </label>
            <select class="form-control m-input m-input--square" name="bulan" id="bulan">
                @foreach($bulan as $key => $value)
                <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3" style="margin-top: 25px">
            <a href="javascript:printDiv('print-js');" class="btn btn-md btn-primary"><i class="fa fa-print"> Cetak</i></a>
        </div>
        <div class="col-md-12" id="print-js">
            @php
            $tgl_awal = date("Y-m-d", strtotime($filter["tahun"]."-".$filter["bulan"]."-"."01"));
            $dates  = date("Y-m-d", strtotime($filter["tahun"]."-".$filter["bulan"]));
            $tgl_akhir = date("Y-m-t", strtotime($dates));
            @endphp
            <center>
                <b style="font-weight:bold">Rekap Proyeksi Penagihan Piutang</b><br>
                <b style="font-weight:bold">Periode : {{ $tgl_awal }} s/d {{ $tgl_akhir }}</b>
            </center>
            <table class="table table-responsive table-stripped mt-2">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>Admin</th>
                        <th>Bulan</th>
                        <th>STT</th>
                        <th>Omzet</th>
                        <th>Proyeksi</th>
                        <th>W1</th>
                        <th>W2</th>
                        <th>W3</th>
                        <th>W4</th>
                        <th>W5</th>
                        <th>STT B</th>
                        <th>Bayar</th>
                        <th>%</th>
                        <th>Deviasi</th>
                        <th>%</th>
                    </tr>
                </thead>
                <thead style="background-color: rgb(163, 162, 162); color : #ffff">
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>12/5*100</th>
                        <th>14</th>
                        <th>(14)/5*100</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach($admin as $key => $value)
                    <tr>
                        @php
                        $pro = 0;
                        $omzet = 0;
                        @endphp
                        <td><a href="{{ url("repproyeksipiutang/".$value->id_user."/reppdetail?tahun=".$filter["tahun"]."&bulan=".$filter["bulan"]) }}">{{ $value->nm_karyawan }}</a></td>
                        <td>{{ $filter["bulan"] }}</td>
                        <td>@if(isset($proyeksi[$value->id_user]->stt)){{ $proyeksi[$value->id_user]->stt }}@endif</td>
                        <td>@if(isset($proyeksi[$value->id_user]->omzet))
                            {{ toNumber($proyeksi[$value->id_user]->omzet) }}
                            @php
                            $omzet += $proyeksi[$value->id_user]->omzet;
                            @endphp
                            @else
                            0
                            @endif
                        </td>
                        <td>
                            @if(isset($proyeksi[$value->id_user]->proyeksi))
                            {{ toNumber($proyeksi[$value->id_user]->proyeksi) }}
                            @php
                            $pro += $proyeksi[$value->id_user]->proyeksi;
                            @endphp
                            @else
                            0
                            @endif
                        </td>
                        @php
                        $total = 0;
                        @endphp
                        <td>@if(isset($week1[$value->id_user]->bayar))
                            {{ toNumber($week1[$value->id_user]->bayar) }}
                            @php
                            $total += $week1[$value->id_user]->bayar;
                            @endphp
                            @else
                            0 
                            @endif
                        </td>
                        <td>@if(isset($week2[$value->id_user]->bayar))
                            {{ toNumber($week2[$value->id_user]->bayar) }}
                            @php
                            $total += $week2[$value->id_user]->bayar;
                            @endphp
                            @else
                            0 
                            @endif
                        </td>
                        <td>@if(isset($week3[$value->id_user]->bayar))
                            {{ toNumber($week3[$value->id_user]->bayar) }}
                            @php
                            $total += $week3[$value->id_user]->bayar;
                            @endphp
                            @else
                            0 
                            @endif
                        </td>
                        <td>@if(isset($week4[$value->id_user]->bayar))
                            {{ toNumber($week4[$value->id_user]->bayar) }}
                            @php
                            $total += $week4[$value->id_user]->bayar;
                            @endphp
                            @else
                            0 
                            @endif
                        </td>
                        <td>@if(isset($week5[$value->id_user]->bayar))
                            {{ toNumber($week5[$value->id_user]->bayar) }}
                            @php
                            $total += $week5[$value->id_user]->bayar;
                            @endphp
                            @else
                            0 
                            @endif
                        </td>
                        <td>@if(isset($count[$value->id_user]->stt))
                            {{ toNumber($count[$value->id_user]->stt) }}
                            @endif
                        </td>
                        <td>{{ toNumber($total) }}</td>
                        <td>
                            @php
                            $persentase = divnum($total, $pro)*100;
                            @endphp
                            {{ number_format($persentase, 2, ',', '.') }}
                        </td>
                        <td>
                            {{ toNumber($pro-$total) }}
                            @php
                            $devia = $pro-$total;
                            @endphp
                        </td>
                        <td>
                            @php
                            $dev = divnum($devia, $pro)*100;
                            @endphp
                            {{ number_format($dev, 2, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <div class="mt-2">
                <p style="font-size: 10pt">- Proyeksi penagihan, dientri per bulan, jika ada 2 proyeksi pada bulan yang sama akan diakumulasi 
                    <br>
                    - Bayar adalah Pembayaran STT proyeksi sesuai dengan range bulan proyeksi <br>
                    - W1 = 1-7, W2 = 8-14, W3=15-21, W4=22-28, W5=>29
                </p>
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

    @if(isset($filter["tahun"]))
    $("#tahun").val("{{ $filter['tahun'] }}");
    @endif

    @if(isset($filter["bulan"]))
    $("#bulan").val("{{ $filter['bulan'] }}");
    @endif
    
    $("#tahun").on("change", function() {
        $("#form-select").submit();
    });
    
    $("#bulan").on("change", function() {
        $("#form-select").submit();
    });
    
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
