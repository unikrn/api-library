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
 * Companies Context.
 */
class Companies extends Api
{
    /**
     * {@inheritdoc}
     */
    protected $endpoint = 'companies';

    /**
     * {@inheritdoc}
     */
    protected $listName = 'companies';

    /**
     * {@inheritdoc}
     */
    protected $itemName = 'company';

    /**
     * @var array
     */
    protected $bcRegexEndpoints = [
        'companies/(.*?)/contact/(.*?)/add'    => 'companies/$1/contact/add/$2', // 2.6.0
        'companies/(.*?)/contact/(.*?)/remove' => 'companies/$1/contact/remove/$2', // 2.6.0
    ];

    /**
     * {@inheritdoc}
     */
    protected $searchCommands = [
        'ids',
        'is:mine',
    ];

    /**
     * Add a contact to the company.
     *
     * @param int $id        Company ID
     * @param int $contactId Contact ID
     * @param int $timeout
     *
     * @return array|mixed
     */
    public function addContact($id, $contactId, $timeout = null)
    {
        return $this->makeRequest($this->endpoint.'/'.$id.'/contact/'.$contactId.'/add', [], 'POST', $timeout);
    }

    /**
     * Remove a contact from the company.
     *
     * @param int $id        Company ID
     * @param int $contactId Contact ID
     * @param int $timeout
     *
     * @return array|mixed
     */
    public function removeContact($id, $contactId, $timeout = null)
    {
        return $this->makeRequest($this->endpoint.'/'.$id.'/contact/'.$contactId.'/remove', [], 'POST', $timeout);
    }
}
