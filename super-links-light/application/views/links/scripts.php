<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}

$addLinksModel = $this->addLinksModel;
$affiliateUrlModel = $this->affiliateUrlModel;
$groupLinkModel = $this->groupLinkModel;
?>

<div class="modal fade" id="newGroupLink" tabindex="-1" role="dialog" aria-labelledby="newGroupLinkLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newGroupLinkLabel">Adicionar nova categoria de link</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
            FormHelper::formStart($groupLinkModel);
            ?>
            <div class="modal-body">
                    <?php
                    FormHelper::text(
                        $groupLinkModel,
                        'groupName',
                        [
                            'feedback' => [
                                'invalid-text' => TranslateHelper::getTranslate('Esse campo é obrigatório')
                            ]
                        ]
                    );
                    ?>
            </div>
            <div class="modal-footer">
                <button type="button" id="saveNewGroup" class="btn btn-primary">Salvar Categoria</button>
            </div>
            <?php
            FormHelper::formEnd();
            ?>
        </div>
    </div>
</div>

<div class="modal fade" id="waitSave" tabindex="-1" role="dialog" aria-labelledby="waitSave" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <div class="spinner-grow text-success" role="status">
                        <span class="sr-only"></span>
                    </div>
                    <div class="">
                        Salvando dados...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript">
    jQuery(document).ready(function(){

        let iswppLink = false;
        if(jQuery("#<?=FormHelper::getFieldId($addLinksModel, 'redirectType')?>").val() == 'wpp_tlg'){
            iswppLink = true;
        }

        jQuery(document).on("change","#<?=FormHelper::getFieldId($addLinksModel, 'redirectType')?>", function(){
            let affiliateUrl = jQuery(this).val()
            if(affiliateUrl == 'wpp_tlg'){
                iswppLink = true;
            }else{
                iswppLink = false;
            }
        })

        jQuery(document).on('click', '#saveNewGroup', function (event) {
            const groupName = jQuery("#<?=FormHelper::getFieldId($groupLinkModel, 'groupName')?>").val()

            const notifier = new Notifier({
                default_time: '4000'
            });

            if(groupName != '' && groupName != "undefined"){
                <?php $url = TEMPLATE_URL . '/saveNewGroupLink'; ?>

                const http = new XMLHttpRequest()
                const url = "<?=$url?>"
                let params = "type=ajax&groupName="+groupName

                http.open('POST', url, true);

                http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                http.onreadystatechange = function () {
                    if (http.readyState == 4 && http.status == 200) {
                        const response = JSON.parse(http.responseText)
                        if(response.status){
                            notifier.notify('success', 'A categoria salva com sucesso!');
                            preencheCampoCategoriaLink(groupName, response.id)
                        }else{
                            notifier.notify('warning', 'Não foi possível salvar. Verifique se essa categoria já existe.');
                        }
                    }else if (http.readyState == 4 && http.status != 200) {
                        notifier.notify('warning', 'Não foi possível salvar. Verifique se essa categoria já existe.');
                    }
                    jQuery('#newGroupLink').modal('hide')
                }

                http.send(params);

            }else{
                notifier.notify('warning', 'Não é possível salvar uma categoria sem nome');
            }
        })

        jQuery('#newGroupLink').on('hidden.bs.modal', function (e) {
            jQuery("#<?=FormHelper::getFieldId($groupLinkModel, 'groupName')?>").val("")
        })

        function preencheCampoCategoriaLink(groupName = '', idGroup = null){
            jQuery("#<?=FormHelper::getFieldId($groupLinkModel, 'id')?>").attr("disabled", "disabled")
            jQuery("#spinner").addClass("spinner-border")

            jQuery("#<?=FormHelper::getFieldId($groupLinkModel, 'id')?> option").prop("selected", false);
            setTimeout(function () {
                jQuery("#<?=FormHelper::getFieldId($groupLinkModel, 'id')?>").append(`
                     <option selected value="${idGroup}">${groupName}</option>
                `)
                jQuery("#spinner").removeClass("spinner-border")
                jQuery("#<?=FormHelper::getFieldId($groupLinkModel, 'id')?>").removeAttr("disabled")
            }, 500)
        }

        showTextHelpRedirect();

        jQuery(document).on("change","#<?=FormHelper::getFieldId($addLinksModel, 'redirectType')?>", function(){
            let redirectType = jQuery(this).val()
            showTextHelpRedirect(redirectType)
        })

        jQuery(document).on("change","#<?=FormHelper::getFieldId($addLinksModel, 'redirectType')?>", function(){
            let redirectType = jQuery(this).val()
            if(redirectType != 'php' && redirectType != 'wpp_tlg'){
                window.open('https://wpsuperlinks.top/pro', '_blank')
            }
        })

        function showTextHelpRedirect(redirectType = ''){
            if(redirectType == '') {
                redirectType = jQuery("#<?=FormHelper::getFieldId($addLinksModel, 'redirectType')?>").val()
            }

            switch(redirectType) {
                case 'javascript':
                    jQuery("#helpTextRedirect").html("<?=$addLinksModel->getHelpTextRedirect('javascript')?>")
                    break;
                case 'php':
                    jQuery("#helpTextRedirect").html("<?=$addLinksModel->getHelpTextRedirect('php')?>")
                    break;
                case 'camuflador':
                    jQuery("#helpTextRedirect").html("<?=$addLinksModel->getHelpTextRedirect('camuflador')?>")
                    break;
                case 'facebook':
                    jQuery("#helpTextRedirect").html("<?=$addLinksModel->getHelpTextRedirect('facebook')?>")
                    break;
                case 'wpp_tlg':
                    jQuery("#helpTextRedirect").html("<?=$addLinksModel->getHelpTextRedirect('wpp_tlg')?>")
                    break;
                default:
                    jQuery("#helpTextRedirect").html("<?=$addLinksModel->getHelpTextRedirect('html')?>")
            }
        }

        jQuery(document).on("click", ".removeAffiliateLink", function(){
            const idAffiliateLink = jQuery(this).attr('data-target')

            if(typeof idAffiliateLink != 'undefined' && idAffiliateLink != 'A' && idAffiliateLink != 'B' && idAffiliateLink != 'C') {
                if (confirm("Deseja remover este link de afiliado? (as métricas deste link serão perdidas)")) {
                    removeCourse(idAffiliateLink).then(result => {
                        if (result.status) {
                            jQuery("#box_" + idAffiliateLink).remove()
                            lastField = jQuery(".dynamicField input").last()
                            dataField = jQuery(lastField).attr('data-field')

                            if (typeof dataField == 'undefined') {
                                jQuery(".dynamicField").append(`<?php FormHelper::dynamicTextField($affiliateUrlModel, 'affiliateUrl', 'A', ['required' => true, 'feedback' => ['invalid-text' => TranslateHelper::getTranslate('O link deve começar com http:// ou https://. Verifique o link digitado, pois não é válido')]]);?>`)
                            }
                        }
                    })
                }
            }else{
                jQuery("#box_" + idAffiliateLink).remove()
            }
        })

        jQuery(document).on("click", ".removeAffiliateLinkClone", function(){
            const idAffiliateLink = jQuery(this).attr('data-target')

            jQuery("#box_" + idAffiliateLink).remove()
            let lastField = jQuery(".dynamicField input").last()
            let dataField = jQuery(lastField).attr('data-field')

            if (typeof dataField == 'undefined') {
                jQuery(".dynamicField").append(`<?php FormHelper::dynamicTextFieldClone($affiliateUrlModel, 'affiliateUrl', 'A', ['required' => true, 'feedback' => ['invalid-text' => TranslateHelper::getTranslate('O link deve começar com http:// ou https://. Verifique o link digitado, pois não é válido')]]);?>`)
            }
        })

        function removeCourse(idAffiliateLink){
            return new Promise((resolve, reject) => {

                <?php $url = TEMPLATE_URL . '/removeAffiliateLink'; ?>

                const http = new XMLHttpRequest()
                const url = "<?=$url?>"
                let params = "type=ajax&id="+idAffiliateLink

                http.open('POST', url, true);

                http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                http.onreadystatechange = function () {
                    if (http.readyState == 4 && http.status == 200) {
                        const response = JSON.parse(http.responseText)
                        resolve(response)
                    }
                }

                http.send(params);
            })
        }

        jQuery(document).on("click", "#addNovaUrl", function(){
            const lastField = jQuery(".dynamicField input").last()
            const dataField = jQuery(lastField).attr('data-field')

            if(getNextDataField(dataField) != -1){
                jQuery(".dynamicField").append(`<?php FormHelper::dynamicTextField($affiliateUrlModel, 'affiliateUrl', '${getNextDataField(dataField)}', ['required' => true, 'feedback' => ['invalid-text' => TranslateHelper::getTranslate('O link deve começar com http:// ou https://. Verifique o link digitado, pois não é válido')]]);?>`)
            }else{
                jQuery("#infoDynamicField").html(`<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                              Você não pode adicionar mais campos
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>`)
            }
        })

        function getNextDataField(lastDataField = 'A') {
            const letters = ["A","B","C"]
            for(let i = 0; i < letters.length; i++){
                if(lastDataField == letters[i]){
                    let next = i + 1
                    return (next >= letters.length)? -1 : letters[next]
                }
            }
        }

        jQuery(document).on("keyup", "#<?=FormHelper::getFieldId($addLinksModel,'keyWord')?>",function () {
            this.value = this.value.replace(/[^a-zA-Z0-9._/\-\b]+$/g,'')
            let keyWord = this.value

            if(this.value.length == 1 && this.value == '/'){
                this.value = ''
                jQuery("#keyWordComplete").html('')
            }else {
                jQuery("#keyWordComplete").html(keyWord)
            }
        })

        jQuery(document).on("keyup", "#<?=FormHelper::getFieldId($addLinksModel,'redirectDelay')?>",function () {
            this.value = this.value.replace(/[^0-9\b]+$/g,'')
            const position1 = jQuery(this).val()[0]
            if(jQuery(this).val().length > 1){
                jQuery(this).val(position1)
            }
        })

        jQuery("#<?=FormHelper::getFormId($addLinksModel)?>").submit(function(e){
            e.preventDefault()
            jQuery('#waitSave').modal('show')
            setValid()

            validRules().then(result => {
                if(result.length == 0){
                    jQuery(this).unbind('submit').submit()
                }

                setTimeout(function(){
                    jQuery('#waitSave').modal('hide')
                }, 500)

            }).catch(err => {
                setTimeout(function(){
                    jQuery('#waitSave').modal('hide')
                }, 500)
            })
        })

        function setValid() {
            jQuery("#<?=FormHelper::getFormId($addLinksModel)?>").find("input[type=text],select,textarea").each(function(index,value){
                jQuery(value).addClass('is-valid')
                jQuery(value).removeClass('is-invalid')
            })
        }

        function validRules(){
            return new Promise((resolve, reject) => {

                <?php $url = TEMPLATE_URL . '/validate'; ?>

                const http = new XMLHttpRequest()
                const url = "<?=$url?>"
                let params = "type=ajax"

                <?php
                if(isset($this->exceptRules) && !empty($this->exceptRules)){
                ?>
                params += "&exceptRules=<?=$this->exceptRules?>"
                <?php
                }
                ?>

                jQuery("#<?=FormHelper::getFormId($addLinksModel)?>").find("input[type=text],select,textarea").each(function (index, value) {
                    if(jQuery(value).hasClass("affiliateUrl")){
                        let affiliateUrlSplited = jQuery(value).val()

                        if(iswppLink){
                            affiliateUrlSplited = 'https://web.telegram.org';
                        }

                        affiliateUrlSplited = affiliateUrlSplited.split("?",1)
                        params += "&" + jQuery(value).attr('name') + "=" + affiliateUrlSplited
                    }else{
                        params += "&" + jQuery(value).attr('name') + "=" + jQuery(value).val()
                    }
                })


                http.open('POST', url, true);

                http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                http.onreadystatechange = function () {
                    if (http.readyState == 4 && http.status == 200) {
                        const response = JSON.parse(http.responseText)
                        // fecha todos os accordions
                        jQuery('.collapse').removeClass('show')

                        let fieldID
                        response.map(function (value) {
                            fieldID = `#${value.model}_${value.attribute}`
                            fieldID = jQuery(fieldID)

                            let showErrorAffiliate = false
                            if (fieldID.length == 0) {
                                fieldID = jQuery(`[name="${value.model}[${value.attribute}][]"]`)
                                showErrorAffiliate = true

                                if(!jQuery(fieldID[0]).val()){
                                    jQuery(fieldID[0]).addClass('is-invalid')
                                    jQuery(fieldID[0]).removeClass('is-valid')
                                }

                                if(fieldID[1] && !jQuery(fieldID[1]).val()){
                                    jQuery(fieldID[1]).addClass('is-invalid')
                                    jQuery(fieldID[1]).removeClass('is-valid')
                                }

                                if(fieldID[2] && !jQuery(fieldID[2]).val()){
                                    jQuery(fieldID[2]).addClass('is-invalid')
                                    jQuery(fieldID[2]).removeClass('is-valid')
                                }
                            }


                            if(!showErrorAffiliate) {
                                jQuery(fieldID).addClass('is-invalid')
                                jQuery(fieldID).removeClass('is-valid')
                            }

                            // abre os accordion que tem campos com erros
                            const headerParent = jQuery(fieldID).parents('.collapse')
                            headerParent.addClass('show')
                        })
                        resolve(response)
                    }
                }

                http.send(params);
            })
        }

        jQuery(document).on("change", "#<?=FormHelper::getFieldId($addLinksModel,'redirectType')?>", function() {
            if(jQuery(this).val() == 'php'){
                jQuery("#collapseTwo .card-body").hide()
                jQuery("#collapseTwo span").html(`<div class="card-body">
                                                <div class="alert alert-warning fade show" role="alert">
                                                  Redirecionamento do tipo "<strong>PHP</strong>" não permitem rastreamento
                                                </div>
                                            </div>`)
            }else{
                jQuery("#collapseTwo .card-body").show()
                jQuery("#collapseTwo span").html('')
            }


            if(jQuery(this).val() == 'facebook'){
                jQuery("#redirectFacebook").hide()
                jQuery("#redirectFacebook").html(`
                 <?php
                    $values = [
                        ['selected' => true, 'text' => TranslateHelper::getTranslate('Redirecionador (Html)'), 'val' => 'html'],
                        ['selected' => false, 'text' => TranslateHelper::getTranslate('Redirecionador (Javascript)'), 'val' => 'javascript'],
                        ['selected' => false, 'text' => TranslateHelper::getTranslate('Camuflador'), 'val' => 'camuflador'],
                    ];

                    FormHelper::selectFacebook(
                        $addLinksModel,
                        'redirectFace',
                        [],
                        $values
                    );
                ?>
                <span class="small">Caso deseje, ative as configurações do Cloak e/ou de como a página é vista nas redes sociais.</span>
                `)
                jQuery("#redirectFacebook").fadeIn('slow')
            }else{
                jQuery("#redirectFacebook").hide('slow')
                jQuery("#redirectFacebook").html('')
            }

            if(jQuery(this).val() == 'camuflador'){
                jQuery("#redirectBox").html(`
                 <?php
                    FormHelper::text(
                        $addLinksModel,
                        "redirectBtn",
                        []
                    );
                ?>
                <span class="small">Caso deseje, ative as configurações do Cloak e/ou de como a página é vista nas redes sociais.</span>
                `)

                jQuery("#enableRedirectJavascript").hide()
                jQuery("#enableRedirectJavascript").html(`
                <div class="row">
                    <div class="col-md-8 mb-3 mt-3">
                         <?php
                            $values = [
                                ['selected' => true, 'text' => TranslateHelper::getTranslate('Habilitado'), 'val' => 'enabled'],
                                ['selected' => false, 'text' => TranslateHelper::getTranslate('Desabilitado'), 'val' => 'disabled'],
                            ];

                            FormHelper::select(
                                $addLinksModel,
                                'enableRedirectJavascript',
                                [],
                                $values
                            );
                         ?>
                        <span class="small">Caso a página não possa ser camuflada corretamente, o link fará o redirecionamento automático ao invés de camuflar</span>
                    </div>
                </div>
                `)
                jQuery("#enableRedirectJavascript").fadeIn('slow')
            }else{
                jQuery("#redirectBox").html(`
                                                <div class="alert alert-warning fade show" role="alert">
                                                  Somente redirecionamento do tipo "<strong>Camuflador</strong>" permitem ativar o redirect no botão voltar do navegador.
                                                </div>
                                            `)
                jQuery("#enableRedirectJavascript").hide('slow')
                jQuery("#enableRedirectJavascript").html('')
            }
        })

        jQuery(document).on("change", "#<?=FormHelper::getFieldId($addLinksModel,'redirectFace')?>", function() {
            if(jQuery(this).val() == 'camuflador'){
                jQuery("#redirectBox").html(`
                 <?php
                FormHelper::text(
                    $addLinksModel,
                    "redirectBtn",
                    []
                );
                ?>
                <span class="small">Caso deseje, ative as configurações do Cloak e/ou de como a página é vista nas redes sociais.</span>
                `)

                jQuery("#enableRedirectJavascript").hide()
                jQuery("#enableRedirectJavascript").html(`
                <div class="row">
                    <div class="col-md-8 mb-3 mt-3">
                         <?php
                $values = [
                    ['selected' => true, 'text' => TranslateHelper::getTranslate('Habilitado'), 'val' => 'enabled'],
                    ['selected' => false, 'text' => TranslateHelper::getTranslate('Desabilitado'), 'val' => 'disabled'],
                ];

                FormHelper::select(
                    $addLinksModel,
                    'enableRedirectJavascript',
                    [],
                    $values
                );
                ?>
                        <span class="small">Caso a página não possa ser camuflada corretamente, o link fará o redirecionamento automático ao invés de camuflar</span>
                    </div>
                </div>
                `)
                jQuery("#enableRedirectJavascript").fadeIn('slow')
            }else{
                jQuery("#redirectBox").html(`
                                                <div class="alert alert-warning fade show" role="alert">
                                                  Somente redirecionamento do tipo "<strong>Camuflador</strong>" permitem ativar o redirect no botão voltar do navegador.
                                                </div>
                                            `)
                jQuery("#enableRedirectJavascript").hide('slow')
                jQuery("#enableRedirectJavascript").html('')
            }
        })

        jQuery(document).on("click", ".uploadImage", function (e) {
            e.preventDefault();
            var image = wp.media({
                title: 'Upload Image',
                // mutiple: true if you want to upload multiple files at once
                multiple: false
            }).open()
                .on('select', function (e) {
                    // This will return the selected image from the Media Uploader, the result is an object
                    var uploaded_image = image.state().get('selection').first()
                    // We convert uploaded_image to a JSON object to make accessing it easier
                    // Output to the console uploaded_image
                    var image_url = uploaded_image.toJSON().url

                    // Let's assign the url value to the input field
                    jQuery(".uploadImage").val(image_url)
                    jQuery("#showImage").html(`<img src="${image_url}" class="img-fluid">`)
                    jQuery("#showButtomRemoveImage").show()
                })
        })
        
        jQuery(document).on("click", "#removeImage", function () {
            jQuery(".uploadImage").val("")
            jQuery("#showImage").html("")
            jQuery("#showButtomRemoveImage").hide()
        })

        if(jQuery(".uploadImage").val() != ""){
            jQuery("#showButtomRemoveImage").show()
        }
    })

</script>
