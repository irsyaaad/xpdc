<form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-search">
    @csrf
    <div class="row">
        <div class="col-md-3">
            <label style="font-weight : bold">
                Perusahaan Asal
            </label>
            <select class="form-control" id="id_perush" name="id_perush">
                <option value="">-- Pilih Perusahaan --</option>
                @foreach ($perusahaan as $key => $value)
                    <option value="{{ $value->id_perush }}"
                        {{ $filter['id_perush'] == $value->id_perush ? 'selected' : '' }}>
                        {{ strtoupper($value->nm_perush) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label style="font-weight: bold;">
                Tahun
            </label>
            <select class="form-control" id="from_year" name="from_year">
                <option selected="selected" value="0">-- Pilih Tahun --</option>
                @php
                    for ($i = date('Y'); $i >= date('Y') - 10; $i -= 1) {
                        echo "<option value='$i'> $i </option>";
                    }
                @endphp
            </select>
        </div>
        <div class="col-md-3">
            <label style="font-weight: bold;">
                Tahun
            </label>
            <select class="form-control" id="to_year" name="to_year">
                <option selected="selected" value="0">-- Pilih Tahun --</option>
                @php
                    for ($i = date('Y'); $i >= date('Y') - 10; $i -= 1) {
                        echo "<option value='$i'> $i </option>";
                    }
                @endphp
            </select>
        </div>
        <div class="col-md-3" style="margin-top: 27px">
            <button type="submit" class="btn btn-md btn-info" data-toggle="tooltip" data-placement="top"
                title="Cari Data">
                <i class="fa fa-search"></i>
            </button>
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip"
                data-placement="top" title="Refresh">
                <i class="fa fa-refresh"></i>
            </a>
            <a href="{{ route('cetak-rekap-gaji', [
                'id_perush' => $filter['id_perush'],
                'from_year' => $filter['from_year'],
                'to_year' => $filter['to_year'],
            ]) }}"
                class="btn btn-md btn-success" data-toggle="tooltip" data-placement="top" title="Refresh">
                <i class="fa fa-print"></i>
            </a>
        </div>
    </div>
</form>
<script>
    $("#id_perush").select2();
    $("#from_year").select2();
    $("#to_year").select2();

    @if (isset($filter['from_year']))
        $("#from_year").val('{{ $filter['from_year'] }}').trigger('change');
    @endif

    @if (isset($filter['to_year']))
        $("#to_year").val('{{ $filter['to_year'] }}').trigger('change');
    @endif
</script>
