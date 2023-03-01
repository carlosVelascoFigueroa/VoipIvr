<?php

namespace App\Http\Controllers;
require '/Volumes/Expansion/code/php/alowareAssesment/vendor/autoload.php';


use Illuminate\Http\Request;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use CallsController;
use Twilio\TwiML\VoiceResponse;
use Twilio\Rest\Client;

class DropboxController extends Controller
{
    //Acounts variable
    private $twilioSid      =  $_ENV['TWILIO_SID'];
    private $twilioToken    =  $_env("TWILIO_TOKEN");

    private $dropboxKey     = $_env("DROPBOX_KEY");
    private $dropboxSecret  = $_env("DROPBOX_SECRET");
    private $dropboxToken   = $_env("DROPBOX_TOKEN");

    public function saveLocal(Request $request){

        $from   = $request->From;
        $url    = $request->RecordingUrl;
        $img    = $request->CallSid . "_" . 'Call'. ".wav";
        file_put_contents( $img, file_get_contents( $url ) );

        return $this->uploadDropbox($img, $from);

    }



    public function uploadDropbox($file_name, $from){
        
        $response   = new VoiceResponse();
        $response->say(
            'it was an error in the recording. please try again',
            ['voice' => 'Alice', 'language' => 'en-GB']
        );


    
        $app        = new DropboxApp($this->dropboxKey,$this->dropboxSecret,$this->dropboxToken);
        $dropbox    = new Dropbox($app);
    
        $fileName   = $file_name;
        $filePath   = '/Volumes/Expansion/code/php/alowareAssesment/public/' .$file_name;

        try {
            $dropboxFile    = new DropboxFile( $filePath );
            $uploadedFile   = $dropbox->upload( $dropboxFile, "/" . $fileName, [ 'autorename' => true ] );
            
            return $this->getLink($file_name, $from);

        } catch ( DropboxClientException $e ) {
            return $response;
        }

    }



    public function getLink($file_name, $from){

        $preFile        = "/"; //neccesary in the beggining of the path for dropbox request
        $data           = json_encode([
            "path" => $preFile.$file_name
        ]);
        $authorization  = 'Authorization: Bearer '. $this->dropboxToken;
        $conten_type    = 'Content-Type: application/json';
        $headers        = [
            $authorization,
            $conten_type
        ];
        
        $opts           = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => $headers,
            'content' => $data
        ));
        
        $context        = stream_context_create($opts);
        $result         = file_get_contents('https://api.dropboxapi.com/2/files/get_temporary_link', false, $context);
        
        $stringData     = (string)$result;
        $linkToArray    =  explode('"link": "', $stringData);
        $linkToStr      = $linkToArray[1];
        $link           = substr($linkToStr, 0, -2);
        
        return $this->sms($link, $from);

    }

    public function sms($link, $from){
        $response   = new VoiceResponse();
        $response->say(
            'you will receive an sms with the link to download the record. Thanks for calling',
            ['voice' => 'Alice', 'language' => 'en-GB']
        );

        $twilio     = new Client($this->twilioSid, $this->twilioToken);
        $twilio->messages->create($from,array(    "from" => "+18506088496",
        "body" => "This is the link where donwload your record: $link",));
        return $response;
    }

}
