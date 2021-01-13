@extends('user.layout.user-layout')
@section('header')
<header class="header">
    <nav class="navbar navbar-expand-lg">
        <div class="search-area">
            <div class="search-area-inner d-flex align-items-center justify-content-center">
                <div class="close-btn"><i class="icon-close"></i></div>
                <div class="row d-flex justify-content-center">
                    <div class="col-md-8">
                        <form action="#">
                            <div class="form-group">
                                <input type="search" name="search" id="search" placeholder="What are you looking for?">
                                <button type="submit" class="submit"><i class="icon-search-1"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="navbar-header d-flex align-items-center justify-content-between">
                <div class="navbar-brand"><i class="fas fa-laugh-wink"></i> CODESTUDIES</div>
                <button type="button" data-toggle="collapse" data-target="#navbarcollapse" aria-controls="navbarcollapse" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"><span></span><span></span><span></span></button>
            </div>
            <div id="navbarcollapse" class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a href="{{URL::to('/')}}" class="nav-link ">Trang chủ</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle active" data-toggle="dropdown">Ngôn ngữ lập trình</a>
                    <div class="dropdown-menu" style="background-color: #fff;">
                        @foreach ($pLanguageView as $item)
                        <a href="{{URL::to('/programming-language')}}/{{$item->code}}" class="dropdown-item" >{{$item->name}}</a>
                        @endforeach
                    </div>
                </li>
                <li class="nav-item"><a href="{{URL::to('/code-learn')}}" class="nav-link ">Luyện tập</a>
                </li>
                </li>
                @if (Auth::check() && Auth::user()->role == 'user')
                <li class="nav-item"><a href="{{URL::to('/user-info')}}" class="nav-link "><?php echo Auth::user()->name; ?></a>
                </li>
                @else
                <li class="nav-item"><a href="{{URL::to('/login')}}" class="nav-link ">Đăng nhập</a>
                </li>
                <li class="nav-item"><a href="{{URL::to('/registration')}}" class="nav-link ">Đăng ký</a>
                </li>
                @endif
                </ul>
            </div>
        </div>
    </nav>
</header>
@endsection
@section('content')
<div class="container">
    <input type="hidden" id="user_id" name="user_id" value="<?php if(Auth::check() && Auth::user()->role == 'user') { echo Auth::id(); } ?>">
    <div class="row">
        <aside class="col-lg-4">
            <div id="resulted">
                <div class="widget tags">
                    @if (empty($user))
                        <div class="alert alert-warning">Dữ liệu bài làm của bạn đã mất!<br>Bạn hãy thực hiện làm lại bộ đề này!</div>
                    @else
                        @foreach ($subjectSet as $item)
                        <h6>Bạn đã hoàn thành: {{$item->name}} <br> Mã đề: {{$quiz}}<br> Thời gian làm bài: {{$createdAt}}</h6>
                        <input type="hidden" name="hidden_language_code" value="{{$item->language_code}}">
                        @endforeach
                        <i>Bạn đã làm đúng
                        @foreach ($userResult as $item)
                            {{$item->total_ans_correct}}
                        @endforeach
                        / {{count($correct)}} câu!</i>
                        <div style="text-align: center"><br>
                            <button class="btn btn-primary" id="btn_rework" style="font-size: 14px;">Làm lại</button>
                            <input type="hidden" name="hidden_sj_code" value="{{$quiz}}">
                        </div>
                        <hr class="sidebar-divider">
                        <?php
                            for ($i = 0; $i < count($correct); $i++) {
                                $countQues = 1 + $i;
                                if($correct[$i]['ans_correct'] == $user[$i]['user_ans']) {
                                    echo '<font color="green"><b style="color:green;">Câu '.$countQues.'</b> bạn trả lời đúng! Bạn đã chọn: <b>'.$correct[$i]['ans_correct'].'</b></font><br>';
                                } else {
                                    echo '<font color="red""><b style="color:red;">Câu '.$countQues.'</b> bạn trả lời sai! Bạn đã chọn: <b>'.$user[$i]['user_ans'].'</b></font><br>';
                                }
                            }
                        ?>
                    @endif
                </div>
            </div>
        </aside>
        <main class="post blog-post col-lg-8">
            <div class="container">
                <div class="card">
                    <div class="card-body" style="min-height: 72vh;">
                        <div class="post-single" id="result">
                            <div class="row">
                                <div class="post col-xl-6" style="padding: 0px;">
                                    <div class="card" style="border-style: none;">
                                        @if (empty($user))
                                            <div style="text-align: center"><br>
                                                @foreach ($subjectSet as $item)
                                                <h6>Bộ đề: {{$item->name}} <br> Mã đề: {{$quiz}}</h6>
                                                <input type="hidden" name="hidden_language_code" value="{{$item->language_code}}">
                                                @endforeach
                                                <button class="btn btn-primary" id="btn_rework" style="font-size: 14px;">Làm lại</button>
                                                <input type="hidden" name="hidden_sj_code" value="{{$quiz}}">
                                            </div>
                                        @else
                                            <div class="card-body">
                                                <header>
                                                    <h6>Đáp án đúng</h6>
                                                </header>
                                                <hr class="sidebar-divider">
                                                <?php $countQues = 0; ?>
                                                @foreach ($question as $item)
                                                    <?php $countQues++; echo '<b>Câu '.$countQues;?>.</b> <?php echo nl2br($item->question) ?><br>
                                                    <label class="form-check-label"> A) {{$item->ans_a}} </label><br>
                                                    <label class="form-check-label"> B) {{$item->ans_b}}</label><br>
                                                    <label class="form-check-label"> C) {{$item->ans_c}}</label><br>
                                                    <label class="form-check-label"> D) {{$item->ans_d}}</label><br>
                                                    <font color="green">Đáp án đúng:</font> <b>{{$item->ans_correct}}</b><br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="post col-xl-6" style="padding: 0px;">
                                    <div class="card" style="border-style: none;">
                                        <div class="card-body" id="rank_show">
                                            <!-- Hiển thị dữ liệu xếp hạng người dùng -->
                                            <input type="hidden" id="language" name="hidden_language" value="{{$quiz}}">
                                            {{ csrf_field() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="post-single" id="rework">
                            <div class="post-body" id="pl_quiz"><!-- Hiển thị dữ liệu câu hỏi trắc nghiệm --></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<script defer>
    $('#rework').hide();
    $(document).ready(function () {
        var id = $('input[name="hidden_language"]').val();
        var _token = $('input[name="_token"]').val();
        Rank(id, _token);
        function Rank(id, _token) {
            $.ajax({
                type: "POST",
                url: "{{route('rank')}}",
                data: {id:id, _token:_token},
                success: function (data) {
                    $('#rank_show').append(data);
                }
            });
        }
    });
</script>
<script>
    $('#btn_rework').click(function (e) {
        e.preventDefault();
        if(confirm('Bạn muốn làm lại bộ đề này? Dữ liệu kết quả bài làm hiện có sẽ bị xóa! Tùy chọn này sẽ không thể khôi phục lại dữ liệu!')) {
            var _token = $('input[name="_token"]').val();
            var sj_code = $('input[name="hidden_sj_code"]').val();
            var language_code = $('input[name="hidden_language_code"]').val();
            $.ajax({
                type: "POST",
                url: "{{route('rework')}}",
                data: {_token:_token, sj_code:sj_code, language_code:language_code},
                success: function (data) {
                    $('#result').hide();
                    $('#resulted').load(' #resulted');
                    $('#pl_quiz').html(data);
                    $('#rework').show();
                    var total = $('input[name="count_hidden"]').val();
                    $('#btn_success').hide();
                    $('input[type=radio]').change(function(){
                        $('.counter').text($(':radio:checked').length);
                        if($(':radio:checked').length == total) {
                            $('#btn_success').show();
                            $('#btn_unsuccess').hide();
                        }
                    });
                }
            });
        }
    });
</script>
@endsection
