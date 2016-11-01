<?php

namespace Trizz\DirectAdmin;

class MailingList extends DirectAdmin
{
    /**
     * Get a list of all available mailing lists. Results are returned as an array with the following keys:
     * - name
     * - subscribers
     * - digest_subscribers
     *
     * @return array Array containing the mailing lists and subscription counts.
     */
    public function lists()
    {
        $mailingLists = $this->sendRequest('/CMD_API_EMAIL_LIST');
        $returnData = [];

        foreach ($mailingLists as $name => $stats) {
            $explodedStats = explode(':', $stats);
            $returnData[] = [
                'name' => $name,
                'subscribers' => $explodedStats[0],
                'digest_subscribers' => $explodedStats[1],
            ];
        }

        return $returnData;
    }

    /**
     * Add a new mailing list.
     *
     * @param string $name The name of the new mailing list.
     *
     * @return array The DirectAdmin API Response.
     */
    public function addList($name)
    {
        return $this->sendRequest('/CMD_API_EMAIL_LIST', ['action' => 'create', 'name' => $name]);
    }

    /**
     * Remove a mailing list.
     *
     * @param string $name The name of the mailing list to delete.
     *
     * @return array The DirectAdmin API Response.
     */
    public function deleteList($name)
    {
        return $this->sendRequest('/CMD_API_EMAIL_LIST', [
            'action' => 'delete',
            'select0' => $name,
        ]);
    }

    /**
     * Get all subscribers for the specified list. The $subscriberType can be: subscribers or digest_subscribers. By
     * default both types are returned.
     *
     * @param string $listName       The name of the list.
     * @param string $subscriberType The type of subscribers to get.
     *
     * @return array Depending on $subscriberType an array or a multidimensional array.
     */
    public function getSubscribers($listName, $subscriberType = 'all')
    {
        $rawMembers = $this->sendRequest('/CMD_API_EMAIL_LIST', ['action' => 'view', 'name' => $listName]);

        $members = ['subscribers' => [], 'digest_subscribers' => []];
        foreach ($rawMembers as $key => $member) {
            $type = substr($key, 0, 1) === 's' ? 'subscribers' : 'digest_subscribers';
            $members[$type][] = $member;
        }

        return ($subscriberType === 'all') ? $members : $members[$subscriberType];
    }

    /**
     * Add a single email address to the specified mailing list.
     *
     * @param string $address The email address to add.
     * @param string $list    The name of the list.
     * @param string $type    Can be "list" or "digest". Default: list.
     *
     * @return array The DirectAdmin API response.
     */
    public function addAddress($address, $list, $type = 'list')
    {
        return $this->addAddresses([$address], $list, $type);
    }

    /**
     * Add multiple email addresses to the specified mailing list.
     *
     * @param array  $addresses The email addresses to add.
     * @param string $list      The name of the list.
     * @param string $type      Can be "list" or "digest". Default: list.
     *
     * @return array The DirectAdmin API response.
     */
    public function addAddresses($addresses, $list, $type = 'list')
    {
        return $this->sendRequest('/CMD_API_EMAIL_LIST', [
            'action' => 'add',
            'name' => $list,
            'type' => $type,
            'email' => implode(',', $addresses)
        ]);
    }

    /**
     * Delete an email address from the specified list.
     *
     * @param string $address The email address to delete.
     * @param string $list    The name of the list.
     * @param string $type    Can be "list" or "digest". Default: list.
     *
     * @return array The DirectAdmin API response.
     */
    public function deleteAddress($address, $list, $type = 'list')
    {
        return $this->deleteAddresses([$address], $list, $type);
    }

    /**
     * Delete multiple email addresses from the specified mailing list.
     *
     * @param array  $addresses The email addresses to add.
     * @param string $list      The name of the list.
     * @param string $type      Can be "list" or "digest". Default: list.
     *
     * @return array The DirectAdmin API response.
     */
    public function deleteAddresses($addresses, $list, $type = 'list')
    {
        foreach ($addresses as $key => $address) {
            $postFields['select'.$key] = $address;
        }

        return $this->sendRequest('/CMD_API_EMAIL_LIST', array_merge([
                'action' => $type === 'list' ? 'delete_subscriber' : 'delete_subscriber_digest',
                'name' => $list,
            ], $postFields)
        );
    }
}