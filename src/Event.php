<?php
/**
 * Event class file.
 * @author Petra Barus <petra.barus@gmail.com>
 */

namespace UrbanIndo\Yii2\CoreWidgets;

/**
 * Event represents event fired for widgets that uses EventTrait.
 * @author Petra Barus <petra.barus@gmail.com>
 */
class Event extends \yii\base\Event
{
    /**
     * @var \yii\base\Widget The widget currently being executed.
     */
    public $widget;

    /**
     * @var string The return result of the widget. Event handlers may modify
     * this property to change the widget result.
     */
    public $result;

    /**
     * @var boolean Whether to continue the widget or not.
     */
    public $isValid = true;

    /**
     * Constructor.
     * @param \yii\base\Widget $widget The widget associated with this action event.
     * @param array            $config Name-value pairs that will be used to initialize
     * the object properties.
     */
    public function __construct(\yii\base\Widget $widget, $config = []) {
        $this->widget = $widget;
        parent::__construct($config);
    }
}
