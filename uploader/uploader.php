<?php
 
	// We're putting all our files in a directory called images.
	$uploaddir = 'uploads/';
	 
	// The posted data, for reference
	$file = $_POST['value'];
	$name = $_POST['name'];
	 
	// Get the MIME type
	$getMime = explode('.', $name);
	$mime = end($getMime);
	 
	// Separate out the data
	$data = explode(',', $file);
	 
	// Encode it correctly
	$encodedData = str_replace(' ','+',$data[1]);
	$decodedData = base64_decode($encodedData);
	
	//creates a new file with same name and extension and uploads it to the uploads folder
	if(file_put_contents($uploaddir.$name, $decodedData)) {
	    echo $name.":uploaded successfully";
	}
	else {
	    // Show an error message should something go wrong.
	    echo "Something went wrong. Check that the file isn't corrupted";
	}
?>