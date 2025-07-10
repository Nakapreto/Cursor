<?php if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}

class SuperLinksFramework
{
    protected $model;
    private $hooks;
    private $filters;
    protected $scenario = null;
    protected $pageData = [];
    protected $exceptRules = '';

    protected function init($hooks = [], $filters = [])
    {
        $hooks = array_merge($hooks, $this->basicHooks());
        $this->setHooks($hooks);
        $this->load_hooks();

        $filters = array_merge($filters, $this->basicFilters());
        $this->setFilters($filters);
        $this->load_filters();
    }

    protected function load_hooks()
    {
        foreach ($this->hooks as $hook) {
            $priority = isset($hook['priority']) ? $hook['priority'] : 10;
            $accepted_args = isset($hook['accepted_args']) ? $hook['accepted_args'] : 1;
            add_action($hook['hook'], $hook['function'], $priority, $accepted_args);
        }
    }

    protected function load_filters()
    {
        foreach ($this->filters as $filter) {
            $priority = isset($filter['priority']) ? $filter['priority'] : 10;
            $accepted_args = isset($filter['accepted_args']) ? $filter['accepted_args'] : 1;
            add_action($filter['hook'], $filter['function'], $priority, $accepted_args);
        }
    }

    private function basicHooks()
    {
        return [
            ['hook' => 'plugins_loaded', 'function' => array($this, 'routes')]
        ];
    }

    private function basicFilters()
    {
        return [];
    }

    protected function setHooks($hooks)
    {
        $this->hooks = $hooks;
    }

    protected function setFilters($filters)
    {
        $this->filters = $filters;
    }

    protected function setModel($model)
    {
        $this->model = $model;
    }

    protected function loadModel()
    {
        if (is_null($this->model)) {
            return null;
        }

        return new $this->model;
    }

    protected function render($renderView)
    {
        require_once $renderView;
    }

    protected function setScenario($scenario)
    {
        $this->scenario = $scenario;
    }

    protected function getScenario()
    {
        return $this->scenario;
    }

    protected function getCurrentPage()
    {
        $uri = $_SERVER["REQUEST_URI"];
        $parts = parse_url($uri);

        if (isset($parts['query'])) {
            parse_str($parts['query'], $query);
            return isset($query['page']) ? $query['page'] : null;
        }

        return null;
    }

    protected function getCurrentUrl()
    {
        $uri = $_SERVER['REQUEST_URI'];

        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        $url = $protocol . $_SERVER['HTTP_HOST'] . $uri;
        $uri = parse_url($url, PHP_URL_PATH);
        $url = $protocol . $_SERVER['HTTP_HOST'] . $uri;

        return $url;
    }

    protected function validate($params = [])
    {
        if(empty($params)){
            return ['error' => TranslateHelper::getTranslate('Não foram passados parametros')];
        }

        // só aceita requisições ajax
        if(!isset($params['type']) || $params['type'] != 'ajax'){
            return ['error' => TranslateHelper::getTranslate('Não foi aceita a requisição')];
        }

        unset($params['type']);
        $excluiRules = [];

        if(isset($params['exceptRules']) && !empty($params['exceptRules'])){
            $exceptRules = explode(',',$params['exceptRules']);

            foreach($exceptRules as $rule){
                if(!empty($rule)){
                    $excluiRules[] = $rule;
                }
            }

            unset($params['exceptRules']);
        }

        $params = (object)$params;

        $response = [];



        foreach($params as $modelKey => $param){
            if(!isset($this->modelList()[$modelKey])){
                continue;
            }

            $model = new $modelKey();

            $model->setAttributes($param);
            $model->setExceptRules($excluiRules);

            $response = array_merge($response,$model->validate());

        }

        echo json_encode($response);
    }

    protected function delete($params = []){
        if(empty($params)){
            return ['error' => TranslateHelper::getTranslate('Não foram passados parametros')];
        }

        // só aceita requisições ajax
        if(!isset($params['type']) || $params['type'] != 'ajax'){
            return ['error' => TranslateHelper::getTranslate('Não foi aceita a requisição')];
        }

        unset($params['type']);

        $id = $params['id'];
        if(!$id){
            echo json_encode(['erro']);
            return;
        }

        $addLinkModel = new SuperLinksAddLinkModel();
        $addLinkModel->loadDataByID($id);

        //se for link do facebook exclui o outro tbm

        if($addLinkModel->getAttribute('redirectType') == 'facebook'){
            $internalKeyWord =  $addLinkModel->getAttribute('keyWord') . '/facebook';

            //pega os dados do link de afiliado corretos
            $internalLinkModel = new SuperLinksAddLinkModel();

            $internalLinkData = $internalLinkModel->getAllDataByParam($internalKeyWord,'keyWord');
            if($internalLinkData) {
                $internalLinkData = array_shift($internalLinkData);
                $internalLinkData = get_object_vars($internalLinkData);
                $idLink = $internalLinkData['id'];
                $internalLinkModel->loadDataByID($idLink);
                $internalLinkModel->delete();
            }
        }

        $addLinkModel->delete();
        $result = $addLinkModel->getLastQueryResult();

        $response = ['status' => true];

        if(isset($result['error']) && $result['error']){
            $response = ['status' => false];
        }

        echo json_encode($response);
    }

    protected function removeAffiliateLink($params = []){
        if(empty($params)){
            return ['error' => TranslateHelper::getTranslate('Não foram passados parametros')];
        }

        // só aceita requisições ajax
        if(!isset($params['type']) || $params['type'] != 'ajax'){
            return ['error' => TranslateHelper::getTranslate('Não foi aceita a requisição')];
        }

        unset($params['type']);

        $id = $params['id'];
        if(!$id){
            echo json_encode(['erro']);
            return;
        }

        $classModel = new SuperLinksAffiliateLinkModel();
        $classModel->loadDataByID($id);
        $classModel->delete();
        $result = $classModel->getLastQueryResult();

        $response = ['status' => true];

        if(isset($result['error']) && $result['error']){
            $response = ['status' => false];
        }

        echo json_encode($response);
    }

    protected function saveNewGroupLink($params = []){
        if(empty($params)){
            return ['error' => TranslateHelper::getTranslate('Não foram passados parametros')];
        }

        // só aceita requisições ajax
        if(!isset($params['type']) || $params['type'] != 'ajax'){
            return ['error' => TranslateHelper::getTranslate('Não foi aceita a requisição')];
        }

        unset($params['type']);

        $groupName = $params['groupName'];
        if(!$groupName){
            echo json_encode(['erro']);
            return;
        }

        $classModel = new SuperLinksGroupLinkModel();
        $classModel->setAttribute('groupName', $groupName);
        $idGroup = $classModel->save();

        if($idGroup) {
            $response = ['id' => $idGroup, 'status' => true];
        }else{
            $response = ['status' => false];
        }

        echo json_encode($response);
    }

    private function slugify($string){
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }

    protected function deleteGroup($params = []){
        if(empty($params)){
            return ['error' => TranslateHelper::getTranslate('Não foram passados parametros')];
        }

        // só aceita requisições ajax
        if(!isset($params['type']) || $params['type'] != 'ajax'){
            return ['error' => TranslateHelper::getTranslate('Não foi aceita a requisição')];
        }

        unset($params['type']);

        $id = $params['id'];
        if(!$id){
            echo json_encode(['erro']);
            return;
        }

        $groupLinkModel = new SuperLinksGroupLinkModel();
        $groupLinkModel->loadDataByID($id);
        $result = $groupLinkModel->delete();

        $response = ['status' => true,'result' => $result];

        if($result != 1){
            $response = ['status' => false,'result' => $result];
        }

        echo json_encode($response);
    }

    public function routes()
    {
        $routes = [
            ['function' => 'validate'],
            ['function' => 'delete'],
            ['function' => 'removeAffiliateLink'],
            ['function' => 'saveNewGroupLink'],
            ['function' => 'deleteGroup'],
        ];

        $url = $this->getCurrentUrl();

        foreach ($routes as $route) {
            if ($url == TEMPLATE_URL . '/' . $route['function']) {
                $route = (object)$route;
                $function = $route->function;
                $this->$function($_POST);
                exit;
            }
        }
    }

    public function getClientIp(){

        if(array_key_exists('HTTP_CLIENT_IP', $_SERVER)){
            return  $_SERVER["HTTP_CLIENT_IP"];
        }elseif(array_key_exists('REMOTE_ADDR', $_SERVER)){
            return $_SERVER["REMOTE_ADDR"];
        }elseif(array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        }

        return '';
    }

    private function modelList(){
        return [
            "SuperLinksAddLinkModel" => '',
            "SuperLinksAffiliateLinkModel" => '',
            "SuperLinksGroupLinkModel" => '',
            "SuperLinksImportHotLinksModel" => '',
            "SuperLinksImportModel" => '',
            "SuperLinksImportPrettyLinksModel" => '',
            "SuperLinksLinkMetricsModel" => '',
            "SuperLinksModel" => '',
            "SuperLinksPosts" => ''
        ];
    }
}