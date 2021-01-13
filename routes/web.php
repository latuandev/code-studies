<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CodeLearnController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProgrammingLanguageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
    USER
*/
    // Trang đăng nhập
        Route::get('/login', [AccountController::class, 'HandleLogin'])->name('login');
        Route::post('/login/post', [AccountController::class, 'HandleLogin'])->name('login.post');
        // Xử lý quên mật khẩu
        Route::post('/login/recovery_pass', [AccountController::class, 'RecoveryPass'])->name('recovery.pass');
        // Trang đặt lại mật khẩu
        Route::get('/reset-pass', [AccountController::class, 'SetNewPass']);
        // Xử lý đặt lại mật khẩu mới
        Route::post('/reset-pass/set_new_pass', [AccountController::class, 'HandleSetNewPass'])->name('set_new_pass');
    // Trang đăng ký
    Route::post('/registration/post', [AccountController::class, 'HandleRegistration'])->name('registration.post');
    // Điều hướng xử lý đăng xuất
    Route::get('/logout', [AccountController::class, 'HandleLogout']);
    // Trang chủ
    Route::get('/', [HomeController::class, 'GetHome'])->name('home');
    // Trang ngôn ngữ lập trình
    Route::get('/programming-language/{code}', [ProgrammingLanguageController::class, 'GetLevel']);
        // Điều hướng xử lý lấy dữ liệu bộ đề trắc nghiệm
        Route::post('programming-language/get_subject_set', [ProgrammingLanguageController::class, 'LoadSubjectSet'])->name('get_subject_set');
        // Điều hướng xử lý lấy dữ liệu câu hỏi trắc nghiệm
        Route::post('programming-language/get_question', [ProgrammingLanguageController::class, 'LoadQuestion'])->name('get_question');
        // Điều hướng xử lý kết quả bài làm
        Route::post('/programming-language/{code}/{level}/{quiz}', [ProgrammingLanguageController::class, 'HandleQuiz']);
    // Trang kết quả bài trắc nghiệm đã làm
    Route::get('/programming-language/{code}/{level}/{quiz}/result', [ProgrammingLanguageController::class, 'Result']);
    Route::post('/programming-language/{code}/{level}/{quiz}/result', [ProgrammingLanguageController::class, 'Result']);
        // Điều hướng xử lý làm lại bộ đề trắc nghiệm
        Route::post('/programming-language/rework', [ProgrammingLanguageController::class, 'Rework'])->name('rework');
        // Điều hướng xử lý xếp hạng người dùng
        Route::post('/rank', [ProgrammingLanguageController::class, 'Rank'])->name('rank');
    // Trang người dùng
    Route::get('/user-info', [UserController::class, 'GetUser'])->name('user');
        // Lấy Modal thông tin người dùng
        Route::post('/user-info/edit_data', [UserController::class, 'EditData'])->name('user-info.edit_data');
        // Cập nhật thông tin người dùng
        Route::post('/user-info/update_data', [UserController::class, 'UpdateData'])->name('user-info.update_data');
        // Tải lên ảnh đại diện của người dùng
        Route::post('/user-info/upload_image', [UserController::class, 'UploadImage'])->name('user-info.upload_image');
        // Thay đổi mật khẩu người dùng
        Route::post('/change-password', [AccountController::class, 'ChangePassword'])->name('change_password');
    // Trang luyện code
    Route::get('code-learn', [CodeLearnController::class, 'CodeLearn']);
        // Lấy dữ liệu bài tập
        Route::post('code-learn/exercise', [CodeLearnController::class, 'CodeLearnLoad'])->name('exercise');
        // Lấy editor code theo ngôn ngữ lập trình
        Route::post('code-learn/language_code', [CodeLearnController::class, 'LanguageCode'])->name('language.code');
        // Lấy dữ liệu code mẫu
        Route::post('code-learn/example_code', [CodeLearnController::class, 'ExampleCode'])->name('example_code');
/*
    ADMIN
*/
    // Trang dashboard
    Route::get('/dashboard', [AdminController::class, 'GetDashboard'])->middleware('check.login')->name('dashboard');
    Route::get('/dashboard/download/file_mau_cau_hoi', [AdminController::class, 'DownloadFileExample']);
    /*
        LANGUAGES
    */
        // Trang ngôn ngữ lập trình
        Route::get('/dashboard-language', [AdminController::class, 'GetLanguage']);
            // Thêm ngôn ngữ lập trình
            Route::post('/dashboard-language/add_language', [AdminController::class, 'HandleAddLanguage'])->name('add_language');
            // Hiển thị dữ liệu vào modal chỉnh sửa ngôn ngữ
            Route::post('/dashboard-language/edit_modal_language', [AdminController::class, 'ModalEditLanguage'])->name('edit_modal_language');
            // Cập nhật thông tin NNLT
            Route::post('/dashboard-language/update_language', [AdminController::class, 'HandleUpdateLanguage'])->name('update_language');
            // Xóa NNLT
            Route::post('/dashboard-language/delete_language', [AdminController::class, 'HandleDeleteLanguage'])->name('delete_language');
    /*
        SUBJECT SETS
    */
        // Trang bộ đề
        Route::get('/dashboard-subject-set', [AdminController::class, 'GetSubjectSet']);
            // Chọn bộ đề theo ngôn ngữ
            Route::post('/dashboard-subject-set/select_language', [AdminController::class, 'HandleSelectLanguage'])->name('select_language');
            // Tìm kiếm bộ đề theo mã
            Route::post('/dashboard-subject-set/search_subject_set', [AdminController::class, 'SearchSubjectSet'])->name('search_subject_set');
            // Thêm bộ đề
            Route::post('/dashboard-subject-set/add_subject_set', [AdminController::class, 'HandlAddSubjectSet'])->name('add_subject_set');
            // Xoá bộ đề
            Route::post('/dashboard-subject-set/delete_subject_set', [AdminController::class, 'DeleteSubjectSet'])->name('delete_subject_set');
        // Trang câu hỏi của bộ đề
        Route::get('/dashboard-subject-set/{id}', [AdminController::class, 'GetSubjectSetDetail']);
            // Hiển thị dữ liệu vào modal chỉnh sửa bộ đề
            Route::post('/dashboard-subject-set/edit_modal_subject_set', [AdminController::class, 'EditModalSubjectSet'])->name('edit_modal_subject_set');
            // Cập nhật thông tin bộ đề
            Route::post('/dashboard-subject-set/update_subject_set', [AdminController::class, 'UpdateSubjectSet'])->name('update_subject_set');
            // Upload file excel câu hỏi
            Route::post('/dashboard-subject-set/upload_file', [AdminController::class, 'UploadExcel'])->name('upload_file');
            // Download file excel câu hỏi
            Route::get('/dashboard-subject-set/download_file/{code}', [AdminController::class, 'DownloadExcelQuestion']);
    /*
        CODE LEARN
    */
    // Trang luyện code
    Route::get('dashboard-code-learn', [AdminController::class, 'GetCodeLearn']);
        // Tìm kiếm bài tập
        Route::post('dashboard-code-learn/search_exercise', [AdminController::class, 'SearchExercise'])->name('search_exercise');
        // Thêm bài tập
        Route::post('dashboard-code-learn/add_exercise', [AdminController::class, 'AddExercise'])->name('add_exercise');
        // Chi tiết bài tập
        Route::post('dashboard-code-learn/update_code_learn', [AdminController::class, 'ExerciseContent'])->name('update_code_learn');
        // Cập nhật bài tập
        Route::post('dashboard-code-learn/update_exercise', [AdminController::class, 'UpdateExercise'])->name('update_exercise');
        // Xóa bài tập
        Route::post('dashboard-code-learn/delete_exercise', [AdminController::class, 'DeleteExercise'])->name('delete_exercise');
    /*
        USER MANAGER
    */
        // Trang quản lý người dùng
        Route::get('/dashboard-user-manager', [AdminController::class, 'GetUserManager']);
        // Xóa người dùng
        Route::post('/dashboard-user-manager/delete_user', [AdminController::class, 'HandleDeleteUser'])->name('delete_user');
        // Lịch sử người dùng
        Route::post('/dashboard-user-manager/history_user', [AdminController::class, 'GetHistoryUser'])->name('history_modal_user');
        // Tìm kiếm người dùng
        Route::post('/dashboard-user-manager/searchsearch-user', [AdminController::class, 'HandleSearchUser'])->name('search_user');

