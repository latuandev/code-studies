<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CodeLearnController extends Controller
{
    // Trang luyện code
    public function CodeLearn() {
        $code_learn = DB::table('code_learn')->select('id', 'question')->get();
        return view('user.code-learn', compact('code_learn'));
    }
    // Lấy dữ liệu bài tập luyện code
    public function CodeLearnLoad(Request $request) {
        if($request->ajax()) {
            $id = $request->id;
            $pr = '';
            $url = url('/code-learn');
            $exercise = DB::table('code_learn')->select('question', 'request')->where('id', $id)->get();
            if(!$exercise->isEmpty()) {
                foreach ($exercise as $key => $value) {
                    $exc = nl2br($value->request);
                    $pr .= '
                        <b>Đề: '.$value->question.'</b><br><br>
                        '.$exc.'<br><br>
                        <button onclick="GetExample('.$id.')" class="btn btn-primary" style="font-size: 14px; margin-bottom: 5px;" id="btn_example_code">Code mẫu</button>
                        <a href="'.$url.'" class="btn btn-primary" style="font-size: 14px; margin-bottom: 5px;">Quay lại</a>
                    ';
                }
            } else {
                $pr .= '
                    <div class="alert alert-danger">Chưa có dữ liệu bài tập!</div>
                ';
            }
            echo $pr;
        }
    }
    // Chọn editor code theo ngôn  ngữ lập trình
    public function LanguageCode(Request $request) {
        if(Auth::check() && Auth::user()->role == 'user') {
            if($request->ajax()) {
                $languageCode = $request->code;
                $pr = '';
                if($languageCode == 'PHP') {
                    $pr .= '
                    <iframe src="https://paiza.io/projects/e/uRMXWyfUjap8X33wd8JmhQ?theme=monokai" width="100%" height="500" scrolling="no" seamless="seamless"></iframe>
                    ';
                } else if($languageCode == 'C#') {
                    $pr .= '
                    <iframe src="https://paiza.io/projects/e/eKOUKfC6n7_RZj4FpsoduQ?theme=monokai" width="100%" height="500" scrolling="no" seamless="seamless"></iframe>
                    ';
                } else if($languageCode == 'C') {
                    $pr .= '
                    <iframe src="https://paiza.io/projects/e/bCSI_0C8FMtYkD8_NE6EDg?theme=monokai" width="100%" height="500" scrolling="no" seamless="seamless"></iframe>
                    ';
                } else if($languageCode == 'C++') {
                    $pr .= '
                    <iframe src="https://paiza.io/projects/e/0_i5E3HJNplPHJ8nq_K7ug?theme=monokai" width="100%" height="500" scrolling="no" seamless="seamless"></iframe>
                    ';
                } else if($languageCode == 'Java') {
                    $pr .= '
                    <iframe src="https://trinket.io/embed/java/d1fcaa7e44" width="100%" height="356" frameborder="0" marginwidth="0" marginheight="0" allowfullscreen></iframe>
                    ';
                } else if($languageCode == 'Python') {
                    $pr .= '
                    <iframe src="https://trinket.io/embed/python3/9d578a67e3" width="100%" height="356" frameborder="0" marginwidth="0" marginheight="0" allowfullscreen></iframe>
                    ';
                } else if($languageCode == 'Swift') {
                    $pr .= '
                    <iframe src="https://paiza.io/projects/e/D-0dSqFcwJuCqFPphc8RvQ?theme=monokai" width="100%" height="500" scrolling="no" seamless="seamless"></iframe>
                    ';
                } else if($languageCode == 'Ruby') {
                    $pr .= '
                    <iframe src="https://paiza.io/projects/e/meF3psotGHNicxkZpDlvcA?theme=monokai" width="100%" height="500" scrolling="no" seamless="seamless"></iframe>
                    ';
                }
                echo $pr;
            }
        } else {
            return Redirect::to('/');
        }
    }
    // Lấy dữ liệu code mẫu
    public function ExampleCode(Request $request) {
        if($request->ajax()) {
            $pr ='';
            if(Auth::check() && Auth::user()->role == 'user') {
                $id = $request->id;
                $exp = DB::table('code_learn')->select('example')->where('id', $id)->get();
                if(!$exp->isEmpty()) {
                    foreach ($exp as $key => $value) {
                        $pr .= '
                            <br><textarea style="width: 100%; height: 400px; resize: none;" disabled>'.$value->example.'</textarea>
                        ';
                    }
                } else{
                    $pr .= '
                        <div class="alert alert-danger">Chưa có dữ liệu code mẫu!</div>
                    ';
                }
            } else {
                $pr .= '
                        <br><div class="alert alert-danger">Bạn cần đăng nhập để tiếp tục!</div>
                    ';
            }
            echo $pr;
        }
    }
}
