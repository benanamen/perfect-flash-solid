<?php
declare(strict_types=1);

namespace Unit;

use PerfectApp\Flash\FlashMessage;
use PerfectApp\Session\Session;

use PHPUnit\Framework\TestCase;

final class FlashMessageTest extends TestCase
{
    private FlashMessage $flashMessage;

    private array $config = [
        'primary' => [
            'created' => 'Item created successfully'
        ],
        'secondary' => [
            'updated' => 'Item updated successfully'
        ],
        'light' => [
            'viewed' => 'Item viewed successfully'
        ],
        'dark' => [
            'deleted' => 'Item deleted successfully'
        ],
        'success' => [
            'saved' => 'Item saved successfully'
        ],
        'info' => [
            'found' => 'Item found successfully'
        ],
        'warning' => [
            'missing' => 'Item missing'
        ],
        'danger' => [
            'error' => 'An error occurred'
        ]
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $session = new Session();
        $this->flashMessage = new FlashMessage($session, $this->config);
    }

    public function testConstructor(): void
    {
        $this->assertInstanceOf(FlashMessage::class, $this->flashMessage);
    }

    public function testSetValidTypeAndAction(): void
    {
        $this->flashMessage->set('success', 'saved');
        $this->expectOutputString('<div class="alert alert-success alert-dismissible fade show" role="alert"> <strong>Item saved successfully</strong>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
        $this->flashMessage->display();
    }

    public function testSetInvalidType(): void
    {
        $this->flashMessage->set('invalid', 'created');
        $this->expectOutputString('<div class="alert alert-danger alert-dismissible fade show" role="alert"> <strong>ERROR: Invalid type "invalid" provided</strong>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
        $this->flashMessage->display();
    }

    public function testSetInvalidAction(): void
    {
        $this->flashMessage->set('primary', 'invalid');
        $this->expectOutputString('<div class="alert alert-danger alert-dismissible fade show" role="alert"> <strong>ERROR: Invalid action "invalid" provided</strong>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
        $this->flashMessage->display();
    }

    public function testSetInvalidTypeAndAction(): void
    {
        $this->flashMessage->set('invalid', 'invalid');
        $this->expectOutputString('<div class="alert alert-danger alert-dismissible fade show" role="alert"> <strong>ERROR: Invalid type "invalid" provided</strong>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
        $this->flashMessage->display();
    }
}
