<?php
/**
 * Company: InfyOm Technologies, Copyright 2019, All Rights Reserved.
 * Author: Vishal Ribdiya
 * Email: vishal.ribdiya@infyom.com
 * Date: 11-07-2019
 * Time: 05:15 PM.
 */

namespace App\Traits;

use App\Exceptions\ApiOperationFailedException;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Log;
use Storage;

/**
 * Trait ImageTrait.
 */
trait ImageTrait
{
    /**
     * @param  string  $file
     * @return bool
     */
    public static function deleteImage($file)
    {
        if (Storage::exists($file)) {
            Storage::delete($file);

            return true;
        }

        return false;
    }

    /**
     * @param  UploadedFile  $file
     * @param  string  $path
     * @param  array  $options
     * @return string
     *
     * @throws ApiOperationFailedException
     */
    public static function makeImage($file, $path, $options = [])
    {
        try {
            $fileName = '';
            if (! empty($file)) {
                $extension = $file->getClientOriginalExtension(); // getting image extension
                if (! in_array(strtolower($extension), ['jpg', 'gif', 'png', 'jpeg'])) {
                    throw  new ApiOperationFailedException('invalid image', \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST);
                }
                $originalName = $file->getClientOriginalName();
                $date = \Illuminate\Support\Carbon::now()->format('Y-m-d');
                $originalName = sha1($originalName.time());
                $fileName = $date.'_'.uniqid().'_'.$originalName.'.'.$extension;
                if (isset($options['file_name']) && ! empty($options['file_name'])) {
                    $fileName = $options['file_name'];
                }
                if (! empty($options)) {
                    $imageThumb = Image::make($file->getRealPath())->fit($options['width'], $options['height']);
                    $imageThumb = $imageThumb->stream();
                    Storage::put($path.DIRECTORY_SEPARATOR.$fileName, $imageThumb->__toString());
                } else {
                    Storage::putFileAs($path, $file, $fileName, 'public');
                }
            }

            return $fileName;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new ApiOperationFailedException($e->getMessage(), $e->getCode());
        }
    }

    public static function uploadPWAIcon($file, $options = [])
    {
        try {
            $fileName = '';
            if (! empty($file)) {
                $extension = $file->getClientOriginalExtension(); // getting image extension
                if (! in_array(strtolower($extension), ['jpg', 'gif', 'png', 'jpeg'])) {
                    throw  new ApiOperationFailedException('invalid image', \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST);
                }
                $originalName = $file->getClientOriginalName();
                $date = \Illuminate\Support\Carbon::now()->format('Y-m-d');
                $originalName = sha1($originalName.time());
                $fileName = $date.'_'.uniqid().'_'.$originalName.'.'.$extension;
                if (isset($options['file_name']) && ! empty($options['file_name'])) {
                    $fileName = $options['file_name'];
                }
                if (! empty($options)) {
                    $imageThumb = Image::make($file->getRealPath())->fit($options['width'], $options['height']);
                    $imageThumb = $imageThumb->stream();
                    Storage::disk('pwa')->put('/'.DIRECTORY_SEPARATOR.$fileName, $imageThumb->__toString());
                } else {
                    Storage::putFileAs('/', $file, $fileName, 'public');
                }
            }

            return $fileName;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new ApiOperationFailedException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param  string  $path
     * @return string
     *
     * @internal param $type
     * @internal param bool $full
     */
    public function imageUrl($path)
    {
        return $this->urlEncoding(Storage::url($path));
    }

    /**
     * @param  \Symfony\Component\HttpFoundation\File\UploadedFile  $file
     * @param  array  $input
     * @param  string  $fileName
     * @return string
     *
     * @throws ApiOperationFailedException
     */
    public static function makeThumbnail($file, $input, $fileName = '')
    {
        try {
            if (! empty($file)) {
                $path = $input['path'].DIRECTORY_SEPARATOR.'thumbnails';
                $extension = $file->getClientOriginalExtension(); // getting image extension
                if (! in_array(strtolower($extension), ['jpg', 'gif', 'png', 'jpeg'])) {
                    throw  new ApiOperationFailedException('invalid image', Response::HTTP_BAD_REQUEST);
                }
                if (empty($fileName)) {
                    $originalName = $file->getClientOriginalName();
                    $date = Carbon::now()->format('Y-m-d');
                    $originalName = sha1($originalName.time());
                    $fileName = 'thumbnail'.'_'.$date.'_'.uniqid().'_'.$originalName.'.'.$extension;
                }
                $sourceWidth = Image::make($file->getRealPath())->width();
                $sourceHeight = Image::make($file->getRealPath())->height();
                $result = self::getSizeAdjustedToAspectRatio($sourceWidth, $sourceHeight);
                $imageThumb = Image::make($file->getRealPath())->fit($result['width'], $result['height']);
                $imageThumb = $imageThumb->stream();
                Storage::put($path.DIRECTORY_SEPARATOR.$fileName, $imageThumb->__toString());
            }

            return $fileName;
        } catch (Exception $e) {
            throw new ApiOperationFailedException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param  string  $url
     * @return mixed
     */
    public function urlEncoding($url)
    {
        $entities = [
            '%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F',
            '%25', '%23', '%5B', '%5D', '%5C',
        ];
        $replacements = [
            '!', '*', "'", '(', ')', ';', ':', '@', '&', '=', '+', '$', ',', '/', '?', '%', '#', '[', ']', '/',
        ];

        return str_replace($entities, $replacements, urlencode($url));
    }

    /**
     * @param    $file
     * @param $path
     * @return string
     *
     * @throws ApiOperationFailedException
     */
    public static function uploadVideo($file, $path)
    {
        try {
            $fileName = '';
            if (! empty($file)) {
                $extension = $file->getClientOriginalExtension(); // getting image extension
                if (! in_array(strtolower($extension), ['mp4', 'mov', 'ogg', 'qt'])) {
                    throw  new ApiOperationFailedException('invalid Video', Response::HTTP_BAD_REQUEST);
                }
                $originalName = $file->getClientOriginalName();
                $date = Carbon::now()->format('Y-m-d');
                $originalName = sha1($originalName.time());
                $fileName = $date.'_'.uniqid().'_'.$originalName.'.'.$extension;
                $contents = file_get_contents($file->getRealPath());
                Storage::put($path.DIRECTORY_SEPARATOR.$fileName, $contents, 'public');
            }

            return $fileName;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new ApiOperationFailedException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param    $file
     * @param $path
     * @return string
     *
     * @throws ApiOperationFailedException
     */
    public static function uploadFile($file, $path)
    {
        try {
            $fileName = '';
            if (! empty($file)) {
                $extension = $file->getClientOriginalExtension(); // getting file extension
                if (! in_array(strtolower($extension), ['mp4', 'mp3', 'ogg', 'wav', 'aac', 'alac', 'zip', 'rar'])) {
                    throw  new ApiOperationFailedException('invalid Video', Response::HTTP_BAD_REQUEST);
                }
                $originalName = $file->getClientOriginalName();
                $date = Carbon::now()->format('Y-m-d');
                $originalName = sha1($originalName.time());
                $fileName = $date.'_'.uniqid().'_'.$originalName.'.'.$extension;
                $contents = file_get_contents($file->getRealPath());
                Storage::put($path.DIRECTORY_SEPARATOR.$fileName, $contents, 'public');
            }

            return $fileName;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new ApiOperationFailedException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $sourceWidth
     * @param $sourceHeight
     * @return array
     */
    private static function getSizeAdjustedToAspectRatio($sourceWidth, $sourceHeight)
    {
        if ($sourceWidth > $sourceHeight) {
            $data = $sourceHeight;
        } else {
            $data = $sourceWidth;
        }
        $result = $data / 100;

        return [
            'width' => round($sourceWidth / $result),
            'height' => round($sourceHeight / $result),
        ];
    }

    /**
     * @param    $file
     * @param $path
     * @return string
     *
     * @throws ApiOperationFailedException
     */
    public static function makeAttachment($file, $path)
    {
        try {
            $fileName = '';
            if (! empty($file)) {
                $extension = $file->getClientOriginalExtension(); // getting image extension
                if (! in_array(strtolower($extension), ['xls', 'pdf', 'doc', 'docx', 'xlsx', 'txt'])) {
                    throw  new ApiOperationFailedException('invalid Attachment', Response::HTTP_BAD_REQUEST);
                }
                $originalName = $file->getClientOriginalName();
                $date = Carbon::now()->format('Y-m-d');
                $originalName = sha1($originalName.time());
                $fileName = $date.'_'.uniqid().'_'.$originalName.'.'.$extension;
                $contents = file_get_contents($file->getRealPath());
                Storage::put($path.DIRECTORY_SEPARATOR.$fileName, $contents);
            }

            return $fileName;
        } catch (Exception $e) {
            throw new ApiOperationFailedException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $url
     * @param $path
     * @return string
     *
     * @throws ApiOperationFailedException
     */
    public function importImageFromUrl($url, $path)
    {
        try {
            $extension = '.png';
            $contents = file_get_contents($url);

            $date = Carbon::now()->format('Y-m-d');
            $originalName = sha1(time());
            $fileName = $date.'_'.uniqid().'_'.$originalName.$extension;
            Storage::put($path.DIRECTORY_SEPARATOR.$fileName, $contents, 'public');

            return $fileName;
        } catch (Exception $e) {
            throw new ApiOperationFailedException($e->getMessage());
        }
    }

    /**
     * @param $file
     * @param  string  $path
     * @return string
     *
     * @throws ApiOperationFailedException
     */
    public static function uploadBase64Image($file, $path)
    {
        try {
            if (! empty($file)) {
                $originalName = time().'.'.explode('/',
                        explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
                $date = \Illuminate\Support\Carbon::now()->format('Y-m-d');
                $fileName = $date.'_'.uniqid().'_'.$originalName;
                $image = Image::make($file);
                $imageThumb = $image->stream();
                Storage::put($path.DIRECTORY_SEPARATOR.$fileName, $imageThumb->__toString());

                return $fileName;
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new ApiOperationFailedException($e->getMessage(), $e->getCode());
        }
    }
}
