<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('pelanggan/import') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <label for="id_ven">
                <b>Kategori Pelanggan</b> <span class="span-required"> *</span>
            </label>
        </div>
        <div class="form-check col-md-2" style="margin-left:15px">
            <input class="form-check-input" type="radio" name="jenis" id="jenis1" value="1"/>
            <label class="form-check-label" for="flexRadioDefault1">Pelanggan Lsj Group</label>
        </div>
        
        <!-- Default checked radio -->
        <div class="form-check col-md-2">
            <input class="form-check-input" type="radio" name="jenis" id="jenis2" value="2"/>
            <label class="form-check-label" for="flexRadioDefault2">Pelanggan Vendor Luar</label>
        </div>
        
    </div>
    <div class="row">
        <div class="col-md-4" id="id_ven_lsj">
            <label for="id_ven">
                <b>Vendor Lsj Group</b> <span class="span-required"> *</span>
            </label>
            
            <select class="form-control m-input m-input--square" id="id_lsj_ven" name="id_lsj_ven">
                <option value="">-- Pilih Perusahaan --</option>
                @foreach($perusahaan as $keu => $value)
                @if($value->id_perush!=Session("perusahaan")["id_perush"])
                <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
                @endif
                @endforeach
            </select>
            
            @if ($errors->has('id_ven'))
            <label style="color: red">
                {{ $errors->first('id_ven') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-4" id="id_ven_luar">
            <label for="id_ven">
                <b>Vendor Luar</b> <span class="span-required"> *</span>
            </label>
            
            <select class="form-control m-input m-input--square" id="id_ven" name="id_ven">
                <option value="">-- Pilih Vendor --</option>
                @foreach($vendor as $keu => $value)
                <option value="{{ $value->id_ven }}">{{ strtoupper($value->nm_ven) }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('id_ven'))
            <label style="color: red">
                {{ $errors->first('id_ven') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-4" style="margin-top:30px">
            <button class="btn btn-sm btn-primary" type="submit">
                <i class="fa fa-download"></i> Import Data
            </button>
        </div>
    </div>
    
    @if(isset($pelanggan)) 
    
    <div>
        
    </div>
    @endif
</form>

    @section("script")
    <script>
        $(document).ready(function() {
            $("#id_ven_luar").hide();
            $("#id_ven_lsj").show();
            $('#jenis1').attr('checked', true);
            
            var today = new Date().toISOString().split('T')[0];
            @if(isset($data->tgl_berangkat)){{ $data->tgl_berangkat }}@else $("#tgl_berangkat").val(today); @endif
            
            $('#jenis1').change(function() {
                $("#id_ven_lsj").show();
                $("#id_ven_luar").hide();
            });
            
            $('#jenis2').change(function() {
                $("#id_ven_luar").show();
                $("#id_ven_lsj").hide();
            });
        });
    </script>
    @endsection