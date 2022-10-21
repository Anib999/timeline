(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    'use strict';

    $('#name').on('blur', function(e){
        let checkVal = this.value;
        if(checkVal != '' && checkVal != undefined){
            let spVal = checkVal.split(' ')
            let newUserName = ''
            for (let index = 0; index < spVal.length; index++) {
                if(index==0) {
                    newUserName += spVal[index]
                }else{
                    newUserName += spVal[index].substring(0,1)
                }
            }
            loadData(newUserName.toLowerCase())
        }
    })

    async function loadData (checkVal) {
        $('#username').val('')
        const res = await checkUserName(checkVal)
        $('#username').val(res)
    }

    function checkUserName(checkVal) {
        return new Promise(resolve => {
            $.ajax({
                url: 'ajaxCheckUserNameAndReturn',
                data: {uname: checkVal},
                dataType: 'json',
                method: 'get'
            }).done(res => {
                resolve(res)
            }).fail(res => resolve(false))
        })
    }

})();