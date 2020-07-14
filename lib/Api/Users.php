<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     MIT http://opensource.org/licenses/MIT
 */

namespace Mautic\Api;

/**
 * Users Context
 */
class Users extends Api
{

    /**
     * {@inheritdoc}
     */
    protected $endpoint = 'users';

    /**
     * {@inheritdoc}
     */
    protected $listName = 'users';

    /**
     * {@inheritdoc}
     */
    protected $itemName = 'user';

    /**
     * {@inheritdoc}
     */
    protected $searchCommands = array(
        'ids',
        'is:admin',
        'is:active',
        'is:inactive',
        'email',
        'role',
        'username',
        'name',
        'position',
    );

    /**
     * Get your (API) user
     *
     * @param int $timeout
     *
     * @return array|mixed
     */
    public function getSelf($timeout = null)
    {
        return $this->makeRequest($this->endpoint.'/self', [], 'GET', $timeout);
    }

    /**
     * Get list of permissions for a user
     *
     * @param  int $id
     * @param  string|array $permissions
     * @param int $timeout
     *
     * @return array|mixed
     */
    public function checkPermission($id, $permissions, $timeout = null)
    {
        return $this->makeRequest($this->endpoint.'/'.$id.'/permissioncheck', array('permissions' => $permissions), 'POST', $timeout);
    }
}