
{{-- <div class="col-md-3">
    <label style="font-weight: bold;">
        Perusahaan Pengirim
    </label>
    <select class="form-control" id="filterperush" name="filterperush">
        <option value="0">-- Pilih Perusahaan --</option>
    </select>
</div> --}}

{{-- <div class="col-md-3">
	<label style="font-weight: bold;">
		Tgl. Dibuat
	</label>
	<input type="date" class="form-control" id="tgl_handling" name="tgl_handling" />
</div> --}}

<div class="col-md-3">
	<label style="font-weight: bold;">
		Tgl. Berangkat <i>(Dari)</i>
	</label>
	<input type="date" class="form-control" id="tgl_berangkat_dr" name="tgl_berangkat_dr" value="@if(isset($filter['tgl_berangkat_dr'])){{$filter['tgl_berangkat_dr']}}@endif">
</div>
<div class="col-md-3">
	<label style="font-weight: bold;">
		Tgl. Berangkat <i>(Sampai)</i>
	</label>
	<input type="date" class="form-control" id="tgl_berangkat_sp" name="tgl_berangkat_sp" />
</div>

<div class="col-md-3">
	<label style="font-weight: bold;">
		Tgl. Selesai <i>(Dari)</i>
	</label>
	<input type="date" class="form-control" id="tgl_selesai_dr" name="tgl_selesai_dr" />
</div>
<div class="col-md-3">
	<label style="font-weight: bold;">
		Tgl. Selesai <i>(Sampai)</i>
	</label>
	<input type="date" class="form-control" id="tgl_selesai_sp" name="tgl_selesai_sp" />
</div>

<div class="col-md-3">
    <label style="font-weight: bold;">
        No Handling
    </label>
    <select class="form-control" id="id_handling" name="id_handling"></select>
</div>

<div class="col-md-3">
    <div class="m-form__control">
        <label style="font-weight: bold; color: white">
            Action
        </label>
        <div class="form-control" style="border: 0px; padding: 0px">
            <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
        </div>
    </div>
</div>
