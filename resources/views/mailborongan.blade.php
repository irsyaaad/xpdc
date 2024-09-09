  @extends('template.email')


@section('title',"Auntentikasi Borongan")

@section('content')
    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Halo!</p>
    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Silakan Masukan Code Dibawah ini : </p>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%;" width="100%">
        <tbody>
        <tr>
            <td>
                <center><h2>@if(isset($kode)){{ $kode }}@endif </h2></center>
            </td>
        </tr>
        </tbody>
    </table>
    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-top: 12px; margin-bottom: 0px;">
    Abaikan email ini apabila anda tidak merequest kode authentikasi</p>
@endsection