$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // Update and Store Professional
    $(document).on('submit', '.industry_vertical_submit', function(event){        
    // $(document).on('submit', '.industry_vertical_submit', function(event){
        event.preventDefault();
        var form = $(this).closest("form")[0];
        var action = $(this).attr("action");
        // var method = $(this).attr("method");
        let formData = new FormData(form); 
        $.ajax({
           
            type: "POST",
            url: action,
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            context: this,
            dataType: 'json',
            success: function (data) {
                // alert("fsafsa");
                console.log(data);
                var update_id = $('#update_id').val();
                if (data.success) {
                    swal({text: update_id ? "Industry Verticals Updated Successfully!" :"Industry Verticals Added Successfully!", type: 
                        "success"})
                        .then(function(){ 
                            location.reload();
                        }
                    );
                    // $(".varient_submit").trigger("reset");
                    // $(".alert-success").prop("hidden", false);
                    // $(".alert-success").html(data.message);
                    // $('#varient_id').val(data.records.id);
                    // $('.varient_option').html(data.records.html);
                }
                else{
                    printErrorMsg(data.message.error);
                    // $('.alert-danger').removeClass("d-none");
                    // $('.alert-danger').append(data.message.error);
                }
            },
            error: function (e) { }
        });
    });

    // Delete varient options
    $(document).on('click', '#delete_role_items', function(event){
        event.preventDefault();
        var id = $(this).attr("id-attr");
        var url = $(this).attr("action-attr");
        alert(url);
        swal({
            title: "Are you sure to Delete!",
            icon: "warning",
            buttons: [
                'cancel',
                'yes'
            ],
            dangerMode: true,
        }).then(function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: 'post',
                    url: url,
                    data: {id:id},
                    processData: false,
                    contentType: false,
                    cache: false,
                    context: this,
                    dataType: 'json',
                    success: function (data) {
                        swal("Prof role type item deleted Successfully", {
                            icon: "success",
                        });
                        $("#"+id+"").remove();
                    },
                    error: function (e) { }
                });
            }
        });
       
    });

    var count = 1;
    var industry_vertical = 1;
    var role_item_count = 1;
    var industry_item_count = 1;
    var update_id = $('#update_id').val();
    
    if (!update_id) {
        // dynamic_field_industy_vertical(industry_vertical);
        // dynamic_field(count);
    }else{
        count = 2;
    }

    function dynamic_field_industy_vertical(industry_number)
    {
        html="";

        html='<div class="mt-2 delete_industry">';
        html += '<div class="row">';

        html += '<div class="col-md-6 col-12">';
        html += '<div class="form-group">';
        html += '<label for="title">Title</label>';
        html += '<div class="input-group mb-1">';
        html += '<input class="form-control @error("icon") is-invalid @enderror" type="text" placeholder = "Industry vertical" name="industry_title['+industry_number+']" required>';

        html += '<span class="input-group-text add_industry_vertical_item"  role-type-count="'+industry_number+'" id="basic-addon2">';
        html += '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>';
        html += '</span>';

        if(industry_number > 1)
        {
            html += '<span class="input-group-text remove delete_industry_btn">';
            html += '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
            html += '</span>';
        }  
        html += '</div>';
        html += '</div>';
        html += '</div>';

        html += '</div></div>';
    
        $('.add_industry_div').append(html);  
                
        industry_vertical  = industry_number;
    }


    $(document).on('click', '.add_industry_vertical_item', function(){
        industry_item_count++;
        
        var type_count = $(this).attr('role-type-count');

        html="";

        html = '<div class="col-md-12 col-12"  id="delete_industry_vertical_items">';
        html += '<div class="row">';

        html += '<div class="col-md-5 col-12 ml-2">';
        html += '<div class="input-group mb-1">';
        html += '<input class="form-control @error("industry_item") is-invalid @enderror" type="text" name="industry_item['+type_count+']['+industry_item_count+']" placeholder="Industry vertical items" required>';
        html += '<span class="input-group-text remove"  id="delete_industry_vertical_items_btn">';
        html += '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';

        html += '</span>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        $(this).closest('.col-md-6').after(html); // Find the closest row element
    });

    $(document).on('click', '.add_industry', function(){
        count++;
        dynamic_field_industy_vertical(count);
    });

    $(document).on('click', '#delete_industry_vertical_items_btn', function(){
        count--;
        $(this).parents("#delete_industry_vertical_items").remove();
    });
    
    $(document).on('click', '.delete_industry_btn', function(){
        // count--;
        $(this).parents(".delete_industry").remove();
    });

    function printErrorMsg (msg) {
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display','block');
        $.each( msg, function( key, value ) {
            $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
        });
    }

    // Varient Filters
    var path = $(location).attr("pathname");
    if (path === '/varient'){
    }

    $(document).on('click', '.var_links .pagination a', function(event) {
        event.preventDefault();

        var page = $(this).attr('href').split('page=')[1];
        $('#varientFltrPage').val(page);
        getVarientAjaxData();
    });

    $(document).on('change', '.varient_fltr', function(event) {
        $('#varientFltrPage').val(1);
        getVarientAjaxData();
    });

    function getVarientAjaxData() {
        $('.loaderOverlay').fadeIn();
    
        jQuery.ajax({
            url: "/get_varient",
            data: $("#varientFilterform").serializeArray(),
            method: 'POST',
            dataType: 'html',
            success: function(response) {
                $('.loaderOverlay').fadeOut();
                $("#all_varients").html(response);
    
                if (feather) {
                    feather.replace({
                        width: 14,
                        height: 14
                    });
                }
            }
        });
    }

});

