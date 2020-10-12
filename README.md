# Instruction
This package is for upload any type of file

For install type this command
```
composer require baki/uploader
```
After installation, on the page where you use the package you need to insert this part of the code
```
use Baki\Uploader;
```
## Example
Add the input:
```
<input type="file" name="your_name_input"> // for single file
<input type="file" name="your_name_input[]" multiple> // for multiple files
```
In the php file, first instantiate the Uploader class
```
$file = new Uploader($request, 'your_name_input'); // $request must be instance of Illuminate\Http\Request
```
There are 2 optional arguments in this class. Delimiter and random name.

Delimiter means that all spaces will be replaced with that argument. The default is '-'.

Random name (bool) represents whether the file name will have random characters. The default is 0.
##### Exaple:
```
$file = new Uploader($request, 'your_name_input', '_', 1);
For multiple files on save it return like
[
    "SlReKKT0_xSMByfiU_josh_wilburne_147469_unsplash.jpg",
    "H8rBfHmB_0UE4bag3_photo_1537411809_5959db7f925d.jpg",
    "hEX1J3dy_x7UleULw_photo_1537408621655_3034354224c4.jpg"
]
-------------------------------------------------------------------------------------
For single file on save it return like "SlReKKT0_xSMByfiU_josh_wilburne_147469_unsplash.jpg"
```

Next set the allowed mime types for file [OPTIONAL] (e.g. for image)
```
$file->setMimeType('image/png', 'image/jpg', 'image/jpeg');
or
$file->setMimeType('image'); // All type of image pass the validation
```
If you do not call this method, you can upload any file. Then there is no validation for the file type
Next set the file max size for upload (The number is in megabytes)
```
$file->setFileMaxSize(20); // the maximum file size to upload is 20MB
```
Next set the save path for file
```
$path = '/var/www/sites/domain.com/project/banners; // this is example
$file->setSavePath($path);
```
On finish call method save for save image on path. This method return the name of file
```
$file->save();
```
##### This method for single file return like
```
    "josh-wilburne-147469-unsplash.jpg"
```
##### This method for multiple files return like 
```
[
    "josh-wilburne-147469-unsplash.jpg",
    "garin-chadwick-sLxQaYfnD20-unsplash.jpg",
    "chewy-8S0cSJ1Dy9Q-unsplash.jpg"
]
``` 