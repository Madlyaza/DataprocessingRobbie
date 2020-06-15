<?php
    use JsonSchema\Validator;
    require_once '../vendor/autoload.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Application</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') 
            {
                if (isset($_POST['SubmitForm']) && !empty($_POST['textInput'])) 
                {
                    $url = "http://localhost/Slim/public/api.php/get/" . $_POST['type'] . "/" . $_POST['database'] . "/" . $_POST['textInput'];
                    if($_POST['type'] == "json")
                    {
                        $array = json_decode(file_get_contents($url), true);
                        $validator = new JsonSchema\Validator;
                        $validator->validate($array, (object)['$ref' => 'file://' . realpath('Schemas/'.$_POST['database'].'.json')]);
                        if ($validator->isValid()) {
                            $continue = true;
                        } else {
                            echo "JSON does not validate. Violations:\n";
                            foreach ($validator->getErrors() as $error) {
                                echo sprintf("[%s] %s\n", $error['property'], $error['message']);
                            }
                        }
                    }
                    else
                    {
                        $xmlstring = file_get_contents($url);
                        $arrayXml = xmlstring2array($xmlstring);
                        $xml = new DOMDocument();
                        $xml->loadXML($xmlstring, LIBXML_NOBLANKS);
                        if (!$xml->schemaValidate("Schemas/".$_POST['database'].".xsd"))
                        {
                            echo "invalid xml";
                        }
                        else
                        {
                            foreach($arrayXml as $arr)
                            {
                                $array = $arr;
                            }
                            $continue = true;
                        }
                    }
                    if($continue)
                    {
                        switch ($_POST['database']) {
                            case 'summonerstats':
                                $value1 = "Top";
                                $value2 = "SummonerId";
                                $infoValue = "Summoner Ids for top";
                                break;
    
                            case 'champidwithname':
                                $value1 = "ChampName";
                                $value2 = "ChampId";
                                $infoValue = "Champion Id";
                                break;
    
                            case 'worldhappiness':
                                $value1 = "Country";
                                $value2 = "HappinessRank";
                                $infoValue = "Happiness Ranks for Countries";
                                break;
    
                            case 'unemploymentrate':
                                $value1 = "OCATION";
                                $value2 = "Value";
                                $infoValue = "Value of Unemployment for Countries";
                                break;
                            }
                        }
                    }
                }
            
            ?>
    </head>
    <body>
        <form action="index.php" method="POST">
            <select class="select" name="database">
                <option value="summonerstats">Summoner Stats</option>
                <option value="champidwithname">Champion Ids With Name</option>
                <option value="worldhappiness">World Happiness</option>
                <option value="unemploymentrate">Unemployment Rate</option>
            </select><br>
            <select class="select" name="type">
                <option value="json">json</option>
                <option value="xml">xml</option>
            </select><br>
            <input class="select" type="text" name="textInput"><br>
            <input class="select" type="submit" value="Submit" name="SubmitForm">
        </form>

        <canvas id="myChart"></canvas>

        <script>
        var ctx = document.getElementById('myChart');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?php foreach ($array as $arr) {
                            echo "'" . $arr['' . $value1 . ''] . "', ";
                        }
                        ?>],
                datasets: [{
                    label: <?php echo "'".$infoValue."'" ?>,
                    data:[<?php foreach ($array as $arr) {
                            echo $arr['' . $value2 . ''] . ",";
                        }
                        ?>],
                    backgroundColor: [
                        
                    ],
                    borderColor: [
                        
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        </script>
    </body>
</html>


<?php
function xmlstring2array($string)
{
    $xml   = simplexml_load_string($string, 'SimpleXMLElement', LIBXML_NOCDATA);

    $array = json_decode(json_encode($xml), TRUE);

    return $array;
}
?>