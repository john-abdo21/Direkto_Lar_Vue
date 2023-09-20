<?php




use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForgotPasswordController;

use App\Http\Controllers\ReportController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RestrictionController;

use App\Http\Controllers\UtilsController;
use App\Http\Controllers\ConfController;

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\IndicatorsController;



// header("Access-Control-Allow-Origin: https://direktor.com.pe");

// header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
// header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Requested-With");

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    // Route::resource('/survey', \App\Http\Controllers\SurveyController::class);

    // Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index']);
    Route::post('/info_person', [AuthController::class, 'info_person']);
    Route::post('/upd_person', [AuthController::class, 'upd_person']);

});

Route::post('/recuperar_credenciales_solicitud', [ForgotPasswordController::class, 'submitForgetPasswordForm']);
Route::post('/recuperar_credenciales_validar', [ForgotPasswordController::class, 'validateTokenPassword']);
Route::post('/recuperar_credenciales', [ForgotPasswordController::class, 'submitResetPasswordForm']);

// Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

// Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
// Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');

// Route::get('/survey-by-slug/{survey:slug}', [\App\Http\Controllers\SurveyController::class, 'showForGuest']);
// Route::post('/survey/{survey}/answer', [\App\Http\Controllers\SurveyController::class, 'storeAnswer']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/create_project', [ProjectController::class, 'create_project']);
Route::get('/sendMails/{user}', [ProjectController::class, 'sendMails']);
Route::post('/get_project', [ProjectController::class, 'get_project']);
Route::post('/get_restrictions', [RestrictionController::class, 'get_restriction']);
Route::post('/edit_project', [ProjectController::class, 'edit_project']);
Route::post('/get_projectreport', [ProjectController::class, 'get_projectreport']);
Route::post('/get_projectuser', [ProjectController::class, 'get_projectuser']);
Route::post('/update_restriction_member', [RestrictionController::class, 'update_member']);
Route::post('/update_restriction_state', [RestrictionController::class, 'update_state']);
Route::post('/update_project_state', [ProjectController::class, 'update_state']);
Route::post('/update_hidden_columns', [RestrictionController::class, 'update_hidden']);

Route::post('/add_front', [RestrictionController::class, 'add_front']);
Route::post('/add_phase', [RestrictionController::class, 'add_phase']);
Route::post('/add_Actividad', [RestrictionController::class, 'add_Actividad']);
Route::post('/get_front', [RestrictionController::class, 'get_front']);
Route::get('/get_restriction_p', [RestrictionController::class, 'get_restriction_p']);
Route::post('/add_restriction',[RestrictionController::class, 'add_restriction']);
Route::post('/delete_front',[RestrictionController::class, 'delete_front']);
Route::post('/delete_restriction',[RestrictionController::class, 'delete_restriction']);
Route::post('/duplicate_restriction',[RestrictionController::class, 'duplicate_restriction']);


Route::get('/get_cargos', [UtilsController::class, 'get_cargos']);
Route::get('/get_tipoproyectos', [UtilsController::class, 'get_tipoproyectos']);
Route::get('/get_ubigeo', [UtilsController::class, 'get_ubigeo']);
Route::get('/get_moneda', [UtilsController::class, 'get_moneda']);
Route::get('/get_areaintegrante', [UtilsController::class, 'get_areaintegrante']);
Route::get('/get_proyrolintegrante', [UtilsController::class, 'get_proyrolintegrante']);
Route::get('/get_utilitarios_proyecto', [UtilsController::class, 'get_utilitarios_proyecto']);

Route::get('/get_programmingdaytypes', [ConfController::class, 'get_programmingdaytypes']);

Route::post('/get_buscar', [UtilsController::class, 'get_search_empresa']);
Route::post('/set_new_empresa', [UtilsController::class, 'set_new_empresa']);
Route::post('/get_data_restricciones', [RestrictionController::class, 'get_data_restricciones']);
Route::post('/upd_restricciones', [RestrictionController::class, 'upd_restricciones']);
Route::post('/upd_numOrden', [RestrictionController::class, 'upd_numOrden']);

/* Upload Excel */
Route::post('/uploadExcel/{user}/{projectId}', [RestrictionController::class, 'uploadExcel']);
Route::post('/get_restrictionsMember', [RestrictionController::class, 'get_restrictionsMember']);
Route::post('/get_estado', [RestrictionController::class, 'get_estado']);



Route::post('/get_proy_applicant', [ProjectController::class, 'get_proy_applicant']);
Route::post('/get_notification', [ProjectController::class, 'get_notification']);
Route::post('/update_cod_notification', [ProjectController::class, 'update_cod_notification']);
Route::post('/register_notification', [ProjectController::class, 'register_notification']);

Route::post('/projects_without_approve', [ProjectController::class, 'projects_without_approve']);
Route::post('/update_projects_without_approve', [ProjectController::class, 'update_projects_without_approve']);


Route::post('/get_search_person', [AuthController::class, 'get_search_person']);
Route::post('/push_enviar_notificaciones', [RestrictionController::class, 'push_enviar_notificaciones']);


Route::get('/cmd_enviar_notificacionDiaria', [RestrictionController::class, 'cron_enviar_notificacionDiaria']);

Route::get('/generar_reporte', [ReportController::class, 'generarReporte']);




Route::post('/send_notification' , [NotificationController::class, 'callNotification']);
Route::post('/get_data_restricciones_indicators' , [IndicatorsController::class, 'get_project_restricciones']);
Route::post('/get_project_indicators' , [IndicatorsController::class, 'get_project_indicators']);


