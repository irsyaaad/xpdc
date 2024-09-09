<table class="table table-responsive table-striped table-bordered">
    <thead style="background-color: grey; color : #ffff; border: 1px solid white;">
        <tr>
            <th rowspan="2" style="border: 1px solid white;">Karyawan</th>
            <th colspan="3" style="border: 1px solid white; text-align:center">Bekerja</th>
            <th colspan="4" style="border: 1px solid white; text-align:center">Tidak Bekerja (+)</th>
            <th colspan="10" style="border: 1px solid white; text-align:center">Tidak Bekerja (-)</th>
        </tr>
        <tr style="border: 1px solid white;">
            <td style="border: 1px solid white;">Hadir</td>
            <td style="border: 1px solid white;">Dinas Dalam Kota</td>
            <td style="border: 1px solid white;">Dinas Luar Kota</td>
            <td style="border: 1px solid white;">Cuti</td>
            <td style="border: 1px solid white;">Sakit</td>
            <td style="border: 1px solid white;">Berduka</td>
            <td style="border: 1px solid white;">Pulang Cepat (Sakit)</td>
            <td style="border: 1px solid white;">Izin Tidak Masuk</td>
            <td style="border: 1px solid white;">Izin Terlambat</td>
            <td style="border: 1px solid white;">Izin Pulang Cepat</td>
            <td style="border: 1px solid white;">Keluar</td>

            <td style="border: 1px solid white;">Terlambat</td>
            <td style="border: 1px solid white;">Tidak Absen Masuk</td>
            <td style="border: 1px solid white;">Terlambat Istirahat</td>
            <td style="border: 1px solid white;">Pulang Awal</td>
            <td style="border: 1px solid white;">Tidak Absen Pulang</td>
            <td style="border: 1px solid white;">Alpha</td>
        </tr>
    </thead>
    <tbody>
        @foreach($statistik as $key => $value)
        @php
            $alpha = $jmla-$jml-$value->absen;
        @endphp
        <tr>
            <td class="td-garis">{{ $value->nm_karyawan }}</td>
            <td class="td-garis">{{ $value->absen }}</td>
            <td class="td-garis">
                @if(isset($izin[$value->id_karyawan]) and $izin[$value->id_karyawan]->id_jenis=="id")
                @php
                    $alpha -= $izin[$value->id_karyawan]->ijin;
                @endphp
                {{ $izin[$value->id_karyawan]->ijin }}
                @endif
            </td>
            <td class="td-garis">
                @if(isset($izin[$value->id_karyawan]) and $izin[$value->id_karyawan]->id_jenis=="dk")
                @php
                    $alpha -= $izin[$value->id_karyawan]->ijin;
                @endphp
                {{ $izin[$value->id_karyawan]->ijin }}
                @endif
            </td>
            <td class="td-garis">
                @if(isset($izin[$value->id_karyawan]) and $izin[$value->id_karyawan]->id_jenis=="c")
                @php
                    $alpha -= $izin[$value->id_karyawan]->ijin;
                @endphp
                {{ $izin[$value->id_karyawan]->ijin }}
                @endif
            </td>
            <td class="td-garis">
                @if(isset($izin[$value->id_karyawan]) and $izin[$value->id_karyawan]->id_jenis=="s")
                @php
                    $alpha -= $izin[$value->id_karyawan]->ijin;
                @endphp
                {{ $izin[$value->id_karyawan]->ijin }}
                @endif
            </td>
            <td class="td-garis">
                @if(isset($izin[$value->id_karyawan]) and $izin[$value->id_karyawan]->id_jenis=="bd")
                @php
                    $alpha -= $izin[$value->id_karyawan]->ijin;
                @endphp
                {{ $izin[$value->id_karyawan]->ijin }}
                @endif
            </td>
            <td class="td-garis">
                @if(isset($izin[$value->id_karyawan]) and $izin[$value->id_karyawan]->id_jenis=="ps")
                {{ $izin[$value->id_karyawan]->ijin }}
                @endif
            </td>
            <td class="td-garis">
                @if(isset($izin[$value->id_karyawan]) and $izin[$value->id_karyawan]->id_jenis=="tm")
                @php
                    $alpha -= $izin[$value->id_karyawan]->ijin;
                @endphp
                {{ $izin[$value->id_karyawan]->ijin }}
                @endif
            </td>
            <td class="td-garis">
                @if(isset($izin[$value->id_karyawan]) and $izin[$value->id_karyawan]->id_jenis=="it")
                @php
                    $alpha -= $izin[$value->id_karyawan]->ijin;
                @endphp
                {{ $izin[$value->id_karyawan]->ijin }}
                @endif
            </td>
            <td class="td-garis">
                @if(isset($izin[$value->id_karyawan]) and $izin[$value->id_karyawan]->id_jenis=="ip")
                {{ $izin[$value->id_karyawan]->ijin }}
                @endif
            </td>
            <td class="td-garis">
                @if(isset($izin[$value->id_karyawan]) and $izin[$value->id_karyawan]->id_jenis=="k")
                {{ $izin[$value->id_karyawan]->ijin }}
                @endif
            </td>
            <td class="td-garis">
                @if(isset($sdatang[$value->id_karyawan][2]->jumlah))
                {{ $sdatang[$value->id_karyawan][2]->jumlah }}
                @endif
            </td>
            <td class="td-garis">
                @if(isset($sdatang[$value->id_karyawan][3]->jumlah))
                {{ $sdatang[$value->id_karyawan][3]->jumlah }}
                @endif
            </td>
            <td class="td-garis">
                @if(isset($sdatang[$value->id_karyawan][5]->jumlah))
                {{ $sdatang[$value->id_karyawan][5]->jumlah }}
                @endif
            </td>
            <td class="td-garis">
                @if(isset($sdatang[$value->id_karyawan][4]->jumlah))
                {{ $sdatang[$value->id_karyawan][4]->jumlah }}
                @endif
            </td>
            <td class="td-garis">
                @if(isset($spulang[$value->id_karyawan]->jumlah))
                {{ $spulang[$value->id_karyawan]->jumlah }}
                @endif
            </td>
            <td class="td-garis">
                @if($alpha<0)
                0
                @else
                {{ $alpha }}
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>