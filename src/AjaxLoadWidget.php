<?php
/**
 * AjaxLoadWidget class file.
 * @author Petra Barus <petra.barus@gmail.com>
 */

namespace UrbanIndo\Yii2\CoreWidgets;

use yii\helpers\Url;
use yii\web\JqueryAsset;

/**
 * AjaxLoadWidget is a widget that loads its content from URL.
 * @author Petra Barus <petra.barus@gmail.com>
 */
class AjaxLoadWidget extends HtmlWidget
{
    /**
     * The URL to load.
     * @var string
     */
    public $url;
    
    /**
     * Content for loading.
     * @var string
     */
    public $loadingContent = '';
    
    /**
     * @var string
     */
    public $errorContent = '';
    
    /**
     * @var string
     */
    public $errorCssClass = '';
    
   /**
     * @return void
     */
    public function run()
    {
        echo $this->beginTag();
        echo $this->loadingContent;
        $this->registerJs();
        echo $this->endTag();
    }
    
    /**
     * @return void
     */
    private function registerJs()
    {
        $url = Url::to($this->url);

        JqueryAsset::register($this->getView());
        $this->getView()->registerJs(<<<JS
$('#{$this->options['id']}').load('{$url}', function (response, textStatus, xhr) {
    if (textStatus == "error") {
        $(this).html('{$this->errorContent}');
        $(this).addCssClass('{$this->errorCssClass}');
    }
});
JS
        );
    }
}
