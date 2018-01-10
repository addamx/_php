<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/1/23
 * Time: 15:27
 */

namespace frontend\components;

use yii\base\component;
use yii\base\Event;

class MessageEvent extends Event
{
    public $message;
}

class TestEvent extends component
{
    const EVENT_MESSAGE_SENT = 'messageSent';

    public function send($message)
    {
        $event = new MessageEvent();
        $event->message = $message;
        $this->trigger(self::EVENT_MESSAGE_SENT, $event);
    }
}