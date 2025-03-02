$(document).on('click', '.remove-group-member', function () {
    let removeItem = $(this).data('id');
    addedMembers = removeItemFromArray(addedMembers, removeItem);
    $('#selectedGroupMembers').val(JSON.stringify(addedMembers));
    if ($(this).parents('#modalAddGroupMembers').length) {
        $('#selectedGroupMembersForEdit').val(JSON.stringify(addedMembers));
    }
    checkAddedMemberListLengthWhileRemoving($(this), 1);
    $(this).parents('.list-group-item').remove();
});

$('#searchMember').keyup(debounce(function(){
    let searchVal = $(this).val();
    window.livewire.emit('searchMembers', searchVal, addedMembers);
},500));

document.getElementById("searchMember").addEventListener("search", function(event) {
    let searchVal = $(this).val();
    window.livewire.emit('searchMembers', searchVal, addedMembers);
});

document.getElementById("searchEditGroupMember").addEventListener("search", function(event) {
    let searchVal = $(this).val();
    window.livewire.emit('searchEditGroupMembers', searchVal, addedMembers);
});

$('#searchEditGroupMember').keyup(debounce(function(){
    let searchVal = $(this).val();
    window.livewire.emit('searchEditGroupMembers', searchVal, addedMembers);
},500));

$(document).on('click', '.add-group-member', function () {
    let memberUi = $(this).parents('.list-group-item');
    let memberId = $(this).data('id');
    addedMembers.push(memberId);
    $('#selectedGroupMembers').val(JSON.stringify(addedMembers));
    if ($(this).parents('#modalAddGroupMembers').length) {
        $('#selectedGroupMembersForEdit').val(JSON.stringify(addedMembers));
    }

    addMembers(memberUi);
    checkAddedMemberListLength($(this));
    checkMemberListLength($(this));
    $(this).parents('.list-group-item').remove();
});

function checkMemberListLength (ele) {
    let remainingMembers = ele.parents('.group-members-list').find('li').length;
    if (remainingMembers > 1) {
        ele.parents('.group-members-list').find('.no-member-found').removeClass('d-none').addClass('d-none');
    } else {
        ele.parents('.group-members-list').find('.no-member-found').removeClass('d-none');
    }
}

function checkAddedMemberListLength (ele, isRemainingMemberLengthOne = false) {
    let membersEle = ele.parents('.new-members').siblings('.added-members');
    let remainingMembers = membersEle.find('li').length;
    let remainingMembersLengthCondition = (isRemainingMemberLengthOne) ? 1 : 0;
    if (remainingMembers > remainingMembersLengthCondition) {
        membersEle.find('.no-member-added').removeClass('d-none').addClass('d-none');
    } else {
        membersEle.find('.no-member-added').removeClass('d-none');
    }
}

function checkAddedMemberListLengthWhileRemoving (ele, isRemainingMemberLengthOne = false) {
    let membersEle = ele.parents('.added-members');
    let remainingMembers = membersEle.find('li').length;
    let remainingMembersLengthCondition = (isRemainingMemberLengthOne) ? 1 : 0;
    if (remainingMembers > remainingMembersLengthCondition) {
        membersEle.find('.no-member-added').removeClass('d-none').addClass('d-none');
    } else {
        membersEle.find('.no-member-added').removeClass('d-none');
    }
}

function addMembers (memberUi) {
    let membersEle = memberUi.parents('.new-members').siblings('.added-members').find('.added-group-members-list');
    memberUi.find('.add-group-member').
        text('Remove').
        addClass('btn-danger').
        addClass('remove-group-member').
        removeClass('add-group-member').
        removeClass('btn-success');
    memberUi = memberUi.html();
    let memberUiList = '<li class="mb-0 list-group-item group-members-list-chat-select__list-item align-items-center d-flex justify-content-between">' +
        memberUi + '</li>';
    membersEle.prepend(memberUiList);
}

function removeItemFromArray (arrayEle, removeItem) {
    arrayEle = jQuery.grep(arrayEle, function (value) {
        return value != removeItem;
    });

    return arrayEle;
}

function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};
