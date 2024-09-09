@extends('template.document2')

@section('data')
<form method="GET" action="{{ url()->current() }}">
    @csrf
    <div class="row">
        <div class="col-md-3">
            <label style="font-weight: bold;">
                Pilih Bulan
            </label>
            <select class="form-control" id="filter-bulan" name="bulan">
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
        </div>
        
        <div class="col-md-3">
            <label style="font-weight: bold;">
                Pilih Tahun
            </label>
            <select name="tahun" class="form-control" id="filter-tahun" name="tahun">
                <option selected="selected" value="">-- Pilih Tahun --</option>
                <?php for($i=date('Y'); $i>=date('Y')-10; $i-=1){ ?>
                <option value="{{ $i }}">{{ $i }}</option>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-3" style="margin-top: 30px"> 
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="fa fa-filter"></i> Filter
            </button>
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning">
                <i class="fa fa-retweet"></i> Reset
            </a>
        </div>
        <div class="col-md-3 text-right" style="margin-top: 25px"> 
            <a href="javascript:void(0)" class="btn btn-md btn-info copy-budgeting" data-toggle="tooltip" data-placement="bottom" title="Copy Setting From Last Month">
                <i class="fa fa-copy"></i>
            </a>
            <a href="javascript:void(0)" class="btn btn-md btn-success add-budgeting">
                <i class="fa fa-plus"></i> Tambah Budgeting
            </a>
        </div>
    </div>
    <br>
    <br><br>
    <table class="table table-sm table-striped table-bordered" id="html_table" width="100%">
        <thead style="background-color: grey; color : #ffff">
            <th>AC4</th>
            <th>Nama AC</th>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Budgeting (Rp.)</th>
            <th>Action</th>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</form>
@include('keuangan::budgeting.modal')
@endsection

@section('script')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
@include('keuangan::budgeting.js')
@endsection
