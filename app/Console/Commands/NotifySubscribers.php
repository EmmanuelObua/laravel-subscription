<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Notified;
use App\Notifications\NewPostCreatedNotification;

use App\Models\Subscription;
use App\Models\Post;
use App\Models\User;

class NotifySubscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscribers:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify subscribers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $posts = Post::orderBy('id', 'desc')->get();

        foreach ($posts as $post) {

            $title = $post->post_title;
            $description = $post->post_description;
            $website_id = $post->website_id;

            $subscriptions = Subscription::where('website_id', $website_id)
                                ->get()
                                ->pluck('user_id');

            $notified  = Notified::where('post_id', $post->id)->whereIn('user_id', $subscriptions->all())->first();

            if (is_null($notifieds)) {

                $subscribers = User::whereIn('id', $subscriptions->all())
                                ->where('id','<>',$notified->user_id)
                                ->get();

                \Notification::send($event->subscribers, new NewPostCreatedNotification($title, $description));

                foreach ($subscribers as $subscriber) {

                    Notified::create([
                        'post_id'   => $post->id,
                        'user_id'   => $subscriber->user_id,
                    ]);

                }

            }

        }      

    }
}
