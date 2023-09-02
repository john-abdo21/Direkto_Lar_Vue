<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationEmail;
use App\Models\conf_colacorreos;

class SendEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {

    }

    public function handle()
    {
        $results = conf_colacorreos::whereNull('dayFechaEnvio')->get();
        if($results){
            foreach ($results as $key => $mail){
                Mail::to($mail->desCorreoEnvio)->send(new InvitationEmail($mail->desMensaje, $mail->desMotivo));
                $insertMail = conf_colacorreos::where('codColaCorreo',$mail->codColaCorreo)->update([
                    "dayFechaEnvio" => date('Y-m-d H:i:s')
                ]);
            }
        }
    }
}


?>
