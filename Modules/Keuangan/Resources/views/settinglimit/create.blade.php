<div class="row">

    <div class="col-md-4">
        <div class="form-group m-form__group">
            <label for="nominal">
                <b>Nominal Limit Piutang</b><span class="span-required"> *</span>
            </label>
            <input class="form-control" name="nominal" id="nominal" type="number" minlength="4" maxlength="100" value="" required/>
            
            @if ($errors->has('nominal'))
            <label style="color: red">
                {{ $errors->first('nominal') }}
            </label>
            @endif
        </div>
    </div>

    <div class="col-md-1" style="margin-top: 40px">
        <label><input type="checkbox" value="1" id="is_default" name="is_default"> Default</label>
    </div>
    
    <div class="col-md-4 " style="margin-top: 1.5%">
        @include('template.inc_action')
    </div>
</div>


@section('script')
<script type="text/javascript">

    @if(isset($data->is_default) and $data->is_default==1)
        $("#is_default").attr("checked", 1);
    @endif

    @if(isset($data->nominal))
        $("#nominal").val('{{ $data->nominal }}');
    @endif
</script>
@endsection