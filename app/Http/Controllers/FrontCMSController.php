<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiOperationFailedException;
use App\Http\Requests\UpdateFrontCmsRequest;
use App\Models\FrontCms;
use App\Traits\ImageTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Laracasts\Flash\Flash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class FrontCMSController extends AppBaseController
{
    /**
     * @return Application|Factory|View
     */
    public function frontCms(): View|Factory|Application
    {
        $frontCms = FrontCms::all()->pluck('value', 'key');

        return view('front_cms.index', compact('frontCms'));
    }

    /**
     * @param UpdateFrontCmsRequest $request
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function updateFrontCms(UpdateFrontCmsRequest $request): Redirector|RedirectResponse|Application
    {
        $input = $request->all();
        $input = Arr::except($input, ['_token']);

        if (isset($input['landing_image']) && !empty($input['landing_image'])) {
            $this->deleteImageValue('landing_image');
            $input['landing_image'] = $this->uploadImage($input['landing_image'], 750, 500);
        }
        
        if (isset($input['features_image']) && !empty($input['features_image'])) {
            $this->deleteImageValue('features_image');
            $input['features_image'] = $this->uploadImage($input['features_image'], 750, 500);
        }
        
        if (isset($input['testimonials_image_1']) && !empty($input['testimonials_image_1'])) {
            $this->deleteImageValue('testimonials_image_1');
            $input['testimonials_image_1'] = $this->uploadImage($input['testimonials_image_1'], 300, 350);
        }
        
        if (isset($input['testimonials_image_2']) && !empty($input['testimonials_image_2'])) {
            $this->deleteImageValue('testimonials_image_2');
            $input['testimonials_image_2'] = $this->uploadImage($input['testimonials_image_2'], 300, 350);
        }
        
        if (isset($input['testimonials_image_3']) && !empty($input['testimonials_image_3'])) {
            $this->deleteImageValue('testimonials_image_3');
            $input['testimonials_image_3'] = $this->uploadImage($input['testimonials_image_3'], 300, 350);
        }      
        
        if (isset($input['start_chat_image']) && !empty($input['start_chat_image'])) {
            $this->deleteImageValue('start_chat_image');
            $input['start_chat_image'] = $this->uploadImage($input['start_chat_image'], 1200, 800);
        }   
        
        if (isset($input['feature_image_1']) && !empty($input['feature_image_1'])) {
            $this->deleteImageValue('feature_image_1');
            $input['feature_image_1'] = $this->uploadImage($input['feature_image_1'], 300, 300);
        }
        
        if (isset($input['feature_image_2']) && !empty($input['feature_image_2'])) {
            $this->deleteImageValue('feature_image_2');
            $input['feature_image_2'] = $this->uploadImage($input['feature_image_2'], 300, 300);
        }
        
        if (isset($input['feature_image_3']) && !empty($input['feature_image_3'])) {
            $this->deleteImageValue('feature_image_3');
            $input['feature_image_3'] = $this->uploadImage($input['feature_image_3'], 300, 300);
        }
        
        if (isset($input['feature_image_4']) && !empty($input['feature_image_4'])) {
            $this->deleteImageValue('feature_image_4');
            $input['feature_image_4'] = $this->uploadImage($input['feature_image_4'], 300, 300);
        }
        
        foreach ($input as $key => $value) {
            FrontCms::where('key', '=', $key)->first()->update(['value' => $value]);
        }
        Flash::success('Front CMS updated successfully.');
        
        return redirect(route('front.cms'));
    }

    /**
     * @param $keyName
     *
     */
    public function deleteImageValue($keyName): void
    {
        $frontCms = FrontCms::where(['key' => $keyName])->first();
        if (!empty($frontCms) && !empty($frontCms->value)) {
            $oldImage = $frontCms->value;
            $frontCms->update(['value' => '']);
            $this->deleteImage($oldImage);
        }
    }

    /**
     * @param $file
     * @param $width
     * @param $height
     *
     * @throws ApiOperationFailedException
     *
     * @return string
     */
    public function uploadImage($file, $width, $height): string
    {
       return  ImageTrait::makeImage($file, FrontCms::PATH, ['width' => $width, 'height' => $height]);
    }

    /**
     * @param $fileName
     *
     * @return bool
     */
    public function deleteImage($fileName): bool
    {
        if (empty($fileName)) {
            return true;
        }
        ImageTrait::deleteImage(FrontCms::PATH.DIRECTORY_SEPARATOR.$fileName);

        return ImageTrait::deleteImage(FrontCms::PATH.DIRECTORY_SEPARATOR.$fileName);
    }
}
