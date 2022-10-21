
/** This is for the sub-categories and jobs drop-down.
 * This will fire off when a user chooses the parent project and subcategory in the
 * day work hour page
 **/
$(document).ready(function () {
    $('#isAdmin').change(function () {
        if (this.checked) {
            $('.role-group').show();
        }else{
            $('.role-group').hide();
        }

    });
});
