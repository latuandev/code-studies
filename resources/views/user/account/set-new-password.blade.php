<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Đăng nhập Trắc nghiệm kiến thức lập trình</title>
        <link href={{ asset('admin/dist/css/styles.css') }} rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5" style="margin-top: 50px;">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Đặt lại mật khẩu mới</h3></div>
                                    <div class="card-body" id="card_login">
                                        <?php
                                            $email = $_GET['email'];
                                            $token = $_GET['token'];
                                        ?>
                                        <form action="{{route('set_new_pass')}}" method="POST" class="form login"> {{ csrf_field() }}
                                            <input type="hidden" name="email" value="{{$email}}">
                                            <input type="hidden" name="token" value="{{$token}}">
                                            <div class="form-group">
                                                <input type="password" class="form-control form-control-user"
                                                    id="exampleInputEmail" aria-describedby="emailHelp"
                                                    placeholder="Nhập mật khẩu mới" name="new_password">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control form-control-user"
                                                    id="exampleInputPassword" placeholder="Xác nhận mật khẩu" name="confirm_new_password">
                                            </div>
                                            <button type="submit" id="submit" class="btn btn-primary btn-user btn-block">
                                                Đặt lại mật khẩu
                                            </button><br>
                                            <div id="show_noti">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    <script>
        $(document).ready(function () {
            $('#submit').click(function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "{{route('set_new_pass')}}",
                    data: $('form').serialize(),
                    dataType: "json",
                    success: function (data) {
                        if(data.error) {
                            $('#show_noti').html(data.error);
                        } else if(data.success) {
                            var set_new_pass_success = $('input[name="set_new_pass_success"]').val();
                            if(set_new_pass_success != '') {
                                alert('Đổi mật khẩu mới thành công!');
                                window.location.href = "{{route('home')}}";
                            }
                        }
                    }
                });
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src={{ asset('admin/dist/js/scripts.js') }}></script>
</body>
</html>
