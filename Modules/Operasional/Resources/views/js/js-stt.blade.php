<script type="text/javascript">
    var asal = "";
    var tujuan = "";
    var layanan = "";
    var status_member = false;

    $("#id_tipe_kirim").select2();
    
    $('#id_pelanggan').select2({
        placeholder: 'Cari Pelanggan ....',
        // minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url('getPelanggan') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#id_pelanggan').empty();
                return {
                    results:  $.map(data, function (item) {
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
    
    $('#id_pelanggan').on("change", function(e) {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ url('getDetailPelanggan') }}/"+$("#id_pelanggan").val(),
            success: function(data) {
                cekLimit($("#id_pelanggan").val());
                $("#pengirim_nm").val(data.nm_pelanggan);
                $("#pengirim_alm").val(data.alamat);
                $("#pengirim_telp").val(data.telp);
                $("#text-asal").text($('#pengirim_id_region').text());
                $("#text-tujuan").text($('#penerima_id_region').text());
                status_member = data.is_member;
                console.log(status_member);
            },
        });
    });
    
    function cekLimit(id) {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ url('ceklimit') }}/"+id,
            success: function(data) {
                if(data.piutang>data.limit){
                    $("#div-limit").show();
                    $("#label-piutang").html("Jumlah Piutang : "+data.piutang);
                    $("#label-plgn").html("Nama Pelanggan : "+$("#id_pelanggan").text());
                }else{
                    $("#div-limit").hide();
                    $("#label-piutang").html("Jumlah Piutang : ");
                    $("#label-plgn").html("Nama Pelanggan : ");
                }
            },
        });
    }

    $('#id_perush_tujuan').on("change", function(e) {
        $("#nm_perush_tujuan").val($("#id_perush_tujuan").text());
    });

    $('#is_ppn').change(function()
    {
        if($(this).is(':checked')) {
            @if(isset($tarif_ppn) and $tarif_ppn > 0)
            var n_ppn = parseFloat($("#n_hrg_bruto").val());
            var temp = $("#n_hrg_bruto").val();
            var disc = $("#n_diskon").val();
            var x 	 = temp.replace(/[.]/g,"");
            var ppn = (parseFloat(x))-(parseFloat(disc));
            var tarif_ppn = "{{$tarif_ppn}}";
            var nilai = parseFloat(ppn*tarif_ppn/100);
            var hasil = nilai.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            $("#n_ppn").val(hasil);
            setNetto();
            @else
            $("#is_ppn").prop("checked", false);
            $("#modal-ppn").modal('show');
            @endif
        }else{
            $("#n_ppn").val("0");
        }
    });

    $('#is_asuransi').change(function()
    {
        if($(this).is(':checked')) {
            @if(isset($tarif_asuransi))
            $("#modal-asuransi").modal('show');
            @else
            $("#is_asuransi").prop("checked", false);
            $("#asuransi-alert").modal('show');
            @endif
        }else{
            $("#n_asuransi").val("0");
        }
        setNetto();
    });

    $('#is_packing').change(function()
    {
        if($(this).is(':checked')) {
            $("#n_packing").removeAttr("readonly");
        }else{
            $("#n_packing").attr("readonly", true);
            $("#n_packing").val("0");
        }
        setNetto();
    });

    $('#id_tarif').change(function() {
        $("#nm_tarif").val($('#id_tarif option:selected').text());
    });

    @if(!is_null(old('id_tarif')))
    $('#id_tarif').append('<option value="{{ old("id_tarif") }}">{{ strtoupper(old("nm_tarif")) }}</option>');
    $('#id_tarif').val({{ old("id_tarif") }});
    @endif

    @if(!is_null(old('cm_brt')))
    $("#cm_brt").val('{{ old('cm_brt') }}');
    @endif

    @if(!is_null(old('id_marketing')))
    $("#id_marketing").val('{{ old("id_marketing") }}');
    @endif

    @if(!is_null(old('id_tipe_kirim')))
    $("#id_tipe_kirim").val('{{ old('id_tipe_kirim') }}').trigger("change");
    @endif

    @if(!is_null(old('cm_vol')))
    $("#cm_vol").val('{{ old('cm_vol') }}');
    @endif

    @if(!is_null(old('n_packing')))
    $("#n_packing").val('{{ old('n_packing') }}');
    @endif

    @if(!is_null(old('is_packing')))
    $("#is_packing").attr("checked", true);
    $("#n_packing").removeAttr("readonly");
    @endif

    @if(!is_null(old('c_tarif')))
    $("#c_tarif").val({{ old('c_tarif') }});
    @endif

    var today = new Date().toISOString().split('T')[0];

    @if(isset($data->tgl_masuk)){{ $data->tgl_masuk }}@else $("#tgl_masuk").val(today); @endif

    $("#id_layanan").prop('disabled', true);

    $("#n_ppn").attr("readonly", true);
    // $("#n_asuransi").attr("readonly", true);
    
    $("#n_tarif_brt").attr("readonly", true);

    $("#n_tarif_vol").attr("readonly", true);

    $("#n_tarif_kubik").attr("readonly", true);
    $("#n_tarif_borongan").attr("readonly", false);

    activeLayanan();
    
    $("#text-asal").text($('#pengirim_id_region').text());
    $("#text-tujuan").text($('#penerima_id_region').text());

    function goSubmit() {
        var cek = $("input[name='c_hitung']:checked").val();

        @if(Request::segment(2)=="create" or Request::segment(3)=="edit")
        if(cek == "3"){
            $("#auth-modal").modal("show");
        }else{
            $("#form-data").submit();
        }
        @else
        $("#form-data").submit();
        @endif
    }

    $('#form-data').submit(function(){
        $(this).find('#btn-submit').prop('disabled', true);
    });

    @if(Request::segment(1)=="stt")
    $('#pengirim_id_region').select2({
        placeholder: 'Cari Wilayah ....',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url('getwilayah') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#pengirim_id_region').empty();
                return {
                    results:  $.map(data, function (item) {
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

    // for region penerima
    $('#penerima_id_region').select2({
        placeholder: 'Cari Wilayah ....',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url('getwilayah') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#penerima_id_region').empty();
                return {
                    results:  $.map(data, function (item) {
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
    @endif

    $('#id_tarif').on("change", function(e) {
        $("#div-tarif").hide();
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ url('getTarifDetail') }}/"+$("#id_tarif").val(),
            success: function(data) {
                $("#n_tarif_brt").val(data.hrg_brt);
                $("#n_tarif_vol").val(data.hrg_vol);
                $("#cm_brt").val(data.min_brt);
                $("#cm_vol").val(data.min_vol);
                $("#cm_kubik").val(data.min_kubik);
                $("#n_tarif_kubik").val(data.hrg_kubik);
                if(data.min_kubik == null){
                    $("#cm_kubik").val('0');
                }
                if(data.hrg_kubik == null){
                    $("#n_tarif_kubik").val("0");
                }

                // if(data.hrg_vol=="0" || data.hrg_brt=="0"){
                    // 	$("#div-tarif").show();
                    // }
                    //setNull();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    setNull();
                }
            });
        });

        $('#pengirim_id_region').on("change", function(e) {
            activeLayanan();
            setNull();
            $("#id_layanan").val(0);
            $("#text-asal").text($('#pengirim_id_region').text());
            $("#nm_pengirim_region").val($('#pengirim_id_region').text());
        });

        $('#penerima_id_region').on("change", function(e) {
            activeLayanan();
            setNull();
            $("#id_layanan").val(0);
            $("#text-tujuan").text($('#penerima_id_region').text());
            $("#nm_penerima_region").val($('#penerima_id_region').text());
        });

        @if(!is_null(old('id_layanan')))
        $("#id_layanan").val({{ old('id_layanan') }});
        @endif

        @if(!is_null(old('id_asuransi')))
        $("#id_asuransi").val({{ old('id_asuransi') }});
        @endif

        @if(!is_null(old('id_cr_byr_o')))
        $("#id_cr_byr_o").val("{{ old('id_cr_byr_o') }}").trigger("change");
        @endif

        @if(!is_null(old('id_packing')))
        $("#id_packing").val("{{ old('id_packing') }}");
        @endif

        @if(!is_null(old('is_ppn')))
        $("#is_ppn").prop("checked", true);
        @endif

        @if(!is_null(old('c_hitung')))
        $("input[name=c_hitung][value=" + {{ old('c_hitung') }} + "]").prop('checked', true);
        @endif
        
        // js for custome view
        $(".col-md-12 > label").css("font-weight", "bold");
        $(".col-md-6 > label").css("font-weight", "bold");
        $(".col-md-4 > label").css("font-weight", "bold");
        $(".col-md-3 > label").css("font-weight", "bold");
        $(".col-md-11 > label").css("font-weight", "bold");
        $(".col-md-5 > label").css("font-weight", "bold");
        $(".col-md-6 > label").css("margin-top", "5px");
        $(".col-md-12 > label").css("margin-top", "5px");

        // for change tarif
        $('#id_layanan').on("change", function(e) {
            getTarif();
        });

        function getTarif() {
            var id_asal = $("#pengirim_id_region").val();
            var id_tujuan = $("#penerima_id_region").val();
            var id_layanan = $("#id_layanan").val();
            var id_pelanggan = $("#id_pelanggan").val();
            var token = "{{ csrf_token() }}";

            $.ajax({
                type: "POST",
                url: "{{ url('getTarifPost') }}",
                dataType: "json",
                data: {_token: token, id_asal: id_asal, id_tujuan: id_tujuan, id_layanan:id_layanan, id_pelanggan:id_pelanggan},
                beforeSend: function(e) {
                    if(e && e.overrideMimeType) {
                        e.overrideMimeType("application/json;charset=UTF-8");
                    }
                },
                success: function(response){
                    // console.log(response);
                    $('#id_tarif').empty();
                    $('#id_tarif').append('<option value="">-- Pilih Tarif --</option>');

                    $.each(response, function(index, value) {
                        $('#id_tarif').append('<option value="'+value.id_tarif+'">'+value.nm_ven+' '+value.ket+ '( ' + value.trbrt + ' - '+ value.trvol +' - '+ value.trkbk +' )' +'</option>');
                    });
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError);
                    setTarif();
                }
            });
        }

        function activeLayanan() {
            var id_asal = $("#pengirim_id_region").val();
            var id_tujuan = $("#penerima_id_region").val();

            if(id_asal!=null && id_tujuan!=null){
                $("#id_layanan").prop('disabled', false);
            }
        }

        $("#auth-btn").click(function() {
            $('#auth-modal').modal();
        });

        $('input[type=radio][name=c_hitung]').change(function() {
            setTarif();
            tes();
        });

        $("#n_asuransi").on("change", function(e) {
            var brt = parseFloat($("input[name='n_asuransi']" ).val());

            $("#n_asuransi").val(hasil);
            if(brt<0){
                $("#n_asuransi").val(0);
            }
        });

        $("#n_asuransi").keyup(function() {
            var brt = parseFloat($("input[name='n_asuransi']" ).val());

            if(brt<0){
                $("#n_asuransi").val(0);
            }

        });

        $("#n_materai").on("change", function(e) {
            var brt = parseFloat($("input[name='n_materai']" ).val());
            if(brt<0){
                $("#n_materai").val(0);
            }
        });

        $("#n_materai").keyup(function() {
            var brt = parseFloat($("input[name='n_materai']" ).val());
            if(brt<0){
                $("#n_materai").val(0);
            }

        });

        $("#n_ppn").on("change", function(e) {
            var brt = parseFloat($("input[name='n_ppn']" ).val());

            if(brt<0){
                $("#n_ppn").val(0);
            }
        });

        $("#n_ppn").keyup(function() {
            var brt = parseFloat($("input[name='n_ppn']" ).val());

            if(brt<0){
                $("#n_ppn").val(0);
            }

        });

        $("#n_packing").on("change", function(e) {
            setNetto();
        });

        $("#n_packing").keyup(function() {
            setNetto();
        });

        $("#n_diskon").on("change", function(e) {
            var brt = parseFloat($("input[name='n_diskon']" ).val());
            if(brt<0){
                $("#n_diskon").val(0);
            }
        });

        $("#n_diskon").keyup(function() {
            var brt = parseFloat($("input[name='n_diskon']" ).val());

            if(brt<0){
                $("#n_diskon").val(0);
            }

        });

        $("#n_koli").on("change", function(e) {
            var brt = parseFloat($("input[name='n_koli']" ).val());

            if(brt<0){
                $("#n_koli").val(0);
            }
        });

        $("#n_koli").keyup(function() {
            var brt = parseFloat($("input[name='n_koli']" ).val());

            if(brt<0){
                $("#n_koli").val(0);
            }

        });

        $("#n_berat").on("change", function(e) {
            var brt = parseFloat($("input[name='n_berat']" ).val());

            if(brt<0){
                $("#n_berat").val(0);
            }

            setTarif();
            tes();
        });

        
        $("#n_berat").keyup(function() {
            var brt = parseFloat($("input[name='n_berat']" ).val());

            if(brt<0){
                $("#n_berat").val(0);
            }

            setTarif();
            tes();
        });

        $("#n_volume").on("change", function(e) {
            var brt = parseFloat($("input[name='n_volume']" ).val());
            //console.log(brt);
            if(brt<0){
                $("#n_volume").val(0);
            }

            setTarif();
            tes();
        });

        $("#n_volume").keyup(function() {
            var vol = parseFloat($("input[name='n_volume']" ).val());
            //console.log(brt);
            if(vol<0){
                $("#n_volume").val(0);
            }

            setTarif();
            tes();
        });


        $("#n_kubik").keyup(function() {
            var kbk = parseFloat($("input[name='n_kubik']" ).val());
            if(kbk<0){
                $("#n_kubik").val(0);
            }

            setTarif();
            tes();
        });

        $("#n_tarif_borongan").keyup(function() {
            var vol = parseFloat($("input[name='n_tarif_borongan']" ).val());
            if(vol<0){
                $("#n_tarif_borongan").val(0);
            }

            setTarif();
            tes();
        });

        function setTarif() {
            var jml = 0;
            var tarif = 0;

            var c_hitung = $("input[name='c_hitung']:checked").val();
            // console.log("-> ",c_hitung);

            if(c_hitung==1){
                jml = parseFloat($("#n_berat" ).val());
                if($("#min_brt" ).val()<jml){
                    jml = parseFloat($("#min_brt" ).val());
                }

                tarif = parseFloat($("#n_tarif_brt" ).val());
            }else if(c_hitung==2){
                jml = parseFloat($("#n_volume" ).val());
                if($("#min_vol" ).val()<jml){
                    jml = parseFloat($("#min_vol" ).val());
                }

                tarif = parseFloat($("#n_tarif_vol" ).val());

            }else if(c_hitung==4){
                jml = parseFloat($("#n_kubik" ).val());
                //console.log("kubik")
                if($("#n_kubik" ).val()<jml){
                    jml = parseFloat($("#min_kubik" ).val());
                }

                tarif = parseFloat($("#n_tarif_kubik" ).val());
                //console.log(tarif);

            }else if(c_hitung==3){
                jml = 1;
                tarif = parseFloat($("#n_tarif_borongan" ).val());

            }else{

                // jml = parseFloat($("input[name='n_borongan']" ).val());
                // tarif = parseFloat($("input[name='n_tarif_bor']" ).val());
            }

            if(isNaN(jml)){
                jml = 1;
            }

            if(isNaN(tarif)){
                tarif = 0;
            }
            var total = parseFloat(jml*tarif);
            var nilai = Rupiah(total);
            $("#n_hrg_bruto").val(nilai);
            return total;
            setPpn();
        }

        function tes() {
            if ($("#id_cr_byr_o").val() > 0) {
                $("#id_cr_byr_o").empty();
            }
            var total = setNetto();
            @if(Request::segment(1)=="stt")
            if (status_member) {
                $.ajax({
                    type: "GET",
                    url: "{{ url("getCaraBayar") }}",
                    dataType: "json",
                    beforeSend: function(e) {
                        if(e && e.overrideMimeType) {
                            e.overrideMimeType("application/json;charset=UTF-8");
                        }
                    },
                    success: function(response){
                        $("#id_cr_byr_o").empty();
                        $("#id_cr_byr_o").append('<option value="">-- Pilih Cara Bayar --</option>');
                        $.each(response,function(key, value)
                        {
                            $("#id_cr_byr_o").append('<option value="' + value.kode + '">' + value.value + '</option>');
                        });
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(thrownError);
                    }
                });
            }
            else if (total >= 500000) {
                $.ajax({
                    type: "GET",
                    url: "{{ url("getCaraBayar") }}",
                    dataType: "json",
                    beforeSend: function(e) {
                        if(e && e.overrideMimeType) {
                            e.overrideMimeType("application/json;charset=UTF-8");
                        }
                    },
                    success: function(response){
                        $("#id_cr_byr_o").empty();
                        $("#id_cr_byr_o").append('<option value="">-- Pilih Cara Bayar --</option>');
                        $.each(response,function(key, value)
                        {
                            $("#id_cr_byr_o").append('<option value=' + value.kode + '>' + value.value + '</option>');
                        });
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(thrownError);
                    }
                });
            } else {
                $("#id_cr_byr_o").empty();
                $("#id_cr_byr_o").append('<option value="">-- Pilih Cara Bayar --</option>');
                $("#id_cr_byr_o").append('<option value="1">' + 'TUNAI CASH' + '</option>');
                $("#id_cr_byr_o").append('<option value="2">' + 'TUNAI TRANSFER' + '</option>');
            }
            @endif

            @if(Request::segment(1)=="dmtiba")
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{ url('getDetailPelanggan') }}/"+$("#id_pelanggan").val(),
                success: function(data) {
                    status_member = data.is_member;
                },
            });

            if (status_member) {
                $.ajax({
                    type: "GET",
                    url: "{{ url("getCaraBayar") }}",
                    dataType: "json",
                    beforeSend: function(e) {
                        if(e && e.overrideMimeType) {
                            e.overrideMimeType("application/json;charset=UTF-8");
                        }
                    },
                    success: function(response){
                        $("#id_cr_byr_o").empty();
                        $("#id_cr_byr_o").append('<option value="">-- Pilih Cara Bayar --</option>');
                        $.each(response,function(key, value)
                        {
                            $("#id_cr_byr_o").append('<option value=' + value.kode + '>' + value.value + '</option>');
                        });
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(thrownError);
                    }
                });
            }
            else if (total >= 500000) {
                $.ajax({
                    type: "GET",
                    url: "{{ url("getCaraBayar") }}",
                    dataType: "json",
                    beforeSend: function(e) {
                        if(e && e.overrideMimeType) {
                            e.overrideMimeType("application/json;charset=UTF-8");
                        }
                    },
                    success: function(response){
                        $("#id_cr_byr_o").empty();
                        $("#id_cr_byr_o").append('<option value="">-- Pilih Cara Bayar --</option>');
                        $.each(response,function(key, value)
                        {
                            $("#id_cr_byr_o").append('<option value=' + value.kode + '>' + value.value + '</option>');
                        });
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(thrownError);
                    }
                });
            } else {
                $("#id_cr_byr_o").empty();
                $("#id_cr_byr_o").append('<option value="">-- Pilih Cara Bayar --</option>');
                $("#id_cr_byr_o").append('<option value="1">' + 'TUNAI CASH' + '</option>');
                $("#id_cr_byr_o").append('<option value="2">' + 'TUNAI TRANSFER' + '</option>');
            }
            @endif
        }

        $("#n_hrg_terusan").keyup(function() {
            setNetto();
            tes();
        });

        $("#n_diskon").keyup(function() {
            setNetto();
            tes();
        });

        $("#n_ppn").keyup(function() {
            setNetto();
            tes();
        });

        $("#n_materai").keyup(function() {
            setNetto();
            tes();
        });

        $("#n_asuransi").keyup(function() {
            setNetto();
            tes();
        });

        function setPpn() {
            if($('#is_ppn').is(':checked')){
                @if(isset($tarif_ppn) and $tarif_ppn > 0)
                var n_ppn = parseFloat($("#n_hrg_bruto").val());
                var ppn = n_ppn*1000;
                var nilai = parseFloat(ppn*1/100);
                var hasil = Rupiah(nilai);


                $("#n_ppn").val(hasil);

                setNetto();
                @else
                alert("Tarif PPN Belum Dibuat");
                @endif
            }else{
                $("#n_ppn").val("0");
            }

        }

    function setNetto() {
        var n_bruto = parseFloat($("#n_hrg_bruto").val());
        var bruto = setTarif();
        var terusan = parseFloat($("#n_hrg_terusan").val());
        var diskon = parseFloat($("#n_diskon").val());
        var n_ppn = parseFloat($("#n_ppn").val());
        var n_packing = parseFloat($("#n_packing").val());

        <?php if(Request::segment(3) == "edit") {?>
            var ppn = n_ppn*1;
            <?php }else{ ?>
                var ppn = n_ppn*1000;
                <?php } ?>

                var materai = parseFloat($("#n_materai").val());
                var asuransi = parseFloat($("#n_asuransi").val());

                if(isNaN(bruto) || bruto<0){
                    bruto = 0;
                    $("#n_hrg_bruto").val(0);
                }

                if (isNaN(terusan) || terusan<0) {
                    terusan = 0;
                    $("#n_hrg_terusan").val(0);
                }

                if(isNaN(diskon) || diskon<0){
                    diskon = 0;
                    $("#n_diskon").val(0);
                }

                if(isNaN(ppn) || ppn<0){
                    ppn =0;
                    $("#n_ppn").val(0);
                }

                if(isNaN(materai) || materai<0){
                    materai = 0;
                    $("#n_materai").val(0);
                }

                if(isNaN(asuransi) || asuransi<0){
                    asuransi = 0;
                    $("#n_asuransi").val(0);
                }

                if(isNaN(n_packing) || n_packing<0){
                    n_packing = 0;
                    $("#n_packing").val("0");
                }

                var netto = parseFloat(bruto+terusan+ppn+materai+asuransi-diskon+n_packing);
                var hasil = Rupiah(netto);
                $("#c_total").val(hasil);
                return netto;
            }

            function Rupiah(nilai) {
                var bilangan = Math.ceil(nilai);
                var	number_string = bilangan.toString(),
                sisa 	= number_string.length % 3,
                rupiah 	= number_string.substr(0, sisa),
                ribuan 	= number_string.substr(sisa).match(/\d{3}/g);


                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                return rupiah;
            }

            function setNull() {
                $("#n_tarif_brt").val(0);
                $("#n_tarif_vol").val(0);
                $("#n_tarif_borongan").val(0);
                
                setPpn();
                setTarif();
                setNetto();
                tes();
            }

            $("#tgl_masuk").on("change", function(e) {
                chekDate();
            });

            $("#tgl_keluar").on("change", function(e) {
                chekDate();
            });

            function chekDate() {
                var masuk = Date.parse($("#tgl_masuk").val());
                var keluar = Date.parse($("#tgl_keluar").val());

                if(keluar < masuk){
                    $("#text-modal").text("Tanggal keluar lebih kecil dari tanggal masuk");
                    $("#tgl_keluar").val("");
                    $('#tarif-modal').modal();
                }
            }
</script>
