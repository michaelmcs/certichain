<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Auth\LoginController,
    BusquedaController,
    CertificadoController,
    CursoController,
    EmpresaController,
    HomeController,
    MiPerfilController,
    ParticipanteController,
    RecuperarClaveController,
    TemarioController,
    UsuarioController
};

// Página de inicio
Route::get('/', fn () => view('welcome'))->name('welcome');

// Login
Auth::routes(['verify' => true]);

// Recuperar contraseña
Route::get('recuperar-clave', [RecuperarClaveController::class, 'index'])->name('recuperar.index');
Route::post('recuperarClaveEnviar', [RecuperarClaveController::class, 'enviarCorreo'])->name('recuperar.enviar');

// Home (dashboard)
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('verified');

// ---------------------
// CERTIFICADOS - ADMIN
// ---------------------
Route::middleware('verified')->group(function () {
    Route::resource('certificado', CertificadoController::class)->except(['show']);
    Route::get('certificado/{id}', [CertificadoController::class, 'ajustar'])->name('certificado.show');
    Route::get('certificado/ajustar/{id}', [CertificadoController::class, 'show'])->name('certificado.ajustar');
    Route::get('verPDF/{id}', [CertificadoController::class, 'verPDF'])->name('certificado.verPDF');
    Route::put('certificadoAdd/{id}', [CertificadoController::class, 'add'])->name('certificado.add');
    Route::get('buscar/certificado/{id}', [CertificadoController::class, 'buscar'])->name('certificado.buscar');
    Route::get('certificado/ver/certificado/{id}', [CertificadoController::class, 'ver'])->name('certificado.ver');
    Route::get('certificado/eliminarModelo/{id}', [CertificadoController::class, 'delete'])->name('certificado.eliminarModelo');
    Route::post('certificado/guardar-posicion', [CertificadoController::class, 'guardarPosicion'])->name('certificado.guardarPosicion');
});

// ---------------------
// BÚSQUEDA DE CERTIFICADOS
// ---------------------
Route::get('/busqueda', [BusquedaController::class, 'index'])->name('busqueda.index');
Route::match(['get', 'post'], '/buscar', [BusquedaController::class, 'buscar'])->name('busqueda.buscar');
Route::get('/certificados/buscar/{id}', [CertificadoController::class, 'buscar'])->name('certificados.buscar');

Route::get('/enviarCorreo/{id}', [BusquedaController::class, 'enviar'])->name('correo.enviar');
Route::get('/validarCodigo/{participante}/{curso}/{codigo}', [BusquedaController::class, 'enviarCodigo'])->name('correo.enviarCodigo');

Route::get('/verCertificado/{participante}/{codigo?}', [BusquedaController::class, 'verCertificado'])->name('busqueda.ver');
Route::get('/verPDF/{id}/{participante}', [BusquedaController::class, 'verPDF'])->name('busqueda.pdf');
Route::get('/verMiCertificadoQR/{id_participante}', [BusquedaController::class, 'verPDFQR'])->name('busqueda.miCertificadoQR');

Route::get('vistaPrevia/{participante}/{codigo?}', [BusquedaController::class, 'verCertificado'])->name('busqueda.vistaPrevia');
Route::get('verMiCertificado/{id_certificado}/{id_participante}', [BusquedaController::class, 'verPDF'])->name('busqueda.miCertificado');

// ---------------------
// CURSO
// ---------------------
Route::middleware('verified')->group(function () {
    Route::resource('curso', CursoController::class);
    Route::get('export/curso', [CursoController::class, 'exportCurso'])->name('exportCurso.index');
    Route::get('buscar/curso/{id}', [CursoController::class, 'buscar'])->name('curso.buscar');
    Route::get('curso/ver/curso/{id}', [CursoController::class, 'ver'])->name('curso.ver');
});

// ---------------------
// TEMARIO
// ---------------------
Route::middleware('verified')->group(function () {
    Route::resource('temario', TemarioController::class);
    Route::get('export/temario', [TemarioController::class, 'exportTemario'])->name('exportTemario.index');
});

// ---------------------
// PARTICIPANTE
// ---------------------
Route::middleware('verified')->group(function () {
    Route::resource('participante', ParticipanteController::class);
    Route::get('export/participante', [ParticipanteController::class, 'exportParticipante'])->name('exportParticipante.index');
    Route::get('exportModelo/participante', [ParticipanteController::class, 'exportModeloParticipante'])->name('exportModeloParticipante.index');
    Route::post('importParticipante', [ParticipanteController::class, 'importParticipante'])->name('importParticipante.index');
    Route::put('modificarCertificadoParticipante/{id}', [ParticipanteController::class, 'modificarCert'])->name('participante.modCer');
    Route::get('eliminarCertificadoParticipante/{id}', [ParticipanteController::class, 'eliminarCert'])->name('participante.eliCer');
    Route::get('eliminarTodoParticipante', [ParticipanteController::class, 'eliminarTodo'])->name('participante.eliminarTodo');
    Route::get('crearQR/{id_participante}', [ParticipanteController::class, 'crearQR'])->name('participante.crearQR');
    Route::get('buscar/participante/{id}', [ParticipanteController::class, 'buscar'])->name('participante.buscar');
    Route::get('participante/ver/participante/{id}', [ParticipanteController::class, 'ver'])->name('participante.ver');
});

// ---------------------
// USUARIO
// ---------------------
Route::middleware('verified')->group(function () {
    Route::resource('usuario', UsuarioController::class);
    Route::post('modificar/foto/{id}', [UsuarioController::class, 'modificarFoto'])->name('modificarFoto.update');
    Route::get('eliminar/foto/{id}', [UsuarioController::class, 'eliminarFoto'])->name('eliminarFoto.delete');
});

// ---------------------
// EMPRESA
// ---------------------
Route::middleware('verified')->group(function () {
    Route::resource('empresa', EmpresaController::class);
    Route::post('updateImg', [EmpresaController::class, 'updateImg'])->name('empresa.updateImg');
    Route::get('eliminarImg/{id}', [EmpresaController::class, 'eliminarImg'])->name('empresa.eliminarImg');
});

// ---------------------
// PERFIL
// ---------------------
Route::middleware('verified')->group(function () {
    Route::get('miPerfil', [MiPerfilController::class, 'miPerfilIndex'])->name('perfil.index');
    Route::post('miPerfilUpdate', [MiPerfilController::class, 'miPerfilEditar'])->name('perfil.update');
    Route::get('miPassword', [MiPerfilController::class, 'miPasswordIndex'])->name('password.index');
    Route::post('miPasswordUpdate', [MiPerfilController::class, 'miPasswordEditar'])->name('password.update');
    Route::post('perfil-update-perfil', [MiPerfilController::class, 'perfilUpdatePerfil'])->name('perfil.updatePerfil');
    Route::get('perfil-delete-perfil-{id}', [MiPerfilController::class, 'perfilDeletePerfil'])->name('perfil.deletePerfil');
});
