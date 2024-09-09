<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('perusahaan') }}@else{{ route('perusahaan.update', $data->id_perush) }}@endif" enctype="multipart/form-data">
    @if(Request::segment(3)=="edit")
    {{ method_field("PUT") }}
    @endif
    @csrf
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Konfigurasi</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Profil</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row">
                <div class="form-group m-form__group col-md-4" style="margin-top: 10px">
                    <label for="logo">
                        <b>Logo</b>
                    </label>
                    
                    <input type="file" class="form-control m-input m-input--square" name="logo" id="logo" placeholder="Pilih Logo Perusahaan">
                    
                    @if ($errors->has('logo'))
                    <label style="color: red">
                        {{ $errors->first('logo') }}
                    </label>
                    @endif
                </div>
                
                <div class="form-group m-form__group col-md-4">
                    <label for="id_perush">
                        <b>Kode Reff</b> <span class="span-required"> *</span>
                    </label>
                    
                    <input type="text" class="form-control m-input m-input--square" name="id_ref" id="id_ref" placeholder="Ex : 78" value="@if(isset($data->kode_ref)){{ $data->kode_ref }}@else{{ old('kode_ref') }}@endif" maxlength="100">
                    
                    @if ($errors->has('id_ref'))
                    <label style="color: red">
                        {{ $errors->first('id_ref') }}
                    </label>
                    @endif
                </div>
                
                <div class="form-group m-form__group col-md-4">
                    <label for="id_perush">
                        <b>Kode Perusahaan</b> <span class="span-required"> *</span>
                    </label>
                    
                    <input type="text" class="form-control m-input m-input--square" name="id_perush" id="id_perush" placeholder="Ex : SUB1" value="@if(isset($data->kode_perush)){{ $data->kode_perush }}@else{{ old('kode_perush') }}@endif" maxlength="100">
                    
                    @if ($errors->has('id_perush'))
                    <label style="color: red">
                        {{ $errors->first('id_perush') }}
                    </label>
                    @endif
                </div>
                
                <div class="form-group m-form__group col-md-4">
                    <label for="nm_perush">
                        <b>Nama Perusahaan</b> <span class="span-required"> *</span>
                    </label>
                    
                    <input type="text" class="form-control m-input m-input--square" name="nm_perush" id="nm_perush" placeholder="Masukan Nama" value="@if(isset($data->nm_perush)){{ $data->nm_perush }}@else{{ old('nm_perush') }}@endif" maxlength="100">
                    
                    @if ($errors->has('nm_perush'))
                    <label style="color: red">
                        {{ $errors->first('nm_perush') }}
                    </label>
                    @endif
                </div>
                
                <div class="form-group m-form__group col-md-4">
                    <label for="cabang">
                        <b>Perusahaan Induk</b> <span class="span-required"> *</span>
                    </label>
                    
                    <select class="form-control" id="cabang" name="cabang" placeholder="Pilih Perusahaan Induk">
                        <option value="">-- Pilih Perusahaan Induk --</option>
                        @foreach($perush as $key => $value)
                        <option value="{{ $value->id_perush }}">{{ strtoupper($value->id_perush." - ".$value->nm_perush) }}</option>
                        @endforeach
                    </select>
                    
                    @if ($errors->has('cabang'))
                    <label style="color: red">
                        {{ $errors->first('cabang') }}
                    </label>
                    @endif
                </div>
                
                <div class="form-group m-form__group col-md-4">
                    <label for="id_region">
                        <b>Kota Asal</b> <span class="span-required"> *</span>
                    </label>
                    
                    <select id="id_region" name="id_region" class="form-control">
                        <option value="">-- Pilih Kota Asal --</option>
                        @foreach ($wilayah as $key => $value)
                        <option value="{{ $value->id_wil }}">{{ $value->nama_wil }}</option>
                        @endforeach
                    </select>
                    
                    <input type="hidden" name="nm_region" id="nm_region">
                    @if ($errors->has('id_region'))
                    <label style="color: red">
                        {{ $errors->first('id_region') }}
                    </label>
                    @endif
                </div>
                
                <div class="form-group m-form__group col-md-4">
                    <label for="id_cab_group">
                        <b>Cabang Group</b> <span class="span-required"> *</span>
                    </label>
                    
                    <select class="form-control" id="id_cab_group" name="id_cab_group" placeholder="Pilih Cabang Group">
                        <option value="">-- Pilih Cabang Group --</option>
                        @foreach($group as $key => $value)
                        <option value="{{ $value->id_cabgroup }}">{{ strtoupper($value->id_cabgroup." - ".$value->nm_cabgroup) }}</option>
                        @endforeach
                    </select>
                    
                    @if ($errors->has('id_cab_group'))
                    <label style="color: red">
                        {{ $errors->first('id_cab_group') }}
                    </label>
                    @endif
                </div>
                
                <div class="form-group m-form__group col-md-4">
                    <label for="telp">
                        <b>No. Telp</b> <span class="span-required"> *</span>
                    </label>
                    
                    <input type="text" class="form-control m-input m-input--square" name="telp" id="telp" placeholder="Masukan No. Telp" value="@if(isset($data->telp)){{ $data->telp }}@else{{ old('telp') }}@endif" maxlength="16">
                    
                    @if ($errors->has('telp'))
                    <label style="color: red">
                        {{ $errors->first('telp') }}
                    </label>
                    @endif
                </div>
                
                <div class="form-group m-form__group col-md-4">
                    <label for="fax">
                        <b>No. Fax</b>
                    </label>
                    
                    <input type="text" class="form-control m-input m-input--square" name="fax" id="fax" placeholder="Masukan No. Fax" value="@if(isset($data->fax)){{ $data->fax }}@else{{ old('fax') }}@endif" maxlength="16">
                    
                    @if ($errors->has('fax'))
                    <label style="color: red">
                        {{ $errors->first('fax') }}
                    </label>
                    @endif
                </div>
                
                <div class="form-group m-form__group col-md-4">
                    <label for="email">
                        <b>Email</b><span class="span-required"> *</span>
                    </label>
                    
                    <input type="email" class="form-control m-input m-input--square" name="email" id="email" placeholder="Masukan Email" value="@if(isset($data->email)){{ $data->email }}@else{{ old('email') }}@endif" maxlength="50" required>
                    
                    @if ($errors->has('email'))
                    <label style="color: red">
                        {{ $errors->first('email') }}
                    </label>
                    @endif
                </div>
                
                <div class="form-group m-form__group col-md-4">
                    <label for="npwp">
                        <b>NPWP</b>
                    </label>
                    
                    <input type="text" class="form-control m-input m-input--square" name="npwp" id="npwp" placeholder="Masukan NPWP" value="@if(isset($data->npwp)){{ $data->npwp }}@else{{ old('npwp') }}@endif" maxlength="16">
                    
                    @if ($errors->has('npwp'))
                    <label style="color: red">
                        {{ $errors->first('npwp') }}
                    </label>
                    @endif
                </div>
                
                <div class="form-group m-form__group col-md-4">
                    <label for="nm_dir">
                        <b>Nama Direktur</b>
                    </label>
                    
                    <input type="text" class="form-control m-input m-input--square" name="nm_dir" id="nm_dir" placeholder="Masukan Direktur" value="@if(isset($data->nm_dir)){{ $data->nm_dir }}@else{{ old('nm_dir') }}@endif" maxlength="50">
                    
                    @if ($errors->has('nm_dir'))
                    <label style="color: red">
                        {{ $errors->first('nm_dir') }}
                    </label>
                    @endif
                </div>
                
                <div class="form-group m-form__group col-md-4">
                    <label for="nm_keu">
                        <b>Nama Keuangan</b>
                    </label>
                    
                    <input type="text" class="form-control m-input m-input--square" name="nm_keu" id="nm_keu" placeholder="Masukan Admin Keuangan" value="@if(isset($data->nm_keu)){{ $data->nm_keu }}@else{{ old('nm_keu') }}@endif" maxlength="50">
                    
                    @if ($errors->has('nm_keu'))
                    <label style="color: red">
                        {{ $errors->first('nm_keu') }}
                    </label>
                    @endif
                </div>
                
                <div class="form-group m-form__group col-md-4">
                    <label for="nm_cs">
                        <b>Nama Customer Service</b>
                    </label>
                    
                    <input type="text" class="form-control m-input m-input--square" name="nm_cs" id="nm_cs" placeholder="Masukan Customer Service" value="@if(isset($data->nm_cs)){{ $data->nm_cs }}@else{{ old('nm_cs') }}@endif" maxlength="50">
                    
                    @if ($errors->has('nm_cs'))
                    <label style="color: red">
                        {{ $errors->first('nm_cs') }}
                    </label>
                    @endif
                </div>
                
                <div class="form-group m-form__group col-md-4">
                    <label for="telp_cs">
                        <b>No. Telp Cs</b> <span class="span-required"> *</span>
                    </label>
                    
                    <input type="text" class="form-control m-input m-input--square" name="telp_cs" id="telp_cs" placeholder="Masukan No. Telp Cs" value="@if(isset($data->telp_cs)){{ $data->telp_cs }}@else{{ old('telp_cs') }}@endif" maxlength="16">
                    
                    @if ($errors->has('telp_cs'))
                    <label style="color: red">
                        {{ $errors->first('telp_cs') }}
                    </label>
                    @endif
                </div>
                
                <div class="form-group m-form__group col-md-4">
                    <label for="telp_cs">
                        <b>Besar ppn (Dalam persen)</b> <span class="span-required"> *</span>
                    </label>
                    
                    <input type="number" class="form-control m-input m-input--square" name="n_ppn" id="n_ppn" step="any" placeholder="ex : 1,1" value="@if(isset($data->n_ppn)){{ $data->n_ppn }}@else{{ old('n_ppn') }}@endif" maxlength="16">
                    
                    @if ($errors->has('telp_cs'))
                    <label style="color: red">
                        {{ $errors->first('telp_cs') }}
                    </label>
                    @endif
                </div>

                <div class="form-group m-form__group col-md-4">
                    <label for="device_id">
                        <b>Device ID (Whatspie)</b> <span class="span-required"> *</span>
                    </label>
                    
                    <input type="number" class="form-control m-input m-input--square" name="device_id" id="device_id" placeholder="ex : 6282320000091" value="@if(isset($data->device_id)){{ $data->device_id }}@else{{ old('device_id') }}@endif" maxlength="16">
                    
                    @if ($errors->has('device_id'))
                    <label style="color: red">
                        {{ $errors->first('device_id') }}
                    </label>
                    @endif
                </div>

                <div class="form-group m-form__group col-md-4">
                    <label for="website">
                        <b>Website</b> <span class="span-required"> *</span>
                    </label>
                    
                    <input type="text" class="form-control m-input m-input--square" name="website" id="website" placeholder="ex : https://lsjexpress.co.id" value="@if(isset($data->website)){{ $data->website }}@else{{ old('website') }}@endif" maxlength="100">
                    
                    @if ($errors->has('website'))
                    <label style="color: red">
                        {{ $errors->first('website') }}
                    </label>
                    @endif
                </div>
                
                <div class="form-group m-form__group col-md-2">
                    <label for="is_aktif">
                        <b>Is Aktif </b>
                    </label>
                    
                    <div class="row">
                        <div class="col-md-12 checkbox">
                            
                            <label><input type="checkbox" value="1" id="is_aktif" name="is_aktif"> Aktif ?</label>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"> 
            <div class="row">
                <div class="form-group m-form__group col-md-12">
                    <label for="alamat">
                        <b>Alamat Lengkap</b> <span class="span-required"> *</span>
                    </label>                    
                    <textarea class="form-control m-input m-input--square" cols="30" rows="5" name="alamat" id="alamat" placeholder="Masukan Alamat" maxlength="100">@if(isset($data->alamat)){{ $data->alamat }}@else{{ old('alamat') }}@endif</textarea>
                    
                    @if ($errors->has('alamat'))
                    <label style="color: red">
                        {{ $errors->first('alamat') }}
                    </label>
                    @endif
                </div>
                
                <div class="form-group m-form__group col-md-12">
                    <label for="header">
                        <b>Header STT </b>
                    </label>
                    <textarea name="header" id="header" cols="30" rows="5" class="form-control m-input m-input--square" placeholder="Masukkan Header STT">@if(isset($data->header)){{ $data->header }}@else{{ old('header') }}@endif</textarea>
                </div>
                
                <div class="form-group m-form__group col-md-12">
                    <label for="header">
                        <b>Keterangan Invoice </b>
                    </label>
                    <textarea name="info_invoice" id="info_invoice" cols="30" rows="5" class="form-control m-input m-input--square" placeholder="Masukkan Informasi Invoice">@if(isset($data->info_invoice)){{ $data->info_invoice }}@else{{ old('info_invoice') }}@endif</textarea>
                </div>
                
                <div class="form-group m-form__group col-md-12">
                    <label for="header">
                        <b>URL </b>
                    </label>
                    <input name="url_booking" id="url_booking" maxlength="300" class="form-control m-input m-input--square" placeholder="Masukkan Url Booking (jika ada) .." value="@if(isset($data->url_booking)){{ $data->url_booking }}@else{{ old('url_booking') }}@endif">
                </div>
            </div>            
        </div>
    </div>
    
    <div class="col-md-12 text-right">
        @include('template.inc_action')
    </div>
    
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.18.0/ckeditor.js" integrity="sha512-woYV6V3QV/oH8txWu19WqPPEtGu+dXM87N9YXP6ocsbCAH1Au9WDZ15cnk62n6/tVOmOo0rIYwx05raKdA4qyQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    CKEDITOR.replace('header',{
        height: "200px",
        on :
        {
            instanceReady : function( ev )
            {
                // Output paragraphs as <p>Text</p>.
                this.dataProcessor.writer.setRules( 'p',
                    {
                        indent : false,
                        breakBeforeOpen : true,
                        breakAfterOpen : false,
                        breakBeforeClose : false,
                        breakAfterClose : true
                    });
            }
        }
    });
    CKEDITOR.setRules('p',{indent:false,breakAfterOpen:false});
    
    CKEDITOR.replace('info_invoice',{
        height: "200px"
    });
</script>