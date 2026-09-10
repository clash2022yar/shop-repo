<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    public function showLogin(){ return view('auth.login'); }
    public function login(Request $request){
        $request->validate(['email'=>'required','password'=>'required']);
        $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        if(Auth::attempt([$field=>$request->email,'password'=>$request->password], $request->remember)){
            $request->session()->regenerate();
            if($request->ajax()) return response()->json(['message'=>'ورود موفق','redirect'=> route('user.dashboard')]);
            return redirect()->intended(route('user.dashboard'));
        }
        if($request->ajax()) return response()->json(['message'=>'اطلاعات ورود نادرست است'],422);
        return back()->withErrors(['email'=>'اطلاعات نادرست'])->onlyInput('email');
    }
    public function showRegister(){ return view('auth.register'); }
    public function register(Request $request){
        $request->validate(['name'=>'required|string|max:255','email'=>'required|email|unique:users','password'=>'required|min:8|confirmed','phone'=>'nullable|string']);
        $user = User::create(['name'=>$request->name,'email'=>$request->email,'phone'=>$request->phone,'password'=>Hash::make($request->password)]);
        Auth::login($user);
        if($request->ajax()) return response()->json(['message'=>'ثبت‌نام موفق','redirect'=> route('user.dashboard')]);
        return redirect()->route('user.dashboard');
    }
    public function logout(Request $request){ Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken(); return $request->ajax()? response()->json(['message'=>'خروج موفق']): redirect('/'); }
}
