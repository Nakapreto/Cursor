<?php if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}

class SuperLinksAutomaticLinkController extends SuperLinksFramework
{


    public function __construct($model = null, $hooks = [], $filters = [])
    {
        $this->setScenario('super_links_automatic_link');

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
        $this->pageData['pageTitle'] = TranslateHelper::getTranslate('Visualizar links inteligentes');

        $this->render(SUPER_LINKS_VIEWS_PATH . '/automaticLinks/listView.php');
    }

    public function viewCookies()
    {
        $this->pageData['pageTitle'] = TranslateHelper::getTranslate('Ativar Cookies');

        $this->render(SUPER_LINKS_VIEWS_PATH . '/automaticLinks/cookies.php');
    }
}