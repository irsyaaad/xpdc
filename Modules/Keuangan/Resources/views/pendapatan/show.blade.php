<div class="row">
    <div class="col-md-12 table-responsive">
        <table class="table table-hover">
            <tbody>

                <tr>
                    <td>No. Transaksi</td>
                    <td> : </td>
                    <td><b>{{ $data->kode_pendapatan }}</b></td>

                    <td>Perusahaan</td>
                    <td> : </td>
                    <td><b>{{ strtoupper($data->perusahaan->nm_perush) }}</b></td>

                    <td>Tgl. Masuk</td>
                    <td> : </td>
                    <td><b>@if(isset($data->tgl_masuk)){{ daydate($data->tgl_masuk).", ".dateindo($data->tgl_masuk) }}@endif</b></td>
                </tr>

                <tr>
                    <td>Akun Debet</td>
                    <td> : </td>
                    <td><b>
                        @if(isset($data->debet->nama))
                        {{ strtoupper("( ".$data->debet->id_ac." )   ".$data->debet->nama) }}
                        @endif
                    </b></td>

                    <td>Total </td>
                    <td> : </td>
                    <td><b>@if(isset($data->c_total)){{ number_format($data->c_total, 2, ',', '.') }} @else {{ number_format("0", 2, ',', '.') }}@endif</b></td>

                    <td>Admin Penerima</td>
                    <td> : </td>
                    <td><b>@if(isset($data->user->nm_user)){{ $data->user->nm_user }}@endif</b></td>
                </tr>

                <tr>
                    <td>Terima Dari</td>
                    <td> : </td>
                    <td><b>{{ strtoupper($data->terima_dr) }}</b></td>

                    <td>Keterangan </td>
                    <td> : </td>
                    <td><b>{{ strtoupper($data->info) }}</b></td>
                </tr>

            </tbody>
        </table>
    </div>

    <div class="col-md-12 text-right">
        <button type="button" data-toggle="modal" data-target="#modal-pendapatan" onclick="goPlus()" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah</button>
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
    </div>

    <div class="col-md-12 mt-2">
        <table class="table table-responsive table-striped">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th>No</th>
                    <th>Akun Kredit</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                    <th>Keterangan</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <form method="GET" action="#" enctype="multipart/form-data" id="form-select">
                    @csrf
                    <input type="hidden" name="_method" value="GET">
                    @foreach($detail as $key => $value)
                    <tr>
                        <td>
                            {{ ($key+1) }}
                        </td>
                        <td>
                            @if(isset($value->akun->id_ac)) {{ "(".$value->akun->id_ac.") ".strtoupper($value->akun->nama) }}@endif
                            <br>
                            {{ $value->tgl_posting }}
                        </td>
                        <td>
                            {{ $value->jumlah }}
                        </td>
                        <td>
                            {{ toRupiah($value->harga) }}
                        </td>
                        <td>
                            {{ toRupiah($value->total) }}
                        </td>
                        <td>
                            {{ $value->info }}
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modal-pendapatan" onclick="getEdit('{{ $value->id_detail }}', '{{ $value->akun->id_ac }}','{{ $value->jumlah }}', '{{ $value->harga }}', '{{ $value->info }}', '{{ $value->tgl_posting }}')">
                                <span> <i class="fa fa-edit"></i></span>
                            </button>
                            @php
                            $url = url(Request::segment(1)."/".$value->id_detail."/deletedetail");
                            @endphp
                            <button type="button" class="btn btn-sm btn-danger" onclick="CheckDelete('{{ $url }}')">
                                <span> <i class="fa fa-times"></i></span>
                            </button>
                        </td>
                    </tr>

                    @endforeach
                </form>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modal-pendapatan" tabindex="-1" role="dialog" aria-labelledby="modal-pendapata" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="margin-left: 10%; font-weight: bold;"><span><i class="fa fa-money"></i></span> Form Tambah Detail Pemasukan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @php
                $ldate = date('Y-m-d H:i:s')
                @endphp
                <div class="modal-body">
                    <form method="POST" action="{{ url(Request::segment(1)."/savedetail") }}" id="form-detail" name="form-detail">
                        @csrf
                        <div class="form-group">
                            <label for="terima_dr">
                                <b>Akun </b><span class="span-required"> *</span>
                            </label>

                            <input type="hidden" name="id_pendapatan" id="id_pendapatan" value="{{ Request::segment(2) }}" required />

                            <select class="form-control" name="id_ac" id="id_ac" required>
                                <option value="">-- Pilih Akun --</option>
                                @foreach($akun as $key => $value)
                                <option value="{{ $value->id_ac }}">{{ $value->nama }} ( {{ $value->parent_3 }} )</option>
                                @endforeach
                            </select>

                            @if ($errors->has('id_ac'))
                            <label class="text-danger">
                                {{ $errors->first('id_ac') }}
                            </label>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="jumlah">
                                <b>Jumlah </b><span class="span-required"> *</span>
                            </label>

                            <input class="form-control" id="jumlah" name="jumlah" type="number" maxlength="24" placeholder="Masukan Jumlah" required />

                            @if ($errors->has('jumlah'))
                            <label class="text-danger">
                                {{ $errors->first('jumlah') }}
                            </label>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="harga">
                                <b>Harga </b><span class="span-required"> *</span>
                            </label>

                            <input class="form-control" id="harga" name="harga" type="number" placeholder="Masukan Harga" maxlength="24" required />

                            @if ($errors->has('harga'))
                            <label class="text-danger">
                                {{ $errors->first('harga') }}
                            </label>
                            @endif
                        </div>

                        {{-- <div class="form-group">
                            <label for="nominal">
                                <b>Tanggal Posting</b> <span class="span-required"> *</span>
                            </label>
                            
                            <input class="form-control m-input m-input--square" id="tgl_posting" name="tgl_posting" type="date" required/>
                            
                            @if ($errors->has('tgl_posting'))
                            <label style="color: red">
                                {{ $errors->first('tgl_posting') }}
                            </label>
                            @endif
                        </div> --}}

                        <div class="form-group">
                            <label for="info">
                                <b>Info Bayar </b><span class="span-required"> *</span>
                            </label>
                            <textarea class="form-control" style="min-height: 100px"  id="info" name="info" placeholder="Masukan Info Bayar (Huruf, Angka, dan Spasi)" maxlength="256" required></textarea>
                            @if ($errors->has('info'))
                            <label class="text-danger">
                                {{ $errors->first('info') }}
                            </label>
                            @endif
                        </div>

                        <div class="form-group text-right">
                            <button class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Simpan"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="goPlus()" data-toggle="tooltip" data-placement="bottom" title="Batal" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i> Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
<script>

    $( document ).ready(function() {
        $('#id_ac').select2({
            dropdownParent: $('#modal-pendapatan')
        });
    });
    function goPlus(){
        $("#form-detail").attr("action", '{{ url(Request::segment(1)."/savedetail") }}');
        $("#id_ac").val("");
        $("#jumlah").val("");
        $("#harga").val("");
        $("#info").text("");
       // $("#tgl_posting").val('{{ date("Y-m-d") }}');
    }
    
    function getEdit(id,akun, jumlah, harga, info, tgl_posting) {
        $("#form-detail").attr("action", '{{ url(Request::segment(1)."/updatedetail") }}/'+id);
        $("#id_ac").val(akun).trigger("change");
        $("#jumlah").val(jumlah);
        $("#harga").val(harga);
        $("#info").html(info);
        //$("#tgl_posting").val(tgl_posting);
    }

</script>
@endsection
