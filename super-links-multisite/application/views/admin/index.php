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
                    <h3><?php TranslateHelper::printTranslate('Super Links Multisite - Plugin Completo de Encurtamento de Links')?></h3>
                    <p class="small"><?php TranslateHelper::printTranslate('Versão completa do Super Links adaptada para WordPress Multisite com subdomínios - sem sistema de ativação obrigatório')?></p>
                </div>
            </div>
            <hr>
        </div>
        <div>
            <div class="row">
                <div class="alert alert-success col-8" role="alert">
                    <h5 class="alert-heading">✅ Bem-vindo ao Super Links Multisite!</h5>
                    <p><strong>Plugin completo e funcional!</strong> Esta versão foi especialmente adaptada para WordPress Multisite.</p>
                    <hr>
                    <p class="mb-0">Todas as funcionalidades estão disponíveis imediatamente após a instalação. Nenhuma ativação necessária!</p>
                </div>
            </div>

            <div class="row mt-3">
                <div class="card col-8">
                    <div class="card-body">
                        <h5 class="card-title">Funcionalidades Completas Disponíveis</h5>
                        <table class="table table-bordered table-hover table-striped m-0">
                            <thead>
                            <tr>
                                <th>Funcionalidades</th>
                                <th class="text-center"><span class="badge badge-success width-5 fs-lg">Multisite</span></th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>🔗 Encurtador de links (Redirecionamento PHP)</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>📱 Encurtador para WhatsApp e Telegram</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>📊 Categorização de links</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>🧪 Testes A/B</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>📥 Importar links de outros plugins</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>🌐 WordPress Multisite</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>🎭 Clonagem de páginas</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>🧠 Links inteligentes</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>🍪 Sistema de cookies</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>🎯 Rastreamento avançado</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>🌍 Geolocalização</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>📝 Popups personalizados</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>🔧 Sem sistema de ativação</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>🔄 Suporte a subdomínios</td>
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
                        <div class="row">
                            <div class="col-md-6">
                                <h6>📝 Criação de Links</h6>
                                <ul>
                                    <li>Acesse "Criar Links" para adicionar novos links</li>
                                    <li>Configure redirecionamentos e opções avançadas</li>
                                    <li>Organize em categorias</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>🎭 Clonagem de Páginas</h6>
                                <ul>
                                    <li>Clone páginas de vendas facilmente</li>
                                    <li>Personalize elementos clonados</li>
                                    <li>Configure tracking e pixels</li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h6>🧠 Links Inteligentes</h6>
                                <ul>
                                    <li>Adicione links automáticos em conteúdo</li>
                                    <li>Configure palavras-chave</li>
                                    <li>Gerencie métricas</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>🍪 Sistema de Cookies</h6>
                                <ul>
                                    <li>Configure cookies personalizados</li>
                                    <li>Gerencie redirecionamentos condicionais</li>
                                    <li>Controle comportamentos avançados</li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="<?php echo admin_url('admin.php?page=super_links_list_view'); ?>" class="btn btn-primary">📝 Criar Primeiro Link</a>
                            <a href="<?php echo admin_url('admin.php?page=super_links_list_Clones'); ?>" class="btn btn-info">🎭 Clonar Páginas</a>
                            <a href="<?php echo admin_url('admin.php?page=super_links_automatic_list_view'); ?>" class="btn btn-warning">🧠 Links Inteligentes</a>
                            <a href="<?php echo admin_url('admin.php?page=super_links_config'); ?>" class="btn btn-secondary">⚙️ Configurações</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="card col-8">
                    <div class="card-body">
                        <h5 class="card-title">💡 Dicas para WordPress Multisite</h5>
                        <div class="alert alert-info">
                            <ul class="mb-0">
                                <li><strong>Subdomínios:</strong> Cada site da rede pode usar seus próprios links encurtados</li>
                                <li><strong>Configuração global:</strong> Plugin ativo em toda a rede automaticamente</li>
                                <li><strong>Dados isolados:</strong> Cada site mantém seus próprios links e métricas</li>
                                <li><strong>Sem limitações:</strong> Todas as funcionalidades disponíveis em todos os sites</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>