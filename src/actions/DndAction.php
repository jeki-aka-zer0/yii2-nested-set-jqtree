<?php

namespace jekiakazer0\nsjqtree\src\actions;

use Yii;
use yii\base;
use yii\base\Exception;
use creocoder\nestedsets\NestedSetsBehavior;

/**
 * Class DndAction
 * @package jekiakazer0\nsjqtree\src\actions
 *
 * action - move nested set node via creocoder/yii2-nested-sets
 *
 * Usage:
 * <code>
 * public function actions(){
 *  return [
 *      'dnd' => [
 *          'class' => 'jekiakazer0\nsjqtree\src\actions\DndAction',
 *      ],
 *  ];
 * }
 * </code>
 *
 * @author zer0 <zer0.stat@mail.ru>
 * @link https://github.com/jeki-aka-zer0
 */
class DndAction extends base\Action
{
    /**
     * @var string - name of find model method
     */
    public $findModelMethod = 'findModel';
    /**
     * @var string - name of position key in $_POST array
     */
    public $positionKey = self::POSITION_KEY;
    /**
     * @var string - name of moved node key in $_POST array
     */
    public $movedKey = self::MOVED_KEY;
    /**
     * @var string - name of target node key in $_POST array
     */
    public $targetKey = self::TARGET_KEY;

    const POSITION_KEY = 'position';
    const MOVED_KEY = 'moved';
    const TARGET_KEY = 'target';


    /**
     * do action
     * @return array
     * @throws Exception
     */
    public function run()
    {
        $method = $this->findModelMethod;
        if (!method_exists($this->controller, $method)) {
            throw new Exception("Method Controller::{$method}() not found");
        }

        /**
         * @var NestedSetsBehavior $moved
         */
        $moved = $this->controller->{$method}(Yii::$app->request->post($this->movedKey));
        $target = $this->controller->{$method}(Yii::$app->request->post($this->targetKey));

        switch (Yii::$app->request->post($this->positionKey)) {
            case 'before':
                $moved->insertBefore($target);
                break;
            case 'after':
                $moved->insertAfter($target);
                break;
            case 'inside':
                $moved->appendTo($target);
                break;
            default:
                throw new Exception('Invalid position parameter.');
                break;
        }

        return [
            'success' => true
        ];
    }
}
