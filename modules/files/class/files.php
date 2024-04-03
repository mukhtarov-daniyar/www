<?

	class Files
	{
		private $saveDirPath = 'documents/attachments/';
		private $sourceFile = '';
		private $tempFilePath;
		private $fileSize;
		private $fileName;
		private $extFile = '';
		private $MIME;
		
		function setPath($path)
		{
			$this->saveDirPath = $path;
		}
		
		/* $file = type array */
		function addFile($file)
		{
			$this->sourceFile = $file;
		}
		/* return filepath url */
		function getUrlFile($prefix='')
		{    
			if (move_uploaded_file($this->getTempFilePath(), $this->saveDirPath.$this->getFileName() ))
			{	
				$newFileName = $this->saveDirPath.$this->getNewFileName();
				$oldFileName = $this->saveDirPath.$this->fileName;
				
				if(rename($oldFileName, $newFileName))
					return $prefix.$newFileName;
				
				else
					return false;
			}else
				return false;
		}
		
		protected function getTempFilePath()
		{
			$this->tempFilePath = $this->sourceFile["tmp_name"];
			
			return $this->tempFilePath;
		}
		
		protected function getExtFile()
		{
			$this->extFile = strtolower(pathinfo($this->getFileName(), PATHINFO_EXTENSION));

			return $this->extFile;
		}

		protected function getMIMETypeFile()
		{
			$FINFO = finfo_open(FILEINFO_MIME_TYPE);
			$this->MIME = finfo_file($FINFO, $this->getTempFilePath());
			
			return $this->MIME;
		}
		
		protected function getTypeFile()
		{
			$this->fileType = $this->sourceFile["type"];		
			
			return $this->fileType;	
		}
		
		protected function getFileSize()
		{
			$this->fileSize = round(filesize($this->getTempFilePath())/1024, 2);	
			
			return $this->fileSize;
		}
		
		protected function getFileName()
		{
			$this->fileName = $this->sourceFile["name"];
			
			return $this->fileName; 
		}
		
		protected function getNewFileName()
		{
			return rand(1000000000, 9999999999999).".".$this->getExtFile();
		}
		
	}

?>