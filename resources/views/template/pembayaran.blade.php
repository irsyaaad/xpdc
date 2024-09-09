<div class="m-input-icon m-input-icon--left">
    <select class="form-control m-input m-input--square" id="stt" name="stt">
        @if(Session('stt')!=null)
            <option value="{{ Session('stt') }}">{{ Session('stt') }}</option>
        @endif
    </select>
    <span class="m-input-icon__icon m-input-icon__icon--left">
        <span>
            <i class="la la-search"></i>
        </span>
    </span>
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
