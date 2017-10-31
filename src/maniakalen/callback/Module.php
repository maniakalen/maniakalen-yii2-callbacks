<?php
/**
 * PHP Version 5.5
 *
 *  Module definition for Yii2 framework 
 *
 * @category Module
 * @package  Callbacks
 * @author   Peter Georgiev <peter.georgiev@concatel.com>
 * @license  GNU GENERAL PUBLIC LICENSE https://www.gnu.org/licenses/gpl.html
 * @link     - 
 */

namespace maniakalen\callback;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Module as BaseModule;

/**
 * Class Module
 *
 *  Module definition for Yii2 framework
 *
 * @category Module
 * @package  Callbacks
 * @author   Peter Georgiev <peter.georgiev@concatel.com>
 * @license  GNU GENERAL PUBLIC LICENSE https://www.gnu.org/licenses/gpl.html
 * @link     -
 */
class Module extends BaseModule implements BootstrapInterface
{
    public $controllerNamespace;
    public $urlRules;
    public $events;
    public $container;
    public $components;

    /**
     * Module initialisation
     *
     * @return null
     */
    public function init()
    {
        parent::init();
        Yii::setAlias('@callback', dirname(__FILE__));
        $config = include Yii::getAlias('@callback/config/main.php');
        Yii::configure($this, $config);

        $this->prepareContainer();

        if (isset($config['aliases']) && !empty($config['aliases'])) {
            Yii::$app->setAliases($config['aliases']);
        }
        return null;
    }


    /**
     * Protected method to add container definition from the config file
     *
     * @return null
     */
    protected function prepareContainer()
    {
        if (!empty($this->container)) {
            if (isset($this->container['definitions'])) {
                $definitions = array_merge(Yii::$container->getDefinitions(), $this->container['definitions']);
                Yii::$container->setDefinitions($definitions);
            }
        }

        return null;
    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     *
     * @return null
     */
    public function bootstrap($app)
    {
        if (is_array($this->components) && !empty($this->components)) {
            $app->setComponents($this->components);
        }
        return null;
    }
}