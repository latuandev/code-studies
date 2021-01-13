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
                <li class="nav-item"><a href="{{URL::to('/user-info')}}" class="nav-link active"><?php echo Auth::user()->name; ?></a>
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
        <main class="post blog-post col-lg-8">
            <div class="container">
                <div class="card">
                    <div class="card-body" style="min-height: 82vh;">
                        <div class="row">
                            <div class="post col-xl-6">
                                <div class="post-body">
                                    <h6>Thông tin cá nhân</h6>
                                    <hr class="sidebar-divider">
                                    <!-- Chưa có ảnh đại diện -->
                                    @if ($user['avatar'] == null)
                                        <div id="user" style="width:100%">
                                            <div id="avatar">
                                                <a href="#"><i class="fas fa-user-circle fa-5x" class="btn btn-info btn-lg" data-toggle="modal" data-target="#upload_file"><font size="2.5px;">Đặt ảnh đại diện</font></i></a><br><br>
                                            </div>
                                            <b>{{$user['name']}}</b><br>
                                            Tuổi: {{$user['age']}}<br>
                                            Email: {{$user['email']}}<br>
                                            Số điện thoại: {{$user['phone']}}<br>
                                            Địa chỉ: {{$user['address']}}<br>
                                            Sở thích: {{$user['interests']}}<br>
                                            <input type="hidden" name="id" value="{{$user['id']}}">
                                        </div>
                                    @else
                                    <!-- Đã có ảnh đại diện -->
                                    <div id="user" style="width:100%">
                                        <div id="avatar">
                                            <a href="#"><img src="../public/user/img/{{$user['avatar']}}" data-toggle="modal" data-target="#upload_file" class="rounded-circle" width="120px" height="120px"/></a><br><br>
                                        </div>
                                        <b>{{$user['name']}}</b><br>
                                        Tuổi: {{$user['age']}}<br>
                                        Email: {{$user['email']}}<br>
                                        Số điện thoại: {{$user['phone']}}<br>
                                        Địa chỉ: {{$user['address']}}<br>
                                        Sở thích: {{$user['interests']}}<br>
                                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="post col-xl-6">
                                <div class="card" style="background: #ECEFF1;">
                                    <div class="card-body">
                                        <h6>Tùy chọn</h6>
                                        <hr class="sidebar-divider">
                                        <button class="btn btn-primary" style="margin-bottom: 5px; font-size: 14px;" data-toggle="modal" data-target="#edit_info">Chỉnh sửa thông tin</button><br>
                                        <button class="btn btn-primary" style="margin-bottom: 5px; font-size: 14px;" data-toggle="modal" data-target="#change_pass">Thay đổi mật khẩu</button><br>
                                        <a href="{{URL::to('/logout')}}" class="btn btn-primary" style="margin-bottom: 5px; font-size: 14px;">Đăng xuất</a><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <aside class="col-lg-4">
            <div style="margin-bottom: 20px; padding: 1.25rem; border: 1px solid rgba(0,0,0,.125); background: #fff; border-radius: .25rem;" id="level">
                <header>
                    <h3 class="h6">Lịch sử bài làm</h3>
                </header>
                <hr class="sidebar-divider">
                <?php $count = 0; $check_history = count($history);?>
                @if ($check_history > 0)
                    @foreach ($history as $item)
                        <a href="{{URL::to('/programming-language')}}/{{$item->language_code}}/{{$item->level}}/{{$item->subject_set_code}}/result">
                        <?php $count++; echo $count; ?>. {{$item->name}} - Mã đề: {{$item->subject_set_code}} - Cấp độ: {{$item->level}}<br>Thời gian làm: {{$item->created_at}}<br><br>
                        </a>
                    @endforeach
                @else
                    <div class="alert alert-danger">Chưa có lịch sử làm bài!</div>
                @endif
            </div>
        </aside>
    </div>
</div>
<!-- Modal thay đổi thông tin cá nhân -->
<div class="modal fade" id="edit_info" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content" style="height: 85vh; overflow: auto; margin-top: 14vh;">
            <div class="modal-header">
                <h6 class="modal-title">Thay đổi thông tin cá nhân</h6>
            </div>
            <div class="modal-body" id="edit_modal">
                <!-- Nội dung chỉnh sửa thông tin người dùng -->
            </div>
            <div class="modal-footer">
                <button type="button" id="btn_edit_info" class="btn btn-primary" data-dismiss="modal">Lưu</button>
                <button type="button" id="btn_edit_info_close" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal thay đổi ảnh đại diện -->
<div class="modal fade" id="upload_file" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content"  style="overflow: auto; margin-top: 14vh;">
            <div class="modal-header">
                <h6 class="modal-title">Đổi ảnh đại diện</h6>
            </div>
            <div class="modal-body" id="upload_modal">
                <form id="upload_form" action="{{route('user-info.upload_image')}}" method="POST" enctype="multipart/form-data"> {{ csrf_field() }}
                    <input type="file" id="image" name="image"><br>
                    <span>Chỉ chấp nhận file jpeg, jpg, png</span>
                    <div id="error">
                        <!-- Hiển thị thông báo khi upload file -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btn_submit" class="btn btn-primary" data-dismiss="modal">Tải lên</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal thay đổi mật khẩu -->
<div class="modal fade" id="change_pass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content"  style="overflow: auto; margin-top: 14vh;">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Đổi mật khẩu</h6>
            </div>
            <div class="modal-body" id="change_pass_modal">
                {{ csrf_field() }}
                Mật khẩu cũ
                <input type="password" id="old_password" class="form-control" name="old_password" value="" placeholder="Nhập mật khẩu cũ của bạn">
                Mật khẩu mới
                <input type="password" class="form-control" name="new_password" value="" placeholder="Nhập mật khẩu mới">
                Nhập lại mật khẩu mới
                <input type="password" class="form-control" name="confirm_password" value="" placeholder="Xác nhận mật khẩu mới"><br>
                <div id="show_noti">
                    <!-- Hiển thị thông báo khi xử lý đổi mật khẩu -->
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="btn_change_pass" type="button">Lưu</button>
                <button class="btn btn-secondary" id="btn_close" type="button" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<!-- Lấy dữ liệu và cập nhật thông tin người dùng -->
<script>
    $(document).ready(function () {
        var _token = $('input[name="_token"]').val();
        // Hiển thị thông tin người dùng vào Modal thay đổi thông tin cá nhân
        EditData(_token);
        function EditData() {
            $.ajax({
                type: "POST",
                url: "{{route('user-info.edit_data')}}",
                data: {_token:_token},
                success: function (data) {
                    $('#edit_modal').html(data);
                }
            });
        }
        // Cập nhật thông tin người dùng
        $(document).on('click', '#btn_edit_info', function () {
            var uploadFile = $("input[name='up_file']").val();
            var name = $("input[name='change_name']").val();
            var option = document.getElementById('change_age');
            var age = option.value;
            var email = $("input[name='change_email']").val();
            var phone = $("input[name='change_phone']").val();
            var address = $("input[name='change_address']").val();
            var interests = $('#change_interests').val();
            $.ajax({
                type: "POST",
                url: "{{route('user-info.update_data')}}",
                data: {name:name, age:age, email:email, phone:phone, address:address, interests:interests, _token:_token},
                success: function (data) {
                    $('#user').load(' #user');
                    alert('Cập nhật thông tin thành công!');
                    EditData(_token);
                }
            });
        });
        // Tải ảnh đại diện của người dùng
        $('#btn_submit').click(function (e) {
            e.preventDefault();
            if(this.id == 'btn_submit') {
                $(document.body).append('<div id="hackergrousrlpprsc-loader">Đang tải</div>');
                $(window).one("click", function() {
                $('#hackergrousrlpprsc-loader').fadeIn(300).delay(1000).fadeOut(300); });
            }
            var formData = new FormData($("#upload_form")[0]);
            $.ajax({
                type: "POST",
                url: "{{route('user-info.upload_image')}}",
                data: formData,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if(data.error) {
                        alert(data.error);
                    } else {
                        $('#avatar').html(data.avatar);
                        $('#avatar').load(' #avatar');
                        $('#upload_modal').load(' #upload_modal');
                    }
                }
            });
        });
        // Thay đổi mật khẩu người dùng
        $(document).on('click', '#btn_change_pass', function () {
            var _token = $('input[name="_token"]').val();
            var oldPassword = $('input[name="old_password"]').val();
            var newPassword = $('input[name="new_password"]').val();
            var confirmPassword = $('input[name="confirm_password"]').val();
            $.ajax({
                type: "POST",
                url: "{{route('change_password')}}",
                data: {_token:_token, oldPassword:oldPassword, newPassword:newPassword, confirmPassword:confirmPassword},
                dataType: 'json',
                success: function (data) {
                    if(data.null) {
                        $('#show_noti').html(data.null);
                    } else if(data.error) {
                        $('#show_noti').html(data.error);
                    } else if(data.success) {
                        $('#show_noti').html(data.success);
                    }
                }
            });
        });
        $(document).on('click', '#btn_close', function () {
            $('#change_pass_modal').load(' #change_pass_modal');
        });
    });
</script>
@endsection
