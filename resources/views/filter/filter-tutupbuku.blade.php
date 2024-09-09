<div class="col-md-3">
	<label style="font-weight: bold;">
		Pilih Tahun
	</label>

        <select class="form-control" id="tahun" name="tahun">
            <option value="0">-- Pilih Tahun --</option>
            @php
                for($i=date('Y'); $i>=date('Y')-10; $i-=1){
                echo "<option value='$i'> $i </option>";
                }
            @endphp
        </select>
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
