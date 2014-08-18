<?php
class Base64ImageUpload{

	private $base64image;
	private $base_path;

	public function setBase64Image($data)
	{
		$this->base64image = $data;
	}

	public function save()
	{
		if (empty($this->base64image)) {
			return false;
		}
		//data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAZAAAAD6CAYAAACPpxFEAAAI1
		list($type, $data) = explode(';', $this->base64image);
		list(, $data)      = explode(',', $data);
		$data = base64_decode($data);

		$md5 = md5($data);
		$path = substr($md5, 0,2) . '/' . substr($md5, 2,2) ;
		$abslute_path = $this->base_path . '/' . $path;

		if(!File::exists($abslute_path))
		{
			File::makeDirectory($abslute_path, 0777, true, true);
		}

		$path .= '/' . $md5 . '.png';
		File::put($this->base_path . '/'. $path,  $data);

		return $path;
	}

	public function setBasePath($path)
	{
		$this->base_path = $path;
	}

}
