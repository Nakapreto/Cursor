<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}
$pageTitle = $this->pageData['pageTitle'];
?>

<div class="wrap">
    <div class="container">
        <div class="py-1">
            <div class="row justify-content-end">
                <div class="col-8">
                    <h3><?= $pageTitle ?></h3>
                    <p class="small">
                        <?php TranslateHelper::printTranslate('Suas vendas vão aumentar com os cookies de afiliado ativado em suas páginas e posts.')?>
                    </p>
                </div>
                <div class="col-4 text-right">
                    <a class="btn btn-success btn-sm" href="https://wpsuperlinks.top/pro" target="_blank"><?= TranslateHelper::getTranslate('Adicionar nova configuração [Pro]') ?></a>
                </div>
            </div>
            <hr>
        </div>
        <div class="border mt-2">
            <div class="card-header">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                        </ul>
                        <form class="form-inline my-2 my-lg-0">
                            <input class="form-control mr-sm-2" type="search" placeholder="Procurar"
                                   aria-label="Procurar" id="inputSearch">
                        </form>
                    </div>
                </nav>
            </div>

            <?php

            $rowTitles = [
                "Nome do Cookie",
                "Post/Página",
                "Status do Cookie",
                "Ativo?",
                '<i class="fas fa-cogs"></i>'
            ];
            TableHelper::loadTable($rowTitles);

            TableHelper::loadRowsAutomaticViews([]);

            TableHelper::tableEnd();
            ?>

        </div>
        <div class="text-center mt-3">
            <a href="https://wpsuperlinks.top/pro" target="_blank" class="btn btn-success btn-sm">Obter a versão Pro agora!</a>
        </div>
    </div>
</div>