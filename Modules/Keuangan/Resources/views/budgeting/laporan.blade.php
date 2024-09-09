@extends('template.document2')

@section('data')

@include("filter.filter-".Request::segment(1))
<style>
    th{
        text-align: center;
        padding: 5px 20px 5px 20px !important;
        vertical-align: center;
    }
</style>

<div class="col text-center mb-3">
    <h4>BUDGETING</h4>
    <h5>Periode : {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}}</h5>
</div>

<div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
    <table class="table table-responsive table-sm table-bordered" width="100%">
        <thead style="background-color: grey; color : #ffff">
            <tr>
                <th rowspan="2" >No</th>
                <th rowspan="2" >Nama Acount</th>
                <th colspan="4">{{ $filter['bulan_kemarin'] }}</th>
                <th colspan="4">{{ $filter['bulan_ini'] }}</th>
                <th colspan="4">{{ $filter['bulan_depan'] }}</th>
            </tr>
            <tr>
                <th>Budget</th>
                <th>Realisasi</th>
                <th>Selisih</th>
                <th>%</th>

                <th>Budget</th>
                <th>Realisasi</th>
                <th>Selisih</th>
                <th>%</th>

                <th>Budget</th>
                <th>Realisasi</th>
                <th>Selisih</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_budget_bulan_kemarin = 0;
                $total_realisasi_bulan_kemarin = 0;
                $total_selisih_bulan_kemarin = 0;
                
                $total_budget_bulan_ini = 0;
                $total_realisasi_bulan_ini = 0;
                $total_selisih_bulan_ini = 0;
                
                $total_budget_bulan_depan = 0;
                $total_realisasi_bulan_depan = 0;
                $total_selisih_bulan_depan = 0;
            @endphp
            @foreach ($ac as $key => $item)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td><a href="{{ route('showrugilabadetail', [
                        'id_ac' => $item->id_ac,
                        'dr_tgl' => $filter['dr_tgl'],
                        'sp_tgl' => $filter['sp_tgl'],
                        ]) }}" style="color:black;">{{$item->nama}}</td>
                    {{-- Bulan Kemarin --}}
                    <td class="text-right">
                        {{ isset($budgeting_bulan_kemarin[$item->id_ac]) ? number_format($budgeting_bulan_kemarin[$item->id_ac], 0, ',', '.') : '0' }}
                        @php
                            $total_budget_bulan_kemarin += isset($budgeting_bulan_kemarin[$item->id_ac]) ? $budgeting_bulan_kemarin[$item->id_ac] : 0;
                        @endphp
                    </td>
                    <td class="text-right">
                        @php
                            $total = 0;
                        @endphp
                        @if (isset($bulanKemarin['debit'][$item->id_ac]) && isset($bulanKemarin['kredit'][$item->id_ac]))  
                            @if ($item->def_pos == 'D')                                
                                {{ number_format($bulanKemarin['debit'][$item->id_ac]-$bulanKemarin['kredit'][$item->id_ac], 0, ',', '.') }}
                                @php
                                    $total = $bulanKemarin['debit'][$item->id_ac]-$bulanKemarin['kredit'][$item->id_ac];
                                @endphp
                            @endif
                            @if ($item->def_pos == 'K')
                                {{ number_format($bulanKemarin['kredit'][$item->id_ac]-$bulanKemarin['debit'][$item->id_ac], 0, ',', '.') }}
                                @php
                                    $total = $bulanKemarin['kredit'][$item->id_ac]-$bulanKemarin['debit'][$item->id_ac];
                                @endphp
                            @endif
                            @php
                                $total_realisasi_bulan_kemarin += $total;
                            @endphp
                        @endif                        
                    </td>
                    <td class="text-right">{{ isset($budgeting_bulan_kemarin[$item->id_ac]) ? toNumber($budgeting_bulan_kemarin[$item->id_ac]-$total) : '0' }}</td>
                        @php
                            $total_selisih_bulan_kemarin += isset($budgeting_bulan_kemarin[$item->id_ac]) ? $budgeting_bulan_kemarin[$item->id_ac]-$total : 0;
                        @endphp
                    <td>{{ (isset($budgeting_bulan_kemarin[$item->id_ac]) && $budgeting_bulan_kemarin[$item->id_ac] > 0 && $total > 0 ) ? round(($total / $budgeting_bulan_kemarin[$item->id_ac]) * 100, 2) : 0}}</td>

                    {{-- Sekarang --}}
                    <td class="text-right">{{ isset($budgeting_bulan_ini[$item->id_ac]) ? number_format($budgeting_bulan_ini[$item->id_ac], 0, ',', '.') : '0' }}</td>
                        @php
                            $total_budget_bulan_ini += isset($budgeting_bulan_ini[$item->id_ac]) ? $budgeting_bulan_ini[$item->id_ac] : 0;
                        @endphp
                    <td class="text-right">
                        @php
                            $total = 0;
                        @endphp
                        @if (isset($bulanIni['debit'][$item->id_ac]) && isset($bulanIni['kredit'][$item->id_ac]))  
                            @if ($item->def_pos == 'D')                                
                                {{ number_format($bulanIni['debit'][$item->id_ac]-$bulanIni['kredit'][$item->id_ac], 0, ',', '.') }}
                                @php
                                    $total = $bulanIni['debit'][$item->id_ac]-$bulanIni['kredit'][$item->id_ac];
                                @endphp
                            @endif
                            @if ($item->def_pos == 'K')
                                {{ number_format($bulanIni['kredit'][$item->id_ac]-$bulanIni['debit'][$item->id_ac], 0, ',', '.') }}
                                @php
                                    $total = $bulanIni['kredit'][$item->id_ac]-$bulanIni['debit'][$item->id_ac];
                                @endphp
                            @endif
                            @php
                                $total_realisasi_bulan_ini += $total;
                            @endphp
                        @endif                        
                    </td>
                    <td class="text-right">{{ isset($budgeting_bulan_ini[$item->id_ac]) ? toNumber($budgeting_bulan_ini[$item->id_ac]-$total) : '0' }}</td>
                        @php
                            $total_selisih_bulan_ini += isset($budgeting_bulan_ini[$item->id_ac]) ? $budgeting_bulan_ini[$item->id_ac]-$total : 0;
                        @endphp
                    <td>{{ (isset($budgeting_bulan_ini[$item->id_ac]) && $budgeting_bulan_ini[$item->id_ac] > 0 && $total > 0 ) ? round(($total / $budgeting_bulan_ini[$item->id_ac]) * 100, 2) : 0}}</td>

                    {{-- Bulan Depan --}}
                    <td class="text-right">{{ isset($budgeting_bulan_depan[$item->id_ac]) ? number_format($budgeting_bulan_depan[$item->id_ac], 0, ',', '.') : '0' }}</td>
                        @php
                            $total_budget_bulan_depan += isset($budgeting_bulan_depan[$item->id_ac]) ? $budgeting_bulan_depan[$item->id_ac] : 0;
                        @endphp
                    <td class="text-right">
                        @php
                            $total = 0;
                        @endphp
                        @if (isset($bulanDepan['debit'][$item->id_ac]) && isset($bulanDepan['kredit'][$item->id_ac]))  
                            @if ($item->def_pos == 'D')                                
                                {{ number_format($bulanDepan['debit'][$item->id_ac]-$bulanDepan['kredit'][$item->id_ac], 0, ',', '.') }}
                                @php
                                    $total = $bulanDepan['debit'][$item->id_ac]-$bulanDepan['kredit'][$item->id_ac];
                                @endphp
                            @endif
                            @if ($item->def_pos == 'K')
                                {{ number_format($bulanDepan['kredit'][$item->id_ac]-$bulanDepan['debit'][$item->id_ac], 0, ',', '.') }}
                                @php
                                    $total = $bulanDepan['kredit'][$item->id_ac]-$bulanDepan['debit'][$item->id_ac];
                                @endphp
                            @endif
                            @php
                                $total_realisasi_bulan_depan += $total;
                            @endphp
                        @endif                        
                    </td>
                    <td class="text-right">{{ isset($budgeting_bulan_depan[$item->id_ac]) ? toNumber($budgeting_bulan_depan[$item->id_ac]-$total) : '0' }}</td>
                        @php
                            $total_selisih_bulan_depan += isset($budgeting_bulan_depan[$item->id_ac]) ? $budgeting_bulan_depan[$item->id_ac]-$total : 0;
                        @endphp
                    <td>{{ (isset($budgeting_bulan_depan[$item->id_ac]) && $budgeting_bulan_depan[$item->id_ac] > 0 && $total > 0 ) ? round(($total / $budgeting_bulan_depan[$item->id_ac]) * 100, 2) : 0}}</td>

                </tr>
            @endforeach
            <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                <td colspan="2" class="text-center">Grand Total</td>
                <td class="text-right">{{ toNumber($total_budget_bulan_kemarin) }}</td>
                <td class="text-right">{{ toNumber($total_realisasi_bulan_kemarin) }}</td>
                <td class="text-right">{{ toNumber($total_selisih_bulan_kemarin) }}</td>
                <td>{{ ($total_budget_bulan_kemarin && $total_realisasi_bulan_kemarin) > 0 ? round(($total_realisasi_bulan_kemarin / $total_budget_bulan_kemarin) * 100, 2) : 0 }}</td>
                <td class="text-right">{{ toNumber($total_budget_bulan_ini) }}</td>
                <td class="text-right">{{ toNumber($total_realisasi_bulan_ini) }}</td>
                <td class="text-right">{{ toNumber($total_selisih_bulan_ini) }}</td>
                <td>{{ ($total_budget_bulan_ini && $total_realisasi_bulan_ini) > 0 ? round(($total_realisasi_bulan_ini / $total_budget_bulan_ini) * 100, 2) : 0 }}</td>
                <td class="text-right">{{ toNumber($total_budget_bulan_depan) }}</td>
                <td class="text-right">{{ toNumber($total_realisasi_bulan_depan) }}</td>
                <td class="text-right">{{ toNumber($total_selisih_bulan_depan) }}</td>
                <td>{{ ($total_budget_bulan_depan && $total_realisasi_bulan_depan) > 0 ? round(($total_realisasi_bulan_depan / $total_budget_bulan_depan) * 100, 2) : 0 }}</td>
            </tr>
        </tbody>
    </table>
</div>

@endsection

