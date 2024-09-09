@extends('template.document')

@section('data')
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('saveupdatestatus') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-4">
            <label>
                Pilih Status
            </label>
            <select name="id_status" id="id_status" class="form-control">
                <option value="">-- Pilih Status --</option>
                @foreach($status as $key => $value)
                <option value="{{$value->kode_status}}">{{ strtoupper($value->nm_ord_stt_stat) }}</option>
                @endforeach
            </select>            
        </div>
        
        <div class="col-md-4">
            <label>
                Pilih Wilayah
            </label>
            <select class="form-control" id="id_kota" name="id_kota" required></select>
        </div>

        <div class="col-md-4">
            <label>
                Pilih Tanggal Update
            </label>
            <input type="date" name="tgl_update" id="tgl_update" class="form-control">
        </div>

        <div class="col-md-12">
            <label>
                Keterangan
            </label>
            <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="5" placeholder="Menggunakan Kapal ..."></textarea>
        </div>

        <div class="col-md-12 text-right" style="margin-top:25px">
            <button type="submit" class="btn btn-success">
                <i class="fa fa-save"></i> Update
            </button>
        </div>
    </div>
    <br>
    <hr>
    <table class="table table-responsive table-bordered" id="tableasal">
        <thead style="background-color: grey; color : #ffff">
            <tr>
                <th>No. </th>
                <th>Kode STT</th>
                <th>Pengirim</th>
                <th>Penerima</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
            $total = 0;
            @endphp
            @foreach($detail as $key => $value)
            <tr>
                <td>{{ $key+1 }} </td>
                <td>
                    <a href="#" onclick="myFunction('{{ $value->id_stt }}')" class="class-edit">
                        {{ strtoupper($value->kode_stt) }}
                    </a><br>{{ dateindo($value->tgl_masuk) }}
                </td>
                <td>{{ strtoupper($value->pengirim_nm)}}
                    <br>
                    <span class="label label-inline label-light-primary font-weight-bold">
                        {{$value->pengirim_telp}}</span>
                        <br>
                        <span >{{$value->pengirim_alm}}</span>
                    </td>
                    <td>@isset($value->penerima_nm)
                        {{ strtoupper($value->penerima_nm)}}
                        @endisset
                        <br>
                        <span class="label label-inline label-light-primary font-weight-bold">
                            @isset($value->penerima_telp)
                            {{$value->penerima_telp}}
                            @endisset
                        </span>
                        <br>
                        <span>
                            @isset($value->penerima_alm)
                            {{$value->penerima_alm}}
                            @endisset
                        </span>
                    </td>
                    <td class="text-center">
                        {{strtoupper($value->nm_status)}}
                    </td>
                    <td class="text-center">
                        <input type="checkbox" class="form-control" name="id_stt[]" id="id_stt" value="{{$value->id_stt}}">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
    <script>
        $("#tgl_update").val('{{ date("Y-m-d") }}');
        $('#id_kota').select2({
            minimumInputLength: 0,
            placeholder: 'Cari Kota ....',
            allowClear: true,
            ajax: {
                url: '{{ url('getKota') }}',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    $('#id_kota').empty();
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
    </script>
    @endsection
    