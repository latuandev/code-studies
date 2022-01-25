<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Trắc nghiệm kiến thức lập trình</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <link rel="stylesheet" href={{ asset('public/user/layout/vendor/bootstrap/css/bootstrap.min.css') }}>
    <link rel="stylesheet" href={{ asset('public/user/layout/vendor/font-awesome/css/font-awesome.min.css') }}>
    <link rel="stylesheet" href={{ asset('public/user/layout/css/fontastic.css') }}>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
    <link rel="stylesheet" href={{ asset('public/user/layout/vendor/@fancyapps/fancybox/jquery.fancybox.min.css') }}>
    <link rel="stylesheet" href={{ asset('public/user/layout/css/style.default.css') }} id="theme-stylesheet">
    <link rel="stylesheet" href={{ asset('public/user/layout/css/custom.css') }}>
    <link rel="shortcut icon" href="favicon.png">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
</head>
<body>
    <noscript>
        <div class="alert alert-danger">Javascript đã bị vô hiệu hóa trên trình duyệt! Vui lòng bật lại Javascript trong phần cài đặt để tiếp tục</div>
    </noscript>
    @yield('header')
    @yield('content')
    <footer class="main-footer">
    <div class="copyrights">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>Được phát triển bởi sinh viên K18 - VKU</p>
                </div>
                <div class="col-md-6 text-right">
                    <p>&copy; 2020 | Lê Anh Tuấn
                    </p>
                </div>
            </div>
        </div>
    </div>
    </footer>
    <script src={{ asset('public/user/layout/vendor/jquery/jquery.min.js') }}></script>
    <script src={{ asset('public/user/layout/vendor/popper.js/umd/popper.min.js') }}> </script>
    <script src={{ asset('public/user/layout/vendor/bootstrap/js/bootstrap.min.js') }}></script>
    <script src={{ asset('public/user/layout/vendor/jquery.cookie/jquery.cookie.js') }}> </script>
    <script src={{ asset('public/user/layout/vendor/@fancyapps/fancybox/jquery.fancybox.min.js') }}></script>
    <script src={{ asset('public/user/layout/js/front.js') }}></script>
</body>
</html>
