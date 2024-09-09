@extends('template.document2')

@section('data')
<form method="GET" action="{{ url(Request::segment(1)) }}">
    @csrf
    <div class="row">       
        <div class="col-md-3">
            <label style="font-weight: bold;">
                Pilih Tahun
            </label>
            <select name="tahun" class="form-control" id="filter-tahun" name="tahun">
                <?php for($i=date('Y', strtotime('+1 year')); $i>=date('Y')-10; $i-=1){ ?>
                    <option value="{{ $i }}" {{ $filter['tahun'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-3" style="margin-top: 30px"> 
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="fa fa-filter"></i> Filter
            </button>
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning">
                <i class="fa fa-retweet"></i> Reset
            </a>
        </div>
    </div>
</form>
    <style>
         th{
            text-align: center;
            padding: 5px 20px 5px 20px !important;
            vertical-align: center;
        }
        td {
            margin: 5px 20px 5px !important;
        }
    </style>
    
<div class="col text-center mb-3 mt-3">
    <h4>Proyeksi Tahunan</h4>
    <h5>Periode : {{ $filter['tahun'] }}</h5>
</div>   
<form action="{{ url('proyeksi-by-tahun/save') }}" method="POST">
    @csrf
    <div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
        <table class="table table-responsive table-sm table-bordered" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <th>AC4</th>
                <th>Nama AC</th>
                <th>Tahun</th>
                <th>Nominal</th>
                <th>Old (%)</th>
                <th>New (%)</th>
                <th>Proyeksi (Rp.)</th>
                <th>Proyeksi (Disimpan)</th>
            </thead>
            <tbody>
                @php
                    $old_total_proyeksi = 0;
                    $old_total_biaya = 0;
                    $total_biaya = 0;
                    $old_total_semua = 0;
                @endphp
                @foreach ($data2 as $item)
                    @isset($data3[$item->id_ac])
                        @foreach ($data3[$item->id_ac] as $parent)
                            @isset($data[$parent->id_ac])
                                @foreach($data[$parent->id_ac] as $key => $value)
                                    @if (isset($nilai['nilai'][$value->id_ac]) && $nilai['nilai'][$value->id_ac] > 0)
                                        <tr>
                                            <td>{{$value->id_ac}}</td>
                                            <td>{{$value->nama}}</td>
                                            <td>
                                                {{  $filter["tahun"]  }}
                                            </td>
                                            <td class="text-right">
                                                @if ($value->def_pos == 'K')                                                    
                                                    {{ isset($nilai['nilai'][$value->id_ac]) ? toNumber($nilai['nilai'][$value->id_ac]) : 0 }}
                                                @else
                                                    {{ isset($nilai['nilai'][$value->id_ac]) ? '-' . toNumber($nilai['nilai'][$value->id_ac]) : 0 }}
                                                @endif
                                            </td>
                                            @php
                                                if ($value->id_ac > 5000 || in_array($value->id_ac, [4050, 4060])) {
                                                    if ($value->def_pos == 'K') {
                                                        $total_biaya += isset($nilai['nilai'][$value->id_ac]) ? ($nilai['nilai'][$value->id_ac]) : 0 ; 
                                                    } else {
                                                        $total_biaya -= isset($nilai['nilai'][$value->id_ac]) ? ($nilai['nilai'][$value->id_ac]) : 0 ;
                                                    }                                                    
                                                }
                                            @endphp
                                            <td class="text-right">{{ ($nilai['total_pendapatan'] && $nilai['nilai'][$value->id_ac]) > 0 ? round(($nilai['nilai'][$value->id_ac]/$nilai['total_pendapatan']) * 100, 2) : '0' }}</td>
                                            <td>
                                                @if ($value->id_ac < 5000 && !in_array($value->id_ac, [4050,4060]))
                                                    <input class="form-control insert" type="number" step="any" 
                                                        name="prosentase_proyeksi[{{ $value->id_ac }}]" 
                                                        id="prosentase_proyeksi_{{ $value->id_ac }}" 
                                                        data-id-ac={{ $value->id_ac }}
                                                        data-def-pos={{ $value->def_pos }}
                                                        data-total-pendapatan={{ $nilai['total_pendapatan'] }}
                                                        value="{{ isset($proyeksi[$value->id_ac]->prosentase) ? $proyeksi[$value->id_ac]->prosentase : 0 }}">
                                                @else
                                                    <input type="number" step="any" 
                                                    class="form-control old-proyeksi" 
                                                    name="prosentase_proyeksi[{{ $value->id_ac }}]" 
                                                    data-id-ac={{ $value->id_ac }}
                                                    data-def-pos={{ $value->def_pos }}
                                                    value="@php
                                                        if (isset($proyeksi[$value->id_ac]->prosentase)) {
                                                            echo $proyeksi[$value->id_ac]->prosentase;
                                                        } else {
                                                           echo ($nilai['total_pendapatan'] && $nilai['nilai'][$value->id_ac]) > 0 ? round(($nilai['nilai'][$value->id_ac]/$nilai['total_pendapatan']) * 100, 2) : '0';
                                                        }   
                                                    @endphp">
                                                @endif
                                            </td>
                                            <td class="text-right" style="width:250px">
                                                @if ($value->id_ac < 5000 && !in_array($value->id_ac, [4050,4060]))
                                                    <input type="text" class="form-control new-proyeksi" 
                                                        name="n_proyeksi[{{ $value->id_ac }}]" 
                                                        id="n_proyeksi_{{ $value->id_ac }}" 
                                                        data-def-pos={{ $value->def_pos }} readonly value="{{ isset($proyeksi[$value->id_ac]->proyeksi) ? $proyeksi[$value->id_ac]->proyeksi : 0 }}">
                                                @else
                                                    <input type="text" class="form-control" 
                                                        name="n_proyeksi[{{ $value->id_ac }}]" 
                                                        id="n_proyeksi_biaya_{{ $value->id_ac }}" readonly value="{{ isset($proyeksi[$value->id_ac]->proyeksi) ? $proyeksi[$value->id_ac]->proyeksi : 0 }}">
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @php
                                                    if (isset($proyeksi[$value->id_ac]->proyeksi)) {
                                                        if ($value->def_pos == 'D') {
                                                            echo '-' . toNumber($proyeksi[$value->id_ac]->proyeksi);
                                                        } else {
                                                            echo toNumber($proyeksi[$value->id_ac]->proyeksi);
                                                        }                                        
                                                    }
                                                @endphp
                                            </td>
                                        </tr>
                
                                        @php
                                            if ($value->id_ac < 5000 && !in_array($value->id_ac, [4050,4060])) {
                                                if ($value->def_pos == 'K') {
                                                    $old_total_proyeksi += isset($proyeksi[$value->id_ac]->proyeksi) ? ($proyeksi[$value->id_ac]->proyeksi) : 0;
                                                } else {
                                                    $old_total_proyeksi -= isset($proyeksi[$value->id_ac]->proyeksi) ? ($proyeksi[$value->id_ac]->proyeksi) : 0;
                                                }                                
                                            } else {
                                                if ($value->def_pos == 'K') {
                                                    $old_total_biaya += isset($proyeksi[$value->id_ac]->proyeksi) ? ($proyeksi[$value->id_ac]->proyeksi) : 0;
                                                } else {
                                                    $old_total_biaya -= isset($proyeksi[$value->id_ac]->proyeksi) ? ($proyeksi[$value->id_ac]->proyeksi) : 0;
                                                }   
                                            }
                
                                            if ($value->def_pos == 'K') {
                                                $old_total_semua += isset($proyeksi[$value->id_ac]->proyeksi) ? ($proyeksi[$value->id_ac]->proyeksi) : 0;
                                            } else {
                                                $old_total_semua -= isset($proyeksi[$value->id_ac]->proyeksi) ? ($proyeksi[$value->id_ac]->proyeksi) : 0;
                                            }  
                                        @endphp
                                    @endif
                                @endforeach
                            @endisset
                            @if ($parent->id_ac == 412)
                                <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                                    <td colspan="3" class="text-center">Total</td>
                                    <td class="text-right">{{ isset($nilai['total_pendapatan']) ? toNumber($nilai['total_pendapatan']) : 0 }}</td>
                                    <td></td>
                                    <td id="total_prosentase"></td>
                                    <td id="total_new_proyeksi"></td>
                                    <td class="text-right">{{ toNumber($old_total_proyeksi) }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endisset
                @endforeach
                
                <tr style="background-color: rgb(233, 231, 231)" class="tr-bold">
                    <td colspan="3" class="text-center">Total</td>
                    <td class="text-right">{{ isset($total_biaya) ? toNumber($total_biaya) : 0 }}</td>
                    <td colspan="3"></td>
                    <td class="text-right">{{ toNumber($old_total_biaya) }}</td>
                </tr>
                <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                    <td colspan="7" class="text-center">Total</td>
                    <td class="text-right">{{ toNumber($old_total_semua) }}</td>
                </tr>
                <input type="hidden" name="tahun" value="{{ $filter['tahun'] }}">
            </tbody>
        </table>
    </div>
    <br>
    <div class="text-right">
        <button type="submit" class="btn btn-sm btn-primary">
            <i class="fa fa-save"></i> Simpan Data
        </button>
    </div>
</form>    
@endsection

@section('script')
<script>
    $('#filter-tahun').select2();

    total_prosentase();

    $(".insert").on("keyup change", function(e) {
        var id_ac = $(this).data('id-ac');
        var def_pos = $(this).data('def-pos');
        var total_pendapatan = $(this).data('total-pendapatan');
        var prosentase = this.value;

        var proyeksi = ((total_pendapatan * prosentase)/ 100);

        // console.log(prosentase, '%');
        // console.log(rupiah(proyeksi), '= ', prosentase, ' x ', rupiah(total_pendapatan));

        if (def_pos == 'K') {            
            $(`#n_proyeksi_${id_ac}`).val(rupiah(proyeksi));
        } else {            
            var nilai = '-' + rupiah(proyeksi);
            $(`#n_proyeksi_${id_ac}`).val(nilai);
        }
        total_prosentase();
    })

    $(".old-proyeksi").on("keyup change", function(e) {
        var id_ac = $(this).data('id-ac');
        var def_pos = $(this).data('def-pos');
        var prosentase = this.value;
        var total_pendapatan = $('#total_new_proyeksi').html();
        total_pendapatan = total_pendapatan.replace(/\D/g,'');
        console.log(total_pendapatan);

        var proyeksi = ((total_pendapatan * prosentase)/ 100);

        // console.log(prosentase, '%');
        // console.log(rupiah(proyeksi), '= ', prosentase, ' x ', rupiah(total_pendapatan));

        if (def_pos == 'K') {            
            $(`#n_proyeksi_${id_ac}`).val(rupiah(proyeksi));
        } else {            
            var nilai = '-' + rupiah(proyeksi);
            $(`#n_proyeksi_${id_ac}`).val(nilai);
        }
        total_prosentase();
    })


    function rupiah(nilai) {
        var bilangan = Math.ceil(nilai);
        var	number_string = bilangan.toString(),
        sisa 	= number_string.length % 3,
        rupiah 	= number_string.substr(0, sisa),
        ribuan 	= number_string.substr(sisa).match(/\d{3}/g);


        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah;
    }

    function total_prosentase() {
        var total_prosentase = 0;
        var total_proyeksi = 0;
        const arr = [];

        $('.insert').each(function(){
            var def_pos = $(this).data('def-pos');
            if (def_pos == 'K') {
                total_prosentase += parseFloat(this.value) || 0; 
            } else {
                total_prosentase -= parseFloat(this.value) || 0;
            }      
        });

        $('.new-proyeksi').each(function(){
            nilai = this.value.replace(/\D/g,'');
            var def_pos = $(this).data('def-pos');
            if (def_pos == 'K') {
                total_proyeksi += parseFloat(nilai) || 0;   
            } else {
                total_proyeksi -= parseFloat(nilai) || 0;   
            }
            arr.push(def_pos);
        });

        $(`#total_prosentase`).text(total_prosentase);
        $(`#total_new_proyeksi`).text(rupiah(total_proyeksi));

        set_proyeksi(total_proyeksi);
        
    }

    function set_proyeksi(params) {
        $('.old-proyeksi').each(function(){
            var id_ac = $(this).data('id-ac');
            var prosentase = parseFloat(this.value) || 0;
            var proyeksi = ((params * prosentase)/ 100);
            $(`#n_proyeksi_biaya_${id_ac}`).val(rupiah(proyeksi));
        });
    }
    
</script>
@endsection
