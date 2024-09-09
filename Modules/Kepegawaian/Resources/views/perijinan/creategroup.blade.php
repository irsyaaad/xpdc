@extends('template.document2')

@section('data')

<form class="m-form m-form--fit m-form--label-align-right" id="form-submit" method="POST" action="{{ url(Request::segment(1)."/".Request::segment(2)) }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        
        <div class="form-group col-md-3">
            <label for="id_perush">
                <b>Perusahaan / Devisi</b> <span class="span-required"> *</span>
            </label>
            <select class="form-control" id="id_perush" name="id_perush">
                <option value=""> -- Pilih Perusahaan / Devisi --</option>
                @foreach($perusahaan as $key => $value)
                <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group col-md-3">
            <label for="jenis_perijinan">
                <b>Jenis Perijinan</b> <span class="span-required"> *</span>
            </label>
            
            <select class="form-control" id="jenis_perijinan" name="jenis_perijinan">
                <option value="">-- Pilih Jenis Izin --</option>
                @foreach($jenis as $key => $value)
                <option value="{{$value->id_jenis}}">{{strtoupper($value->nm_jenis)}}</option>
                @endforeach
            </select>
            
            @if ($errors->has('nm_jenis'))
            <label style="color: red">
                {{ $errors->first('nm_jenis') }}
            </label>
            @endif
        </div>
        
        <div class="form-group col-md-3">
            <label for="dr_tgl">
                <b>Dari Tanggal</b> <span class="span-required"> *</span>
            </label>

            <input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="@if(isset($dr_tgl) and $dr_tgl!=null){{ $dr_tgl }}@else{{ old("dr_tgl") }}@endif">
        </div>
        
        <div class="form-group col-md-3">
            <label for="sp_tgl">
                <b>Sampai Tanggal</b> <span class="span-required"> *</span>
            </label>
            <input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="@if(isset($sp_tgl) and $sp_tgl!=null){{ $sp_tgl }}@else{{ old("sp_tgl") }}@endif">
        </div>
        
        <div class="form-group col-md-3">
            <label for="keterangan">
                <b>Keterangan</b> <span class="span-required"> *</span>
            </label>
            
            <textarea type="text" class="form-control" name="keterangan" id="keterangan">@if(isset($keterangan) and $keterangan!=null){{ $keterangan }}@else{{ old("keterangan") }}@endif</textarea>
            
            @if ($errors->has('keterangan'))
            <label style="color: red">
                {{ $errors->first('keterangan') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-9 text-right" style="padding-top: 40px">
            <button class="btn btn-sm btn-success" onclick="goSubmit()"><i class="fa fa-save"> </i> Simpan</button>
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-times"> </i> Kembali </a>
        </div>
        
        <div class="col-md-12">
            <div style="overflow-x:auto;">
                <table class="table table-responsive table-striped" width="100%" >
                    <thead style="background-color: grey; color : #ffff">
                        <tr>
                            <th>No</th>
                            <th>Nama Karyawan</th>
                            <th>Kelamin</th>
                            <th>Jenis Karyawan</th>
                            <th>Perusahaan</th>
                            <th class="text-center">
                                <input type="checkbox" value="1" id="c_all" name="c_all"> Pilih Semua
                            </th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($data as $key => $value)
                        <tr>
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ strtoupper($value->nm_karyawan) }}</td>
                                <td>
                                    @if($value->jenis_kelamin=="L")
                                    Laki - Laki
                                    @else
                                    Perempuan
                                    @endif
                                </td>
                                <td>
                                    @if(isset($value->nm_jenis))
                                    {{ $value->nm_jenis }}
                                    @endif
                                </td>
                                <td>
                                    @if(isset($value->nm_perush))
                                    {{ strtoupper($value->nm_perush) }}
                                    @endif	
                                </td>
                                <td>
                                    <input type="checkbox" name="c_pro[]" id="c_pro{{ $value->id_karyawan }}" class="form-control c_pro" value="{{  $value->id_karyawan }}">
                                </td>
                            </tr>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>

@endsection

@section('script')
<script> 
    $(function(){
        $('#c_all').change(function()
        {
            if($(this).is(':checked')) {
                $(".c_pro").prop("checked", true);
            }else{   
                $(".c_pro").prop("checked", false);
            }
        });
    });
    
    @if(isset($id_perush))
    $('#id_perush').val('{{ $id_perush }}');
    @endif
    
    @if(isset($jenis_perijinan))
    $('#jenis_perijinan').val('{{ $jenis_perijinan }}');
    @endif

    $('#id_perush').change(function(){
        $("#form-submit").submit();
    });

    function goSubmit(){
        $("#form-submit").attr("action", "{{ url(Request::segment(1)) }}/savegroup");
        $("#form-submit").submit();
    }
</script>
@endsection