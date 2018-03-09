<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Auth;
use Mail;

class UsersController extends Controller
{
   public function __construct()
   {
     $this->middleware('auth',[
       'except' => ['show','create','store','index', 'confirmEmail']
     ]);

     $this->middleware('auth',[
       'guest' => ['create']
     ]);
   }

   public function index()
   {
     $users = User::paginate(10);
     return view('users.index',compact('users'));
   }

    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
      return view('users.show',compact('user'));
    }

    public function store(Request $request)
    {
      $this->validate($request,[
        'name' => 'required|max:50',
        'email' => 'required|email|unique:users|max:255',
        'password' => 'required|confirmed|min:6'
      ]);

      $user = User::create([
        'name' => $request->name,
        'email' =>$request->email,
        'password' => bcrypt($request->password),
      ]);

      // Auth::login($user);先发送验证邮件
      $this->sendEmailConfirmationTo($user);
      session()->flash('success','验证邮件已发送到你的注册邮箱上，请注意查收。');
      return redirect()->route('/');
    }

    public function edit(User $user)
    {
      $this->authorize('update',$user);
      return view('users.edit',compact('user'));
    }

    public function update(User $user,Request $request)
    {
      $this->validate($request,[
        'name' => 'required|max:50',
        'password' => 'required|confirmed|min:6'
      ]);

      $this->authorize('update',$user);

      $user->update([
        'name' => $request->name,
        'password' => bcrypt($request->password),
      ]);

      session()->flash('success','个人资料更新成功！');

      return redirect()->route('users.show',$user->id);
    }

    public function destroy(User $user)
    {
      $this->authorize('destroy',$user);
      $user->delete();
      session()->flash('success','成功删除用户！');
      return back();
    }

    public function sendEmailConfirmationTo($user)
    {
      $view = 'emails.confrim';
      $data = compact('user');
      $from = '1025926969@qq.com';
      $name = 'NoNo1';
      $to = $user->email;
      $subject = "感谢注册！";

      Mail::send($view,$date,function ($message) use ($from,$name,$to,$subject){
        $message->from($from,$name)->to($to)->$subject;
      });
    }

    public function confirmEmail($token)
    {
      $user = User::where('activation_token',$token)->firstOrFail();

      $user->activated = true;
      $user->activation_token = null;
      $user->save();

      Auth::login($user);
      session()->flash('success','激活成功~');
      return redirect()->route('users.show',[$user]);
    }
}
