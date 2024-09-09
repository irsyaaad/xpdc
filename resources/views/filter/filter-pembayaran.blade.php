<div class="col-md-4">
	<label style="font-weight: bold;" class="mt-1">
		Pelanggan
	</label>
	<select class="form-control" id="f_id_pelanggan" name="f_id_pelanggan">
        <option value="">-- Pelanggan --</option>
		@foreach($pelanggan as $key => $value)
			<option value="{{ $value->id_pelanggan }}">{{ $value->nm_pelanggan }}</option>
		@endforeach
    </select>
</div>

<div class="col-md-4">
	<label style="font-weight: bold;" class="mt-1">
		Nomor RESI
	</label>
	<select class="form-control" id="f_id_stt" name="f_id_stt">
        <option value="">-- Nomor RESI --</option>
		@foreach($stt as $key => $value)
			<option value="{{ $value->id_stt }}">{{ $value->kode_stt }}</option>
		@endforeach
    </select>
</div>

<div class="col-md-4">
	<label style="font-weight: bold;" class="mt-1">
		Cara Bayar
	</label>
	<select class="form-control" id="f_cr_byr" name="f_cr_byr">
        <option value="">-- Cara Bayar --</option>
		@foreach($cara as $key => $value)
			<option value="{{ $value->id_cr_byr_o }}">{{ $value->nm_cr_byr_o }}</option>
		@endforeach
    </select>
</div>

<div class="col-md-4">
	<label style="font-weight: bold;" class="mt-1">
		Dari Tanggal Bayar
	</label>
	<input class="form-control" type="date" name="dr_tgl" id="dr_tgl" value="@if(isset($filter['dr_tgl'])){{$filter['dr_tgl']}}@endif">
</div>

<div class="col-md-4">
	<label style="font-weight: bold;" class="mt-1">
		Sampai Tanggal Bayar
	</label>
	<input class="form-control" type="date" name="sp_tgl" id="sp_tgl" value="@if(isset($filter['sp_tgl'])){{$filter['sp_tgl']}}@endif">
</div>

<div class="col-md-4 mt-5">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
    <div class="dropdown d-inline-block">
		<button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		   <i class="fa fa-print"> </i> Cetak Data
		</button>
		<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            @php
                $array = [
                    'dr_tgl' => $filter['dr_tgl'],
                    'sp_tgl' => $filter['sp_tgl'],
                ];
                if (isset($filter['pelanggan'])) {
                    $array['id_pelanggan'] = $filter['pelanggan']->id_pelanggan;
                }
                if (isset($filter['stt'])) {
                    $array['id_stt'] = $filter['stt']->id_stt;
                }
            @endphp
			<a class="dropdown-item" href="{{ route('cetakallpembayaran', $array) }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i> Cetak All Data</a>
		</div>
	</div>
</div>

<script>
    $("#f_cr_byr").select2();
    $("#f_id_pelanggan").select2();
    $("#f_id_stt").select2();
    function CheckStatus(){
        $("#modal-status").modal('show');
    }

    function goSubmitUpdate() {
        $("#form-status").submit();
    }

    function html(){
		window.location = "{{ url(Request::segment(1)."/cetakall") }}";
	}

    @if(isset($page))
    $("#shareselect").val('{{ $page }}');
    @endif
    
    $("#shareselect").on("change", function(e) {
        $("#form-select").submit();
    });

    @if(isset($filter["f_cr_byr"]))
    $("#f_cr_byr").val('{{ $filter["f_cr_byr"] }}').trigger("chage");
    @endif
    
    @if(isset($filter["f_id_pelanggan"]))
    $("#f_id_pelanggan").val('{{ $filter["f_id_pelanggan"] }}').trigger("chage");
    @endif

    @if(isset($filter["f_id_stt"]))
    $("#f_id_stt").val('{{ $filter["f_id_stt"] }}').trigger("chage");
    @endif
</script>
