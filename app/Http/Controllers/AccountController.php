<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    // Xử lý đăng ký tài khoản
    public function HandleRegistration(Request $request) {
        if($request->ajax()) {
            $name = $request->name;
            $age = $request->age;
            $email = $request->email;
            $password = $request->password;
            $confirmPassword = $request->confirm_password;
            $assetEmail = '';
            $selectEmail = DB::table('users')->select('email')->where('email', $email)->get();
            foreach ($selectEmail as $value) {
                $assetEmail = $value->email;
            }
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ], [
                'email.required' => '<div class="alert alert-danger">Bạn chưa nhập email!</div>',
                'email.email' => '<div class="alert alert-danger">Vui lòng nhập địa chỉ email hợp lệ!<div>',
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()->all()]);
            } else {
                if(($name == null) || ($age == null) || ($password == null) || ($confirmPassword == null)) {
                    return response()->json([
                        'null' => '<div class="alert alert-danger">Bạn chưa nhập đủ thông tin cần thiết!</div>'
                    ]);
                } else if($email == $assetEmail) {
                    return response()->json([
                        'error' => '<div class="alert alert-danger">Email đã được sử dụng!</div>'
                    ]);
                } else if($password != $confirmPassword) {
                    return response()->json([
                        'error' => '<div class="alert alert-danger">Mật khẩu bạn vừa nhập không trùng!</div>'
                    ]);
                } else {
                    $user = array();
                    $user['name'] = $name;
                    $user['age'] = $age;
                    $user['email'] = $email;
                    $user['password'] = bcrypt($password);
                    $user['role'] = 'user';
                    DB::table('users')->insert($user);
                    return response()->json([
                        'success' => '<div class="alert alert-success">Tạo tài khoản thành công!</div>'
                    ]);
                }
            }
        }
    }
    // Xử lý đăng nhập
    public function HandleLogin(Request $request) {
        if($request->ajax()) {
            $email = $request->email;
            $password = $request->password;
            if($email == null || $password == null) {
                return response()->json([
                    'null' => '<br><div class="alert alert-danger">Bạn chưa nhập đủ thông tin cần thiết!</div>'
                ]);
            } else if(Auth::attempt(['email' => $email, 'password' => $password])) {
                $role = Auth::user()->role;
                return response()->json([
                    'success' => '<br><input type="hidden" id="hidden_role" name="hidden_role" value="'.$role.'">'
                ]);
            } else {
                return response()->json([
                    'error' => '<br><div class="alert alert-danger">Đăng nhập thất bại. Vui lòng kiểm tra lại Email hoặc Mật khẩu!</div>'
                ]);
            }
        }
    }
    // Xử lý đăng xuất
    public function HandleLogout() {
        Auth::logout();
        return Redirect::to('/');
    }
    // Xử lý thay đổi mật khẩu
    public function ChangePassword(Request $request) {
        if(Auth::check() && Auth::user()->role == 'user') {
            $userId = Auth::id();
            $selectEmail = DB::table('users')->select('email')->where('id', $userId)->get();
            foreach ($selectEmail as $value) {
                $email = $value->email;
            }
            if($request->ajax()) {
                $old = $request->oldPassword;
                $new = $request->newPassword;
                $confirm = $request->confirmPassword;
                if($old == null || $new == null || $confirm == null) {
                    return response()->json([
                        'null' => '<div class="alert alert-danger">Bạn chưa nhập đủ thông tin cần thiết!</div>'
                    ]);
                } else if(Auth::attempt(['email' => $email, 'password' => $old])) {
                    if($new == $confirm) {
                        DB::table('users')->where('id', $userId)->update(['password' => bcrypt($new)]);
                        return response()->json([
                            'success' => '<div class="alert alert-success">Thay đổi mật khẩu thành công!</div>'
                        ]);
                    } else {
                        return response()->json([
                            'error' => '<div class="alert alert-danger">Mật khẩu mới bạn nhập không khớp nhau!</div>'
                        ]);
                    }
                } else {
                    return response()->json([
                        'error' => '<div class="alert alert-danger">Bạn nhập mật khẩu cũ không chính xác!</div>'
                    ]);
                }
            }
        } else {
            return Redirect::to('/');
        }
    }
    // Xử lý quên mật khẩu
    public function RecoveryPass(Request $request) {
        if($request->ajax()) {
            $pr = '';
            $rcv_email = $request->recovery_email;
            $titel_mail = 'Đặt lại mật khẩu webtracnghiem';
            $user_id = '';
            $user = DB::table('users')->select('id', 'email')->where('email', $rcv_email)->get();
            foreach ($user as $value) {
                $user_id = $value->id;
            }
            $validator = Validator::make($request->all(), [
                'recovery_email' => 'required|email',
            ], [
                'recovery_email.required' => '<div class="alert alert-danger">Bạn chưa nhập email!</div>',
                'recovery_email.email' => '<div class="alert alert-danger">Vui lòng nhập địa chỉ email hợp lệ!<div>',
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()->all()]);
            } else {
                if($user) {
                    $count_user = $user->count();
                    if($count_user == 0) {
                        return response()->json([
                            'error' => '<div class="alert alert-danger">Email này chưa đăng ký tài khoản!</div>'
                        ]);
                    } else {
                        $token_random = Str::random(15);
                        DB::table('users')->where('id', $user_id)->update(['email_verified_token' => $token_random]);
                        $to_email = $rcv_email; // Gửi đến email này
                        $link_reset_pass = url('/reset-pass?email='.$to_email.'&token='.$token_random);
                        // Phần data hiển thị ở thân email
                        $data = array(
                            "name" => $titel_mail,
                            "body" => $link_reset_pass,
                            'email' => $to_email
                        );
                        Mail::send('user.account.reset-pass-noti', ['data' => $data], function ($message) use ($titel_mail, $data){
                            $message->to($data['email'])->subject($titel_mail);
                            $message->from($data['email'], $titel_mail);
                        });
                        return response()->json([
                            'success' => '<div class="alert alert-success">Đã gửi email thành công! Vui lòng kiểm tra email để đặt lại mật khẩu!</div>'
                        ]);
                    }
                }
            }
        }
    }
    // Trang đặt lại mật khẩu mới
    public function SetNewPass() {
        return view('user.account.set-new-password');
    }
    // Xử lý đặt lại mật khẩu mới
    public function HandleSetNewPass(Request $request) {
        if($request->ajax()) {
            $email = $request->email;
            $token = $request->token;
            $new_pass = $request->new_password;
            $confirm_new_pass = $request->confirm_new_password;
            $user = DB::table('users')->select('id')->where(['email_verified_token' => $token])->get();
            $count_user = $user->count();
            if($count_user == 0) {
                return response()->json([
                    'error' => '<div class="alert alert-danger">Liên kết đã hết hạn! Vui lòng thực hiện lại quá trình "Quên mật khẩu!" !</div>'
                ]);
            } else {
                foreach ($user as $value) {
                    $user_id = $value->id;
                }
                if($new_pass != $confirm_new_pass) {
                    return response()->json([
                        'error' => '<div class="alert alert-danger">Mật khẩu bạn nhập không khớp!</div>'
                    ]);
                } else if($new_pass == '' || $confirm_new_pass == '') {
                    return response()->json([
                        'error' => '<div class="alert alert-danger">Bạn chưa nhập đủ thông tin!</div>'
                    ]);
                } else {
                    DB::table('users')->where('id', $user_id)->update(['password' => bcrypt($new_pass)]);
                    DB::table('users')->where('id', $user_id)->update(['email_verified_token' => '']);
                    return response()->json([
                        'success' => '<input type="hidden" name="set_new_pass_success" value="Success!">'
                    ]);
                }
            }
        }
    }
}
