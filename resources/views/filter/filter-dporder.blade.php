<div class="col-md-3" style="padding-left:10px">
<div class="m-form__label">
        <label style="font-weight: bold;">
            ID STT
        </label>
    </div>
<div class="m-form__control">
    <select class="form-control" id="stt" name="stt">
        @if(Session('stt')!=null)
            <option value="{{ Session('stt') }}">{{ Session('stt') }}</option>
        @endif
    </select>
</div>
</div>
<script>
$('#stt').select2({
        placeholder: 'Masukkan STT yang dicari',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url('getStt') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#stt').empty();
                return {
                    results: $.map(data, function (item) {
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
</script>