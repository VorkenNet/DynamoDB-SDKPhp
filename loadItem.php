<?php
require_once("include-aws/aws-autoloader.php");
require_once("config.inc.php");

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

date_default_timezone_set('UTC');

$credentials = new Aws\Credentials\Credentials($key, $secret);
$sdk = new Aws\Sdk([
    //'endpoint'   => 'http://localhost:8000',
    'region'   => $region,
    'version'  => 'latest',
    'credentials' => $credentials
]);

$dynamodb = $sdk->createDynamoDb();

$marshaler = new Marshaler();
//The DynamoDB Marshaler class has methods for converting JSON documents and PHP arrays to the DynamoDB format.
//In this program, $marshaler->marshalJson($json) takes a JSON document and converts it into a DynamoDB item.

$tableName = 'Movies';

$movies = json_decode(file_get_contents('moviedata.json'), true);
foreach ($movies as $movie) {

    $year = $movie['year'];
    $title = $movie['title'];
    $info = $movie['info'];

    $json = json_encode([
        'year' => $year,
        'title' => $title,
        'info' => $info
    ]);

    $params = [
        'TableName' => $tableName,
        'Item' => $marshaler->marshalJson($json)
    ];

    try {
        $result = $dynamodb->putItem($params);
        echo "Added movie: " . $movie['year'] . " " . $movie['title'] . "\n";
    } catch (DynamoDbException $e) {
        echo "Unable to add movie:\n";
        echo $e->getMessage() . "\n";
        break;
    }

}
 ?>
