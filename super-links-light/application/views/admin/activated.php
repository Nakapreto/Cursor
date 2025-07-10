<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}

?>

<div class="wrap">
    <div class="container">
        <div class="py-1">
            <div class="row justify-content-end">
                <div class="col-12">
                    <?php
                    AlertHelper::displayAlert(TranslateHelper::getTranslate('O plugin foi ativado com sucesso!'));
                    ?>
                </div>

                <div class="col-12 mt-1">
                    <a class="btn btn-success btn-sm"
                       href="admin.php?page=super_links_add"><?= TranslateHelper::getTranslate('Adicionar link') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>