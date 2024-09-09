<div class="col-md-3">
    <label for="id_invoice" >Nomor Invoice</label>
    <select class="form-control" id="id_invoice" name="id_invoice"></select>
</div>

<div class="col-md-3">
    <label for="id_perush_tj" >Perusahaan Pengirim</label>
    <select class="form-control" id="id_perush" name="id_perush">
        <option value="">-- Pilih Perusahaan --</option>
        @foreach($perusahaan as $key => $value)
        <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
        @endforeach
    </select>

    @if ($errors->has('id_perush_tj'))
    <label style="color: red">
        {{ $errors->first('id_perush_tj') }}
    </label>
    @endif
</div>

<div class="col-md-3">
    <label for="id_perush_tj" >Dari Tanggal</label>
    <input type="date" placeholder="Pilih Tgl Invoice" class="form-control" id="dr_tgl" name="dr_tgl" value="@if(isset($filter['dr_tgl'])){{$filter['dr_tgl']}}@endif"/>
    @if ($errors->has('tgl_invoice'))
    <label style="color: red">
        {{ $errors->first('tgl_invoice') }}
    </label>
    @endif
</div>

<div class="col-md-3">
    <label for="id_perush_tj" >Sampai Tanggal</label>
    <input type="date" placeholder="Pilih Tgl Invoice" class="form-control" id="sp_tgl" name="sp_tgl" value="@if(isset($filter['sp_tgl'])){{$filter['sp_tgl']}}@endif" />
    @if ($errors->has('tgl_invoice'))
    <label style="color: red">
        {{ $errors->first('tgl_invoice') }}
    </label>
    @endif
</div>

<div class="col-md-3">
    <label for="id_status" >Status Bayar</label>
    <select class="form-control" id="id_status" name="id_status">
        <option value="">-- Pilih Status --</option>
    </select>

    @if ($errors->has('id_status'))
    <label style="color: red">
        {{ $errors->first('id_status') }}
    </label>
    @endif
</div>

<div class="col-md-6" style="margin-top: 2%">
    <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
    <a href="{{ url(Request::segment(1)."/reset") }}" class="btn btn-sm btn-warning"><span><i class="fa fa-times"> </i></span> Reset </a>
    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-create" onclick="refresh()"><span><i class="fa fa-plus"> </i></span> Tambah Invoice</button>
</div>
