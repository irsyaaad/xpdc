<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Email</title>
    <link rel="icon" href="{{asset('img/logo.png') }}">
    <style>
        @media only screen and (max-width: 620px) {
            table[class=body] h1 {
                font-size: 28px !important;
                margin-bottom: 10px !important;
            }
            table[class=body] p,
            table[class=body] ul,
            table[class=body] ol,
            table[class=body] td,
            table[class=body] span,
            table[class=body] a {
                font-size: 16px !important;
            }
            table[class=body] .wrapper,
            table[class=body] .article {
                padding: 10px !important;
            }
            table[class=body] .content {
                padding: 0 !important;
            }
            table[class=body] .container {
                padding: 0 !important;
                width: 100% !important;
            }
            table[class=body] .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
            }
            table[class=body] .btn table {
                width: 100% !important;
            }
            table[class=body] .btn a {
                width: 100% !important;
            }
            table[class=body] .img-responsive {
                height: auto !important;
                max-width: 100% !important;
                width: auto !important;
            }
        }
        @media all {
            .ExternalClass {
                width: 100%;
            }
            .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
                line-height: 100%;
            }
            .apple-link a {
                color: inherit !important;
                font-family: inherit !important;
                font-size: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                text-decoration: none !important;
            }
            .btn-primary table td:hover {
                background-color: #34495e !important;
            }
            .btn-primary a:hover {
                background-color: #34495e !important;
                border-color: #34495e !important;
            }
        }
    </style>
</head>
<body class="" style="font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" width="100%">
    <tr>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">&nbsp;</td>
        <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; max-width: 580px; padding: 10px; width: 580px; Margin: 0 auto;" width="580" valign="top">
            <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">

                <!-- START CENTERED WHITE CONTAINER -->
                <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">@yield('precontent','')</span>
                <div class="header">
                    <a href="#" style="color: #3498db; text-decoration: underline; margin: 25px auto; display: block; max-width: 80px;"></a>
                    <table role=border="0">
                    <tr>
                      <td width="35%">
                      @php

                        if (Storage::exists('public/uploads/perusahaan/'.$data['perusahaan']->logo)) {
                            $path = 'public/uploads/perusahaan/'.$data['perusahaan']->logo;

                            $full_path = Storage::path($path);
                            $base64 = base64_encode(Storage::get($path));
                            $image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
                            $data['perusahaan']->logo = $image;
                        }

                        @endphp
                        <img src="{{ $data['perusahaan']->logo }}" style="width: 120px">
                      </td>
                      <td>
                        <div style="text-align:center; margin-bottom:10px">
                        <p style="margin:1px">{{$data['perusahaan']->nm_perush}}</p>
                        <p style="margin:1px; font-size:12px">{{$data['perusahaan']->alamat}},{{$data['perusahaan']->kotakab}}-{{$data['perusahaan']->provinsi}}</p>
                        <p style="margin:1px; font-size:12px">Telp. {{$data['perusahaan']->telp}} (Hunting), Fax. {{$data['perusahaan']->fax}}</p>
                        </div>
                      </td>
                    </tr>
                    </table>
                </div>
                <table role="presentation" class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background: #ffffff; border-radius: 3px; width: 100%;" width="100%">

                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                    <hr>
                        <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;" valign="top">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" width="100%">
                                <tr>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top" width="30%">
                                        NO. STT
                                    </td>
                                    <td width="5%">:</td>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        {{$data['data']->id_stt}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        NAMA PENGIRIM
                                    </td>
                                    <td>:</td>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        {{strtoupper($data['data']->pengirim_nm)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        NAMA PENERIMA
                                    </td>
                                    <td>:</td>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        {{strtoupper($data['data']->penerima_nm)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        ASAL
                                    </td>
                                    <td>:</td>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        {{$data['data']->asal->nama_wil}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        LAYANAN
                                    </td>
                                    <td>:</td>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        {{strtoupper($data['data']->layanan->nm_layanan)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        TUJUAN
                                    </td>
                                    <td>:</td>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        {{$data['data']->tujuan->nama_wil}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        JUMLAH KOLI
                                    </td>
                                    <td>:</td>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        {{$data['data']->n_koli}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        BERAT
                                    </td>
                                    <td>:</td>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        {{$data['data']->n_berat}} Kg
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        VOLUME
                                    </td>
                                    <td>:</td>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        {{$data['data']->n_volume}} M3
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        TOTAL
                                    </td>
                                    <td>:</td>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                        {{"Rp. ".number_format($data['data']->c_total, 0, ',', '.').",-"}}
                                    </td>
                                </tr>
                                <tr><td><br></td></tr>
                                <tr>
                                <td style="font-family: sans-serif; font-size: 18px; vertical-align: top;" valign="top">
                                        Status Barang
                                    </td>
                                    <td>:</td>
                                    <td style="font-family: sans-serif; font-size: 18px; vertical-align: top;" valign="top">
                                        {{$data['data']->status->nm_ord_stt_stat}}
                                </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- END MAIN CONTENT AREA -->
                </table>
                

                <!-- START FOOTER -->
                <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" width="100%">
                        <tr>
                            <td class="content-block" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; color: #999999; font-size: 12px; text-align: center;" valign="top" align="center">
                                <span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;"></span>
                                <!--<br> Don't like these emails? <a href="http://i.imgur.com/CScmqnj.gif">Unsubscribe</a>.-->
                            </td>
                        </tr>
                        <tr>
                            <td class="content-block powered-by" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; color: #999999; font-size: 12px; text-align: center;" valign="top" align="center">
                                <a href="#" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">{{$data['perusahaan']->nm_perush}}</a>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- END FOOTER -->

                <!-- END CENTERED WHITE CONTAINER -->
            </div>
        </td>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">&nbsp;</td>
    </tr>
</table>
</body>
</html>