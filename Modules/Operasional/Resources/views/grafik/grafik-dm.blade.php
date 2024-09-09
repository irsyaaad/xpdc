<form method="POST" action="{{ url('operasional/filterdm') }}" id="form-share" name="form-share">
    @csrf
<div class="row">
<div class="col-md-3">
	<div class="m-form__group m-form__group--inline">
		<div class="m-form__control">
		<label style="font-weight: bold;">
				Bulan
        </label>
			<select class="form-control" id="filterdm" name="filterdm">
				<option value="0">-- Pilih Bulan --</option>
				<option value="1">  Januari  </option>
				<option value="2">  Februari  </option>
				<option value="3">  Maret  </option>
				<option value="4">  April  </option>
				<option value="5">  Mei  </option>
				<option value="6">  Juni  </option>
				<option value="7">  Juli  </option>
				<option value="8">  Agustus  </option>
				<option value="9">  September  </option>
				<option value="10">  Oktober  </option>
				<option value="11">  November  </option>
				<option value="12">  Desember  </option>
            </select>
		</div>
	</div>
	<div class="d-md-none m--margin-bottom-10"></div>
</div>
<div class="col-md-3">
	<div class="m-form__group m-form__group--inline">
		<div class="m-form__control">
		<label style="font-weight: bold;">
				Tahun
        </label>
        <select name="tahun" class="form-control" id="tahundm" name="tahundm">
        <option selected="selected" value="0">-- Pilih Tahun --</option>
        <?php
        for($i=date('Y'); $i>=date('Y')-10; $i-=1){
        echo"<option value='$i'> $i </option>";
        }
        ?>
        </select>
		</div>
	</div>
	<div class="d-md-none m--margin-bottom-10"></div>
</div>
<div class="col-md-3">
	<div class="m-form__group m-form__group--inline">
		<div class="m-form__control">
		<label style="font-weight: bold;">
				Layanan
        </label>
			<select class="form-control" id="layanandm" name="layanandm">
				<option value="0">-- Pilih Layanan --</option>
				<option value="1">  Trucking  </option>
				<option value="2">  Container  </option>
				<option value="3">  Kapal  </option>
            </select>
		</div>
	</div>
	<div class="d-md-none m--margin-bottom-10"></div>
</div>
<div class="col-md-2" style="padding-top:4px">
	<br>
	<button type="submit" class="btn btn-md btn-primary"><span><i class="fa fa-search"></i></span></button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning"><span><i class="fa fa-refresh"></i></span></a>
    </div>
</div>
    </form>
<br>
<canvas id="dm" class="col-md-11" style="padding-left:10%"></canvas>
<script>
    
    @if(Session('bulandm') != null)
		$("#filterdm").val('{{ Session('bulandm') }}');
	@endif
    @if(Session('tahundm') != null)
		$("#tahundm").val('{{ Session('tahundm') }}');
	@endif
    @if(Session('layanandm') != null)
		$("#layanandm").val('{{ Session('layanandm') }}');
	@endif
    //bar
    var data = <?php echo json_encode( $dm ) ?>;
    var temp = [];
    for (let index = 1; index <= 31; index++) {
        temp[index-1] = data[index];
        
    }
    var tgl = [];
    for (let index = 1; index <= 31; index++) {
        tgl[index-1] = index;        
    }
    console.log(temp);
    var ctxB = document.getElementById("dm").getContext('2d');
    var myBarChart = new Chart(ctxB, {
        type: 'bar',
        data: {
            labels: tgl,
            datasets: [{
                label: 'Jumlah DM / Tanggal Keluar',
                data: temp,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',

                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',

                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',

                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',

                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',

                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',

                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',

                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        min: 1,
                        max: 500,
                        stepSize: 100,
                        reverse: false,
                    }
                }]
            }
        }
    });

</script>