@extends('template.document')

@section('data')
<form method="GET" action="{{ url()->current() }}">
    @csrf
    <div class="row">
        <div class="col-md-3">
            <label style="font-weight: bold;">
                Pilih Bulan
            </label>
            <select class="form-control" id="f_bulan" name="f_bulan">
                <option value="">-- Pilih Bulan --</option>
                <option value="01">  Januari  </option>
                <option value="02">  Februari  </option>
                <option value="03">  Maret  </option>
                <option value="04">  April  </option>
                <option value="05">  Mei  </option>
                <option value="06">  Juni  </option>
                <option value="07">  Juli  </option>
                <option value="08">  Agustus  </option>
                <option value="09">  September  </option>
                <option value="10">  Oktober  </option>
                <option value="11">  November  </option>
                <option value="12">  Desember  </option>
            </select>
        </div>
        
        <div class="col-md-3">
            <label style="font-weight: bold;">
                Pilih Tahun
            </label>
            <select name="tahun" class="form-control" id="f_tahun" name="f_tahun">
                <option selected="selected" value="">-- Pilih Tahun --</option>
                <?php for($i=date('Y'); $i>=date('Y')-10; $i-=1){ ?>
                <option value="{{ $i }}">{{ $i }}</option>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-6" style="margin-top: 30px"> 
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="fa fa-filter"></i> Filter
            </button>
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning">
                <i class="fa fa-retweet"></i> Reset
            </a>
            <a href="{{ url(Request::segment(1)) . '/generate' }}" class="btn btn-sm btn-success">
                <i class="fa fa-refresh"></i> Generate from Proyeksi Tahunan
            </a>
        </div>
    </div>
    <table class="table table-responsive table-striped mt-2">
        <thead style="background-color: grey; color : #ffff">
            <th>AC4</th>
            <th>Nama AC</th>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Proyeksi (Rp.)</th>
            <th>Action</th>
        </thead>
        <tbody>
            @foreach($data as $key => $value)
            <tr>
                <td>{{$value->id_ac}}</td>
                <td>{{$value->nama}}</td>
                <td>
                    {{ $filter["f_bulan"] }}
                </td>
                <td>
                    {{  $filter["f_tahun"]  }}
                </td>
                <td>@if(isset($nilai[$value->id_ac]))
                    {{ toNumber($nilai[$value->id_ac]->proyeksi) }}
                    @else 
                    0 
                    @endif
                </td>
                <td>
                    @if (isset($nilai[$value->id_ac]))
                    <button type="button" class="btn btn-warning btn-sm" onclick="edit({{$nilai[$value->id_ac]}},{{$value}})">
                        <i class="fa fa-pencil"></i>
                    </button>
                    @else
                    <button type="button" class="btn btn-success btn-sm" title="Set Proyeksi" onclick="setBayar({{$value}})">
                        <i class="fa fa-book"></i>
                    </button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>

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
                <form method="POST" action="{{ url(Request::segment(1))}}" id="form-bayar">
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
                                <th width="180px"> Bulan </th>
                                <th width="10px"> : </th>
                                <th>
                                    <select class="form-control" id="bulan" name="bulan">
                                        <option value="">-- Pilih Bulan --</option>
                                        <option value="01">  Januari  </option>
                                        <option value="02">  Februari  </option>
                                        <option value="03">  Maret  </option>
                                        <option value="04">  April  </option>
                                        <option value="05">  Mei  </option>
                                        <option value="06">  Juni  </option>
                                        <option value="07">  Juli  </option>
                                        <option value="08">  Agustus  </option>
                                        <option value="09">  September  </option>
                                        <option value="10">  Oktober  </option>
                                        <option value="11">  November  </option>
                                        <option value="12">  Desember  </option>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <th width="180px"> Tahun </th>
                                <th width="10px"> : </th>
                                <th> <input type="text" class="form-control no-border" id="tahun" name="tahun" value="{{ $filter["f_tahun"] }}"> </th>
                            </tr>
                            
                        </thead>
                    </table>
                    <br>
                    
                    
                    <div class="form-group">
                        <label for="n_bayar" >Nominal<span class="span-required"> *</span></label>
                        <input class="form-control" id="nominal" name="nominal" type="number" placeholder="Masukkan Nominal Proyeksi ..." />
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
                                <input type="hidden" name="id_pro" id="id_pro">
                            </tr>
                            <tr>
                                <th width="180px"> Nama AC </th>
                                <th width="10px"> : </th>
                                <th> <input type="text" class="form-control no-border" readonly id="nm_ac_e" name="nm_ac_e"> </th>
                                <input type="hidden" name="def_pos" id="def_pos">
                            </tr>
                            <tr>
                                <th width="180px"> Bulan </th>
                                <th width="10px"> : </th>
                                <th>
                                    <select class="form-control" id="bulan_e" name="bulan_e">
                                        <option value="">-- Pilih Bulan --</option>
                                        <option value="01">  Januari  </option>
                                        <option value="02">  Februari  </option>
                                        <option value="03">  Maret  </option>
                                        <option value="04">  April  </option>
                                        <option value="05">  Mei  </option>
                                        <option value="06">  Juni  </option>
                                        <option value="07">  Juli  </option>
                                        <option value="08">  Agustus  </option>
                                        <option value="09">  September  </option>
                                        <option value="10">  Oktober  </option>
                                        <option value="11">  November  </option>
                                        <option value="12">  Desember  </option>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <th width="180px"> Tahun </th>
                                <th width="10px"> : </th>
                                <th> <input type="text" class="form-control no-border" id="tahun_e" name="tahun_e" value="{{ $filter["f_tahun"] }}"> </th>
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
@endsection

@section('script')
<script>
    @if(isset($filter["f_tahun"]))
    $("#f_tahun").val("{{ $filter["f_tahun"] }}");
    @endif
    
    @if(isset($filter["f_bulan"]))
    $("#f_bulan").val("{{ $filter["f_bulan"] }}");
    @endif
    
    function setBayar(data) {
        var today = new Date().toISOString().split('T')[0];
        $("#modal-dm").modal('show');
        $("#id_ac").val(data["id_ac"]);
        $("#nm_ac").val(data["nama"]);
        
        function goSubmitUpdate() {
            $("#form-bayar").submit();
        }
    }
    
    function edit(data,proyeksi) {
        console.log(data);
        $("#modal-edit").modal('show');
        var d = new Date(data['tgl']);
        var bulan = d.getMonth()+1;
        if (bulan < 10) {
            bulan ="0"+bulan;
        } else {
            
        }
        $("#id_ac_e").val(data["ac4"]);
        $("#nm_ac_e").val(proyeksi["nama"]);
        $("#bulan_e").val(bulan);
        $("#tahun_e").val(d.getFullYear());
        $("#nominal_e").val(data["proyeksi"]);
        $("#id_pro").val(data["id"]);
    }
    
</script>
@endsection
