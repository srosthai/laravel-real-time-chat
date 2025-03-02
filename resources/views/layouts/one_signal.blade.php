<script>
    Notification.requestPermission().then(function (result) {
        console.log(result);
    });

    let checkedFromProfile = false;
    let currentUserId = '{{ getLoggedInUserId() }}';
    let isSubscribedBefore = '{{ !is_null(Auth::user()->is_subscribed) ? true : false }}';
    let pushNotificationIsEnabled = '{{Auth::user()->is_subscribed}}';

    let playerId = '';
    let popupIsOpen = false;

    let oneSignalAppId = '{{ config('onesignal.app_id') }}';
    var OneSignal = window.OneSignal || [];
    
    OneSignal.push(function () {
        OneSignal.init({
            appId: oneSignalAppId,
// autoRegister: true,
// autoResubscribe: true,
        });

        window.OneSignal.getUserId(function (userId) {
            playerId = userId;
            console.log('Player id is : ' + userId);
        });

        $('#webNotification').on('ifChanged', function () {
            if (checkedFromProfile) {
                return;
            }

            if (pushNotificationIsEnabled <= 0) {
                console.log('Enable');
                OneSignal.push(function () {
                    OneSignal.showSlidedownPrompt({ force: true });
                });

                updateWebPushNotification(true, false);
            } else {
                if (confirm('Are you sure to disable web notification ?')) {
                    OneSignal.setSubscription(false);
                    updateWebPushNotification(false, false);
                }
            }
        });

        OneSignal.on('popoverCancelClick', function (promptClickResult) {
            console.log('popoverCancelClick');
            OneSignal.setSubscription(false);
            updateWebPushNotification(false);
        });

        OneSignal.on('popoverAllowClick', function (promptClickResult) {
            console.log('popoverAllowClick');

            OneSignal.setSubscription(true);
            updateWebPushNotification(true);
        });

// /** Show Subscribe web notification only first time */
        OneSignal.isPushNotificationsEnabled(function (isEnabled) {
            if (isEnabled) {
                return;
            }
            OneSignal.showSlidedownPrompt();
        });

    });

    setTimeout(function () {
        if (playerId && playerId.length > 0) {
            updateUserPlayerId(playerId);
        }
    }, 10000);

    function updateWebPushNotification (isSubscribed, reload = true) {
        /** Change Web notification Status */
        let data = {};
        data.is_subscribed = isSubscribed;

        $.ajax({
            url: route('update-web-notifications'),
            type: 'PUT',
            data: data,
            success: function (result) {
                if (result.success) {
                    if (reload) {
                        setTimeout(function () {
                            location.reload(); // need timeout here, because we can't direct reload while one signal is processing its data
                        }, 3000);
                    }
                    $('#editProfileModal').modal('hide');
                }
            },
            error: function (result) {
                displayToastr('Error', 'error', result.responseJSON.message);
            },
        });
    }

    function updateUserPlayerId (userId) {
        /** Change Web notification Status */
        if (!userId) {
            return;
        }

        $.ajax({
            url: route('update-player-id'),
            type: 'PUT',
            data: { 'player_id': userId },
            success: function (result) {
            },
            error: function (result) {
                displayToastr('Error', 'error', result.responseJSON.message);
            },
        });
    }

</script>
