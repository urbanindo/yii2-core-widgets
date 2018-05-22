<?php
/**
 * EventInterface interface file.
 * @author Petra Barus <petra.barus@gmail.com>
 */

namespace UrbanIndo\Yii2\CoreWidgets;

use yii\base\Widget as BaseWidget;

/**
 * EventInterface provides event contants.
 * @author Petra Barus <petra.barus@gmail.com>
 * @deprecated due to Yii 2.0.11
 */
interface EventInterface
{
    /**
     * @event WidgetEvent an event raised right before executing a widget action.
     * You may set [[WidgetEvent::isValid]] to be false to cancel the widget execution.
     */
    const EVENT_BEFORE_RUN = BaseWidget::EVENT_BEFORE_RUN;

    /**
     * @event WidgetEvent an event raised right after executing a widget.
     */
    const EVENT_AFTER_RUN = BaseWidget::EVENT_AFTER_RUN;
}
