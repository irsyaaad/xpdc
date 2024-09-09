@extends('template.document2')

@section('data')
@if(Request::segment(1)=="omsetbytipekirim" and Request::segment(2)==null)
@include("template.filter")

<table class="table table-responsive table-striped table-sm" id="html_table" width="100%">
	<thead style="background-color: grey; color : #ffff">
		<tr>
            <th>No</th>
            <th>ID Tipe Kirim</th>
            <th>Nama Tipe Kirim</th>
            <th>STT</th>
            <th>Berat</th>
            <th>Volume</th>
            <th>Koli</th>
            <th>Omset</th>
            <th>Tujuan</th>
		</tr>
    </thead>
    <tbody>
        @foreach($data as $key => $value)
        <tr>
            <td>{{$key+1}}</td>
            <td>@if(isset($value->id_tipe_kirim)){{$value->id_tipe_kirim}}@endif</td>
            <td>@if(isset($value->nm_tipe_kirim))<a href="{{ url(Request::segment(1)."/".$value->id_tipe_kirim."/show") }}" style="color:black;">{{$value->nm_tipe_kirim}}</a>@endif</td>
            <td>@if(isset($value->stt)){{$value->stt}}@endif</td>
            <td>@if(isset($value->berat)){{$value->berat}}@endif</td>
            <td>@if(isset($value->volume)){{$value->volume}}@endif</td>
            <td>@if(isset($value->koli)){{$value->koli}}@endif</td>
            <td>@if(isset($value->omset))Rp. {{ number_format($value->omset, 0, ',', '.') }}@endif</td>
            <td>@if(isset($value->nama_wil)){{$value->nama_wil}}@endif</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endif
@if(Request::segment(1)=="omsetbytipekirim" and Request::segment(2)!=null)
<table class="table table-responsive table-striped table-sm" id="html_table" width="100%">
	<thead style="background-color: grey; color : #ffff">
		<tr>
            <th>No</th>
            <th>Nama Tipe Kirim</th>
            <th>STT</th>
            <th>Tanggal Masuk</th>
            <th>Nama Pengirim</th>
            <th>Tujuan</th>
            <th>Berat</th>
            <th>Volume</th>
            <th>Koli</th>
            <th>Omset</th>
		</tr>
    </thead>
    <tbody>
        @php
            $berat = 0;
            $volume = 0;
            $koli = 0;
            $omset = 0;
        @endphp
        @foreach($data as $key => $value)
        <tr>
            <td>{{$key+1}}</td>
            <td>@if(isset($value->nm_tipe_kirim)){{$value->nm_tipe_kirim}}@endif</td>
            <td>@if(isset($value->id_stt)){{$value->id_stt}}@endif</td>
            <td>@if(isset($value->tgl_masuk)){{$value->tgl_masuk}}@endif</td>
            <td>@if(isset($value->pengirim_nm)){{$value->pengirim_nm}}@endif</td>
            <td>@if(isset($value->nama_wil)){{$value->nama_wil}}@endif</td>
            <td>@if(isset($value->n_berat)){{$value->n_berat}}
                @php $berat+=$value->n_berat @endphp
            @endif</td>
            <td>@if(isset($value->n_volume)){{$value->n_volume}}
                @php $volume+=$value->n_volume @endphp
            @endif</td>
            <td>@if(isset($value->n_koli)){{$value->n_koli}}
                @php $koli+=$value->n_koli @endphp
            @endif</td>
            <td>@if(isset($value->c_total))Rp. {{ number_format($value->c_total, 0, ',', '.') }}
                @php $omset+=$value->c_total @endphp
            @endif</td>
        </tr>
        @endforeach
        <tr >
            <td style="border-top:1px solid black" class="text-center" colspan=6>Total</td>
            <td style="border-top:1px solid black">{{$berat}}</td>
            <td style="border-top:1px solid black">{{$volume}}</td>
            <td style="border-top:1px solid black">{{$koli}}</td>
            <td style="border-top:1px solid black">Rp. {{ number_format($omset, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

@endif

@if(Request::segment(1)=="omsetbylayanan" and Request::segment(2)==null)
@include("template.filter")

<table class="table table-responsive table-striped table-sm" id="html_table" width="100%">
	<thead style="background-color: grey; color : #ffff">
		<tr>
            <th>No</th>
            <th>Nama Layanan</th>
            <th>STT</th>
            <th>Berat</th>
            <th>Volume</th>
            <th>Koli</th>
            <th>Omset</th>
            <th>Tujuan</th>
		</tr>
    </thead>
    <tbody>
        @foreach($data as $key => $value)
        <tr>
            <td>{{$key+1}}</td>
            <td>@if(isset($value->nm_layanan))<a href="{{ url(Request::segment(1)."/".$value->id_layanan."/show") }}" style="color:black;">{{$value->nm_layanan}}</a>@endif</td>
            <td>@if(isset($value->stt)){{$value->stt}}@endif</td>
            <td>@if(isset($value->berat)){{$value->berat}}@endif</td>
            <td>@if(isset($value->volume)){{$value->volume}}@endif</td>
            <td>@if(isset($value->koli)){{$value->koli}}@endif</td>
            <td>@if(isset($value->omset))Rp. {{ number_format($value->omset, 0, ',', '.') }}@endif</td>
            <td>@if(isset($value->nama_wil)){{$value->nama_wil}}@endif</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endif
@if(Request::segment(1)=="omsetbylayanan" and Request::segment(2)!=null)
<table class="table table-responsive table-striped table-sm" id="html_table" width="100%">
	<thead style="background-color: grey; color : #ffff">
		<tr>
            <th>No</th>
            <th>Nama Tipe Kirim</th>
            <th>STT</th>
            <th>Tanggal Masuk</th>
            <th>Nama Pengirim</th>
            <th>Tujuan</th>
            <th>Berat</th>
            <th>Volume</th>
            <th>Koli</th>
            <th>Omset</th>
		</tr>
    </thead>
    <tbody>
        @php
            $berat = 0;
            $volume = 0;
            $koli = 0;
            $omset = 0;
        @endphp
        @foreach($data as $key => $value)
        <tr>
            <td>{{$key+1}}</td>
            <td>@if(isset($value->nm_layanan)){{$value->nm_layanan}}@endif</td>
            <td>@if(isset($value->id_stt)){{$value->id_stt}}@endif</td>
            <td>@if(isset($value->tgl_masuk)){{$value->tgl_masuk}}@endif</td>
            <td>@if(isset($value->pengirim_nm)){{$value->pengirim_nm}}@endif</td>
            <td>@if(isset($value->nama_wil)){{$value->nama_wil}}@endif</td>
            <td>@if(isset($value->n_berat)){{$value->n_berat}}
                @php $berat+=$value->n_berat @endphp
            @endif</td>
            <td>@if(isset($value->n_volume)){{$value->n_volume}}
                @php $volume+=$value->n_volume @endphp
            @endif</td>
            <td>@if(isset($value->n_koli)){{$value->n_koli}}
                @php $koli+=$value->n_koli @endphp
            @endif</td>
            <td>@if(isset($value->c_total))Rp. {{ number_format($value->c_total, 0, ',', '.') }}
                @php $omset+=$value->c_total @endphp
            @endif</td>
        </tr>
        @endforeach
        <tr >
            <td style="border-top:1px solid black" class="text-center" colspan=6>Total</td>
            <td style="border-top:1px solid black">{{$berat}}</td>
            <td style="border-top:1px solid black">{{$volume}}</td>
            <td style="border-top:1px solid black">{{$koli}}</td>
            <td style="border-top:1px solid black">Rp. {{ number_format($omset, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

@endif
@endsection
