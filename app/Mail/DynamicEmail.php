<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DynamicEmail extends Mailable implements ShouldQueue {
	use Queueable, SerializesModels;

	public $content;
	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($content) {
		$this->content = $content;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		if (isset($this->content['attachment']) && !empty($this->content['attachment'])) {
			return $this->subject($this->content['subject'])
				->markdown('emails.dynamic_email')
				->attach($this->content['attachment'])
				->with('content', $this->content)->onQueue('emails');
		} else {
			return $this->subject($this->content['subject'])
				->markdown('emails.dynamic_email')
				->with('content', $this->content)->onQueue('emails');
		}

	}
}
