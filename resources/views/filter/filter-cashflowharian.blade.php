<div class="col-md-2">
	<div class="m-form__control">
		<label style="font-weight: bold;">
			Dari Tanggal
		</label>
		<input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="@if(Session('dr_tgl')!= null){{Session('dr_tgl')}}@endif">
	</div>
</div>

<div class="col-md-2">
	<div class="m-form__control">
		<label style="font-weight: bold;">
			Sampai Tanggal
		</label>
		<input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="@if(Session('sp_tgl')!= null){{Session('sp_tgl')}}@endif">
	</div>
</div>
<div class="col-md-2" style="margin-right:20px">
<div class="m-form__label">
        <label style="font-weight: bold;">
            User
        </label>
</div>
<div class="m-form__control">
    <select class="form-control" id="nm_user" name="nm_user">
        @if(Session('nm_user')!=null)
            <option value="{{ Session('nm_user') }}">{{ Session('nm_user') }}</option>
        @endif
    </select>
</div>
</div>
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
@if(Session('tahun') != null)
	$("#tahun").val('{{ Session('tahun') }}');
@else
	$("#tahun").val(tahun);
@endif
$('#nm_user').select2({
        placeholder: 'Masukkan Nama yang dicari',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url('getUser') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#nm_user').empty();
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.value,
                            id: item.kode
                        }
                    })
                };
            },
            cache: true
        }
    });
function html(){
	window.location = "{{ url(Request::segment(1)."/cetak") }}";
}
function excel(){
	window.location = "{{ url(Request::segment(1)."/excel") }}";
}
</script>