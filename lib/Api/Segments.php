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
 * Segments Context.
 */
class Segments extends Api
{
    /**
     * {@inheritdoc}
     */
    protected $endpoint = 'segments';

    /**
     * {@inheritdoc}
     */
    protected $listName = 'lists';

    /**
     * {@inheritdoc}
     */
    protected $itemName = 'list';

    /**
     * @var array
     */
    protected $bcRegexEndpoints = [
        'segments/(.*?)/contact/(.*?)/add'    => 'segments/$1/contact/add/$2', // 2.6.0
        'segments/(.*?)/contact/(.*?)/remove' => 'segments/$1/contact/remove/$2', // 2.6.0
    ];

    /**
     * Add a contact to the segment.
     *
     * @param int $segmentId Segment ID
     * @param int $contactId Contact ID
     * @param int $timeout
     *
     * @return array|mixed
     */
    public function addContact($segmentId, $contactId, $timeout = null)
    {
        return $this->makeRequest($this->endpoint.'/'.$segmentId.'/contact/'.$contactId.'/add', [], 'POST'. $timeout);
    }

    /**
     * Add a contact list of ids to the segment
     * list of contact must be added in ids[] query parameter.
     *
     * @param int   $segmentId  Segment ID
     * @param array $contactIds
     * @param int $timeout
     *
     * @return array|mixed
     */
    public function addContacts($segmentId, $contactIds, $timeout = null)
    {
        return $this->makeRequest($this->endpoint.'/'.$segmentId.'/contacts/add', $contactIds, 'POST', $timeout);
    }

    /**
     * Add a lead to the segment.
     *
     * @deprecated 2.0.1, use addContact() instead
     *
     * @param int $id     Segment ID
     * @param int $leadId Lead ID
     *
     * @return array|mixed
     */
    public function addLead($id, $leadId)
    {
        return $this->addContact($id, $leadId);
    }

    /**
     * Remove a contact from the segment.
     *
     * @param int $segmentId Segment ID
     * @param int $contactId Contact ID
     * @param int $timeout
     *
     * @return array|mixed
     */
    public function removeContact($segmentId, $contactId, $timeout = null)
    {
        return $this->makeRequest($this->endpoint.'/'.$segmentId.'/contact/'.$contactId.'/remove', [], 'POST', $timeout);
    }

    /**
     * Remove a lead from the segment.
     *
     * @deprecated 2.0.1, use addContact() instead
     *
     * @param int $id     Segment ID
     * @param int $leadId Lead ID
     *
     * @return array|mixed
     */
    public function removeLead($id, $leadId)
    {
        return $this->removeContact($id, $leadId);
    }

    /**
     * Returns an array of all segments and the number of leads per segment
     *
     * @param null|integer $mincount    Minimum number of leads a segment must contain to be included in the result
     * @return array|mixed
     */
    public function getLeadCounts($mincount = null){
        return $this->makeRequest($this->endpoint.'/leadcounts', ['mincount' => $mincount], 'POST');
    }
}
