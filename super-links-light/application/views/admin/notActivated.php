<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}

$message = isset($this->pageData['message'])?  $this->pageData['message'] : '';
?>

<div class="wrap">
    <div class="container">
        <div class="py-1">
            <div class="row justify-content-end">
                <div class="col-12">
                    <?php
                    if(isset($this->pageData['sendEmail']) && $this->pageData['sendEmail']){
                        AlertHelper::displayAlert(TranslateHelper::getTranslate('Verifique seu telefone digitado:') . "<br>". $message , 'success');
                    }else{
                        AlertHelper::displayAlert(TranslateHelper::getTranslate('O plugin não foi ativado.') . "<br>". $message , 'danger');
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>