<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/2/7
 * Time: 0:15
 */

namespace frontend\components;

use yii\i18n\MissingTranslationEvent;

class TranslationEventHandler
{
    public static function handleMissingTranslation(MissingTranslationEvent $event)
    {
        $event->translatedMessage = "@MISSING: {$event->category}.{$event->message} FOR LANGUAGE {$event->language} @";
    }
}