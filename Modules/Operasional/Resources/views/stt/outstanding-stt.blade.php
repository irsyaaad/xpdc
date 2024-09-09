@extends('template.document2')

@section('data')
    <!-- css data table -->
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    {{-- filter modal rekap by status --}}
    <div class="row mb-3" style="display: flex">
        <div class="col-lg-12 col-md-12 col-sm-12 text-right">
            <button type="button" class="btn btn-primary text-white" data-toggle="modal" data-target="#entrimodal">
                <i class="fa fa-file"></i> Rekap Entri Status
            </button>
            <div class="modal fade" id="entrimodal" tabindex="-1" role="dialog" aria-labelledby="EntriModal"
                aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <form action="rekapentristatus" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Silahkan Masukkan Tanggal Rekap</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body row text-left">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <label for="entri_awal">Tanggal Awal</label>
                                    <input type="date" name="entri_awal" id="entri_awal" class="form-control"
                                        value="{{ date('Y-m-d', strtotime('-1 month')) }}">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <label for="entri_akhir">Tanggal Akhir</label>
                                    <input type="date" name="entri_akhir" id="entri_akhir" class="form-control"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Cari Rekap Entri</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- end filter --}}

    {{-- filter tanggal data update status STT --}}
    <form id="filter-form" class="m-form m-form--fit m-form--label-align-right" action="{{ url(Request::segment(1)) }}"
        method="GET">
        <div class="row">
            <div class="col-md-4 mt-3">
                <label>Kode STT</label>
                <select name="id_stt" id="id_stt" class="form-control">
                    <option value="">Pilih STT</option>
                    @foreach ($detail as $item)
                        <option value="{{ $item->id_stt }}"
                            {{ isset($filter['id_stt']) && $filter['id_stt'] == $item->id_stt ? 'selected' : '' }}>
                            {{ $item->kode_stt }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mt-3">
                <label>Tanggal Update (Awal)</label>
                <input type="date" name="dr_tgl" id="dr_tgl" class="form-control" value="{{ $filter['dr_tgl'] }}">
            </div>
            <div class="col-md-4 mt-3">
                <label>Tanggal Update (Akhir)</label>
                <input type="date" name="sp_tgl" id="sp_tgl" class="form-control" value="{{ $filter['sp_tgl'] }}">
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 text-right" style="margin-top:25px">
                <a href="{{ route('filteroutstanding') }}" class="btn btn-warning">
                    <i class="fa fa-refresh"></i> Refresh
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-search"></i> Cari Data
                </button>
            </div>
        </div>
    </form>
    {{-- end filter --}}
    <hr>
    {{-- form and update data status stt --}}
    <br style="gap: 20px;">
    <div class=" row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h5><b>Form Update status Data</b></h5>
            <hr>
        </div>
    </div>
    {{-- <input type="text" class="form-control" id="search" placeholder="Type to search"> --}}
    <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('saveupdatestatus') }}"
        enctype="multipart/form-data" id="form-plan-realization">
        @csrf
        <div class="row" id="form-update" style="display: none">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <label>
                    Pilih Status
                </label>
                <select name="id_status" id="id_status" class="form-control">
                    <option value="">-- Pilih Status --</option>
                    @foreach ($status as $key => $value)
                        <option value="{{ $value->kode_status }}">{{ strtoupper($value->nm_ord_stt_stat) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6">
                <label>
                    Pilih Wilayah
                </label>
                <select class="form-control" id="id_kota" name="id_kota" required></select>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 mt-3">
                <label>
                    Pilih Tanggal Update
                </label>
                <input type="date" name="tgl_update" id="tgl_update" class="form-control">
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 mt-3">
                <label>
                    Keterangan Update
                </label>
                <textarea class="form-control" name="keterangan" cols="30" rows="3"></textarea>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 text-right" style="margin-top:25px">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Update
                </button>
            </div>
        </div>
        <hr>
        <table class="table table-responsive table-bordered" id="tableasal">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th style="font-size: 11px;">No. </th>
                    <th style="font-size: 11px">Kode STT (AWB)</th>
                    <th style="font-size: 11px">Pengirim</th>
                    <th style="font-size: 11px">Penerima</th>
                    <th style="font-size: 11px" width="90px">Status Terakhir</th>
                    <th class="text-center" width="80px" style="font-size: 11px">Tgl. Status Terakhir</th>
                    <th class="text-center" width="80px" style="font-size: 11px">Tgl. Harus Update</th>
                    <th width="80px" style="font-size: 11px;">Selisih (Hari)</th>
                    <th class="text-center" style="font-size: 11px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($detail as $key => $value)
                    @php
                        if ($value->diff_date < 0) {
                            $color = 'red';
                        } elseif ($value->diff_date == 0) {
                            $color = 'green';
                        } else {
                            $color = 'black';
                        }
                    @endphp
                    <tr style="color : {{ $color }}">
                        <td style="font-size: 11px;">{{ $key + 1 }}. </td>
                        <td style="font-size: 11px;">
                            {{ strtoupper($value->kode_stt) }}
                            @if (strtoupper($value->no_awb) != null && strtoupper($value->no_awb) != '')
                                ({{ strtoupper($value->no_awb) }})
                            @endif
                            <br>
                            {{ dateindo($value->tgl_masuk) }}
                        </td>
                        <td style="font-size: 11px;">{{ strtoupper($value->pengirim_nm) }}
                            <br>
                            <span class="label label-inline label-light-primary">
                                {{ $value->pengirim_telp }}</span>
                            <br>
                            <span>{{ $value->asal }}</span>
                        </td>
                        <td style="font-size: 11px;">
                            @isset($value->penerima_nm)
                                {{ strtoupper($value->penerima_nm) }}
                            @endisset
                            <br>
                            <span class="label label-inline label-light-primary">
                                @isset($value->penerima_telp)
                                    {{ $value->penerima_telp }}
                                @endisset
                            </span>
                            <br>
                            <span>
                                @isset($value->tujuan)
                                    {{ $value->tujuan }}
                                @endisset
                            </span>
                        </td>
                        <td class="text-center" style="font-size: 10px">
                            {{ strtoupper($value->nm_status) }}
                        </td>
                        <td class="text-center" style="font-size: 11px;">
                            {{ dateindo($value->tgl_update) }}
                        </td>
                        <td class="text-center" style="font-size: 11px;">
                            {{ dateindo($value->tgl_harus_update) }}
                        </td>
                        <td class="text-center" style="font-size: 11px">
                            {{ $value->diff_date }}
                        </td>
                        <td class="text-center">
                            @if ($value->id_status == 6)
                                <a href="javascript:void(0)" class="btn" type="button"
                                    onclick="CheckSampai('{{ $value->id_stt }}')" data-toggle="tooltip"
                                    data-placement="bottom" title="Stt Sampai Tujuan">
                                    <span><i class="fa fa-check"></i></span> SSK
                                </a>
                            @else
                                <input type="checkbox" class="form-control check" name="id_stt[]" id="id_stt"
                                    value="{{ $value->id_stt }}">
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>
    <div class="modal fade" id="modal-end" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" style="margin-left: 7%; font-weight: bold;"> <i class="fa fa-truck"></i>
                        Apakah Anda Barang Sudah Sampai ?
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" style="margin-top: -2%">
                    <form method="POST" action="{{ url('dmtiba/sampai') }}" enctype="multipart/form-data"
                        id="form-end">
                        @csrf

                        <label style="font-weight : bold ">
                            Kota Posisi Barang <span class="text-danger"> *</span>
                        </label>
                        <select class="form-control" id="id_kota_handling" name="id_kota_handling"></select>
                        <br>
                        <br>
                        <h6>Tgl Update<span class="text-danger"> *</span> </h6>
                        <input type="date" class="form-control" name="tgl_update" value="{{ date('Y-m-d') }}"
                            id="tgl_update" required>
                        <br>

                        <h6>Foto Dokumentasi 1</h6>
                        <input class="form-control" name="dok1" id="dok1" type="file" />
                        <img id="img1" name="img1" src="">
                        @if ($errors->has('dok1'))
                            <label style="color: red">
                                {{ $errors->first('dok1') }}
                            </label>
                        @endif

                        <br>

                        <h6>Foto Dokumentasi 2</h6>
                        <input class="form-control" name="dok2" id="dok2" type="file" />
                        <img id="img2" name="img2" src="">
                        @if ($errors->has('dok2'))
                            <label style="color: red">
                                {{ $errors->first('dok2') }}
                            </label>
                        @endif

                        <input class="form-control" name="id_stt" id="id_stt_modal" required type="hidden" />
                        @if ($errors->has('id_stt'))
                            <label style="color: red">
                                {{ $errors->first('id_stt') }}
                            </label>
                        @endif

                        <h6>Keterangan<span class="span-required"> * </span></h6>
                        <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Masukan Keterangan ..." required></textarea>
                        @if ($errors->has('keterangan'))
                            <label style="color: red">
                                {{ $errors->first('keterangan') }}
                            </label>
                        @endif

                        <br>
                        <h6>Nama Penerima<span class="span-required"> * </span></h6>
                        <input type="text" class="form-control" name="nm_penerima" id="nm_penerima" maxlength="100"
                            placeholder="Masukan Nama Penerima ..." required />
                        @if ($errors->has('nm_penerima'))
                            <label style="color: red">
                                {{ $errors->first('nm_penerima') }}
                            </label>
                        @endif
                        <br>
                        <div class="text-right">
                            <button type="submit" class="btn btn-sm btn-success" id="modal-btn-si">Sampai</button>
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"
                                aria-label="Close"><span aria-hidden="true">Batal</span></button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $('#tableasal').DataTable({
            lengthMenu: [
                [100, 300, 1000, -1],
                [100, 300, 1000, 'All']
            ],
            order: [
                [0, 'asc']
            ],
            paging: false
        });
        $("#tgl_update").val('{{ date('Y-m-d') }}');
        $("#id_stt").select2();
        $('#id_kota').select2({
            minimumInputLength: 0,
            placeholder: 'Cari Kota ....',
            allowClear: true,
            ajax: {
                url: '{{ url('getKota') }}',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    $('#id_kota').empty();
                    return {
                        results: $.map(data, function(item) {
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

        $('.check').click(function() {
            var tots = 0;
            $(".check:checked").each(function() {
                tots += 1;
            });
            if (tots > 0) {
                $("#form-update").show();
            } else {
                $("#form-update").hide();
            }
            console.log(tots);
        });

        // var $rows = $('#tableasal tr');
        // $('#search').keyup(function() {
        //     var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

        //     $rows.show().filter(function() {
        //         var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        //         return !~text.indexOf(val);
        //     }).hide();
        // });

        // const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

        // const comparer = (idx, asc) => (a, b) => ((v1, v2) =>
        //     v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
        // )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

        // // do the work...
        // document.querySelectorAll('th').forEach(th => th.addEventListener('click', (() => {
        //     const table = th.closest('table');
        //     Array.from(table.querySelectorAll('tr:nth-child(n+2)'))
        //         .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
        //         .forEach(tr => table.appendChild(tr));
        // })));

        $('#id_kota_handling').select2({
            minimumInputLength: 0,
            placeholder: 'Cari Kota ....',
            allowClear: true,
            ajax: {
                url: '{{ url('getKota') }}',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    $('#id_kota').empty();
                    return {
                        results: $.map(data, function(item) {
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

        function CheckSampai(id = "") {
            $("#id_stt_modal").val(id);
            $("#id_kota_handling").attr('required', true);
            // $("#dok1").attr('required', true);
            // $("#dok2").attr('required', true);
            $("#nm_penerima").attr('required', true);
            $("#modal-end").modal('show');
        }
    </script>
@endsection
