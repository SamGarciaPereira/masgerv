    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\index\DashboardController;
    use App\Http\Controllers\cliente\ClienteController;
    use App\Http\Controllers\orcamento\OrcamentoController;
    use App\Http\Controllers\processo\ProcessoController;
    use App\Http\Controllers\manutencao\ManutencaoController;
    use App\Http\Controllers\financeiro\ContasPagarController;
    use App\Http\Controllers\financeiro\ContasReceberController;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\anexo\AnexoController;

    //ROTAS DE AUTENTICAÇÃO
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function() {
        //ROTA PRINCIPAL
        Route::get('/', [DashboardController::class, 'index'])->name('home');

        //ROTAS ADMIN
        Route::middleware(['admin'])->group(function () {
            //ROTAS DO MÓDULO FINANCEIRO 
            Route::prefix('financeiro')->name('financeiro.')->group(function () {
                Route::resource('contas-pagar', ContasPagarController::class);
                Route::resource('contas-receber', ContasReceberController::class);
            });
        });

        //ROTA DE SOLICITAÇÕES 
        Route::get('admin/solicitacoes', [App\Http\Controllers\admin\SolicitacaoController::class, 'index'])->name('admin.solicitacao.index');

        //APROVAR SOLICITAÇÃO
        Route::post('admin/solicitacoes/{solicitacao}/aceitar', [App\Http\Controllers\admin\SolicitacaoController::class, 'accept'])->name('admin.solicitacoes.accept');

        //RECUSAR SOLICITAÇÃO
        Route::post('admin/solicitacoes/{solicitacao}/recusar', [App\Http\Controllers\admin\SolicitacaoController::class, 'reject'])->name('admin.solicitacoes.reject');

        //ROTAS DOS MÓDULOS (CRUD)
        Route::resource('clientes', ClienteController::class);
        Route::resource('orcamentos', OrcamentoController::class);
        Route::resource('processos', ProcessoController::class);
        Route::resource('manutencoes', ManutencaoController::class)
            ->parameters(['manutencoes' => 'manutencao'])
            ->only(['index', 'store', 'update', 'destroy']);

        
        //ROTAS DO MÓDULO DE MANUTENÇÃO
        Route::prefix('manutencoes/corretiva')->name('manutencoes.corretiva.')->group(function () {
            Route::get('/', [ManutencaoController::class, 'indexCorretiva'])->name('index');
            Route::get('/create', [ManutencaoController::class, 'createCorretiva'])->name('create');
            Route::get('/{manutencao}/edit', [ManutencaoController::class, 'editCorretiva'])->name('edit');
        });

        Route::prefix('manutencoes/preventiva')->name('manutencoes.preventiva.')->group(function () {
            Route::get('/', [ManutencaoController::class, 'indexPreventiva'])->name('index');
            Route::get('/create', [ManutencaoController::class, 'createPreventiva'])->name('create');
            Route::get('/{manutencao}/edit', [ManutencaoController::class, 'editPreventiva'])->name('edit');
        });

        //ROTAS DE ANEXOS
        Route::post('/anexos/upload', [AnexoController::class, 'store'])->name('anexos.store');
        Route::delete('/anexos/{anexo}', [AnexoController::class, 'destroy'])->name('anexos.destroy');
        Route::get('/anexos/{anexo}/download', [AnexoController::class, 'download'])->name('anexos.download');
        Route::get('/anexos/{anexo}/{filename}', [AnexoController::class, 'show'])->name('anexos.show');
    });








