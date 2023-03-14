$(($) => {
    getImgCnt();
    $("#ngc_bulk_setting_format").bind('change', () => {
        getImgCnt();
    });
});

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
            console.log(response.data);
            $('.img-count').show();
            if(response.data > 0){
                $('.img-count').text(response.data + " Image's Available");
            }else{
                $('.img-count').text("Images not availble!");
            }
        },
    });
}