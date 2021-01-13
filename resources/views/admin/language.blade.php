@extends('admin.layout.dashboard')
@section('content')
<div class="container-fluid">
    <h1 class="mt-4"></h1><h5 class="h5_reload">Ngôn ngữ lập trình - Tổng số: <?php echo count($data); ?></h5>
    <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#addModal">Thêm ngôn ngữ</button><br><br>
    <div class="table-responsive" id="table_data">
        <!-- Bảng dữ liệu ngôn ngữ lập trình -->
        <table class="table  table-bordered" style="text-align: center">
            <thead class="thead-light">
            <tr>
                <th>STT</th>
                <th>Tên ngôn ngữ</th>
                <th>Mã ngôn ngữ</th>
                <th>Chức năng</th>
            </tr>
            </thead>
            <tbody id="tr_data">
                <?php $count = 0; ?>
                @foreach ($data as $item)
                    <?php $count++; ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->code}}</td>
                        <td>
                            <button style="margin: 5px;" onclick="EditModal({{$item->id}})" type="button" id="btn_edit" class="btn btn-primary" data-toggle="modal" data-target="#editModal">Cập nhật</button>
                            <button onclick="DeleteLanguage({{$item->id}})" type="button" id="btn_delete" class="btn btn-danger">Xóa</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Modal sửa thông tin ngôn ngữ -->
<div class="modal fade" id="editModal" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" style="height: 85vh; overflow: auto;">
            <div class="modal-header">
            <h5 class="modal-title">Cập nhật thông tin</h5>
            </div>
            <div class="modal-body" id="editModalRefresh">
                <!-- Hiển thị dữ liệu modal -->
                <form id="edit_modal" action="{{route('update_language')}}" method="POST">
                    {{ csrf_field() }}
                </form><br>
                <div id="show_noti">
                    <!-- Hiển thị thông báo khi sửa ngôn ngữ ở đây -->
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" id="btn_update" class="btn btn-primary">Lưu</button>
            <button type="button" id="btn_update_close" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal thêm ngôn ngữ -->
<div class="modal fade" id="addModal" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <!-- Modal content -->
        <div class="modal-content" style="height: 85vh; overflow: auto;">
            <div class="modal-header">
            <h5 class="modal-title">Thêm ngôn ngữ lập trình</h5>
            </div>
            <div class="modal-body" id="add_modal">
                <!-- Hiển thị dữ liệu modal -->
                <form action="{{route('add_language')}}" method="POST"> {{ csrf_field() }}
                    <b>Tên ngôn ngữ lập trình</b>
                    <input type="text" class="form-control" name="add_name" placeholder="Ví dụ: Ngôn ngữ Java">
                    <b>Mã ngôn ngữ lập trình</b>
                    <input type="text" class="form-control" name="add_code" placeholder="Ví dụ: Java">
                    <b>Mô tả ngôn ngữ lập trình</b>
                    <textarea style="width: 100%; height: 200px; resize: none" name="add_des"></textarea><br>
                    <b>Liên kết</b>
                    <input type="text" class="form-control" name="add_url" placeholder="Nhập liên kết xem thông tin về ngôn ngữ">
                </form><br>
                <div id="show_noti_add">
                    <!-- Hiển thị thông báo khi thêm ngôn ngữ ở đây -->
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
                url: "{{route('add_language')}}",
                data: $('form').serialize(),
                dataType: 'JSON',
                success: function (data) {
                    if(data.null) {
                        $('#show_noti_add').html(data.null);
                    } else if(data.error) {
                        $('#show_noti_add').html(data.error);
                    } else if(data.success) {
                        $('#show_noti_add').html(data.success);
                        $("#table_data").load(" #table_data");
                        $(".h5_reload").load(' .h5_reload');
                    }
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
    // Hiển thị modal sửa ngôn ngữ lập trình
    function EditModal(id) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            type: "POST",
            url: "{{route('edit_modal_language')}}",
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
    // Cập nhật NNLT
    $('#btn_update').click(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{route('update_language')}}",
            data: $('form').serialize(),
            dataType: 'JSON',
            success: function (data) {
                if(data.success) {
                    $('#show_noti').html(data.success);
                    $("#table_data").load(" #table_data");
                }
            }
        });
    });
    // reset modal sau khi đóng
    $(document).on('click', '#btn_update_close', function () {
        $('#editModalRefresh').load(' #editModalRefresh');
    });
</script>
<script>
    // Xóa NNLT
    function DeleteLanguage(id) {
        var _token = $('input[name="_token"]').val();
        if(confirm('Bạn có muốn xóa ngôn ngữ này không?')) {
            $.ajax({
                type: "POST",
                url: "{{route('delete_language')}}",
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
