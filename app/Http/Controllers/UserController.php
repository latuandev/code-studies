<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /*
        Trang thông tin cá nhân người dùng
    */
        public function GetUser() {
            $userId = Auth::id();
            if(Auth::check() && Auth::user()->role == 'user') {
                $dataUser = DB::table('users')->select('id', 'name', 'age', 'email', 'avatar', 'phone', 'address', 'interests')->where('id', '=', $userId)->get();
                $date = getdate();
                $y = $date['year'];
                foreach ($dataUser as $key => $value) {
                    // Tinh so tuoi
                    $age = $value->age;
                    $ageNumber = $y - $age;

                    $user = array(
                        "id" => $value->id,
                        "name" => $value->name,
                        "age" => $ageNumber,
                        "email" => $value->email,
                        "avatar" => $value->avatar,
                        "phone" => $value->phone,
                        "address" => $value->address,
                        "interests" => $value->interests
                    );
                }
                // Lấy lịch sử làm bài của người dùng
                $history = DB::table('subject_sets')->join('user_results', 'subject_sets.code', '=', 'user_results.subject_set_code')
                ->where('user_id', '=', $userId)->select('subject_sets.level', 'subject_sets.name', 'user_results.subject_set_code', 'subject_sets.language_code', 'user_results.created_at')->get();
                return view('user.user', compact('history', 'user'));
            } else {
                return Redirect::to('/');
            }
        }
    /*
        Lấy dữ liệu modal chỉnh sửa thông tin người dùng
    */
        public function EditData(Request $request) {
            $userId = Auth::id();
            if(Auth::check() && Auth::user()->role == 'user') {
                if($request->ajax()) {
                    $data = DB::table('users')->select('id', 'name', 'age', 'email', 'avatar', 'phone', 'address', 'interests')->where('id', '=', $userId)->get();
                    $edit = ''; $edit2 = ''; $edit3 = '';
                    if(!$data->isEmpty()) {
                        $date = getdate();
                        $y = $date['year'];
                        foreach ($data as $value) {
                            $edit .= '
                                <b>Tên</b>
                                <input type="text" id="name" class="form-control" name="change_name" value="'.$value->name.'">
                                <b>Năm sinh</b>
                                <select class="form-control" id="change_age">
                                    <option value="'.$value->age.'" selected disabled="disabled">Năm sinh hiện tại: '.$value->age.'</option>
                            ';
                                    for($i = $y; $i >= 1920; $i--) {
                                        $edit2 .= '<option value="'.$i.'">'.$i.'</option>';
                                    }
                            $edit3 .= '</select>
                                <b>Email</b>
                                <input type="text" id="email" class="form-control" name="change_email" value="'.$value->email.'" disabled>
                                <b>Số điện thoại</b>
                                <input type="text" id="phone" class="form-control" name="change_phone" value="'.$value->phone.'">
                                <b>Địa chỉ</b>
                                <input type="text" id="address" class="form-control" name="change_address" value="'.$value->address.'">
                                <b>Sở thích</b><br>
                                <textarea style="width: 100%; height:150px; resize: none;" id="change_interests">'.$value->interests.'</textarea>
                            ';
                        }
                    } else {
                        $edit .= '
                            <div class"alert alert-danger">Không tìm thấy dữ liệu người dùng!</div>
                        ';
                    }
                    echo $edit.$edit2.$edit3;
                }
            } else {
                return Redirect::to('/');
            }
        }
    /*
        Cập nhật thông tin người dùng vào csdl
    */
        public function UpdateData(Request $request) {
            $userId = Auth::id();
            if(Auth::check() && Auth::user()->role == 'user') {
                if($request->ajax()) {
                    $data = array(
                        'name' => $request->name,
                        'age' => $request->age,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'interests' => $request->interests
                    );
                    DB::table('users')->where('id', '=', $userId)->update($data);
                }
            } else {
                return Redirect::to('/');
            }
        }
    /*
        Tải ảnh đại diện người dùng
    */
        public function UploadImage(Request $request) {
            $userId = Auth::id();
            if(Auth::check() && Auth::user()->role == 'user') {
                $validator = Validator::make($request->all(), [
                    'image' => 'required|mimes:jpeg,png,jpg|max:2048',
                ], [
                    'image.required' => 'Bạn chưa chọn file hình ảnh!',
                    'image.mimes' => 'Chỉ được chọn hình ảnh có đuôi file .jpeg, .png, .jpg!',
                    'image.max' => 'File hình ảnh quá lớn, hình ảnh phải nhỏ hơn 2048px!'
                ]);
                if ($validator->fails()) {
                    return response()->json(['error'=>$validator->errors()->all()]);
                } else {
                    $name = $request->file('image');
                    $newName = $name->getClientOriginalName();
                    $data = ['avatar' => $newName];
                    DB::table('users')->where('id', '=', $userId)->update($data);
                    $name->move(public_path('user/img'), $newName);
                    return response()->json([
                        'avatar' => '<a href="#"><img src="../public/user/img/'.$newName.'" data-toggle="modal" data-target="#myModalFile" class="rounded-circle" width="120px" height="120px"/></a><br><br>',
                        ]);
                }
            } else {
                return Redirect::to('/');
            }
        }
    /* END */
}
