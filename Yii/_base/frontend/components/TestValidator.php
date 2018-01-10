<?php
namespace frontend\components;

use yii\validators\Validator;

class TestValidator extends Validator
{
    //用于model数据验证
    public function validateAttribute($model, $attribute)
    {
        if (!in_array($model->$attribute, ['addams', 'addamx'])) {
            $this->addError($model, $attribute, 'The ' . $attribute . ' must be either "addams" or "addamx".');
        }
    }

    //不用于model的验证
    public function validate($value, &$error = null)
    {
        if (!in_array($value, ['addams', 'addamx'])) {
            $error = 'The ' . $value . ' must be either "addams" or "addamx".';
            return false;
        }
        return true;
    }

    /*也可以通过重写yii\validators\Validator::validateValue() 方法替代 validateAttribute() 和 validate()，因为默认状态下， 后两者的实现使用过调用validateValue()实现的。*/




    public function init()
    {
        parent::init();
        $this->message = '无效的状态输入。';
    }

    //支持客户端验证
    public function clientValidateAttribute($model, $attribute, $view)
    {
        $statuses = json_encode(['addams', 'addams']);
        $message = json_encode($this->message);
        return <<<JS
if ($.inArray(value, $statuses) === -1) {
    messages.push($message);
}
JS;
    }
}