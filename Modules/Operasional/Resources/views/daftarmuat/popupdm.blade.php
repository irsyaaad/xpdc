<div class="row">
    <div class="col-md-5">
        <table class="table table-responsive table-sm">
            <thead>
                <tr>
                    <td width="30%">No. DM</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if (isset($data->kode_dm))
                            <span class="label label-inline label-light-primary font-weight-bold">
                                {{ $data->kode_dm }}
                            </span>
                            @endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="30%">Perusahaan Asal</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if (isset($data->perush_asal->nm_perush))
                                {{ strtoupper($data->perush_asal->nm_perush) }}
                            @endif
                        </b>
                    </td>
                </tr>
                @if ($data->is_ven)
                    <tr>
                        <td width="30%">Vendor Tujuan</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if (isset($data->vendor->nm_ven))
                                    {{ strtoupper($data->vendor->nm_ven) }}
                                @elseif(isset($data->perush_tujuan->nm_perush))
                                    {{ strtoupper($data->perush_tujuan->nm_perush) }}
                                @endif
                            </b>
                        </td>
                    </tr>
                @endif
                <tr>
                    <td width="30%">Kota Tujuan</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if (isset($data->wilayah_tujuan->nama_wil))
                                {{ strtoupper($data->wilayah_tujuan->nama_wil) }}
                            @endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="30%">Layanan</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if (isset($data->layanan->nm_layanan))
                                {{ strtoupper($data->layanan->nm_layanan) }}
                            @endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="30%">Status</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if (isset($data->status->nm_status))
                            <span class="label label-inline label-light-primary font-weight-bold">
                                {{ strtoupper($data->status->nm_status) }}
                            </span>
                            @endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="30%">Keterangan</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            {{ strtoupper($data->info) }}
                        </b>
                    </td>
                </tr>

            </thead>
        </table>
    </div>
    <div class="col-md-4">
        <table class="table table-responsive table-sm">
            <thead>

                <tr>
                    <td width="40%">Rencana Berangkat</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            {{ daydate($data->tgl_berangkat) . ', ' . dateindo($data->tgl_berangkat) }}
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="40%">Realisasi Berangkat</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        @if (isset($data->atd) and $data->atd != null)
                            <b>
                                {{ daydate($data->atd) . ', ' . dateindo($data->atd) }}
                            </b>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td width="40%">Estimasi Sampai</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            {{ daydate($data->tgl_sampai) . ', ' . dateindo($data->tgl_sampai) }}
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="40%">Realisasi Sampai</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        @if (isset($data->ata) and $data->ata != null)
                            <b>
                                {{ daydate($data->ata) . ', ' . dateindo($data->ata) }}
                            </b>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </thead>
        </table>
    </div>

    <div class="col-md-3">
        <table class="table table-responsive table-sm">
            <tr>
                <td width="40%">Est. Pendapatan</td>
                <td width="2%">
                    :
                </td>
                <td>
                    <b>
                        @if (isset($data->c_total))
                            {{ 'Rp. ' . number_format($data->c_total, 0, ',', '.') }}
                        @else
                            {{ 'Rp. 0,00' }}
                        @endif
                    </b>
                </td>
            </tr>
            <tr>
                <td width="40%">Proyeksi Biaya</td>
                <td width="2%">
                    :
                </td>
                <td>
                    <b>
                        @if (isset($data->c_pro))
                            {{ 'Rp. ' . number_format($data->c_pro, 0, ',', '.') }}
                        @else
                            {{ 'Rp. 0,00' }}
                        @endif
                    </b>
                </td>
            </tr>
            <tr>
                @php
                    $proyeksi = (float) $data->c_total - $data->c_pro;
                @endphp
                <td width="40%">Proyeksi Laba / Rugi</td>
                <td width="2%">
                    :
                </td>
                <td>
                    <b>
                        @if (isset($proyeksi))
                            {{ 'Rp. ' . number_format($proyeksi, 0, ',', '.') }}
                        @else
                            {{ 'Rp. 0,00' }}
                        @endif
                    </b>
                </td>
            </tr>
        </table>
    </div>
</div>
<table class="table table-responsive table-sm">
    <tr>
        <td width="40%">Dari Pelabuhan</td>
        <td width="2%"><b>:</b></td>
        <td>
            <b>
                {{ $data->nm_dari }}
            </b>
        </td>
    </tr>
    <tr>
        <td width="40%">Ke Pelabuhan</td>
        <td width="2%"><b>:</b></td>
        <td>
            <b>
                {{ $data->nm_tuju }}
            </b>
        </td>
    </tr>
    <tr>
        <td width="40%">PJ Asal</td>
        <td width="2%"><b>:</b></td>
        <td>
            <b>
                {{ $data->nm_pj_dr }}
            </b>
        </td>
    </tr>
    <tr>
        <td width="40%">PJ Tujuan</td>
        <td width="2%"><b>:</b></td>
        <td>
            <b>
                {{ $data->nm_pj_tuju }}
            </b>
        </td>
    </tr>
    <tr>
        @if (isset($data->no_container) and $data->no_container != null)
            <td width="30%">No. Container</td>
            <td width="2%"><b>:</b></td>
            <td>
                <span class="label label-inline label-light-danger font-weight-bold">
                    {{ $data->no_container }}
                </span>
            </td>
        @endif
    </tr>
    <tr>
        @if (isset($data->no_seal) and $data->no_seal != null)
            <td width="30%">No. Seal</td>
            <td width="2%"><b>:</b></td>
            <td>
                <span class="label label-inline label-light-danger font-weight-bold">
                    {{ $data->no_seal }}
                </span>
            </td>
        @endif
    </tr>
</table>
