
<div class="col-md-3">
    <label style="font-weight: bold;">
        Pilih Tahun
    </label>
    <select name="tahun" class="form-control" id="tahun" name="tahun">
        <option selected="selected" value="0">-- Pilih Tahun --</option>
        <?php
        for($i=date('Y'); $i>=date('Y')-10; $i-=1){
            echo"<option value='$i'> $i </option>";
        }
        ?>
    </select>
</div>
<div class="col-md-9">
    <label style="font-weight: bold;">
        Action
    </label><br>
    <button class="btn btn-md btn-primary" data-toggle="tooltip" data-placement="bottom" title="Cari Data"><span><i class="fa fa-search"></i></span></button>
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip" data-placement="bottom" title="Refresh Data"><span><i class="fa fa-refresh"></i></span></a>
    <a href="{{ route('cetakrugilabapertahun', [
    'tahun' => $filter['tahun'],
    ]) }}" style="color:white;" class="btn btn-md btn-accent" data-toggle="tooltip" data-placement="top" title="Cetak pdf" target="_blank" rel="nofollow"><i class="fa fa-print"></i></a>
    <a href="{{ route('excelrugilabapertahun', [
    'tahun' => $filter['tahun'],
    ]) }}" style="color:white;" class="btn btn-md btn-success" data-toggle="tooltip" data-placement="top" title="Cetak Excel" target="_blank" rel="nofollow"><i class="fa fa-file"></i></a>
</div>

@section('script')
<script>
    var d = new Date();
    var date = d.getDate();
    var month = d.getMonth() + 1;
    var bln  ="0"+month;
    var tahun = d.getFullYear();
    console.log(bln);
    console.log(tahun);
    @if(Session('bulan') != null)
    $("#bulan").val('{{ Session('bulan') }}');
    @else
    $("#bulan").val(bln);
    @endif
    @if(isset($filter['tahun']))
    $("#tahun").val('{{ $filter['tahun'] }}');
    @else
    $("#tahun").val(tahun);
    @endif
    function html(){
        window.location = "{{ url(Request::segment(1)."/cetak") }}";
    }
    function excel(){
        window.location = "{{ url(Request::segment(1)."/excel") }}";
    }
</script>
@endsection
