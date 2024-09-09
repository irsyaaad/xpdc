<style>
    .col-md-4{
        margin-top: 15px;
    }
    
    .col-md-12{
        margin-top: 15px;
    }
    
    #divbayar{
        margin-top: 15px;
        border-radius: 10px;
        padding: 5px;
        padding-bottom: 10px;
    }
</style>
<div class="row">
    <div class="col-md-6">
        <h4><i class="fa fa-thumb-tack"></i>
            <b>Data Handling</b>
        </h4>
    </div>
    <div class="col-md-6 text-right">
        @if(Request::segment(3)=="bayar")
        <a href="{{ url(Request::segment(1)."/create") }}" class="btn btn-warning">
            <i class="fa fa-reply"></i>	Kembali
        </a>
        @else
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-warning">
            <i class="fa fa-reply"></i>	Kembali
        </a>
        @endif
    </div> 
    
    <div class="col-md-12" style="margin-top:0.5%">
        <table class="table table-responsive">
            <thead>
                <tr>
                    <td width="13%">No. Handling</td>
                    <td width="2%"><b>:</b></td>
                    <td width="15%">
                        <b>
                            @if(isset($handling->id_handling)){{ strtoupper($handling->id_handling) }}@endif
                        </b>
                    </td>

                    <td width="7%">Armada</td>
                    <td width="2%"><b>:</b></td>
                    <td width="15%">
                        <b>
                            @if(isset($handling->armada->nm_armada)){{ strtoupper($handling->armada->nm_armada) }}@endif
                        </b>
                    </td>

                    <td width="9%">Tgl Berangkat</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->tgl_berangkat) and $handling->tgl_berangkat!=null)
						<b>
							{{ dateindo($handling->tgl_berangkat) }}
						</b>
						@else
						-
						@endif
                        </b>
                    </td>
                    
                    <td width="3%">Total</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->n_bayar)){{ "Rp. ".number_format($handling->n_bayar, 0, ',', '.') }} @else {{ "Rp. 0,00" }} @endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="13%">Perusahaan</td>
                    <td width="2%"><b>:</b></td>
                    <td width="15%">
                        <b>
                            @if(isset($handling->perusahaan->nm_perush)){{ strtoupper($handling->perusahaan->nm_perush) }}@endif
                        </b>
                    </td>

                    <td width="7%">Sopir</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->sopir->nm_sopir)){{ strtoupper($handling->sopir->nm_sopir) }}@endif
                        </b>
                    </td>

                    <td width="7%">Tgl Selesai</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->tgl_selesai) and $handling->tgl_selesai!=null)
						<b>
							{{ dateindo($handling->tgl_selesai) }}
						</b>
						@else
						-
						@endif
                        </b>
                    </td>

                    <td width="5%">Biaya</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->c_biaya)){{ "Rp. ".number_format($handling->c_biaya, 0, ',', '.') }} @else {{ "Rp. 0,00" }} @endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="13%">Perusahaan Pengirim</td>
                    <td width="2%"><b>:</b></td>
                    <td width="15%">
                        <b>
                            @if(isset($handling->perusahaankirim->nm_perush)){{ strtoupper($handling->perusahaankirim->nm_perush) }}@endif
                        </b>
                    </td>
                    
                    <td width="10%">Kota Asal </td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->asal->nama_wil)){{ strtoupper($handling->asal->nama_wil) }}@endif
                        </b>
                    </td>

                    <td width="7%">Jam Berangkat </td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->waktu_berangkat)){{ strtoupper($handling->waktu_berangkat) }}@endif
                        </b>
                    </td>

                    <td width="7%">KM Awal </td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->km_awal)){{ strtoupper($handling->km_awal." KM / H") }}@endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="13%">Layanan </td>
                    <td width="2%"><b>:</b></td>
                    <td width="15%">
                        <b>
                            @if(isset($handling->layanan->nm_layanan)){{ strtoupper($handling->layanan->nm_layanan) }}@endif
                        </b>
                    </td>

                    <td width="10%">Kota Tujuan</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->nm_tuju)){{ strtoupper($handling->nm_tuju) }}@endif
                        </b>
                    </td>

                    <td width="5%">Jam Selesai</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->waktu_selesai)){{ strtoupper($handling->waktu_selesai) }}@endif
                        </b>
                    </td>

                    <td width="7%">KM Akhir </td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->km_akhir)){{ strtoupper($handling->km_akhir." KM / H") }}@endif
                        </b>
                    </td>
                </tr>
                <tr> 
                    @if(isset($handling->layanan->kode_layanan) and $handling->layanan->kode_layanan=="C")
                    <td width="5%">No. Seal</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->no_seal)){{ strtoupper($handling->no_seal) }}@endif
                        </b>
                    </td>
                    <td width="5%">No. Container</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->no_container)){{ strtoupper($handling->no_container) }}@endif
                        </b>
                    </td>
                    @endif

                    <td width="3%">Keterangan</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->info)){{ strtoupper($handling->info) }}@endif
                        </b>
                    </td>

                    <td width="5%">Status</td>
                    <td width="2%"><b>:</b></td>
                    <td width="15%">
                        <b>
                            @if(isset($handling->status->nm_status)){{ strtoupper($handling->status->nm_status) }}@endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td colspan="9"></td>
                    <td width="8%">Status Approve</td>
                    <td width="2%"><b>:</b></td>
                    <td width="15%">
                        <b>
                            @if(isset($handling->is_approve) and $handling->is_approve==true)
                            {{ "Approved" }}
                            @else
                            {{ "-" }}
                            @endif
                        </b>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <h4><i class="fa fa-chevron-right"></i>
            <b>Data Biaya Handling</b>
        </h4>
    </div>
    <div class="col-md-6 text-right">
        @if(isset($biaya) and count($biaya)>0)
        <button class="btn btn-sm btn-success" onclick="setBayar()">
            <i class="fa fa-money"></i> Set Bayar
        </button>
        @endif
    </div>

    @include('keuangan::handling.proyeksi')
</div>