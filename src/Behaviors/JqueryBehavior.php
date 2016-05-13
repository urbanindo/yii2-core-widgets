<?php
/**
 * JqueryAttachBehavior class file.
 * @author Petra Barus <petra.barus@gmail.com>
 */

namespace UrbanIndo\Yii2\CoreWidgets\Behaviors;

use yii\web\View;
use UrbanIndo\Yii2\CoreWidgets\Event;
use UrbanIndo\Yii2\CoreWidgets\EventInterface;

/**
 * JqueryAttachBehavior is a behavior to attach enclosing javascript (jQuery) action
 * to the widget.
 *
 * @author Petra Barus <petra.barus@gmail.com>
 * @property-read \yii\base\Widget $owner
 */
class JqueryBehavior extends \yii\base\Behavior
{
    /**
     * Javascript function that accept JQuery object of widget with owner id.
     * This will be wrapped by function (widget) {} closure, where widget is
     * the single jQuery object.
     * @var string
     */
    public $script = <<<JS
console.log(widget);
JS
    ;
    
    /**
     * Javascript position from \yii\web\ViewAction::POS_* constant
     * @var integerr
     */
    public $position = View::POS_READY;
    
    /**
     * Widget id selector. By default will uses Widget::id, but sometimes
     * it doesn't works, e.g when using custom layout with Widget::begin()
     * @var string
     */
    public $widgetId = null;
    
    /**
     * @inheritdoc
     * @param mixed $owner Owner.
     */
    public function attach($owner)
    {
        /* @var $owner \yii\base\Widget */
        $this->owner = $owner;
        $this->owner->on(EventInterface::EVENT_AFTER_RUN, [$this, 'afterRun']);
    }
    
    /**
     * Attach javascript after widget run with $this->jsPosition.
     * @param Event $event WidgetEvent.
     */
    public function afterRun(Event $event)
    {
        $event; // unused
        if (isset($this->widgetId)) {
            $id = $this->widgetId;
        } else {
            $id = $this->owner->options['id'];
        }
        $js = <<<JS
;(function(widget){

{$this->script}

})($('#{$id}'));
JS
        ;
        $this->owner->getView()->registerJs($js, $this->position);
    }
}
