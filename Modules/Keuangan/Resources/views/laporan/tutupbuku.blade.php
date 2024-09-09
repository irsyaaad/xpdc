@extends('template.document')

@section('data')
<h4 class="text-center">LAPORAN TUTUP BUKU</h4>
<br><br>
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include('template.filter2')
    @csrf
    <div class="row mt-1">
        <div class="col-md-12" >
            <table class="table table-borderless table-sm ">
                <thead style="background-color: grey; color : #ffff">
                    <th>Kode AC 4</th>
                    <th>Nama AC 4</th>
                    <th>Nominal</th>
                    <th>Periode (Tahun)</th>
                    <th>Nominal di Tutup</th>
                    <th>Action</th>
                </thead>
                <tbody >
                    @foreach($ac as $key => $value)
                    <tr style="{{ (isset($data[$value->id_ac]) && isset($tutup[$value->id_ac]) && ($data[$value->id_ac] != $tutup[$value->id_ac]->total)) ? 'color:red' : 'color:black' }}">
                        <td>{{$value->id_ac}}</td>
                        <td>{{$value->nama}}</td>
                        <td>@if(isset($data[$value->id_ac]))
                            @if ($value->id_ac == 3110)
                            Rp. {{ number_format($lababerjalan, 0, ',', '.') }}
                            @else
                            Rp. {{ number_format($data[$value->id_ac], 0, ',', '.') }}
                            @endif
                            @endif
                        </td>
                        <td>
                            {{$periode}}
                        </td>

                        <td>
                            @if (isset($tutup[$value->id_ac]))
                            Rp. {{ number_format($tutup[$value->id_ac]->total, 0, ',', '.') }}
                            @else
                            0
                            @endif
                        </td>
                        <td>
                            @if (isset($tutup[$value->id_ac]))
                            <button class="btn btn-warning btn-sm" onclick="edit({{$tutup[$value->id_ac]}})">
                                <i class="fa fa-pencil"></i>
                            </button>
                            @else
                            @if ($value->id_ac == 3110)
                            <button class="btn btn-success btn-sm" onclick="setBayar({{$value}},{{$lababerjalan}})">
                                <i class="fa fa-book"></i>
                            </button>
                            @else
                            <button class="btn btn-success btn-sm" onclick="setBayar({{$value}},{{$data[$value->id_ac]}})">
                                <i class="fa fa-book"></i>
                            </button>
                            @endif
                            
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>
<br>
{{-- <div class="text-right">
    <a href="{{ url(Request::segment(1)."/tutupbuku") }}" class="btn btn-success"><i class="fa fa-book"></i> Tutup Buku</a>
</div> --}}


<div class="modal fade" id="modal-dm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 10%; font-weight: bold;"><span><i class="fa fa-money"></i></span> INPUT SALDO AWAL</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php
            $ldate = date('Y-m-d H:i:s')
            @endphp
            <div class="modal-body">
                <form method="POST" action="{{ url(Request::segment(1)).'/savesaldo' }}" id="form-bayar">
                    @csrf
                    <table>
                        <thead>
                            <tr>
                                <th width="180px"> ID AC </th>
                                <th width="10px"> : </th>
                                <th> <input type="text" class="form-control no-border" readonly id="id_ac" name="id_ac"> </th>
                            </tr>
                            <tr>
                                <th width="180px"> Nama AC </th>
                                <th width="10px"> : </th>
                                <th> <input type="text" class="form-control no-border" readonly id="nm_ac" name="nm_ac"> </th>
                                <input type="hidden" name="def_pos" id="def_pos">
                            </tr>
                            <tr>
                                <th width="180px"> Periode </th>
                                <th width="10px"> : </th>
                                <th> <input type="text" class="form-control no-border" readonly id="tgl" name="tgl" value="{{$periode}}"> </th>
                            </tr>

                        </thead>
                    </table>
                    <br>


                    <div class="form-group">
                        <label for="n_bayar" >Nominal<span class="span-required"> *</span></label>
                        <input class="form-control" id="nominal" name="nominal" type="number" placeholder="Masukkan Saldo terakhir ..." />
                        @if ($errors->has('nominal'))
                        <label style="color: red">
                            {{ $errors->first('nominal') }}
                        </label>
                        @endif
                    </div>



                    <div class="col-md-12 text-right">

                        <button type="submit" class="btn btn-sm btn-success" id="modal-btn-si" >Simpan</button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 10%; font-weight: bold;"><span><i class="fa fa-money"></i></span> EDIT SALDO AWAL</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php
            $ldate = date('Y-m-d H:i:s')
            @endphp
            <div class="modal-body">
                <form method="POST" action="{{ url(Request::segment(1)).'/editsaldo' }}" id="form-edit">
                    @csrf
                    <table>
                        <thead>
                            <tr>
                                <th width="180px"> ID AC </th>
                                <th width="10px"> : </th>
                                <th> <input type="text" class="form-control no-border" readonly id="id_ac_e" name="id_ac_e"> </th>
                            </tr>
                            <input type="hidden" name="id_t" id="id_t">
                            <tr>
                                <th width="180px"> Periode </th>
                                <th width="10px"> : </th>
                                <th> <input type="text" class="form-control no-border" id="tgl_e" name="tgl_e" value="{{$periode}}"> </th>
                            </tr>

                        </thead>
                    </table>
                    <br>


                    <div class="form-group">
                        <label for="n_bayar" >Nominal<span class="span-required"> *</span></label>
                        <input class="form-control" id="nominal_e" name="nominal_e" type="number" placeholder="Masukkan Saldo terakhir ..." />
                        @if ($errors->has('nominal'))
                        <label style="color: red">
                            {{ $errors->first('nominal') }}
                        </label>
                        @endif
                    </div>



                    <div class="col-md-12 text-right">

                        <button type="submit" class="btn btn-sm btn-success" id="modal-btn-si" >Simpan</button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    function setBayar(data,nominal) {
        var today = new Date().toISOString().split('T')[0];
        $("#modal-dm").modal('show');
        $("#id_ac").val(data["id_ac"]);
        $("#nm_ac").val(data["nama"]);
        $("#def_pos").val(data["def_pos"]);
        $("#nominal").val(nominal);

        function goSubmitUpdate() {
            //alert("hello");
            $("#form-bayar").submit();
        }
    }

    function edit(data) {
        console.log(data);
        $("#modal-edit").modal('show');

        if (data["ac4_debit"] > 0) {
            console.log(data["ac4_debit"]);
            $("#id_ac_e").val(data["ac4_debit"]);
        } else {
            $("#id_ac").val(data["ac4_kredit"]);
        }

        $("#id_t").val(data["id"]);
        $("#nominal_e").val(data["total"]);
    }
</script>
@endsection
