<?php

declare(strict_types=1);

namespace PerfectApp\Flash;

use PerfectApp\Session\SessionInterface;

/**
 *
 */
class FlashMessage
{
    /**
     * @var SessionInterface
     */
    private SessionInterface $session;

    /**
     * @var array
     */
    private array $config;

    /**
     * @param SessionInterface $session
     * @param array $config
     */
    public function __construct(SessionInterface $session, array $config)
    {
        $this->session = $session;
        $this->config = $config;
    }

    /**
     * @param string $type
     * @param string $action
     * @param string $icon
     * @return void
     */
    public function set(string $type, string $action, string $icon = ''): void
    {
        $allowedTypes = ['primary', 'secondary', 'light', 'dark', 'success', 'info', 'warning', 'danger'];

        if (!in_array($type, $allowedTypes)) {
            $message = sprintf('ERROR: Invalid type "%s" provided', $type);
            $this->addMessage('danger', $message, '');
            return;
        }

        if (!isset($this->config[$type][$action])) {
            $message = sprintf('ERROR: Invalid action "%s" provided', $action);
            $this->addMessage('danger', $message, '');
            return;
        }

        $message = $this->config[$type][$action];
        $this->addMessage($type, $message, $icon);
    }

    /**
     * @return void
     */
    public function display(): void
    {
        $messages = $this->session->get('flash_messages') ?? [];

        foreach ($messages as $message) {
            printf(
                '<div class="alert alert-%s alert-dismissible fade show" role="alert">%s <strong>%s</strong>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>',
                $message['type'],
                $message['icon'],
                $message['message']
            );
        }

        $this->session->delete('flash_messages');
    }

    /**
     * @param string $type
     * @param string $message
     * @param string $icon
     * @return void
     */
    private function addMessage(string $type, string $message, string $icon): void
    {
        $messages = $this->session->get('flash_messages') ?? [];
        $messages[] = ['type' => $type, 'message' => $message, 'icon' => $icon];
        $this->session->set('flash_messages', $messages);
    }
}
