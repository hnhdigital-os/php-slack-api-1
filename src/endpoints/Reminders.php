<?php

namespace HnhDigital\SlackApi;

/*
 * This file is part of the PHP Slack API.
 *
 * (c) H&H|Digital <hello@hnh.digital>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use HnhDigital\SlackApi\Foundation\Base;

/**
 * This is the Reminders class.
 *
 * This file is automatically generated.
 *
 * @link 
 *
 * @author Rocco Howard <rocco@hnh.digital>
 */
class Reminders extends Base
{
    /**
     * Endpoint.
     *
     * @var string
     */
    protected $endpoint = '';

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets information about a reminder.
     *
     * @link https://api.slack.com/methods/reminders.info
     *
     * @return array
     */
    public function info($optional = [])
    {
        return $this->apiCall('get', '/reminders.info');
    }

    /**
     * Lists all reminders created by or for a given user.
     *
     * @link https://api.slack.com/methods/reminders.list
     *
     * @return array
     */
    public function getList()
    {
        return $this->apiCall('get', '/reminders.list');
    }

    /**
     * Creates a reminder.
     *
     * @param array $optional
     *                        - [text=null] (string) The content of the reminder
     *                        - [user=null] (string) The user who will receive the reminder. If no user is
     *                        specified, the reminder will go to user who created it.
     *                        - [time=null] (string) When this reminder should happen: the Unix timestamp (up
     *                        to five years from now), the number of seconds until the
     *                        reminder (if within 24 hours), or a natural language
     *                        description (Ex. "in 15 minutes," or "every Thursday")
     *
     * @link https://api.slack.com/methods/reminders.add
     *
     * @return mixed
     */
    public function add($optional = [])
    {
        return $this->apiCall('post', '/reminders.add', ['json' => array_merge([
        ], $optional)]);
    }

    /**
     * Marks a reminder as complete.
     *
     * @param array $optional
     *                        - [reminder=null] (string) The ID of the reminder to be marked as complete
     *
     * @link https://api.slack.com/methods/reminders.complete
     *
     * @return mixed
     */
    public function complete($optional = [])
    {
        return $this->apiCall('post', '/reminders.complete', ['json' => array_merge([
        ], $optional)]);
    }

    /**
     * Deletes a reminder.
     *
     * @param array $optional
     *                        - [reminder=null] (string) The ID of the reminder
     *
     * @link https://api.slack.com/methods/reminders.delete
     *
     * @return mixed
     */
    public function delete($optional = [])
    {
        return $this->apiCall('post', '/reminders.delete', ['json' => array_merge([
        ], $optional)]);
    }
}
