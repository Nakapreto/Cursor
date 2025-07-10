<?php if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}

class FormHelper {

    /**
    * @param null $model
    * @param array $options: id, class
    * @param string $method
    * @param string $action
    */
    public static function formStart($model = null, $options = [], $method = 'post', $action = '#'){
        if(!is_null($model)){
    ?>
        <form id="<?php echo isset($options['id'])? $options['id'] : self::getFormId($model);?>" class="<?php echo isset($options['class'])? $options['class'] : '';?>" method="<?=$method?>" action="<?=$action?>" novalidate >
            <input type="hidden" name="scenario" value="<?php echo $model->getIsNewRecord()? 'insert' : 'update';?>">
        <?php
        }
    }

    public static function formEnd(){
       echo '</form>';
    }

    /**
    * @param null $model
    * @param null $attribute
    * @param array $options: required, class, id, placeholder
     */
    public static function text($model = null, $attribute = null, $options = []){
         if(!is_null($model) && !is_null($attribute)){
        ?>
            <label for="<?=$model->getModelName()?>[<?=$attribute?>]"><?= $model->attributeLabels()[$attribute] ?></label>
             <div class="input-group">
                <?php if(isset($options['prepend'])){?>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?=$options['prepend']?></span>
                    </div>
                    <?php
                        }
                    ?>
                <input
                       type="text"
                       class="<?php echo isset($options['class'])? $options['class'] : 'form-control';?>"
                       id="<?php echo isset($options['id'])? $options['id'] : self::getFieldId($model, $attribute);?>"
                       name="<?=$model->getModelName()?>[<?=$attribute?>]"
                       <?php echo isset($options['placeholder'])? 'placeholder="' . $options['placeholder'] . '"' : '';?>
                       <?php echo 'value="' . $model->getAttribute($attribute) . '"'; ?>
                       <?php echo isset($options['required'])? 'required' : '';?>
                       <?php echo isset($options['disabled'])? 'disabled' : '';?>
                       <?php echo isset($options['autocomplete'])? ' autocomplete="off" ' : '';?>
                >
                <?php if(isset($options['append'])){?>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?=$options['append']?></span>
                    </div>
                <?php
                    }

                    if(isset($options['feedback'])){
                        self::feedback(
                            isset($options['feedback']['valid-text'])? $options['feedback']['valid-text'] : '',
                           isset($options['feedback']['invalid-text'])? $options['feedback']['invalid-text'] : ''
                        );
                    }
                ?>

             </div>
    <?php
        }
    }

    public static function number($model = null, $attribute = null, $options = []){
         if(!is_null($model) && !is_null($attribute)){
        ?>
            <label for="<?=$model->getModelName()?>[<?=$attribute?>]"><?= $model->attributeLabels()[$attribute] ?></label>
             <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="minusBtn" style="cursor: pointer;">-</span>
                </div>
                <input
                       type="text"
                       class="<?php echo isset($options['class'])? $options['class'] : 'form-control';?>"
                       id="numberField"
                       name="<?=$model->getModelName()?>[<?=$attribute?>]"
                       <?php echo isset($options['placeholder'])? 'placeholder="' . $options['placeholder'] . '"' : '';?>
                       <?php echo 'value="' . $model->getAttribute($attribute) . '"'; ?>
                       <?php echo isset($options['required'])? 'required' : '';?>
                       <?php echo isset($options['disabled'])? 'disabled' : '';?>
                       <?php echo isset($options['autocomplete'])? ' autocomplete="off" ' : '';?>
                >
                <div class="input-group-prepend">
                    <span class="input-group-text" id="plusBtn" style="cursor: pointer;">+</span>
                </div>
                <?php
                    if(isset($options['feedback'])){
                        self::feedback(
                            isset($options['feedback']['valid-text'])? $options['feedback']['valid-text'] : '',
                           isset($options['feedback']['invalid-text'])? $options['feedback']['invalid-text'] : ''
                        );
                    }
                ?>

             </div>
    <?php
        }
    }

    /**
    * @param null $model
    * @param null $attribute
    * @param array $options: required, class, id, placeholder
     */
    public static function textArea($model = null, $attribute = null, $options = []){
         if(!is_null($model) && !is_null($attribute)){
        ?>
            <label for="<?=$model->getModelName()?>[<?=$attribute?>]"><?= $model->attributeLabels()[$attribute] ?></label>
            <textarea
                   class="<?php echo isset($options['class'])? $options['class'] : 'form-control';?>"
                   id="<?php echo isset($options['id'])? $options['id'] : self::getFieldId($model, $attribute);?>"
                   name="<?=$model->getModelName()?>[<?=$attribute?>]"
                   <?php echo isset($options['placeholder'])? 'placeholder="' . $options['placeholder'] . '"' : '';?>
                   <?php echo isset($options['required'])? ' required ' : '';?>
                   <?php echo isset($options['disabled'])? ' disabled ' : '';?>
                   rows="3"
            ><?php echo $model->getAttribute($attribute); ?></textarea>
    <?php
            if(isset($options['feedback'])){
                self::feedback(
            isset($options['feedback']['valid-text'])? $options['feedback']['valid-text'] : '',
           isset($options['feedback']['invalid-text'])? $options['feedback']['invalid-text'] : ''
                );
            }
        }
    }

     /**
    * @param null $model
    * @param null $attribute
    * @param array $options: class, id, placeholder
    */
    public static function select($model = null, $attribute = null, $options = [], $values = []){
        if(!is_null($model) && !is_null($attribute)){
        ?>
            <label for="<?=$model->getModelName()?>[<?=$attribute?>]"><?= $model->attributeLabels()[$attribute] ?></label>
            <div class="input-group">
                <?php if(isset($options['prepend'])){?>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?=$options['prepend']?></span>
                    </div>
                <?php
                    }
                ?>
            <select
                class="<?php echo isset($options['class'])? $options['class'] : 'form-control';?>"
                id="<?php echo isset($options['id'])? $options['id'] : self::getFieldId($model, $attribute);?>"
                name="<?=$model->getModelName()?>[<?=$attribute?>]"
                <?php echo isset($options['disabled'])? ' disabled ' : '';?>
                >

                <?php
                foreach($values as $value){
                    if($model->getAttribute($attribute)){
                        $value['selected'] = false;
                    }
                    if($value['val'] && $value['val'] == $model->getAttribute($attribute)){
                        $value['selected'] = true;
                    }
                    echo $value['selected']? "<option value='" .  $value['val'] . "' selected>" .  $value['text'] . "</option>" : "<option value='" .  $value['val'] . "' >" .  $value['text'] . "</option>";
                }
                ?>
            </select>
                <?php if(isset($options['append'])){?>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?=$options['append']?></span>
                    </div>
                <?php
                    }
                    if(isset($options['feedback'])){
                        self::feedback(
                                    isset($options['feedback']['valid-text'])? $options['feedback']['valid-text'] : '',
                                   isset($options['feedback']['invalid-text'])? $options['feedback']['invalid-text'] : ''
                                );
                    }
                ?>
            </div>
    <?php
        }
    }


    public static function selectFacebook($model = null, $attribute = null, $options = [], $values = []){
        if(!is_null($model) && !is_null($attribute)){
        ?>
            <label for="<?=$model->getModelName()?>[<?=$attribute?>]">Apenas para os usuários</label>
            <div class="input-group">
                <?php if(isset($options['prepend'])){?>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?=$options['prepend']?></span>
                    </div>
                <?php
                    }
                ?>
            <select
                class="<?php echo isset($options['class'])? $options['class'] : 'form-control';?>"
                id="<?php echo isset($options['id'])? $options['id'] : self::getFieldId($model, $attribute);?>"
                name="<?=$model->getModelName()?>[<?=$attribute?>]"
                <?php echo isset($options['disabled'])? ' disabled ' : '';?>
                >

                <?php
                foreach($values as $value){
                    if($model->getAttribute($attribute)){
                        $value['selected'] = false;
                    }
                    if($value['val'] == $model->getAttribute($attribute)){
                        $value['selected'] = true;
                    }
                    echo $value['selected']? "<option value='" .  $value['val'] . "' selected>" .  $value['text'] . "</option>" : "<option value='" .  $value['val'] . "' >" .  $value['text'] . "</option>";
                }
                ?>
            </select>
                <?php if(isset($options['append'])){?>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?=$options['append']?></span>
                    </div>
                <?php
                    }
                    if(isset($options['feedback'])){
                        self::feedback(
                                    isset($options['feedback']['valid-text'])? $options['feedback']['valid-text'] : '',
                                   isset($options['feedback']['invalid-text'])? $options['feedback']['invalid-text'] : ''
                                );
                    }
                ?>
            </div>
    <?php
        }
    }
    /**
    * @param string $validText
    * @param string $invalidText
     */
    public static function feedback($validText = '', $invalidText = ''){
        if(!empty($invalidText)){
        ?>
            <div class="invalid-feedback">
                <?=$invalidText?>
            </div>
        <?php
        }
        if(!empty($validText)){
        ?>
            <div class="valid-feedback">
                <?=$validText?>
            </div>
        <?php
        }
    }

    /**
    * @param null $model
    * @param string $text
    * @param array $options: id, class
    */
    public static function submitButton($model = null, $text = 'Salvar', $options = []){
        if(!is_null($model)){
        ?>
            <button type="submit"
                id="<?php echo isset($options['id'])? $options['id'] : self::getFormSubmitId($model);?>"
                class="btn <?php echo isset($options['class'])? $options['class'] : 'btn-success btn-lg';?>"
             >
             <?=$text?>
             </button>
        <?php
        }
    }

    public static function dynamicTextFieldUpdate($model = null, $attribute = null, $field = 'A', $options = []){
        if(!is_null($model) && !is_null($attribute)){
            $cont = 0;
           if($model->getAttribute($attribute)){
               foreach($model->getAttribute($attribute) as $id => $value){
                   switch ($cont){
                       case 0:
                           $field = 'A';
                           break;
                       case 1:
                           $field = 'B';
                           break;
                       case 2:
                           $field = 'C';
                           break;
                   }
    ?>
    <div id="box_<?=$id?>" class="row">
        <div class="col-md-6 mt-2">
           <label for="<?=self::getDynamicFieldId($model, $attribute, $field)?>"><?= $model->attributeLabels()[$attribute] ?> <?=$field?></label>
            <div class="input-group">
                <?php if(isset($options['prepend'])){?>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?=$options['prepend']?></span>
                    </div>
                <?php
                    }
                ?>
                <input type="text"
                       class="<?php echo isset($options['class'])? $options['class'] : 'form-control affiliateUrl';?>"
                       id="<?php echo isset($options['id'])? $options['id'] : self::getDynamicFieldId($model, $attribute, $field);?>"
                       data-field="<?=$field?>"
                       name="<?=$model->getModelName()?>[<?=$attribute?>][<?=$id?>]"
                       <?php echo isset($options['placeholder'])? 'placeholder="' . $options['placeholder'] . '"' : '';?>
                       <?php echo "value='" . $value . "'"; ?>
                       <?php echo isset($options['required'])? ' required ' : '';?>
                       <?php echo isset($options['disabled'])? ' disabled ' : '';?>
                       <?php echo isset($options['autocomplete'])? ' autocomplete="off" ' : '';?>
                       disabled
                >
                <?php if(isset($options['append'])){?>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?=$options['append']?></span>
                    </div>
                <?php
                    }
                    if(isset($options['feedback'])){
                        self::feedback(
                                    isset($options['feedback']['valid-text'])? $options['feedback']['valid-text'] : '',
                                   isset($options['feedback']['invalid-text'])? $options['feedback']['invalid-text'] : ''
                                );
                    }
                ?>
            </div>
        </div>
        <div class="col-md-6" style="margin-top: 43px; margin-left: 0px; padding-left: 0px;">
            <a class="button removeAffiliateLink" data-target="<?=$id?>">Remover este link de afiliado</a>
        </div>
    </div>
    <?php
    $cont++;
                }
           }
        }
    }

     public static function dynamicTextFieldClone($model = null, $attribute = null, $field = 'A', $options = []){
        if(!is_null($model) && !is_null($attribute)){
            $cont = 0;
           if($model->getAttribute($attribute)){
               foreach($model->getAttribute($attribute) as $id => $value){
                   switch ($cont){
                       case 0:
                           $field = 'A';
                           break;
                       case 1:
                           $field = 'B';
                           break;
                       case 2:
                           $field = 'C';
                           break;
                   }
    ?>
    <div id="box_<?=$id?>" class="row">
        <div class="col-md-6 mt-2">
           <label for="<?=self::getDynamicFieldId($model, $attribute, $field)?>"><?= $model->attributeLabels()[$attribute] ?> <?=$field?></label>
            <div class="input-group">
                <?php if(isset($options['prepend'])){?>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?=$options['prepend']?></span>
                    </div>
                <?php
                    }
                ?>
                <input type="text"
                       class="<?php echo isset($options['class'])? $options['class'] : 'form-control';?>"
                       id="<?php echo isset($options['id'])? $options['id'] : self::getDynamicFieldId($model, $attribute, $field);?>"
                       data-field="<?=$field?>"
                       name="<?=$model->getModelName()?>[<?=$attribute?>][<?=$id?>]"
                       <?php echo isset($options['placeholder'])? 'placeholder="' . $options['placeholder'] . '"' : '';?>
                       <?php echo "value='" . $value . "'"; ?>
                       <?php echo isset($options['required'])? ' required ' : '';?>
                       <?php echo isset($options['disabled'])? ' disabled ' : '';?>
                       <?php echo isset($options['autocomplete'])? ' autocomplete="off" ' : '';?>
                >
                <?php if(isset($options['append'])){?>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?=$options['append']?></span>
                    </div>
                <?php
                    }
                    if(isset($options['feedback'])){
                        self::feedback(
                                    isset($options['feedback']['valid-text'])? $options['feedback']['valid-text'] : '',
                                   isset($options['feedback']['invalid-text'])? $options['feedback']['invalid-text'] : ''
                                );
                    }
                ?>
            </div>
        </div>
        <div class="col-md-6" style="margin-top: 43px; margin-left: 0px; padding-left: 0px;">
            <a class="button removeAffiliateLinkClone" data-target="<?=$id?>">Remover este link de afiliado</a>
        </div>
    </div>
    <?php
    $cont++;
                }
           }
        }
    }

     public static function dynamicTextField($model = null, $attribute = null, $field = 'A', $options = []){
        if(!is_null($model) && !is_null($attribute)){
        ?>
        <div id="box_<?=$field?>" class="row">
            <div class="col-md-6 mt-2">
               <label for="<?=self::getDynamicFieldId($model, $attribute, $field)?>"><?= $model->attributeLabels()[$attribute] ?> <?=$field?></label>
                <div class="input-group">
                    <?php if(isset($options['prepend'])){?>
                        <div class="input-group-prepend">
                            <span class="input-group-text"><?=$options['prepend']?></span>
                        </div>
                    <?php
                        }
                    ?>
                    <input type="text"
                           class="<?php echo isset($options['class'])? $options['class'] : 'form-control affiliateUrl';?>"
                           id="<?php echo isset($options['id'])? $options['id'] : self::getDynamicFieldId($model, $attribute, $field);?>"
                           data-field="<?=$field?>"
                           name="<?=$model->getModelName()?>[<?=$attribute?>][]"
                           <?php echo isset($options['placeholder'])? 'placeholder="' . $options['placeholder'] . '"' : '';?>
                           <?php echo isset($options['required'])? ' required ' : '';?>
                           <?php echo isset($options['disabled'])? ' disabled ' : '';?>
                    >
                    <?php if(isset($options['append'])){?>
                        <div class="input-group-prepend">
                            <span class="input-group-text"><?=$options['append']?></span>
                        </div>
                    <?php
                        }
                        if(isset($options['feedback'])){
                            self::feedback(
                                        isset($options['feedback']['valid-text'])? $options['feedback']['valid-text'] : '',
                                       isset($options['feedback']['invalid-text'])? $options['feedback']['invalid-text'] : ''
                                    );
                        }
                    ?>
                </div>
            </div>
            <?php
                if(!isset($options['hideRemoveLink'])){
             ?>
                    <div class="col-md-6" style="margin-top: 43px; margin-left: 0px; padding-left: 0px;">
                        <a class="button removeAffiliateLink" data-target="<?=$field?>">Remover este link de afiliado</a>
                    </div>
            <?php
                }
            ?>
        </div>
            <?php
        }
    }

    public static function inputFile($model = null, $attribute = null, $options = []){
        if(!is_null($model) && !is_null($attribute)){
    ?>
            <label for="<?=$model->getModelName()?>[<?=$attribute?>]"><?= $model->attributeLabels()[$attribute] ?></label>
             <div class="input-group">
                <?php if(isset($options['prepend'])){?>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?=$options['prepend']?></span>
                    </div>
                    <?php
                        }
                    ?>
                <input
                       type="text"
                       class="form-control <?php echo isset($options['class'])? $options['class'] : '';?>"
                       id="<?php echo isset($options['id'])? $options['id'] : self::getFieldId($model, $attribute);?>"
                       name="<?=$model->getModelName()?>[<?=$attribute?>]"
                       <?php echo isset($options['placeholder'])? 'placeholder="' . $options['placeholder'] . '"' : '';?>
                       <?php echo 'value="' . $model->getAttribute($attribute) . '"'; ?>
                       <?php echo isset($options['required'])? ' required ' : '';?>
                       <?php echo isset($options['disabled'])? ' disabled ' : '';?>
                       autocomplete="off"
                >
                <?php if(isset($options['append'])){?>
                    <div class="input-group-prepend <?php echo isset($options['class'])? $options['class'] : '';?>">
                        <span class="input-group-text"><?=$options['append']?></span>
                    </div>
                <?php
                    }

                    if(isset($options['feedback'])){
                        self::feedback(
                            isset($options['feedback']['valid-text'])? $options['feedback']['valid-text'] : '',
                           isset($options['feedback']['invalid-text'])? $options['feedback']['invalid-text'] : ''
                        );
                    }
                ?>

             </div>
    <?php
        }
    }

    public static function getDynamicFieldId($model = null, $attribute = null, $field = 'A'){
        if(!is_null($model) && !is_null($attribute)){
            return self::getFieldId($model, $attribute) . '_' . $field;
        }

        return '';
    }

    public static function getFieldId($model = null, $attribute = null){
        if(!is_null($model) && !is_null($attribute)){
            return $model->getModelName() . '_' . $attribute;
        }

        return '';
    }

    public static function getFormSubmitId($model = null){
        if(!is_null($model)){
            return $model->getModelName() . '_submit';
        }

        return '';
    }

    public static function getFormId($model = null){
        if(!is_null($model)){
            return $model->getModelName() . '_form';
        }

        return '';
    }
}