<div class="m-form m-form--label-align-right m--margin-bottom-20">
    <div class="row align-items-center">
        <div class="col-xl-12">
            <div class="form-group row">
                <div class="col-md-4">
                    <label style="font-weight: bold;">
                        Dari Tanggal
                    </label>
                    <input type="date" class="form-control" name="dr_tgl" id="dr_tgl"
                        value="{{ isset($filter['dr_tgl']) ? $filter['dr_tgl'] : '' }}">
                </div>
                <div class="col-md-4">
                    <label style="font-weight: bold;">
                        Sampai Tanggal
                    </label>
                    <input type="date" class="form-control" name="sp_tgl" id="sp_tgl"
                        value="{{ isset($filter['sp_tgl']) ? $filter['sp_tgl'] : '' }}">
                </div>
                <div class="col-md-4">
                    <label style="font-weight: bold;">
                        Kode STT
                    </label>
                    <select class="form-control m-input m-input--square" name="marketing" id="marketing"
                        required="required">
                        <option value="">Pilih Marketing</option>
                        @foreach ($marketing as $item)
                            <option value="{{ $item->id_marketing }}"
                                @if (!empty($data->id_marketing) && $data->id_marketing == $item->id_marketing) selected @else {{ old('marketing') }} @endif>
                                {{ $item->nm_marketing }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 row d-inline-block mt-2">
                    <div class="text-right">
                        <button class="btn btn-md btn-primary" data-toggle="tooltip" data-placement="bottom"
                            title="Cari Data"><span><i class="fa fa-search"></i></span></button>
                        <a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip"
                            data-placement="bottom" title="Refresh Data"><span><i class="fa fa-refresh"></i></span></a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
    $('#marketing').select2();

    @if (isset($filter['stt']->kode_stt))
        $("#id_stt").empty();
        $("#id_stt").append(
            '<option value="{{ $filter['stt']->id_stt }}">{{ strtoupper($filter['stt']->kode_stt) }}</option>');
    @endif
</script>
