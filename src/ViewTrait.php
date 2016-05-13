<?php
/**
 * ViewTrait class file.
 * @author Petra Barus <petra.barus@gmail.com>
 */

namespace UrbanIndo\Yii2\CoreWidgets;

use Yii;
use ReflectionClass;

/**
 * ViewTrait provides render in `views` directory.
 *
 * The class using this trait must implement \yii\base\ViewContextInterface.
 *
 * @author Petra Barus <petra.barus@gmail.com>
 * @see \yii\base\ViewContextInterface
 */
trait ViewTrait
{

    /**
     * @var \yii\base\View
     */
    private $_view;

    /**
     * Returns the directory containing the view files for this widget.
     * The default implementation returns the 'views' subdirectory under
     * the directory containing the widget class file.
     * @return string the directory containing the view files for this widget.
     */
    public function getViewPath()
    {
        $class = new ReflectionClass($this);
        return dirname($class->getFileName()) . DIRECTORY_SEPARATOR . 'views';
    }

    /**
     * Returns the view object that can be used to render views or view files.
     * The [[render()]] and [[renderFile()]] methods will use
     * this view object to implement the actual view rendering.
     * If not set, it will default to the "view" application component.
     * @return \yii\web\View the view object that can be used to render views or view files.
     */
    public function getView()
    {
        if ($this->_view === null) {
            $this->_view = Yii::$app->getView();
        }

        return $this->_view;
    }

    /**
     * Renders a view.
     * The view to be rendered can be specified in one of the following formats:
     *
     * - path alias (e.g. "@app/views/site/index");
     * - absolute path within application (e.g. "//site/index"): the view name starts with double slashes.
     *   The actual view file will be looked for under the [[Application::viewPath|view path]] of the application.
     * - absolute path within module (e.g. "/site/index"): the view name starts with a single slash.
     *   The actual view file will be looked for under the [[Module::viewPath|view path]] of the currently
     *   active module.
     * - relative path (e.g. "index"): the actual view file will be looked for under [[viewPath]].
     *
     * If the view name does not contain a file extension, it will use the default one `.php`.
     *
     * @param string $view   The view name.
     * @param array  $params The parameters (name-value pairs) that should be made available in the view.
     * @return string The rendering result.
     * @throws \yii\base\InvalidParamException if the view file does not exist.
     */
    public function render($view, $params = [])
    {
        return $this->getView()->render($view, $params, $this);
    }
}
