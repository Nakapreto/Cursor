<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}

$addLinksModel = $this->addLinksModel;
$groupLinkModel = $this->groupLinkModel;
$affiliateUrlModel = $this->affiliateUrlModel;

?>

<div class="wrap">
        <?php
        FormHelper::formStart($addLinksModel);
        ?>
        <div class="container">
            <div class="py-1">
                <div class="row justify-content-end">
                    <div class="col-8">
                        <h3><?= $this->pageData['pageTitle'] ?></h3>
                        <p class="small"><?php TranslateHelper::printTranslate('Preencha os campos abaixo para criar um novo link redirecionável')?></p>
                    </div>
                    <div class="col-4 text-right">
                        <?php
                        FormHelper::submitButton(
                            $addLinksModel,
                            TranslateHelper::getTranslate('Atualizar link'),
                            [
                                'class' => 'btn-success btn-sm',
                                'id' => 'submitForm'
                            ]
                        );
                        ?>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="col-md-12 order-md-1">
            <div class="accordion col-md-12" id="super-links-config-box">
                <div class="card col-md-12 no-margin no-padding">
                    <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <?php TranslateHelper::printTranslate('Dados do link')?>
                            </button>
                        </h2>
                    </div>

                    <input type="text" style="display: none;" name="<?=$addLinksModel->getModelName()?>[id]" value="<?php echo $addLinksModel->getAttribute('id');?>">

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                         data-parent="#super-links-config-box">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <?php
                                    $values = [
                                        ['selected' => true, 'text' => TranslateHelper::getTranslate('Habilitado'), 'val' => 'enabled'],
                                        ['selected' => false, 'text' => TranslateHelper::getTranslate('Desabilitado'), 'val' => 'disabled'],
                                    ];

                                    FormHelper::select(
                                        $addLinksModel,
                                        'statusLink',
                                        [],
                                        $values
                                    );
                                    ?>
                                </div>
                                <div class="col-md-5 mb-3">
                                    <?php
                                    $values = $groupLinkModel->getAllGroupsValues();

                                    FormHelper::select(
                                        $groupLinkModel,
                                        'id',
                                        [],
                                        $values
                                    );
                                    ?>
                                    <span id="spinner"></span>
                                    <a href="#" data-toggle="modal" data-target="#newGroupLink">Clique aqui para adicionar nova categoria</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <?php
                                    FormHelper::text(
                                            $addLinksModel,
                                            'linkName',
                                            [
                                                'feedback' => [
                                                    'invalid-text' => TranslateHelper::getTranslate('Um nome de link é obrigatório o preenchimento para identificar o link criado')
                                                ]
                                            ]
                                    );
                                    ?>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <?php
                                    FormHelper::text(
                                            $addLinksModel,
                                            'keyWord',
                                            [
                                                'feedback' => [
                                                    'invalid-text' => TranslateHelper::getTranslate('Já existe um link com este caminho, ou está vazio')
                                                ],
                                                'autocomplete' => 'off'
                                            ]
                                    );
                                    echo "<small>" . TEMPLATE_URL . "/<span id='keyWordComplete'></span></small>";
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <?php
                                    $values = $addLinksModel->getOptionsRedirectLight();

                                    FormHelper::select(
                                        $addLinksModel,
                                        'redirectType',
                                        [
                                            'feedback' => [
                                                'invalid-text' => TranslateHelper::getTranslate('Esta opção está habilitada somente na versão Pro')
                                            ]
                                        ],
                                        $values
                                    );
                                    ?>
                                    <span class="small" id="helpTextRedirect"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3 dynamicField">
                                    <?php
                                    FormHelper::dynamicTextFieldUpdate(
                                            $affiliateUrlModel,
                                            'affiliateUrl',
                                            'A',
                                            [
                                                'feedback' => [
                                                    'invalid-text' => TranslateHelper::getTranslate('O link deve começar com http:// ou https://. Verifique o link digitado, pois não é válido.')
                                                ],
                                                'placeholder' => 'https://',
                                                'class' => 'form-control affiliateUrl'
                                            ]
                                    );
                                    ?>
                                </div>
                                <div class="col-md-8"  id="infoDynamicField"></div>
                            </div>
                            <div class="row">
                                <div class="col-auto my-1">
                                    <button type="button" id="addNovaUrl" class="btn btn-info">
                                        <?php TranslateHelper::printTranslate('Adicionar nova Url de Afiliado')?>
                                    </button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <h4><?php TranslateHelper::printTranslate('Descrição do link');?></h4>
                                    <hr>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <?php
                                    FormHelper::textArea(
                                        $addLinksModel,
                                        "description",
                                        []
                                    );
                                    ?>
                                </div>
                            </div>


                            <div class="row mt-5 mb-3">
                                <div class="col-md-12">
                                    <h4><?php TranslateHelper::printTranslate('Opções de carregamento da página [Pro]');?></h4>
                                    <hr>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mt-4">
                                    <h4><?php TranslateHelper::printTranslate('Habilitar o redirect no botão voltar [Pro]');?></h4>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card col-md-12 no-margin no-padding">
                    <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button"
                                    data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                                    aria-controls="collapseTwo">
                                <?php TranslateHelper::printTranslate('Opções de rastreamento [Pro]')?>
                            </button>
                        </h2>
                    </div>
                </div>
                <div class="card col-md-12 no-margin no-padding">
                    <div class="card-header" id="headingThree">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button"
                                    data-toggle="collapse" data-target="#collapseThree" aria-expanded="false"
                                    aria-controls="collapseThree">
                                <?php TranslateHelper::printTranslate('Cloak (opções de camuflagem do link por países) [Pro]')?>
                            </button>
                        </h2>
                    </div>
                </div>

                <div class="card col-md-12 no-margin no-padding">
                    <div class="card-header" id="headingFour">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button"
                                    data-toggle="collapse" data-target="#collapseFour" aria-expanded="false"
                                    aria-controls="collapseFour">
                                <?php TranslateHelper::printTranslate('Visualização da página nas redes sociais [Pro]')?>
                            </button>
                        </h2>
                    </div>
                </div>

            </div>

            <div class="col-md-12 mt-4">
                <?php
                    FormHelper::submitButton(
                            $addLinksModel,
                            TranslateHelper::getTranslate('Atualizar link'),
                            [
                                'class' => 'btn-success btn-sm'
                            ]
                    );
                ?>
            </div>
        </div>

    <?php
        FormHelper::formEnd();
    ?>
</div>

<?php
$this->render(SUPER_LINKS_VIEWS_PATH . '/links/scripts.php');
?>