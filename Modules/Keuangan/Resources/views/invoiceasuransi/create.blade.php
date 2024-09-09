@extends('template.document')

@section('data')


<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url(Request::segment(1)) }}@else{{ url(Request::segment(1), $data->id_invoice) }}@endif" enctype="multipart/form-data">
  @if(Request::segment(3)=="edit") 
  {{ method_field("PUT") }} 
  @endif
  @csrf

  <div class="row">
    <div class="col-md-3">
      <label for="cabang" >No. Invoice</label> 
      <input type="text" class="form-control" id="id_invoice" name="id_invoice" value="@if(isset($data->id_invoice)){{strtoupper($data->id_invoice)}}@endif" disabled>
      
      @if ($errors->has('id_invoice'))
      <label style="color: red">
        {{ $errors->first('id_invoice') }}
      </label>
      @endif  
    </div>

    <div class="col-md-3">
      <label for="no_rek" >Tanggal <span class="span-required"> *</span></label> 
      <input type="date" class="form-control" id="tgl" name="tgl" required="required">
      
      @if ($errors->has('no_rek'))
      <label style="color: red">
        {{ $errors->first('no_rek') }}
      </label>
      @endif  
    </div>
    
    <div class="col-md-3">
      <label for="no_rek" >Jatuh Tempo <span class="span-required"> *</span></label> 
      <input type="date" class="form-control" id="inv_j_tempo" name="inv_j_tempo" value="@if(isset($data->inv_j_tempo)){{$data->inv_j_tempo}}@endif" required="required">
      @if ($errors->has('no_rek'))
      <label style="color: red">
        {{ $errors->first('no_rek') }}
      </label>
      @endif  
    </div>

    <div class="col-md-3">
      <label for="id_plgn" >Pelanggan<span class="span-required"> *</span></label> 
      <select class="form-control m-input m-input--square" id="id_pelanggan" name="id_pelanggan" required>
        <option value="">-- pilih pelanggan --</option>
        @foreach ($pelanggan as $key => $value)
        <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
        @endforeach
      </select>
      
      @if ($errors->has('pelanggan'))
      <label style="color: red">
        {{ $errors->first('pelanggan') }}
      </label>
      @endif  
    </div>   
  </div>
  
  <div class="col-md-12 text-right">
    @include('template.inc_action')
  </div>
  
</form>
@endsection

@section('script')

<script type="text/javascript">
  var today = (new Date()).toISOString().split('T')[0];
  
  @if(old("tgl")!=null)
  $("#tgl").val('{{ old("tgl") }}');
  @elseif(isset($data->tgl))
  $("#tgl").val('{{$data->tgl}}');
  @else
  $("#tgl").val(today);
  @endif
  
  $("#id_pelanggan").select2();
  
  @if(isset($data->id_bank))
  $("#id_bank").val({{ $data->id_bank }});
  @endif
  
  @if(isset($data->is_aktif) and $data->is_aktif==1)
  $("#is_aktif").prop("checked", true);
  @endif
  
  @if(old('kontak')!=null)
  $("#kontak").val('{{ old("kontak") }}');
  @elseif(isset($data->kontak))
  $("#kontak").val('{{$data->kontak}}');
  @endif
  
  @if(old('id_pelanggan')!=null)
  $("#id_pelanggan").val('{{ old("id_pelanggan") }}').trigger("change");
  @elseif(isset($data->id_plgn))
  $("#id_pelanggan").val('{{ $data->id_plgn }}').trigger("change");
  @endif
  
</script>

@endsection