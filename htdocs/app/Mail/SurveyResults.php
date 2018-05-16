<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Survey;

class SurveyResults extends Mailable
{
    use Queueable, SerializesModels;

    public $survey;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( Survey $survey )
    {
        $this->survey = $survey;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this
            ->subject('Resultados de tu evaluación con Win')
            ->view('emails.results')
            ->with([
                'results'  => $this->survey->results,
            ]);
        $this->withSwiftMessage( function( $message ){
            $message
                ->getHeaders()
                ->addTextHeader('Reply-To', 'win@ead.cl');
        });
    }
}
