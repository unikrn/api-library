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
 * Leads Context
 *
 * This class is deprecated and will be removed in future versions! Use Contacts instead!
 *
 * @deprecated Use Contacts instead!
 */
class Leads extends Contacts
{
    /**
     * Get a list of lead segments
     *
     * @param int $timeout
     * @return array|mixed
     */
    public function getLists($timeout = null)
    {
        return $this->makeRequest('contacts/list/segments', [], 'GET', $timeout);
    }

    /**
     * @param        $id
     * @param string $search
     * @param int    $start
     * @param int    $limit
     * @param string $orderBy
     * @param string $orderByDir
     * @param int $timeout
     *
     * @return array|mixed
     */
    public function getLeadNotes($id, $search = '', $start = 0, $limit = 0, $orderBy = '', $orderByDir = 'ASC', $timeout = null)
    {
        $parameters = array(
            'search'        => $search,
            'start'         => $start,
            'limit'         => $limit,
            'orderBy'       => $orderBy,
            'orderByDir'    => $orderByDir,
        );

        $parameters = array_filter($parameters);

        return $this->makeRequest('contacts/'.$id.'/notes', $parameters, 'GET', $timeout);
    }

    /**
     * @param $id
     * @param int $timeout
     *
     * @return array|mixed
     */
    public function getLeadLists($id, $timeout = 6)
    {
        return $this->makeRequest('contacts/'.$id.'/segments', [], 'GET', $timeout);
    }

    /**
     * @param $id
     * @param int $timeout
     *
     * @return array|mixed
     */
    public function getLeadCampaigns($id, int $timeout = null)
    {
        return $this->makeRequest('contacts/'.$id.'/campaigns', [], 'GET', $timeout);
    }
}
