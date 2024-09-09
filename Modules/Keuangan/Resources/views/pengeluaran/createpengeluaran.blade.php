@extends('template.document')

@section('data')
@if(Request::segment(2)=="create")
<form method="POST" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data">
    @else
    <form method="POST" action="{{ url(Request::segment(1), $data->id_pengeluaran) }}" enctype="multipart/form-data">
        {{ method_field("PUT") }}
        @endif
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="form-group m-form__group">
                    <label for="id_ac">
                        <b>Akun Kredit</b><span class="span-required"> *</span>
                    </label>
                    
                    <select class="form-control" name="id_ac" id="id_ac" required>
                        <option value="">-- Pilih Akun --</option>
                        @foreach($akun as $key => $value)
                        <option value="{{ $value->id_ac }}">{{ $value->nama." (".$value->id_ac.")" }}</option>
                        @endforeach
                    </select>
                    
                    @if ($errors->has('id_ac'))
                    <label style="color: red">
                        {{ $errors->first('id_ac') }}
                    </label>
                    @endif
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group m-form__group">
                    <label for="tgl_masuk">
                        <b>Tanggal Keluar</b><span class="span-required"> *</span>
                    </label>
                    
                    <input type="date" class="form-control"  id="tgl_masuk" name="tgl_masuk" value="@if(isset($data->tgl_keluar)){{ $data->tgl_keluar }}@else{{old('tgl_keluar')}}@endif" maxlength="32" required/>
                    
                    @if ($errors->has('tgl_masuk'))
                    <label style="color: red">
                        {{ $errors->first('tgl_masuk') }}
                    </label>
                    @endif
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group m-form__group">
                    <label for="terima_dr">
                        <b>Untuk</b><span class="span-required"> *</span>
                    </label>
                    
                    <input class="form-control"  id="terima_dr" name="terima_dr" value="@if(isset($data->terima_dr)){{ $data->terima_dr }}@else{{old('terima_dr')}}@endif" maxlength="32" required placeholder="Mr. Andi"/>
                    
                    @if ($errors->has('terima_dr'))
                    <label style="color: red">
                        {{ $errors->first('terima_dr') }}
                    </label>
                    @endif
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group m-form__group">
                    <label for="info">
                        <b>Keterangan</b><span class="span-required"> *</span>
                    </label>
                    <textarea class="form-control" style="min-height: 100px"  id="info" name="info" minlength="4" maxlength="256" required>@if(isset($data->info)){{ $data->info }}@else{{old('info')}}@endif</textarea>
                    @if ($errors->has('info'))
                    <label style="color: red">
                        {{ $errors->first('info') }}
                    </label>
                    @endif
                </div>
            </div>
            
            <div class="col-md-12 text-right">
                @include('template.inc_action')
            </div>
        </div>
    </form>
    @endsection
    @section('script')
    <script>
        @if (isset($data->tgl_keluar))
            $("#tgl_masuk").val('{{$data->tgl_keluar}}');
        @else
            var today = new Date().toISOString().split('T')[0];
            $("#tgl_masuk").val(today);
        @endif

        $("#id_ac").select2();
        
        @if(isset($data->id_ac))
        $("#id_ac").val('{{ $data->id_ac }}').trigger("change");
        @elseif(old("id_ac")!=null)
        $("#id_ac").val('{{ old('id_ac') }}').trigger("change");
        @endif
        
    </script>
    @endsection
    