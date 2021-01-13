<?php

namespace App\Http\Controllers;

use App\Exports\QuestionsExport;
use App\Imports\QuestionsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    /*
        Trang dashboard
    */
        // Trang hướng dẫn
        public function GetDashboard() {
            if(Auth::check() && Auth::user()->role == 'admin') {
                return view('admin.user-manual');
            } else {
                return Redirect::to('/');
            }
        }
        // Tải xuống file mẫu câu hỏi
        public function DownloadFileExample() {
            if(Auth::check() && Auth::user()->role == 'admin') {
                $file = public_path().'/admin/file_cau_hoi_dinh_dang.xlsx';
                $headers = array(
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                );
                return Response::download($file, 'file_cau_hoi_dinh_dang.xlsx', $headers);
            } else {
                return Redirect::to('/');
            }
        }
    /*
        LANGUAGES
    */
        // Trang ngôn ngữ lập trình
        public function GetLanguage() {
            if(Auth::check() && Auth::user()->role == 'admin') {
                $data = DB::table('languages')->select('id', 'name', 'code')->get();
                return view('admin.language', compact('data'));
            } else {
                return Redirect::to('/');
            }
        }
        // Xử lý thêm ngôn ngữ lập trình
        public function HandleAddLanguage(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    $name = $request->add_name;
                    $code = $request->add_code;
                    $des = $request->add_des;
                    $url = $request->add_url;
                    $assetCode = '';
                    $selectCode = DB::table('languages')->select('code')->where('code', $code)->get();
                    foreach ($selectCode as $value) {
                        $assetCode = $value->code;
                    }
                    if($name == null || $code == null) {
                        return response()->json([
                            'null' => '<div class="alert alert-danger">Chưa nhập đủ thông tin cần thiết!</div>'
                        ]);
                    } else if ($code == $assetCode) {
                        return response()->json([
                            'error' => '<div class="alert alert-danger">Đã có mã đề này!</div>'
                        ]);
                    } else {
                        $data = array();
                        $data['name'] = $name;
                        $data['code'] = $code;
                        $data['des'] = $des;
                        $data['url'] = $url;
                        DB::table('languages')->insert($data);
                        return response()->json([
                            'success' => '<div class="alert alert-success">Thêm ngôn ngữ thành công!</div>'
                        ]);
                    }
                }
            } else {
                return Redirect::to('/');
            }
        }
        // Xử lý lấy dữ liệu ngôn ngữ hiển thị vào modal chỉnh sửa
        public function ModalEditLanguage(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    $id = $request->id;
                    $pLanguage = DB::table('languages')->select('id', 'name', 'code', 'des', 'url')->where('id', $id)->get();
                    if(!$pLanguage->isEmpty()) {
                        foreach ($pLanguage as $value) {
                            return response()->json([
                                'success' => '  <input type="hidden" name="hidden_id" value="'.$value->id.'">
                                                <b>Tên ngôn ngữ lập trình</b>
                                                <input type="text" class="form-control" name="name" value="'.$value->name.'">
                                                <b>Mã ngôn ngữ lập trình</b>
                                                <input type="text" class="form-control" name="code" value="'.$value->code.'">
                                                <b>Mô tả</b>
                                                <textarea style="width: 100%; height: 200px; resize: none;" name="des">'.$value->des.'</textarea><br>
                                                <b>Liên kết</b>
                                                <input type="text" class="form-control" name="url" value="'.$value->url.'">'
                            ]);
                        }
                    } else {
                        return response()->json([
                            'error' => 'Không tìm thấy dữ liệu!'
                        ]);
                    }
                }
            } else {
                return Redirect::to('/');
            }
        }
        // Xử lý chỉnh sửa dữ liệu NNLT
        public function HandleUpdateLanguage(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    $id = $request->hidden_id;
                    $name = $request->name;
                    $code = $request->code;
                    $des = $request->des;
                    $url = $request->url;
                    $data = array();
                    $data['name'] = $name;
                    $data['code'] = $code;
                    $data['des'] = $des;
                    $data['url'] = $url;
                    DB::table('languages')->where('id', $id)->update($data);
                    return response()->json([
                        'success' => '<div class="alert alert-success">Cập nhật ngôn ngữ thành công!</div>'
                    ]);
                }
            } else {
                return Redirect::to('/');
            }
        }
        // Xóa NNLT
        public function HandleDeleteLanguage(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    $id = $request->id;
                    DB::table('languages')->where('id', $id)->delete();
                }
            } else {
                return Redirect::to('/');
            }
        }
    /*
        END LANGUAGES
    */

    /*
        SUBJECT SETS
    */
        // Trang bộ đề trắc nghiệm
        public function GetSubjectSet() {
            if(Auth::check() && Auth::user()->role == 'admin') {
                $data = DB::table('subject_sets')->select('id', 'language_code', 'name', 'code', 'level')->get();
                $language = DB::table('languages')->select('code')->get();
                return view('admin.subject-set', compact('data', 'language'));
            } else {
                return Redirect::to('/');
            }
        }
        // Xử lý lấy dữ liệu bộ đề khi chọn ngôn ngữ
        public function HandleSelectLanguage(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    $languageCode = $request->code;
                    $pr = '';
                    $count = 0;
                    $url = url('/dashboard-subject-set');
                    if($languageCode == 'all') {
                        $data = DB::table('subject_sets')->select('id', 'language_code', 'name', 'code', 'level')->get();
                        if(!$data->isEmpty()) {
                            foreach ($data as $value) {
                                $count++;
                                $pr .= '
                                    <tr>
                                        <td>'.$count.'</td>
                                        <td>'.$value->language_code.'</td>
                                        <td>'.$value->name.'</td>
                                        <td>'.$value->code.'</td>
                                        <td>'.$value->level.'</td>
                                        <td>
                                            <a href="'.$url.'/'.$value->code.'" class="btn btn-info">Chi tiết</a>
                                            <button onclick="DeleteSubjectSet('.$value->id.')" type="button" id="btn_delete" class="btn btn-danger">Xóa</button>
                                        </td>
                                    </tr>
                                ';
                            }
                        } else {
                            $pr .= '
                                <div class="alert alert-danger">Không tìm thấy dữ liệu!</div>
                            ';
                        }
                    } else {
                        $data = DB::table('subject_sets')->select('id', 'language_code', 'name', 'code', 'level')
                        ->where('language_code', $languageCode)->get();
                        if(!$data->isEmpty()) {
                            foreach ($data as $value) {
                                $count++;
                                $pr .= '
                                    <tr>
                                        <td>'.$count.'</td>
                                        <td>'.$value->language_code.'</td>
                                        <td>'.$value->name.'</td>
                                        <td>'.$value->code.'</td>
                                        <td>'.$value->level.'</td>
                                        <td>
                                            <a href="'.$url.'/'.$value->code.'" class="btn btn-info">Chi tiết</a>
                                            <button onclick="DeleteSubjectSet('.$value->id.')" type="button" id="btn_delete" class="btn btn-danger">Xóa</button>
                                        </td>
                                    </tr>
                                ';
                            }
                        } else {
                            $pr .= '
                                <div class="alert alert-danger">Không tìm thấy dữ liệu!</div>
                            ';
                        }
                    }
                    echo $pr;
                }
            } else {
                return Redirect::to('/');
            }
        }
        // Xử lý tìm kiếm mã bộ đề
        public function SearchSubjectSet(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    $search = $request->search;
                    $pr = '';
                    $count = 0;
                    $url = url('/dashboard-subject-set');
                    $data = DB::table('subject_sets')->select('id', 'language_code', 'name', 'code', 'level')
                    ->where('code', 'like', '%'.$search.'%')->get();
                    if(!$data->isEmpty()) {
                        foreach ($data as $value) {
                            $count++;
                            $pr .= '
                                <tr>
                                    <td>'.$count.'</td>
                                    <td>'.$value->language_code.'</td>
                                    <td>'.$value->name.'</td>
                                    <td>'.$value->code.'</td>
                                    <td>'.$value->level.'</td>
                                    <td>
                                        <a href="'.$url.'/'.$value->code.'" class="btn btn-info">Chi tiết</a>
                                        <button onclick="DeleteSubjectSet('.$value->id.')" type="button" id="btn_delete" class="btn btn-danger">Xóa</button>
                                    </td>
                                </tr>
                            ';
                        }
                    } else {
                        $pr .= '
                            <div class="alert alert-danger">Không tìm thấy dữ liệu!</div>
                        ';
                    }
                    echo $pr;
                }
            } else {
                return Redirect::to('/');
            }
        }
        // Xử lý thêm bộ đề
        public function HandlAddSubjectSet(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    $languageCode = $request->code;
                    $name = $request->name;
                    $level = $request->level;
                    $assetName = '';
                    $pr = '';
                    // Tạo chuỗi ngẫu nhiên
                    $chars = '0123456789';
                    $code = substr(str_shuffle($chars), 0, 5);
                    $checkName = DB::table('subject_sets')->select('name')->where([['name', $name], ['level', $level]])->get();
                    foreach ($checkName as $value) {
                        $assetName = $value->name;
                    }
                    // Mã bộ đề
                    $sjCode = $languageCode.'-'.$code;
                    if($name == null) {
                        $pr .= '
                            <div class="alert alert-danger">Chưa nhập đủ thông tin cần thiết!</div>
                        ';
                    } else if($name == $assetName) {
                        $pr .= '
                            <div class="alert alert-danger">Đã có tên bộ đề này!</div>
                        ';
                    }else {
                        $pr .= '
                            <div class="alert alert-success">Tạo bộ đề thành công!</div>
                        ';
                        $data = array();
                        $data['language_code'] = $languageCode;
                        $data['name'] = $name;
                        $data['code'] = $sjCode;
                        $data['level'] = $level;
                        DB::table('subject_sets')->insert($data);
                    }
                }
                echo $pr;
            } else {
                return Redirect::to('/');
            }
        }
        // Xử lý xóa bộ đề
        public function DeleteSubjectSet(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    $id = $request->id;
                    $jsCode = $request->code;
                    DB::table('subject_sets')->where('id', $id)->delete();
                    DB::table('questions')->where('subject_set_code', $jsCode)->delete();
                }
            } else {
                return Redirect::to('/');
            }
        }
        // Trang chi tiết bộ đề
        public function GetSubjectSetDetail($id) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                $subjectSet = DB::table('subject_sets')->select('id', 'language_code', 'name', 'code', 'level')->where('code', $id)->get();
                $data = DB::table('questions')->select('subject_set_code', 'question', 'ans_a', 'ans_b', 'ans_c', 'ans_d', 'ans_correct')->where('subject_set_code', $id)->get();
                $questionArray = json_decode(json_encode($data), true);
                return view('admin.question', compact('data', 'subjectSet', 'questionArray'));
            } else {
                return Redirect::to('/');
            }
        }
        // Modal chỉnh sửa bộ đề
        public function EditModalSubjectSet(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    $id = $request->id;
                    $subjectSet = DB::table('subject_sets')->select('language_code', 'name', 'code', 'level')
                    ->where('code', $id)->get();
                    if(!$subjectSet->isEmpty()) {
                        foreach ($subjectSet as $value) {
                            return response()->json([
                                'success' => '  <input type="hidden" name="hidden_id" value="'.$value->code.'">
                                                Mã ngôn ngữ lập trình
                                                <input type="text" class="form-control" name="language_code" value="'.$value->language_code.'" disabled>
                                                Tên bộ đề trắc nghiệm
                                                <input type="text" class="form-control" name="name" value="'.$value->name.'">
                                                Mã bộ đề trắc nghiệm
                                                <input type="text" class="form-control" name="language_code" value="'.$value->code.'" disabled>
                                                Cấp độ
                                                <select class="form-control" id="sel_level">
                                                    <option value="Dễ">Dễ</option>
                                                    <option value="Khó">Khó</option>
                                                    <option value="Tổng hợp">Tổng hợp</option>
                                                </select>'
                            ]);
                        }
                    } else {
                        return response()->json([
                            'error' => '<div class="alert alert-danger">Không tìm thấy dữ liệu!</div>'
                        ]);
                    }
                }
            } else {
                return Redirect::to('/');
            }
        }
        // Cập nhật thông tin bộ đề
        public function UpdateSubjectSet(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    $id = $request->id;
                    $name = $request->name;
                    $level = $request->level;
                    $data = array();
                    $data['name'] = $name;
                    $data['level'] = $level;
                    DB::table('subject_sets')->where('code', $id)->update($data);
                    return response()->json([
                        'success' => '<div class="alert alert-success">Cập nhật thành công!</div>'
                    ]);
                }
            } else {
                return Request::to('/');
            }
        }
        // Tải lên câu hỏi
        public function UploadExcel(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    $validator = Validator::make($request->all(), [
                        'select_file' => 'required|mimes:xls,xlsx'
                    ], [
                        'select_file.required' => '<div class="alert alert-danger">Bạn chưa chọn file excel!</div>',
                        'select_file.mimes' => '<div class="alert alert-danger">Chỉ được chọn file excel có đuôi file .xls, .xlsx!</div>',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['error'=>$validator->errors()->all()]);
                    } else {
                        $path = $request->file('select_file');
                        $code = $request->hidden_code;
                        $data = DB::table('questions')->select('subject_set_code')->where('subject_set_code', $code)->get();
                        if(!$data->isEmpty()) {
                            DB::table('questions')->where('subject_set_code', $code)->delete();
                            Excel::import(new QuestionsImport, $path);
                            return response()->json([
                                'success' => '<div class="alert alert-success">Cập nhật dữ liệu câu hỏi thành công!</div>'
                            ]);
                        } else {
                            Excel::import(new QuestionsImport, $path);
                            return response()->json([
                                'success' => '<div class="alert alert-success">Tải lên dữ liệu câu hỏi thành công!</div>'
                            ]);
                        }
                    }
                }
            } else {
                return Redirect::to('/');
            }
        }
        // Tải xuống câu hỏi
        public function DownloadExcelQuestion($code) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                $checkQuestion = DB::table('questions')->select('subject_set_code')
                ->where('subject_set_code', $code)->get();
                if(!$checkQuestion->isEmpty()) {
                    $nameFile = $code.'_questions.xlsx';
                    return (new QuestionsExport($code))->download($nameFile);
                } else {
                    return redirect()->back();
                }
            } else {
                return Redirect::to('/');
            }
        }
    /*
        END SUBJECT SETS
    */

    /*
        CODE LEARN
    */
        // Trang bài tập
        public function GetCodeLearn() {
            $data = DB::table('code_learn')->select('id', 'code', 'question')->get();
            return view('admin.code-learn', compact('data'));
        }
        // Thêm bài tập
        public function AddExercise(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    // Tạo chuỗi ngẫu nhiên
                    $chars = '0123456789';
                    $str = substr(str_shuffle($chars), 0, 5);
                    $code = 'exercise-'.$str;
                    $question = $request->add_question;
                    $req = $request->add_request;
                    $example = $request->add_example;
                    if($question == null || $req == null || $example == null) {
                        return response()->json([
                            'null' => '<div class="alert alert-danger">Chưa nhập đủ thông tin cần thiết!</div>'
                        ]);
                    } else {
                        $data = array();
                        $data['code'] = $code;
                        $data['question'] = $question;
                        $data['request'] = $req;
                        $data['example'] = $example;
                        DB::table('code_learn')->insert($data);
                        return response()->json([
                            'success' => '<div class="alert alert-success">Thêm bài tập thành công!</div>'
                        ]);
                    }
                }
            } else {
                return Redirect::to('/');
            }
        }
        // Tìm kiếm bài tập
        public function SearchExercise(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    $search = $request->search;
                    $pr = '';
                    $count = 0;
                    $url = url('/dashboard-code-learn');
                    $data = DB::table('code_learn')->select('id', 'code', 'question')
                    ->where('code', 'like', '%'.$search.'%')->get();
                    if(!$data->isEmpty()) {
                        foreach ($data as $value) {
                            $count++;
                            $pr .= '
                                <tr>
                                    <td>'.$count.'</td>
                                    <td>'.$value->code.'</td>
                                    <td>'.$value->question.'</td>
                                    <td>
                                        <button style="margin: 5px;" onclick="EditModal({{$item->id}})" type="button" id="btn_edit" class="btn btn-primary" data-toggle="modal" data-target="#editModal">Chi tiết</button>
                                        <button onclick="DeleteExercise({{$item->id}})" type="button" id="btn_delete" class="btn btn-danger">Xóa</button>
                                    </td>
                                </tr>
                            ';
                        }
                    } else {
                        $pr .= '
                            <div class="alert alert-danger">Không tìm thấy dữ liệu!</div>
                        ';
                    }
                    echo $pr;
                }
            } else {
                return Redirect::to('/');
            }
        }
        // Chi tiết bài tập
        public function ExerciseContent(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    $id = $request->id;
                    $exc = DB::table('code_learn')->select('id', 'code', 'question', 'request', 'example')->where('id', $id)->get();
                    if(!$exc->isEmpty()) {
                        foreach ($exc as $value) {
                            return response()->json([
                                'success' => '  <input type="hidden" name="hidden_id" value="'.$value->id.'">
                                                <b>Mã bài tập</b>
                                                <input type="text" class="form-control" name="code" value="'.$value->code.'" disabled>
                                                <b>Đề bài tập</b>
                                                <textarea style="width: 100%; height: 100px;" name="update_question">'.$value->question.'</textarea><br>
                                                <b>Yêu cầu</b>
                                                <textarea style="width: 100%; height: 200px;" name="update_request">'.$value->request.'</textarea><br>
                                                <b>Code mẫu</b>
                                                <textarea style="width: 100%; height: 200px;" name="update_example">'.$value->example.'</textarea><br>
                                            '
                            ]);
                        }
                    } else {
                        return response()->json([
                            'error' => 'Không tìm thấy dữ liệu!'
                        ]);
                    }
                }
            } else {
                return Redirect::to('/');
            }
        }
        // Cập nhật bài tập
        public function UpdateExercise(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    $id = $request->hidden_id;
                    $question = $request->update_question;
                    $req = $request->update_request;
                    $example = $request->update_example;
                    $data = array();
                    $data['question'] = $question;
                    $data['request'] = $req;
                    $data['example'] = $example;
                    DB::table('code_learn')->where('id', $id)->update($data);
                    return response()->json([
                        'success' => '<div class="alert alert-success">Cập nhật bài tập thành công!</div>'
                    ]);
                }
            } else {
                return Redirect::to('/');
            }
        }
        // Xóa bài tập
        public function DeleteExercise(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    $id = $request->id;
                    DB::table('code_learn')->where('id', $id)->delete();
                }
            } else {
                return Redirect::to('/');
            }
        }
    /*
        END CODE LEARN
    */

    /*
        USER MANAGER
    */
        // Trang quản lý người dùng
        public function GetUserManager() {
            if(Auth::check() && Auth::user()->role == 'admin') {
                $user = DB::table('users')->select('id', 'name', 'age', 'email', 'phone', 'address', 'avatar')
                ->where('role', 'user')->get();
                return view('admin.user-manager', compact('user'));
            } else {
                return Redirect::to('/');
            }
        }
        // Xóa người dùng
        public function HandleDeleteUser(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    $id = $request->id;
                    DB::table('users')->where('id', $id)->delete();
                    DB::table('user_results')->where('user_id', $id)->delete();
                    DB::table('user_result_details')->where('user_id', $id)->delete();
                }
            } else {
                return Redirect::to('/');
            }
        }
        // Xem lịch sử người dùng
        public function GetHistoryUser(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    $id = $request->id;
                    $count = 0;
                    $pr = '';
                    // lịch sử người dùng
                    $history = DB::table('subject_sets')->join('user_results', 'subject_sets.code', '=', 'user_results.subject_set_code')->where('user_id', '=', $id)
                    ->select('subject_sets.level', 'subject_sets.name', 'user_results.subject_set_code', 'subject_sets.language_code', 'user_results.created_at', 'user_results.total_ans_correct')->get();
                    if(!$history->isEmpty()) {
                        foreach ($history as $value) {
                            $count++;
                            $url = url('/dashboard-subject-set/'.$value->subject_set_code);
                            $pr .= '
                                <b>'.$count.'.</b> Bộ đề: '.$value->name.' - Mã đề: <a href="'.$url.'">'.$value->subject_set_code.'</a> - Thời gian làm: '.$value->created_at.' - Kết quả đạt được: '.$value->total_ans_correct.'<br>
                            ';
                        }
                    } else {
                        $pr .= '
                            <div class="alert alert-danger">Chưa có dữ liệu người dùng!</div>
                        ';
                    }
                    echo $pr;
                }
            } else {
                return Redirect::to('/');
            }
        }
        // Tìm kiếm người dùng
        public function HandleSearchUser(Request $request) {
            if(Auth::check() && Auth::user()->role == 'admin') {
                if($request->ajax()) {
                    $search = $request->search;
                    $pr = '';
                    $count = 0;
                    $data = DB::table('users')->select('id', 'name', 'age', 'email', 'phone', 'address', 'avatar')
                    ->where([['name', 'like', '%'.$search.'%'],['email', 'like', '%'.$search.'%']])->get();
                    if(!$data->isEmpty()) {
                        foreach ($data as $value) {
                            $count++;
                            if($value->avatar == null) {
                                $pr .= '
                                <tr>
                                    <td>'.$count.'</td>
                                    <td>
                                        <i class="fas fa-user-circle fa-4x"></i><br>
                                        '.$value->name.'
                                    </td>
                                    <td>'.$value->age.'</td>
                                    <td>'.$value->email.'</td>
                                    <td>'.$value->phone.'</td>
                                    <td>'.$value->address.'</td>
                                    <td>
                                        <button style="margin: 5px;" onclick="HistoryModal('.$value->id.')" type="button" id="btn_history" class="btn btn-info" data-toggle="modal" data-target="#historyModal">Lịch sử</button>
                                        <button onclick="DeleteUser('.$value->id.')" type="button" id="btn_delete" class="btn btn-danger">Xóa</button>
                                    </td> {{ csrf_field() }}
                                </tr>';
                            } else {
                                $pr .= '
                                <tr>
                                    <td>'.$count.'</td>
                                    <td>
                                        <img src="user/img/'.$value->avatar.'" class="rounded-circle" style="width: 70px; height: 70px"><br>
                                        '.$value->name.'
                                    </td>
                                    <td>'.$value->age.'</td>
                                    <td>'.$value->email.'</td>
                                    <td>'.$value->phone.'</td>
                                    <td>'.$value->address.'</td>
                                    <td>
                                        <button style="margin: 5px;" onclick="HistoryModal('.$value->id.')" type="button" id="btn_history" class="btn btn-info" data-toggle="modal" data-target="#historyModal">Lịch sử</button>
                                        <button onclick="DeleteUser('.$value->id.')" type="button" id="btn_delete" class="btn btn-danger">Xóa</button>
                                    </td> {{ csrf_field() }}
                                </tr>';
                            }
                        }
                    } else {
                        $pr .= '
                            <div class="alert alert-danger">Không tìm thấy dữ liệu!</div>
                        ';
                    }
                    echo $pr;
                }
            } else {
                return Redirect::to('/');
            }
        }
    /*
        END USER MANAGER
    */
}
