@extends('admin.layout.dashboard')
@section('content')
<div class="container-fluid"><br>
    <h5>Thông tin bộ đề</h5>
    <div class="table-responsive" id="table_subject_set"><br>
        <!-- Bảng dữ liệu bộ đề trắc nghiệm -->
        <table class="table table-bordered" style="text-align: center">
            <thead class="thead-light">
            <tr>
                <th>Mã ngôn ngữ</th>
                <th>Tên bộ đề</th>
                <th>Mã bộ đề</th>
                <th>Cấp độ</th>
                <th>Chức năng</th>
            </tr>
            </thead>
            <tbody id="tr_data_all">
                <?php $count = 0; ?>
                @foreach ($subjectSet as $item)
                        <td>{{$item->language_code}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->code}}</td>
                        <td>{{$item->level}}</td>
                        <td>
                            <input type="hidden" name="hidden_sj_id" value="{{$item->code}}">
                            <button style="margin: 5px;" name="edit_modal" type="button" id="btn_edit" class="btn btn-primary" data-toggle="modal" data-target="#edit_subject_set" onclick="EditModal({{$item->id}})">Cập nhật</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <h5>Dữ liệu câu hỏi</h5>
    <div id="div_nodata">
        <button style="margin: 5px;" type="button" id="btn_upload" class="btn btn-primary" data-toggle="modal" data-target="#upload_question">Tải lên file câu hỏi</button>
    </div>
    <div id="div_hasdata">
        <button style="margin: 5px;" type="button" id="btn_upload" class="btn btn-primary" data-toggle="modal" data-target="#upload_question">Cập nhật file câu hỏi</button>
        @foreach ($subjectSet as $item)
            <a href="{{URL::to('/dashboard-subject-set/download_file')}}/{{$item->code}}" style="margin: 5px;" id="btn_download" class="btn btn-primary">Tải xuống file câu hỏi</a>
        @endforeach
    </div>
    <div class="table-responsive" id="table_data"><br>
        <!-- Bảng dữ liệu câu hỏi trắc nghiệm -->
        <table class="table table-bordered" style="text-align: center">
            <thead class="thead-light">
            <tr>
                <th>STT</th>
                <th>Câu hỏi</th>
                <th>Đáp án A</th>
                <th>Đáp án B</th>
                <th>Đáp án C</th>
                <th>Đáp án D</th>
                <th>Đáp án đúng</th>
            </tr>
            </thead>
            <tbody id="tr_data_all">
                <?php $count = 0; ?>
                @foreach ($data as $item)
                    <?php $count++; ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <input type="hidden" name="hidden_question_code" value="{{$item->subject_set_code}}">
                        <td>{{$item->question}}</td>
                        <td>{{$item->ans_a}}</td>
                        <td>{{$item->ans_b}}</td>
                        <td>{{$item->ans_c}}</td>
                        <td>{{$item->ans_d}}</td>
                        <td>{{$item->ans_correct}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Modal sửa thông tin bộ đề -->
<div class="modal fade" id="edit_subject_set" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Cập nhật thông tin</h5>
            </div>
            <div class="modal-body" id="edit_modal_form">
                {{ csrf_field() }}
                <!-- Hiển thị dữ liệu modal -->
                <form id="edit_modal" action="{{route('update_subject_set')}}" method="POST">
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
<!-- Modal upload file excel câu hỏi -->
<div class="modal fade" id="upload_question" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Tải lên file câu hỏi</h5>
            </div>
            <div class="modal-body" id="upload_modal_form">
                <form id="upload_form" action="{{route('upload_file')}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        @foreach ($subjectSet as $item)
                        <input type="hidden" name="hidden_code" value="{{$item->code}}">
                        @endforeach
                        <label for="">Chọn file Excel</label>
                        <input type="file" class="form-control-file" name="select_file" placeholder="" aria-describedby="fileHelpId">
                        <small id="fileHelpId" class="form-text text-muted">Chỉ chấp nhận file xls, xlsx</small>
                    </div>
                </form>
                <div id="show_noti_upload_file">
                    <!-- Hiển thị thông báo khi upload file excel ở đây -->
                </div>
            </div>
            <div class="modal-footer">
            <button type="submit" id="btn_upload_file" class="btn btn-primary">Lưu</button>
            <button type="button" id="btn_upload_close" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<script>
    var questionCode = $('input[name="hidden_question_code"]').val();
    if(questionCode == null) {
        $('#div_nodata').show();
        $('#div_hasdata').hide();
    } else {
        $('#div_nodata').hide();
        $('#div_hasdata').show();
    }
</script>
<script>
    // Hiển thị modal sửa bộ đề
    function EditModal(id) {
        var _token = $('input[name="_token"]').val();
        var id = $('input[name="hidden_sj_id"]').val();
        $.ajax({
            type: "POST",
            url: "{{route('edit_modal_subject_set')}}",
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
    // Cập nhật thông tin bộ đề
    $('#btn_update').click(function (e) {
        e.preventDefault();
        var _token = $('input[name="_token"]').val();
        var option = document.getElementById('sel_level');
        var level = option.value;
        var name = $('input[name="name"]').val();
        var id = $('input[name="hidden_id"]').val();
        $.ajax({
            type: "POST",
            url: "{{route('update_subject_set')}}",
            data: {_token:_token, id:id, name:name, level:level},
            dataType: "json",
            success: function (data) {
                $('#show_noti').html(data.success);
                $('#table_subject_set').load(' #table_subject_set');
            }
        });
    });
    // Reset modal chỉnh sửa bộ đề sau khi đóng
    $('#btn_update_close').click(function (e) {
        e.preventDefault();
        $('#edit_modal_form').load(' #edit_modal_form');
    });
    // Upload file excel
    $('#btn_upload_file').click(function (e) {
        e.preventDefault();
        if(this.id == 'btn_upload_file') {
            $(document.body).append('<div id="hackergrousrlpprsc-loader">Đang tải</div>');
            $(window).one("click", function() {
            $('#hackergrousrlpprsc-loader').fadeIn(500).delay(2500).fadeOut(500); });
        }
        var formData = new FormData($("#upload_form")[0]);
        $.ajax({
            type: "POST",
            url: "{{route('upload_file')}}",
            data: formData,
            dataType: 'JSON',
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                if(data.error) {
                    $('#show_noti_upload_file').html(data.error);
                } else if(data.success) {
                    $('#show_noti_upload_file').html(data.success);
                    $('#table_data').load(' #table_data');
                    $('#div_nodata').hide();
                    $('#div_hasdata').show();
                }
            },
            error: function() {
                $('#table_data').load(' #table_data');
                $('#div_nodata').show();
                $('#div_hasdata').hide();
                $('#show_noti_upload_file').html('<div class="alert alert-danger">Tải lên file thất bại! <br> Dữ liệu câu hỏi đã bị xóa! <br>Vui lòng kiểm tra nội dung file và tải lên lại!</div>');
            }
        });
    });
    // Reset modal upload file sau khi đóng
    $('#btn_upload_close').click(function (e) {
        e.preventDefault();
        $('#upload_modal_form').load(' #upload_modal_form');
    });
</script>
@endsection
