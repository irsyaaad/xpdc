@extends('template.document')

@section('data')

@if(Request::segment(1)=="memorial" && (Request::segment(2)==null or Request::segment(2)=="page" or Request::segment(2)=="filter"))
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include('template.filter-collapse')
    @csrf
    <div class="row mt-1">
        <div class="col-md-12 table-responsive">
            <table class="table table-hover">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No</th>
                        <th>No. Transaksi</th>
                        <th>Debet</th>
                        <th>Kredit</th>
                        <th>Tanggal</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                        <th>Admin</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ strtoupper($value->kode_memorial) }}</td>
                        <td>
                            @isset($value->debet->nama)
                            {{ strtoupper($value->debet->nama) }}
                            @endisset
                        </td>
                        <td>
                            @isset($value->kredit->nama)
                            {{ strtoupper($value->kredit->nama) }}
                            @endisset
                        </td>
                        <td>{{ dateindo($value->tgl) }}</td>
                        <td>
                            {{ toRupiah($value->nominal) }}
                        </td>
                        <td>
                            {{ $value->info }}
                        </td>
                        <td>@isset($value->user->nm_user)
                            {{ strtoupper($value->user->nm_user) }}
                            @endisset</td>
                            <td>
                                <a class="btn btn-sm btn-danger" href="#" onclick="CheckDelete('{{ url(Request::segment(1).'/'.$value->id_memorial) }}')"><i class="fa fa-times"></i> Delete</a>   
                            </td>
                        </tr>
                        @endforeach
                        @if(count($data)< 1)
                        <tr>
                            <td colspan="9" class="text-center"> Data Kosong </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('template.paginate')
        </div>
    </form>
    <script>
        @if(isset($page))
        $("#shareselect").val('{{ $page }}');
        @endif
        
        $("#shareselect").on("change", function(e) {
            $("#form-select").submit();
        });
        
        $('#debet').select2({
            placeholder: 'Cari Akun ....',
            ajax: {
                url: '{{ url('getACPerush') }}',
                minimumInputLength: 3,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    $('#id_ac').empty();
                    return {
                        results:  $.map(data, function (item) {
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
        $('#kredit').select2({
            placeholder: 'Cari Akun ....',
            ajax: {
                url: '{{ url('getACPerush') }}',
                minimumInputLength: 3,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    $('#id_ac').empty();
                    return {
                        results:  $.map(data, function (item) {
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
        $('#id_memo').select2({
            placeholder: 'Cari Memorial ....',
            ajax: {
                url: '{{ url('getMemorial') }}',
                minimumInputLength: 3,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    $('#id_pendapatan').empty();
                    return {
                        results:  $.map(data, function (item) {
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
        
        @if(get_admin() and isset($filter["filterperush"]->nm_perush))
        $("#filterperush").empty();
        $("#filterperush").append('<option value="{{ $filter["filterperush"]->id_perush }}">{{ strtoupper($filter["filterperush"]->nm_perush) }}</option>');
        @endif
        
        @if(isset($filter["id_memo"]->id_memorial))
        $("#id_memo").empty();
        $("#id_memo").append('<option value="{{ $filter["id_memo"]->id_memorial }}">{{ strtoupper($filter["id_memo"]->kode_memorial) }}</option>');
        @endif
        
        @if(isset($filter["debet"]->nama))
        $("#debet").empty();
        $("#debet").append('<option value="{{ $filter["debet"]->id_ac }}">{{ strtoupper($filter["debet"]->nama) }}</option>');
        @endif
        
        @if(isset($filter["kredit"]->nama))
        $("#kredit").empty();
        $("#kredit").append('<option value="{{ $filter["kredit"]->id_ac }}">{{ strtoupper($filter["kredit"]->nama) }}</option>');
        @endif
    </script>
    @endif
    @endsection