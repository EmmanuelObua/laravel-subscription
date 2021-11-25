<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\NewPostCreated;
use App\Notifications\NewPostCreatedNotification;

class NewPostCreatedListener implements ShouldQueue
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(NewPostCreated $event)
    {

        $title = $event->post->post_title;
        $description = $event->post->post_description;

        \Notification::send($event->subscribers, new NewPostCreatedNotification($title, $description));

    }

}
