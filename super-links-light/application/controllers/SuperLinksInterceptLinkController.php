<?php if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}

class SuperLinksInterceptLinkController extends SuperLinksFramework
{

    protected $interceptLink;

    private $expireTimeCache = 3;

    private $expireDaysToUniqueAccess = 6;

    private $isUniqueAccess = false;

    public function __construct($model = null, $hooks = [], $filters = [])
    {
        $this->setScenario('super_links_intercept');

        $this->setModel($model);
        $this->interceptLink = $this->loadModel();

        $this->init($hooks, $filters);
    }

    public function init($hooks = [], $filters = [])
    {
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

    public function index(){
        if(get_option('enable_redis_superLinks')) {
            try {
                if (extension_loaded('redis')) {
                    $redis = new Redis();
                    $redis->connect('localhost', 6379);
                    $redis->flushAll();
                }
            } catch (Throwable $t) {
                update_option('enable_redis_superLinks', false);
                wp_cache_delete('alloptions', 'options');
            } catch (Exception $e) {
                update_option('enable_redis_superLinks', false);
                wp_cache_delete('alloptions', 'options');
            }
        }

        date_default_timezone_set("America/Sao_Paulo");

        $url = $this->getCurrentUrl();

        $idSuperLink = $this->getIDSuperLink($url);

        if(!$idSuperLink) {
            return;
        }

        $addLinkModel = new SuperLinksAddLinkModel();
        $affiliateLinkModel = new SuperLinksAffiliateLinkModel();

        $addLinkModel->loadDataByID($idSuperLink);

        if(!$addLinkModel->getAttributes()){
            return;
        }

        $abLastTest = $addLinkModel->getAttribute('abLastTest');

        if(!$abLastTest){
            $abLastTest = 0;
        }

        $atualTestAb = $abLastTest;

        $lastCacheTime = date("Y-m-d H:i");
        $ip = $this->getClientIp();

        if(!isset($_COOKIE['ipClient'])) {
            setcookie('ipClient', $ip, time() + (86400 * $this->expireDaysToUniqueAccess), "/");
            $this->isUniqueAccess = true;
        }

        // Só salva o teste atual após a expiração do cache
        if((isset($_COOKIE['ipClient']) && !isset($_COOKIE['timeIpClient']))){
            $atualTestAb = $this->getAtualTestAb($affiliateLinkModel, $idSuperLink, $abLastTest);
            $addLinkModel->updateLastTestAb($atualTestAb);
        }

        $affiliateLinkData = $affiliateLinkModel->getAllDataByParam($idSuperLink, 'idLink', 'ORDER BY id ASC', 'limit 1', 'OFFSET ' . $atualTestAb);
        $affiliateLinkData = array_shift($affiliateLinkData);
        $affiliateUrl = trim($affiliateLinkData->affiliateUrl);

        // Atualiza métricas
        if(!isset($_COOKIE['timeIpClient'])){
            $metricsModel = new SuperLinksLinkMetricsModel();
            $metricsModel->updateMetricsByIDLink($affiliateLinkData->id, $this->isUniqueAccess, false);
            setcookie('timeIpClient',$lastCacheTime,time() + $this->expireTimeCache,"/");
        }

        $this->doPhpRedirect($affiliateUrl);

        exit;
    }

    private function getIDSuperLink($url = ''){
        if(empty($url)){
            return false;
        }

        $superLinksModel = new SuperLinksModel();

        if(!$superLinksModel->isPluginActive()){
            return false;
        }

        $addLinkModel = new SuperLinksAddLinkModel();
        $dataLinks = $addLinkModel->getAllDataByParam('enabled','statusLink');

        foreach($dataLinks as $dataLink){
            if(($url == TEMPLATE_URL . '/' . $dataLink->keyWord) || ($url == TEMPLATE_URL . '/' . $dataLink->keyWord.'/')){
                return $dataLink->id;
            }
        }

        return false;
    }

    private function doPhpRedirect($affiliateUrl = ''){
        if(empty($affiliateUrl)){
            return;
        }

        header('Cache-Control: max-age='.$this->expireTimeCache);
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        header('Location: ' . $affiliateUrl);
    }

    private function getAtualTestAb($affiliateLinkModel = null, $idSuperLink = null, $abLastTest = 0){
        if(is_null($affiliateLinkModel) || is_null($idSuperLink)){
            return 0;
        }

        $affiliateLinksBySuperLinkID = $affiliateLinkModel->getAllDataByParam($idSuperLink, 'idLink');
        $atualAbTest = $abLastTest + 1;

        if($atualAbTest == count($affiliateLinksBySuperLinkID)){
            $atualAbTest = 0;
        }

        return $atualAbTest;
    }

}