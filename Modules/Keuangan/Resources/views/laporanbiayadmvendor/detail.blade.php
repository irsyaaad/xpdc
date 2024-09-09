@extends('template.document2')

@section('data')
<div class="text-right">
    <a href="{{ $back }}" class="btn btn-sm btn-warning">
        <i class="fa fa-reply"></i>	Kembali
    </a>
</div>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <td width="30%">No. DM</td>
                <td width="2%"><b>:</b></td>
                <td>
                    <b>
                        @if(isset($data->kode_dm)){{ $data->kode_dm }}@endif
                    </b>
                </td>
            </tr>
            <tr>
                <td width="30%">Perusahaan Asal</td>
                <td width="2%"><b>:</b></td>
                <td>
                    <b>
                        @if(isset($data->perush_asal->nm_perush)){{ strtoupper($data->perush_asal->nm_perush) }}@endif
                    </b>
                </td>
            </tr>
            <tr>
                <td width="30%">Vendor Tujuan</td>
                <td width="2%"><b>:</b></td>
                <td>
                    <b>
                        @if(isset($data->perush_asal->nm_perush)){{ strtoupper($data->vendor->nm_ven) }}@endif
                    </b>
                </td>
            </tr>
            <tr>
                <td width="30%">Kota Tujuan</td>
                <td width="2%"><b>:</b></td>
                <td>
                    <b>
                        @if(isset($data->wilayah_tujuan->nama_wil)){{ strtoupper($data->wilayah_tujuan->nama_wil) }}@endif
                    </b>
                </td>
            </tr>
            <tr>
                <td width="30%">Layanan</td>
                <td width="2%"><b>:</b></td>
                <td>
                    <b>
                        @if(isset($data->layanan->nm_layanan)){{ strtoupper($data->layanan->nm_layanan) }}@endif
                    </b>
                </td>
            </tr>
            <tr>
                <td width="30%">Status</td>
                <td width="2%"><b>:</b></td>
                <td>
                    <b>
                        @if(isset($data->status->nm_status))
                        {{ strtoupper($data->status->nm_status) }}
                        @endif
                    </b>
                </td>
            </tr>
            <tr>
                <td width="30%">Keterangan</td>
                <td width="2%"><b>:</b></td>
                <td>
                    <b>
                        {{ strtoupper($data->info) }}
                    </b>
                </td>
            </tr>
            
        </thead>
    </table>
</div>
<div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap;">
    <table class="table table-bordered table-striped" id="biaya-table">
        <thead style="background-color: grey; color : #ffff">
            <th>No</th>
            <th>ID Biaya</th>
            <th>Tgl Posting</th>
            <th>Group Biaya</th>
            <th>Keterangan</th>
            <th>Biaya</th>
            <th>Bayar</th>
            <th>Sisa</th>
        </thead>
        <tbody>
            @php
            $total_biaya = 0;
            $total_bayar = 0;
            $total_sisa  = 0;
            @endphp
            @foreach($biaya as $key => $value)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{ $value->id_pro_bi }}</td>
                <td>{{ dateindo($value->tgl_posting) }}</td>
                <td>{{ $value->nm_biaya_grup }}</td>
                <td>{{ $value->keterangan }}</td>
                <td class="text-right">@if(isset($value->biaya)){{ number_format($value->biaya, 0, ',', '.') }} @else 0 @endif</td>
                <td class="text-right">@if(isset($value->bayar)){{ number_format($value->bayar, 0, ',', '.') }} @else 0 @endif</td>
                <td class="text-right">{{ isset($value->sisa) ? number_format($value->sisa, 0, ',', '.') : '0' }}</td>
            </tr>
            @php
            $total_biaya += $value->biaya;
            $total_bayar += $value->bayar;
            $total_sisa  += $value->sisa;
            @endphp
            @endforeach
            <tr style="background-color: grey; color : #ffff">
                <td colspan=5 class="text-center">Total</td>
                <td class="text-right">{{ number_format($total_biaya, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($total_bayar, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($total_sisa, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

