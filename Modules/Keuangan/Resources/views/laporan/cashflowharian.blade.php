@extends('template.document2')

@section('data')
<style>
    th{
        text-align: center;
        padding: 5px 20px 5px 20px !important;
        vertical-align: center;
    }
    td{
        padding: 5px 20px 5px 20px !important;
        vertical-align: center;
    }
</style>
<form method="GET" action="{{ url("cashflowharian") }}" enctype="multipart/form-data" id="form-select">
    @csrf
    <div class="row">
        <div class="col-md-4">
            <label style="font-weight: bold;">
                Dari Tanggal
            </label>
            <input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="@if(isset($filter["dr_tgl"])){{ $filter["dr_tgl"] }}@endif">
        </div>
        
        <div class="col-md-4">
            <label style="font-weight: bold;">
                Sampai Tanggal
            </label>
            <input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="@if(isset($filter["sp_tgl"])){{ $filter["sp_tgl"] }}@endif">
        </div>
        
        <div class="col-md-4 text-right" style="margin-top: 25px">
            <button type="submit" class="btn btn-md btn-primary" class="btn btn-primary" title="Cari Data">
                <i class="fa fa-search"></i> Cari
            </button>
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip" data-placement="top" title="Refresh">
                <i class="fa fa-refresh"></i> Reset
            </a>
            <div class="dropdown d-inline-block">
                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Cetak
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a href="@if(isset($filter["cetak"])){{ $filter["cetak"] }}@endif" class="dropdown-item" target="_blank">
                        <i class="fa fa-file-pdf-o"></i>Pdf
                    </a>
                    <a href="@if(isset($filter["excel"])){{ $filter["excel"] }}@endif" class="dropdown-item" >
                        <i class="fa fa-print"></i>Excel
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-3">
            <label style="font-weight: bold;">
                Pilih Perkiraan
            </label>
            <select class="form-control" name="id_ac" id="id_ac">
                <option value="">Pilih Perkiraan</option>
                @foreach ($filter_ac as $item)
                    <option value="{{ $item->id_ac }}" {{ isset($filter['id_ac']) && $filter['id_ac'] == $item->id_ac ? 'selected' : '' }}>{{ $item->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12 mt-3">
            <label style="font-weight: bold;">
                Pilih User
            </label>
            <select class="form-control" name="id_user" id="id_user">
                <option value="">Pilih User</option>
                @foreach ($users as $item)
                    <option value="{{ $item->id_user }}" {{ isset($filter['id_user']) && $filter['id_user'] == $item->id_user ? 'selected' : '' }}>{{ $item->nm_user }}</option>
                @endforeach
            </select>
        </div>
    </div>
	<br>
	<input type="text" class="form-control" id="search" placeholder="Type to search">
        <div class="table-responsive mt-3" style="display: block; overflow-x: auto;white-space: nowrap;">
            <table class="table table-sm table-hover" id="jurnal-table">
                <thead style="background-color: grey; color : #ffff">
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No. Detail</th>
                    <th>Keterangan</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                    <th>Nama User</th>
                </thead>                
                <tbody>
                    @foreach($acperush as $key => $value)
                        @php
                            $sub_total_d = 0;
                            $sub_total_k = 0;
                        @endphp
                        @if (isset($cashout[$value->id_ac]) || isset($cashin[$value->id_ac]))
                            <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                                <td colspan=7>{{ strtoupper($value->nama) }}</td>
                            </tr>                        
                            <tr>
                                <td colspan=7>CASH IN</td>
                            </tr>                            
                            @if(isset($cashin[$value->id_ac]))
                                @php 
                                    $sub_total_d = 0;
                                    $sub         = 0;
                                    $nomer       = 1;
                                @endphp
                                @foreach($cashin[$value->id_ac] as $key2 => $value2)
                                    <tr>
                                        <td>{{ $nomer++ }}</td>
                                        <td>{{ dateindo($value2->tgl) }}</td>
                                        <td>{{ $value2->id_detail }}</td>
                                        <td>{{ $value2->keterangan }}</td>
                                        <td class="text-right">{{ number_format($value2->nominal, 0, ',', '.') }}</td>
                                        <td class="text-right">0</td>
                                            @php $sub_total_d += $value2->nominal @endphp
                                        <td>{{ strtoupper($value2->nm_user) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="text-right">{{ toNumber($sub_total_d) }}</td>
                                    <td class="text-right">0</td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="7">-</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan=7>CASH OUT</td>
                            </tr>
                            @if(isset($cashout[$value->id_ac]))
                                @php 
                                    $sub_total_k = 0;
                                    $sub         = 0;
                                    $nomer       = 1;
                                @endphp
                                @foreach($cashout[$value->id_ac] as $key2 => $value2)
                                    <tr>
                                        <td>{{ $nomer++ }}</td>
                                        <td>{{ dateindo($value2->tgl) }}</td>
                                        <td>{{$value2->id_detail}}</td>
                                        <td>{{$value2->keterangan}}</td>
                                        <td class="text-right">0</td>
                                        <td class="text-right">{{ number_format($value2->nominal, 0, ',', '.') }}</td>
                                        @php $sub_total_k += $value2->nominal @endphp
                                        <td>{{ strtoupper($value2->nm_user) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">{{ toNumber($sub_total_k) }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="7">-</td>
                                </tr>
                            @endif
                            <tr class="tr-bold">
                                <td colspan="4" rowspan="2" class="text-center" style="vertical-align: middle">TOTAL {{ strtoupper($value->nama) }}</td> 
                                <td class="text-right">{{ number_format($sub_total_d, 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($sub_total_k, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="tr-bold">
                                <td colspan="2" class="text-right">{{ number_format($sub_total_d - $sub_total_k, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>   
        </div>
    </div>
</form>
<script>
    var $rows = $('#jurnal-table tr');
        $('#search').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
            
            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
        $('#id_ac').select2();
        $('#id_user').select2();
</script>
@endsection