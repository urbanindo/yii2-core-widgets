<?php

/**
 * EventTrait trait file.
 *
 * @author Petra Barus <petra.barus@gmail.com>
 */

namespace UrbanIndo\Yii2\CoreWidgets;

use Yii;
use yii\base\InvalidCallException;
use Exception;

/**
 * EventTrait overrides `begin`, `end`, and `widget` methods from basic
 * `yii\base\Widget` class to enable event drivent widget.
 *
 * Use this for advanced widget that uses behavior like caching, etc.
 *
 * @author Petra Barus <petra.barus@gmail.com>
 */
trait EventTrait
{
    /**
     * This method is invoked right before an widget is executed.
     *
     * The method will trigger the [[EVENT_BEFORE_RUN]] event. The return value of the method
     * will determine whether the widget should continue to run.
     *
     * @return boolean Whether widget is valid to continue.
     */
    public function beforeRun() {
        $event = new Event($this);
        $this->trigger(EventInterface::EVENT_BEFORE_RUN, $event);
        return $event->isValid;
    }

    /**
     * This method is invoked right after an widget is executed.
     *
     * The method will trigger the [[EVENT_AFTER_ACTION]] event. The return value of the method
     * will be used as the widget return value.
     * ```
     *
     * @param mixed $result The widget return result.
     * @return mixed the processed widget result.
     */
    public function afterRun($result) {
        $event = new Event($this);
        $event->result = $result;
        $this->trigger(EventInterface::EVENT_AFTER_RUN, $event);
        return $event->result;
    }

    /**
     * Begins a widget.
     * This method creates an instance of the calling class. It will apply the configuration
     * to the created instance. A matching [[end()]] call should be called later.
     * @param array $config Name-value pairs that will be used to initialize the object properties
     * @return static The newly created widget instance.
     */
    public static function begin($config = [])
    {
        $config['class'] = get_called_class();
        /* @var $widget Widget */
        $widget = Yii::createObject($config);
        static::$stack[] = $widget;

        return $widget;
    }

    /**
     * Ends a widget.
     * Note that the rendering result of the widget is directly echoed out.
     * @return static the widget instance that is ended.
     * @throws InvalidCallException if [[begin()]] and [[end()]] calls are not properly nested
     */
    public static function end()
    {
        if (empty(static::$stack)) {
            throw new InvalidCallException('Unexpected ' . get_called_class() . '::end() call. A matching begin() is not found.');
        }
        
        $widget = array_pop(static::$stack);
        /* @var $widget \yii\base\Widget */
        if (get_class($widget) !== get_called_class()) {
            throw new InvalidCallException('Expecting end() of ' . get_class($widget) . ', found ' . get_called_class());
        }
        
        if ($widget->beforeRun()) {
            $result = $widget->run();
            $result = $widget->afterRun($result);
            echo $result;
        }
        return $widget;
    }

    /**
     * Creates a widget instance and runs it.
     * The widget rendering result is returned by this method.
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @return string the rendering result of the widget.
     * @throws Exception
     */
    public static function widget($config = [])
    {
        ob_start();
        ob_implicit_flush(false);
        try {
            $config['class'] = get_called_class();
            $widget = Yii::createObject($config);
            /* @var $widget \yii\base\Widget */
            $out = '';
            if ($widget->beforeRun()) {
                $result = $widget->run();
                $out = $widget->afterRun($result);
            }
        } catch (Exception $e) {
            // close the output buffer opened above if it has not been closed already
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            throw $e;
        }

        return ob_get_clean() . $out;
    }
}
