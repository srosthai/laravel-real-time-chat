const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.copyDirectory('resources/assets/images', 'public/assets/images');
mix.copyDirectory('resources/assets/fonts', 'public/assets/fonts');
mix.copyDirectory('resources/assets/icons', 'public/assets/icons');

mix.copy('node_modules/video.js/dist/video-js.css', 'public/assets/css/video-js.css');
mix.copy('node_modules/@coreui/coreui/dist/css/coreui.min.css', 'public/assets/css/coreui.min.css');
mix.copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/assets/css/bootstrap.min.css');
mix.copy('node_modules/simple-line-icons/css/simple-line-icons.css', 'public/assets/css/simple-line-icons.css');
mix.copy('node_modules/jquery-toast-plugin/dist/jquery.toast.min.css', 'public/assets/css/jquery.toast.min.css');

mix.copy('node_modules/jquery/dist/jquery.min.js', 'public/assets/js/jquery.min.js');
mix.copy('node_modules/video.js/dist/video.min.js', 'public/assets/js/video.min.js');
mix.copy('node_modules/popper.js/dist/umd/popper.min.js', 'public/assets/js/popper.min.js');
mix.copy('node_modules/@coreui/coreui/dist/js/coreui.min.js', 'public/assets/js/coreui.min.js');
mix.copy('node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js', 'public/assets/js/perfect-scrollbar.min.js');
mix.copy('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js', 'public/assets/js/bootstrap.bundle.min.js');
mix.copy('node_modules/jquery-toast-plugin/dist/jquery.toast.min.js', 'public/assets/js/jquery.toast.min.js');
mix.copy('node_modules/emojione/lib/js/emojione.min.js', 'public/assets/js/emojione.min.js');
mix.copy('node_modules/sweetalert2/dist/sweetalert2.all.min.js', 'public/assets/js/sweetalert2.all.min.js');
mix.copy('node_modules/icheck/', 'public/assets/icheck/');
mix.copy('resources/assets/js/moment-with-locales.js',
    'public/assets/js/moment-with-locales.min.js')

mix.js('resources/assets/js/app.js', 'public/assets/js').
    js('resources/assets/js/chat.js', 'public/assets/js').
    js('resources/assets/js/notification.js', 'public/assets/js').
    js('resources/assets/js/set_user_status.js', 'public/assets/js').
    js('resources/assets/js/profile.js', 'public/assets/js').
    js('resources/assets/js/custom.js', 'public/assets/js').
    js('resources/assets/js/auth-forms.js', 'public/assets/js').
    js('resources/assets/js/set-user-on-off.js', 'public/assets/js').
    js('resources/assets/js/admin/users/user.js',
        'public/assets/js/admin/users').
    js('resources/assets/js/admin/meetings/meetings.js',
        'public/assets/js/admin/meetings').
    js('resources/assets/js/admin/meetings/meeting_index.js',
        'public/assets/js/admin/meetings').
    js('resources/assets/js/admin/meetings/member_meeting_index.js',
        'public/assets/js/admin/meetings').
    js('resources/assets/js/admin/users/edit_user.js',
        'public/assets/js/admin/users').
    js('resources/assets/js/admin/roles/role.js',
        'public/assets/js/admin/roles').
    js('resources/assets/js/admin/roles/create_edit_role.js',
        'public/assets/js/admin/roles').
    js('resources/assets/js/admin/reported_users/reported_users.js',
        'public/assets/js/admin/reported_users').
    js('resources/assets/js/admin/front_cms/front-cms.js',
        'public/assets/js/admin/front_cms').
    js('resources/assets/js/custom-datatables.js',
        'public/assets/js/custom-datatables.js');

mix.sass('resources/assets/sass/style.scss', 'public/assets/css').
    sass('resources/assets/sass/font-awesome.scss', 'public/assets/css').
    sass('resources/assets/sass/admin_panel.scss', 'public/assets/css').
    sass('resources/assets/landing-page-scss/scss/landing-page-style.scss', 'public/assets/css').
    sass('resources/assets/sass/new-conversation.scss', 'public/assets/css').
    sass('resources/assets/sass/custom-style.scss', 'public/assets/css').
    version();
/*

mix.babel('public/assets/js/app.js', 'public/assets/js/app.js').
    babel('public/assets/js/chat.js', 'public/assets/js/chat.js').
    babel('public/assets/js/notification.js',
        'public/assets/js/notification.js')
    .babel('public/assets/js/set_user_status.js',
        'public/assets/js/set_user_status.js')
   .babel('public/assets/js/profile.js', 'public/assets/js/profile.js')
   .babel('public/assets/js/custom.js', 'public/assets/js/custom.js')
   .babel('public/assets/js/set-user-on-off.js', 'public/assets/js/set-user-on-off.js')
   .babel('public/assets/js/auth-forms.js', 'public/assets/js/auth-forms.js').version();

mix.babel('public/assets/js/jquery.min.js', 'public/assets/js/jquery.min.js')
   .babel('public/assets/js/video.min.js', 'public/assets/js/video.min.js')
   .babel('public/assets/js/popper.min.js', 'public/assets/js/popper.min.js')
   .babel('public/assets/js/coreui.min.js', 'public/assets/js/coreui.min.js')
   .babel('public/assets/js/perfect-scrollbar.min.js', 'public/assets/js/perfect-scrollbar.min.js')
   .babel('public/assets/js/bootstrap.min.js', 'public/assets/js/bootstrap.min.js')
   .babel('public/assets/js/jquery.toast.min.js', 'public/assets/js/jquery.toast.min.js')
   .babel('public/assets/js/emojione.min.js', 'public/assets/js/emojione.min.js')
   .babel('public/assets/js/sweetalert2.all.min.js', 'public/assets/js/sweetalert2.all.min.js');

mix.babel('public/assets/css/video-js.css', 'public/assets/css/video-js.css')
   .babel('public/assets/css/coreui.min.css', 'public/assets/css/coreui.min.css')
   .babel('public/assets/css/bootstrap.min.css', 'public/assets/css/bootstrap.min.css')
   .babel('public/assets/css/simple-line-icons.css', 'public/assets/css/simple-line-icons.css')
   .babel('public/assets/css/jquery.toast.min.css', 'public/assets/css/jquery.toast.min.css');
*/
