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
 * Contacts Context
 */
class Contacts extends Api
{

    /**
     * Contact unsubscribed themselves.
     */
    const UNSUBSCRIBED = 1;

    /**
     * Contact was unsubscribed due to an unsuccessful send.
     */
    const BOUNCED = 2;

    /**
     * Contact was manually unsubscribed by user.
     */
    const MANUAL = 3;

    /**
     * {@inheritdoc}
     */
    protected $endpoint = 'contacts';

    /**
     * {@inheritdoc}
     */
    protected $listName = 'contacts';

    /**
     * {@inheritdoc}
     */
    protected $itemName = 'contact';

    /**
     * @var array
     */
    protected $bcRegexEndpoints = array(
        'contacts/(.*?)/dnc/(.*?)/add'    => 'contacts/$1/dnc/add/$2', // 2.6.0
        'contacts/(.*?)/dnc/(.*?)/remove' => 'contacts/$1/dnc/remove/$2' // 2.6.0
    );

    /**
     * {@inheritdoc}
     */
    protected $searchCommands = array(
        'ids',
        'is:anonymous',
        'is:unowned',
        'is:mine',
        'name',
        'email',
        'segment',
        'company',
        'owner',
        'ip',
        'common',
    );

    /**
     * @param string $search
     * @param int    $start
     * @param int    $limit
     * @param string $orderBy
     * @param string $orderByDir
     * @param bool   $publishedOnly
     * @param bool   $minimal
     * @param int   $timeout
     *
     * @return array|mixed
     */
    public function getIdentified($search = '', $start = 0, $limit = 0, $orderBy = '', $orderByDir = 'ASC', $publishedOnly = false, $minimal = false, $timeout = null)
    {
        $search = ($search) ? "$search !is:anonymous" : '!is:anonymous';

        return $this->getList($search, $start, $limit, $orderBy, $orderByDir, $publishedOnly, $minimal, $timeout);
    }

    /**
     * Get a list of users available as contact owners
     *
     * @param int $timeout
     *
     * @return array|mixed
     */
    public function getOwners($timeout = null)
    {
        return $this->makeRequest($this->endpoint.'/list/owners', [], 'GET', $timeout);
    }

    /**
     * Get a list of custom fields
     *
     * @param int $timeout
     *
     * @return array|mixed
     */
    public function getFieldList($timeout = null)
    {
        return $this->makeRequest($this->endpoint.'/list/fields', [], 'GET', $timeout);
    }

    /**
     * Get a list of contact segments
     *
     * @param int $timeout
     *
     * @return array|mixed
     */
    public function getSegments($timeout = null)
    {
        return $this->makeRequest($this->endpoint.'/list/segments', [], 'GET', $timeout);
    }

    /**
     * Get a list of a contact's engagement events
     *
     * @param int    $id Contact ID
     * @param string $search
     * @param array  $includeEvents
     * @param array  $excludeEvents
     * @param string $orderBy
     * @param string $orderByDir
     * @param int    $page
     * @param int    $timeout
     *
     * @return array|mixed
     *
     * @deprecated 2.10.0 Use getActivityForContact instead. The response is slightly different.
     */
    public function getEvents(
        $id,
        $search = '',
        array $includeEvents = array(),
        array $excludeEvents = array(),
        $orderBy = '',
        $orderByDir = 'ASC',
        $page = 1,
        $timeout = null
    ) {
        return $this->fetchActivity('/'.$id.'/events', $search, $includeEvents, $excludeEvents, $orderBy, $orderByDir, $page, null, null, $timeout);
    }

    /**
     * Get a list of contact activity events for all contacts
     *
     * @param int       $id Contact ID
     * @param string    $search
     * @param array     $includeEvents
     * @param array     $excludeEvents
     * @param string    $orderBy
     * @param string    $orderByDir
     * @param int       $page
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     * @param int    $timeout
         *
     * @return array|mixed
     */
    public function getActivityForContact(
        $id,
        $search = '',
        array $includeEvents = array(),
        array $excludeEvents = array(),
        $orderBy = '',
        $orderByDir = 'ASC',
        $page = 1,
        \DateTime $dateFrom = null,
        \DateTime $dateTo = null,
        $timeout = null
    ) {
        return $this->fetchActivity('/'.$id.'/activity', $search, $includeEvents, $excludeEvents, $orderBy, $orderByDir, $page, $dateFrom, $dateTo, $timeout);
    }

    /**
     * Get a list of contact engagement events.
     * Not related to a specific contact ID
     *
     * @param string    $search
     * @param array     $includeEvents
     * @param array     $excludeEvents
     * @param string    $orderBy
     * @param string    $orderByDir
     * @param int       $page
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     * @param int       $timeout
     *
     * @return array|mixed
     */
    public function getActivity(
        $search = '',
        array $includeEvents = array(),
        array $excludeEvents = array(),
        $orderBy = '',
        $orderByDir = 'ASC',
        $page = 1,
        \DateTime $dateFrom = null,
        \DateTime $dateTo = null,
        int $timeout = null
    ) {
        return $this->fetchActivity('/activity', $search, $includeEvents, $excludeEvents, $orderBy, $orderByDir, $page, $dateFrom, $dateTo, $timeout);
    }

    /**
     * Get a list of contact activity events for all contacts
     *
     * @param string    $path of the URL after the endpoint
     * @param string    $search
     * @param array     $includeEvents
     * @param array     $excludeEvents
     * @param string    $orderBy
     * @param string    $orderByDir
     * @param int       $page
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     * @param int       $timeout
     *
     * @return array|mixed
     */
    protected function fetchActivity(
        $path,
        $search = '',
        array $includeEvents = array(),
        array $excludeEvents = array(),
        $orderBy = '',
        $orderByDir = 'ASC',
        $page = 1,
        \DateTime $dateFrom = null,
        \DateTime $dateTo = null,
        $timeout = null
    ) {
        $parameters = array(
            'filters' => array(
                'search'        => $search,
                'includeEvents' => $includeEvents,
                'excludeEvents' => $excludeEvents,
            ),
            'order'   => array(
                $orderBy,
                $orderByDir,
            ),
            'page'    => $page
        );

        if ($dateFrom) {
            $parameters['filters']['dateFrom'] = $dateFrom->format('Y-m-d H:i:s');
        }

        if ($dateTo) {
            $parameters['filters']['dateTo'] = $dateTo->format('Y-m-d H:i:s');
        }

        return $this->makeRequest($this->endpoint.$path, $parameters, 'GET', $timeout);
    }

    /**
     * Get a list of a contact's notes
     *
     * @param int    $id Contact ID
     * @param string $search
     * @param int    $start
     * @param int    $limit
     * @param string $orderBy
     * @param string $orderByDir
     * @param int   $timeout
     *
     * @return array|mixed
     */
    public function getContactNotes($id, $search = '', $start = 0, $limit = 0, $orderBy = '', $orderByDir = 'ASC', $timeout = null)
    {
        $parameters = array(
            'search'     => $search,
            'start'      => $start,
            'limit'      => $limit,
            'orderBy'    => $orderBy,
            'orderByDir' => $orderByDir,
        );

        $parameters = array_filter($parameters);

        return $this->makeRequest($this->endpoint.'/'.$id.'/notes', $parameters, 'GET', $timeout);
    }

    /**
     * Get a list of a contact's devices
     *
     * @param int    $id Contact ID
     * @param string $search
     * @param int    $start
     * @param int    $limit
     * @param string $orderBy
     * @param string $orderByDir
     * @param int   $timeout
     *
     * @return array|mixed
     */
    public function getContactDevices($id, $search = '', $start = 0, $limit = 0, $orderBy = '', $orderByDir = 'ASC', $timeout = null)
    {
        $parameters = array(
            'search'     => $search,
            'start'      => $start,
            'limit'      => $limit,
            'orderBy'    => $orderBy,
            'orderByDir' => $orderByDir,
        );

        $parameters = array_filter($parameters);

        return $this->makeRequest($this->endpoint.'/'.$id.'/devices', $parameters, 'GET', $timeout);
    }

    /**
     * Get a list of smart segments the contact is in
     *
     * @param $id
     * @param int   $timeout
     *
     * @return array|mixed
     */
    public function getContactSegments($id, $timeout = null)
    {
        return $this->makeRequest($this->endpoint.'/'.$id.'/segments', [], 'GET', $timeout);
    }

    /**
     * Get a list of companies the contact is in
     *
     * @param $id
     * @param int $timeout
     *
     * @return array|mixed
     */
    public function getContactCompanies($id, $timeout = null)
    {
        return $this->makeRequest($this->endpoint.'/'.$id.'/companies', [], 'GET', $timeout);
    }

    /**
     * Get a list of campaigns the contact is in
     *
     * @param $id
     * @param int $timeout
     *
     * @return array|mixed
     */
    public function getContactCampaigns($id, $timeout = null)
    {
        return $this->makeRequest($this->endpoint.'/'.$id.'/campaigns', [], 'GET', $timeout);
    }

    /**
     * Add the points to a contact
     *
     * @param int   $id
     * @param int   $points
     * @param array $parameters 'eventName' and 'actionName'
     * @param int   $timeout
     *
     * @return mixed
     */
    public function addPoints($id, $points, array $parameters = array(), $timeout = null)
    {
        return $this->makeRequest('contacts/'.$id.'/points/plus/'.$points, $parameters, 'POST', $timeout);
    }

    /**
     * Subtract points from a contact
     *
     * @param int   $id
     * @param int   $points
     * @param array $parameters 'eventName' and 'actionName'
     * @param int   $timeout
     *
     * @return mixed
     */
    public function subtractPoints($id, $points, array $parameters = array(), $timeout = null)
    {
        return $this->makeRequest('contacts/'.$id.'/points/minus/'.$points, $parameters, 'POST', $timeout);
    }

    /**
     * Adds Do Not Contact
     *
     * @param int    $id
     * @param string $channel
     * @param int    $reason
     * @param null   $channelId
     * @param string $comments
     * @param int   $timeout
     *
     * @return array|mixed
     */
    public function addDnc($id, $channel = 'email', $reason = Contacts::MANUAL, $channelId = null, $comments = 'via API', $timeout = null) {

        return $this->makeRequest(
            'contacts/'.$id.'/dnc/'.$channel.'/add',
            array(
                'reason'    => $reason,
                'channelId' => $channelId,
                'comments'  => $comments,
            ),
            'POST',
            $timeout
        );
    }

    /**
     * Removes Do Not Contact
     *
     * @param int    $id
     * @param string $channel
     * @param int   $timeout
     *
     * @return mixed
     */
    public function removeDnc($id, $channel = 'email', $timeout = null)
    {
        return $this->makeRequest(
            'contacts/'.$id.'/dnc/'.$channel.'/remove',
            array(),
            'POST',
            $timeout
        );
    }

    /**
     * Add UTM Tags to Contact
     *
     * @param int    $id
     * @param array  $utmTags
     * @param int    $timeout
     *
     * @return mixed
     */
    public function addUtm($id, $utmTags, int $timeout = null) {
        return $this->makeRequest(
            'contacts/'.$id.'/utm/add',
            $utmTags,
            'POST',
            $timeout
        );
    }

    /**
     * Remove UTM Tags from a Contact
     *
     * @param int    $id
     * @param int    $utmId
     * @param int    $timeout
     *
     * @return mixed
     */
    public function removeUtm($id, $utmId, int $timeout = null) {
        return $this->makeRequest(
            'contacts/'.$id.'/utm/'.$utmId.'/remove',
            array(),
            'POST',
            $timeout
        );
    }

    /**
     * Merge 2 contacts
     *
     * @param int    $id
     * @param int    $merge_contact_id
     *
     * @return array|mixed
     */
    public function merge($id, $merge_contact_id){
        $supported = $this->isSupported('merge');
        return (true === $supported) ? $this->makeRequest('contacts/'.$id.'/merge', ['lead_to_merge' => $merge_contact_id], 'POST') : $supported;
    }

}
