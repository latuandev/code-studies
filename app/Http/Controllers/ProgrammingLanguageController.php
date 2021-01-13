<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProgrammingLanguageController extends Controller
{
    /*
        Trang ngôn ngữ lập trình
    */
        public function GetLevel($code) {
            $language = DB::table('languages')->select('name', 'des', 'url')
            ->where('code', $code)->get();
            return view('user.programming-language', compact('language', 'code'));
        }
    /*
        Lấy dữ liệu bộ đề trắc nghiệm
    */
        public function LoadSubjectSet(Request $request) {
            if(Auth::check() && Auth::user()->role == 'user') {
                if($request->ajax()) {
                    $code = $request->code;
                    $level = $request->level;
                    $pr_ul = ''; $pr_title = ''; $pr_end_ul = '';
                    $subjectSet = DB::table('subject_sets')->select('name', 'code')
                    ->where([['level', $level], ['language_code', $code]])->get();
                    if(!$subjectSet->isEmpty()) {
                        $pr_title .= '
                            <header><h3 class="h6" id="name_sj">Bộ đề trắc nghiệm</h3></header>
                            <hr class="sidebar-divider">
                            <ul class="list-inline">
                        ';
                        foreach ($subjectSet as $value) {
                            $sjCode = "'".$value->code."'";
                            $pr_ul .= '
                                <li class="list-inline-item"><button id="btn_click" onclick="GetQuestion('.$sjCode.')" type="button" class="btn btn-primary" style="margin-bottom: 5px; font-size: 14px;">'.$value->name.'</button></li>
                            ';
                        }
                        $pr_end_ul .= '
                            </ul>
                        ';
                    } else {
                        $pr_ul .= '
                            <header><h3 class="h6" id="name_sj">Bộ đề trắc nghiệm</h3></header>
                            <hr class="sidebar-divider">
                            <div class="alert alert-danger">Chưa có dữ liệu bộ đề!</div>
                        ';
                    }
                }
                echo $pr_title.$pr_ul.$pr_end_ul;
            } else {
                if($request->ajax()) {
                    $pr = '';
                    $pr .= '
                    <div class="alert alert-danger">Bạn cần đăng nhập để tiếp tục!</div>
                    ';
                    echo $pr;
                }
            }
        }
    /*
        Lấy dữ liệu câu hỏi
    */
        public function LoadQuestion(Request $request) {
            if(Auth::check() && Auth::user()->role == 'user') {
                if($request->ajax()) {
                    $sjCode = $request->sjCode;
                    $language_code = $request->code;
                    $userId = Auth::id();
                    $checkSubjectSetCode = ''; $count = 0; $pr = ''; $pr_title = ''; $pr_button = ''; $sjName = ''; $sjLevel = '';
                    // Lấy dữ liệu bộ đề đã làm của người dùng
                    $subjectSetCode = DB::table('user_results')->select('subject_set_code')
                    ->where([['user_id', '=', $userId], ['subject_set_code', '=', $sjCode]])->get();
                    foreach ($subjectSetCode as $value) {
                        $checkSubjectSetCode = $value->subject_set_code;
                    }
                    // Lấy tên và cấp độ bộ đề
                    $subjectSet = DB::table('subject_sets')->select('name', 'level')
                    ->where('code', '=', $sjCode)->get();
                    foreach ($subjectSet as $value) {
                        $sjName = $value->name;
                        $sjLevel = $value->level;
                    }
                    // Chưa có dữ liệu người dùng làm bộ đề
                    if($sjCode != $checkSubjectSetCode) {
                        // Lấy dữ liệu câu hỏi
                        $ques = DB::table('questions')->select('id', 'question', 'ans_a', 'ans_b', 'ans_c', 'ans_d', 'ans_correct')
                        ->where('subject_set_code', '=', $sjCode)->get();
                        $question = json_decode(json_encode($ques), true);
                        shuffle($question);
                        // dd($question);
                        if(count($question) > 0) {
                            $token = csrf_field();
                            $url = url('/programming-language/'.$language_code.'/'.$sjLevel.'/'.$sjCode);
                            $pr_title = '
                                <header><h6>Bộ đề: '.$sjName.' - Mã đề: '.$sjCode.' - Cấp độ: '.$sjLevel.'</h6></header>
                                <hr class="sidebar-divider">
                                <form action="'.$url.'" method="POST">'.$token.'
                            ';
                            $subjectSetDetail = DB::table('random_set_of_questions')->select('subject_set_code')->where([['user_id', $userId], ['subject_set_code', $sjCode]])->get();
                            if(count($subjectSetDetail) == count($question)) {
                                DB::table('random_set_of_questions')->where([['user_id', $userId], ['subject_set_code', $sjCode]])->delete();
                            }
                            $data = array();
                            foreach ($question as $value) {
                                $count++;
                                $data['user_id'] = $userId;
                                $data['subject_set_code'] = $sjCode;
                                $data['question'] = $value['question'];
                                $data['ans_a'] = $value['ans_a'];
                                $data['ans_b'] = $value['ans_b'];
                                $data['ans_c'] = $value['ans_c'];
                                $data['ans_d'] = $value['ans_d'];
                                $data['ans_correct'] = $value['ans_correct'];
                                DB::table('random_set_of_questions')->insert($data);
                                $pr .= '
                                    <b>Câu '.$count.'.</b> '.nl2br($value['question']).'<br>
                                    <label class="form-check-label" style="margin-left: 20px;"> <input type="radio" id="1" class="form-check-input" name="'.$count.'" value="A"> A) '.$value['ans_a'].' </label><br>
                                    <label class="form-check-label" style="margin-left: 20px;"> <input type="radio" id="2" class="form-check-input" name="'.$count.'" value="B"> B) '.$value['ans_b'].'</label><br>
                                    <label class="form-check-label" style="margin-left: 20px;"> <input type="radio" id="3" class="form-check-input" name="'.$count.'" value="C"> C) '.$value['ans_c'].'</label><br>
                                    <label class="form-check-label" style="margin-left: 20px;"> <input type="radio" id="4" class="form-check-input" name="'.$count.'" value="D"> D) '.$value['ans_d'].'</label><br>
                                ';
                            }
                            $total_question = 0;
                            $total_question = count($question);
                            $pr_button .= '
                                <input type="hidden" name="count_hidden" value="'.$total_question.'">
                                <div class="btn_submit_success" style="text-align: center"><br>
                                    <button id="btn_unsuccess" class="btn btn-primary" style="font-size: 14px;" disabled>
                                        <span class="spinner-grow spinner-grow-sm" style="margin-bottom:4px;"></span>
                                        Bạn chưa chọn đủ đáp án
                                    </button>
                                    <button type="submit" id="btn_success" class="btn btn-success" style="font-size: 14px;">
                                        Hoàn thành
                                    </button>
                                </div><br>
                                </form>
                            ';
                        } else {
                            $pr .= '
                                <header><h6>Bộ đề: '.$sjName.' - Mã đề: '.$sjCode.' - Cấp độ: '.$sjLevel.'</h6></header>
                                <div class="alert alert-danger">Chưa có dữ liệu câu hỏi!</div>
                            ';
                        }
                    // Có dữ liệu người dùng làm bộ đề
                    } else {
                        $pr .= '
                            <input type="hidden" name="hidden_check_sj" value="'.$checkSubjectSetCode.'">
                            <input type="hidden" name="hidden_language_code" value="'.$language_code.'">
                            <input type="hidden" name="hidden_sj_level" value="'.$sjLevel.'">
                        ';
                    }
                    echo $pr_title.$pr.$pr_button;
                }
            } else {
                return Redirect::to('/');
            }
        }
    /*
        Xử lý kết quả bài trắc nghiệm
    */
        public function HandleQuiz(Request $request, $code, $level, $quiz) {
            if(Auth::check() && Auth::user()->role == 'user') {
                $result = $request->all();
                $checkSubjectSetCode = '';
                $userId = Auth::id();
                // Lấy dữ liệu bộ đề đã làm của người dùng
                $subjectSetCode = DB::table('user_results')->select('subject_set_code')
                ->where([['user_id', '=', $userId], ['subject_set_code', '=', $quiz]])->get();
                foreach ($subjectSetCode as $value) {
                    $checkSubjectSetCode = $value->subject_set_code;
                }
                // Đã có dữ liệu bộ đề đã làm
                if($quiz == $checkSubjectSetCode) {
                    // Xóa dữ liệu bài làm cũ của người dùng
                    DB::table('user_result_details')->where([['user_id', '=', $userId], ['subject_set_code', '=', $quiz]])->delete();
                    // Vòng lặp loại bỏ key '_token' ra khỏi mảng dữ liệu đáp án người dùng đã chọn
                    foreach ($result as $key => $value) {
                        if($key == '_token' || $key == 'count_hidden') {
                            continue;
                        }
                        $saveUserAnsDetail = array();
                        $saveUserAnsDetail['user_id'] = Auth::id();
                        $saveUserAnsDetail['subject_set_code'] = $quiz;
                        $saveUserAnsDetail['user_ans'] = $value;
                        DB::table('user_result_details')->insert($saveUserAnsDetail);
                    }
                    // Lấy kết quả đã làm bộ đề của người dùng
                    $user_ans = DB::table('user_result_details')->select('user_ans')
                    ->where([['user_id', '=', $userId], ['subject_set_code', '=', $quiz]])->get();
                    $user = json_decode(json_encode($user_ans), true);
                    // Lấy dữ liệu câu trả lời đúng của bộ đề
                    $ansCorrect = DB::table('random_set_of_questions')->select('ans_correct')->where([['user_id', '=', $userId], ['subject_set_code', '=', $quiz]])->get();
                    $correct = json_decode(json_encode($ansCorrect), true);
                    // Lấy tên và cấp độ bộ đề
                    $subjectSet = DB::table('subject_sets')->select('name', 'level')->where('code', '=', $quiz)->get();
                    foreach ($subjectSet as $value) {
                        $subjectSetName = $value->name;
                    }
                    // Vòng lặp tính số câu trả lời đúng của người dùng
                    $totalAnsCorrect = 0;
                    for ($i = 0; $i < count($correct); $i++) {
                        if($correct[$i]['ans_correct'] == $user[$i]['user_ans']) {
                            $totalAnsCorrect++;
                        }
                    }
                    DB::table('user_results')->where([['user_id', '=', $userId], ['subject_set_code', '=', $quiz]])
                    ->update(['total_ans_correct' => $totalAnsCorrect, 'created_at' => Carbon::now()]);
                    // Chuyển đến trang hiển thị kết quả bài trắc nghiệm đã làm
                    return Redirect::to('/programming-language'.'/'.$code.'/'.$level.'/'.$quiz.'/result');
                // Chưa có dữ liệu bộ đề
                } else {
                    // Vòng lặp loại bỏ key '_token' ra khỏi mảng dữ liệu đáp án người dùng đã chọn
                    foreach ($result as $key => $value) {
                        if($key == '_token' || $key == 'count_hidden') {
                            continue;
                        }
                        $saveUserAnsDetail = array();
                        $saveUserAnsDetail['user_id'] = Auth::id();
                        $saveUserAnsDetail['subject_set_code'] = $quiz;
                        $saveUserAnsDetail['user_ans'] = $value;
                        DB::table('user_result_details')->insert($saveUserAnsDetail);
                    }
                    // Lấy kết quả đã làm bộ đề của người dùng
                    $user_ans = DB::table('user_result_details')->select('user_ans')->where([['user_id', '=', $userId], ['subject_set_code', '=', $quiz]])->get();
                    $user = json_decode(json_encode($user_ans), true);
                    // Lấy dữ liệu câu trả lời đúng của bộ đề
                    $ansCorrect = DB::table('random_set_of_questions')->select('ans_correct')->where([['user_id', '=', $userId], ['subject_set_code', '=', $quiz]])->get();
                    $correct = json_decode(json_encode($ansCorrect), true);
                    // Lấy tên và cấp độ bộ đề
                    $subjectSet = DB::table('subject_sets')->select('name', 'level')->where('code', '=', $quiz)->get();
                    foreach ($subjectSet as $value) {
                        $subjectSetName = $value->name;
                    }
                    // Vòng lặp tính số câu trả lời đúng của người dùng
                    $totalAnsCorrect = 0;
                    for ($i = 0; $i < count($correct); $i++) {
                        if($correct[$i]['ans_correct'] == $user[$i]['user_ans']) {
                            $totalAnsCorrect++;
                        }
                    }
                    $saveUserAns = array();
                    $saveUserAns['user_id'] = Auth::id();
                    $saveUserAns['language_code'] = $code;
                    $saveUserAns['subject_set_name'] = $subjectSetName;
                    $saveUserAns['subject_set_code'] = $quiz;
                    $saveUserAns['total_ans_correct'] = $totalAnsCorrect;
                    $saveUserAns['created_at'] = Carbon::now();
                    DB::table('user_results')->insert($saveUserAns);
                    // Chuyển đến trang hiển thị kết quả bài trắc nghiệm đã làm
                    return Redirect::to('/programming-language'.'/'.$code.'/'.$level.'/'.$quiz.'/result');
                }
            } else {
                return Redirect::to('/');
            }
        }
    /*
        Trang hiển thị dữ liệu bộ đề đã làm của người dùng
    */
        public function Result($code, $level, $quiz) {
            if(Auth::check() && Auth::user()->role == 'user') {
                $userId = Auth::id(); $createdAt = '';
                // Lấy kết quả bộ đề đã làm của người dùng
                $user_ans = DB::table('user_result_details')->select('user_ans')->where([['user_id', '=', $userId], ['subject_set_code', '=', $quiz]])->get();
                $user = json_decode(json_encode($user_ans), true);

                    // Lấy tổng số câu trả lời đúng của người dùng
                    $userResult = DB::table('user_results')->select('total_ans_correct')
                    ->where([['user_id', '=', $userId], ['subject_set_code', '=', $quiz]])->get();
                    // Lấy thời gian làm bài của người dùng
                    $time = DB::table('user_results')->select('created_at')->where([['user_id', '=', $userId], ['subject_set_code', '=', $quiz]])->get();
                    foreach ($time as $value) {
                        $createdAt = $value->created_at;
                    }
                    // Lấy tên và cấp độ bộ đề
                    $subjectSet = DB::table('subject_sets')->select('language_code', 'name', 'level')->where('code', '=', $quiz)->get();
                    // Lấy kết quả đúng của bộ đề
                    $ansCorrect = DB::table('random_set_of_questions')->select('ans_correct')->where([['user_id', '=', $userId], ['subject_set_code', '=', $quiz]])->get();
                    $correct = json_decode(json_encode($ansCorrect), true);
                    // Lấy dữ liệu câu hỏi và đáp án của bộ đề
                    $question = DB::table('random_set_of_questions')->select('question', 'ans_a', 'ans_b', 'ans_c', 'ans_d', 'ans_correct')->where([['user_id', '=', $userId], ['subject_set_code', '=', $quiz]])->get();
                return view('user.result', compact('code', 'level', 'quiz', 'correct', 'user', 'subjectSet', 'userResult', 'question', 'createdAt'));
            } else {
                return Redirect::to('/');
            }
        }
    /*
        Xử lý làm lại bài trắc nghiệm
    */
        public function Rework(Request $request) {
            if(Auth::check() && Auth::user()->role == 'user') {
                if($request->ajax()) {
                    $userId = Auth::id();
                    $sjCode = $request->sj_code;
                    $language_code = $request->language_code;
                    $count = 0; $pr = ''; $pr_button = ''; $pr_title = '';
                    DB::table('user_results')->where([['user_id', $userId], ['subject_set_code', $sjCode]])->delete();
                    DB::table('user_result_details')->where([['user_id', $userId], ['subject_set_code', $sjCode]])->delete();
                    // Lấy tên và cấp độ bộ đề
                    $subjectSet = DB::table('subject_sets')->select('name', 'level')
                    ->where('code', '=', $sjCode)->get();
                    foreach ($subjectSet as $value) {
                        $sjName = $value->name;
                        $sjLevel = $value->level;
                    }
                    // Lấy dữ liệu câu hỏi
                    $ques = DB::table('questions')->select('id', 'question', 'ans_a', 'ans_b', 'ans_c', 'ans_d', 'ans_correct')
                    ->where('subject_set_code', '=', $sjCode)->get();
                    if(!$ques->isEmpty()) {
                        $token = csrf_field();
                        $url = url('/programming-language/'.$language_code.'/'.$sjLevel.'/'.$sjCode);
                        $pr_title = '
                            <header><h6>Bộ đề: '.$sjName.' - Mã đề: '.$sjCode.' - Cấp độ: '.$sjLevel.'</h6></header>
                            <hr class="sidebar-divider">
                            <form action="'.$url.'" method="POST">'.$token.'
                        ';
                        $question = json_decode(json_encode($ques), true);
                        shuffle($question);
                        $subjectSetDetail = DB::table('random_set_of_questions')->select('subject_set_code')->where([['user_id', $userId], ['subject_set_code', $sjCode]])->get();
                        if(count($subjectSetDetail) == count($question)) {
                            DB::table('random_set_of_questions')->where([['user_id', $userId], ['subject_set_code', $sjCode]])->delete();
                        }
                        $data = array();
                        foreach ($question as $value) {
                            $count++;
                            $data['user_id'] = $userId;
                            $data['subject_set_code'] = $sjCode;
                            $data['question'] = $value['question'];
                            $data['ans_a'] = $value['ans_a'];
                            $data['ans_b'] = $value['ans_b'];
                            $data['ans_c'] = $value['ans_c'];
                            $data['ans_d'] = $value['ans_d'];
                            $data['ans_correct'] = $value['ans_correct'];
                            DB::table('random_set_of_questions')->insert($data);
                            $pr .= '
                                <b>Câu '.$count.'.</b> '.nl2br($value['question']).'<br>
                                <label class="form-check-label" style="margin-left: 20px;"> <input type="radio" class="form-check-input" name="'.$value['id'].'" value="A"> A) '.$value['ans_a'].' </label><br>
                                <label class="form-check-label" style="margin-left: 20px;"> <input type="radio" class="form-check-input" name="'.$value['id'].'" value="B"> B) '.$value['ans_b'].'</label><br>
                                <label class="form-check-label" style="margin-left: 20px;"> <input type="radio" class="form-check-input" name="'.$value['id'].'" value="C"> C) '.$value['ans_c'].'</label><br>
                                <label class="form-check-label" style="margin-left: 20px;"> <input type="radio" class="form-check-input" name="'.$value['id'].'" value="D"> D) '.$value['ans_d'].'</label><br>
                                ';
                        }
                        $total_question = 0;
                        $total_question = count($question);
                        $pr_button .= '
                            <input type="hidden" name="count_hidden" value="'.$total_question.'">
                            <div class="btn_submit_success" style="text-align: center"><br>
                                <button id="btn_unsuccess" class="btn btn-primary" disabled>
                                    <span class="spinner-grow spinner-grow-sm" style="margin-bottom:4px; font-size: 14px;"></span>
                                    Bạn chưa chọn đủ đáp án
                                </button>
                                <button type="submit" id="btn_success" class="btn btn-success" style="font-size: 14px;">
                                    Hoàn thành
                                </button>
                            </div><br>
                            </form>
                        ';
                    }
                    echo $pr_title.$pr.$pr_button;
                }
            } else {
                return Redirect::to('/');
            }
        }
    /*
        Xử lý kết quả xếp hạng người dùng
    */
        public function Rank(Request $request) {
            if(Auth::check() && Auth::user()->role == 'user') {
                $userId = Auth::id();
                $code = $request->id;
                if($request->ajax()) {
                    // Lấy kết quả làm bài của người dùng
                    $data = DB::table('user_results')->join('users', 'user_results.user_id', '=', 'users.id')
                    ->select('user_results.total_ans_correct', 'users.name', 'user_results.user_id')->where('user_results.subject_set_code', '=', $code)->get();
                    $sort = json_decode(json_encode($data), true);
                    rsort($sort);
                    $pr = ''; $pr1 = ''; $pr2 = '';
                    $pr1 .= '
                        <h6>Bảng xếp hạng (Top 100)</h6>
                        <hr class="sidebar-divider">
                    ';
                    if(!$data->isEmpty()) {
                        $count = 0;
                        foreach ($sort as $value) {
                            $count++;
                            if($count == 100) {
                                break;
                            }
                            // Lấy vị trí xếp hạng của người dùng đang đăng nhập hiện tại
                            if($value['user_id'] == $userId) {
                                $pr2 .= '
                                    <font color="green"><b>Vị trí của bạn thứ '.$count.'.</b> '.$value['name'].' - Số câu đúng '.$value['total_ans_correct'].'</font><br>
                                    <hr class="sidebar-divider">
                                ';
                            }
                            // Lấy vị trí xếp hạng của tất cả người dùng
                            $pr .= '
                                <b>Hạng thứ '.$count.'.</b> '.$value['name'].' - Số câu đúng '.$value['total_ans_correct'].'</font><br>
                            ';
                        }
                    } else {
                        $pr .= '
                            <div class="alert alert-danger">Không tìm thấy dữ liệu xếp hạng!<div>
                        ';
                    }
                    echo $pr1.$pr2.$pr;
                }
            } else {
                return Redirect::to('/');
            }
        }
    /* END */
}
