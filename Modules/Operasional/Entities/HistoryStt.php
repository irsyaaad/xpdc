<?php

namespace Modules\Operasional\Entities;

use DB;
use Illuminate\Database\Eloquent\Model;

class HistoryStt extends Model
{
    protected $fillable = [];
    protected $table = "t_history_stt";
    public $incrementing = false;
    protected $primaryKey = 'id_history';
    public $keyType = 'string';

    public function status()
    {
        return $this->belongsTo('Modules\Operasional\Entities\StatusStt', 'id_status', 'id_ord_stt_stat');
    }

    public static function getHistory($id_stt)
    {
        $sql = "select id_stt,id_status,nm_penerima,keterangan,id_user,nm_user,nm_pengirim,upper(nm_status) as nm_status,place,gambar1, gambar2, no_status, id_wil, id_perush, nm_sopir, id_history,
        is_penerusan,tgl_update,is_notifikasi,foto_dooring,
        to_char(created_at, 'YYYY-MM-DD HH24:MI:SS') as created_at,
        to_char(updated_at, 'YYYY-MM-DD HH24:MI:SS') as updated_at from t_history_stt
         where id_stt = '" . $id_stt . "' order by id_history desc";

        $data = DB::select($sql);

        return $data;
    }

    public static function getHistoryDokumen($id_stt)
    {
        $sql = "select id_stt,id_status,nm_penerima,keterangan,id_user,nm_user,nm_pengirim,upper(nm_status) as nm_status,place,gambar1, gambar2, no_status, id_wil, id_perush, nm_sopir, id_history,
        is_penerusan,tgl_update,is_notifikasi,foto_dooring,
        to_char(created_at, 'YYYY-MM-DD HH24:MI:SS') as created_at,
        to_char(updated_at, 'YYYY-MM-DD HH24:MI:SS') as updated_at from t_history_stt
         where id_stt = '" . $id_stt . "' and kode_status::INTEGER > 90 order by created_at desc";

        $data = DB::select($sql);

        return $data;
    }
}
