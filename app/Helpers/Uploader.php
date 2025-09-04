<?php

namespace App\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Image;
Use DB;
use File;

class Uploader
{

    public static function universalUpload($options,$request){

        $multiple = false;
        if(empty($options['directory'])){
            return array('success'=>false,'error'=>true,'message'=>"No Defined Path",'files_upload'=>0,'media_path'=>array());
        }

        if(empty($options['files'])){
            return array('success'=>false,'error'=>true,'message'=>"No Files Found.",'files_upload'=>0,'media_path'=>array());
        }

        if(isset($options['multiple']) and $options['multiple']){
			$multiple = true;
 		}

        $path = $options['directory'];
        $files = $options['files'];
        $thumb = $options['thumb'];
        $allowExtension = !empty($options['allowExtension'])?$options['allowExtension']:array('jpg','png','gif','bmp','webp','svg','JPG','PNG','GIF','BMP','WebP','SVG');
        $filesArray =array();

        foreach ($files as $k => $fileKey) {
            if($request->hasFile($fileKey))
			{
                $RequestFiles = $request->file($fileKey);

                if(empty($RequestFiles)){
                    return array('success'=>false,'error'=>true,'message'=>"No Files Found.",'files_upload'=>0,'media_path'=>array());
                }

                if(!File::exists($path)){ File::makeDirectory($path, 0775, true, true);}

                if($multiple){
                    foreach($RequestFiles as $FKey=>$file)
                    {
                        $getFile = Uploader::fileUpload($file,$path,$thumb,$allowExtension);
                        if(!empty($getFile)){
                            if(!empty($getFile['error'])){
                                $filesArray[$fileKey][$FKey]['error'] = $getFile['error'];
                                $rMsg = "File Not Uploaded.";
                            }else{
                                $rMsg = "File Uploaded Successfully.";
                                $filesArray[$fileKey][$FKey]['orgName'] =  $getFile['orgName'];
                                $filesArray[$fileKey][$FKey]['mediaPath'] = $getFile['mediaPath'];
                            }
                        }

                    }
                }
                else
                {
                    $getFile = Uploader::fileUpload($RequestFiles,$path,$thumb,$allowExtension);
                    if(!empty($getFile)){
                        if(!empty($getFile['error'])){
                            $filesArray[$fileKey]['error'] = $getFile['error'];
                            $rMsg = "File Not Uploaded.";
                        }else{
                            $rMsg = "File Uploaded Successfully.";
                            $filesArray[$fileKey]['orgName'] = $getFile['orgName'];
                            $filesArray[$fileKey]['mediaPath'] = $getFile['mediaPath'];
                        }
                    }
                }
            }
        }

        if(empty($filesArray)){
            return array('success'=>false,'error'=>true,'message'=>"No Files Found.",'files_upload'=>0,'media_path'=>array());
        }
        return array('success'=>true,'message'=>$rMsg,'count'=>count($filesArray),'media_path'=>$filesArray);
    }

    public static function fileUpload($file,$path,$thumb,$allowExtension){

        $extension = $file->extension();
        $orgName = $file->getClientOriginalName();
        $newNameImage = rand().str_replace(' ','_',$orgName);
        $response = array();
        if(in_array($extension,$allowExtension)){
            $errorMsg = '';
            $getFile = $file->getPathName();
            if(in_array($extension,array('jpg','png','gif','bmp','webp','JPG','PNG','GIF','BMP','WebP'))){

                if(!File::exists($path)){  File::makeDirectory($path, 0775, true, true); }

                try {
                    $img = Image::read($getFile)->orient()->save($path.$newNameImage);
                } catch (\Exception $e) {
                    $errorMsg = str_replace($path,'',$e->getMessage());
                    $response['error'] = $errorMsg;
                    return $response;
                }


                if(!empty($thumb)){
                    foreach ($thumb as $t => $ratio) {
                        if(!empty($ratio['w']) && !empty($ratio['h'])){
                            $savePath = $path.$ratio['w'].'X'.$ratio['h'];

                            if(!File::exists($savePath)){  File::makeDirectory($savePath, 0775, true, true); }

                            $imageDir = $savePath.'/'.$newNameImage;

                            try {
                                Image::read($getFile)->resize($ratio['w'], $ratio['h'])->orient()->save($imageDir);
                            } catch (\Exception $e) {
                                $errorMsg = str_replace($path,'',$e->getMessage());
                            }
                        }
                    }
                }
            }
            else
            {
                if(!File::exists($path)){  File::makeDirectory($path, 0775, true, true); }
                $isUpload = $file->move($path,$newNameImage);
            }

            $response['orgName'] = $orgName;
            $response['mediaPath'] = $newNameImage;
            $response['error'] = (!empty($errorMsg))?$errorMsg:'';
        }
        else{
            $response['error'] = 'Invalid extension file. Allowed extension is '.implode(',',$allowExtension);
        }
        return $response;
    }

    public static function universalUnlink($imageName, $directory){

		if(empty($imageName)){
			return array('success'=>false,'error'=>true,'message'=>"Image Name is Empty",'files_unlink'=>0);
		}

        if(empty($directory)){
            return array('success'=>false,'error'=>true,'message'=>"Path is Empty",'files_unlink'=>0);
        }

        if(is_dir($directory)){
            if(file_exists($directory.$imageName)){
                @unlink($directory.$imageName);
            }
        }

        return array('success'=>true,'message'=>"Files Unlink Successfully.");
    }
}
