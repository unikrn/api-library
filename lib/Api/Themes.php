<?php
/**
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 *
 * @see        http://mautic.org
 *
 * @license     MIT http://opensource.org/licenses/MIT
 */

namespace Mautic\Api;

/**
 * Themes Context.
 */
class Themes extends Api
{
    /**
     * {@inheritdoc}
     */
    protected $endpoint = 'themes';

    /**
     * {@inheritdoc}
     */
    protected $listName = 'themes';

    /**
     * {@inheritdoc}
     */
    protected $itemName = 'theme';

    protected $temporaryFilePath = null;

    /**
     * {@inheritdoc}
     */
    public function edit($id, array $parameters, $createIfNotExists = false, $timeout = null)
    {
        return $this->actionNotSupported('edit');
    }

    /**
     * @return array|mixed
     */
    public function create(array $parameters, $timeout = null)
    {
        if (!isset($parameters['file'])) {
            throw new \InvalidArgumentException('theme zip file must be set in parameters');
        }

        return parent::create($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function createBatch(array $parameters, $timeout = null)
    {
        return $this->actionNotSupported('createBatch');
    }

    /**
     * {@inheritdoc}
     */
    public function editBatch(array $parameters, $createIfNotExists = false, $timeout = null)
    {
        return $this->actionNotSupported('editBatch');
    }

    /**
     * {@inheritdoc}
     */
    public function deleteBatch(array $ids, $timeout = null)
    {
        return $this->actionNotSupported('deleteBatch');
    }

    /**
     * @return null
     */
    public function getTemporaryFilepath()
    {
        return $this->temporaryFilePath ?: sys_get_temp_dir();
    }

    /**
     * @param null $temporaryFilePath
     */
    public function setTemporaryFilePath($temporaryFilePath)
    {
        $this->temporaryFilePath = $temporaryFilePath;
    }
}
