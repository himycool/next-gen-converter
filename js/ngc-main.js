$(($) => {
    getImgCnt();

    //default state
    $("#ngc_bulk_from").val("upload");
    $('.media-files').hide();

    $("#ngc_bulk_setting_format").bind('change', () => {
        getImgCnt();
    });

    $('#bulk-files').change( () => {
        if($('#bulk-files').get(0).files.length === 0 ){
            $('#bulk-optimize').prop('disabled', true);
        }else{
            $('#bulk-optimize').prop('disabled', false);
        }
    });

    //bulk optimize call
    $('#bulk-optimize').on("click",() => {
        convertImage();
        $('#bulk-optimize').prop('disabled', true);
    });

    //image from select
    $("#ngc_bulk_from").bind('change', () => {
        getImgCnt();
        let bulkFrom = $('#ngc_bulk_from option:selected').val();
        $('#bulk-optimize').prop('disabled', false);
        if(bulkFrom == 'upload'){
            $('.bulk-files').show();
            $('.media-files').hide();
        }else{
            $('.bulk-files').hide();
            $('.media-files').show();
        }
    });


});

//get image count
function getImgCnt() {
    $('.display-msg').text("");
    $('.my-box').html("");
    let mimeVal = $('#ngc_bulk_setting_format option:selected').val();
    $.ajax({
        type: 'POST',
        url: '/wp-admin/admin-ajax.php',
        data: {
            action: 'get_image_count',
            mimeType: mimeVal
        },
        success: (response) => {
           // console.log(response.data);
            $('.img-count').show();
            if(response.data > 0){
                $('.img-count').text(response.data + " Image's available");
                $('#bulk-optimize').prop('disabled', false);
            }else{
                $('.img-count').text("Image not available!");
                $('#bulk-optimize').prop('disabled', true);
            }
        },
    });
}

//convert images
function convertImage() {
    $('.display-msg').text("");
    $('.my-box').html("");
    let mimeValFrom = $('#ngc_bulk_setting_format option:selected').val();
    let perRun = $('#ngc_bulk_per_run option:selected').val();
    let bulkFrom = $('#ngc_bulk_from option:selected').val();
   // let rmvImg = $("#rmv-exs").is(":checked") ? 1 : 0;

    let form_data = new FormData();
    let file_data = $('input[type="file"]')[0].files;
    for(let i = 0;i<file_data.length;i++){
        form_data.append("bulk-files-"+i, file_data[i]);
    }
    form_data.append("action", 'convert_images');
    form_data.append("mimeValFrom", mimeValFrom);
    form_data.append("perRun", perRun);
    form_data.append("bulkFrom", bulkFrom);

    $.ajax({
        type: 'POST',
        url: '/wp-admin/admin-ajax.php',
        data: form_data,
        contentType: false,
        processData: false,
        beforeSend: function(){
            $('.my-box').html('<div class="progress"><div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div>');
            $('.progress-bar').animate({width: "30%"}, 100);
        },
        success: (response) => {
            //console.log(response.data);
            $('.progress-bar').animate({width: "100%"}, 100);
            setTimeout(function(){
                $('.progress-bar').css({width: "100%"});
            }, 500);
            $('#bulk-optimize').prop('disabled', false);
            $('.display-msg').text("Jobs done, please check log file to more info");
        },
    });
}