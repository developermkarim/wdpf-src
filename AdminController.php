<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function index()
    {
        return view('admin.login');
    }

    public function auth(Request $request)
    {
        /*return $request->post();*/

        $email = $request->post('email');
        $password = $request->post('password');
       /* $result = Admin::where(['email'=>$email,'password'=>$password])->get();*/

     /*   echo '<pre>';
        print_r($result);
        echo '</pre>';*/

        $result = Admin::where(['email'=>$email])->first();
        if($result)
        {
            
            if(Hash::check($request->post('password'),$result->password))
            {
            $request->session()->put('ADMIN_LOGIN',true);
            $request->session()->put('ADMIN_ID');
            $request->session()->flash('status',$result['status']);
            //   return redirect('admin/dashboard');
             return view('admin.activate');
            
        }

        else
        {
            $request->session()->flash('error','Please Enter Valid Password');
            return redirect('admin');
        }
    }
    else
    {
         $request->session()->flash('error','Please Enter Valid Login Details');
         return redirect('admin');
    }
}

    public function dashboard()
    {
        return view('admin.dashboard');
    }

public function deactivate()
{

   Admin::where('status',1)->update(array('status' => 0));
    session()->forget('ADMIN_LOGIN');
    session()->forget('ADMIN_ID');
    session()->flash('error','Account Deactivated');
    return redirect('admin');
}

public function activate()
{
 
   $result = Admin::where('status',0)->update(array('status' => 1));

    if($result && session()->has('ADMIN_LOGIN')){
        return redirect('admin/dashboard');
    }
    else{
        return redirect('admin');
    }
}

}



