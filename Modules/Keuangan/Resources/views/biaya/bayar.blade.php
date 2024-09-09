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
    <div class="col-md-12">
        <table class="table table-responsive">
            <thead>
                <tr>
                    <td width="10%">No. DM</td>
                    <td width="2%"><b>:</b></td>
                    <td width="10%">
                        <b>
                            @if(isset($dm->kode_dm)){{ strtoupper($dm->kode_dm) }}@endif
                        </b>
                    </td>
                    
                    <td width="6%">Total Pendapatan</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            {{ torupiah($dm->c_total) }}
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="10%">Perusahaan Asal</td>
                    <td width="2%"><b>:</b></td>
                    <td width="10%">
                        <b>
                            @if(isset($dm->perush_asal->nm_perush)){{ strtoupper($dm->perush_asal->nm_perush) }}@endif
                        </b>
                    </td>
                    
                    <td width="6%">Biaya HPP</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            {{ torupiah($dm->c_pro) }}
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="10%">Vendor Tujuan</td>
                    <td width="2%"><b>:</b></td>
                    <td width="10%">
                        <b>
                            @if(isset($dm->vendor->nm_ven)){{ strtoupper($dm->vendor->nm_ven) }}@endif
                        </b>
                    </td>
                    
                    <td width="6%">Terbayar</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            {{ torupiah($dm->n_bayar) }}
                        </b>
                    </td>
                    
                </tr>
                <tr>
                    <td width="10%">Layanan </td>
                    <td width="2%"><b>:</b></td>
                    <td width="10%">
                        <b>
                            @if(isset($dm->layanan->nm_layanan)){{ strtoupper($dm->layanan->nm_layanan) }}@endif
                        </b>
                    </td>
                    
                    <td width="10%">Sisa</td>
                    <td width="2%"><b>:</b></td>
                    <td width="10%">
                        <b>
                            {{ torupiah($dm->c_pro-$dm->n_bayar) }}
                        </b>
                    </td>
                </tr>    
                
                <tr>
                    <td width="7%">Status</td>
                    <td width="2%"><b>:</b></td>
                    <td width="8%">
                        <b>
                            @if(isset($dm->status->nm_status)){{ strtoupper($dm->status->nm_status) }}@endif
                        </b>
                    </td>
                </tr>
                
            </thead>
        </table>
    </div>
</div>

<div class="row">
    @include('keuangan::biaya.proyeksi')
</div>
<script>
    
</script>