<?php 

error_reporting(E_ALL);


if (!$link = mysql_connect('localhost', 'license_dev', 'license_dev'))
{
    echo 'Не удалось подключиться к mysql';
    exit;
}

if (!mysql_select_db('license_dev', $link))
{
    echo 'Не удалось выбрать базу данных';
    exit;
}

// Get domain from where request came
$parse = parse_url($_SERVER['HTTP_REFERER']);
$domain = $parse['host'];

$sql = "
    SELECT 
        `m`.*,
        (
            SELECT `expire`
            FROM `keys`
            WHERE `module_code` = `m`.`code` AND `match` = '" . $domain . "'
            LIMIT 1
        ) AS `key_expired_at`
    FROM `module` AS `m` 
";

$result = mysql_query($sql, $link);

if (!$result)
{
    echo "Ошибка DB, запрос не удался\n";
    echo 'MySQL Error: ' . mysql_error();
    exit;
}


$modules = array('apps' => array());


while ($module = mysql_fetch_assoc($result))
{
    if ($module['key_expired_at'])
    {
        // Get normal dates
        $today = time();
        $seconds_left = $module['key_expired_at'] - $today;
        
        // Module will be expired in N days
        $days_left = floor($seconds_left/3600/24);

        // Module will be expired in 01/06/2014
        $expired_at = gmdate("d-m-Y", $module['key_expired_at']);
    }
    else
    {
        $days_left = NULL;
        $expired_at = NULL;
    }

    $modules['apps'][] = array(
    	"image" => 'http://' . $_SERVER['HTTP_HOST'] . "/public/modules/" . $module['code'] . '/logo-md.png',
		"title" => $module['name'],
		"category" => $module['category'],
		"updated_at" => "Updated " . $module['updated_at'],
		"price" => $module['price'] . "$",
        "system_name" => $module['code'],
        "expired_at" => $expired_at,
		"days_left" => $days_left
	);
}

echo $_GET['callback'] . '(' . json_encode($modules) . ')';

mysql_free_result($result); die();