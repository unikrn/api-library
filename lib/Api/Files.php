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
 * Files Context.
 */
class Files extends Api
{
    /**
     * {@inheritdoc}
     */
    protected $endpoint = 'files/images';

    /**
     * {@inheritdoc}
     */
    protected $listName = 'files';

    /**
     * {@inheritdoc}
     */
    protected $itemName = 'file';

    /**
     * Changes the file folder to look at.
     *
     * @param string $folder [images, assets]
     */
    public function setFolder($folder = 'assets')
    {
        $folder         = str_replace('/', '.', $folder);
        $this->endpoint = 'files/'.$folder;
    }

    /**
     * {@inheritdoc}
     */
    public function edit($id, array $parameters, $createIfNotExists = false, $timeout = null)
    {
        return $this->actionNotSupported('edit');
    }

    /**
     * @param array $parameters
     * @param int|null $timeout
     * @return array|\array[][]|bool|mixed
     */
    public function create(array $parameters, $timeout = null)
    {
        if (!isset($parameters['file'])) {
            throw new \InvalidArgumentException('file must be set in parameters');
        }

        return parent::create($parameters, $timeout);
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
}
