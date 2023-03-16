$(($) => {
    //image count ajax
    getImgCnt();
    $("#ngc_bulk_setting_format").bind('change', () => {
        getImgCnt();
    });

    //bulk optimize call
    $('#bulk-optimize').on("click",() => {
       // alert('done');
        convertImage();
        $('#bulk-optimize').prop('disabled', true);
    });


});

//get image count
function getImgCnt() {
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
                $('.img-count').text(response.data + " Image's Available");
                $('#bulk-optimize').prop('disabled', false);
            }else{
                $('.img-count').text("Image not availble!");
                $('#bulk-optimize').prop('disabled', true);
            }
        },
    });
}

//convert images
function convertImage() {
    let mimeValFrom = $('#ngc_bulk_setting_format option:selected').val();
    let perRun = $('#ngc_bulk_per_run option:selected').val();
   // let rmvImg = $("#rmv-exs").is(":checked") ? 1 : 0;

    $.ajax({
        type: 'POST',
        url: '/wp-admin/admin-ajax.php',
        data: {
            action: 'convert_images',
            mimeValFrom: mimeValFrom,
            perRun: perRun
            //rmvImg: rmvImg
        },
        success: (response) => {
            //console.log(response.data);
            $('#bulk-optimize').prop('disabled', false);
            $('.display-msg').text("Jobs done, please check log file to more info");
        },
    });
}