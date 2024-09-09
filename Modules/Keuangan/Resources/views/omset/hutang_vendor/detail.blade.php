@extends('template.document2')

@section('data')

<style>
    th {
        text-align: center;
    }
</style>
<div class="text-right">
    <a href="{{ $back }}" class="btn btn-sm btn-warning">
        <i class="fa fa-reply"></i>	Kembali
    </a>
</div>
<table class="table">
    <tr>
        <td>Nama Vendor</td>
        <td>:</td>
        <td>{{ $vendor->nm_ven }}</td>
    </tr>
    <tr>
        <td>Alamat Vendor</td>
        <td>:</td>
        <td>{{ $vendor->alm_ven }}</td>
    </tr>
    <tr>
        <td>Telp Vendor</td>
        <td>:</td>
        <td>{{ $vendor->telp_ven }}</td>
    </tr>
</table>
<div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
    <table class="table table-responsive table-sm table-striped" width="100%">
        <thead style="background-color: grey; color : #ffff">
            <tr>
                <th>No</th>
                <th>Kode DM</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Biaya Stt</th>
                <th>Biaya Vendor</th>
                <th>Hutang</th>
                <th>Bayar</th>
                <th>Akhir</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_hutang = 0;
                $total_bayar = 0;
                $total_akhir = $saldo_awal->saldo_awal;
            @endphp
            <tr>
                <td class="text-center" colspan="8">Saldo Awal</td>
                <td>{{ $saldo_awal->saldo_awal }}</td>
            </tr>
            @foreach ($data as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ strtoupper($value->kode_dm) }}</td>
                    <td>{{ empty($value->tgl_posting)? '(Bayar) ' . $value->tgl_bayar : '(Posting) ' . $value->tgl_posting }}</td>
                    <td>{{ $value->keterangan }}</td>
                    <td class="text-right">{{ number_format($value->stt, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($value->vendor, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($value->hutang, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($value->bayar, 0, ',', '.') }}</td>
                    @php
                        $total_hutang += $value->hutang;
                        $total_bayar += $value->bayar;
                        $total_akhir += ($value->hutang - $value->bayar);
                    @endphp
                    <td class="text-right">{{ number_format($total_akhir, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6"></td>
                <td class="text-right">{{ number_format($total_hutang, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($total_bayar, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($total_akhir, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection