<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require '../vendor/autoload.php';

$app = new \Slim\App;

// get voor de RestApi
$app->get('/get/{contentType}/{table}/{where}', function (Request $request, Response $response, array $args) {
    $contentType = $request->getAttribute('contentType');
    $dbTableName = $request->getAttribute('table');
    $where = $request->getAttribute('where');
    $response = databaseGet($dbTableName, $where, $contentType);
    return $response;
});

// post voor de RestApi
$app->post('/post/{table}', function (Request $request, Response $response, array $args) {
    $dbTableName = $request->getAttribute('table');
    //Switch case waar de geselecteerde database wordt gekozen en dan de meegestuurde Json data wordt meegegeven naar de databaseSql functie
    //in deze functie wordt de data in de database gezet
    switch ($dbTableName) {
        case 'summonerstats':
            $summId = $request->getParam('SummonerId');
            $top = $request->getParam('Top');
            $jungle = $request->getParam('Jungle');
            $mid = $request->getParam('Mid');
            $support = $request->getParam('Support');
            $adc = $request->getParam('Adc');
            $sqlString = "INSERT INTO summonerstats VALUES (?, ?, ?, ?, ?, ?)";
            $dataTypes = "iiiiii";
            databaseSql($sqlString, $dataTypes, $summId, $top, $jungle, $mid, $support, $adc);
            break;
        case 'worldhappiness':
            $country = $request->getParam('Country');
            $region = $request->getParam('Region');
            $hapinessRank = $request->getParam('HappinessRank');
            $hapinessScore = $request->getParam('HappinessScore');
            $lowerConfidenceInterval = $request->getParam('LowerConfidenceInterval');
            $upperConfidenceInterval = $request->getParam('UpperConfidenceInterval');
            $economy = $request->getParam('Economy');
            $family = $request->getParam('Family');
            $health = $request->getParam('Health');
            $freedom = $request->getParam('Freedom');
            $trust = $request->getParam('Trust');
            $generosity = $request->getParam('Generosity');
            $dystopiaResidual = $request->getParam('DystopiaResidual');
            $year = $request->getParam('Year');
            $sqlString = "INSERT INTO worldhappiness VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $dataTypes = "ssiddddddddddi";
            databaseSql($sqlString, $dataTypes, $country, $region, $hapinessRank, $hapinessScore, $lowerConfidenceInterval, $upperConfidenceInterval, $economy, $family, $health, $freedom, $trust, $generosity, $dystopiaResidual, $year);
            break;
        case 'unemploymentrate':
            $ocation = $request->getParam('OCATION');
            $indicator = $request->getParam('INDICATOR');
            $subject = $request->getParam('SUBJECT');
            $measure = $request->getParam('MEASURE');
            $frequency = $request->getParam('FREQUENCY');
            $time = $request->getParam('TIME');
            $value = $request->getParam('Value');
            $flagCodes = $request->getParam('FlagCodes');
            $sqlString = "INSERT INTO unemploymentrate VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $dataTypes = "sssssiis";
            databaseSql($sqlString, $dataTypes, $ocation, $indicator, $subject, $measure, $frequency, $time, $value, $flagCodes);
            break;
        case 'champidwithname':
            $champId = $request->getParam('ChampId');
            $ChampName = $request->getParam('ChampName');
            $sqlString = "INSERT INTO champidwithname VALUES (?, ?)";
            $dataTypes = "is";
            databaseSql($sqlString, $dataTypes, $champId, $ChampName);
            break;
        default:
            echo "That database doesnt exist in our API im very sorry for the inconvenience";
            break;
    }
    return $response;
});

// Delete functie voor de Rest Api
$app->delete('/delete/{table}/{where}', function (Request $request, Response $response, array $args) {
    $dbTable = $request->getAttribute('table');
    $where = $request->getAttribute('where');
    // Switch case voor de Delete functie waarbij de geselecteerde Database wordt gebruikt om de correcte sql string te pakken
    // daarna wordt de data naar databaseSql gestuurd waar de SQL wordt uitgevoerd en de data uit de Database wordt verwijderd
    switch ($dbTable) {
        case 'champidwithname':
            $sqlString = "DELETE FROM champidwithname WHERE ChampName = ?";
            $dataTypes = "s";
            break;
        case 'worldhappiness':
            $sqlString = "DELETE FROM worldhappiness WHERE Country = ?";
            $dataTypes = "s";
            break;
        case 'unemploymentrate':
            $sqlString = "DELETE FROM unemploymentrate WHERE OCATION = ?";
            $dataTypes = "i";
            break;
        case 'summonerstats':
            $sqlString = "DELETE FROM summonerstats WHERE SummonerId = ?";
            $dataTypes = "i";
            break;
    }
    databaseSql($sqlString, $dataTypes, $where);
    return $response;
});

// Put functie voor de RestApi
$app->put('/update/{table}/{where}', function (Request $request, Response $response, array $args) {
    $dbTableName = $request->getAttribute('table');
    $where = $request->getAttribute('where');
    //Switch case waar de geselecteerde database wordt gekozen en dan de meegestuurde Json data wordt meegegeven naar de databaseSql functie
    //in deze functie wordt de data in de database gezet
    switch ($dbTableName) {
        case 'summonerstats':
            $top = $request->getParam('Top');
            $jungle = $request->getParam('Jungle');
            $mid = $request->getParam('Mid');
            $support = $request->getParam('Support');
            $adc = $request->getParam('Adc');
            $sqlString = "UPDATE summonerstats SET Top=?,Jungle=?,Mid=?,Support=?,Adc=? WHERE SummonerId = ?";
            $dataTypes = "iiiiis";
            databaseSql($sqlString, $dataTypes, $top, $jungle, $mid, $support, $adc, $where);
            break;
        case 'worldhappiness':
            $region = $request->getParam('Region');
            $hapinessRank = $request->getParam('HappinessRank');
            $hapinessScore = $request->getParam('HappinessScore');
            $lowerConfidenceInterval = $request->getParam('LowerConfidenceInterval');
            $upperConfidenceInterval = $request->getParam('UpperConfidenceInterval');
            $economy = $request->getParam('Economy');
            $family = $request->getParam('Family');
            $health = $request->getParam('Health');
            $freedom = $request->getParam('Freedom');
            $trust = $request->getParam('Trust');
            $generosity = $request->getParam('Generosity');
            $dystopiaResidual = $request->getParam('DystopiaResidual');
            $year = $request->getParam('Year');
            $sqlString = "UPDATE worldhappiness SET Region=?, HappinessRank=?, HappinessScore=?,LowerConfidenceInterval=?,UpperConfidenceInterval=?,Economy=?,Family=?,Health=?,Freedom=?,Trust=?,Generosity=?,DystopiaResidual=?,Year=? WHERE Country = ?";
            $dataTypes = "siddddddddddis";
            databaseSql($sqlString, $dataTypes, $region, $hapinessRank, $hapinessScore, $lowerConfidenceInterval, $upperConfidenceInterval, $economy, $family, $health, $freedom, $trust, $generosity, $dystopiaResidual, $year, $where);
            break;
        case 'unemploymentrate':
            $indicator = $request->getParam('INDICATOR');
            $subject = $request->getParam('SUBJECT');
            $measure = $request->getParam('MEASURE');
            $frequency = $request->getParam('FREQUENCY');
            $time = $request->getParam('TIME');
            $value = $request->getParam('Value');
            $flagCodes = $request->getParam('FlagCodes');
            $sqlString = "UPDATE unemploymentrate SET INDICATOR=?, SUBJECT=?, MEASURE=?, MEASURE=?, FREQUENCY=?, TIME=?, Value=?, FlagCodes=? WHERE OCATION = ?";
            $dataTypes = "ssssiiss";
            databaseSql($sqlString, $dataTypes, $indicator, $subject, $measure, $frequency, $time, $value, $flagCodes, $where);
            break;
        case 'champidwithname':
            $ChampName = $request->getParam('ChampName');
            $sqlString = "UPDATE champidwithname SET ChampName=? WHERE ChampId = ?";
            $dataTypes = "si";
            databaseSql($sqlString, $dataTypes, $ChampName, $where);
            break;
        default:
            echo "That database doesnt exist in our API im very sorry for the inconvenience";
            break;
    }
    return $response;
});

$app->run();

function databaseGet(String $dbTable, String $where, String $contentType)
{
    // De functie die wordt aangeroepen als de RestApi een Get oproep krijgt. 
    // Deze is gesplitst van de Post, Delete en Put omdat deze heel andere dingen moet doen
    $host = "localhost"; //Localhost if using Xampp
    $user = "root"; //User login
    $password = "123"; //database password login
    $databaseName = "dataprocessing"; //database name

    $conn = mysqli_connect($host, $user, $password, $databaseName);

    if ($conn === false) {
        echo "<p>Unable to connect to the database.</p><p> error: " . mysqli_errno() . ": " . mysqli_error() . "</p>";
    } else {
        if ($dbTable != null) {
            if ($where == "all") {
                $sql = "SELECT * FROM $dbTable";
            } else {
                switch ($dbTable) {
                    case 'champidwithname':
                        $sql = "SELECT * FROM $dbTable WHERE ChampName = '$where'";
                        break;
                    case 'worldhappiness':
                        $sql = "SELECT * FROM $dbTable WHERE Country = '$where'";
                        break;
                    case 'unemploymentrate':
                        $sql = "SELECT * FROM $dbTable WHERE TIME = '$where'";
                        break;
                    case 'summonerstats':
                        $sql = "SELECT * FROM $dbTable WHERE Top = '$where'";
                        break;
                }
            }
        }
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $data = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        } else {
            echo "Unable to get data from the database, please try again. if this error keeps up send me a discord message on: Madlyaza#3463";
        }
        mysqli_close($conn);
    }
    if($contentType == "json")
    {
        return json_encode($data);
    }
    else
    {
        $xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
        array_to_xml($data, $xml_data);
        return $xml_data->asXML();
    }
    return "we dont support that format";
}

function databaseSql(String $sqlString, String $dataTypes, ...$items)
{
    // de functie die wordt aangeroepen door de RestApi Delete, Update en Post.
    $host = "localhost"; //Localhost if using Xampp
    $user = "root"; //User login
    $password = "123"; //database password login
    $databaseName = "dataprocessing"; //database name

    $conn = mysqli_connect($host, $user, $password, $databaseName);

    if ($conn === false) {
        echo "<p>Unable to connect to the database.</p><p> error: " . mysqli_errno() . ": " . mysqli_error() . "</p>";
    } else {
        if ($stmt = mysqli_prepare($conn, $sqlString)) {
            mysqli_stmt_bind_param($stmt, "$dataTypes", ...$items);
            if (mysqli_stmt_execute($stmt)) {
                echo '{"Notice": {"text": "Succes"}';
            } else {
                echo "something went wrong with the execute, either try again or start crying";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
}

function array_to_xml( $data, &$xml_data ) {
    foreach( $data as $key => $value ) {
        if( is_array($value) ) {
            if( is_numeric($key) ){
                $key = 'item'; //dealing with <0/>..<n/> issues
            }
            $subnode = $xml_data->addChild($key);
            array_to_xml($value, $subnode);
        } else {
            $xml_data->addChild("$key",htmlspecialchars("$value"));
        }
     }
}