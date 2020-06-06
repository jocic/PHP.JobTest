<?php
    
    /***********************************************************\
    |* Author: Djordje Jocic                                   *|
    |* Website: http://www.djordjejocic.com/                   *|
    |* Email Address: office@djordjejocic.com                  *|
    |* ------------------------------------------------------- *|
    |* Filename: process.php                                   *|
    |* ------------------------------------------------------- *|
    |* Copyright (C) 2020                                      *|
    \***********************************************************/
    
    /******************\
    |* CORE CONSTANTS *|
    \******************/
    
    /**
     * Secrect used for validating submitted ReCaptcha tokens.
     * 
     * @var string
     */
    
    const RECAPTCHA_SECRET = "XXXXXX";
    
    /******************\
    |* CORE VARIABLES *|
    \******************/
    
    /**
     * Submitted <i>name</i> for the appointment.
     * 
     * @var string
     */
    
    $name = empty($_POST["name"]) ? "/" : $_POST["name"];
    
    /**
     * Submitted <i>phone</i> for the appointment.
     * 
     * @var string
     */
    
    $phone = empty($_POST["phone"]) ? "/" : $_POST["phone"];
    
    /**
     * Submitted <i>email</i> for the appointment.
     * 
     * @var string
     */
    
    $email = empty($_POST["email"]) ? "/" : $_POST["email"];
    
    /**
     * Submitted <i>time</i> for the appointment.
     * 
     * @var string
     */
    
    $time = empty($_POST["time"]) ? "12" : $_POST["time"];
    
    /**
     * Submitted <i>date</i> for the appointment.
     * 
     * @var string
     */
    
    $date = empty($_POST["date"]) ? date("m-d-Y") : $_POST["date"];
    
    /**
     * Submitted <i>note</i> for the appointment.
     * 
     * @var string
     */
    
    $note = empty($_POST["note"]) ? "/" : $_POST["note"];
    
    /**
     * Submitted <i>recaptcha</i> for the appointment.
     * 
     * @var string
     */
    
    $recaptcha = empty($_POST["recaptcha"]) ? "/" : $_POST["recaptcha"];
    
    /*****************\
    |* GET FUNCTIONS *|
    \*****************/
    
    // GET METHODS GO HERE
    
    /*****************\
    |* SET FUNCTIONS *|
    \*****************/
    
    // SET METHODS GO HERE
    
    /******************\
    |* CORE FUNCTIONS *|
    \******************/
    
    /**
     * Checks the access token of a client, and refreshes
     * it if it was expired.
     * 
     * @author    Djordje Jocic <office@djordjejocic.com>
     * @copyright 2020 Djordje Jocic
     * @version   1.0.0
     * @access    public
     * 
     * @param object $client
     *   Instance of the <i>Google_Client</i> class.
     * @return void
     */
    
    function check_token($client)
    {
        // Core Variables
        
        $refresh_token = null;
        $access_token  = null;
        
        // Logic
        
        if ($client->isAccessTokenExpired())
        {
            $refresh_token = $client->getRefreshToken();
            
            $client->refreshToken($refresh_token);
            
            $access_token = $client->getAccessToken();
            
            $access_token["refresh_token"] = $refresh_token; // Save Refreshed Token
            
            $access_token = json_encode($access_token);
            
            if (!file_put_contents(__DIR__ . "/calendar_credentials.json", $access_token))
            {
                exit("Check RWX permissions.");
            }
        }
    }
    
    /**
     * Inserts Google Event to the Google Calendar of the client.
     * 
     * @author    Djordje Jocic <office@djordjejocic.com>
     * @copyright 2020 Djordje Jocic
     * @version   1.0.0
     * @access    public
     * 
     * @param object $event
     *   Instance of the <i>Google_Service_Calendar_Event</i> class.
     * @return boolean
     *   Value <i>TRUE</i> if event was inserted, and vice versa.
     */
    
    function insert_event($event)
    {
        // Core Variables
        
        $client       = new \Google_Client();
        $access_token = file_get_contents(__DIR__ . "/calendar_credentials.json");
        $service      = null;
        
        // Create Client Object
        
        $client->setApplicationName("Google API Application");
        $client->setScopes(implode(" ", array(
            \Google_Service_Calendar::CALENDAR,
            \Google_Service_Calendar::CALENDAR_READONLY
        )));
        $client->setAuthConfigFile(__DIR__ . "/calendar_client_secret.json");
        $client->setAccessToken($accessToken);
        $client->setApprovalPrompt("force");
        $client->setAccessType("offline");
        
        // Check Token
        
        check_token($client);
        
        // Create Service Object
        
        $service = new \Google_Service_Calendar($client);
        
        try
        {
            $event = $service->events->insert("primary", $event);
        }
        catch (\Exception $e)
        {
            return false;
        }
        
        return true;
    }
    
    /*******************\
    |* CHECK FUNCTIONS *|
    \*******************/
    
    /**
     * Checks if the provided <i>ReCaptcha</i> token is valid.
     * 
     * @author    Djordje Jocic <office@djordjejocic.com>
     * @copyright 2020 Djordje Jocic
     * @version   1.0.0
     * @access    public
     * 
     * @param object $token
     *   Token that should be checked.
     * @return boolean
     *   Value <i>TRUE</i> if the provided token is valid, and vice versa.
     */
    
    function is_token_valid($token)
    {
        // Core Variables
        
        $url  = "https://www.google.com/recaptcha/api/siteverify";
        $data = [
            "secret"   => RECAPTCHA_SECRET,
            "response" => $token
        ];
        
        // Other Variables
        
        $ch       = null;
        $response = null;
        
        // Logic
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = json_decode(curl_exec($ch), true);
        
        return isset($response["success"]) && $response["success"];
    }
    
    /*******************\
    |* OTHER FUNCTIONS *|
    \*******************/
    
    // OTHER METHODS GO HERE
    
    /*************\
    |* PROCEDURE *|
    \*************/
    
    require "vendor/autoload.php";
    
    header("Content-Type: application/json");
    
    if (getenv("REQUEST_METHOD") == "POST")
    {
        // Core Variables
        
        $datetime = null;
        $event    = null;
        
        // Notification Variables
        
        $notif_contents = "";
        $notif_subject  = "Appointment Notification: " . $name;
        $notif_headers  = "MIME-Version: 1.0\r\n" .
                          "Content-type: text/plain; charset=UTF-8\r\n";
         
        // Control Variables
        
        $inserted = false;
        
        // Validate Form
        
        if ($this->is_token_valid($recaptcha))
        {
            // Process Variables.
            
            $datetime = DateTime::createFromFormat("m/d/Y h:i A", $date . " " . $time);
            
            $notif_contents = "Appointment Details\r\n" .
                              "---------------------\r\n" .
                              "Datetime: " . $datetime . "\r\n" .
                              "---------------------\r\n" .
                              "Name: " . $name . "\r\n" .
                              "Phone: " . $phone . "\r\n" .
                              "Email: " . $email . "\r\n" .
                              "---------------------\r\n" .
                              $note . "\r\n";
            
            // Create Event Object.
            
            $event = new \Google_Service_Calendar_Event([
                "summary"     => "Name: " . $name,
                "description" => $notif_contents,
                "start" => [
                    "dateTime" => $datetime,
                    "timeZone" => "Europe/Belgrade"
                ],
                "attendees" => [ [
                    "displayName" => $name,
                    "email"       => $email,
                    "comment"     => $phone
                ] ],
                "reminders" => [
                    "useDefault" => false,
                    "overrides" => [ [
                        "method"  => "email",
                        "minutes" => 12 * 60
                    ], [
                        "method"  => "popup",
                        "minutes" => 15
                    ], [
                        "method"  => "popup",
                        "minutes" => 30
                    ] ]
                ]
            ]);
            
            // Insert Event
            
            $inserted = insert_event($event);
            
            if ($inserted)
            {
                mail($email, $notif_subject, $notif_contents, $notif_headers);
                
                exit(json_encode([ "status" => true ]));
            }
        }
    }
    
    exit(json_encode([ "status" => false ]));
