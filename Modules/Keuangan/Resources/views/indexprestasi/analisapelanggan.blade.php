@extends('template.document2')

@section('data')
@include("template.filter2")
<table class="table table-responsive table-sm" id="html_table" width="100%">
	<thead style="background-color: grey; color : #ffff">
		<tr>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">No</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Marketing</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">STT</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Koli</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Omset</th>
            <th colspan="3" class="text-center">Pelanggan</th>
            <th colspan="4" class="text-center">Jenis</th>
        </tr>
        <tr>
            <th class="text-center">Jumlah</th>
            <th class="text-center">Aktif</th>
            <th class="text-center"> % </th>
            <th class="text-center">Baru</th>
            <th class="text-center"> % </th>
            <th class="text-center">Reorder</th>
            <th class="text-center"> % </th>
        </tr>
    </thead>
    <tbody>
        @foreach($marketing as $key => $value)
            <tr>
                <td class="text-center">{{$key+=1}}</td>
                <td class="text-center">{{strtoupper($value->nm_marketing)}}</td>
                <td class="text-center">
                    @if(isset($data[$value->id_marketing]['total_stt'])) 
                        {{ $data[$value->id_marketing]['total_stt'] }} 
                    @endif
                </td>
                <td class="text-center">
                    @if(isset($data[$value->id_marketing]['total_koli'])) 
                        {{ $data[$value->id_marketing]['total_koli'] }} 
                    @endif
                </td>
                <td class="text-center">
                    @if(isset($data[$value->id_marketing]['total_omset'])) 
                        Rp. {{number_format($data[$value->id_marketing]['total_omset'], 0, ',', '.')}}
                    @endif
                </td>
                <td class="text-center">
                    @if(isset($data[$value->id_marketing]['jml_pelanggan'])) 
                        {{ $data[$value->id_marketing]['jml_pelanggan'] }} 
                    @endif
                </td>
                <td class="text-center">
                    @if(isset($data[$value->id_marketing]['aktif'])) 
                        {{ $data[$value->id_marketing]['aktif'] }} 
                    @endif
                </td>
                <td class="text-center">
                    @if(isset($data[$value->id_marketing]['aktif']) and $data[$value->id_marketing]['aktif'] > 0 and isset($data[$value->id_marketing]['jml_pelanggan']) and $data[$value->id_marketing]['jml_pelanggan'] > 0) 
                        {{round(($data[$value->id_marketing]['aktif']/$data[$value->id_marketing]['jml_pelanggan']) * 100,2)}} %
                    @endif
                </td>
                <td class="text-center">
                    @if(isset($data[$value->id_marketing]['baru'])) 
                        {{ $data[$value->id_marketing]['baru'] }} 
                    @endif
                </td>
                <td class="text-center">
                    @if(isset($data[$value->id_marketing]['baru']) and $data[$value->id_marketing]['baru'] > 0 and isset($data[$value->id_marketing]['jml_pelanggan']) and $data[$value->id_marketing]['jml_pelanggan'] > 0) 
                        {{round(($data[$value->id_marketing]['baru']/$data[$value->id_marketing]['jml_pelanggan']) * 100,2)}} %
                    @endif
                </td>
                <td class="text-center">
                    @if(isset($data[$value->id_marketing]['reorder'])) 
                        {{ $data[$value->id_marketing]['reorder'] }} 
                    @endif
                </td>
                <td class="text-center">
                    @if(isset($data[$value->id_marketing]['reorder']) and $data[$value->id_marketing]['reorder'] > 0 and isset($data[$value->id_marketing]['jml_pelanggan']) and $data[$value->id_marketing]['jml_pelanggan'] > 0) 
                        {{round(($data[$value->id_marketing]['reorder']/$data[$value->id_marketing]['jml_pelanggan']) * 100,2)}} %
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<hr>
<ul>
    <li>Baru, Jika -12 Bulan kebelakang tidak pernah melakukan pengiriman dari tanggal kirim pada range periode</li>
    <li>Reorder, Jika -12 Bulan kebelakang ada melakukan pengiriman dari tanggal kirim pada range periode</li>
    <li>Aktif, dihitung -36 Bulan kebelakang dari tanggal akhir periode</li>
</ul>
@endsection
