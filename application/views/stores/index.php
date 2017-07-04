
<div class="container">
    <div class="page-header">
        <h1><?= $title ?> <small>Please Select a Country and a City</small></h1>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <select id="js_country_select" class="form-control country-dropdown" aria-labelledby="dropdownMenu1">
                
            </select>
        </div>

        <div class="col-md-6">
            <select id="js_city_select" class="form-control country-dropdown" aria-labelledby="dropdownMenu1">
                
            </select>
        </div>
    </div>
</div>

<div id="js_err_msg" class="container" style="display: none;">
    <div class="alert alert-danger" role="alert"></div>
</div>

<div id="js_results_wrap" class="container" style="display: none;">
    <h3 id="js_results_heading"></h3>
    <div id="js_results_header" class="headers">
            <div>Store Location</div>
            <div>Available Titles</div>
            <div>Manage Store</div>
    </div>
    <div id="js_results_table" class="storeResults">
        
    </div>
</div>