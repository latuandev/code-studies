@extends('user.layout.user-layout')
@section('header')
<header class="header">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <div class="navbar-header d-flex align-items-center justify-content-between">
                <div class="navbar-brand"><i class="fas fa-laugh-wink"></i> CODESTUDIES</div>
                <button type="button" data-toggle="collapse" data-target="#navbarcollapse" aria-controls="navbarcollapse" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"><span></span><span></span><span></span></button>
            </div>
            <div id="navbarcollapse" class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a href="{{URL::to('/')}}" class="nav-link active ">Trang chủ</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Ngôn ngữ lập trình</a>
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
        <div id="show_noti_reset_pass">
            <!-- Hiển thị thông báo gửi email thành công! -->
        </div>
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
<section class="featured-posts no-padding-top">
    <div class="container">
        <div class="row d-flex align-items-stretch">
            <div class="text col-lg-7">
                <div class="text-inner d-flex align-items-center">
                    <div class="content">
                    <header class="post-header">
                        <div class="category">Trắc nghiệm kiến thức lập trình</div>
                        <h2 class="h4">Xin chào!</h2>
                    </header>
                    <p>Bạn là một lập trình viên?<br> Hay đơn giản chỉ là một người đam mê lĩnh vực công nghệ thông tin?<br>
                    Và cụ thể hơn, bạn là một người yêu thích lập trình?</p>
                    </div>
                </div>
            </div>
            <div class="image col-lg-5"><img src="public/user/layout/img/img_pl.jpg" alt="..."></div>
        </div>
        <div class="row d-flex align-items-stretch">
            <div class="image col-lg-5"><img src="public/user/layout/img/img_pl2.png" alt="..."></div>
            <div class="text col-lg-7">
                <div class="text-inner d-flex align-items-center">
                    <div class="content">
                    <p>Bạn đã tự tin với kiến thức và hiểu biết của bản thân?<br>
                    Hoặc bạn chưa thực sự chắc chắn về điều đấy?<br>
                    Với các bộ câu hỏi trắc nghiệm của chúng tôi, bạn hoàn toàn có thể
                    kiểm tra lại kiến thức của bản thân thông qua các bài trắc nghiệm một
                    cách nhanh chóng.<br></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row d-flex align-items-stretch">
            <div class="text col-lg-7">
                <div class="text-inner d-flex align-items-center">
                    <div class="content">
                        <p>Là một trang web chứa kiến thức về các ngôn ngữ lập trình,
                        được tổng hợp từ nhiều nguồn với nhiều câu hỏi về lý thuyết lẫn
                        thuật toán.<br> Chúng tôi hứa hẹn sẽ mang lại cho bạn một trải nghiệm
                        thực sự thú vị!<br>Hãy cùng chúng tôi khám phá kho tàn kiến thức của bạn nào!<br><br>
                        <a href="{{URL::to('/programming-language/Java')}}" type="button" class="btn btn-outline-secondary" style="font-size: 14px;">Bắt đầu với ngôn ngữ Java</a>
                    </div>
                </div>
            </div>
            <div class="image col-lg-5"><img src="public/user/layout/img/img_pl3.jpg" alt="..."></div>
        </div>
    </div>
</section>
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
