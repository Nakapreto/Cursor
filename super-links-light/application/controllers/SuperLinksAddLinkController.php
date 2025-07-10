<?php if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}

class SuperLinksAddLinkController extends SuperLinksFramework
{

    protected $addLinksModel;
    protected $groupLinkModel;
    protected $affiliateUrlModel;

    private $toast;
    private $timeToExpire;
    private $urlView;

    public function __construct($model = null, $hooks = [], $filters = [])
    {
        $this->toast = TranslateHelper::getTranslate('O link foi salvo com sucesso!');
        $this->timeToExpire = time() + 60;
        $this->urlView = TEMPLATE_URL . '/wp-admin/admin.php?page=super_links_list_view';

        $this->setScenario('super_links_add');

        $this->setModel($model);
        $this->addLinksModel = $this->loadModel();

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

    public function view()
    {
        if($this->isPluginActive()) {
            if(isset($_GET['idCategory']) && $_GET['idCategory']) {
                $idGroup = $_GET['idCategory'];

                if($idGroup == 'none'){
                    $idGroup = null;
                }

                $this->viewLinksByGroup($idGroup);
            }else {

                $this->pageData['pageTitle'] = TranslateHelper::getTranslate('Visualizar categorias de links existentes');

                $this->groupLinkModel = new SuperLinksGroupLinkModel();
                $allGroups['groups'] = $this->groupLinkModel
                    ->getAllData();

                $allGroups['existCategory'] = $this->existCategory($allGroups['groups']);

                $this->pageData = array_merge($allGroups, $this->pageData);

                $allLinks['links'] = $this->addLinksModel
                    ->getAllData();

                $allLinks['existLinkWithoutCategory'] = $this->existLinkwithoutCategory($allLinks['links']);
                $allLinks['existLinks'] = $this->existLinks($allLinks['links']);
                $allLinks['totalAccessThisLink'] = $this->totalAccessLink($allLinks['links']);

                $this->pageData = array_merge($allLinks, $this->pageData);


                $this->render(SUPER_LINKS_VIEWS_PATH . '/links/listView.php');
            }
        }
    }

    private function existLinkwithoutCategory($links = []){
        foreach($links as $link){
            if(!$link->idGroup){
                return true;
            }
        }

        return false;
    }

    private function existCategory($groups = []){
        if($groups){
            return true;
        }

        return false;
    }

    private function existLinks($links = []){
        if($links){
            return true;
        }

        return false;
    }

    private function totalAccessLink($links = []){
        $totalAccess = [];
        foreach($links as $link){
            $affiliate = new SuperLinksAffiliateLinkModel();
            $affiliateData = $affiliate->getAllDataByParam($link->id,'idLink');

            foreach($affiliateData as $affiliateDatum){
                $metrics = new SuperLinksLinkMetricsModel();
                $metricsData = $metrics->getAllDataByParam($affiliateDatum->id,'idAffiliateLink');
                if($metricsData){
                    $metricsData = array_shift($metricsData);
                    $access = 0;

                    if($metricsData){
                        $access = $metricsData->accessTotal;
                    }

                    if(isset($totalAccess[$link->id])) {
                        $totalAccess[$link->id] = $totalAccess[$link->id] + $access;
                    }else{
                        $totalAccess[$link->id] = $access;
                    }
                }else{
                    $totalAccess[$link->id] = 0;
                }
            }
        }

        return $totalAccess;
    }

    private function viewLinksByGroup($idGroup = null){
        $groupName = "Sem categoria";

        if(!is_null($idGroup)){
            $this->groupLinkModel = new SuperLinksGroupLinkModel();
            $this->groupLinkModel->loadDataByID($idGroup);
            $groupName = $this->groupLinkModel->getAttribute('groupName');
        }

        $this->pageData['pageTitle'] = TranslateHelper::getTranslate('Visualizar links existentes da Categoria: ') . "<strong>" . $groupName . "</strong>";

        $addLinksModel = new SuperLinksAddLinkModel();
        $this->addLinksModel = $addLinksModel;
        $linksByGroup = $addLinksModel->getLinksByIDGroup($idGroup);

        $allLinks['links'] = $linksByGroup;
        $allLinks['existLinks'] = $this->existLinks($allLinks['links']);
        $allLinks['totalAccessThisLink'] = $this->totalAccessLink($allLinks['links']);

        $this->pageData = array_merge($allLinks, $this->pageData);

        $this->render(SUPER_LINKS_VIEWS_PATH . '/links/listViewLinks.php');
    }

    public function viewLink()
    {
        if($this->isPluginActive()) {
            $id = $_GET['id'];
            $this->pageData['pageTitle'] = TranslateHelper::getTranslate('Desculpe...');

            if ($id) {
                $addLinkModel = new SuperLinksAddLinkModel();
                $addLinkModel->loadDataByID($id);

                if (!empty($addLinkModel->getAttributes())) {
                    $this->pageData['pageTitle'] = TranslateHelper::getTranslate('Dados do link');

                    $this->pageData = array_merge($addLinkModel->getAttributes(), $this->pageData);

                    $affiliateLinks = new SuperLinksAffiliateLinkModel();

                    $idLink = $addLinkModel->getAttribute('id');

                    if($addLinkModel->getAttribute('redirectType') == 'facebook'){
                        $internalKeyWord =  $addLinkModel->getAttribute('keyWord') . '/facebook';

                        //pega os dados do link de afiliado corretos
                        $internalLinkModel = new SuperLinksAddLinkModel();

                        $internalLinkData = $internalLinkModel->getAllDataByParam($internalKeyWord,'keyWord');
                        if($internalLinkData) {
                            $internalLinkData = array_shift($internalLinkData);
                            $internalLinkData = get_object_vars($internalLinkData);
                            $idLink = $internalLinkData['id'];
                        }
                    }

                    $affiliateData = $affiliateLinks->getAllDataByParam(
                        $idLink,
                        'idLink'
                    );

                    $pageDataAffiliate = [];

                    foreach ($affiliateData as $affiliateDatum) {
                        $metricsModel = new SuperLinksLinkMetricsModel();
                        $metricsData = $metricsModel->getAllDataByParam($affiliateDatum->id, 'idAffiliateLink');
                        $pageDataAffiliate[] = ['affiliateData' => $affiliateDatum, 'metrics' => $metricsData];
                    }

                    $this->pageData = array_merge(['affiliate' => $pageDataAffiliate], $this->pageData);
                }
            }
            $this->render(SUPER_LINKS_VIEWS_PATH . '/links/viewLink.php');
        }
    }

    public function create()
    {
        $savedLink = false;
        if($this->isPluginActive()) {
            $this->pageData['pageTitle'] = TranslateHelper::getTranslate('Criar novo link');
            $this->groupLinkModel = new SuperLinksGroupLinkModel();
            $this->affiliateUrlModel = new SuperLinksAffiliateLinkModel();

            $this->addLinksModel->setAttribute('redirectDelay', '0');

            if (isset($_POST['scenario'])) {
                $addLinksModel = $this->addLinksModel;
                $affiliateUrlModel = $this->affiliateUrlModel;
                $groupLinkModel = $this->groupLinkModel;

                $addLinksModel->setAttributes($_POST[$addLinksModel->getModelName()]);
                $addLinksModel->setAttribute('createdAt', DateHelper::agora());

                $keyWord = $addLinksModel->getAttribute('keyWord');
                $keyWord = strtolower($keyWord);
                $addLinksModel->setAttribute('keyWord', $keyWord);

                if($_POST[$groupLinkModel->getModelName()]['id']){
                    $addLinksModel->setAttribute('idGroup', $_POST[$groupLinkModel->getModelName()]['id']);
                }else{
                    $addLinksModel->setNullToAttribute('idGroup');
                }

                $idAddLinks = $addLinksModel->save();

                if ($idAddLinks) {
                    $redirectType = $addLinksModel->getAttribute('redirectType');

                    if($redirectType != 'wpp_tlg'){
                        $redirectType = 'php';
                    }

                    SuperLinksAddLinkModel::saveDependencies($idAddLinks, $_POST, $redirectType);
                    $savedLink = true;
                }
            }

            if($savedLink){
                $toast = $this->toast;
                $timeToExpire = $this->timeToExpire;

                $groups = $this->groupLinkModel
                    ->getAllData();

                if($addLinksModel->getAttribute('idGroup')) {
                    $urlView = $this->urlView . '&idCategory=' . $addLinksModel->getAttribute('idGroup');
                }else if($this->existCategory($groups) && !$addLinksModel->getAttribute('idGroup')){
                    $urlView = $this->urlView . '&idCategory=none';
                }else{
                    $urlView = $this->urlView;
                }

                echo "<script>
                          document.cookie = \"toastSPL=$toast; expires=$timeToExpire; path=/\";
                          document.location = '".$urlView."'
                      </script>";
                exit();
            }

            $this->render(SUPER_LINKS_VIEWS_PATH . '/links/index.php');
        }
    }

    public function update()
    {
        $savedLink = false;
        if($this->isPluginActive()) {
            $id = $_GET['id'];
            $this->pageData['pageTitle'] = TranslateHelper::getTranslate('Desculpe...');

            if ($id) {
                $this->pageData['pageTitle'] = TranslateHelper::getTranslate('Editar link');
                $this->groupLinkModel = new SuperLinksGroupLinkModel();
                $this->affiliateUrlModel = new SuperLinksAffiliateLinkModel();

                $this->addLinksModel->loadDataByID($id);

                $this->addLinksModel->setAttribute('redirectDelay', '0');

                $this->addLinksModel->setIsNewRecord(false);

                if (!empty($this->addLinksModel->getAttributes())) {

                    $idLink = $this->addLinksModel->getAttribute('id');
                    $idGroup = $this->addLinksModel->getAttribute('idGroup');

                    $this->groupLinkModel->setAttribute('id', $idGroup);

                    $dataAffiliate = $this->affiliateUrlModel->getAllDataByParam(
                        $idLink,
                        'idLink'
                    );

                    if ($dataAffiliate) {
                        $dataAffiliateId = $dataAffiliate;
                        $dataAffiliateId = array_shift($dataAffiliateId);
                    }

                    if(isset($dataAffiliateId->id)) {
                        $this->affiliateUrlModel->loadDataByID($dataAffiliateId->id);

                        $affiliateUrl = [];
                        foreach ($dataAffiliate as $affiliate) {
                            $urlSemEspacos = $affiliate->affiliateUrl;
                            $urlSemEspacos = str_replace(' ', "%20", $urlSemEspacos);
                            $affiliateUrl[$affiliate->id] = $urlSemEspacos;
                        }

                        $this->affiliateUrlModel->setAttribute('affiliateUrl', $affiliateUrl);
                    }
                }

                if (isset($_POST['scenario'])) {
                    $addLinksModel = $this->addLinksModel;
                    $groupLinkModel = $this->groupLinkModel;

                    $linkBeforeUpdate = new SuperLinksAddLinkModel();
                    $linkBeforeUpdate->loadDataByID($id);

                    $addLinksModel->setAttributes($_POST[$addLinksModel->getModelName()]);
                    $addLinksModel->setAttribute('updatedAt', DateHelper::agora());

                    $keyWord = $addLinksModel->getAttribute('keyWord');
                    $keyWord = strtolower($keyWord);
                    $addLinksModel->setAttribute('keyWord', $keyWord);

                    $addLinksModel->setAttribute('redirectDelay', '0');

                    $addLinksModel->setAttribute('abLastTest', '0');

                    if($_POST[$groupLinkModel->getModelName()]['id']){
                        $addLinksModel->setAttribute('idGroup', $_POST[$groupLinkModel->getModelName()]['id']);
                    }else{
                        $addLinksModel->setNullToAttribute('idGroup');
                    }

                    $isSavedLink = $addLinksModel->save();

                    if ($isSavedLink) {
                        SuperLinksAddLinkModel::updateDependencies($addLinksModel, $_POST, 'php');
                        $savedLink = true;
                    }
                }
            }

            if($savedLink){
                $toast = TranslateHelper::getTranslate('O link foi atualizado com sucesso!');
                $timeToExpire = $this->timeToExpire;

                $groups = $this->groupLinkModel
                    ->getAllData();

                if($addLinksModel->getAttribute('idGroup')) {
                    $urlView = $this->urlView . '&idCategory=' . $addLinksModel->getAttribute('idGroup');
                }else if($this->existCategory($groups) && !$addLinksModel->getAttribute('idGroup')){
                    $urlView = $this->urlView . '&idCategory=none';
                }else{
                    $urlView = $this->urlView;
                }

                echo "<script>
                          document.cookie = \"toastSPL=$toast; expires=$timeToExpire; path=/\";
                          document.location = '".$urlView."'
                      </script>";
                exit();
            }

            $this->render(SUPER_LINKS_VIEWS_PATH . '/links/update.php');
        }
    }

    public function cloneLink()
    {
        $savedLink = false;
        if($this->isPluginActive()) {
            $id = $_GET['id'];
            $this->pageData['pageTitle'] = TranslateHelper::getTranslate('Desculpe...');

            if ($id) {
                $this->pageData['pageTitle'] = TranslateHelper::getTranslate('Duplicar este link');
                $this->groupLinkModel = new SuperLinksGroupLinkModel();
                $this->affiliateUrlModel = new SuperLinksAffiliateLinkModel();

                $this->addLinksModel->loadDataByID($id);

                $this->addLinksModel->setAttribute('redirectDelay', '0');

                if (!empty($this->addLinksModel->getAttributes())) {

                    $idLink = $this->addLinksModel->getAttribute('id');
                    $idGroup = $this->addLinksModel->getAttribute('idGroup');

                    $this->groupLinkModel->setAttribute('id', $idGroup);

                    $dataAffiliate = $this->affiliateUrlModel->getAllDataByParam(
                        $idLink,
                        'idLink'
                    );

                    if ($dataAffiliate) {
                        $dataAffiliateId = $dataAffiliate;
                        $dataAffiliateId = array_shift($dataAffiliateId);
                    }

                    $this->affiliateUrlModel->loadDataByID($dataAffiliateId->id);

                    $affiliateUrl = [];
                    foreach ($dataAffiliate as $affiliate) {
                        $urlSemEspacos = $affiliate->affiliateUrl;
                        $urlSemEspacos = str_replace(' ', "%20", $urlSemEspacos);
                        $affiliateUrl[$affiliate->id] = $urlSemEspacos;
                    }

                    $this->affiliateUrlModel->setAttribute('affiliateUrl', $affiliateUrl);

                    $this->addLinksModel->setAttribute('linkName', '');
                    $this->addLinksModel->setAttribute('keyWord', '');
                }

                if (isset($_POST['scenario'])) {
                    $addLinksModel = new SuperLinksAddLinkModel();
                    $groupLinkModel = new SuperLinksGroupLinkModel();

                    $addLinksModel->setAttributes($_POST[$addLinksModel->getModelName()]);
                    $addLinksModel->setAttribute('createdAt', DateHelper::agora());

                    $keyWord = $addLinksModel->getAttribute('keyWord');
                    $keyWord = strtolower($keyWord);
                    $addLinksModel->setAttribute('keyWord', $keyWord);

                    $addLinksModel->setAttribute('redirectDelay', '0');

                    $addLinksModel->setAttribute('abLastTest', '0');

                    if($_POST[$groupLinkModel->getModelName()]['id']){
                        $addLinksModel->setAttribute('idGroup', $_POST[$groupLinkModel->getModelName()]['id']);
                    }else{
                        $addLinksModel->setNullToAttribute('idGroup');
                    }

                    $idAddLinks = $addLinksModel->save();

                    if ($idAddLinks) {
                        SuperLinksAddLinkModel::saveDependencies($idAddLinks, $_POST, 'php');
                        $savedLink = true;
                    }
                }
            }

            if($savedLink){
                $toast = $this->toast;
                $timeToExpire = $this->timeToExpire;

                $groups = $this->groupLinkModel
                    ->getAllData();

                if($addLinksModel->getAttribute('idGroup')) {
                    $urlView = $this->urlView . '&idCategory=' . $addLinksModel->getAttribute('idGroup');
                }else if($this->existCategory($groups) && !$addLinksModel->getAttribute('idGroup')){
                    $urlView = $this->urlView . '&idCategory=none';
                }else{
                    $urlView = $this->urlView;
                }

                echo "<script>
                          document.cookie = \"toastSPL=$toast; expires=$timeToExpire; path=/\";
                          document.location = '".$urlView."'
                      </script>";
                exit();
            }
            $this->render(SUPER_LINKS_VIEWS_PATH . '/links/clone.php');
        }
    }

    public function editGroup()
    {
        if($this->isPluginActive()) {
            $id = $_GET['id'];
            $this->pageData['pageTitle'] = TranslateHelper::getTranslate('Desculpe...');

            if ($id) {
                $this->pageData['pageTitle'] = TranslateHelper::getTranslate('Editar a Categoria');
                $this->groupLinkModel = new SuperLinksGroupLinkModel();
                $this->groupLinkModel->setIsNewRecord(false);

                $this->groupLinkModel->loadDataByID($id);

                if (isset($_POST['scenario'])) {
                    $groupLinkModel = $this->groupLinkModel;
                    $groupLinkModel->setAttributes($_POST[$groupLinkModel->getModelName()]);
                    $idGroupModel = $groupLinkModel->save();

                    if ($idGroupModel) {
                        $toast = TranslateHelper::getTranslate('A categoria foi atualizada com sucesso!');
                        $timeToExpire = $this->timeToExpire;
                        $urlView = $this->urlView;
                        echo "<script>
                          document.cookie = \"toastSPL=$toast; expires=$timeToExpire; path=/\";
                          document.location = '" . $urlView . "'
                        </script>";
                        exit();
                    }else{
                        $toast = TranslateHelper::getTranslate('Não houve alteração na categoria!');
                        $timeToExpire = $this->timeToExpire;
                        $urlView = $this->urlView;
                        echo "<script>
                          document.cookie = \"toastSPL=$toast; expires=$timeToExpire; path=/\";
                          document.location = '" . $urlView . "'
                        </script>";
                        exit();
                    }
                }
            }

            $this->render(SUPER_LINKS_VIEWS_PATH . '/links/editGroup.php');
        }
    }

    public function clonePages()
    {
        $this->pageData['pageTitle'] = TranslateHelper::getTranslate('Clonar Páginas');

        $this->render(SUPER_LINKS_VIEWS_PATH . '/links/clonePageView.php');
    }

    public function popupsSuperLinks()
    {
        $this->pageData['pageTitle'] = TranslateHelper::getTranslate('Super Links Popups');

        $this->render(SUPER_LINKS_VIEWS_PATH . '/links/popupsView.php');
    }

    public function isPluginActive(){
        $superLinksModel = new SuperLinksModel();
        $currentPage = $this->getCurrentPage();

        if(!$superLinksModel->isPluginActive() && ($currentPage != 'super_links_activation' && $currentPage != 'super_links')){
            $this->render(SUPER_LINKS_VIEWS_PATH . '/admin/index.php');
            return false;
        }

        return true;
    }
}