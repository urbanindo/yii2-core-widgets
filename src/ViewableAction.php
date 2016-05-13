<?php
/**
 * ViewTrait class file.
 * @author Petra Barus <petra.barus@gmail.com>
 */

namespace UrbanIndo\Yii2\CoreWidgets;

/**
 * ViewableAction is an action that provides view capability like widget.
 * The view will be stored in the `view` directory in the same directory with
 * the class.
 *
 * @author Petra Barus <petra.barus@gmail.com>
 * @see \yii\base\ViewContextInterface
 */
abstract class ViewableAction extends \yii\base\Action implements \yii\base\ViewContextInterface
{
    use ViewTrait;
}
