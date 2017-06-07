<?php
/**
 * Created by PhpStorm.
 * User: steve
 * Date: 07/06/17
 * Time: 16:34
 */

namespace Wollanup\Api\Swagger\Test;

use Eukles\Action\ActionAbstract;
use Eukles\Action\ActionInterface;
use Eukles\Service\QueryModifier\QueryModifierInterface;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\UploadedFileInterface;

class FooAction extends ActionAbstract
{
    
    /**
     * Action factory
     *
     * @param ContainerInterface $c
     *
     * @return ActionInterface
     */
    public static function create(ContainerInterface $c)
    {
        // TODO: Implement create() method.
    }
    
    /**
     * @param QueryModifierInterface $qm
     *
     * @return ModelCriteria
     */
    public function createQuery(QueryModifierInterface $qm = null)
    {
        // TODO: Implement createQuery() method.
    }
    
    /**
     * @deprecated
     */
    public function deprecated() { }
    
    /**
     * Get method summary
     *
     * Get method description
     *
     * @return string
     */
    public function get()
    {
        return 'foo';
    }
    
    /**
     * Get method summary
     *
     * Get method description
     *
     * @param int $id
     *
     * @return int
     */
    public function getById($id)
    {
        return $id;
    }
    
    /**
     * @internal
     */
    public function internal() { }
    
    public function noComment() { }
    
    public function upload(UploadedFileInterface $file) { }
}
