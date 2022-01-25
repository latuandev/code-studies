@extends('admin.layout.dashboard')
@section('content')
<div class="container-fluid">
    <h1 class="mt-4"></h1><h5>Xin chào Admin!</h5>
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area mr-1"></i>
                    Hướng dẫn tạo bộ đề và tải lên file câu hỏi
                </div>
                <div class="card-body">
                    <a href="{{ asset('public/admin/manual.jpg') }}"><img src="{{ asset('public/admin/manual.jpg') }}" style="width: 100%; height: 100%;"/></a>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar mr-1"></i>
                    File mẫu - định dạng câu hỏi
                </div>
                <div class="card-body">
                    <a href="{{ asset('public/admin/file_mau_cau_hoi.png') }}"><img src="{{ asset('public/admin/file_mau_cau_hoi.png') }}" style="width: 100%; height: 100%;"/></a><br><br>
                    <a href="{{URL::to('/dashboard/download/file_mau_cau_hoi')}}"><button class="btn btn-primary">Tải file mẫu - định dạng câu hỏi</button></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
