<?php

App::uses('Component', 'Controller');
App::uses('Inflector', 'Utility');
App::uses('CakeSession', 'Model/Datasource');

/**
 * The CakePHP FlashComponent provides a way for you to write a flash variable
 * to the session from your controllers, to be rendered in a view with the
 * FlashHelper.
 *
 * @package       Cake.Controller.Component
 */
class FlashComponent extends Component {

    /**
     * Default configuration
     *
     * @var array
     */
    protected $_defaultConfig = array(
            'key' => 'flash',
            'element' => 'default',
            'params' => array(),
            'clear' => false
    );

    /**
     * Constructor
     *
     * @param ComponentCollection $collection The ComponentCollection object
     * @param array $settings Settings passed via controller
     */
    public function __construct(ComponentCollection $collection, $settings = array()) {
        $this->_defaultConfig = $this->merge($this->_defaultConfig, $settings);
    }

    public function merge(array $data, $merge){
        $args = array_slice(func_get_args(), 1);
        $return = $data;

        foreach ($args as &$curArg) {
            $stack[] = array((array)$curArg, &$return);
        }
        unset($curArg);

        while (!empty($stack)) {
            foreach ($stack as $curKey => &$curMerge) {
                foreach ($curMerge[0] as $key => &$val) {
                    if (!empty($curMerge[1][$key]) && (array)$curMerge[1][$key] === $curMerge[1][$key] && (array)$val === $val) {
                        $stack[] = array(&$val, &$curMerge[1][$key]);
                    } elseif ((int)$key === $key && isset($curMerge[1][$key])) {
                        $curMerge[1][] = $val;
                    } else {
                        $curMerge[1][$key] = $val;
                    }
                }
                unset($stack[$curKey]);
            }
            unset($curMerge);
        }
        return $return;
    }
    /**
     * Used to set a session variable that can be used to output messages in the view.
     *
     * In your controller: $this->Flash->set('This has been saved');
     *
     * ### Options:
     *
     * - `key` The key to set under the session's Flash key
     * - `element` The element used to render the flash message. Default to 'default'.
     * - `params` An array of variables to make available when using an element
     *
     * @param string $message Message to be flashed. If an instance
     *   of Exception the exception message will be used and code will be set
     *   in params.
     * @param array $options An array of options.
     * @return void
     */

    public function set($message, $options = array()) {
        $options += $this->_defaultConfig;

        if ($message instanceof Exception) {
            $options['params'] += array('code' => $message->getCode());
            $message = $message->getMessage();
        }

        list($plugin, $element) = pluginSplit($options['element'], true);
        if (!empty($options['plugin'])) {
            $plugin = $options['plugin'] . '.';
        }
        $options['element'] = $plugin . 'Flash/' . $element;

        $messages = array();
        if ($options['clear'] === false) {
            $messages = (array)CakeSession::read('Message.' . $options['key']);
        }

        $newMessage = array(
                'message' => $message,
                'key' => $options['key'],
                'element' => $options['element'],
                'params' => $options['params']
        );

        $messages[] = $newMessage;

        CakeSession::write('Message.' . $options['key'], $messages);
    }

    /**
     * Magic method for verbose flash methods based on element names.
     *
     * For example: $this->Flash->success('My message') would use the
     * success.ctp element under `app/View/Element/Flash` for rendering the
     * flash message.
     *
     * @param string $name Element name to use.
     * @param array $args Parameters to pass when calling `FlashComponent::set()`.
     * @return void
     * @throws InternalErrorException If missing the flash message.
     */
    public function __call($name, $args) {
        $options = array('element' => Inflector::underscore($name));

        if (count($args) < 1) {
            throw new InternalErrorException('Flash message missing.');
        }

        if (!empty($args[1])) {
            $options += (array)$args[1];
        }

        $this->set($args[0], $options);
    }
}
