@extends('templatev2.defaultlayout')

@section('toolbar_action')
    <div class="me-3">
        <button class="btn btn-sm btn-flex btn-light fw-bold" type="button" data-bs-toggle="collapse"
            data-bs-target="#filter-data" aria-expanded="false" aria-controls="filter-data">
            <i class="ki-outline ki-filter fs-5 me-1 text-gray-500"></i> Filter
        </button>
    </div>
@endsection
@section('content')
    @php
        $total_aktiva = 0;
        $url = $filter['show'];
    @endphp
    <div class="row mt-2">
        <div class="col">
            <div class="card">
                <div class="card-body d-flex flex-center flex-column pt-12 p-9">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4 text-dark fw-bold fs-6">
                        @foreach ($data1 as $key => $value)
                            @if ($value->id_ac == 1)
                                <tr>
                                    <td>{{ $value->nama }}</td>
                                </tr>
                                @if (isset($data2[$value->id_ac]))
                                    @foreach ($data2[$value->id_ac] as $key2 => $value2)
                                        <tr>
                                            <td style="padding-left:10px">
                                                <p>{{ $value2->nama }}</p>
                                            </td>
                                            @if (isset($data3[$value2->id_ac]))
                                                @foreach ($data3[$value2->id_ac] as $key3 => $value3)
                                        <tr>
                                            <td style="padding-left:50px">
                                                <a href="{{ $url . '&id_ac=' . $value3->id_ac }}"
                                                    class="text-dark">{{ $value3->nama }}</a>
                                            </td>
                                            @if (isset($nilai[$value3->id_ac]))
                                                <td class="text-end">
                                                    {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}
                                                </td>
                                                @php
                                                    $total_aktiva += $nilai[$value3->id_ac];
                                                @endphp
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                                </td>
                                </tr>
                            @endforeach
                        @endif
                        @endif
                        @endforeach
                        <tr>
                            <td>Total Aktiva</td>
                            <td style="text-align:right"> {{ number_format($total_aktiva, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        @php $total_pasiva = 0;  @endphp
        <div class="col">
            <div class="card">
                <div class="card-body d-flex flex-center flex-column pt-12 p-9">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4 text-dark fw-bold fs-6">
                        @foreach ($data1 as $key => $value)
                            @if ($value->id_ac == 2)
                                <tr>
                                    <td>{{ $value->nama }}</td>
                                </tr>
                                @if (isset($data2[$value->id_ac]))
                                    @foreach ($data2[$value->id_ac] as $key2 => $value2)
                                        <tr>
                                            <td style="padding-left:10px">
                                                <p>{{ $value2->nama }}</p>
                                            </td>
                                            @if (isset($data3[$value2->id_ac]))
                                                @foreach ($data3[$value2->id_ac] as $key3 => $value3)
                                        <tr>
                                            <td style="padding-left:50px"><a href="{{ $url . '&id_ac=' . $value3->id_ac }}"
                                                    class="text-dark">{{ $value3->nama }}</a></td>
                                            @if (isset($nilai[$value3->id_ac]))
                                                <td class="text-end">
                                                    {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}
                                                </td>
                                                @php $total_pasiva+=$nilai[$value3->id_ac] @endphp
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                                </td>
                                </tr>
                            @endforeach
                        @endif
                        @endif
                        @endforeach
                        @foreach ($data1 as $key => $value)
                            @if ($value->id_ac == 3)
                                <tr>
                                    <td>{{ $value->nama }}</td>
                                </tr>
                                @if (isset($data2[$value->id_ac]))
                                    @foreach ($data2[$value->id_ac] as $key2 => $value2)
                                        <tr>
                                            @if (isset($data3[$value2->id_ac]))
                                                @foreach ($data3[$value2->id_ac] as $key3 => $value3)
                                        <tr>
                                            <td style="padding-left:50px"><a
                                                    href="{{ route('showneraca', [
                                                        'id_ac' => $value3->id_ac,
                                                        'dr_tgl' => $filter['dr_tgl'],
                                                        'sp_tgl' => $filter['sp_tgl'],
                                                    ]) }}"
                                                    style="color:black;">{{ $value3->nama }}</a></td>
                                            @if (isset($lababerjalan) and $value3->id_ac == 321)
                                                <td class="text-end"> {{ number_format($lababerjalan, 0, ',', '.') }}
                                                </td>
                                                @php $total_pasiva+=$lababerjalan @endphp
                                            @elseif(isset($nilai[$value3->id_ac]))
                                                <td class="text-end">
                                                    {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}
                                                </td>
                                                @php $total_pasiva+=$nilai[$value3->id_ac] @endphp
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                                </td>
                                </tr>
                            @endforeach
                        @endif
                        @endif
                        @endforeach
                        <tr><td></td></tr>
                        <tr><td></td></tr>
                        <tr><td></td></tr>
                        <tr>
                            <td>Total Pasiva</td>
                            <td style="text-align:right"> {{ number_format($total_pasiva, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
