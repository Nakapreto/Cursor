<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}

$pageTitle = $this->pageData['pageTitle'];
$affiliateData = isset($this->pageData['affiliate']) ? $this->pageData['affiliate'] : null;
$linkName = isset($this->pageData['linkName']) ? $this->pageData['linkName'] : null;
$keyWord = isset($this->pageData['keyWord']) ? $this->pageData['keyWord'] : null;
$redirectType = isset($this->pageData['redirectType']) ? $this->pageData['redirectType'] : null;
$statusLink = isset($this->pageData['statusLink']) ? $this->pageData['statusLink'] : null;

$addLinksModel = new SuperLinksAddLinkModel();
?>

<div class="wrap">
    <div class="container">
        <?Php
        if (!$linkName) {
            ?>
            <div class="row text-center">
                <div class="col-12">
                    <h3><?= $pageTitle ?></h3>
                </div>
            </div>
            <div class="border mt-2">
                <div class="row">
                    <div class="col">
                        <div class="box-link">
                            <p class="box-text "><?php TranslateHelper::printTranslate('Não encontramos o que você procurava') ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="row">
                <div class="col-12">
                    <h3><?= $pageTitle . ': <strong>' . $linkName . '</strong>' ?></h3>
                    <hr>
                </div>
            </div>
            <div class="mt-2">
                <div class="list-group mb-4">
                    <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                        <p class="mb-1"><strong><?= $addLinksModel->attributeLabels()['statusLink'] ?>:</strong> <?= $statusLink? '<span class="text-success">Habilitado</span>' : '<span class="text-warning">Desabilitado</span>' ?></p>
                        <p class="mb-1"><strong><?= $addLinksModel->attributeLabels()['redirectType'] ?>:</strong> <?= $redirectType ?></p>
                        <p class="mb-1"><strong><?= $addLinksModel->attributeLabels()['keyWord'] ?>:</strong> <?= TEMPLATE_URL . '/' . $keyWord ?></p>
                    </a>
                </div>
                <?php
                $affiliateModel = new SuperLinksAffiliateLinkModel();
                $metricsModel = new SuperLinksLinkMetricsModel();

                foreach ($affiliateData as $affiliateDatum) {
                    $dataAffiliate = $affiliateDatum['affiliateData'];
                    $metrics = isset($affiliateDatum['metrics'][0]) ? $affiliateDatum['metrics'][0] : [];
                    ?>
                    <ul class="list-group mb-3">
                        <li class="list-group-item">
                            <strong><?= $affiliateModel->attributeLabels()['affiliateUrl'] ?>:</strong> <span
                                    class="btn btn-success"><?= $dataAffiliate->affiliateUrl ?></span>
                            <div class="row mt-5">
                                <div class="col-md-4 offset-1">
                                    <div class="box-link">
                                        <h2 class="timer box-title count-number"><?= isset($metrics->accessTotal) ? $metrics->accessTotal : '0' ?></h2>
                                        <p class="box-text "><?= $metricsModel->attributeLabels()['accessTotal'] ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 offset-1">
                                    <div class="box-link">
                                        <h2 class="timer box-title count-number"><?= isset($metrics->uniqueTotalAccesses) ? $metrics->uniqueTotalAccesses : '0' ?></h2>
                                        <p class="box-text "><?= $metricsModel->attributeLabels()['uniqueTotalAccesses'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <?Php
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>
