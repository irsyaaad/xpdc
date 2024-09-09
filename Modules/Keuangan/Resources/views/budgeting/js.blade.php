<script>
    $(function() {
        var url = `{{ route('budgeting-data')}}?bulan={{ $filter['bulan'] }}&tahun={{ $filter['tahun'] }}`;
        $('#html_table').DataTable({
            processing: true,
            serverSide: true,
            lengthMenu: [[50, 100], [50, 100]],
            ajax: `{{ route('budgeting-data')}}?bulan={{ $filter['bulan'] }}&tahun={{ $filter['tahun'] }}`,
            "order": [[ 2, 'desc' ]],
            columns: [
            { data: 'ac4', name: 'ac4' },
            { data: 'nama', name: 'nama' },
            { data: 'bulan', name: 'bulan' },
            { data: 'tahun', name: 'tahun' },
            { data: 'nominal', name: 'nominal' },
            {"render": function ( data, type, row )
            {
                var html = '<div class="dropdown"><button style="background-color: #00c5dc; color:white; font-weight: bold; text-align: center; border-color:#00c5dc; border-radius:5px" class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    html += '<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">';
                        // html += '<a class="dropdown-item" href="{{ url(Request::segment(1)) }}/'+row.id+'/show"><i class="fa fa-eye"></i> Lihat</a>';
                        html += `<a class="dropdown-item" href="javascript:void(0)" onclick="edit('${row.id}', '${row.ac4}', '${row.tgl}', '${row.nominal}', '${row.keterangan}')"><i class="fa fa-pencil"></i> Edit</a>`;
                        html += `<a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(${row.id})"><i class="fa fa-trash"></i> Delete</a>`;
                        html += '</div></div>'
                        return html;
                    }
                },
                ]
            });
        });
    
        $('#modal-btn-simpan').on('click', function (e) {
            let form = $('#form-add-budgeting')[0];
            var formData = new FormData(form);
    
            $.ajax({
                url: "{{ url('budgeting') }}",
                type: 'POST',
                data: formData,
                success: function (response) {
                    if (response.status) {
                        alert('Sukses Menyimpan Data');
                        $('#html_table').DataTable().ajax.reload();
                        // $("#form-add-budgeting").trigger('reset');
                    } else {
                        alert(response.message);
                    }
                },
                error: function (response) {
                    console.log(response);
                },
                contentType: false,
                processData: false
            });
        })
    
        $("#filter-bulan").select2();
        @if(isset($filter["bulan"]))
        $("#filter-bulan").val("{{ $filter["bulan"] }}").trigger('change');
        @endif
        
        $("#filter-tahun").select2();
        @if(isset($filter["tahun"]))
        $("#filter-tahun").val("{{ $filter["tahun"] }}").trigger('change');
        @endif
    
        $('.add-budgeting').click(function(){
            $("#modal-budgeting").modal('show');
            $("#id_ac").select2();
            $("#bulan").select2();
        });
        
        function setBayar(data) {
            var today = new Date().toISOString().split('T')[0];
            $("#modal-dm").modal('show');
            $("#id_ac").val(data["id_ac"]);
            $("#nm_ac").val(data["nama"]);
            
            function goSubmitUpdate() {
                $("#form-bayar").submit();
            }
        }
        
        function edit(id, ac4, tgl, nominal, keterangan) {
            var regex = /[.,\s]/g;
            var newNominal = nominal.replace(regex, '');
    
            var dt = new Date(tgl);
            var bulan = dt.getMonth() + 1;
            if (bulan < 10) {
                bulan = '0' + bulan;
            }
            var tahun = dt.getFullYear();
    
            $("#id_ac_edit").select2();
            $("#bulan_edit").select2();
            $('#id_ac_edit').val(ac4).trigger('change'); 
            $('#bulan_edit').val(bulan).trigger('change'); 
            $('#tahun_edit').val(tahun); 
            $('#nominal_edit').val(newNominal); 
            $('#id_budgeting').val(id); 
            $('#keterangan_edit').text(keterangan); 
    
            $("#modal-edit-budgeting").modal('show');
        }
    
        $('#modal-btn-update').on('click', function (e) {
            let form = $('#form-edit-budgeting')[0];
            var formData = new FormData(form);
            $.ajax({
                url: "{{ url('budgeting/update-budgeting') }}",
                type: 'POST',
                data: formData,
                success: function (response) {
                    if (response) {
                        alert('Sukses Menyimpan Data');
                        $('#html_table').DataTable().ajax.reload();
                        // $("#form-edit-budgeting").trigger('reset');
                    }
                },
                error: function (response) {
                    console.log(response);
                },
                contentType: false,
                processData: false
            });
        })
    
        function deleteItem(id) {
            $('#id_delete_budgeting').val(id); 
            $("#modal-delete-setting").modal('show');
        }

        $('.copy-budgeting').click(function(){
            $("#modal-copy-setting").modal('show');
        });
</script>