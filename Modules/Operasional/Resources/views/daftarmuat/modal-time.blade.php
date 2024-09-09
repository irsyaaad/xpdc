<div class="modal fade" id="modal-time" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body text-center">
        <h5><b id="text-auth">Atur Waktu</b></h5>
      </div>
      <div class="modal-body">
        <div class="row" style="margin: 2%">
          <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('dmtrucking', $data->id_dm) }}" enctype="multipart/form-data">
            <table>
              <tr>
                <td>
                  <label for="date_etd" style="font-weight: bold;">
                    <b>Estimasi Waktu Berangkat</b> <span class="span-required"></span>
                  </label>

                  <input type="date" class="form-control m-input m-input--square" id="date_etd" name="date_etd" placeholder="Masukan Tanggal Estimasi">

                  @if ($errors->has('date_etd'))
                  <label style="color: red">
                    {{ $errors->first('date_etd') }}
                  </label>
                  @endif
                </td>
                <td>
                  <label for="time_etd" style="font-weight: bold;">
                     :
                  </label>

                  <input type="time" class="form-control m-input m-input--square" id="time_etd" name="time_etd" placeholder="Masukan Waktu Estimasi">

                  @if ($errors->has('time_etd'))
                  <label style="color: red">
                    {{ $errors->first('time_etd') }}
                  </label>
                  @endif
                </td>
                <td style="padding-left: 2%">
                  <label for="date_eta" style="font-weight: bold;">
                    <b>Aktual Waktu Berangkat</b> <span class="span-required"></span>
                  </label>

                  <input type="date" class="form-control m-input m-input--square" id="date_eta" name="date_eta" placeholder="Masukan Tanggal Aktual">

                  @if ($errors->has('date_eta'))
                  <label style="color: red">
                    {{ $errors->first('date_eta') }}
                  </label>
                  @endif
                </td>
                <td style="padding-left: 2%">
                  <label for="time_eta" style="font-weight: bold;">
                     :
                  </label>

                  <input type="time" class="form-control m-input m-input--square" id="time_eta" name="time_eta" placeholder="Masukan Waktu Aktual">

                  @if ($errors->has('time_eta'))
                  <label style="color: red">
                    {{ $errors->first('time_eta') }}
                  </label>
                  @endif
                </td>
              </tr>
            </table>
          </form>
        </div>
        <div class="text-right">
          <button type="button" class="btn btn-sm btn-success"><i class="fa fa-times"></i> Submit</button>
          <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        </div>
      </div>
    </div>
  </div>
</div>