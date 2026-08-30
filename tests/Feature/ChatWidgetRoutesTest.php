<?php

namespace Tests\Feature;

use Tests\TestCase;

class ChatWidgetRoutesTest extends TestCase
{
    public function test_chat_widget_admin_route_exists_for_authenticated_users()
    {
        $response = $this->get('/chat-widget');

        $response->assertRedirect('/login');
    }

    public function test_widget_chat_list_route_exists_for_authenticated_users()
    {
        $response = $this->get('/widget-chats');

        $response->assertRedirect('/login');
    }
}
