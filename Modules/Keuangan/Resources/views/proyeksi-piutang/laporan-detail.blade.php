@extends('template.document2')

@section('data')
<div class="row">
    <div class="col-md-12 text-right">
        <a href="javascript:printDiv('print-js');" class="btn btn-sm btn-primary"><i class="fa fa-print"> Cetak</i></a>
        <a class="btn btn-sm btn-warning" href="{{ url("repproyeksipiutang?tahun=".$filter["tahun"]."&bulan=".$filter["bulan"]) }}"><i class="fa fa-reply"></i> Kembali </a>
    </div>

    <div class="col-md-12" id="print-js">
        @php
        $tgl_awal = date("Y-m-d", strtotime($filter["tahun"]."-".$filter["bulan"]."-"."01"));
        $dates  = date("Y-m-d", strtotime($filter["tahun"]."-".$filter["bulan"]));
        $tgl_akhir = date("Y-m-t", strtotime($dates));
        @endphp
        <center>
            <b style="font-weight:bold">Detail Proyeksi Penagihan Piutang {{ $user->karyawan->nm_karyawan }}</b><br>
            <b style="font-weight:bold">Periode : {{ $tgl_awal }} s/d {{ $tgl_akhir }}</b>
        </center>

        <table class="table table-responsive table-stripped">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>STT</th>
                    <th>Pengirim</th>
                    <th>Omzet</th>
                    <th>Proyeksi</th>
                    <th>Bayar</th>
                    <th>No. Invoice</th>
                    <th>Tgl. Inv</th>
                    <th>J. Tempo</th>
                    <th>Piutang Akhir</th>
                    <th>Marketing</th>
                </tr>
            </thead>
            <tbody>
                @php
                $tomzet =0;
                $tproyeksi =0;
                $tbayar =0;
                $takhir = 0;
                @endphp
                @foreach($data as $key => $value)
                @php
                $piutang = 0;
                @endphp
                <tr>
                    <td>{{ $filter["bulan"] }}</td>
                    <td>{{ $filter["tahun"] }}</td>
                    <td>{{ $value->kode_stt }}</td>
                    <td>{{ $value->nm_pelanggan }}</td>
                    <td class="text-right">{{ toNumber($value->c_total) }}</td>
                    <td class="text-right">{{ toNumber($value->piutang) }}</td>
                    <td class="text-right">{{ toNumber($value->n_bayar) }}</td>
                    <td>
                        @if(isset($invoice[$value->id_stt]->kode_invoice))
                        {{ $invoice[$value->id_stt]->kode_invoice }}
                        @endif
                    </td>
                    <td>
                        @if(isset($invoice[$value->id_stt]->tgl))
                        {{ $invoice[$value->id_stt]->tgl }}
                        @endif
                    </td>
                    <td>
                        @if(isset($invoice[$value->id_stt]->inv_j_tempo))
                        {{ $invoice[$value->id_stt]->inv_j_tempo }}
                        @endif
                    </td>
                    @php
                    $piutang = $value->c_total-$value->n_bayar;
                    @endphp
                    <td class="text-right">{{ toNumber($piutang) }}</td>
                    <td>{{ $value->nm_marketing }}</td>
                    @php
                    $tomzet += $value->c_total;
                    $tproyeksi += $value->piutang;
                    $tbayar += $value->n_bayar;
                    $takhir += $piutang;
                    @endphp
                </tr>
                @endforeach
                <tr>
                    <td colspan="4" class="text-right"><b style="font-weight:bold">Total :</b> </td>
                    <td class="text-right"><b style="font-weight:bold">{{ toNumber($tomzet) }}</b> </td>
                    <td class="text-right"><b style="font-weight:bold">{{ toNumber($tproyeksi) }}</b> </td>
                    <td class="text-right"><b style="font-weight:bold">{{ toNumber($tbayar) }}</b> </td>
                    <td colspan="3"></td>
                    <td class="text-right"><b style="font-weight:bold">{{ toNumber($takhir) }}</b> </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <br>
    </div>
</div>
<textarea id="printing-css" style="display:none;">
    @media print{
        @page
        {
            size: A4 landscape;
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
