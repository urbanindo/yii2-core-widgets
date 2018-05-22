<?php
/**
 * WidgetCache class file.
 * @author Petra Barus <petra.barus@gmail.com>
 */

namespace UrbanIndo\Yii2\CoreWidgets\Behaviors;

use yii\base\Behavior;
use yii\base\Widget;
use yii\base\WidgetEvent;
use yii\di\Instance;
use yii\caching\Cache;

/**
 * WidgetCache is a behavior for EventTrait to handle caching.
 *
 * @author Petra Barus <petra.barus@gmail.com>
 * @property-read \yii\base\Widget $owner
 */
class WidgetCache extends Behavior
{
    const CACHE_DURATION_1_MINUTE = 60;

    /**
     * @var Cache|array|string the cache object or the application component ID of the cache object.
     */
    public $cache = 'cache';

    /**
     * @var integer number of seconds that the data can remain valid in cache.
     * Use 0 to indicate that the cached data will never expire.
     */
    public $duration = self::CACHE_DURATION_1_MINUTE;

    /**
     * @var array|Dependency The dependency that the cached content depends on.
     * This can be either a [[Dependency]] object or a configuration array for creating the dependency object.
     * For example,
     *
     * ```php
     * [
     *     'class' => 'yii\caching\DbDependency',
     *     'sql' => 'SELECT MAX(updated_at) FROM post',
     * ]
     * ```
     *
     * would make the output cache depend on the last modified time of all posts.
     * If any post has its modification time changed, the cached content would be invalidated.
     *
     * If [[cacheCookies]] or [[cacheHeaders]] is enabled, then
     * [[\yii\caching\Dependency::reusable]] should be enabled as well to save
     * performance.
     * This is because the cookies and headers are currently stored separately
     *  from the actual page content, causing the dependency to be evaluated twice.
     */
    public $dependency;

    /**
     * @var array list of factors that would cause the variation of the content being cached.
     * Each factor is a string representing a variation (e.g. the language, a GET parameter).
     * The following variation setting will cause the content to be cached in different versions
     * according to the current application language:
     *
     * ~~~
     * [
     *     Yii::$app->language,
     * ]
     * ~~~
     */
    public $variations = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->cache = Instance::ensure($this->cache, Cache::className());
    }

    /**
     * @inheritdoc
     * @param mixed $owner The owner of the behavior.
     */
    public function attach($owner)
    {
        $this->owner = $owner;
        $this->owner->on(Widget::EVENT_BEFORE_RUN, [$this, 'beforeRun']);
    }
    
    /**
     * Handle cache before run.
     * @param WidgetEvent $event The event.
     */
    public function beforeRun(WidgetEvent $event)
    {
        foreach (['cache', 'duration', 'dependency', 'variations'] as $name) {
            $properties[$name] = $this->{$name};
        }
        
        if ($this->owner->getView()->beginCache(get_class($this->owner) . $this->owner->getId(), $properties)) {
            $this->owner->on(Widget::EVENT_AFTER_RUN, [$this, 'afterRun']);
            $event->isValid = true;
        } else {
            $event->isValid = false;
        }
    }
    
    /**
     * Handle cache after run.
     * @param WidgetEvent $event The event.
     */
    public function afterRun(WidgetEvent $event)
    {
        echo $event->result;
        $event->result = '';
        $this->owner->getView()->endCache();
    }
}
