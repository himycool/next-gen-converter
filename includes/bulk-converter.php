<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/himycool/next-gen-converter
 * @since      0.1
 *
 * @package    NextGenConverter
 * @subpackage NextGenConverter/admin/partials
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <div id="icon-themes" class="icon32"></div>  
    <h2>Next Gen Bulk Converter</h2>
    <p>Here you can set bulk setting</p>
    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row">Bulk Formats From</th>
                <td>
                    <select id='ngc_bulk_setting_format' name='ngc_bulk_setting_format'>
                        <option value='image/png'>PNG</option>
                        <option value='image/jpeg'>JPEG</option>
                        <option value='image/svg+xml'>SVG</option>
                        <option value='image/gif'>GIF</option>
                        <option value='image/apng'>APNG</option>
                    </select>
                    <span class="img-count"></span>
                </td>
            </tr>
            <tr>
                <th scope="row">Images Per Run</th>
                <td>
                    <select id='ngc_bulk_per_run' name='ngc_bulk_per_run'>
                        <option value='10'>10</option>
                        <option value='20'>20</option>
                        <option value='30'>30</option>
                        <option value='40'>40</option>
                        <option value='50'>50</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <p class=""submit>
        <input type="button" name="bulk-optimize" id="bulk-optimize" class="button button-primary" value="Start Bulk Optimization">
    </p>
    
</div>