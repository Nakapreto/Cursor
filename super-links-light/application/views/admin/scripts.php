<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}

$superLinksModel = $this->superLinksModel;
?>
<script type="application/javascript">
    jQuery(document).ready(function(){

        jQuery(document).on("click","#alterarTelefone",function(){
            jQuery("#hiddenEmailSpl").remove()
            jQuery("#hiddenDdiSpl").remove()
            jQuery("#<?=FormHelper::getFieldId($superLinksModel,'telefone')?>").prop("disabled", false)
            jQuery("#<?=FormHelper::getFieldId($superLinksModel,'ddi')?>").prop("disabled", false)
            jQuery("#submitActivation").text("Ativar plugin")
        })

        jQuery(document).on("click","#submitActivation",function(){
            jQuery(this).remove()
            jQuery("#alterarTelefone").remove()
            jQuery("#ativaBotaowp").show()
        })

        jQuery("#<?=FormHelper::getFormId($superLinksModel)?>").submit(function(e){
            e.preventDefault()
            setValid()

            validRules().then(result => {
                if(result.length == 0){
                    jQuery(this).unbind('submit').submit()
                }
            })
        })

        jQuery(document).on("keyup","#SuperLinksModel_telefone",function(){
            let tel = jQuery(this).val()
            tel = mascararTel(tel)
            jQuery(this).val(tel)
        })

        function mascararTel(v){
            v=v.replace(/\D/g,"");
            v=v.replace(/^(\d{2})(\d)/g,"($1) $2");
            v=v.replace(/(\d)(\d{4})$/,"$1-$2");
            return v;
        }

        jQuery(document).on("keyup","#SuperLinksModel_ddi",function(){
            let tel = jQuery(this).val()
            tel = mascararDdi(tel)
            jQuery(this).val(tel)
        })

        function mascararDdi(v){
            v=v.replace(/\D/g,"");
            return v;
        }

        function setValid() {
            jQuery("#<?=FormHelper::getFormId($superLinksModel)?>").find("input[type=text],select,textarea").each(function(index,value){
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

                jQuery("#<?=FormHelper::getFormId($superLinksModel)?>").find("input[type=text],select,textarea").each(function (index, value) {
                    params += "&" + jQuery(value).attr('name') + "=" + jQuery(value).val()
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

                            if (fieldID.length == 0) {
                                fieldID = jQuery(`[name="${value.model}[${value.attribute}][]"]`)
                            }

                            jQuery(fieldID).addClass('is-invalid')
                            jQuery(fieldID).removeClass('is-valid')

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
    })
</script>
