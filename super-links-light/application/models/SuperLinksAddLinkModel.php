<?php
if(!defined('ABSPATH'))
    die('You are not allowed to call this page directly.');

class SuperLinksAddLinkModel extends SuperLinksCoreModel {

    private $defaultSecondsToRedirectDelay = 0;

    public function __construct() {
        parent::__construct();

        $this->setAttributesKeys(
            $this->attributeLabels()
        );

        $this->setTableName(
            $this->tables['spl_link']
        );
    }

    public function getModelName(){
        return 'SuperLinksAddLinkModel';
    }

    public function rules()
    {
        return [
            [
                'linkName, keyWord, redirectType', 'required'
            ],
            [
                'keyWord', 'uniqueLink'
            ]
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id' => TranslateHelper::getTranslate('ID Link'),
            'idGroup' => TranslateHelper::getTranslate('ID do grupo'),
            'linkName' => TranslateHelper::getTranslate('Nome do link'),
            'description' => '',
            'keyWord' => TranslateHelper::getTranslate('Endereço do link'),
            'redirectType' => TranslateHelper::getTranslate('Tipo do redirecionamento'),
            'redirectDelay' => TranslateHelper::getTranslate('Redirecionar após'),
            'statusLink' => TranslateHelper::getTranslate('Status'),
            'abLastTest' => TranslateHelper::getTranslate('Último teste A/B'),
            'createdAt' => TranslateHelper::getTranslate('Criado em:'),
            'updatedAt' => TranslateHelper::getTranslate('Atualizado em:'),
            'redirectBtn' => TranslateHelper::getTranslate('Url do Redirect no botão voltar'),
            'enableRedirectJavascript' => TranslateHelper::getTranslate('Habilitar redirecionamento javascript caso a página não possa ser camuflada?'),
        );
    }

    public function getOptionsRedirectImport($selected = 'html'){
        $values = [
//            ['selected' => false, 'text' => TranslateHelper::getTranslate('Redirecionador (Html) [Pro]'), 'val' => ''],
//            ['selected' => false, 'text' => TranslateHelper::getTranslate('Redirecionador (Javascript) [Pro]'), 'val' => ''],
            ['selected' => false, 'text' => TranslateHelper::getTranslate('Redirecionador (PHP)'), 'val' => 'php'],
//            ['selected' => false, 'text' => TranslateHelper::getTranslate('Camuflador [Pro]'), 'val' => '']
        ];

        foreach($values as $key => $value){
            $selectedValue = false;
            if($value['val'] == $selected){
                $selectedValue = true;
            }
            $values[$key] = ['selected' => $selectedValue, 'text' => $value['text'] , 'val' => $value['val']];
        }

        return $values;
    }

    public function getOptionsRedirectLight(){
        return [
            ['selected' => true, 'text' => TranslateHelper::getTranslate('Redirecionador (PHP)'), 'val' => 'php'],
            ['selected' => false, 'text' => TranslateHelper::getTranslate('Link para Whatsapp e Telegram'), 'val' => 'wpp_tlg'],
            ['selected' => false, 'text' => TranslateHelper::getTranslate('Redirecionador (Html) [Pro]'), 'val' => ''],
            ['selected' => false, 'text' => TranslateHelper::getTranslate('Redirecionador (Javascript) [Pro]'), 'val' => ''],
            ['selected' => false, 'text' => TranslateHelper::getTranslate('Camuflador [Pro]'), 'val' => ''],
            ['selected' => false, 'text' => TranslateHelper::getTranslate('Link especial para o Facebook [Pro]'), 'val' => ''],
        ];
    }

    private function helpTextRedirect(){
        return array(
            'html' => 'Redireciona o usuário para seus links de afiliado, podendo ser rastreado.',
            'wpp_tlg' => 'Redireciona o usuário para seus links de Whatsapp ou Telegram. Não é possível fazer rastreamento (Somente a versão pro é permitido o rastreio).',
            'javascript' => 'Redireciona o usuário para seus links de afiliado, podendo ser rastreado.',
            'php' => 'Redireciona o usuário para seus links de afiliado. Não é possível fazer rastreamento.',
            'camuflador' => 'Exibe a página referente aos seus links de afiliado sem sair do seu site',
            'facebook' => 'Link especial para realizar anúncios ou compartilhamentos no Facebook',
        );
    }

    public function getHelpTextRedirect($redirectType = ''){
        return isset($this->helpTextRedirect()[$redirectType]) ? $this->helpTextRedirect()[$redirectType] : '';
    }

    /**
     * @param string $keyword
     * @return array|bool|object|null
     */
    public function getLinkByKeyword($keyWord = ''){
        if(empty($keyWord)){
            return [];
        }

        return $this->getAllDataByParam($keyWord,'keyWord');
    }

    public function uniqueLink($attribute = ''){
        $attributeVal = $this->getAttribute($attribute);
        return (empty($this->getLinkByKeyword($attributeVal)) || $this->isTheSameLink())? true : false;
    }

    private function isTheSameLink(){

        $keyWord = $this->getAttribute('keyWord');
        $id = $this->getAttribute('id');

        if(!$id) {
            return false;
        }

        $addLinkData = $this->getAllDataByParam($id,'id');
        if($addLinkData) {
            $addLinkData = array_shift($addLinkData);
        }

        if(isset($addLinkData->keyWord) && (trim($addLinkData->keyWord) == trim($keyWord))){
            return true;
        }

        return false;
    }

    public function updateLastTestAb($atualTestAb = 0){
        $this->setIsNewRecord(false);
        $this->setAttribute('abLastTest', $atualTestAb);
        $this->setExceptRules(['uniqueLink']);
        $this->save();
    }

    public function getDefaultRedirectDelay(){
        $redirectDelay = $this->getAttribute('redirectDelay');

        if($redirectDelay < $this->defaultSecondsToRedirectDelay) {
            return $this->defaultSecondsToRedirectDelay;
        }

        return $redirectDelay;
    }


    public static function saveDependencies($idLink = null, $post = [], $redirectType = ''){
        if(is_null($idLink) || !$post || !$redirectType){
            return false;
        }

        $affiliateUrlModel = new SuperLinksAffiliateLinkModel();

        foreach ($post[$affiliateUrlModel->getModelName()]['affiliateUrl'] as $value) {
            $urlSemEspacos = $value;
            $urlSemEspacos = str_replace(' ', "%20", $urlSemEspacos);
            $affiliateUrlModel->setAttribute('affiliateUrl', $urlSemEspacos);
            $affiliateUrlModel->setAttribute('createdAt', DateHelper::agora());
            $affiliateUrlModel->setAttribute('idLink', $idLink);
            $affiliateUrlModel->save();
        }

        return true;
    }


    public static function updateDependencies($addLinksModel = null, $post = [], $redirectType = ''){
        if(is_null($addLinksModel) || !$post || !$redirectType){
            return false;
        }

        $affiliateUrlModel = new SuperLinksAffiliateLinkModel();

        $idSuperLink = $addLinksModel->getAttribute('id');

        if(isset($_POST[$affiliateUrlModel->getModelName()]['affiliateUrl']) && $_POST[$affiliateUrlModel->getModelName()]['affiliateUrl']) {
            foreach ($_POST[$affiliateUrlModel->getModelName()]['affiliateUrl'] as $value) {
                $updateAffiliateLink = new SuperLinksAffiliateLinkModel();

                $urlSemEspacos = $value;
                $urlSemEspacos = str_replace(' ', "%20", $urlSemEspacos);
                $updateAffiliateLink->setAttribute('affiliateUrl', $urlSemEspacos);

                $updateAffiliateLink->setAttribute('idLink', $idSuperLink);

                $updateAffiliateLink->updateAffiliateLink();
            }
        }
    }


    public function getLinksByIDGroup($idGroup = null){
        if(is_null($idGroup)){
            global $wpdb;
            $tableName = $this->getTableName();

            if(is_null($tableName)){
                return [];
            }

            return $wpdb->get_results($wpdb->prepare("SELECT * FROM $tableName where idGroup IS NULL and (redirectType = '%s'  OR  redirectType = '%s') ", 'php', 'wpp_tlg'));
        }

        return $this->getAllDataByParam($idGroup,'idGroup');
    }

    public function getAllData(){
        $tableName = $this->getTableName();

        if(is_null($tableName)){
            return [];
        }

        global $wpdb;

        return $wpdb->get_results($wpdb->prepare(" SELECT * FROM $tableName where redirectType = '%s' OR  redirectType = '%s' ORDER BY id ASC", 'php', 'wpp_tlg'));
    }

    public function getAllDataByParam($val = null, $param = '', $order = '', $limit = '', $offset = ''){
        global $wpdb;
        $tableName = $this->getTableName();

        if(is_null($tableName) || is_null($val) || empty($param)){
            return [];
        }

        $tmp = '%d';

        if(is_string($val)){
            $tmp = '%s';
        }

        return $wpdb->get_results($wpdb->prepare("SELECT * FROM $tableName where $param = $tmp and (redirectType = '%s' OR  redirectType = '%s') $order $limit $offset", $val, 'php', 'wpp_tlg'));
    }
}