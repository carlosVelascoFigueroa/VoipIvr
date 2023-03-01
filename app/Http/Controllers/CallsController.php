<?php

namespace App\Http\Controllers;

require '/Volumes/Expansion/code/php/alowareAssesment/vendor/autoload.php';
use Illuminate\Http\Request;
use App\Http\Requests;
use Twilio\TwiML\VoiceResponse;
use Twilio\Rest\Client;
use App\Models\Call;


class CallsController extends Controller
{
    public function welcome(){

        $response   = new VoiceResponse();
        $gather     = $response->gather([
            'numDigits' => 1,
            'input' => 'speech dtmf',
            'action' => '/menu',
            'method' => 'GET'
        ]);

        $gather->say('Thank you for calling.
            Please. press 1 to forward the call. Or Press 2 to' .
            'go to the voicemail.',
            ['loop' => 1]
        );

        return $response;

    }


    public function menu(Request $request)
    {
        $selectedOption = $request->input('Digits');

        $response       = new VoiceResponse();
        $redirect       = $response->redirect('/welcome', ['method' => 'GET'] );
        $response->say(
            'Invalid option.',
            ['voice' => 'Alice', 'language' => 'en-GB']
        );

        

        switch ($selectedOption) {
            case 1:
                return $this->caseOne();
            case 2:
                return $this->caseTwo();
        }
            return $response;
            
    }


    public function caseOne(){

        $response = new VoiceResponse();
        $response->say('Please wait while we Contact the other party');
        $response->dial("+5491168748935");

        return $response;


    }

    public function caseTwo(){

        $response  = new VoiceResponse();
        $response->say('Please leave a message at the beep. Press the star key when finished.');
        $response->record(['maxLength' => 30, 'finishOnKey' => '*','action'=>'/dropbox', 'method' => 'GET']);
        $response->say('I did not receive a recording');

        echo $response;

    }

    
    public function savecdr(Request $request){
        //save info from request
        $call = new Call;
        
        $call->direction   =$request->Direction;
        $call->callsid     =$request->CallSid;
        $call->from        =$request->From;
        $call->to          =$request->To;

        $call->save();
        return $this->welcome();
    }
    

}


