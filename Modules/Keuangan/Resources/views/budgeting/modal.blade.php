<div class="modal fade" id="modal-budgeting" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 10%; font-weight: bold;"><span><i class="fa fa-money"></i></span> INPUT BUDGETING</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php
            $ldate = date('Y-m-d H:i:s')
            @endphp
            <div class="modal-body">
                <form method="POST" action="#" id="form-add-budgeting">
                    @csrf
                    <table width="100%">
                        <thead>
                            <tr>
                                <th width="180px">Akun</th>
                                <th width="10px"> : </th>
                                <th>
                                    <select class="form-control" id="id_ac" name="id_ac">
                                        <option value="">-- Pilih Akun --</option>
                                        @foreach ($akun as $item)
                                            <option value="{{ $item->id_ac }}">{{ $item->nama }} ({{ $item->parent_3 }})</option>
                                        @endforeach
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <th width="180px"> Bulan </th>
                                <th width="10px"> : </th>
                                <th>
                                    <select class="form-control" id="bulan" name="bulan">
                                        <option value="">-- Pilih Bulan --</option>
                                        <option value="01">  Januari  </option>
                                        <option value="02">  Februari  </option>
                                        <option value="03">  Maret  </option>
                                        <option value="04">  April  </option>
                                        <option value="05">  Mei  </option>
                                        <option value="06">  Juni  </option>
                                        <option value="07">  Juli  </option>
                                        <option value="08">  Agustus  </option>
                                        <option value="09">  September  </option>
                                        <option value="10">  Oktober  </option>
                                        <option value="11">  November  </option>
                                        <option value="12">  Desember  </option>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <th width="180px"> Tahun </th>
                                <th width="10px"> : </th>
                                <th> <input type="text" class="form-control no-border" id="tahun" name="tahun" value=""> </th>
                            </tr>
                            
                        </thead>
                    </table>
                    <br>
                    <div class="form-group">
                        <label for="n_bayar" >Nominal<span class="span-required"> *</span></label>
                        <input class="form-control" id="nominal" name="nominal" type="number" placeholder="Masukkan Nominal Proyeksi ..." />
                        @if ($errors->has('nominal'))
                        <label style="color: red">
                            {{ $errors->first('nominal') }}
                        </label>
                        @endif
                    </div>      
                    <div class="form-group">
                        <label for="n_bayar" >Keterangan<span class="span-required"> *</span></label>
                        <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="5"></textarea>
                        @if ($errors->has('nominal'))
                        <label style="color: red">
                            {{ $errors->first('nominal') }}
                        </label>
                        @endif
                    </div>
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-sm btn-success" id="modal-btn-simpan" >Simpan</button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit-budgeting" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 10%; font-weight: bold;"><span><i class="fa fa-money"></i></span> INPUT BUDGETING</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php
            $ldate = date('Y-m-d H:i:s')
            @endphp
            <div class="modal-body">
                <form method="PUT" action="#" id="form-edit-budgeting">
                    @csrf
                    <table width="100%">
                        <thead>
                            <tr>
                                <th width="180px">Akun</th>
                                <th width="10px"> : </th>
                                <th>
                                    <select class="form-control" id="id_ac_edit" name="id_ac">
                                        <option value="">-- Pilih Akun --</option>
                                        @foreach ($akun as $item)
                                            <option value="{{ $item->id_ac }}">{{ $item->nama }} ({{ $item->parent_3 }})</option>
                                        @endforeach
                                    </select>
                                </th>
                            </tr>
                            <input type="hidden" id="id_budgeting" name="id">
                            <tr>
                                <th width="180px"> Bulan </th>
                                <th width="10px"> : </th>
                                <th>
                                    <select class="form-control" id="bulan_edit" name="bulan">
                                        <option value="">-- Pilih Bulan --</option>
                                        <option value="01">  Januari  </option>
                                        <option value="02">  Februari  </option>
                                        <option value="03">  Maret  </option>
                                        <option value="04">  April  </option>
                                        <option value="05">  Mei  </option>
                                        <option value="06">  Juni  </option>
                                        <option value="07">  Juli  </option>
                                        <option value="08">  Agustus  </option>
                                        <option value="09">  September  </option>
                                        <option value="10">  Oktober  </option>
                                        <option value="11">  November  </option>
                                        <option value="12">  Desember  </option>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <th width="180px"> Tahun </th>
                                <th width="10px"> : </th>
                                <th> <input type="text" class="form-control no-border" id="tahun_edit" name="tahun" value=""> </th>
                            </tr>
                            
                        </thead>
                    </table>
                    <br>
                    <div class="form-group">
                        <label for="n_bayar" >Nominal<span class="span-required"> *</span></label>
                        <input class="form-control" id="nominal_edit" name="nominal" type="number" placeholder="Masukkan Nominal Proyeksi ..." />
                        @if ($errors->has('nominal'))
                        <label style="color: red">
                            {{ $errors->first('nominal') }}
                        </label>
                        @endif
                    </div>      
                    <div class="form-group">
                        <label for="n_bayar" >Keterangan<span class="span-required"> *</span></label>
                        <textarea class="form-control" name="keterangan" id="keterangan_edit" cols="30" rows="5"></textarea>
                        @if ($errors->has('nominal'))
                        <label style="color: red">
                            {{ $errors->first('nominal') }}
                        </label>
                        @endif
                    </div>
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-sm btn-success" id="modal-btn-update" >Update</button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-copy-setting" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 10%; font-weight: bold;"><span><i class="fa fa-money"></i></span> Copy Budgeting To ?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php
            $ldate = date('Y-m-d H:i:s')
            @endphp
            <div class="modal-body">
                <form method="POST" action="{{ url('budgeting/copy-budgeting') }}" id="form-copy-setting">
                    @csrf
                    <table width="100%">
                        <thead>
                            <tr>
                                <th width="180px"> Dari Bulan </th>
                                <th width="10px"> : </th>
                                <th>
                                    <select class="form-control" id="dari_bulan_copy" name="dari_bulan">
                                        <option value="">-- Pilih Bulan --</option>
                                        <option value="01">  Januari  </option>
                                        <option value="02">  Februari  </option>
                                        <option value="03">  Maret  </option>
                                        <option value="04">  April  </option>
                                        <option value="05">  Mei  </option>
                                        <option value="06">  Juni  </option>
                                        <option value="07">  Juli  </option>
                                        <option value="08">  Agustus  </option>
                                        <option value="09">  September  </option>
                                        <option value="10">  Oktober  </option>
                                        <option value="11">  November  </option>
                                        <option value="12">  Desember  </option>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <th width="180px"> Dari Tahun </th>
                                <th width="10px"> : </th>
                                <th> <input type="text" class="form-control no-border" id="dari_tahun_copy" name="dari_tahun" value=""> </th>
                            </tr>                            
                        </thead>
                        <thead>
                            <tr>
                                <th width="180px">Ke Bulan </th>
                                <th width="10px"> : </th>
                                <th>
                                    <select class="form-control" id="bulan_copy" name="bulan">
                                        <option value="">-- Pilih Bulan --</option>
                                        <option value="01">  Januari  </option>
                                        <option value="02">  Februari  </option>
                                        <option value="03">  Maret  </option>
                                        <option value="04">  April  </option>
                                        <option value="05">  Mei  </option>
                                        <option value="06">  Juni  </option>
                                        <option value="07">  Juli  </option>
                                        <option value="08">  Agustus  </option>
                                        <option value="09">  September  </option>
                                        <option value="10">  Oktober  </option>
                                        <option value="11">  November  </option>
                                        <option value="12">  Desember  </option>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <th width="180px">Ke Tahun </th>
                                <th width="10px"> : </th>
                                <th> <input type="text" class="form-control no-border" id="tahun_copy" name="tahun" value=""> </th>
                            </tr>                            
                        </thead>
                    </table>
                    <br><br>
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-sm btn-success" id="modal-btn-update" >Copy</button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-delete-setting" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 10%; font-weight: bold;"><span><i class="fa fa-money"></i></span> Anda Yakin Menghapus Setting Ini?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php
            $ldate = date('Y-m-d H:i:s')
            @endphp
            <div class="modal-body">
                <form method="POST" action="{{ url('budgeting/delete-budgeting') }}" id="form-delete-setting">
                    @csrf
                    <input type="hidden" name="id" id="id_delete_budgeting">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-sm btn-success" id="modal-btn-update" >Delete</button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>