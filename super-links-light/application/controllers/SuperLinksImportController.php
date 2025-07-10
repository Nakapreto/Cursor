<?php if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}

class SuperLinksImportController extends SuperLinksFramework
{
    protected $importModel;
    private $toast;
    private $timeToExpire;
    private $urlView;

    public function __construct($model = null, $hooks = [], $filters = [])
    {
        $this->toast = TranslateHelper::getTranslate('A importação foi feita com sucesso!');
        $this->timeToExpire = time() + 60;
        $this->urlView = TEMPLATE_URL . '/wp-admin/admin.php?page=super_links_import_links';

        $this->setScenario('super_links_import_links');

        $this->setModel($model);
        $this->importModel = $this->loadModel();

        $this->init($hooks, $filters);
    }

    public function init($hooks = [], $filters = []){
        $hooks = array_merge($hooks, $this->basicHooks());
        $filters = array_merge($filters, $this->basicFilters());

        parent::init($hooks, $filters);
    }

    private function basicHooks()
    {
        return [];
    }

    private function basicFilters()
    {
        return [];
    }

    public function importLinks()
    {
        if($this->isPluginActive()) {
            $this->pageData['pageTitle'] = TranslateHelper::getTranslate('Importação de links');
            $execImport = false;

            if (isset($_POST['scenario'])) {
                $importModel = $this->importModel;
                $post = $_POST[$importModel->getModelName()];
                if($post['pluginToImport']) {
                    if ($post['pluginToImport'] == 'prettyLinks') {
                        $this->importPrettyLinks();
                        $execImport = true;
                    }elseif($post['pluginToImport'] == 'hotLinksPlus'){
                        $this->importHotLinksPlus();
                        $execImport = true;
                    }
                }
            }

            if(!$execImport) {
                $this->render(SUPER_LINKS_VIEWS_PATH . '/import/importLinks.php');
            }
        }
    }

    private function importPrettyLinks(){
        $prettyModel = new SuperLinksImportPrettyLinksModel();

        if($_POST['scenario'] == 'import'){

            $saveSuccess = false;
            $error = false;

            foreach($_POST['import'] as $importLink){
                $addLinksModel = new SuperLinksAddLinkModel();
                $affiliateUrlModel = new SuperLinksAffiliateLinkModel();

                $importLink = explode(',', $importLink);
                $idLink = trim($importLink[0]);
                $redirectType = trim($importLink[1]);
                $group = trim($importLink[2]);

                $idGroup = $this->saveAndReturnIdGroup($group);

                if(!$idLink){
                    continue;
                }

                $link = $prettyModel->getAllDataByParam($idLink,'id');

                if($link){
                    $link = array_shift($link);

                    $keyWord = $link->slug;
                    $keyWord = strtolower($keyWord);

                    $linkName = $link->name;
                    if(!$linkName){
                        $linkName = ucfirst($keyWord);
                    }

                    $addLinksModel->setAttribute('linkName', $linkName);
                    $addLinksModel->setAttribute('description', $link->description);
                    $addLinksModel->setAttribute('keyWord', $keyWord);
                    $addLinksModel->setAttribute('redirectType', $redirectType);
                    $addLinksModel->setAttribute('abLastTest', '0');
                    $addLinksModel->setAttribute('createdAt', DateHelper::agora());

                    if($idGroup){
                        $addLinksModel->setAttribute('idGroup', $idGroup);
                    }

                    $idAddLinks = $addLinksModel->save();


                    if ($idAddLinks) {
                        $affiliateUrlModel->setAttribute('affiliateUrl', $link->url);
                        $affiliateUrlModel->setAttribute('createdAt', DateHelper::agora());
                        $affiliateUrlModel->setAttribute('idLink', $idAddLinks);
                        $affiliateSave = $affiliateUrlModel->save();

                        if($affiliateSave) {
                            $saveImport = new SuperLinksImportModel();
                            $saveImport->setAttribute('idLink', $idAddLinks);
                            $saveImport->setAttribute('pluginToImport', 'prettyLinks');
                            $saveImport->setAttribute('idLinkInPlugin', $idLink);
                            $saveImport->setAttribute('createdAt', DateHelper::agora());
                            $saveImport->save();

                            $saveSuccess = true;

                        }else{
                            $deleteLink = new SuperLinksAddLinkModel();
                            $deleteLink->loadDataByID($idAddLinks);
                            $deleteLink->delete();
                            $error = true;
                        }
                    }else{
                        $error = true;
                    }
                }

            }

            if($saveSuccess && !$error) {
                $toast = "Os links foram importados com sucesso";
                $typeToast = "success";
            }else if($saveSuccess && $error){
                $toast = "Alguns links foram importados com sucesso e em alguns houve erros. Verifique se possuem um link válido e não existem outros links com os mesmos dados no Super Links.";
                $typeToast = "warning";
            }else if(!$saveSuccess && $error){
                $toast = "Os links não puderam ser importados. Verifique se possuem um link válido e não existem outros links com os mesmos dados no Super Links.";
                $typeToast = "error";
            }

            $timeToExpire = $this->timeToExpire;
            $urlView = $this->urlView;
            echo "<script>
                              document.cookie = \"toastSPL=$toast; expires=$timeToExpire; path=/\";
                              document.cookie = \"typeToastSPL=$typeToast; expires=$timeToExpire; path=/\";
                              document.location = '" . $urlView . "'
                            </script>";
            exit();

        }else {
            $allData = $prettyModel->getDataPrettyLinks();
            $this->pageData = array_merge($allData, $this->pageData);
            $this->render(SUPER_LINKS_VIEWS_PATH . '/import/prettyListLinks.php');
        }
    }

    private function importHotLinksPlus(){
        $hotLinksModel = new SuperLinksImportHotLinksModel();

        if($_POST['scenario'] == 'import'){

            $saveSuccess = false;
            $error = false;

            foreach($_POST['import'] as $importLink){
                $addLinksModel = new SuperLinksAddLinkModel();

                $importLink = explode(',', $importLink);
                $idLink = trim($importLink[0]);
                $redirectType = trim($importLink[1]);
                $group = trim($importLink[2]);

                $idGroup = $this->saveAndReturnIdGroup($group);

                if(!$idLink){
                    continue;
                }

                $link = $hotLinksModel->getAllDataByParam($idLink,'id_link');

                if($link){
                    $link = array_shift($link);

                    $keyWord = $link->palavra_chave;
                    $keyWord = strtolower($keyWord);

                    $linkName = $link->nome_link;
                    if(!$linkName){
                        $linkName = ucfirst($keyWord);
                    }

                    $addLinksModel->setAttribute('linkName', $linkName);
                    $addLinksModel->setAttribute('description', $link->descricao);
                    $addLinksModel->setAttribute('keyWord', $keyWord);
                    $addLinksModel->setAttribute('redirectType', $redirectType);
                    $addLinksModel->setAttribute('abLastTest', '0');
                    $addLinksModel->setAttribute('createdAt', DateHelper::agora());

                    if($idGroup){
                        $addLinksModel->setAttribute('idGroup', $idGroup);
                    }

                    $idAddLinks = $addLinksModel->save();

                    if ($idAddLinks) {
                        $affiliateSave = false;

                        if($link->url_afiliado){
                            $affiliateUrlModel = new SuperLinksAffiliateLinkModel();
                            $affiliateUrlModel->setAttribute('affiliateUrl', $link->url_afiliado);
                            $affiliateUrlModel->setAttribute('createdAt', DateHelper::agora());
                            $affiliateUrlModel->setAttribute('idLink', $idAddLinks);
                            if($affiliateUrlModel->save()){
                                $affiliateSave = true;
                            }
                        }
                        if($link->url_afiliado2){
                            $affiliateUrlModel = new SuperLinksAffiliateLinkModel();
                            $affiliateUrlModel->setAttribute('affiliateUrl', $link->url_afiliado2);
                            $affiliateUrlModel->setAttribute('createdAt', DateHelper::agora());
                            $affiliateUrlModel->setAttribute('idLink', $idAddLinks);
                            if($affiliateUrlModel->save()){
                                $affiliateSave = true;
                            }
                        }
                        if($link->url_afiliado3){
                            $affiliateUrlModel = new SuperLinksAffiliateLinkModel();
                            $affiliateUrlModel->setAttribute('affiliateUrl', $link->url_afiliado3);
                            $affiliateUrlModel->setAttribute('createdAt', DateHelper::agora());
                            $affiliateUrlModel->setAttribute('idLink', $idAddLinks);
                            if($affiliateUrlModel->save()){
                                $affiliateSave = true;
                            }
                        }

                        if($affiliateSave) {
                            $saveImport = new SuperLinksImportModel();
                            $saveImport->setAttribute('idLink', $idAddLinks);
                            $saveImport->setAttribute('pluginToImport', 'prettyLinks');
                            $saveImport->setAttribute('idLinkInPlugin', $idLink);
                            $saveImport->setAttribute('createdAt', DateHelper::agora());
                            $saveImport->save();

                            $saveSuccess = true;

                        }else{
                            $deleteLink = new SuperLinksAddLinkModel();
                            $deleteLink->loadDataByID($idAddLinks);
                            $deleteLink->delete();
                            $error = true;
                        }
                    }else{
                        $error = true;
                    }
                }

            }

            if($saveSuccess && !$error) {
                $toast = "Os links foram importados com sucesso";
                $typeToast = "success";
            }else if($saveSuccess && $error){
                $toast = "Alguns links foram importados com sucesso e em alguns houve erros. Verifique se possuem um link válido e não existem outros links com os mesmos dados no Super Links.";
                $typeToast = "warning";
            }else if(!$saveSuccess && $error){
                $toast = "Os links não puderam ser importados. Verifique se possuem um link válido e não existem outros links com os mesmos dados no Super Links.";
                $typeToast = "error";
            }

            $timeToExpire = $this->timeToExpire;
            $urlView = $this->urlView;
            echo "<script>
                              document.cookie = \"toastSPL=$toast; expires=$timeToExpire; path=/\";
                              document.cookie = \"typeToastSPL=$typeToast; expires=$timeToExpire; path=/\";
                              document.location = '" . $urlView . "'
                            </script>";
            exit();

        }else {
            $allData = $hotLinksModel->getDataHotLinks();
            $this->pageData = array_merge($allData, $this->pageData);
            $this->render(SUPER_LINKS_VIEWS_PATH . '/import/hotLinksList.php');
        }
    }

    private function saveAndReturnIdGroup($groupName = 'sem categoria'){
        $groupModel = new SuperLinksGroupLinkModel();

        // verfico se a categoria não existe no superlinks e salvo
        $groupData = $groupModel->getGroupByGroupName($groupName);

        if($groupName && !$groupData && strtolower($groupName) != 'sem categoria'){
            $groupModel->setAttribute('groupName', $groupName);
            $idGroup = $groupModel->save();
            if(!$idGroup){
                $idGroup = null;
            }
        }else{
            $groupData = array_shift($groupData);
            $idGroup = isset($groupData->id)? $groupData->id : null;
        }

        return $idGroup;
    }

    public function isPluginActive(){
        $superLinksModel = new SuperLinksModel();
        $currentPage = $this->getCurrentPage();

        if(!$superLinksModel->isPluginActive() && ($currentPage != 'super_links_activation' && $currentPage != 'super_links')){
            $this->render(SUPER_LINKS_VIEWS_PATH . '/admin/notActivated.php');
            return false;
        }

        return true;
    }
}