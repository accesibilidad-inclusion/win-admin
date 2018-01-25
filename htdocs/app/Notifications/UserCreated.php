<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserCreated extends Notification
{
    use Queueable;

	public $user;
	public $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( User $user, string $password )
    {
        $this->user = $user;
		$this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
					->subject('Tus datos de acceso para '. env('APP_NAME'))
                    ->greeting('¡Bienvenido/a '. $this->user->name .'!')
					->line('Tu cuenta de usuario/a para acceder a WIN Admin ha sido creada')
					->line('Puedes acceder con los siguientes datos:')
					->line('Correo electrónico: '. $this->user->email)
					->line('Contraseña: '. $this->password)
                    ->action('Iniciar sesión', route('login'));
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
            //
        ];
    }
}
