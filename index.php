<?php
    // Credit: For CORS Acces https://stackoverflow.com/questions/51504231/php-api-rest-does-not-accept-cors-requests-even-using-header-access-control-al/51504293
    if(isset($_SERVER["HTTP_ORIGIN"]))
    {
        // You can decide if the origin in $_SERVER['HTTP_ORIGIN'] is something you want to allow, or as we do here, just allow all
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    }
    else
    {
        //No HTTP_ORIGIN set, so we allow any. You can disallow if needed here
        header("Access-Control-Allow-Origin: *");
    }

    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Max-Age: 60000");    // cache for 10 minutes

    if($_SERVER["REQUEST_METHOD"] == "OPTIONS")
    {
        if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"]))
            header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT"); //Make sure you remove those you do not want to support

        if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        //Just exit with 200 OK with the above headers for OPTIONS method
        exit(0);
    }
    //From here, handle the request as it is ok

    define("ENTITY", 2);
    define("ID", 3);
    define("MAX_PIECES", 4);
    define("ENTITY_TRACKS", "tracks");
    define("ENTITY_ARTISTS", "artists");
    define("ENTITY_ALBUMS", "albums");
    define("ENTITY_CUSTOMERS", "customers");
    define("ENTITY_INVOICELINES", "invoicelines");
    define("ENTITY_INVOICES", "invoices");

    $url = strtok($_SERVER["REQUEST_URI"], "?");    

    if (substr($url, strlen($url) - 1) == "/")
    {
        $url = substr($url, 0, strlen($url) - 1);
    }
    $urlPieces = explode("/", urldecode($url));

    header("Content-Type: application/json");
    header("Accept-version: v1");

    $pieces = count($urlPieces);

    if ($pieces > MAX_PIECES) {              
        echo formatError();
    } else if ($pieces == 2) {      
        echo APIDescription();
    } else {
        
        $entity = $urlPieces[ENTITY];
        
        switch ($entity) {
            case ENTITY_TRACKS:
                require_once("src/models/track.php");
                $track = new Track();
                $verb = $_SERVER["REQUEST_METHOD"];

                switch ($verb) 
                {
                    case "GET":
                        if (isset($_GET["order"]) && isset($_GET["name"])) {
                            echo json_encode($track->BrowseTracks($_GET["order"], $_GET["name"]));
                        }
                        else if (isset($_GET["id"]))
                        {
                            echo json_encode($track->Read($_GET["id"]));
                        }
                        else if (isset($_GET["name"])){
                            echo json_encode($track->GetPrice($_GET["name"]));
                        } 
                        else
                        {
                            echo json_encode($track->List());
                        }
                        $track = null;
                        break;
                    
                    case "POST":
                        if(isset($_POST["name"]) && isset($_POST["albumId"]) && isset($_POST["mediaType"]) && isset($_POST["genreId"]) && isset($_POST["composer"]) && isset($_POST["milliseconds"]) && isset($_POST["bytes"]) && isset($_POST["unitPrice"])) {
                            echo json_encode($track->Create($_POST["name"],
                                                            $_POST["albumId"],
                                                            $_POST["mediaType"],
                                                            $_POST["genreId"],
                                                            $_POST["composer"],
                                                            $_POST["milliseconds"],
                                                            $_POST["bytes"],
                                                            $_POST["unitPrice"]));
                        }
                        $track = null;
                        break;

                    case "PUT":
                        $trackData = (array) json_decode(file_get_contents("php://input"), TRUE);
                        if ((count($urlPieces) == 4) 
                        && isset($trackData["name"])
                        && isset($trackData["albumId"]) 
                        && isset($trackData["mediaTypeId"]) 
                        && isset($trackData["genreId"])
                        && isset($trackData["composer"]) 
                        && isset($trackData["milliseconds"]) 
                        && isset($trackData["bytes"]) 
                        && isset($trackData["unitPrice"])) {
                            echo json_encode($track->Update($urlPieces[ID], $trackData));
                        }
                        $track = null;
                        break;

                    case "DELETE":
                        if ($pieces < MAX_PIECES) {
                            echo formatError();
                        } else {
                            echo json_encode($track->Delete($urlPieces[ID]));
                        }
                        $track = null;
                        break;
                        
                }
                break;
            case ENTITY_ARTISTS:
                require_once("src/models/artist.php");
                $artist = new Artist();
                $verb = $_SERVER["REQUEST_METHOD"];

                switch ($verb) 
                {
                    case "GET":
                        if (isset($_GET["name"])) {
                            echo json_encode($artist->BrowseArtists($_GET["name"]));
                        }
                        else if (isset($_GET["id"])){
                            echo json_encode($artist->Read($_GET["id"]));
                        }
                        else 
                        {
                            echo json_encode($artist->List());
                        }
                        $artist = null;

                        break;

                    case "POST":
                        if(isset($_POST["name"])) {
                            echo json_encode($artist->Create($_POST["name"]));
                        }
                        $artist = null;
                        break;

                    case "PUT":
                        $artistData = (array) json_decode(file_get_contents("php://input"), TRUE);
                        if ((count($urlPieces) == 4) && isset($artistData["name"])) {
                            echo json_encode($artist->Update($urlPieces[ID], $artistData["name"]));
                        }
                        $artist = null;
                        break;

                    case "DELETE":
                        if ($pieces < MAX_PIECES) {
                            echo formatError();
                        } else {
                            echo json_encode($artist->Delete($urlPieces[ID]));
                        }
                        $artist = null;
                        break;
                        
                }
                break;
            case ENTITY_ALBUMS:
                require_once("src/models/album.php");
                $album = new Album();
                $verb = $_SERVER["REQUEST_METHOD"];
                switch ($verb) 
                {
                    case "GET":
                        if (isset($_GET["order"]) && isset($_GET["name"])) {
                            echo json_encode($album->BrowseAlbums($_GET["order"], $_GET["name"]));
                        } 
                        else if (isset($_GET["id"])){
                            echo json_encode($album->Read($_GET["id"]));
                        }
                        else {
                            echo json_encode($album->List());
                        }
                        $album = null;
                        break;

                    case "POST":
                        if (isset($_POST["title"]) && isset($_POST["artistId"])) {
                            echo json_encode($album->Create($_POST["title"], $_POST["artistId"]));
                        }
                        $album = null;
                        break;
                    
                    case "PUT":
                        $albumData = (array) json_decode(file_get_contents("php://input"), TRUE);
                        if ((count($urlPieces) == 4) && isset($albumData["title"]) && isset($albumData["artistId"])) {
                            echo json_encode($album->Update($urlPieces[ID], $albumData));
                        }
                        $album = null;
                        break;

                    case "DELETE":
                        if ($pieces < MAX_PIECES) {
                            echo formatError();
                        } else {
                            echo json_encode($album->Delete($urlPieces[ID]));
                        }
                        $album = null;
                        break;
                }
                break;
            case ENTITY_CUSTOMERS:
                require_once("src/models/customer.php");
                $customer = new Customer();
                $verb = $_SERVER["REQUEST_METHOD"];

                switch ($verb) 
                {
                    case "GET":
                        if(isset($_GET["email"])){
                            echo json_encode($customer->Read($_GET["email"]));
                        }
                        $customer = null;
                        break;

                    case "POST":
                        if(isset($_POST["firstName"]) && isset($_POST["lastName"]) && isset($_POST["password"]) && isset($_POST["company"]) && isset($_POST["address"]) && isset($_POST["city"]) && isset($_POST["state"]) && isset($_POST["country"]) && isset($_POST["postalCode"]) && isset($_POST["phone"]) && isset($_POST["fax"]) && isset($_POST["email"])){
                            echo json_encode($customer->Create($_POST));
                        }
                        else {
                            formatError();
                        }
                        $customer = null;
                        break;
                    
                    case "PUT":
                        $customerData = (array) json_decode(file_get_contents("php://input"), TRUE);
                        if ((count($urlPieces) == 3) && isset($customerData["customerId"]) && isset($customerData["password"])) {
                            echo json_encode($customer->Update("password", $customerData));
                        }
                        else if ((count($urlPieces) == 3) && isset($customerData["customerId"]) && isset($customerData["firstName"]) && isset($customerData["lastName"]) && isset($customerData["email"]) && isset($customerData["company"]) && isset($customerData["phone"]) && isset($customerData["fax"])){
                            echo json_encode($customer->Update("profile", $customerData));
                        }
                        else if ((count($urlPieces) == 3) && isset($customerData["customerId"]) && isset($customerData["address"]) && isset($customerData["city"]) && isset($customerData["state"]) && isset($customerData["country"]) && isset($customerData["postalCode"])){
                            echo json_encode($customer->Update("shipping", $customerData));
                        }
                        $customer = null;
                        break;
                }
                break;
            case ENTITY_INVOICES:
                require_once("src/models/invoice.php");
                $invoice = new Invoice();
                $verb = $_SERVER["REQUEST_METHOD"];

                switch ($verb) 
                {
                    case "POST":
                        if(isset($_POST["customerId"]) && isset($_POST["billindAddress"]) && isset($_POST["billingCity"]) && isset($_POST["billingState"]) && isset($_POST["billingCountry"]) && isset($_POST["billingPostalCode"]) && isset($_POST["total"])){
                            echo json_encode($invoice->Create($_POST));
                        }
                        break;
                }
                break;
            case ENTITY_INVOICELINES:
                require_once("src/models/invoiceline.php");
                $invoiceline = new InvoiceLine();
                $verb = $_SERVER["REQUEST_METHOD"];

                switch ($verb) 
                {
                    case "POST":
                        if(isset($_POST["invoiceId"]) && isset($_POST["quantity"]) && isset($_POST["trackId"]) && isset($_POST["unitPrice"])){
                            echo json_encode($invoiceline->Create($_POST));
                        }
                        $invoiceline = null;
                        break;
                }
                break;
            default:
                echo formatError();
            }
        }
    

    /**
     * Returns the REST API description
     */
    function APIDescription() {
        $apiDescription = "Description of API usage";

        return json_encode($apiDescription);
    }

    /**
     * Returns a format error
     */
    function formatError() {
        $output["error"] = "Incorrect format";
        return json_encode($output);
    }

?>