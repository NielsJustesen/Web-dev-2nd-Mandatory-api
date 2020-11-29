<?php
    

    define('ENTITY', 2);
    define('ID', 3);
    define('MAX_PIECES', 4);
    define('ENTITY_TRACKS', 'tracks');
    define('ENTITY_ARTISTS', 'artists');
    define('ENTITY_ALBUMS', 'albums');
    define('ENTITY_CUSTOMERS', 'customers');
    define('ENTITY_INVOICELINES', 'invoicelines');
    define('ENTITY_INVOICES', 'invoices');



    $url = strtok($_SERVER['REQUEST_URI'], "?");    

    if (substr($url, strlen($url) - 1) == '/')
    {
        $url = substr($url, 0, strlen($url) - 1);
    }
    $urlPieces = explode('/', urldecode($url));

    header('Content-Type: application/json');
    header('Accept-version: v1');

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
                $verb = $_SERVER['REQUEST_METHOD'];

                switch ($verb) 
                {
                    case 'GET':
                        echo json_encode("1");

                        if (isset($_GET["order"]) && isset($_GET["name"])) {
                            echo json_encode($track->BrowseTracks($_GET["order"], $_GET["name"]));
                        }
                        else {
                            formatError();
                        }
                        $track = null;
                        break;
                    
                    case 'POST':
                        echo json_encode("2");

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

                    case 'PUT':
                        echo json_encode("3");

                        $trackData = (array) json_decode(file_get_contents('php://input'), TRUE);
                        if ((count($urlPieces) == 4) && isset($trackData['name'])) {
                            echo json_encode($track->Update($urlPieces[ID], $trackData['name']));
                        }
                        $track = null;
                        break;

                    case 'DELETE':
                        echo json_encode("4");

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
                require_once('src/models/artist.php');
                $artist = new Artist();
                $verb = $_SERVER['REQUEST_METHOD'];

                switch ($verb) 
                {
                    case 'GET':
                        echo json_encode("5");

                        if (isset($_GET["name"])) {
                            echo json_encode($artist->BrowseArtists($_GET["name"]));
                        }
                        else {
                            echo json_encode($artist->List());
                        }
                        $artist = null;

                        break;

                    case 'POST':
                        echo json_encode("6");

                        if(isset($_POST['name'])) {
                            echo json_encode($artist->Create($_POST['name']));
                        }
                        $artist = null;
                        break;

                    case 'PUT':
                        echo json_encode("7");

                        $artistData = (array) json_decode(file_get_contents('php://input'), TRUE);
                        if ((count($urlPieces) == 4) && isset($artistData['name'])) {
                            echo json_encode($artist->Update($urlPieces[ID], $artistData['name']));
                        }
                        $artist = null;
                        break;

                    case 'DELETE':
                        echo json_encode("8");

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
                require_once('src/models/album.php');
                $album = new Album();
                $verb = $_SERVER['REQUEST_METHOD'];
                switch ($verb) 
                {
                    case 'GET':
                        echo json_encode("9");

                        if (isset($_GET["order"]) && isset($_GET['name'])) {
                            echo json_encode($album->BrowseAlbums($_GET["order"], $_GET["name"]));
                        }
                        else {
                            echo json_encode($album->List());
                        }
                        $album = null;
                        break;

                    case 'POST':
                        echo json_encode("10");

                        if (isset($_POST['title']) && isset($_POST['artistId'])) {
                            echo json_encode($album->Create($_POST['title'], $_POST['artistId']));
                        }
                        $album = null;
                        break;
                    
                    case 'PUT':
                        echo json_encode("11");

                        $albumData = (array) json_decode(file_get_contents('php://input'), TRUE);
                        if ((count($urlPieces) == 4) && isset($albumData['title'])) {
                            echo json_encode($album->Update($urlPieces[ID], $albumData['title']));
                        }
                        $album = null;
                        break;

                    case 'DELETE':
                        echo json_encode("12");

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
                $verb = $_SERVER['REQUEST_METHOD'];

                switch ($verb) 
                {
                    case 'GET':
                        
                        $customer = null;
                        break;
                        

                    case 'POST':
                        
                        $customer = null;
                        break;

                    
                    case 'PUT':
                        
                        $customer = null;
                        break;

                    case 'DELETE':
                        if ($pieces < MAX_PIECES) {
                            echo formatError();
                        } else {
                            echo json_encode($customer->Delete($urlPieces[ID]));
                        }
                        $customer = null;
                        break;
                }
                break;
            case ENTITY_INVOICES:
                require_once("src/models/invoice.php");
                $invoice = new Invoice();
                $verb = $_SERVER['REQUEST_METHOD'];

                switch ($verb) 
                {
                    case 'GET':
                       
                        $invoice = null;
                        break;
                        

                    case 'POST':
                        
                        $invoice = null;
                        break;

                    
                    case 'PUT':
                       
                        $invoice = null;
                        break;

                    case 'DELETE':
                        if ($pieces < MAX_PIECES) {
                            echo formatError();
                        } else {
                            echo json_encode($invoice->Delete($urlPieces[ID]));
                        }
                        $invoice = null;
                        break;
                }
                break;
            case ENTITY_INVOICELINES:
                require_once("src/models/invoiceline.php");
                $invoiceline = new InvoiceLine();
                $verb = $_SERVER['REQUEST_METHOD'];

                switch ($verb) 
                {
                    case 'GET':
                        
                        $invoiceline = null;
                        break;
                        

                    case 'POST':
                        
                        $invoiceline = null;
                        break;

                    
                    case 'PUT':
                        
                        $invoiceline = null;
                        break;

                    case 'DELETE':
                        if ($pieces < MAX_PIECES) {
                            echo formatError();
                        } else {
                            echo json_encode($invoiceline->Delete($urlPieces[ID]));
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
        $output['error'] = 'Incorrect format';
        return json_encode($output);
    }

?>