<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostNotification extends Notification
{
    private $post;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Novo comentário')
                    ->line($this->comment->title)
                    ->action('Ver comentário', route('posts.show', $this->comment->post_id))
                    ->line('Abraços!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [

        ];
    }

    public function toDatabase($notifiable)
    {
        if($this->post->tipo == 'E'){
            return [
                'post' => $this->post->load('opcoesEnquete'),
            ];
        } else {
            return [
                'post' => $this->post,
            ];
        }
    }
}
