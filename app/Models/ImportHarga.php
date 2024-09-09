<?php

namespace App\Models;

use Maatwebsite\Excel\Concerns\ToModel;
use DB;
use App\Models\HargaVendor;
use Auth;
use Maatwebsite\Excel\Concerns\Importable;

class ImportHarga implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    
    use Importable;
    
    public function model(array $row)
    {
        $data  = [
            'wil_asal'     => $row[2],
            'wil_tujuan'    => $row[3],
            'id_ven'    => $row[4],
            'harga' => $row[5],
            'min_kg' => $row[6],
            'hrg_kubik' => $row[7],
            'min_kubik' => $row[8],
            'time' => $row[9],
            'keterangan' => $row[10],
            'rekomendasi' => $row[11],
            'same_balik' => $row[12],
            'id_user'    => Auth::user()->id_user,
            'parent'    => null,
            'type' => 1,
        ];
        
        return $data;
    }
    
    // public function model(array $row)
    // {
        //     $parent = [];
        //     $child = [];
        //     if($row[1]==null){
            //         $parent = [
                //             'wil_asal'     => $row[2],
                //             'wil_tujuan'    => $row[3],
                //             'id_ven'    => $row[4],
                //             'harga' => $row[5],
                //             'min_kg' => $row[6],
                //             'hrg_kubik' => $row[7],
                //             'min_kubik' => $row[8],
                //             'time' => $row[9],
                //             'keterangan' => $row[10],
                //             'rekomendasi' => $row[11],
                //             'id_user'    => Auth::user()->id_user,
                //             'parent'    => null,
                //             'type' => 1,
                //         ];
                //     }elseif($row[1]==null){
                    //         $child[$row[1]] = [
                        //             'wil_asal'     => $row[2],
                        //             'wil_tujuan'    => $row[3],
                        //             'id_ven'    => $row[4],
                        //             'harga' => $row[5],
                        //             'min_kg' => $row[6],
                        //             'hrg_kubik' => $row[7],
                        //             'min_kubik' => $row[8],
                        //             'time' => $row[9],
                        //             'keterangan' => $row[10],
                        //             'rekomendasi' => $row[11],
                        //             'id_user'    => Auth::user()->id_user,
                        //             'parent'    => null,
                        //             'type' => 1,
                        //         ];
                        //     }
                        //     $data["parent"] = $parent;
                        //     $data["child"] = $child;
                        
                        //     return $data;
                        // }
                    }