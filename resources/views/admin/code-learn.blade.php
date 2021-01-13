@extends('admin.layout.dashboard')
@section('content')
<div class="container-fluid">
    <h1 class="mt-4"></h1>
    <div class="row">
        <div class="col-xl-6">
            <h5 class="h5_reload">Bài tập luyện code - Tổng số: <?php echo count($data); ?></h5>
            <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#addModal">Thêm bài tập</button>
        </div>
        <div class="col-xl-6"><h5>Tìm kiếm bài tập</h5>
            <form id="search_form" action="{{route('search_exercise')}}" method="POST"> {{ csrf_field() }}
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Nhập mã bài tập để tìm kiếm" name="search">
                </div>
            </form>
        </div>
    </div>
    <div class="table-responsive" id="table_data">
        <table class="table  table-bordered" style="text-align: center">
            <thead class="thead-light">
            <tr>
                <th>STT</th>
                <th>Mã</th>
                <th>Đề bài tập</th>
                <th>Chức năng</th>
            </tr>
            </thead>
            <tbody id="tr_data">
                <?php $count = 0; ?>
                @foreach ($data as $item)
                    <?php $count++; ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td>{{$item->code}}</td>
                        <td>{{$item->question}}</td>
                        <td>
                            <button style="margin: 5px;" onclick="EditModal({{$item->id}})" type="button" id="btn_edit" class="btn btn-primary" data-toggle="modal" data-target="#editModal">Chi tiết</button>
                            <button onclick="DeleteExercise({{$item->id}})" type="button" id="btn_delete" class="btn btn-danger">Xóa</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tbody id="tr_data_ajax">
                <!-- Hiển thị dữ liệu sau khi thực thi tìm kiếm bằng ajax -->
            </tbody>
        </table>
    </div>
</div>
<!-- Modal chi tiết bài tập -->
<div class="modal fade" id="editModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" style="height: 85vh; overflow: auto;">
            <div class="modal-header">
            <h5 class="modal-title">Bài tập</h5>
            </div>
            <div class="modal-body" id="editModalRefresh">
                <!-- Hiển thị dữ liệu modal -->
                <form id="edit_modal" action="{{route('update_exercise')}}" method="POST">
                    {{ csrf_field() }}
                </form><br>
                <div id="show_noti">
                    <!-- Hiển thị thông báo khi có thay đổi dữ liệu bài tập -->
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" id="btn_update" class="btn btn-primary">Cập nhật</button>
            <button type="button" id="btn_update_close" class="btn btn-secondary" data-dismiss="modal">Thoát</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal thêm bài tập -->
<div class="modal fade" id="addModal" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content" style="height: 85vh; overflow: auto;">
            <div class="modal-header">
            <h5 class="modal-title">Thêm bài tập</h5>
            </div>
            <div class="modal-body" id="add_modal">
                <!-- Hiển thị dữ liệu modal -->
                <form action="{{route('add_exercise')}}" method="POST"> {{ csrf_field() }}
                    <b>Đề bài</b>
                    <textarea style="width: 100%; height: 100px; resize: none;" name="add_question"></textarea><br>
                    <b>Yêu cầu</b>
                    <textarea style="width: 100%; height: 200px; resize: none;" name="add_request"></textarea><br>
                    <b>Code mẫu</b>
                    <textarea style="width: 100%; height: 200px; resize: none;" name="add_example"></textarea><br>
                </form><br>
                <div id="show_noti_add">
                    <!-- Hiển thị thông báo khi thêm bài tập ở đây -->
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" id="btn_add" class="btn btn-primary">Thêm</button>
            <button type="button" id="btn_close" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<script>
    // Thêm ngôn ngữ lập trình
    $(document).ready(function () {
        $('#btn_add').click(function (e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{route('add_exercise')}}",
                data: $('form').serialize(),
                dataType: 'JSON',
                success: function (data) {
                    if(data.null) {
                        $('#show_noti_add').html(data.null);
                    } else if(data.success) {
                        $('#show_noti_add').html(data.success);
                        $("#table_data").load(" #table_data");
                        $(".h5_reload").load(' .h5_reload');
                    }
                },
                error: function () {
                    $('#show_noti_add').html('<div class="alert alert-danger">Thêm bài tập thất bại!</div>');
                }
            });
        });
        // Reset modal khi đóng
        $(document).on('click', '#btn_close', function () {
            $('#add_modal').load(' #add_modal');
        });
    });
</script>
<script>
    // Tìm kiếm bài tập theo mã
    $('#search_form').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{route('search_exercise')}}",
            data: $('form').serialize(),
            success: function (data) {
                $('#tr_data').hide();
                $('#tr_data_ajax').show();
                $('#tr_data_ajax').html(data);
            }
        });
    });
</script>
<script>
    // Hiển thị modal chi tiết bài tập
    function EditModal(id) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            type: "POST",
            url: "{{route('update_code_learn')}}",
            data: {_token:_token, id:id},
            dataType: 'json',
            success: function (data) {
                if(data.error) {
                    $('#edit_modal').html(data.error);
                } else {
                    $('#edit_modal').html(data.success);
                }
            }
        });
    }
</script>
<script>
    // Cập nhật bài tập
    $('#btn_update').click(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{route('update_exercise')}}",
            data: $('form').serialize(),
            dataType: 'JSON',
            success: function (data) {
                if(data.success) {
                    $('#show_noti').html(data.success);
                    $("#table_data").load(" #table_data");
                }
            },
            error: function() {
                $('#show_noti').html('<div class="alert alert-danger">Cập nhật bài tập thất bại!</div>');
            }
        });
    });
    // reset modal sau khi đóng
    $(document).on('click', '#btn_update_close', function () {
        $('#editModalRefresh').load(' #editModalRefresh');
    });
</script>
<script>
    // Xóa bài tập
    function DeleteExercise(id) {
        var _token = $('input[name="_token"]').val();
        if(confirm('Bạn có muốn xóa bài tập này không?')) {
            $.ajax({
                type: "POST",
                url: "{{route('delete_exercise')}}",
                data: {_token:_token, id:id},
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
