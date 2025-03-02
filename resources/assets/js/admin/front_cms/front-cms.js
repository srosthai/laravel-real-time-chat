$('#frontCmsImg').on('change', function () {
    readURL(this, 'front-cms-img');
});

$('#featuresImg').on('change', function () {
    readURL(this, 'features-img');
});

$('#testimonialImageInput1').on('change', function () {
    readURL(this, 'testimonials-img-1');
});

$('#testimonialImageInput2').on('change', function () {
    readURL(this, 'testimonials-img-2');
});

$('#testimonialImageInput3').on('change', function () {
    readURL(this, 'testimonials-img-3');
});

$('#startChatImg').on('change', function () {
    readURL(this, 'start-chat-img');
});

$('#featureImageInput1').on('change', function () {
    readURL(this, 'feature-img-1');
});

$('#featureImageInput2').on('change', function () {
    readURL(this, 'feature-img-2');
});

$('#featureImageInput3').on('change', function () {
    readURL(this, 'feature-img-3');
});

$('#featureImageInput4').on('change', function () {
    readURL(this, 'feature-img-4');
});

// profile js
function readURL (input, photoId) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();

        reader.onload = function (e) {
            $('#' + photoId).attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

$('#frontCmsFrom').on('submit', function (event) {
    event.preventDefault();
    let loadingButton = jQuery(this).find('#btnSave');
    loadingButton.button('loading');

    $('#frontCmsFrom')[0].submit();

    return true;
});
