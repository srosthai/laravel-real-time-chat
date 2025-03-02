@extends('layouts.app')
@section('title')
    {{ __('Front CMS') }}
@endsection
@section('page_css')
    <link rel="stylesheet" href="{{ mix('assets/css/admin_panel.css') }}">
@endsection
@section('content')
    <div class="container-fluid page__container">
        <div class="animated fadeIn main-table">
            @include('flash::message')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header page-header">
                            <div class="pull-left page__heading my-2">
                                @yield('title')
                            </div>
                        </div>
                        <div class="card-body">
                            @include('coreui-templates::common.errors')
                            <form method="post" id="frontCmsFrom" enctype="multipart/form-data"
                                  action="{{ route('front.cms.update') }}">
                                {{ csrf_field() }}
                                <h4 class="mb-4">Main Content</h4>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('sub_heading', __('Sub Heading').':' ) !!}<span class="red">*</span>
                                            {!! Form::text('sub_heading', $frontCms['sub_heading'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Sub Heading')]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('description', __('Description').':' ) !!}<span class="red">*</span>
                                            {!! Form::textarea('description', $frontCms['description'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Description'), 'rows' => 2]) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mt-2 d-flex flex-wrap">
                                        <div class="mt-2 user__upload-btn w-auto me-sm-4 me-2">
                                            <label class="btn btn-primary">
                                                {{ __('Upload Image') }}
                                                <input id="frontCmsImg" class="d-none" name="landing_image" type="file"
                                                       accept="image/*">
                                            </label>
                                        </div>
                                        <div class="cms-img-wrapper">
                                            <img src="{{ !empty(getFrontCmsImage('landing_image')) ? getFrontCmsImage('landing_image') : asset('assets/images/chat-illustrator.png') }}"
                                                 alt="" id="front-cms-img">
                                        </div>
                                    </div>
                                </div>

                                <h4 class="mb-4">Features</h4>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('feature_title_1', __('Title 1').':' ) !!}<span class="red">*</span>
                                            {!! Form::text('feature_title_1', $frontCms['feature_title_1'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Title 1')]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('feature_text_1', __('Description').':' ) !!}<span
                                                    class="red">*</span>
                                            {!! Form::textarea('feature_text_1', $frontCms['feature_text_1'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Description'), 'rows' => 2]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group mt-2 d-flex flex-wrap">
                                            <div class="mt-4 user__upload-btn w-auto me-sm-4 me-2">
                                                <label class="btn btn-primary">
                                                    {{ __('Upload Image') }}
                                                    <input id="featureImageInput1" class="d-none" name="feature_image_1"
                                                           type="file"
                                                           accept="image/*">
                                                </label>
                                            </div>
                                            <div class="cms-img-wrapper">
                                                <img src="{{ !empty(getFrontCmsImage('feature_image_1')) ? getFrontCmsImage('feature_image_1') : asset('assets/images/no-img.jpeg') }}"
                                                     alt="" id="feature-img-1" width="10">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('feature_title_2', __('Title 2').':' ) !!}<span class="red">*</span>
                                            {!! Form::text('feature_title_2', $frontCms['feature_title_2'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Title 2')]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('feature_text_2', __('Description').':' ) !!}<span
                                                    class="red">*</span>
                                            {!! Form::textarea('feature_text_2', $frontCms['feature_text_2'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Description'), 'rows' => 2]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group mt-2 d-flex flex-wrap">
                                            <div class="mt-4 user__upload-btn w-auto me-sm-4 me-2">
                                                <label class="btn btn-primary">
                                                    {{ __('Upload Image') }}
                                                    <input id="featureImageInput2" class="d-none" name="feature_image_2"
                                                           type="file"
                                                           accept="image/*">
                                                </label>
                                            </div>
                                            <div class="cms-img-wrapper">
                                                <img src="{{ !empty(getFrontCmsImage('feature_image_2')) ? getFrontCmsImage('feature_image_2') : asset('assets/images/no-img.jpeg') }}"
                                                     alt="" id="feature-img-2">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('feature_title_3', __('Title 3').':' ) !!}<span
                                                    class="red">*</span>
                                            {!! Form::text('feature_title_3', $frontCms['feature_title_3'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Title 3')]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('feature_text_3', __('Description').':' ) !!}<span
                                                    class="red">*</span>
                                            {!! Form::textarea('feature_text_3', $frontCms['feature_text_3'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Description'), 'rows' => 2]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group mt-2 d-flex flex-wrap">
                                            <div class="mt-4 user__upload-btn w-auto me-sm-4 me-2">
                                                <label class="btn btn-primary">
                                                    {{ __('Upload Image') }}
                                                    <input id="featureImageInput3" class="d-none" name="feature_image_3"
                                                           type="file"
                                                           accept="image/*">
                                                </label>
                                            </div>
                                            <div class="cms-img-wrapper">
                                                <img src="{{ !empty(getFrontCmsImage('feature_image_3')) ? getFrontCmsImage('feature_image_3') : asset('assets/images/no-img.jpeg') }}"
                                                     alt="" id="feature-img-3">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('feature_title_4', __('Title 4').':' ) !!}<span
                                                    class="red">*</span>
                                            {!! Form::text('feature_title_4', $frontCms['feature_title_4'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Title 4')]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('feature_text_4', __('Description').':' ) !!}<span
                                                    class="red">*</span>
                                            {!! Form::textarea('feature_text_4', $frontCms['feature_text_4'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Description'), 'rows' => 2]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group mt-2 d-flex flex-wrap">
                                            <div class="mt-4 user__upload-btn w-auto me-sm-4 me-2">
                                                <label class="btn btn-primary">
                                                    {{ __('Upload Image') }}
                                                    <input id="featureImageInput4" class="d-none" name="feature_image_4"
                                                           type="file"
                                                           accept="image/*">
                                                </label>
                                            </div>
                                            <div class="cms-img-wrapper">
                                                <img src="{{ !empty(getFrontCmsImage('feature_image_4')) ? getFrontCmsImage('feature_image_4') : asset('assets/images/no-img.jpeg') }}"
                                                     alt="" id="feature-img-4">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="mb-4">Chat Features</h4>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mt-2 d-flex flex-wrap">
                                        <div class="mt-4 user__upload-btn w-auto me-sm-4 me-2">
                                            <label class="btn btn-primary">
                                                {{ __('Upload Image') }}
                                                <input id="featuresImg" class="d-none" name="features_image"
                                                       type="file"
                                                       accept="image/*">
                                            </label>
                                        </div>
                                        <div class="cms-img-wrapper">
                                            <img src="{{ !empty(getFrontCmsImage('features_image')) ? getFrontCmsImage('features_image') : asset('assets/images/chat-landing2.png') }}"
                                                 alt="" id="features-img">
                                        </div>
                                    </div>
                                </div>

                                <h4 class="mb-4">TESTIMONIALS</h4>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('testimonials_name_1', __('Name').':' ) !!}<span
                                                    class="red">*</span>
                                            {!! Form::text('testimonials_name_1', $frontCms['testimonials_name_1'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Name')]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('testimonials_comment_1', __('Comment').':' ) !!}<span
                                                    class="red">*</span>
                                            {!! Form::textarea('testimonials_comment_1', $frontCms['testimonials_comment_1'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Comment'), 'rows' => 2]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group mt-2 d-flex flex-wrap">
                                            <div class="mt-4 user__upload-btn w-auto me-sm-4 me-2">
                                                <label class="btn btn-primary">
                                                    {{ __('Upload Image') }}
                                                    <input id="testimonialImageInput1" class="d-none"
                                                           name="testimonials_image_1" type="file"
                                                           accept="image/*">
                                                </label>
                                            </div>
                                            <div class="cms-img-wrapper">
                                                <img src="{{ !empty(getFrontCmsImage('testimonials_image_1')) ? getFrontCmsImage('testimonials_image_1') : asset('assets/images/team-3.jpg') }}"
                                                     alt="" id="testimonials-img-1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('testimonials_name_2', __('Name').':' ) !!}<span
                                                    class="red">*</span>
                                            {!! Form::text('testimonials_name_2', $frontCms['testimonials_name_2'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Name')]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('testimonials_comment_2', __('Comment').':' ) !!}<span
                                                    class="red">*</span>
                                            {!! Form::textarea('testimonials_comment_2', $frontCms['testimonials_comment_2'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Comment'), 'rows' => 2]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group mt-2 d-flex flex-wrap">
                                            <div class="mt-4 user__upload-btn w-auto me-sm-4 me-2">
                                                <label class="btn btn-primary">
                                                    {{ __('Upload Image') }}
                                                    <input id="testimonialImageInput2" class="d-none"
                                                           name="testimonials_image_2" type="file"
                                                           accept="image/*">
                                                </label>
                                            </div>
                                            <div class="cms-img-wrapper">
                                                <img src="{{ !empty(getFrontCmsImage('testimonials_image_2')) ?getFrontCmsImage('testimonials_image_2') : asset('assets/images/team-3.jpg') }}"
                                                     alt="" id="testimonials-img-2">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('testimonials_name_3', __('Name').':' ) !!}<span
                                                    class="red">*</span>
                                            {!! Form::text('testimonials_name_3', $frontCms['testimonials_name_3'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Name')]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('testimonials_comment_3', __('Comment').':' ) !!}<span
                                                    class="red">*</span>
                                            {!! Form::textarea('testimonials_comment_3', $frontCms['testimonials_comment_3'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Comment'), 'rows' => 2]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group mt-2 d-flex flex-wrap">
                                            <div class="mt-4 user__upload-btn w-auto me-sm-4 me-2">
                                                <label class="btn btn-primary">
                                                    {{ __('Upload Image') }}
                                                    <input id="testimonialImageInput3" class="d-none"
                                                           name="testimonials_image_3" type="file"
                                                           accept="image/*">
                                                </label>
                                            </div>
                                            <div class="cms-img-wrapper">
                                                <img src="{{ !empty(getFrontCmsImage('testimonials_image_3')) ? getFrontCmsImage('testimonials_image_3') : asset('assets/images/team-3.jpg') }}"
                                                     alt="" id="testimonials-img-3">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="mb-4">Start Chat Component</h4>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('start_chat_title', __('Title').':' ) !!}<span
                                                    class="red">*</span>
                                            {!! Form::text('start_chat_title', $frontCms['start_chat_title'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Title')]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('start_chat_subtitle', __('Sub Title').':' ) !!}<span
                                                    class="red">*</span>
                                            {!! Form::text('start_chat_subtitle', $frontCms['start_chat_subtitle'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Sub Title')]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group mt-2 d-flex flex-wrap">
                                            <div class="mt-4 user__upload-btn w-auto me-sm-4 me-2">
                                                <label class="btn btn-primary">
                                                    {{ __('Upload Image') }}
                                                    <input id="startChatImg" class="d-none" name="start_chat_image"
                                                           type="file"
                                                           accept="image/*">
                                                </label>
                                            </div>
                                            <div class="cms-img-wrapper">
                                                <img src="{{ !empty(getFrontCmsImage('start_chat_image')) ? getFrontCmsImage('start_chat_image') : asset('assets/images/chat-landing1.png') }}"
                                                     alt="" id="start-chat-img">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="mb-4">Footer</h4>
                                <div class="col-md-6 p-0">
                                    <div class="form-group login-group__sub-title">
                                        {!! Form::label('footer_description', __('Description').':' ) !!}<span
                                                class="red">*</span>
                                        {!! Form::textarea('footer_description', $frontCms['footer_description'], ['class' => 'form-control login-group__input', 'required','placeholder'=> __('Description'), 'rows' => 5]) !!}
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    {{ Form::button(__('messages.save') , ['type'=>'submit', 'id' => 'btnSave', 'class' => 'btn btn-primary me-1','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> " .__('messages.processing')]) }}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('assets/js/admin/front_cms/front-cms.js') }}"></script>
@endsection
