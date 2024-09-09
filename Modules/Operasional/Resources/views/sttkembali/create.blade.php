@if(Request::segment(2)=="create")
	<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('sttkembali') }}" enctype="multipart/form-data">
	@else
	<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('sttkembali', $data->id_stt_kembali) }}" enctype="multipart/form-data">
	{{ method_field("PUT") }} 
	@endif
@csrf
    <div class="row">        

        <div class="form-group col-md-6">
            <label for="tgl">
                <b>Tgl</b> <span class="span-required"> * </span>
            </label>
            
            <input type="date" class="form-control m-input m-input--square" maxlength="56" name="tgl" id="tgl" value="@if(isset($data->tgl)){{ $data->tgl }}@else {{ old("tgl") }}@endif" required="required">
            
            @if ($errors->has('tgl'))
            <label style="color: red">
                {{ $errors->first('tgl') }}
            </label>
            @endif
        </div>

        <div class="form-group col-md-6">
            <label for="status">
                <b>Status</b> <span class="span-required"> * </span>
            </label>
            <select class="form-control m-input m-input--square" name="status" id="status" required="required">
                @foreach ($status as $item)
                    <option value="{{ $item }}" @if (!empty($data->status) && $data->status == $item) selected @else {{ old("status") }} @endif>{{ $item }}</option>
                @endforeach
            </select>

            @if ($errors->has('status'))
            <label style="color: red">
                {{ $errors->first('status') }}
            </label>
            @endif
        </div>

        <div class="form-group col-md-6">
            <label for="petugas">
                <b>Petugas</b> <span class="span-required"> * </span>
            </label>
            
            <input type="text" class="form-control m-input m-input--square" maxlength="56" name="petugas" id="petugas" value="{{ Auth::user()->nm_user }}" readonly>
            
            @if ($errors->has('tgl'))
            <label style="color: red">
                {{ $errors->first('tgl') }}
            </label>
            @endif
        </div>

        <div class="form-group col-md-6">
            <label for="cabang_asal">
                <b>Cabang Asal</b> <span class="span-required"> * </span>
            </label>
            
            <input type="hidden" name="cabang_asal" id="cabang_asal" value="{{ Session('perusahaan')['id_perush'] }}">
            <input type="text" class="form-control m-input m-input--square" maxlength="56" name="cabang_asal" id="cabang_asal" value="{{ Session('perusahaan')['nm_perush'] }}" readonly>
            
            @if ($errors->has('tgl'))
            <label style="color: red">
                {{ $errors->first('tgl') }}
            </label>
            @endif
        </div>

        <div class="form-group col-md-6">
            <label for="cabang_tujuan">
                <b>Cabang Tujuan</b> <span class="span-required"> * </span>
            </label>
            
            <select class="form-control m-input m-input--square" name="cabang_tujuan" id="cabang_tujuan" required="required">
                <option value="98" @if (!empty($data->id_perush_tujuan) && $data->id_perush_tujuan == 98) selected @else {{ old("cabang_tujuan") }} @endif>VENDOR KOTA-KOTA</option>
                <option value="98" @if (!empty($data->id_perush_tujuan) && $data->id_perush_tujuan == 99) selected @else {{ old("cabang_tujuan") }} @endif>VENDOR LUAR PULAU</option>
                @foreach ($perush as $item)
                    <option value="{{ $item->id_perush }}" @if (!empty($data->id_perush_tujuan) && $data->id_perush_tujuan == $item->id_perush) selected @else {{ old("cabang_tujuan") }} @endif>{{ $item->nm_perush }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('cabang_tujuan'))
            <label style="color: red">
                {{ $errors->first('cabang_tujuan') }}
            </label>
            @endif
        </div>

        <div class="form-group col-md-6">
            <label for="keterangan">
                <b>Keterangan</b>
            </label>
            
            <textarea class="form-control m-input m-input--square" name="keterangan" id="keterangan" maxlength="256" style="min-height: 100px">@if(isset($data->keterangan)){{ $data->keterangan }}@else{{ old("keterangan") }}@endif</textarea>

            @if ($errors->has('keterangan'))
            <label style="color: red">
                {{ $errors->first('keterangan') }}
            </label>
            @endif
        </div>

        @if (isset($detail_stt))
            <div class="col-md-12">
                <h4>Detail STT </h4>
                <table class="table" width="100%">
                    <thead style="background-color: grey; color : #ffff;">
                        <tr>
                            <th>No. </th>
                            <th>Kode STT (AWB)</th>
                            <th>Pengirim</th>
                            <th>Penerima</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $total = 0;
                        @endphp
                        @foreach($detail_stt as $key => $value)
                        <tr>
                            <td>{{ $key+1 }} </td>
                            <td>
                                {{ strtoupper($value->kode_stt) }} ({{ strtoupper($value->no_awb) }})
                                <br>
                                {{ dateindo($value->tgl_masuk) }}
                            </td>
                            <td>{{ strtoupper($value->pengirim_nm)}}
                                <br>
                                <span class="label label-inline label-light-primary">
                                    {{$value->pengirim_telp}}</span>
                                    <br>
                                    <span >{{$value->asal->nama_wil}}</span>
                                </td>
                                <td>@isset($value->penerima_nm)
                                    {{ strtoupper($value->penerima_nm)}}
                                    @endisset
                                    <br>
                                    <span class="label label-inline label-light-primary">
                                        @isset($value->penerima_telp){{$value->penerima_telp}}@endisset
                                    </span>
                                    <br>
                                    <span>
                                        @isset($value->penerima_alm)
                                        {{$value->tujuan->nama_wil}}
                                        @endisset
                                    </span>
                                </td>
                                
                                <td class="text-center"><a class="btn btn-md btn-danger" href="{{ url('sttkembali') . '/deletestt/' . $value->id_stt }}"> <i class="fa fa-trash"></i></a></td>
                            </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
        <input type="text" class="form-control" id="search" placeholder="Type to search">
    <br>
    <table class="table table-responsive table-bordered" id="tableasal" style="display: block; height: 500px; overflow: auto;" width="100%">
        <thead style="background-color: grey; color : #ffff;">
            <tr>
                <th>No. </th>
                <th>Kode STT (AWB)</th>
                <th>Pengirim</th>
                <th>Penerima</th>
                <th>Status Terakhir</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
            $total = 0;
            @endphp
            @foreach($detail as $key => $value)
            <tr style = {{ $value->diff_date > 3 ? "color:red" : '' }}>
                <td>{{ $key+1 }} </td>
                <td>
                    {{ strtoupper($value->kode_stt) }} ({{ strtoupper($value->no_awb) }})
                    <br>
                    {{ dateindo($value->tgl_masuk) }}
                </td>
                <td>{{ strtoupper($value->pengirim_nm)}}
                    <br>
                    <span class="label label-inline label-light-primary">
                        {{$value->pengirim_telp}}</span>
                        <br>
                        <span >{{$value->asal->nama_wil}}</span>
                    </td>
                    <td>@isset($value->penerima_nm)
                        {{ strtoupper($value->penerima_nm)}}
                        @endisset
                        <br>
                        <span class="label label-inline label-light-primary">
                            @isset($value->penerima_telp){{$value->penerima_telp}}@endisset
                        </span>
                        <br>
                        <span>
                            @isset($value->penerima_alm)
                            {{$value->tujuan->nama_wil}}
                            @endisset
                        </span>
                    </td>
                    <td class="text-center">
                        {{strtoupper($value->nm_status)}}<br>
                        {{ dateindo($value->tgl_update) }} ({{ $value->diff_date }})
                    </td>
                    <td class="text-center">
                        @if($value->id_status == 6)
                        <a href="javascript:void(0)" class="btn" type="button" onclick="CheckSampai('{{ $value->id_stt }}')" data-toggle="tooltip" data-placement="bottom" title="Ambil Stt Di Gudang">
                            <span><i class="fa fa-check"></i></span> Sampai Tujuan
                        </a>
                        @else
                            <input type="checkbox" class="form-control check" name="id_stt[]" id="id_stt" value="{{$value->id_stt}}">
                        @endif
                    </td>
                </tr>
                @endforeach
        </tbody>
    </table>
    <div class="col-md-12 text-right">
        @include('template.inc_action')
    </div>
</form>

@section('script')
<script>
@if(isset($data->id_perush_tj))
    $("#id_perush_tj").val('{{ $data->id_perush_tj }}');
@endif

@if(isset($data->id_perush_tj))
    $("#id_perush_tj").val('{{ $data->id_perush_tj }}');
@endif

@if(isset($data->kurir))
    $("#kurir").val('{{ $data->kurir }}');
@endif

$("#id_perush_tj").change(function() {
    var id = $("#id_perush_tj").val();
    $.ajax({
        type: "GET",
        url: "{{ url('getKaryawan') }}/"+id,
        beforeSend: function(e) {
            if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
            }
        },
        success: function(response){ 
            $('#id_karyawan').empty();
            $.each(response, function(index, value) {
                $('#id_karyawan').append('<option value="'+value.kode+'">'+value.nama+'</option>');
            });
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError);
        }
    });
});

var $rows = $('#tableasal tr');
        $('#search').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
            
            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });

@if(isset($data->karyawan->nm_karyawan))
    $('#id_karyawan').append('<option value="{{ $data->id_karyawan }}">{{ $data->karyawan->nm_karyawan }}</option>');
    $("#id_karyawan").val('{{ $data->id_karyawan }}');
@endif

$("#status").select2();
$("#cabang_tujuan").select2();

</script>
@endsection