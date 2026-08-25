<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HubungiKamiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $email;
    public $subjek;
    public $pesan;

    /**
     * Create a new message instance.
     */
    public function __construct($nama, $email, $subjek, $pesan)
    {
        $this->nama = $nama;
        $this->email = $email;
        $this->subjek = $subjek;
        $this->pesan = $pesan;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subjek ?? 'Pesan dari Website DPMD')
                    ->html("
                        <h3>Pesan Baru dari Hubungi Kami</h3>
                        <p><strong>Nama:</strong> {$this->nama}</p>
                        <p><strong>Email:</strong> {$this->email}</p>
                        <p><strong>Subjek:</strong> {$this->subjek}</p>
                        <p><strong>Pesan:</strong></p>
                        <p style='white-space: pre-wrap;'>{$this->pesan}</p>
                    ");
    }
}
