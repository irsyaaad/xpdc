@extends('template.document')
@section('data')
    <form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
        @csrf
        <div class="row mt-1">
            <div class="col-md-12" style="overflow-x:auto;">
                <table class="table table-responsive table-hover">
                    <thead style="background-color: grey; color : #ffff">
                        <tr>
                            <th>No</th>
                            <th>Nama Vendor</th>
                            <th>Group</th>
                            <th>Kota</th>
                            <th>Is Aktif</th>
                            <th class="text-center">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data as $key => $value)
                            <tr>
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    {{ strtoupper($value->nm_ven) }} <br>
                                    {{ $value->telp_ven }}
                                </td>
                                <td>
                                    @if (isset($value->group->nm_grup_ven))
                                        {{ strtoupper($value->group->nm_grup_ven) }}
                                    @endif
                                </td>
                                <td>
                                    @if (isset($value->wilayah->nama_wil))
                                        {{ strtoupper($value->wilayah->nama_wil) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($value->is_aktif == 1)
                                        <i class="fa fa-check" style="color: green"></i>
                                    @else
                                        <i class="fa fa-times" style="color: red"></i>
                                    @endif
                                </td>
                                <td>
                                    {!! inc_edit($value->id_ven) !!}
                                </td>
                            </tr>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @include('template.paginate')
        </div>
    </form>
@endsection
@section('script')
    <script type="text/javascript">
        $("#shareselect").on("change", function(e) {
            $("#form-select").submit();
        });

        @if (isset($filter['page']))
            $("#shareselect").val('{{ $filter['page'] }}');
        @endif

        $('#f_id_ven').select2({
            placeholder: 'Cari Nama Vendor ....',
            ajax: {
                url: '{{ url('getVendor') }}',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    $('#f_id_ven').empty();
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.value,
                                id: item.kode
                            }
                        })
                    };
                },
                cache: true
            }
        });

        @if (isset($filter['f_id_grup_ven']))
            $("#f_id_grup_ven").val('{{ $filter['f_id_grup_ven'] }}');
        @endif
        @if (isset($filter['f_id_ven']->nm_ven))
            $("#f_id_ven").empty();
            $("#f_id_ven").append(
                '<option value="{{ $filter['f_id_ven']->id_ven }}">{{ strtoupper($filter['f_id_ven']->nm_ven) }}</option>'
            );
        @endif
    </script>
@endsection
