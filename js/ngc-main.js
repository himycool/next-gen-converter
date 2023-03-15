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
            }else{
                $('.img-count').text("Image not availble!");
            }
        },
    });
}

//convert images
function convertImage() {
    let mimeValFrom = $('#ngc_bulk_setting_format option:selected').val();
    let perRun = $('#ngc_bulk_per_run option:selected').val();
    let rmvImg = $("#rmv-exs").attr("checked") ? 1 : 0;

    $.ajax({
        type: 'POST',
        url: '/wp-admin/admin-ajax.php',
        data: {
            action: 'convert_images',
            mimeValFrom: mimeValFrom,
            perRun: perRun,
            rmvImg: rmvImg
        },
        success: (response) => {
            console.log(response.data);
            // $('.img-count').show();
            // if(response.data > 0){
            //     $('.img-count').text(response.data + " Image's Available");
            // }else{
            //     $('.img-count').text("Image not availble!");
            // }
        },
    });
}