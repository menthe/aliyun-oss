<?php

return [
	'aliyun-oss' => [
		'staticEndPoint' => env('STATIC_ENDPOINT', ''),
		'ossBucket' => env('OSS_BUCKET', ''),
		'ossPrefix' => env('OSS_PREFIX', ''),
		'ossCity' => env('OSS_CITY', '上海'),
		'networkType' => env('NETWORK_TYPE', '经典网络'),
		'ossServer' => env('OSS_SERVER', 'http://oss-cn-shanghai.aliyuncs.com'),
		'ossServerInternal' =>env('OSS_SERVER_INTERNAL', 'http://oss-cn-shanghai-internal.aliyuncs.com'),
		'accessKeyId' => env('ACCESS_KEY_ID', ''),
		'accessKeySecret' => env('ACCESS_KEY_SECRET', ''),
	]
];