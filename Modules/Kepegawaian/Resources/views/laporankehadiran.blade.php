@extends('template.document2')
@section('data')
@if(Request::segment(1)=="laporankehadiran" or Request::segment(2)=="filter")
<div id="accordion" style="margin-top: -25px">
    <div class="card border-0">
        <div id="headingOne" class="text-right">
            <button class="btn btn-md btn-primary" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <i class="fa fa-filter"> </i> Filter
            </button>
        </div>
        
        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-search">
                    @csrf
                    <div class="row">

                        <div class="col-md-3">
                            <b>Perusahaan : </b>
                            <div class="m-form__control">
                                <select class="form-control" id="f_id_perush" name="f_id_perush">
                                    <option value="">-- Pilih Perusahaan --</option>
                                    @if(get_admin())
                                    @foreach($perusahaan as $key => $value)
                                    <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
                                    @endforeach
                                    @else
                                    @foreach($role_perush as $key => $value)
                                    <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <b>Bulan : </b> 
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
                            <b>Tahun : </b> 
                            <select name="tahun" class="form-control" id="f_tahun" name="f_tahun">
                                <option selected="selected" value="">-- Pilih Tahun --</option>
                                <?php
                                for($i=date('Y'); $i>=date('Y')-10; $i-=1){
                                    echo"<option value='$i'> $i </option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3" style="padding-top: 25px">
                            @include('kepegawaian::filter.eksport')
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@php
$bulan = date("m", strtotime($tgl));
$tahun = date("y", strtotime($tgl));
$days = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
@endphp

<div class="row">
    <div class="col-md-12" style="overflow-x:auto;">
        <table class="table table-responsive table-bordered table-sm" width="100%" id="mytable" style="margin-top: 5px">
            <thead style="background-color: grey; color : #ffff">
                <tr rowspan="2">
                    <th>Nama Karyawan</th>
                    @php 
                    $bulan = date('M', strtotime($tgl));
                    $tahun = date('Y', strtotime($tgl));
                    @endphp
                    <th colspan="{{ $days }}" class="text-center">{{$bulan}} - {{$tahun}}</th>
                    <th rowspan="2" class="text-center">Total Kehadiran</th>
                </tr>
                <tr>
                    <th></th>
                    @for($i = 1; $i<=$days; $i++)
                    <th class="text-center">{{$i}}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach($karyawan as $key => $value)
                <tr>
                    @php
                    $total = 0;
                    @endphp
                    <td>{{$value->nm_karyawan}}</td>
                    @for($i = 1; $i<=$days; $i++)
                    @php
                    $minggu = date("Y-m-d", strtotime($tgl."-".$i));
                    $minggu = daydate($minggu);
                    @endphp
                    @if(isset($day[$value->id_karyawan][$i]) and $minggu!="Minggu")
                    @php
                    $total += 1;
                    @endphp
                    <td>
                        <i class="fa fa-check text-success"></i>
                    </td>
                    @elseif($minggu=="Minggu")
                    <td>
                        
                    </td>
                    @else
                    <td> 
                        <i class="fa fa-times text-danger"></i>
                    </td>
                    @endif
                    @endfor
                    <td class="text-center">
                        {{ $total }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@else

@endif

@endsection
@section('script')
<script>
    var bln  = "{{ date('m') }}";
    var tahun = "{{ date('Y') }}";
    
    @if(isset($filter["f_bulan"]))
    $("#f_bulan").val('{{ $filter["f_bulan"] }}');
    @else
    $("#f_bulan").val(bln);
    @endif
    
    @if(isset($filter["f_tahun"]))
    $("#f_tahun").val('{{ $filter["f_tahun"] }}');
    @else
    $("#f_tahun").val(tahun);
    @endif
    
    @if(isset($filter["f_status"]))
    $("#f_status").val('{{ $filter["f_status"] }}');
    @endif
    
    $("#f_id_perush").select2();
    
    @if(isset($filter["f_id_perush"]))
    $("#f_id_perush").val('{{ $filter["f_id_perush"] }}').trigger("change");
    @endif
    
    function html(){
        var url = "{{ url(Request::segment(1)."/cetak") }}";
        window.open(url);
    }

    function excel(){
        window.location = "{{ url(Request::segment(1)."/excel") }}";
        window.open(url);
    }
    
</script>
@endsection