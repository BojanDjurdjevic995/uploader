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
There are 2 optional arguments in this class. delimiter and random name.
Delimiter means that all spaces will be replaced with that argument. The default is '-'.
Random name (bool) represents whether the file name will have random characters. The default is 0.

Next set the allowed mime types for file [OPTIONAL] (e.g. for image)
```
$file->setMimeType('image/png', 'image/jpg', 'image/jpeg');
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
##### This method for single file return name of file (e.g. josh-wilburne-147469-unsplash.jpg)
##### This method for multiple files return array of names for file (e.g. ['josh-wilburne-147469-unsplash.jpg', 'garin-chadwick-sLxQaYfnD20-unsplash.jpg', 'chewy-8S0cSJ1Dy9Q-unsplash.jpg'])