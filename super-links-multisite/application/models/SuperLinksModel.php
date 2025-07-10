<?php
if(!defined('ABSPATH'))
    die('You are not allowed to call this page directly.');

class SuperLinksModel extends SuperLinksCoreModel {

    public function __construct() {
        parent::__construct();

        $this->setTableName(
            $this->tables['spl_linkActivation']
        );
    }

    public function getModelName(){
        return 'SuperLinksModel';
    }

    public function rules()
    {
        return [
            // Removido validações de licença - plugin sempre ativo
        ];
    }

    public function attributeLabels()
    {
        return array(
            'active' => TranslateHelper::getTranslate('Plugin está ativo?'),
        );
    }

    public function isPluginActive(){
        // Plugin sempre ativo para multisite - sem sistema de ativação
        return true;
    }

    public function verifyLicense($license = ''){
        // Plugin sempre ativo - sem verificação de licença
        return true;
    }

    public function should_install() {
        $old_db_version = get_option('superLinks_db_version');

        return (SUPER_LINKS_DB_VERSION != $old_db_version);
    }

    public function verified_version() {
        $old_vr_version = get_option('superLinks_vr_version');

        return (SUPER_LINKS_VERIFIED_VERSION != $old_vr_version);
    }

    public function superLinks_install() {
        global $wpdb;

        if($this->should_install()) {
            $char_collation = $wpdb->get_charset_collate();

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            $this->createTableSplGroupLink($char_collation);
            $this->insertGeneralGroup();
            $this->createTableSplLinks($char_collation);
            $this->createTableSplAffiliateLinks($char_collation);
            $this->createTableSplLinkMetrics($char_collation);
            $this->createTableSplLinkMonitoring($char_collation);
            $this->createTableSplLinkCloak($char_collation);
            $this->createTableSplLinkConfigSocial($char_collation);
            $this->createTableSplLinkActivation($char_collation);
            $this->createTableSplLinkConfigWaitPage($char_collation);

            $this->updateTablesV101();

            $this->createTableSplAutomaticLinks($char_collation);
            $this->createTableSplAutomaticLinkMetrics($char_collation);

            $this->updateTablesV104();
            $this->updateTablesV105();
            $this->updateTablesV106();

            $this->createTableSplImport($char_collation); // v1.0.7

            $this->updateTablesV108(); // v1.0.8
            $this->createTableSplLinkClonePage($char_collation); // v1.0.9
            $this->createTableSplLinkCookiePage($char_collation); // v1.0.10
            $this->updateTablesV1011(); // v1.0.11
            $this->updateTablesV1013($char_collation); // v1.0.13
            $this->updateTablesV1014(); // v1.0.14
            $this->updateTablesV1015(); // v1.0.15
            $this->updateTablesV1016(); // v1.0.16
            $this->updateTablesV1017(); // v1.0.17
            $this->updateTablesV1020(); // v1.0.17
            $this->createTableCloneGroupLink($char_collation); // v1.0.21
            $this->updateTablesV1021(); // v1.0.22
            $this->updateTablesV1022(); // v1.0.23
            $this->updateTablesV1023(); // v1.0.24
            $this->updateTablesV1024(); // v1.0.25
            $this->updateTablesV1025(); // v1.0.26
            $this->updateTablesV1026(); // v1.0.28
            $this->updateTablesV1027(); // v1.0.28
            $this->createTableAutomaticGroupLink($char_collation); // v1.0.31
            $this->updateTablesV1031(); // v1.0.31
            $this->updateTablesV1032(); // v1.0.32
            $this->updateTablesV1033(); // v1.0.33
            $this->updateTablesV1034(); // v1.0.34
            $this->updateTablesV1035(); // v1.0.35
            $this->createTableSplLinkApiConvertFacebook($char_collation); // v1.0.35
            $this->updateTablesV1036(); // v1.0.36
            $this->createTableSplLinkips($char_collation); // v1.0.37
            $this->updateTablesV1038(); // v1.0.38
            $this->updateTablesV1039(); // v1.0.39
            $this->createTableSplLinkGringaPage($char_collation); // v1.0.40
            $this->updateTablesV1040(); // v1.0.40

            $this->saveDbVersion(SUPER_LINKS_DB_VERSION);
        }

        if($this->verified_version()) {
            $this->saveVrVersion(SUPER_LINKS_VERIFIED_VERSION);
        }
    }


    private function saveDbVersion($superLinks_db_version){
        update_option('superLinks_db_version', $superLinks_db_version);
        wp_cache_delete('alloptions', 'options');
    }

    private function saveVrVersion($superLinks_vr_version){
        update_option('superLinks_vr_version', $superLinks_vr_version);
        wp_cache_delete('alloptions', 'options');
    }

    private function createTableSplGroupLink($char_collation) {
        $sql = "CREATE TABLE {$this->tables['spl_group']} (
              id int(11) NOT NULL auto_increment,
              groupName varchar(255) NOT NULL,
              defaultGroup tinyint(1) NOT NULL DEFAULT 0,
              description text default NULL,
              PRIMARY KEY  (id)
            ) {$char_collation};";

        dbDelta($sql);
    }

    private function insertGeneralGroup() {
        global $wpdb;
        $wpdb->insert(
            $this->tables['spl_group'],
            array(
                'groupName' => 'Geral',
                'defaultGroup' => 1
            )
        );
    }

    private function createTableSplLinks($char_collation)
    {
        $sql = "CREATE TABLE {$this->tables['spl_link']} (
              id int(11) NOT NULL auto_increment,
              idGroup int(11) DEFAULT NULL,
              linkName varchar(255) NOT NULL,
              description text default NULL,
              keyWord varchar(255) DEFAULT NULL,
              redirectType varchar(255) DEFAULT 'html',
              redirectDelay int(2) DEFAULT 1,
              statusLink varchar(64) DEFAULT 'enabled',
              abLastTest int(2) NOT NULL DEFAULT 0,
              createdAt datetime NOT NULL,
              updatedAt datetime DEFAULT NULL,
              PRIMARY KEY  (id),
              FOREIGN KEY (idGroup) REFERENCES " . $this->tables['spl_group'] . "(id),
              KEY statusLink (statusLink),
              KEY redirectType (redirectType(191)),
              KEY createdAt (createdAt),
              KEY updatedAt (updatedAt)
            ) {$char_collation};";

        dbDelta($sql);
    }

    private function createTableSplAffiliateLinks($char_collation)
    {
        $sql = "CREATE TABLE {$this->tables['spl_affiliateLink']} (
              id int(11) NOT NULL auto_increment,
              idLink int(11) NOT NULL,
              affiliateUrl tinytext NOT NULL,
              createdAt datetime NOT NULL,
              updatedAt datetime default NULL,
              PRIMARY KEY (id),
              FOREIGN KEY (idLink) REFERENCES " . $this->tables['spl_link'] . "(id) on update cascade on delete cascade,
              KEY createdAt (createdAt),
              KEY updatedAt (updatedAt)
            ) {$char_collation};";

        dbDelta($sql);
    }

    private function createTableSplLinkMetrics($char_collation) {
        $sql = "CREATE TABLE {$this->tables['spl_linkMetrics']} (
              id int(11) NOT NULL auto_increment,
              idAffiliateLink int(11) NOT NULL,
              accessTotal int(11) NOT NULL DEFAULT 0,
              uniqueTotalAccesses int(11) NOT NULL DEFAULT 0,
              PRIMARY KEY  (id),
              FOREIGN KEY (idAffiliateLink) REFERENCES " . $this->tables['spl_affiliateLink'] . "(id) on update cascade on delete cascade
            ) {$char_collation};";

        dbDelta($sql);
    }

    private function createTableSplLinkMonitoring($char_collation) {
        $sql = "CREATE TABLE {$this->tables['spl_linkMonitoring']} (
              id int(11) NOT NULL auto_increment,
              idLink int(11) NOT NULL,
              googleMonitoringID varchar(255) DEFAULT '',
              pixelID varchar(255) DEFAULT '',
              track varchar(255) DEFAULT '',
              codeHeadPage text DEFAULT '',
              codeBodyPage text DEFAULT '',
              codeFooterPage text DEFAULT '',
              PRIMARY KEY  (id),
              FOREIGN KEY (idLink) REFERENCES " . $this->tables['spl_link'] . "(id) on update cascade on delete cascade
            ) {$char_collation};";

        dbDelta($sql);
    }

    private function createTableSplLinkCloak($char_collation) {
        $sql = "CREATE TABLE {$this->tables['spl_linkCloak']} (
              id int(11) NOT NULL auto_increment,
              idLink int(11) NOT NULL,
              statusCloak varchar(64) DEFAULT 'disabled',
              geolocationBlocks varchar(255) DEFAULT '',
              redirectUrl text DEFAULT '',
              showRedirectButton varchar(64) DEFAULT 'disabled',
              redirectButtonText varchar(255) DEFAULT '',
              statusCloakAutomatic varchar(64) DEFAULT 'disabled',
              cloacktransparencia varchar(64) DEFAULT 'disabled',
              onlyPersonType varchar(64) DEFAULT 'disabled',
              allowOnlyCountry varchar(255) DEFAULT '',
              allowOnlyState varchar(255) DEFAULT '',
              cloacktransparenciaOpacity varchar(64) DEFAULT '0.6',
              waitTimeCloak int(2) DEFAULT 0,
              cloakBgColor varchar(64) DEFAULT '#ffffff',
              cloakTextColor varchar(64) DEFAULT '#000000',
              hideScrollBar varchar(64) DEFAULT 'disabled',
              PRIMARY KEY  (id),
              FOREIGN KEY (idLink) REFERENCES " . $this->tables['spl_link'] . "(id) on update cascade on delete cascade
            ) {$char_collation};";

        dbDelta($sql);
    }

    private function createTableSplLinkConfigSocial($char_collation) {
        $sql = "CREATE TABLE {$this->tables['spl_linkConfigSocial']} (
              id int(11) NOT NULL auto_increment,
              idLink int(11) NOT NULL,
              socialTitle varchar(255) DEFAULT '',
              socialDescription text DEFAULT '',
              socialImage text DEFAULT '',
              PRIMARY KEY  (id),
              FOREIGN KEY (idLink) REFERENCES " . $this->tables['spl_link'] . "(id) on update cascade on delete cascade
            ) {$char_collation};";

        dbDelta($sql);
    }

    private function createTableSplLinkActivation($char_collation) {
        $sql = "CREATE TABLE {$this->tables['spl_linkActivation']} (
              id int(11) NOT NULL auto_increment,
              license_key varchar(255) NOT NULL,
              hp_atualizacao varchar(255) DEFAULT '',
              active varchar(64) DEFAULT 'disabled',
              PRIMARY KEY  (id)
            ) {$char_collation};";

        dbDelta($sql);
    }

    private function createTableSplLinkConfigWaitPage($char_collation) {
        $sql = "CREATE TABLE {$this->tables['spl_linkConfigWaitPage']} (
              id int(11) NOT NULL auto_increment,
              idLink int(11) NOT NULL,
              titleWaitPage varchar(255) DEFAULT '',
              textWaitPage text DEFAULT '',
              backColorWaitPage varchar(64) DEFAULT '#ffffff',
              colorTextWaitPage varchar(64) DEFAULT '#000000',
              PRIMARY KEY  (id),
              FOREIGN KEY (idLink) REFERENCES " . $this->tables['spl_link'] . "(id) on update cascade on delete cascade
            ) {$char_collation};";

        dbDelta($sql);
    }

    private function createTableSplLinkApiConvertFacebook($char_collation) {
        $sql = "CREATE TABLE {$this->tables['spl_linkApiConvert']} (
              id int(11) NOT NULL auto_increment,
              idLink int(11) NOT NULL,
              pixelIdPage varchar(255) DEFAULT '',
              tokenPixelPage varchar(500) DEFAULT '',
              eventPage varchar(255) DEFAULT '',
              PRIMARY KEY  (id),
              FOREIGN KEY (idLink) REFERENCES " . $this->tables['spl_link'] . "(id) on update cascade on delete cascade
            ) {$char_collation};";

        dbDelta($sql);
    }

    private function updateTablesV101(){
        global $wpdb;

        //Inclui campo para funcionalidade de redirect no botão voltar
        $sql = "ALTER TABLE {$this->tables['spl_link']} add redirectBtn varchar(255) DEFAULT '' ";
        $wpdb->query($sql);
    }

    private function updateTablesV104(){
        global $wpdb;

        //Inclui campo para funcionalidade de redirect no botão voltar
        $sql = "ALTER TABLE {$this->tables['spl_link']} add enableRedirectJavascript varchar(64) DEFAULT 'disabled'";
        $wpdb->query($sql);
    }

    private function updateTablesV105(){
        global $wpdb;

        //remove os insert groups padrão iniciais
        $wpdb->delete(
            $this->tables['spl_group'],
            array(
                'defaultGroup' => 1
            )
        );
    }

    private function updateTablesV106(){
        global $wpdb;

        $this->loadDataByID('1');
        $superLinksDataActivation = $this->getAttributes();


        if(isset($superLinksDataActivation['license_key']) && isset($superLinksDataActivation['active']) && $superLinksDataActivation['active'] && $superLinksDataActivation['license_key']){
            $licence = $superLinksDataActivation['license_key'];

            update_option('spl_code_top', $licence);
            wp_cache_delete('alloptions', 'options');
        }

        sleep(2);

        $sql = "ALTER TABLE {$this->tables['spl_linkActivation']} drop column license_key";
        $wpdb->query($sql);
    }

    private function updateTablesV108(){
        global $wpdb;

        //altera tamanho do campo de url de afiliado
        $sql = "ALTER TABLE {$this->tables['spl_affiliateLink']} modify affiliateUrl text NOT NULL";
        $wpdb->query($sql);
    }



    private function createTableSplLinkClonePage($char_collation) {
        $sql = "CREATE TABLE {$this->tables['spl_clonePageLinks']} (
              id int(11) NOT NULL auto_increment,
              idLink int(11) NOT NULL,
              pageItem varchar(255) DEFAULT '',
              newItem varchar(255) DEFAULT '',
              PRIMARY KEY  (id),
              FOREIGN KEY (idLink) REFERENCES " . $this->tables['spl_link'] . "(id) on update cascade on delete cascade
            ) {$char_collation};";

        dbDelta($sql);
    }

    private function createTableSplLinkCookiePage($char_collation) {
        $sql = "CREATE TABLE {$this->tables['spl_cookieLinks']} (
              id int(11) NOT NULL auto_increment,
              cookieName varchar(255) default '',
              idPost text,
              linkSuperLinks text,
              statusCookie varchar(64) DEFAULT 'enabled',
              timeCookie varchar(100) DEFAULT '',
              urlCookie text,
              redirect varchar(64) DEFAULT 'disabled',
              urlCamuflada text,
              qtdAcessos int(11) NOT NULL DEFAULT 0,
              activeWhen varchar(100) DEFAULT '',
              PRIMARY KEY (id)
            ) {$char_collation};";

        dbDelta($sql);
    }

    private function updateTablesV1011(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_clonePageLinks']} add typeItem varchar(255) DEFAULT 'link' "; // link, image
        $wpdb->query($sql);

        $sql = "ALTER TABLE {$this->tables['spl_link']} add htmlClonePage longtext";
        $wpdb->query($sql);
    }

    private function updateTablesV1013($char_collation){
        global $wpdb;

        $sql = "CREATE TABLE {$this->tables['spl_cookieGroup']} (
              id int(11) NOT NULL auto_increment,
              groupName varchar(255) NOT NULL,
              defaultGroup tinyint(1) NOT NULL DEFAULT 0,
              description text default NULL,
              PRIMARY KEY  (id)
            ) {$char_collation};";

        dbDelta($sql);


        $sql = "ALTER TABLE {$this->tables['spl_cookieLinks']} add idGroup int(11) DEFAULT NULL";
        $wpdb->query($sql);

        $sql = "ALTER TABLE {$this->tables['spl_cookieLinks']} ADD FOREIGN KEY (idGroup) REFERENCES ".$this->tables['spl_cookieGroup']."(id)";
        $wpdb->query($sql);
    }

    private function updateTablesV1014(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_cookieLinks']} add idPage text";
        $wpdb->query($sql);

    }

    private function createTableSplAutomaticLinks($char_collation) {
        $sql = "CREATE TABLE {$this->tables['spl_automaticLinks']} (
                id int(11) NOT NULL auto_increment,
                page_id mediumint(9),
                title varchar(255) NOT NULL,
                keywords varchar(255) NOT NULL,
                url varchar(255) NOT NULL,
                num smallint(5) NOT NULL DEFAULT 1,
                target varchar(255) NOT NULL default '_self',
                nofollow tinyint(1) NOT NULL default 0,
                active tinyint(1) NOT NULL default 1,
                partly_match tinyint(1) NOT NULL default 0,
                titleattr varchar(255),
                PRIMARY KEY  (id)
            ) {$char_collation};";

        dbDelta($sql);
    }

    private function createTableSplAutomaticLinkMetrics($char_collation) {
        $sql = "CREATE TABLE {$this->tables['spl_automaticMetrics']} (
              id int(11) NOT NULL auto_increment,
              idAutomaticLink int(11) NOT NULL,
              idPost int(11) NOT NULL,
              keyword varchar (255) NOT NULL,
              accessTotal int(11) NOT NULL DEFAULT 0,
              uniqueTotalAccesses int(11) NOT NULL DEFAULT 0,
              PRIMARY KEY  (id),
              FOREIGN KEY (idAutomaticLink) REFERENCES " . $this->tables['spl_automaticLinks'] . "(id) on update cascade on delete cascade,
              KEY idAutomaticLink (idAutomaticLink),
              KEY idPost (idPost),
              KEY keyword (keyword)
            ) {$char_collation};";

        dbDelta($sql);
    }

    private function createTableSplImport($char_collation) {
        $sql = "CREATE TABLE {$this->tables['spl_importLinks']} (
              id int(11) NOT NULL auto_increment,
              idLink int(11) NOT NULL,
              pluginToImport varchar (255) NOT NULL,
              idLinkInPlugin varchar(255) NOT NULL,
              createdAt datetime NOT NULL,
              PRIMARY KEY  (id),
              FOREIGN KEY (idLink) REFERENCES " . $this->tables['spl_link'] . "(id) on update cascade on delete cascade,
              KEY idLink (idLink),
              KEY pluginToImport (pluginToImport),
              KEY idLinkInPlugin (idLinkInPlugin),
              KEY createdAt (createdAt)
            ) {$char_collation};";

        dbDelta($sql);
    }

    private function updateTablesV1015(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_link']} add saveHtmlClone varchar(64) DEFAULT 'enabled'";
        $wpdb->query($sql);
    }

    private function updateTablesV1016(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_linkMonitoring']} add trackGoogle varchar(255) DEFAULT ''";
        $wpdb->query($sql);
    }

    private function updateTablesV1017(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_link']} add enableProxy varchar(64) DEFAULT 'disabled'";
        $wpdb->query($sql);
    }

    private function updateTablesV1020(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_link']} add numberWhatsapp varchar(64) DEFAULT ''";
        $wpdb->query($sql);

        $sql = "ALTER TABLE {$this->tables['spl_link']} add textWhatsapp text DEFAULT ''";
        $wpdb->query($sql);
    }

    private function createTableCloneGroupLink($char_collation) {
        global $wpdb;
        $subquery = "SELECT CONSTRAINT_NAME
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                    WHERE TABLE_NAME =  '{$this->tables['spl_link']}'
                    AND COLUMN_NAME =  'idGroup'";

        $constraitName = $wpdb->get_row($subquery);
        if($constraitName && isset($constraitName->CONSTRAINT_NAME)) {
            $constraitName = $constraitName->CONSTRAINT_NAME;

            if($constraitName) {
                $sqlRemove = "ALTER TABLE {$this->tables['spl_link']} DROP FOREIGN KEY " . $constraitName . ";";
                $wpdb->query($sqlRemove);
            }
        }

        $sql = "CREATE TABLE {$this->tables['spl_cloneGroup']} (
              id int(11) NOT NULL auto_increment,
              groupName varchar(255) NOT NULL,
              defaultGroup tinyint(1) NOT NULL DEFAULT 0,
              description text default NULL,
              PRIMARY KEY  (id)
            ) {$char_collation};";

        dbDelta($sql);

        $old_db_version = get_option('superLinks_db_version');

        if($old_db_version < '1.0.23') {
            $categoriesLinks = new SuperLinksGroupLinkModel();
            $allCategoriesLinks = $categoriesLinks->getAllData();

            foreach ($allCategoriesLinks as $groupLink) {
                if (!$groupLink->defaultGroup) {
                    $cloneGroup = new SuperLinksCloneGroupModel();
                    $cloneGroup->setAttribute('id', $groupLink->id);
                    $cloneGroup->setAttribute('groupName', $groupLink->groupName);
                    $cloneGroup->setAttribute('defaultGroup', $groupLink->defaultGroup);
                    $cloneGroup->setAttribute('description', $groupLink->description);
                    $cloneGroup->save();
                }
            }
        }
    }

    private function updateTablesV1021(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_link']} add idPage text DEFAULT ''";
        $wpdb->query($sql);
    }

    private function updateTablesV1022(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_link']} add idPopupDesktop int(11) DEFAULT null";
        $wpdb->query($sql);
        $sql = "ALTER TABLE {$this->tables['spl_link']} add idPopupMobile int(11) DEFAULT null";
        $wpdb->query($sql);
    }

    private function updateTablesV1023(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_link']} add exitIntentPopup varchar(64) DEFAULT 'disabled'";
        $wpdb->query($sql);
        $sql = "ALTER TABLE {$this->tables['spl_link']} add loadPopupAfterSeconds int(11) DEFAULT 0";
        $wpdb->query($sql);
    }

    private function updateTablesV1024(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_link']} add popupBackgroundColor varchar(255) DEFAULT 'rgba(255, 255, 255, 100)'";
        $wpdb->query($sql);
        $sql = "ALTER TABLE {$this->tables['spl_link']} add popupAnimation varchar(255) DEFAULT 'none'";
        $wpdb->query($sql);
    }

    private function updateTablesV1025(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_link']} add compatibilityMode varchar(64) DEFAULT 'disabled'";
        $wpdb->query($sql);
    }

    private function updateTablesV1026(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_link']} add forceCompatibility varchar(64) DEFAULT 'enabled'";
        $wpdb->query($sql);
    }

    private function updateTablesV1027(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_link']} add counterSuperEscassez varchar(64) DEFAULT '0'";
        $wpdb->query($sql);
    }

    private function createTableAutomaticGroupLink($char_collation){
        $sql = "CREATE TABLE {$this->tables['spl_automaticGroup']} (
              id int(11) NOT NULL auto_increment,
              groupName varchar(255) NOT NULL,
              defaultGroup tinyint(1) NOT NULL DEFAULT 0,
              description text default NULL,
              PRIMARY KEY  (id)
            ) {$char_collation};";

        dbDelta($sql);
    }

    private function updateTablesV1031(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_automaticLinks']} add idGroup int(11) DEFAULT NULL";
        $wpdb->query($sql);
    }

    private function updateTablesV1032(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_link']} add alertaConversoes varchar(64) DEFAULT '0'";
        $wpdb->query($sql);
    }

    private function updateTablesV1033(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_link']} add rgpd varchar(64) DEFAULT '0'";
        $wpdb->query($sql);
    }

    private function updateTablesV1034(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_affiliateLink']} MODIFY affiliateUrl text";
        $wpdb->query($sql);
    }

    private function updateTablesV1035(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_linkMonitoring']} add testEventApiFacebook varchar(255) DEFAULT ''";
        $wpdb->query($sql);

        $sql = "ALTER TABLE {$this->tables['spl_linkMonitoring']} add tokenApiFacebook text DEFAULT ''";
        $wpdb->query($sql);

        $sql = "ALTER TABLE {$this->tables['spl_linkMonitoring']} add enableApiFacebook varchar(64) NOT NULL DEFAULT 'disabled'";
        $wpdb->query($sql);

        $sql = "ALTER TABLE {$this->tables['spl_linkMonitoring']} add pixelApiFacebook varchar(255) DEFAULT ''";
        $wpdb->query($sql);

        $sql = "ALTER TABLE {$this->tables['spl_linkMonitoring']} add logErrorApiFacebook text DEFAULT ''";
        $wpdb->query($sql);
    }

    private function updateTablesV1036(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_link']} add renovaHtmlClone varchar(255) DEFAULT 'disabled'";
        $wpdb->query($sql);
    }

	private function createTableSplLinkips($char_collation) {
		$sql = "CREATE TABLE {$this->tables['spl_linkIps']} (
              id int(11) NOT NULL auto_increment,
              idLink int(11) NOT NULL,
              ipClient varchar(255) NOT NULL DEFAULT '',
              blocked varchar(255) NOT NULL DEFAULT False,
              url text DEFAULT '',
              datasAcesso text DEFAULT '',
              PRIMARY KEY  (id),
              FOREIGN KEY (idLink) REFERENCES " . $this->tables['spl_link'] . "(id) on update cascade on delete cascade
            ) {$char_collation};";

		dbDelta($sql);
	}

	private function updateTablesV1038(){
		global $wpdb;

		$sql = "ALTER TABLE {$this->tables['spl_link']} add opniaoClientePgClonada varchar(255) DEFAULT 'sim'";
		$wpdb->query($sql);
	}

	private function updateTablesV1039(){
		global $wpdb;

		$sql = "ALTER TABLE {$this->tables['spl_link']} add removerPixelPgClonada varchar(255) DEFAULT 'enabled'";
		$wpdb->query($sql);
	}

    private function createTableSplLinkGringaPage($char_collation) {
        $sql = "CREATE TABLE {$this->tables['spl_linkGringaPage']} (
              id int(11) NOT NULL auto_increment,
              idLink int(11) NOT NULL,
              checkoutProdutor text DEFAULT '',
              linkPaginaVenda varchar(255) DEFAULT '',
              tempoRedirecionamentoCheckout varchar(255) DEFAULT '1',
              textoTempoRedirecionamento varchar(255) DEFAULT '',
              abrirPaginaBranca varchar(255) DEFAULT 'disabled',
              PRIMARY KEY  (id),
              FOREIGN KEY (idLink) REFERENCES " . $this->tables['spl_link'] . "(id) on update cascade on delete cascade
            ) {$char_collation};";

        dbDelta($sql);
    }


    private function updateTablesV1040(){
        global $wpdb;

        $sql = "ALTER TABLE {$this->tables['spl_link']} add usarEstrategiaGringa varchar(255) DEFAULT 'no'";
        $wpdb->query($sql);
    }
}