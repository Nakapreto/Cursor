<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}
$superLinksModel = new SuperLinksModel();
?>

<div class="wrap">
    <div class="container">
        <div class="py-1">
            <div class="row justify-content-end">
                <div class="col-12">
                    <h3><?php TranslateHelper::printTranslate('Super Links Multisite - Encurtador de Links')?></h3>
                    <p class="small"><?php TranslateHelper::printTranslate('Plugin de encurtamento de links otimizado para WordPress Multisite com subdomínios')?></p>
                </div>
            </div>
            <hr>
        </div>
        <div>
            <div class="row">
                <div class="alert alert-info col-8" role="alert">
                    <h5 class="alert-heading">Bem-vindo ao Super Links Multisite!</h5>
                    <p>Este plugin foi adaptado especialmente para WordPress Multisite configurado com subdomínios.</p>
                    <hr>
                    <p class="mb-0">Agora você pode criar links encurtados usando seu próprio domínio em qualquer site da sua rede multisite.</p>
                </div>
            </div>

            <div class="row mt-3">
                <div class="card col-8">
                    <div class="card-body">
                        <h5 class="card-title">Funcionalidades Disponíveis</h5>
                        <table class="table table-bordered table-hover table-striped m-0">
                            <thead>
                            <tr>
                                <th>Funcionalidades</th>
                                <th class="text-center"><span class="badge badge-success width-5 fs-lg">Multisite</span></th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Encurtador de links (Redirecionamento PHP)</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Encurtador de links para Whatsapp e Telegram</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Categorização de links</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Testes A/B</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Importar links de outros plugins</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Compatível com WordPress Multisite</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Suporte a subdomínios</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Sem sistema de ativação obrigatório</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="card col-8">
                    <div class="card-body">
                        <h5 class="card-title">Primeiros Passos</h5>
                        <ol>
                            <li><strong>Criar Links:</strong> Acesse "Criar Links" no menu para começar a criar seus links encurtados.</li>
                            <li><strong>Configurações:</strong> Acesse "Configurações" para ajustar as opções do plugin.</li>
                            <li><strong>Importar:</strong> Se você já usa outro plugin de links, pode importar seus links existentes.</li>
                        </ol>
                        <div class="mt-3">
                            <a href="<?php echo admin_url('admin.php?page=super_links_list_view'); ?>" class="btn btn-primary">Criar Primeiro Link</a>
                            <a href="<?php echo admin_url('admin.php?page=super_links_config'); ?>" class="btn btn-secondary">Configurações</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
