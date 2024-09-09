<div class="col-md-3">
    <label style="font-weight: bold;">
        Dari Tanggal
    </label>
    <input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="@if(isset($filter['dr_tgl'])){{$filter['dr_tgl']}}@endif">
</div>

<div class="col-md-3">
    <label style="font-weight: bold;">
        Sampai Tanggal
    </label>
    <input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="@if(isset($filter['sp_tgl'])){{$filter['sp_tgl']}}@endif">
</div>
<div class="col-md-6">
    <label style="font-weight: bold;">
        Search :
    </label>
    <input type="text" class="form-control" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name">
</div>

@section('script')
<script>
    // var d = new Date();
    // var date = "01";
    // var month = d.getMonth() + 1;
    // var bln  ="0"+month;
    // var tahun = d.getFullYear();

    // var tanggal = date+'/'+(month)+'/'+(tahun);
    // var cuks	= tahun+'-'+month+'-'+date;
    // console.log(tanggal);

    // var dr_tgl = new Date("01/01/2022").getFullYear()+'-'+new Date("01/01/2022").getMonth()+1+'-'+new Date("01/01/2022").getDate();
    // console.log(dr_tgl);

    // $("#dr_tgl").val(dr_tgl);

    function html(){
        window.location = "{{ url(Request::segment(1)."/cetak") }}";
    }
    function excel(){
        window.location = "{{ url(Request::segment(1)."/excel") }}";
    }

    function myFunction() {
        var input, filter, table, tr, td, i ;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("jurnal-table");
        tr = table.getElementsByTagName("tr"),
        th = table.getElementsByTagName("th");

        // Loop through all table rows, and hide those who don't match the        search query
        for (i = 1; i < tr.length; i++) {
            tr[i].style.display = "none";
            for(var j=0; j<th.length; j++){
                td = tr[i].getElementsByTagName("td")[j];
                if (td) {
                    if (td.innerHTML.toUpperCase().indexOf(filter.toUpperCase()) > -1)                               {
                        tr[i].style.display = "";
                        break;
                    }
                }
            }
        }
    }
</script>
@endsection
