<div class="row">
    <div class="col-md-6">
        <h4><i class="fa fa-thumb-tack"></i>
            <b>DATA DM</b>
        </h4>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-warning">
            <i class="fa fa-reply"></i>	Kembali
        </a>
    </div>  
    
    <div class="col-md-12" style="margin-top: 1%">
        <table class="table table-responsive table-striped" id="html_table" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th>No. DM</th>
                    <th>Layanan</th>
                    <th>Asal</th>
                    <th>Tujuan</th>
                    <th>Dari</th>
                    <th>Ke</th>
                    <th>PJ Asal</th>
                    <th>PJ Tujuan</th>
                    <th>Status</th>
                    {{-- <th>Keterangan</th> --}}
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dm as $key => $value)
                <tr>
                    <td>
                        <a href="{{ url(Request::segment(1)).'/'.$value->id_dm.'/show' }}" class="class-edit">{{ strtoupper($value->id_dm) }}</a>
                    </td>
                    <td>@if(isset($value->layanan->nm_layanan)){{ strtoupper($value->layanan->nm_layanan) }}@endif</td>
                    <td>@if(isset($value->perush_asal->nm_perush)){{ strtoupper($value->perush_asal->nm_perush) }}@endif</td>
                    <td>@if(isset($value->perush_tujuan->nm_perush)){{ strtoupper($value->perush_tujuan->nm_perush) }}@endif</td>
                    <td>{{ strtoupper($value->nm_dari) }}</td>
                    <td>{{ strtoupper($value->nm_tuju) }}</td>
                    <td>{{ strtoupper($value->nm_pj_dr) }}</td>
                    <td>{{ strtoupper($value->nm_pj_tuju) }}</td>
                    <td>
                        @if(isset($value->status->nm_status))
                        {{ strtoupper($value->status->nm_status) }}
                        @endif
                    </td>
                    {{-- <td>{{ $value->info }}</td> --}}
                    <td>
                        <a href="{{ url(Request::segment(1)).'/'.$value->id_dm.'/bayar' }}" class="btn btn-sm btn-success">
                            <i class="fa fa-money"></i> Bayar
                        </a>
                    </td>	
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row" style="margin-top: 4%; font-weight: bold;">
    <div class="col-md-2">
        Halaman : <b>{{ $dm->currentPage() }}</b>
    </div>
    <div class="col-md-2">
        Jumlah Data : <b>{{ $dm->total() }}</b>
    </div>
    <div class="col-md-8" style="width: 100%">
        {{ $dm->links() }}
    </div>
</div>

<script>
    $("#generalSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#html_table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
</script>