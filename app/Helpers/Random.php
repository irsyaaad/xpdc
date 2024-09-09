<?php 
if (! function_exists('getkode')) {
    function getkode($panjang)
    {
        $karakter       = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $panjangKata = strlen($karakter);
        $kode = '';
        for ($i = 0; $i < $panjang; $i++) {
            $kode .= $karakter[rand(0, $panjangKata - 1)];
        }
        return $kode;
    }
}

if (! function_exists('divnum')) {
    
    function divnum($numerator, $denominator)
    {
        return $denominator == 0 ? 0 : ($numerator / $denominator);
    }
    
}

if (! function_exists('inc_edit')) {
    function inc_edit($id)
    {   
        $menu = \App\Models\Menu::where("route", Request::segment(1))->first();
        
        $inc_role = \App\Models\RoleMenu::where("id_role", Session("role")["id_role"])->where("id_menu", $menu->id_menu)->first();
        $html = "";
        if($inc_role!=null){
            $url = "'".url(Request::segment(1).'/'.$id)."'";
            $html = '<center>';                            
            if($inc_role->c_update==true){
                $html = $html.'<a href="'.url(Request::segment(1).'/'.$id.'/edit').'" class="btn btn-sm btn-warning" style="color: #fff" data-toggle="tooltip" data-placement="bottom" title="Edit">
                <i class="fa fa-pencil"></i>
                </a>'; 
            }
            
            if($inc_role->c_delete==true){
                $html =    $html.' <button class="btn btn-sm btn-danger" id = "hapus" type="button" onclick="CheckDelete('.$url.')" data-toggle="tooltip" data-placement="bottom" title="Delete">
                <i class="fa fa-times"></i>
                </button>';
            }
            
            $html = $html.'</center>';
        }
        
        return $html;
    }
}

if (! function_exists('inc_delete')) {
    function inc_delete($id)
    {   
        $menu = \App\Models\Menu::where("route", Request::segment(1))->first();
        
        $inc_role = \App\Models\RoleMenu::where("id_role", Session("role")["id_role"])->where("id_menu", $menu->id_menu)->first();
        
        $html = "";
        if($inc_role!=null){
            $html = '<center>';if($inc_role->c_update==true){
                $html = $html.'<a href='.url(Request::segment(1).'/'.$id.'/edit').' class="btn btn-sm btn-warning" style="color: #fff" data-toggle="tooltip" data-placement="bottom" title="Edit">
                <i class="fa fa-pencil"></i>
                </a>'; 
            }'</center>';
        }
        return $html;
    }
}

if (! function_exists('inc_dropdown')) {
    function inc_dropdown($id)
    {   
        $menu = \App\Models\Menu::where("route", Request::segment(1))->first();
        
        $inc_role = \App\Models\RoleMenu::where("id_role", Session("role")["id_role"])->where("id_menu", $menu->id_menu)->first();
        
        $html = "";
        $url = "'".url(Request::segment(1).'/'.$id)."'";
        if($inc_role!=null){
            $html = '<center>
            <div class="dropdown">
            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Action
            </button>
            <div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">';
            if($inc_role->c_other==true){
                $html = $html.'<a class="dropdown-item" href="'.url(Request::segment(1)).'/'.$id.'/show'.'"><i class="fa fa-eye"></i> Detail</a>';
            }
            
            if($inc_role->c_update==true){
                $html = $html.'<a class="dropdown-item" href="'.url(Request::segment(1).'/'.$id.'/edit').'"><i class="fa fa-pencil"></i> Edit</a>';
            }
            
            if($inc_role->c_delete==true){
                $html = $html.'<a class="dropdown-item" href="#" onclick="CheckDelete('.$url.')"><i class="fa fa-times"></i> Delete</a>';
            }
            
            $html = $html.'</div>
            </div>
            </center>';
        }
        
        return $html;
    }
}

if (! function_exists('inc_dropdown_show')) {
    function inc_dropdown_show($id)
    {   
        $menu = \App\Models\Menu::where("route", Request::segment(1))->first();
        
        $inc_role = \App\Models\RoleMenu::where("id_role", Session("role")["id_role"])->where("id_menu", $menu->id_menu)->first();
        
        $html = "";
        if($inc_role!=null){
            $html = '<center>
            <div class="dropdown">
            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Action
            </button>
            <div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">';
            
            if($inc_role->c_other==true){
                $html = $html.'<a class="dropdown-item" href="'.url(Request::segment(1)).'/'.$id.'/show'.'"><i class="fa fa-eye"></i> Detail</a>';
            }
            
            if($inc_role->c_update==true){
                $html = $html.'<a class="dropdown-item" href="'.url(Request::segment(1).'/'.$id.'/edit').'"><i class="fa fa-pencil"></i> Edit</a>';
            }
            
            if($inc_role->c_delete==true){
                $html = $html.'<a class="dropdown-item" href="#" onclick="CheckDelete('."'".$id."'".')"><i class="fa fa-times"></i> Delete</a>';
            }
            
            $html = $html.'
            </div>
            </div>
            </center>';
        }
        
        return $html;
    }
}

if (! function_exists('inc_dropdown_edit')) {
    function inc_dropdown_edit($id)
    {   
        $menu = \App\Models\Menu::where("route", Request::segment(1))->first();
        
        $inc_role = \App\Models\RoleMenu::where("id_role", Session("role")["id_role"])->where("id_menu", $menu->id_menu)->first();
        
        $html = "";
        if($inc_role!=null){
            $html = '<center>
            <div class="dropdown">
            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Action
            </button>
            <div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="'.url(Request::segment(1).'/'.$id.'/edit').'"><i class="fa fa-pencil"></i> Edit</a>
            <a class="dropdown-item" href="'.url(Request::segment(1)).'/'.$id.'/show'.'"><i class="fa fa-eye"></i> Detail</a>
            </div>
            </div>
            </center>';
        }
        
        return $html;
    }
}

if (! function_exists('inc_dropdown_delete')) {
    function inc_dropdown_delete($id)
    {   
        $menu = \App\Models\Menu::where("route", Request::segment(1))->first();
        
        $inc_role = \App\Models\RoleMenu::where("id_role", Session("role")["id_role"])->where("id_menu", $menu->id_menu)->first();
        
        $html = "";
        if($inc_role!=null){
            $html = '<center>
            <div class="dropdown">
            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Action
            </button>
            <div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="#" onclick="CheckDelete('."'".$id."'".')"><i class="fa fa-times"></i> Delete</a>
            <a class="dropdown-item" href="'.url(Request::segment(1)).'/'.$id.'/show'.'"><i class="fa fa-eye"></i> Detail</a>
            </form>
            </div>
            </div>
            </center>';
        }
        
        return $html;
    }
}

if (! function_exists('route_redirect')) {
    function route_redirect()
    { 
        $route = Request::segment(1);
        
        return $route;
    }
}

if (! function_exists('get_menu')) {
    function get_menu($id)
    { 
        $data = \App\Models\Menu::where("route", $id)->get()->first();  
        
        if($data==null){
            
            return null;
        }else{
            
            return $data->nm_menu;
        }
    }
}

if (! function_exists('get_kode_perush')) {
    function get_kode_perush($id)
    { 
        $data = \App\Models\Perusahaan::findOrFail($id);
        
        return $data->kode_perush;
    }
}

if (! function_exists('toRupiah')) {
    function toRupiah($data, $digit = null)
    { 
        $data = "Rp. ".number_format($data, $digit, ',', '.');
        
        return $data;
    }
}

if (!function_exists('get_admin')) {
    function get_admin(){
        $role = \App\Models\Role::where("nm_role", "Administrator")->get()->first();
        $id = \App\Models\RoleUser::where("id_role", $role->id_role)->where("id_user", Auth::user()->id_user)->get()->first();
        
        if($id==null){
            return 0;
        }
        $ide = \App\Models\RoleUser::where("id_role", Session("role")["id_role"])->where("id_user", Auth::user()->id_user)->get()->first();
        
        if($id->id_role==$ide->id_role){
            
            return 1;
        }else{
            
            return 0;
        }
    }
}

if (! function_exists('get_role_menu')) {
    function get_role_menu($nama)
    { 
        $menu = \App\Models\Menu::where(DB::raw("lower(nm_menu)"), strtolower($nama))->get()->first();
        $data = \App\Models\RoleMenu::where("id_role", Session("role")["id_role"])->where("id_menu", $menu->id_menu)->get()->first();
        
        return $data;
    }
}