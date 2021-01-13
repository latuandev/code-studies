@extends('admin.layout.dashboard')
@section('content')
<div class="container-fluid">
    <h1 class="mt-4"></h1>
    <div class="row">
        <div class="col-xl-6"><h5 class="h5_reload">Người dùng - Tổng số: <?php echo count($user); ?></h5></div>
        <!-- Tìm mã bộ đề -->
        <div class="col-xl-6">
            <form id="search_form" action="{{route('search_user')}}" method="POST"> {{ csrf_field() }}
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Tìm kiếm người dùng theo tên hoặc email" name="search">
                </div>
            </form>
        </div>
    </div>
    <div class="table-responsive" id="table_data">
        <!-- Bảng dữ liệu người dùng -->
        <table class="table  table-bordered" style="text-align: center">
            <thead class="thead-light">
            <tr>
                <th>STT</th>
                <th>Người dùng</th>
                <th>Tuổi</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Địa chỉ</th>
                <th>Chức năng</th>
            </tr>
            </thead>
            <tbody id="tr_data_all">
                <?php $count = 0; ?>
                @foreach ($user as $item)
                    <?php $count++; ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td>
                            @if ($item->avatar == null)
                                <i class="fas fa-user-circle fa-4x"></i><br>
                                {{$item->name}}
                            @else
                                <img src="user/img/{{$item->avatar}}" class="rounded-circle" style="width: 70px; height: 70px"><br>
                                {{$item->name}}
                            @endif
                        </td>
                        <td>{{$item->age}}</td>
                        <td>{{$item->email}}</td>
                        <td>{{$item->phone}}</td>
                        <td>{{$item->address}}</td>
                        <td>
                            <button style="margin: 5px;" onclick="HistoryModal({{$item->id}})" type="button" id="btn_history" class="btn btn-info" data-toggle="modal" data-target="#historyModal">Lịch sử</button>
                            <button onclick="DeleteUser({{$item->id}})" type="button" id="btn_delete" class="btn btn-danger">Xóa</button>
                        </td> {{ csrf_field() }}
                    </tr>
                @endforeach
            </tbody>
            <tbody id="tr_data_ajax">
                <!-- Dữ liệu người dùng sau khi tìm kiếm sẽ ở đây -->
            </tbody>
        </table>
    </div>
</div>
<!-- Modal lịch sử người dùng -->
<div class="modal fade" id="historyModal" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content" style="height: 85vh; overflow: auto;">
            <div class="modal-header">
            <h5 class="modal-title">Lịch sử người dùng</h5>
            </div>
            <div class="modal-body" id="history_modal" style="height: 300px; overflow: auto;">
                <!-- Hiển thị lịch sử người dùng ở đây -->
            </div>
            <div class="modal-footer">
            <button type="button" id="btn_history_close" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<script>
    // Xóa người dùng
    function DeleteUser(id) {
        var _token = $('input[name="_token"]').val();
        if(confirm('Bạn có muốn xóa người dùng này không?')) {
            $.ajax({
                type: "POST",
                url: "{{route('delete_user')}}",
                data: {_token:_token, id:id},
                success: function (data) {
                    alert('Xóa thành công!');
                    $("#table_data").load(" #table_data");
                    $(".h5_reload").load(' .h5_reload');
                }
            });
        }
    }
    // Lịch sử người dùng
    function HistoryModal(id) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            type: "POST",
            url: "{{route('history_modal_user')}}",
            data: {_token:_token, id:id},
            success: function (data) {
                $('#history_modal').html(data);
            }
        });
    }
    // Tìm kiếm người dùng
    $('#search_form').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{route('search_user')}}",
            data: $('form').serialize(),
            success: function (data) {
                $('#tr_data_all').hide();
                $('#tr_data_ajax').show();
                $('#tr_data_ajax').html(data);
            }
        });
    });
</script>
@endsection
