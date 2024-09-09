
@extends('template.document2')

@section('data')
<style>
    .hidden { display: none }
</style>
<form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
    @include('template.filter-collapse')
    @csrf
    <div class="row-mt 1">
        <div class="col-md-12 table-responsive" style="display: block; overflow-x: auto; white-space: nowrap;">
            <table class="table table-hover">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th rowspan="2">
                            <button type="button" class="btn btn-sm btn-light" data-column="#column-a"><i class="fa fa-user"></i></button>
                            <button type="button" class="btn btn-sm btn-light" data-column="#column-b"><i class="fa fa-home"></i></button>
                        </th>
                        <th rowspan="2">Nama Pelanggan</th>
                        <th rowspan="2" id="column-a" class="hidden">Group</th>
                        <th rowspan="2" id="column-b" class="hidden">Alamat</th>
                        <th colspan="2" class="text-center">Jumlah (STT)</th>
                        <th rowspan="2" class="text-right">Limit</th>
                        <th rowspan="2" class="text-right">Piutang</th>
                        <th rowspan="2" class="text-right">Terbayar</th>
                        <th rowspan="2" class="text-right">Sisa Piutang</th>
                        <th rowspan="2" class="text-right">Sisa Limit</th>
                    </tr>
                    <tr>
                        <th>Kiriman</th>
                        <th>Lunas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $value)
                    <tr>
                        <td>{{ ($key+1) }}</td>
                        <td>
                            @if(isset($value->nm_pelanggan))
                            <a href="{{ url(Request::segment(1)."/".$value->id_pelanggan."/show") }}">{{$value->nm_pelanggan}}</a>
                            @endif
                            <br>
                            @if(isset($value->telp)){{$value->telp}}@endif
                        </td>
                        <td id="column-a" class="hidden">
                            {{ "(".$value->kode_plgn_group.") ".$value->nm_group }}
                        </td>
                        <td id="column-b" class="hidden">@if(isset($value->alamat)){{$value->alamat}}@endif</td>
                        <td>@if(isset($value->total_stt)){{$value->total_stt}}@endif</td>
                        <td>@if(isset($value->total_stt_byr)){{$value->total_stt_byr}}@endif</td>
                        <td class="text-right">{{ toNumber($value->limit_piutang) }}</td>
                        <td class="text-right">{{ toNumber($value->total) }}</td>
                        <td class="text-right">{{ toNumber($value->bayar) }}</td>
                        <td class="text-right">{{ toNumber($value->kurang) }}</td>
                        <td class="text-right">{{ toNumber($value->limit_piutang-($value->total-$value->bayar)) }}</td>
                    </tr>
                    @endforeach
                    @if ($filter['tipe_data'] != 'SUDAH LUNAS')
                        <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                            <td colspan="2">TOTAL</td>
                            <td id="column-a" class="hidden"></td>
                            <td id="column-b" class="hidden"></td>
                            <td colspan="3"></td>
                            <td class="text-right">{{ toNumber($total_piutang->omset) }}</td>
                            <td class="text-right">{{ toNumber($total_piutang->bayar) }}</td>
                            <td class="text-right">{{ toNumber($total_piutang->piutang) }}</td>
                            <td></td>
                        </tr>                        
                    @endif
                </tbody>
            </table>
            <div class="row" style="margin-top: 4%; font-weight: bold;">
                @include("template.paginator")
            </div>
        </div>
    </div>
</form>
@endsection

@section('script')
<script type="text/javascript">
    @if(isset($page))
    $("#shareselect").val('{{ $page }}');
    @endif

    $("#shareselect").on("change", function(e) {
        $("#form-select").submit();
    });

    $("#f_id_pelanggan").select2();
    $("#f_id_group").select2();

    @if(isset($filter["f_id_pelanggan"]))
    $("#f_id_pelanggan").val('{{ $filter["f_id_pelanggan"] }}').trigger("change");
    @endif

    @if(isset($filter["f_id_group"]))
    $("#f_id_group").val('{{ $filter["f_id_group"] }}').trigger("change");
    @endif

    $(document).on("click", "[data-column]", function () {
      var button = $(this),
          header = $(button.data("column")),
          table = header.closest("table"),
          index = header.index() + 1,
          selector = "tbody tr td:nth-child(" + index + ")",
          column = table.find(selector).add(header);

      column.toggleClass("hidden");
    });
</script>
@endsection
