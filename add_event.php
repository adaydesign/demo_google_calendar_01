


<html>
    <head>
        <title>ADD Event</title>
    </head>
    <body>
        <div>
            <h3>ADD EVENT</h3>
            <?php

                if(isset($_POST['submit'])){
                    $summary     = $_POST["summary"];
                    $description = $_POST["description"]; 
                    $location    = $_POST["location"];
                    $start       = $_POST["start"];
                    $end         = $_POST["end"];
                    $group       = $_POST["group"];

                    $start_v     = array();
                    $end_v       = array();
                    if(isset($_POST["allday"])){
                        $start_v     = array(
                            'date'  => "$start",
                            'timeZone'  => 'Asia/Bangkok'
                        );
                        $end_v       = array(
                            'date'  => "$end",
                            'timeZone'  => 'Asia/Bangkok'
                        );
                    }else{
                        $start_v     = array(
                            'dateTime'  => "$start",
                            'timeZone'  => 'Asia/Bangkok'
                        );
                        $end_v       = array(
                            'dateTime'  => "$end",
                            'timeZone'  => 'Asia/Bangkok'
                        );
                    }

                    // value
                    $event_value = array('summary'=>"$summary",
                        'location'      => "$location",
                        'description'   => "$description",
                        'start'         => $start_v,
                        'end'           => $end_v,
                        'colorId'       => "$group"
                    );
                    print_r($event_value);

                    // google api connect
                    require_once __DIR__.'/vendor/autoload.php';
                    
                    session_start();
                    
                    $client = new Google_Client();
                    $client->setAuthConfig('client_secret.json');
                    $client->addScope(Google_Service_Calendar::CALENDAR);
                    
                    if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
                      $client->setAccessToken($_SESSION['access_token']);
                    
                      $service  = new Google_Service_Calendar($client);
                      $event    = new Google_Service_Calendar_Event($event_value);

                      $calendarId = 'aday.3ddesign@gmail.com';
                      $event = $service->events->insert($calendarId, $event);
                      echo "Link : ". $event->htmlLink;
                      echo "Event ID : ".$event->id;

                    }else{
                        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/google_calendar/oauth2callback.php';
                        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
                    }


                    echo "<hr>";
                }

            ?>
            <form method="POST">
                <div><input type="text" placeholder=": summary" name="summary" required></div>
                <div><input type="text" placeholder=": location" name="location" required></div>
                <div><input type="text" placeholder=": description" name="description" required></div>
                <div><input type="text" placeholder=": start - datetime" name="start" required> ex. 2015-05-28T09:00:00+07:00</div>
                <div><input type="text" placeholder=": end - datetime" name="end" required> ex. 2015-05-28T17:00:00+07:00</div>
                <div><input type="checkbox" name="allday" value="1"> ทั้งวัน</div>
                <div>
                    <select name="group">
                        <option value='1'>กลุ่มที่ 1</option>
                        <option value='2'>กลุ่มที่ 2</option>
                        <option value='3'>กลุ่มที่ 3</option>
                        <option value='4'>กลุ่มที่ 4</option>
                        <option value='5'>กลุ่มที่่่ 5</option>
                    </select>
                </div>
                <div><input type="submit" name="submit"></div>
            </form>
        </div>
    </body>
</html>