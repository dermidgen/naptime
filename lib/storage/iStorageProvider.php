<?php

namespace naptime\storage;

interface iStorageProvider
{
	public function getDoc($filename);
	public function getDocs();
	public function saveDoc($filename, $content);
	public function saveDocs(array $files);
}

?>
