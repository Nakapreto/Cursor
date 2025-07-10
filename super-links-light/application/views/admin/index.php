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
                    <h3><?php TranslateHelper::printTranslate('Olá, você está usando a versão light do Super Links')?></h3>
                    <p class="small"><?php TranslateHelper::printTranslate('Na versão Pro, você pode clonar páginas e camuflar seus links para conseguir fazer anúncios em tráfego pago')?></p>
                </div>
            </div>
            <hr>
        </div>
        <div>
            <div class="row">
                <div class="alert alert-primary col-8" role="alert">
                    Saiba em primeira mão as novidades e atualizações do Super Links -> <a href="https://wpsuperlinks.top/novidades" target="_blank" class="btn btn-primary btn-sm">Clique aqui</a>
                </div>
                <div class="col-4" style="text-align:right; right: 10px; top: 33%; height: 100% !important; position: fixed;">
                    <a href="https://wpsuperlinks.top/afiliados" target="_blank"><img src="https://wpsuperlinks.top/imagens/afiliados-super-links.png" style="width: 300px;" class="img-fluid"></a>
                </div>
            </div>
            <div class="row">
                <div class="card col-8">
                    <div class="card-body">
                        <h5 class="card-title text-danger">Atenção:</h5>
                        <div>
                            <div>Você está usando menos de 20% de todo o potencial do Super Links</div>
                            <div class="progress mt-2">
                                <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 21%;" aria-valuenow="19" aria-valuemin="0" aria-valuemax="100">19%</div>
                            </div>
                            <div class="text-center mt-3">
                                <a href="https://wpsuperlinks.top/pro" target="_blank" class="btn btn-success btn-sm">Obter a versão Pro agora!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="card col-8">
                    <div class="card-body">
                        <h5 class="card-title">Compare as versões</h5>
                        <table class="table table-bordered table-hover table-striped m-0">
                            <thead>
                            <tr>
                                <th>Funcionalidades</th>
                                <th class="text-center"><span class="badge badge-danger width-5 fs-lg">Light</span></th>
                                <th class="text-center"><span class="badge badge-success width-5 fs-lg">Pro</span></th>
                            </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>Encurtador de links (Redirecionamento PHP)</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Encurtador de links para Whatsapp e Telegram</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Categorização de links</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Testes A/B</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Importar links de outros plugins</td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Rastreamento de links para Whatsapp e Telegram</td>
                                    <td class="text-center"><i class="fas fa-times-circle fa-2x text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Clonagem de páginas</td>
                                    <td class="text-center"><i class="fas fa-times-circle fa-2x text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Popups</td>
                                    <td class="text-center"><i class="fas fa-times-circle fa-2x text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Redirecionamentos Html ou Javascript</td>
                                    <td class="text-center"><i class="fas fa-times-circle fa-2x text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Camuflador de página</td>
                                    <td class="text-center"><i class="fas fa-times-circle fa-2x text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Link especial para anúncios no Facebook</td>
                                    <td class="text-center"><i class="fas fa-times-circle fa-2x text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Habilite redirecionamento caso a página não possa ser camuflada</td>
                                    <td class="text-center"><i class="fas fa-times-circle fa-2x text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Back Redirect</td>
                                    <td class="text-center"><i class="fas fa-times-circle fa-2x text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Opções de carregamento da página</td>
                                    <td class="text-center"><i class="fas fa-times-circle fa-2x text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Pixel de conversão de lead</td>
                                    <td class="text-center"><i class="fas fa-times-circle fa-2x text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Track personalizado no pixel do Facebook</td>
                                    <td class="text-center"><i class="fas fa-times-circle fa-2x text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Rastreamento de links</td>
                                    <td class="text-center"><i class="fas fa-times-circle fa-2x text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Insira scripts nos links</td>
                                    <td class="text-center"><i class="fas fa-times-circle fa-2x text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Cloak - habilite seus links em determinados lugares</td>
                                    <td class="text-center"><i class="fas fa-times-circle fa-2x text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Links inteligentes</td>
                                    <td class="text-center"><i class="fas fa-times-circle fa-2x text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Ativação de Cookies</td>
                                    <td class="text-center"><i class="fas fa-times-circle fa-2x text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Back redirect em todo o blog</td>
                                    <td class="text-center"><i class="fas fa-times-circle fa-2x text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Redirecionar ou exibir Popup ao sair da página</td>
                                    <td class="text-center"><i class="fas fa-times-circle fa-2x text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle fa-2x text-success"></i></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center mt-3">
                            <a href="https://wpsuperlinks.top/pro" target="_blank" class="btn btn-success btn-sm">Obter a versão Pro agora!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
