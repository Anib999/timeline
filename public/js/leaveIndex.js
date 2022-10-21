(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    'use strict';

    const urlParams = new URLSearchParams(window.location.search);
    const q = urlParams.get('q');
    const showMod = urlParams.get('show');

    if(showMod){
        // $(`#userName option[value="${q}"]`).prop("selected", "selected");
        $(`#userName`).val(q).trigger('change')
        $('#leave-applicable').trigger('click')
        // $('#leave-applicable').trigger('change')
        var newURL = location.href.split("?")[0];
        window.history.pushState('object', document.title, newURL);
    }


})();