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
                <li class="nav-item"><a href="#" class="nav-link " data-toggle="modal" data-target="#loginModal">Đăng nhập</a>
                </li>
                <li class="nav-item"><a href="#" class="nav-link " data-toggle="modal" data-target="#regModal">Đăng ký</a>
                </li>
                @endif
                </ul>
            </div>
        </div>
    </nav>
</header>
<div class="modal" id="loginModal" style="margin-top: 10vh;" data-backdrop="static">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h6 class="modal-title">Đăng nhập</h6>
        <button type="button" id="btn_close" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body" id="card_login">
            <form id="form_login" action="{{route('login.post')}}" method="POST"> {{ csrf_field() }}
                <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" placeholder="Nhập địa chỉ email" name="email">
                </div>
                <div class="form-group">
                <label for="pwd">Mật khẩu:</label>
                <input type="password" class="form-control" id="pwd" placeholder="Nhập mật khẩu" name="password">
                </div>
                <div class="form-group">
                <label>
                    <a href="#" id="btn_rcv">Quên mật khẩu?</a>
                </label>
                </div>
                <button id="submit" type="submit" class="btn btn-primary btn-user btn-block">Đăng nhập</button>
                <div id="show_noti">
                    <!-- Hiển thị thông báo nếu người dùng đăng nhập bị lỗi -->
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
<div class="modal" id="rcvModal" style="margin-top: 10vh;" data-backdrop="static">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h6 class="modal-title">Quên mật khẩu</h6>
        <button type="button" id="btn_rcv_close" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body" id="card_recovery">
            <form action="{{route('recovery.pass')}}" method="POST" class="form login"> {{ csrf_field() }}
                <div class="form-group">
                    Nhập email đặt lại mật khẩu
                    <input type="email" class="form-control form-control-user"
                        id="exampleInputPassword" placeholder="Nhập email của tài khoản để đặt lại mật khẩu" name="recovery_email">
                </div>
                <button type="submit" id="submit_recovery" class="btn btn-primary btn-user btn-block">
                    Nhận liên kết
                </button><br>
                <div id="show_noti_rcv">
                    <!-- Hiển thị thông báo nếu người dùng nhập email sai -->
                </div>
            </form>
        </div>
        <br><div id="show_noti_reset_pass" style="padding-left: 10px; padding-right: 10px;">
            <!-- Hiển thị thông báo gửi email thành công! -->
        </div><br>
    </div>
    </div>
</div>
<div class="modal" id="regModal" style="margin-top: 10vh;" data-backdrop="static">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h6 class="modal-title">Đăng ký tài khoản</h6>
        <button type="button" id="btn_reg_close" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body" id="card_reg">
            <form action="{{route('registration.post')}}" method="POST"> {{ csrf_field() }}
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">Tên của bạn
                        <input type="text" class="form-control form-control-user" id="exampleFirstName"
                            placeholder="Nhập tên của bạn" name="reg_name">
                    </div>
                    <div class="col-sm-6">Năm sinh
                        <select class="form-control" id="age">
                            <?php $data = getdate(); $y = $data['year']; ?>
                            @for ($i = $y; $i >= 1920; $i--)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="form-group">Email
                    <input type="email" class="form-control form-control-user" id="exampleInputEmail"
                        placeholder="Nhập email của bạn" name="reg_email">
                </div>
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">Mật khẩu
                        <input type="password" class="form-control form-control-user"
                            id="exampleInputPassword" placeholder="Nhập mật khẩu của bạn" name="reg_password">
                    </div>
                    <div class="col-sm-6">Xác nhận mật khẩu
                        <input type="password" class="form-control form-control-user"
                            id="exampleRepeatPassword" placeholder="Nhập lại mật khẩu của bạn" name="reg_confirm_password">
                    </div>
                </div>
                <button type="submit" id="reg_submit" class="btn btn-primary btn-user btn-block">
                    Đăng ký
                </button>
            </form><br>
            <div id="show_noti_reg">
                <!-- Hiển thị thông báo nếu có lỗi khi đăng ký tài khoản -->
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
@section('content')
<div class="container">
    <input type="hidden" id="user_id" name="user_id" value="<?php if(Auth::check() && Auth::user()->role == 'user') { echo Auth::id(); } ?>">
    <div class="row">
        <aside class="col-lg-4">
            <div class="widget tags" id="level">
                <header>
                    <h3 class="h6">Cấp độ bài trắc nghiệm</h3>
                </header>
                <hr class="sidebar-divider">
                <ul class="list-inline">
                    <li class="list-inline-item"><button id="btn_click" type="button" onclick="GetLevel('Dễ')" class="btn btn-info" style="margin-bottom: 5px; font-size: 14px;">Cấp độ dễ</button></li>
                    <li class="list-inline-item"><button id="btn_click" type="button" onclick="GetLevel('Khó')" class="btn btn-warning" style="margin-bottom: 5px; font-size: 14px;">Cấp độ khó</button></li>
                    <li class="list-inline-item"><button id="btn_click" type="button" onclick="GetLevel('Tổng hợp')" class="btn btn-danger" style="margin-bottom: 5px; font-size: 14px;">Cấp độ tổng hợp</button></li>
                </ul>
                <input type="hidden" name="hidden_code" value="{{$code}}"><br>
                {{ csrf_field() }}
                <div id="subject_set"><!-- Hiển thị bộ đề trắc nghiệm --></div>
            </div>
        </aside>
        <main class="post blog-post col-lg-8">
            <div class="container">
                <div class="card">
                    <div class="card-body" style="min-height: 70vh;">
                        <div class="post-single">
                            <div class="post-body" id="pl_info">
                                <h6>Ngôn ngữ {{$code}}</h6>
                                <hr class="sidebar-divider">
                                @foreach ($language as $item)
                                    <?php
                                        $des = nl2br($item->des);
                                        $url = $item->url;
                                    ?>
                                @endforeach
                                @if ($des != '')
                                    <?php
                                        echo $des;
                                    ?><br><br>
                                    <blockquote class="blockquote">
                                        <a href="{{$item->url}}" target="blank">
                                        <footer class="blockquote-footer">Xem thêm thông tin tại:
                                            <cite title="Source Title">đây</cite>
                                        </footer>
                                        </a>
                                    </blockquote>
                                @else
                                    <div class="alert alert-danger">Chưa tìm thấy dữ liệu thông tin về ngôn ngữ này!</div>
                                @endif
                            </div>
                            <div class="post-body" id="pl_quiz"><!-- Hiển thị câu hỏi --></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<script>
    $(document).ready(function () {
        var user_id = $('input[name="user_id"]').val();
        if(user_id == '') {
            // alert('Trước tiên bạn phải đăng nhập!');
            // window.location.href = "{{route('login')}}";
        }
    });
</script>
<script>
    $('#subject_set').hide();
    $('#pl_quiz').hide();
    function GetLevel(level) {
        var _token = $('input[name="_token"]').val();
        var code = $('input[name="hidden_code"]').val();
        $.ajax({
            type: "POST",
            url: "{{route('get_subject_set')}}",
            data: {_token:_token, level:level, code:code},
            success: function (data) {
                $('#subject_set').html(data);
                $('#subject_set').show();
            },
            error: function () {
                $('#subject_set').html('<div class="alert alert-danger">Đã xảy ra lỗi khi lấy dữ liệu bộ đề trắc nghiệm!</div>');
                $('#subject_set').show();
            }
        });
    }
</script>
<script>
    function GetQuestion(sjCode) {
        var _token = $('input[name="_token"]').val();
        var code = $('input[name="hidden_code"]').val();
        $.ajax({
            type: "POST",
            url: "{{route('get_question')}}",
            data: {_token:_token, code:code, sjCode:sjCode},
            success: function (data) {
                $('#pl_info').hide();
                $('#pl_quiz').html(data);
                $('#pl_quiz').show();
                var total = $('input[name="count_hidden"]').val();
                $('#btn_success').hide();
                $('input[type=radio]').change(function(){
                    $('.counter').text($(':radio:checked').length);
                    if($(':radio:checked').length == total) {
                        $('#btn_success').show();
                        $('#btn_unsuccess').hide();
                    }
                });
                var check_sj = $('input[name="hidden_check_sj"]').val();
                var language_code = $('input[name="hidden_language_code"]').val();
                var level = $('input[name="hidden_sj_level"]').val();
                if(check_sj == null) {
                } else {
                    alert('Bạn đã hoàn thành bộ đề này! Kết quả sẽ được hiển thị cho bạn!');
                    window.location.href = "{{URL::to('/programming-language')}}/" + language_code + "/" + level + "/" + check_sj + "/result";
                }
            },
            error: function () {
                $('#pl_info').hide();
                $('#pl_quiz').html('<div class="alert alert-danger">Đã xảy ra lỗi khi lấy dữ liệu câu hỏi trắc nghiệm!</div>');
                $('#pl_quiz').show();
            }
        });
    }
</script>
<!-- Xử lý đăng nhập tài khoản -->
<script>
    $(document).ready(function () {
        $('#submit').click(function (e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{route('login.post')}}",
                data: $('form').serialize(),
                dataType: 'json',
                success: function (data) {
                    if(data.null) {
                        $('#show_noti').html(data.null);
                    } else if(data.error) {
                        $('#show_noti').html(data.error);
                    } else if(data.success) {
                        $('#show_noti').html(data.success);
                        var user_role = $('input[name="hidden_role"]').val();
                        if(user_role == 'user') {
                            window.location.reload();
                        } else if(user_role == 'admin') {
                            window.location.href = "{{route('dashboard')}}";
                        }
                    }
                }
            });
        });
        $('#btn_close').click(function (e) {
            $('#card_login').find('form').trigger('reset');
            $('#show_noti').load(' #show_noti');
        });
        $('#btn_rcv').click(function (e) {
            e.preventDefault();
            $('#loginModal').modal('hide');
            $('#rcvModal').modal('show');
        });
    });
</script>
<!-- Xử lý quên mật khẩu -->
<script>
    $('#submit_recovery').click(function (e) {
        e.preventDefault();
        var email = $('input[name="recovery_email"]').val();
        var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!filter.test(email)) {
            $('#show_noti_rcv').html('<div class="alert alert-danger">Vui lòng nhập địa chỉ email hợp lệ!</div>');
            email.focus;
            return false;
        } else {
            if(this.id == 'submit_recovery') {
                $(document.body).append('<div id="hackergrousrlpprsc-loader">Đang tải</div>');
                $(window).one("click", function() {
                $('#hackergrousrlpprsc-loader').fadeIn(500).delay(5000).fadeOut(500); });
            }
            $.ajax({
                type: "POST",
                url: "{{route('recovery.pass')}}",
                data: $('form').serialize(),
                dataType: 'json',
                success: function (data) {
                    if(data.error) {
                        $('#show_noti_rcv').html(data.error);
                    } else if(data.success) {
                        $('#card_recovery').hide();
                        $('#show_noti_reset_pass').html(data.success);
                    }
                },
                error: function (error) {
                    $('#show_noti_reset_pass').html('<div class="alert alert-danger">Gửi email thất bại! Vui lòng thử lại sau!</div>');
                }
            });
        }
    });
    $('#btn_rcv_close').click(function (e) {
        e.preventDefault();
        $('#card_recovery').show();
        $('#card_login').find('form').trigger('reset');
        $('#show_noti').load(' #show_noti');
        $('#card_recovery').find('form').trigger('reset');
        $('#show_noti_rcv').load(' #show_noti_rcv');
        $('#show_noti_reset_pass').load(' #show_noti_reset_pass');
    });
</script>
<!-- Xử lý đăng ký tài khoản -->
<script>
    $(document).ready(function () {
        $('#reg_submit').click(function (e) {
            e.preventDefault();
            var _token = $('input[name="_token"]').val();
            var name = $('input[name="reg_name"]').val();
            var option = document.getElementById('age');
            var age = option.value;
            var email = $('input[name="reg_email"]').val();
            var password = $('input[name="reg_password"]').val();
            var confirm_password = $('input[name="reg_confirm_password"]').val();
            $.ajax({
                type: "POST",
                url: "{{route('registration.post')}}",
                data: {_token:_token, name:name, age:age, email:email, password:password, confirm_password:confirm_password},
                dataType: 'json',
                success: function (data) {
                    if(data.null) {
                        $('#show_noti_reg').html(data.null);
                    } else if(data.error) {
                        $('#show_noti_reg').html(data.error);
                    } else if(data.success) {
                        $('#show_noti_reg').html(data.success);
                    }
                }
            });
        });
        $('#btn_reg_close').click(function (e) {
            e.preventDefault();
            $('#card_reg').find('form').trigger('reset');
            $('#show_noti_reg').load(' #show_noti_reg');
        });
    });
</script>
@endsection
