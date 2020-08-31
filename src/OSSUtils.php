<?php

namespace Harris\AliyunOSS;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use OSS\OssClient;

class OSSUtils {

	private $ossClient;
	private $bucket;

	public function __construct($isInternal = false) {
		if(config('networkType') == 'VPC' && !$isInternal) {
			throw new \Exception('VPC模式下不允许外网上传或者下载');
		}
		
		$ossClient = new OssClient(config('fileuploads.aliyun-oss.accessKeyId'), config('fileuploads.aliyun-oss.accessKeySecret'), config('fileuploads.aliyun-oss.ossServer'));
		$this->ossClient = $ossClient;
		$this->bucket = config('fileuploads.aliyun-oss.ossBucket');
	}

	public static function doUpload($mpf) {
		$oName = $mpf->getClientOriginalName();
		$oExt = $mpf->getClientOriginalExtension();
		$nName = md5($oName . time() . rand()) . '.' . $oExt;
		$date = Carbon::now()->format('Ymd');
		$path = $date . '/' . $nName;
		$ossKey = Config::get('fileuploads.aliyun-oss.ossPrefix') . $path;
		$oss = new OSSUtils();
		
		$result = $oss->ossClient->putObject(
			$oss->bucket,
			$ossKey,
			$mpf->get(),
			[]
		);
		
		if($result) {
			return $path;
		}
		return false;
	}

	public static function doUploadFs($oPath) {
		$oName = basename($oPath);
		$oExt = substr(strrchr($oName, '.'), 1);
		if($oExt == false) {
			$oExt = 'jpg';
		}
		$nName = md5($oName . time() . rand()) . '.' . $oExt;
		$date = Carbon::now()->format('Ymd');
		$path = $date . '/' . $nName;
		$ossKey = Config::get('fileuploads.aliyun-oss.ossPrefix') . $path;
		$oss = new OSSUtils();
		
		$content = file_get_contents($oPath);
		
		$result = $oss->ossClient->putObject(
			$oss->bucket,
			$ossKey,
			$content,
			[]
		);
		
		if($result) {
			return $path;
		}
		return false;
	}

	public static function doUploadContent($content) {
		$oExt = 'jpg';
		$nName = md5(time() . rand()) . '.' . $oExt;
		$date = Carbon::now()->format('Ymd');
		$path = $date . '/' . $nName;
		$ossKey = Config::get('fileuploads.aliyun-oss.ossPrefix') . $path;
		$oss = new OSSUtils();
		
		$result = $oss->ossClient->putObject(
			$oss->bucket,
			$ossKey,
			$content,
			[]
		);
		
		if($result) {
			return $path;
		}
		return false;
	}

	public static function deleteOSSObject($path) {
		$oss = new OSSUtils();
		$ossKey = config('fileuploads.aliyun-oss.ossPrefix') . $path;
		$oss->ossClient->deleteObject(config('fileuploads.aliyun-oss.ossBucket'), $ossKey);
	}

	public static function url($path, $dimension = null) {
		$ossKey = config('fileuploads.aliyun-oss.ossPrefix') . $path;
		$url = config('fileuploads.aliyun-oss.staticEndPoint') . $ossKey;
		return self::append($url, $dimension);
	}

	public static function getObjectUrl($ossKey) {
		return config('fileuploads.aliyun-oss.staticEndPoint') . $ossKey;
	}

	public static function getAllObjectKey() {
		$oss = new OSSUtils();
		return $oss->ossClient->getAllObjectKey(config('fileuploads.aliyun-oss.ossBucket'));
	}

	public static function getAllObjectUrls() {
		$objectKeys = self::getAllObjectKey();
		$data = [];
		foreach($objectKeys as $key) {
			$data[count($data)] = [
				'url' => self::getObjectUrl($key),
			];
		}
		return $data;
	}

	public static function append($imgUrl, $dimension) {
		if(!empty($dimension)) {
			$divider = strpos($dimension, 'x');
			$height = substr($dimension, $divider + 1);
			$width = substr($dimension, 0, $divider);
            return $imgUrl . "?x-oss-process=image/resize,m_fill,h_$height,w_$width";
		}
		return $imgUrl;
	}
}
