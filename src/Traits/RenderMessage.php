<?php

namespace Traits;

trait RenderMessage
{
    /**
     * Render a message view.
     * 
     * @param  string $message
     * @return void
     */
    protected function renderMessageView($message)
    {
        include __DIR__ . "/../../view/header.php";
        include __DIR__ . "/../../view/parts/message.php";
        include __DIR__ . "/../../view/footer.php";
    }
}