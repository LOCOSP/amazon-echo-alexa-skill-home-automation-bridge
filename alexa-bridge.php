<?php

function getResponseArray($value) {
    return array(
      'version' => '1.0',
      'response' => array(
        'outputSpeech' => array(
          'type' => 'PlainText',
          'text' => 'OK, ' . $value,
        ),
        'card' => array(
          'text' => 'OK, ' . $value,
          'title' => 'AMAZON',
           'type' => 'Standard',
        ),
        'shouldEndSession' => true
      ),
      'sessionAttributes' => array()
    );
}

// Get raw POST data
$post = file_get_contents( 'php://input' );

// Decode the JSON into a stdClass object
$post = json_decode( $post );

// Check the applicationId to make sure it's your Alexa Skill
if ( 'amzn1.ask.skill.xxxsecretxxx' == $post->session->application->applicationId ) {

    // 
    switch($post->request->intent->name) {

      case 'test':
        $response = getResponseArray('beeyatch!');
        break;

      case 'LIVINGROOMCORNERON':
        $response = getResponseArray('Livingroom corner on');
        include '/var/www/html/interface/tasker/lights/CornnerLightOn.php';
        break;

      case 'LIVINGROOMCORNEROFF':
        $response = getResponseArray('Livingroom corner off');
        include '/var/www/html/interface/tasker/lights/CornnerLightOff.php';
        break;

      case 'GALLERYON':
        $response = getResponseArray('Gallery turned on');
        include '/var/www/html/interface/tasker/lights/GalleryLightOn.php';
        break;

      case 'GALLERYOFF':
        $response = getResponseArray('Gallery turned off');
        include '/var/www/html/interface/tasker/lights/GalleryLightOff.php';
        break;

      case 'TVOFF':
        $response = getResponseArray('I will turn off');
        get_headers('http://$dreamboxip/web/powerstate?newstate=5');
        break;

      case 'TVON':
        $response = getResponseArray('it is done');
        get_headers('http://$dreamboxip/web/powerstate?newstate=4');
        break;

      case 'AMPLIFIERON':
        $response = getResponseArray('Amplifier on');
        include '/var/www/html/interface/tasker/audio/AmpliON.php';
        break;

      case 'AMPLIFIEROFF':
        $response = getResponseArray('Amplifier off');
        include '/var/www/html/interface/tasker/audio/AmpliOFF.php';
        break;

      case 'NIGHTMODE':
        $response = getResponseArray('About time');
       // include '/var/www/html/interface/tasker/sceny/goodnight.php';
        break;

      default:
        $response = getResponseArray('I\'m sorry, but I dont know how to handle the command ' . $post->request->intent->slots->command->value);
        break;

    }

    //
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Insert code to run if the applicationId does NOT match
    echo 'The applicationId does NOT match!';
}
