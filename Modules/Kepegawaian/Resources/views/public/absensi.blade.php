<table class="table table-responsive table-hover">
    <thead style="background-color: grey; color : #ffff">
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Karyawan</th>
            <th rowspan="2">Tgl Absen</th>
            <th rowspan="2"> Jam Masuk</th>
            <th rowspan="2"> Jam Istirahat > Jam Istirahat Masuk</th>
            <th rowspan="2"> Jam Pulang</th>
            <th rowspan="2">Status Kehadiran</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key => $value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>
                @if(isset($value->nm_karyawan))
                {{ strtoupper($value->nm_karyawan) }}
                @endif
            </td>
            <td>
                {{ daydate($value->tgl_absen).", ".dateindo($value->tgl_absen) }}
            </td>
            <td>
                {{ $value->jam_datang }} 
            </td>
            <td>
                {{ $value->jam_istirahat }} > {{ $value->jam_istirahat_masuk }}
            </td>
            <td>
                {{ $value->jam_pulang }}
            </td>
            <td>
                <ul>
                    @if($value->jam_masuk == "00:00:00")
                    <li class="text-danger">Tidak Absen Masuk</li>
                    @endif
                    @if($value->jam_istirahat == "00:00:00")
                    <li class="text-danger">
                        Tidak Absen Istirahat
                    </li>
                    @endif
                    @if($value->jam_istirahat_masuk == "00:00:00")
                    <li class="text-danger">
                        Tidak Absen Masuk Istirahat
                    </li>
                    @endif
                    @if($value->jam_pulang == "00:00:00")
                    <li class="text-danger">
                        Tidak Absen Pulang
                    </li>
                    @endif
                </ul>
            </td>
        </tr>
        @endforeach
    </tbody>
</table> 