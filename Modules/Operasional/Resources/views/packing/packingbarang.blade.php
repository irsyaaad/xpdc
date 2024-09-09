@extends('template.document2')

@section('data')

@if((Request::segment(1)=="packingbarang" && Request::segment(2)==null) or (Request::segment(2)=="filter" or Request::segment(2)=="page"))
<div class="row">
    <div class="col-md-12">
        @include('operasional::filter.filter-packingbarang')
    </div>
    
    <div class="col-md-12" style="margin-top: 25px">
        <table class="table table-responsive table-striped" id="html_table" width="100%" >
            <thead  style="background-color: grey; color : #ffff;">>
                <tr>
                    <th>No</th>
                    <th>Kode STT</th>
                    <th>No AWB</th>
                    <th>Perusahaan Asal</th>
                    <th>Pelanggan</th>
                    <th>Pengirim</th>
                    <th>Nominal</th>
                    <th>Dibayar</th>
                    <th>Status Bayar</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $value)
                <tr> 
                    <td>{{ ($key+1) }}</td>
                    <td>{{ $value->kode_stt }}</td>
                    <td>{{ $value->no_awb }}</td>
                    <td>{{ $value->nm_perush }}</td>
                    <td>{{ $value->nm_pelanggan }}</td>
                    <td>{{ $value->nm_pengirim }}</td>
                    <td>{{ $value->n_total }}</td>
                    <td>{{ $value->n_bayar }}</td>
                    <td>
                        @if($value->is_lunas == true)
                        <i class="fa fa-check text-success"></i>
                        @else
                        <i class="fa fa-times text-danger"></i>
                        @endif
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                            </button>
                            <div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <form method="POST" action="{{ url(Request::segment(1).'/'.$value->id_packing) }}" id="form-delete{{ $value->id_packing }}" name="form-delete{{ $value->id_packing }}">
                                    @if($value->n_bayar == "0")
                                    <a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_packing.'/edit') }}"><i class="fa fa-edit"></i> Edit</a>
                                    <a class="dropdown-item" href="#"  onclick="CheckDelete('{{ $value->id_packing }}')"><i class="fa fa-times"></i> Hapus</a>
                                    {{ method_field("DELETE") }}
                                    @csrf
                                    @endif
                                    
                                    {{-- <a class="dropdown-item" href="{{ url(Request::segment(1).'/bayar/'.$value->id_stt) }}"><i class="fa fa-money"></i> Bayar</a> --}}
                                    {{-- <a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_packing."/cetak_pdf") }}"><i class="fa fa-print"></i> Cetak</a> --}}
                                    <a class="dropdown-item" href="{{ url(Request::segment(1).'/import/'.$value->id_stt) }}"><i class="fa fa-eye"></i> Detail</a>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row" style="margin-top: 4%; font-weight: bold;">
    <div class="col-md-2">
        Halaman : <b>{{ $data->currentPage() }}</b>
    </div>
    {{-- <div class="col-md-2">
        Jumlah Data : <b>{{ $data->total() }}</b>
    </div> --}}
    <div class="col-md-2">
        @if(Request::segment(2)=="filter")
        <form method="POST" action="{{ url('packingbarang/filter') }}" id="form-share" name="form-share">
            @else
            <form method="POST" action="{{ url('packingbarang/page') }}" id="form-share" name="form-share">
                @endif
                @csrf
                <select class="form-control" style="font-weight: bold;" id="shareselect" name="shareselect">
                    <option value="50">50 Data</option>
                    <option value="100">100 Data</option>
                    <option value="500">500 Data</option>
                    <option value="1000">1000 Data</option>
                </select>
            </form>
        </div>
        <div class="col-md-8" style="width: 100%">
            {{ $data->links() }}
        </div>
    </div>
    
    @elseif(Request::segment(2)=="create" or Request::segment(3)=="edit" )
    <style type="text/css">
        .select2-selection {
            height: 43px !important;
            padding: 1px;
        }
        input{
            background-color: #fff;
            color: black;
        }
    </style>
    @endif
    
    @endsection
    @section('script')
    <script>
        
    </script>
    @endsection