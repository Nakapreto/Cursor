<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}
$superLinksModel = $this->superLinksModel;
$telefone = get_option('email_super_links');

$ddi = $superLinksModel->getAttribute('ddi');

?>

<div class="wrap">
    <?php
    FormHelper::formStart($superLinksModel);
    ?>
    <div class="container">
        <div class="py-1">
            <div class="row">
                <div class="col-8">
                    <h3><?= $this->pageData['pageTitle'] ?></h3>
                    <p class="small"><?php TranslateHelper::printTranslate('Preencha com o seu telefone para ativar o plugin')?></p>
                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-md-2 mb-3">
                    <?php
                        if(!$telefone){
                            FormHelper::text(
                                $superLinksModel,
                                'ddi',
                                [
                                    'feedback' => [
                                        'invalid-text' => TranslateHelper::getTranslate('É necessário preencher este campo com o ddi'),
                                    ],
                                ]
                            );
                        }else{
                        FormHelper::text(
                            $superLinksModel,
                            'ddi',
                            [
                                'feedback' => [
                                    'invalid-text' => TranslateHelper::getTranslate('É necessário preencher este campo com o ddi'),
                                ],
                                'disabled' => true
                            ]
                        );
                    ?>
                            <input type="hidden" name="<?=$superLinksModel->getModelName()?>[ddi]" value="<?=$ddi?>" id="hiddenDdiSpl">
                            <?php
                        }
                    ?>
                    <p class="small">Deixe 55 para Brasil (Somente números)</p>
                </div>
                <div class="col-md-6 mb-3">
                    <?php
                    if(!$telefone){
                        FormHelper::text(
                            $superLinksModel,
                            'telefone',
                            [
                                'feedback' => [
                                    'invalid-text' => TranslateHelper::getTranslate('É necessário preencher este campo com o seu telefone válido e que tenha whatsapp'),
                                ]
                            ]
                        );
                    }else {
                        $superLinksModel->setAttribute('telefone', $telefone);
                        FormHelper::text(
                            $superLinksModel,
                            'telefone',
                            [
                                'feedback' => [
                                    'invalid-text' => TranslateHelper::getTranslate('É necessário preencher este campo com o seu telefone válido e que tenha whatsapp'),
                                ],
                                'disabled' => true
                            ]
                        );
                    ?>
                        <input type="hidden" name="<?=$superLinksModel->getModelName()?>[telefone]" value="<?=$telefone?>" id="hiddenEmailSpl">
                    <?php
                    }
                    ?>
                    <p class="small">Digite somente os números, incluindo o DDD.  </p>
                </div>

                <div class="col-md-12 mt-4">
                    <?php
                        FormHelper::submitButton(
                            $superLinksModel,
                            TranslateHelper::getTranslate('Ativar plugin'),
                            [
                                'class' => 'btn-success btn-sm'
                            ]
                        );
                    ?>

                    <div id="ativaBotaowp" style="display: none;">
                        <?Php
                        FormHelper::submitButton(
                            $superLinksModel,
                            TranslateHelper::getTranslate('Ativar plugin'),
                            [
                                'class' => 'btn-success btn-sm'
                            ]
                        );
                        ?>
                    </div>

                    <button type="button" class="btn btn-primary btn-sm" id="alterarTelefone">
                        Alterar telefone
                    </button>
                </div>
            </div>

        </div>
    </div>
    <?php
    FormHelper::formEnd();
    ?>
</div>

<?php
$this->render(SUPER_LINKS_VIEWS_PATH . '/admin/scripts.php');
?>
