<?php

namespace Handymaster\SystemNotification;

use Illuminate\Support\Facades\Session;

class SystemNotification
{
    /**
     * Alert id in stack
     *
     * @var string
     */
    protected $id;

    /**
     * Configuration options.
     *
     * @var array
     */
    protected $config;

    /**
     * Notify constructor.
     */
    public function __construct()
    {
        $this->id = uniqid('', true);
        $this->setDefaultConfig();
    }

    /**
     * Sets all default config options for an alert.
     *
     * @return void
     */
    protected function setDefaultConfig(): void
    {
        $this->config = [
            'title' => '',
            'message' => '',
            'status' => config('system-notification.status'),
            'to' => config('system-notification.container')
        ];
    }

    /**
     * Flash a message.
     *
     * @param string $title
     * @param string $message
     * @param string|null $status
     * @return $this
     */
    public function notify(string $title = '', string $message = '', ?string $status = null): self
    {
        $this->config['title'] = $title;
        $this->config['message'] = $message;
        if ($status !== null) {
            $this->config['status'] = $status;
        }
        $this->flash();

        return $this;
    }

    /**
     * @param array $config
     * @return SysNotificationRepository
     */
    public function withButton(array $config = []): SysNotificationRepository
    {
        $this->config['button'] = $config;

        return $this;
    }

    /**
     * Display a success status alert message with a message and a title.
     *
     * @param string $title
     * @param string $message
     * @return $this
     */
    public function success(string $title = '', string $message = ''): self
    {
        $this->notify($title, $message, 'success');

        return $this;
    }

    /**
     * Display a info status alert message with a message and a title.
     *
     * @param string $title
     * @param string $message
     * @return $this
     */
    public function info(string $title = '', string $message = ''): self
    {
        $this->notify($title, $message, 'info');

        return $this;
    }

    /**
     * Display a warning status alert message with a message and a title.
     *
     * @param string $title
     * @param string $message
     * @return $this
     */
    public function warning(string $title = '', string $message = ''): self
    {
        $this->notify($title, $message, 'warning');

        return $this;
    }

    /**
     * Display a error status alert message with a message and a title.
     *
     * @param string $title
     * @param string $message
     * @return $this
     */
    public function error(string $title = '', string $message = ''): self
    {
        $this->notify($title, $message, 'error');

        return $this;
    }

    /**
     * Positioned notification dialog
     *
     * @param string $container
     * @return SysNotificationRepository
     */
    public function to(string $container): self
    {
        Session::forget("system-notification.{$this->config['to']}.{$this->id}");
        $this->config['to'] = $container;
        $this->flash();

        return $this;
    }

    /**
     * Flash the config options for alert.
     *
     * @return void
     */
    public function flash()
    {
        Session::flash("system-notification.{$this->config['to']}.{$this->id}", $this->config);
    }

    /**
     * Extract notifications for specified container
     *
     * @param $container
     * @return false|string
     */
    public function extract($container)
    {
        $notifications = Session::pull("system-notification.$container");
        $notifications = array_values($notifications);
        $notifications = array_unique($notifications, SORT_REGULAR);

        return json_encode($notifications);
    }
}
