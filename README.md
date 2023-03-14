# VoipIvr
voip ivr with twilio  and php/laravel stack


This project was maded in php/laravel stack

you could paste the directories in your localhost and then run "composer install" but the project will not be able to run due to the keys that are in environment variables and also not due to the redirection with ngrok with my personal twilio account.

for this reason the code will be running on my localhost until you do the corresponding tests

so please follow the instructions below: 

A)To test the ivr you must make an outgoing call to the number +18506088496 and you will have an ivr with 2 options:

	1-Forward the call to the agent
	2-go to the voicemail (Here the message you leave in the voice mail will be recorded and then an SMS will be sent to you with the download link of the recording 

B)The call will be forwarded to my personal number (I am using myself as an agent, and it shows the original caller id of the person who making the call

C)The voicemail recording  calls are save into a dropbox account doing the communication via dropboxApp, after that i get the temporary link to download the file of record from the dropbox api  and that link are send how sms to the caller.

D)To see the call history you can enter the route: https://b758-190-192-244-155.sa.ngrok.io/cdr which will show you a list of all calls (mini cdr) and if you make new calls you will be able to see them reflected there (refresh the page). This is under a mysql database on my localhost which communicates with Laravel through the mvc model.


If you have any question please let me know

PS:  i enjoyed doing this 

Best Regards.

Engineer Carlos Velasco
