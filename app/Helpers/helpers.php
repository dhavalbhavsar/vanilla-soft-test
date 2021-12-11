<?php

if (! function_exists('storeBase64File')) {
    function storeBase64File($attachmentObject)
    {
        if (strpos($attachmentObject['base64_file'], ';base64') !== false) {
            [, $attachmentObject['base64_file']] = explode(';', $attachmentObject['base64_file']);
            [, $attachmentObject['base64_file']] = explode(',', $attachmentObject['base64_file']);
        }


        $ext  = pathinfo($attachmentObject['file_name'], PATHINFO_EXTENSION);

        $newFileName = md5(date('Y-m-d H:i:s:u')).'-'.bin2hex(random_bytes(10)).'.'.$ext;

		$imageData = base64_decode($attachmentObject['base64_file']);

		Storage::disk('local')->put($newFileName, $imageData);

		return [
			'file_name' => $newFileName,
			'file_path' => 'app'
		];
    }
}