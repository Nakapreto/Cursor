<?php if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}

class TableHelper {

    public static function loadTable($rowTitles = []){
        if(empty($rowTitles)) {
            return;
        }
        ?>
        <table class="table table-hover table-bordered" id="table-spl">
            <thead style="text-align: center;">
                <tr id="table-head">
                    <?php
                    foreach($rowTitles as $rowTitle){
                        echo "<th scope='col'>$rowTitle</th>";
                    }
                    ?>
                </tr>
            </thead>
        <tbody style="text-align: center;">
        <?php
    }

    public static function loadRows($values = []){
        if(empty($values)) {
            ?>
            <tr>
                <td scope="row" colspan="5"><?=TranslateHelper::getTranslate('Ainda não foi criado nenhum link')?></td>
            </tr>
            <?php
        }else {

            echo '<tr data-status="' . $values[2] . '" id="link_' . $values[0] . '" class="filterLink" >';
            unset($values[0]);
            foreach ($values as $value) {
                echo "<td>$value</td>";
            }
            echo '</tr>';
        }
    }

    public static function loadRowsImport($values = []){
        if(empty($values)) {
            ?>
            <tr>
                <td scope="row" colspan="6"><?=TranslateHelper::getTranslate('Não existem links para importar deste plugin')?></td>
            </tr>
            <?php
        }else {

            echo '<tr data-status="' . $values['slug'] . '" id="link_' . $values['id'] . '" class="filterLink" >';
            echo "<td><input type='checkbox' data-target='".$values['id']."' class='selectImport'></td>";
            unset($values['id']);
            foreach ($values as $key => $value) {
                echo "<td style='max-width: 200px; font-size: 0.8em; word-wrap: break-word;'>$value</td>";
            }
            echo '</tr>';
        }
    }

    public static function loadRowsCategories($values = []){
        if(empty($values)) {
            ?>
            <tr>
                <td scope="row"><?=TranslateHelper::getTranslate('Ainda não foi criada nenhuma categoria')?></td>
            </tr>
            <?php
        }else {

            echo '<tr data-status="' . $values[1] . '" id="group_' . $values[0] . '" class="filterCategory">';
            unset($values[0]);
            foreach ($values as $value) {
                echo "<td>$value</td>";
            }
            echo '</tr>';
        }
    }

    public static function loadRowsAutomaticLinks($values = []){
        if(empty($values)) {
            ?>
            <tr>
                <td scope="row"><?=TranslateHelper::getTranslate('Ainda não foi criado nenhum link')?></td>
            </tr>
            <?php
        }else {

            echo '<tr data-status="' . $values["keyword"] . '" id="link_' . $values["id"] . '" class="filterLink" >';
            unset($values['id']);
            foreach ($values as $id => $value) {
                echo "<td data-target='$id'>$value</td>";
            }
            echo '</tr>';
        }
    }

    public static function loadRowsAutomaticViews($values = []){
        if(empty($values)) {
            ?>
            <tr>
                <td scope="row" colspan="5"><?=TranslateHelper::getTranslate('Está funcionalidade está disponível na versão pro do Super Links')?></td>
            </tr>
            <?php
        }else {

            echo '<tr data-status="' . $values["keywords"] . '" id="link_' . $values["id"] . '" class="filterLink" >';
            unset($values['id']);
            unset($values['keywords']);
            foreach ($values as $id => $value) {
                echo "<td>$value</td>";
            }
            echo '</tr>';
        }
    }

    public static function tableEnd(){
       echo '</tbody>
        </table>';
    }

}