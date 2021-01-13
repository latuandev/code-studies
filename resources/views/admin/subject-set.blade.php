@extends('admin.layout.dashboard')
@section('content')
<div class="container-fluid">
    <h1 class="mt-4"></h1>
    <div class="row">
        <!-- Chọn ngôn ngữ để hiển thị dữ liệu bộ đề -->
        <div class="col-xl-6"><h5>Chọn bộ đề trắc nghiệm theo ngôn ngữ</h5>
            <form action="{{route('select_language')}}" method="POST"> {{ csrf_field() }}
                <div class="form-group">
                    <select class="form-control" id="sel">
                        <option value="all">Tất cả</option>
                        @foreach ($language as $item)
                            <option value="{{$item->code}}">{{$item->code}}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <button type="submit" id="btn_select" class="btn btn-primary">Xem</button>
            <button type="submit" id="btn_add" class="btn btn-primary" data-toggle="modal" data-target="#add_subject_set">Tạo bộ đề</button><br><br>
        </div>
        <!-- Tìm mã bộ đề -->
        <div class="col-xl-6"><h5>Tìm kiếm bộ đề trắc nghiệm</h5>
            <form id="search_form" action="{{route('search_subject_set')}}" method="POST"> {{ csrf_field() }}
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Nhập mã bộ đề để tìm kiếm" name="search">
                </div>
            </form>
        </div>
    </div>
    <h5 class="h5_reload">Bộ đề trắc nghiệm - Tổng số: <?php echo count($data); ?></h5>
    <div class="table-responsive" id="table_data"><br>
        <!-- Bảng dữ liệu bộ đề trắc nghiệm -->
        <table class="table table-bordered" style="text-align: center">
            <thead class="thead-light">
            <tr>
                <th>STT</th>
                <th>Mã ngôn ngữ</th>
                <th>Tên bộ đề</th>
                <th>Mã bộ đề</th>
                <th>Cấp độ</th>
                <th>Chức năng</th>
            </tr>
            </thead>
            <tbody id="tr_data_all">
                <?php $count = 0; ?>
                @foreach ($data as $item)
                    <?php $count++; ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td>{{$item->language_code}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->code}}</td>
                        <td>{{$item->level}}</td>
                        <td>
                            <a href="{{URL::to("/dashboard-subject-set")}}/{{$item->code}}" class="btn btn-info">Chi tiết</a>
                            <button style="margin: 5px;" onclick="DeleteSubjectSet({{$item->id}},'{{$item->code}}')" type="button" id="btn_delete" class="btn btn-danger">Xóa</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tbody id="tr_data_ajax">
                <!-- Hiển thị dữ liệu sau khi thực thi tim kiem bang ajax -->
            </tbody>
        </table>
    </div>
</div>
<!-- Modal thêm bộ đề -->
<div class="modal fade" id="add_subject_set" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Tạo bộ đề trắc nghiệm</h5>
            </div>
            <div class="modal-body" id="add_modal">
                <form>
                    Mã ngôn ngữ lập trình
                    <select class="form-control" id="sel_code">
                        @foreach ($language as $item)
                            <option value="{{$item->code}}">{{$item->code}}</option>
                        @endforeach
                    </select>
                    Tên bộ đề
                    <input type="text" class="form-control" name="sj_name" placeholder="Ví dụ: Java cơ bản 1 - dễ">
                    Cấp độ bộ đề
                    <select class="form-control" id="sel_level">
                        <option value="Dễ">Dễ</option>
                        <option value="Khó">Khó</option>
                        <option value="Tổng hợp">Tổng hợp</option>
                    </select>
                </form><br>
                <div id="show_noti_add">
                    <!-- Hiển thị thông báo khi thêm bộ đề ở đây -->
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" id="btn_save" class="btn btn-primary">Thêm</button>
            <button type="button" id="btn_close" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<script>
    // Xem bộ đề theo ngôn ngữ
    $('#btn_select').click(function (e) {
        e.preventDefault();
        var _token = $('input[name="_token"]').val();
        var option = document.getElementById('sel');
        var code = option.value;
        $.ajax({
            type: "POST",
            url: "{{route('select_language')}}",
            data: {_token:_token, code:code},
            success: function (data) {
                $('#tr_data_all').hide();
                $('#tr_data_ajax').show();
                $('#tr_data_ajax').html(data);
            }
        });
    });
    // Tìm kiếm bộ đề theo mã
    $('#search_form').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{route('search_subject_set')}}",
            data: $('form').serialize(),
            success: function (data) {
                $('#tr_data_all').hide();
                $('#tr_data_ajax').show();
                $('#tr_data_ajax').html(data);
            }
        });
    });
    // Thêm bộ đề
    $('#btn_save').click(function (e) {
        e.preventDefault();
        var _token = $('input[name="_token"]').val();
        var option_code = document.getElementById('sel_code');
        var option_level = document.getElementById('sel_level');
        var code = option_code.value;
        var name = $('input[name="sj_name"]').val();
        var level = option_level.value;
        $.ajax({
            type: "POST",
            url: "{{route('add_subject_set')}}",
            data: {_token:_token, code:code, name:name, level:level},
            success: function (data) {
                $('#show_noti_add').html(data);
                $('#table_data').load(' #table_data');
                $(".h5_reload").load(' .h5_reload');
            }
        });
    });
    // reset modal sau khi đóng
    $(document).on('click', '#btn_close', function () {
        $('#add_modal').load(' #add_modal');
    });
    // Xóa bộ đề
    function DeleteSubjectSet(id,code) {
        var _token = $('input[name="_token"]').val();
        if(confirm('Bạn có muốn xóa bộ đề này không?')) {
            $.ajax({
                type: "POST",
                url: "{{route('delete_subject_set')}}",
                data: {_token:_token, id:id, code:code},
                success: function (data) {
                    alert('Xóa thành công!');
                    $("#table_data").load(" #table_data");
                    $(".h5_reload").load(' .h5_reload');
                }
            });
        }
    }
</script>
@endsection
