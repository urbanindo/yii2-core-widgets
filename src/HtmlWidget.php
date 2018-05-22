<?php

/**
 * HtmlWidget class.
 *
 * @author Petra Barus <petra.barus@gmail.com>
 */

namespace UrbanIndo\Yii2\CoreWidgets;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\Widget;

/**
 * HtmlWidget is an extend class for widgets that provides enclosing tag and events.
 *
 * Use this for advanced widget that uses behavior like caching, etc.
 *
 * @author Petra Barus <petra.barus@gmail.com>
 */
abstract class HtmlWidget extends Widget
{
    use EventTrait;

    /**
     * To modify the tag that encloses the widget, update 'tag' element on this array.
     * @var array the HTML attributes for the widget container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    /**
     * The tag for enclosing this widget.
     * @var string
     */
    private $_tag;

    /**
     * Initializes the widget.
     * This method will register the bootstrap asset bundle. If you override this method,
     * make sure you call the parent implementation first.
     */
    public function init()
    {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }

    /**
     * @return string
     */
    public function beginTag()
    {
        $this->_tag = ArrayHelper::getValue($this->options, 'tag', 'div');
        if ($this->_tag == false) {
            return '';
        }
        unset($this->options['tag']);
        return Html::beginTag($this->_tag, $this->options);
    }

    /**
     * @return string
     */
    public function endTag()
    {
        if ($this->_tag == false) {
            return '';
        }
        return Html::endTag($this->_tag);
    }
}
