<div class="col-md-2">
	<div class="m-form__control">
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
</div>
<script>
var d = new Date();
var date = d.getDate();
var month = d.getMonth() + 1;
var bln  ="0"+month;
var tahun = d.getFullYear();
console.log(bln);
console.log(tahun);
@if(Session('tahun') != null)
	$("#tahun").val('{{ Session('tahun') }}');
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