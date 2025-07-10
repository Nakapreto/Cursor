<?php
if(!defined('ABSPATH'))
    die('You are not allowed to call this page directly.');

class SuperLinksCoreModel {

    protected $tables= [];
    protected $attributes = [];
    protected $newRecord = true;
    private $tableName = null;
    private $attributesKeys = [];
    protected $errors = [];
    protected $exceptRules = [];
    private $rules = [];
    private $lastQueryResult = [];


    public function __construct() {
        global $wpdb;

        $this->tables['spl_group'] = $wpdb->prefix . "superLinks_GroupLink";
        $this->tables['spl_link'] = $wpdb->prefix . "superLinks_Link";
        $this->tables['spl_affiliateLink'] = $wpdb->prefix . "superLinks_AffiliateLink";
        $this->tables['spl_linkMetrics'] = $wpdb->prefix . "superLinks_LinkMetrics";
        $this->tables['spl_importLinks'] = $wpdb->prefix . "superLinks_ImportLinks";
    }

    protected function setTableName($tableName){
        $this->tableName = trim($tableName);
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setAttributes($values)
    {
        if(!is_array($values))
            return;

        foreach($values as $name => $value)
        {
            if(!empty($value) || is_bool($value)){
                $this->setAttribute($name,$value);
            }
        }
    }

    public function setAttribute($attributeName = '', $value = '')
    {
        if(empty($attributeName))
            return;

        if(!is_array($value)){
            if(is_null($value)){
                $value = '';
            }
            $value = trim($value);
        }

        $this->attributes[$attributeName] = $value;
    }

    public function setNullToAttribute($attributeName = '')
    {
        if(empty($attributeName))
            return;

        $this->attributes[$attributeName] = null;
    }

    public function removeAttribute($attributeName = ''){
        if(empty($attributeName))
            return;

        if(isset($this->attributes[$attributeName])){
            unset($this->attributes[$attributeName]);
        }
    }

    public function getAttribute($name)
    {
        if(isset($this->attributes[$name])){
            return $this->attributes[$name];
        }
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function loadDataByID($id = null){
        $tableName = $this->getTableName();

        if(is_null($tableName) || is_null($id)){
            return [];
        }

        global $wpdb;

        $dataArray = [];
        $dataObject = $wpdb->get_row( $wpdb->prepare(" SELECT * FROM $tableName where id = %d ", $id));

        if($dataObject) {
            $dataArray = get_object_vars($dataObject);
        }

        $this->setAttributes($dataArray);
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

        return $wpdb->get_results($wpdb->prepare("SELECT * FROM $tableName where $param = $tmp $order $limit $offset", $val));
    }

    public function getAllData(){
        $tableName = $this->getTableName();

        if(is_null($tableName)){
            return [];
        }

        global $wpdb;

        return $wpdb->get_results(" SELECT * FROM $tableName ORDER BY id ASC");
    }


    public function setAttributesKeys($attributesLabel = []){
        $this->attributesKeys = array_keys($attributesLabel);
    }

    public function save()
    {
        if(is_null($this->getTableName())){
            return false;
        }

        if($this->isValid()) {
            return $this->getIsNewRecord() ? $this->insert() : $this->update();
        }

        return false;
    }

    public function isValid(){
       return empty($this->validate())? true : false;
    }

    public function validate(){

        foreach($this->getRules() as $rule){
            $attributes = explode(',', $rule[0]);
            $function = trim($rule[1]);

            foreach($attributes as $attribute){
                if (!$this->$function(trim($attribute))) {
                    $this->setErrors(trim($attribute), $function);
                }
            }
        }

        return $this->errors;
    }

    private function setErrors($attribute = '', $rule = ''){
        $this->errors[] = [
            'model' => trim($this->getModelName()),
            'attribute' => trim($attribute),
            'rule' => trim($rule)
        ];
    }

    public function getIsNewRecord()
    {
        return $this->newRecord;
    }

    public function setIsNewRecord($value)
    {
        $this->newRecord = $value;
    }

    private function insert()
    {
        global $wpdb;
        $attributes = $this->attributes;
        $wpdb->insert(
            $this->getTableName(),
            $attributes
        );

        $this->saveQueryResult();

        return $wpdb->insert_id;
    }


    private function update()
    {
        global $wpdb;
        $attributes = $this->attributes;

        $result = $wpdb->update(
            $this->getTableName(),
            $attributes,
            ['id' => $this->getAttribute('id')]
        );

        $this->saveQueryResult();

        return $result;
    }

    private function saveQueryResult(){
        global $wpdb;

        $error = $wpdb->last_result;
        $query = $wpdb->last_query;
        $this->lastQueryResult = [
          'error' => $error,
          'query' => $query
        ];
    }

    public function getLastQueryResult(){
        return $this->lastQueryResult;
    }


    public function delete()
    {
        global $wpdb;
        $id = $this->getAttribute('id');

        $result = $wpdb->delete(
            $this->getTableName(),
            ['id' => $id]
        );

        $this->saveQueryResult();

        return $result;
    }

    protected function required($attribute = ''){
        $attribute = $this->getAttribute($attribute);

        if(!is_array($attribute) && !$attribute){
            return false;
        }

        if(is_array($attribute)){
            foreach($attribute as $attr){
                if(!$attr){
                    return false;
                }
            }
        }

        return true;
    }

    public function setExceptRules($rules = []){
        $this->exceptRules = $rules;
    }

    private function removeExceptedRules(){
        if(!$this->exceptRules){
            return false;
        }

        foreach($this->exceptRules as $exceptRule){
            foreach($this->rules as $key => $rule) {
                if (in_array($exceptRule, $rule)) {
                    unset($this->rules[$key]);
                }
            }
        }
    }

    private function getRules(){
        $this->rules = $this->rules();
        $this->removeExceptedRules();
        return $this->rules;
    }

    public function isValidLink($attribute = ''){
        $attributeVal = $this->getAttribute($attribute);

        if(!is_array($attributeVal) && !$attributeVal){
            return false;
        }

        if(is_array($attributeVal)){
            $retorno = true;
            foreach($attributeVal as $k => $attr){
                if($attr){
                    $attr = trim($attr);
                    $attr = $this->addHttpToLink($attr);
                    $attributeVal[$k] = $attr;
                    if(!wp_http_validate_url($attr)) {
                        $retorno = false;
                    }
                }
            }
            $this->setAttribute($attribute,$attributeVal);
            return $retorno;
        }else{
            $attributeVal = trim($attributeVal);
            if($attributeVal && $attributeVal != "") {
                $attributeVal = $this->addHttpToLink($attributeVal);
                $this->setAttribute($attribute, $attributeVal);
                return (!wp_http_validate_url($attributeVal)) ? false : true;
            }else{
                return false;
            }
        }
    }

    public function isValidUrl($attribute = ''){
        $attributeVal = $this->getAttribute($attribute);

        if(!$attributeVal){
            return true;
        }

        $attributeVal = trim($attributeVal);
        $attributeVal = $this->addHttpToLink($attributeVal);
        $this->setAttribute($attribute,$attributeVal);
        return (!wp_http_validate_url($attributeVal))? false : true;
    }

    public function addHttpToLink($attribute = ''){
        if($attribute){
            if(parse_url($attribute, PHP_URL_SCHEME) != "http" && parse_url($attribute, PHP_URL_SCHEME) != "https" ){
                if(wp_http_validate_url("https://" . $attribute)){
                    return "https://" . $attribute;
                }else if(wp_http_validate_url("http://" . $attribute)){
                    return "http://" . $attribute;
                }
            }
        }

        return $attribute;
    }
}