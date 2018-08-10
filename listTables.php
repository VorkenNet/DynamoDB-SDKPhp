<?php
require_once("include-aws/aws-autoloader.php");
require_once("config.inc.php");

use Aws\DynamoDb\Exception\DynamoDbException;

date_default_timezone_set('UTC');

$credentials = new Aws\Credentials\Credentials($key, $secret);
$sdk = new Aws\Sdk([
    'region'   => $region,
    'version'  => 'latest',
    'credentials' => $credentials
]);

try {
  $dynamodb = $sdk->createDynamoDb();
  $result = $dynamodb->listTables();
  // TableNames contains an array of table names
  foreach ($result['TableNames'] as $tableName) {
      echo $tableName . "\n";
  }
} catch (DynamoDbException $e) {
    echo "Unable to List Tables:\n";
    echo $e->getMessage() . "\n";
}
?>
