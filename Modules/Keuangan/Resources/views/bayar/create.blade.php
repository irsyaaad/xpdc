<div class="row">
    <div class="col-md-6">
        <h4><i class="fa fa-thumb-tack"></i>
            <b>DATA STT</b>
        </h4>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-warning">
            <i class="fa fa-reply"></i>	Kembali
        </a>
    </div>  
    
    <div class="col-md-12">
        <form action="{{ url(Request::segment(1)."/create") }}" class="col-xl-12" name="form-filter" id="form-filter" method="post">
            @csrf
            <div class="form-group row">
                @include('keuangan::bayar.filter-stt')
                <div class="col-md-2" style="padding-top:4px">
                    <br>
                    <button class="btn btn-md btn-primary"><span><i class="fa fa-search"></i></span></button>
                </div>
            </div>
        </form>
        <br><br>
        <table class="table table-sm table-striped" style="margin-top:-3%" id="html_table">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th>No </th>
                    <th>No. STT</th>
                    <th>Pengirim</th>
                    <th>Asal</th>
                    <th>Tujuan</th>
                    <th>Berat</th>
                    <th>Volume</th>
                    <th>Koli</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stt as $key => $value)
                <tr>
                    <td>
                        {{ $key+1 }}
                    </td>
                    <td>
                        {{ strtoupper($value->kode_stt) }}
                    </td>
                    <td>
                        {{ $value->pengirim_nm }}
                    </td>
                    <td>
                        @if(isset($value->asal)){{ $value->asal->nama_wil }}@endif
                    </td>
                    <td>
                        @if(isset($value->tujuan)){{ $value->tujuan->nama_wil }}@endif
                    </td>
                    <td>
                        @if(isset($value->n_berat)){{ $value->n_berat }}@endif
                    </td>
                    <td>
                        @if(isset($value->n_volume)){{ $value->n_volume }}@endif
                    </td>
                    <td>
                        @if(isset($value->n_koli)){{ $value->n_koli }}@endif
                    </td>
                    <td>
                        @if(isset($value->c_total)){{ number_format($value->c_total, 0, ',', '.') }}@endif
                    </td>
                    <td>
                        <a href="{{ url(Request::segment(1)).'/'.$value->id_stt.'/bayar' }}" class="btn btn-sm btn-success">
                            <i class="fa fa-money"></i> Bayar
                        </a>
                    </td>	
                </tr>
                @endforeach
                @if(count($stt)< 1)
                <tr> 
                    <td colspan="7" class="text-center">STT Bayar Tujuan Kosong</td>
                </tr>
                @endif

            </tbody>
        </table>
    </div>
</div>

<div class="row" style="margin-top: 4%; font-weight: bold;">
    <div class="col-md-2">
        Halaman : <b>{{ $stt->currentPage() }}</b>
    </div>
    <div class="col-md-2">
        Jumlah Data : <b>{{ $stt->total() }}</b>
    </div>
    <div class="col-md-8" style="width: 100%">
        {{ $stt->links() }}
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