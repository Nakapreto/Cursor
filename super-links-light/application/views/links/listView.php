<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}
$pageTitle = $this->pageData['pageTitle'];
$addLinkModel = $this->addLinksModel;

$existCategory = $this->pageData['existCategory'];

if ($existCategory) {
?>

    <div class="wrap">
        <div class="container">
            <div class="py-1">
                <div class="row justify-content-end">
                    <div class="col-8">
                        <h3><?= $pageTitle ?></h3>
                        <p class="small"><?php TranslateHelper::printTranslate('Clique no botão amarelo do lado direito de cada categoria para ver os links associados a ela.') ?></p>
                    </div>
                    <div class="col-4 text-right">
                        <a class="btn btn-success btn-sm"
                           href="admin.php?page=super_links_add"><?= TranslateHelper::getTranslate('Adicionar novo link') ?></a>
                    </div>
                </div>
                <hr>
            </div>
            <div id="listCategories">
                <?php $this->render(SUPER_LINKS_VIEWS_PATH . '/links/listViewCategorized.php'); ?>
            </div>
        </div>
    </div>

<?php
}else{
        $this->render(SUPER_LINKS_VIEWS_PATH . '/links/listViewLinks.php');
    }
?>
