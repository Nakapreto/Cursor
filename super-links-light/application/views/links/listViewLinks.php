<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}
$pageTitle = $this->pageData['pageTitle'];
$addLinkModel = $this->addLinksModel;
?>

<div class="wrap">
    <div class="container">
        <div class="py-1">
            <div class="row justify-content-end">
                <div class="col-8">
                    <h3><?= $pageTitle ?></h3>
                    <p class="small"><?php TranslateHelper::printTranslate('Com os Links redirecionáveis você pode camuflar links para fazer anúncios no Facebook e Google, monitorar métricas e muito mais...')?></p>
                </div>
                <div class="col-4 text-right">
                    <a class="btn btn-success btn-sm"
                       href="admin.php?page=super_links_add"><?= TranslateHelper::getTranslate('Adicionar novo link') ?></a>
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
                            <li class="nav-item">
                                <a class="nav-link btn-filter" data-target="Html">Html</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn-filter" data-target="Php">Php</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn-filter" data-target="Wpp_tlg">WPP e TLG</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn-filter" data-target="Javascript">Javascript</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn-filter" data-target="Camuflador">Camuflador</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn-filter" data-target="Facebook">Facebook</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn-filter" data-target="all">Todos</a>
                            </li>
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
                $addLinkModel->attributeLabels()['linkName'],
                $addLinkModel->attributeLabels()['redirectType'],
                $addLinkModel->attributeLabels()['keyWord'],
                $addLinkModel->attributeLabels()['statusLink'],
                '<i class="fas fa-cogs"></i>'
            ];
            TableHelper::loadTable($rowTitles);

            $links = $this->pageData['links'];
            $totalAccessThisLink = $this->pageData['totalAccessThisLink'];

            // remove os links que são criados para redirecionamento no facebook
            foreach ($links as $link) {
                $link = get_object_vars($link);
                if($link['redirectType'] == 'facebook'){
                    $affiliateUrl = new SuperLinksAffiliateLinkModel();
                    $affiliateData = $affiliateUrl->getAllDataByParam($link['id'],'idLink');
                    if($affiliateData){
                        $affiliateData = array_shift($affiliateData);
                        $affiliateData = get_object_vars($affiliateData);
                        foreach($links as $id => $l){
                            $l = get_object_vars($l);
                            if($affiliateData['affiliateUrl'] == (TEMPLATE_URL . '/' . $l['keyWord']) ){
                                $totalAccessThisLink[$link['id']] = $totalAccessThisLink[$l['id']]; // coloca a metrica do link disponivel para o usuario no lugar da metrica do link visivel para os robos do facebook
                                unset($links[$id]);
                            }
                        }
                    }
                }
            }

            foreach ($links as $link) {
                $link = get_object_vars($link);

                $actions = '  <a class="btn btn-outline-success btn-sm" href="admin.php?page=super_links_clone_link&id='.$link['id'].'" data-container="body" data-toggle="popover" data-placement="top" data-content="Fazer uma cópia deste link"><i class="fas fa-clone"></i></a>
                              <a class="btn btn-outline-warning btn-sm spl-actions-view" href="admin.php?page=super_links_view_link&id='.$link['id'].'" data-container="body" data-toggle="popover" data-placement="top" data-content="Visualizar métricas do link"><i class="fas fa-eye"></i></a>
                              <a class="btn btn-outline-primary btn-sm spl-actions-edit" href="admin.php?page=super_links_edit_link&id='.$link['id'].'" data-container="body" data-toggle="popover" data-placement="top" data-content="Editar o link"><i class="fas fa-pen"></i></a>
                              <a class="btn btn-outline-danger btn-sm delete spl-actions-delete" data-target="'.$link['id'].'" data-container="body" data-toggle="popover" data-placement="top" data-content="Excluir este link"><em class="fa fa-trash"></em></a>';

                $accessThisLink = isset($totalAccessThisLink[$link['id']])? $totalAccessThisLink[$link['id']] : '0';

                $textAccessTotal = 'Total de acessos neste link: '.$accessThisLink;

                $linkName = '
                    <button type="button" class="btn btn-sm btn-primary" data-container="body" data-toggle="popover" data-placement="top" data-content="'.$textAccessTotal.'">
                      '.$link['linkName'].' <span class="badge badge-light">Cliques: '.$accessThisLink.'</span>
                    </button>
                ';

                $link = [
                    $link['id'],
                    $linkName,
                    ucfirst($link['redirectType']),
                    '<span data-container="body" data-toggle="popover" data-placement="top" data-content="'.$textAccessTotal.'"><a href="' . TEMPLATE_URL . '/' . $link['keyWord'] . '" target="_blank">/' . $link['keyWord'] . '</a> &nbsp;&nbsp;
                    <a id="copyLink" href="#" class="badge badge-success" data-target="' . TEMPLATE_URL . '/' . $link['keyWord'] . '">Copiar</a></span>',
                    ($link['statusLink'] == 'enabled')? '<span class="text-success">Habilitado</span>' : '<span class="text-warning">Desabilitado</span>',
                    $actions
                ];
                TableHelper::loadRows($link);
            }

            $existLinks = $this->pageData['existLinks'];

            if(!$existLinks){
                $link = [];

                TableHelper::loadRows($link);
            }

            TableHelper::tableEnd();
            ?>


        </div>
    </div>
</div>

<script type="application/javascript">
    jQuery(document).ready(function () {

        jQuery(document).on('click', '.btn-filter', function () {
            const target = jQuery(this).data('target')
            if (target != 'all') {
                jQuery('.table .filterLink').css('display', 'none')
                jQuery('.table tr[data-status="' + target + '"]').show('slow')
            } else {
                jQuery('.table tr').css('display', 'none').show('slow')
            }
        })

        jQuery(document).on('click', '#copyLink',function (e) {
            e.preventDefault()
            let field = jQuery(this)

            let inputTest = document.createElement("input")
            inputTest.value = field.attr('data-target')
            document.body.appendChild(inputTest)
            inputTest.select()
            document.execCommand('copy')
            document.body.removeChild(inputTest)

            field.hide()
            field.html('Copiado!')
            field.show('slow')
            setTimeout(function(){
                field.hide()
                field.html('Copiar')
                field.show('slow')
            }, 2000)
        })

        jQuery(document).on('keyup', '#inputSearch', function () {
            const inputSearch = jQuery(this).val()
            searchSpl(inputSearch)
        })

        jQuery(document).on('click', '.delete', function () {
            const idLink = jQuery(this).attr('data-target')

            if (confirm("Deseja excluir este link?")) {
                deleteLink(idLink).then(result => {
                    if(result.status){
                        jQuery("#link_"+idLink).remove()
                    }
                })
            }
        })

        function deleteLink(idLink){
            return new Promise((resolve, reject) => {

                <?php $url = TEMPLATE_URL . '/delete'; ?>

                const http = new XMLHttpRequest()
                const url = "<?=$url?>"
                let params = "type=ajax&id="+idLink

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

        function searchSpl(input = '') {
            let filter, table, tr, td, i, txtValue, searchBox, j, tdBox, displayYes

            filter = input.toUpperCase()
            table = document.getElementById("table-spl")
            tr = table.getElementsByTagName("tr")

            displayYes = []
            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                tdBox = tr[i].getElementsByTagName("td");
                searchBox = (tdBox.length - 1)
                for(j = 0; j < searchBox; j++){
                    td = tr[i].getElementsByTagName("td")[j];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            displayYes.push(tr[i])
                        }
                    }
                }

                if(tr[i].getAttribute('id') != 'table-head'){
                    tr[i].style.display = "none";
                }
            }

            for(i=0;i < displayYes.length; i++){
                displayYes[i].style.display = "";
            }
        }
    });
</script>