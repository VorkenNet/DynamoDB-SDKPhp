<?php
require_once("include-aws/aws-autoloader.php");
require_once("config.inc.php");

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

date_default_timezone_set('UTC');

$credentials = new Aws\Credentials\Credentials($key, $secret);
$sdk = new Aws\Sdk([
    'region'   => $region,
    'version'  => 'latest',
    'credentials' => $credentials
]);

$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();

$tableName = 'Movies';

$year = 2013;
$title = 'Escape Plan';

$key = $marshaler->marshalJson('
    {
        "year": ' . $year . ',
        "title": "' . $title . '"
    }
');


$params = [
    'TableName' => $tableName,
    'Key' => $key
];

try {
    $result = $dynamodb->getItem($params);
    print_r($marshaler->unmarshalItem($result["Item"]));

} catch (DynamoDbException $e) {
    echo "Unable to get item:\n";
    echo $e->getMessage() . "\n";
}

?>
