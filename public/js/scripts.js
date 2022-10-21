/** This is for the sub-categories and jobs drop-down.
 * This will fire off when a user chooses the parent project and subcategory in the
 * day work hour page
 **/
$(document).ready(function ($) {


    $('#project').on('change', function () {
        var subcat = $('#sub_category');
        var project_id = $(this).val()
        $('#project_id').val( project_id );

        if(isNaN(project_id) || project_id  == '')
            return;

        subcat.before('<i class="fa fa-spin fa-spinner addGroup-loader"></i>');
        subcat.css('pointer-events','none');

        $.get($(this).data('url'), {
                option: project_id
            },
            function (res) {
                subcat.empty();
                subcat.append("<option value=''>select subCategory</option>");
                for(let i = 0; i < res.length; i++){
                    subcat.append("<option value='" + res[i].id + "'>" + res[i].name + "</option>");
                }
                
                subcat.siblings('.addGroup-loader').remove();
                subcat.css('pointer-events','all');
            }/*,
            function (res) {
                console.log(res);
                subcat.siblings('.addGroup-loader').remove();
            }*/
        );
    });
    $('#sub_category').on('change', function () {

        $('#sub_category_id').val($(this).val());
        var subcat_id = $(this).val();
        var workDetail = $('#workDetail');
        var project_id = $('#project').val();
        workDetail.empty();

        workDetail.before('<i class="fa fa-spin fa-spinner addGroup-loader"></i>');
        workDetail.css('pointer-events','none');
        
        if (!isNaN(subcat_id) && subcat_id != '' && !isNaN(project_id)) {
            $.post($('#workDetail').attr('route'), {
                    'subCat': subcat_id,
                    'project': project_id,
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                function (res) { //success
                    workDetail.append("<option value=''> Select Work Detail</option>");
                    for(let i = 0; i < res.length; i++){
                        workDetail.append("<option value='" + res[i].id + "'>" + res[i].name + "</option>");
                    }
                    workDetail.siblings('.addGroup-loader').remove();
                    workDetail.css('pointer-events','all');
                }/*,
                function (res) {
                    console.log(res);
                    workDetail.siblings('.addGroup-loader').remove();
                }*/
            );
        }
    });
});