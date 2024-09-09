@extends('template.document2')

@section('data')
@if(Request::segment(1)=="neracabyperkiraan" && (Request::segment(2)==null or Request::segment(2)=="filter"))
@include("template.filter2")

<table class="table table-sm table-bordered">
    <thead style="background-color: grey; color : #ffff">
    <tr>
        <th rowspan=2 class="text-center">No</th>
        <th rowspan=2 class="text-center">AC 4</th>
        <th rowspan=2 class="text-center">Perkiraan 4</th>
        <th colspan=2 class="text-center">Saldo Awal</th>
        <th colspan=3 class="text-center">Transaksi Bulanan</th>
        <th colspan=3 class="text-center">Total Berjalan</th>
    </tr>
    <tr>
        <th class="text-center">Debet</th>    
        <th class="text-center">Kredit</th>
        <th class="text-center">Debet</th>    
        <th class="text-center">Kredit</th>
        <th class="text-center">Total</th>
        <th class="text-center">Debet</th>    
        <th class="text-center">Kredit</th>
        <th class="text-center">Total</th>   
    </tr>
    </thead>
    <tbody>
    @foreach($data1 as $key => $value)
        @if($value->id_ac < 4)
        <tr style="background-color: grey; color:#ffff"><td colspan=11>{{$value->nama}}</td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr style="background-color: grey; color:#ffff">
                    <td colspan=11>{{$value2->nama}}</td>                    
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr style="background-color: #e3e3e3;">
                                <td colspan=11>{{$value3->nama}}</td>
                                    @if(isset($data4[$value3->id_ac]))
                                        @php
                                        $total = 0;
                                        $total_deb = 0;
                                        $total_kre = 0;
                                        $total_deb_sa = 0;
                                        $total_kre_sa = 0;
                                        $total_deb_bi = 0;
                                        $total_kre_bi = 0;
                                        $total_tot_bi = 0;
                                        @endphp
                                        @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                            <tr>
                                                <td></td>
                                                <td>{{$value4->ac_perush}}</td>
                                                <td>{{$value4->nama_ac_perush}}</td>
                                                <td>Rp. {{ number_format($sa_debit[$value4->ac_perush], 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($sa_kredit[$value4->ac_perush], 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($bulanini_deb[$value4->ac_perush], 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($bulanini_kre[$value4->ac_perush], 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($bulanini_deb[$value4->ac_perush]-$bulanini_kre[$value4->ac_perush], 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($value4->debit, 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($value4->kredit, 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($value4->debit-$value4->kredit, 0, ',', '.') }}</td>
                                                @php
                                                $total += $value4->debit-$value4->kredit;
                                                $total_deb += $value4->debit;
                                                $total_kre += $value4->kredit;
                                                $total_deb_sa += $sa_debit[$value4->ac_perush];
                                                $total_kre_sa += $sa_kredit[$value4->ac_perush];
                                                $total_deb_bi += $bulanini_deb[$value4->ac_perush];
                                                $total_kre_bi += $bulanini_kre[$value4->ac_perush];
                                                $total_tot_bi += $bulanini_deb[$value4->ac_perush]-$bulanini_kre[$value4->ac_perush];
                                                @endphp
                                            </tr>
                                        @endforeach
                                        <tr style="background-color: #d5e8e8;">
                                              <td colspan=3 rowspan=2 class="text-center">Sub Total {{$value3->nama}}</td>   
                                              <td>Rp. {{ number_format($total_deb_sa, 0, ',', '.') }}</td> 
                                              <td>Rp. {{ number_format($total_kre_sa, 0, ',', '.') }}</td>  
                                              <td>Rp. {{ number_format($total_deb_bi, 0, ',', '.') }}</td>  
                                              <td>Rp. {{ number_format($total_kre_bi, 0, ',', '.') }}</td>  
                                              <td>Rp. {{ number_format($total_tot_bi, 0, ',', '.') }}</td>   
                                              <td>Rp. {{ number_format($total_deb, 0, ',', '.') }}</td>    
                                              <td>Rp. {{ number_format($total_kre, 0, ',', '.') }}</td>   
                                              <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>                                  
                                        </tr>
                                        <tr style="background-color: #d5e8e8;">
                                            <td colspan=2 class="text-center">Rp. {{ number_format($total_deb_sa-$total_kre_sa, 0, ',', '.') }}</td> 
                                            <td colspan=3 class="text-center">Rp. {{ number_format($total_tot_bi, 0, ',', '.') }}</td>
                                            <td colspan=3 class="text-center">Rp. {{ number_format($total, 0, ',', '.') }}</td> 
                                        </tr>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                @endforeach
            @endif
        @endif
    @endforeach
    </tbody>
</table>

@endif
@endsection