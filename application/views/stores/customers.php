
<div class="container">
    <h1><?= $title ?></h1>
</div>

<div class="manageStore container">
    <div id="js_store_details">
    </div>
    <div>
        <button id="js_add_customer_btn" type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCustomerModal">New Customer</button>
    </div>
</div>

<div id="js_err_msg" class="container" style="display: none;">
    <div class="alert alert-danger" role="alert"></div>
</div>

<div id="js_success_msg" class="container" style="display: none;">
    <div class="alert alert-success" role="alert"></div>
</div>

<div id="js_results_wrap" class="container">
    <h3 id="js_results_heading"></h3>
    <div class="headers">
        <div>Last Name</div>
        <div>First Name</div>
        <div>Active?</div>
        <div>Movies Rented:</div>
        <div>Actions</div>
    </div>
    <div id="js_results_table" class="storeResults customerList isList">

    </div>
</div>

<!-- Rental Listings Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Titles Rented</h4>
      </div>
      <div id="rental_list_modal_content" class="modal-body rentalList isList">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
      </div>
    </div>
  </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Customer</h4>
      </div>
      <div id="add_customer_modal_content" class="modal-body">
            <p>To create a new customer, first and last names are required.</p>
            <div class="row">
                <div class="col-lg-6">
                    <div class="input-group">
                        <input id="js_add_customer_first_name" type="text" class="form-control" placeholder="First Name">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="input-group">
                        <input id="js_add_customer_last_name" type="text" class="form-control" placeholder="Last Name">
                    </div>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button id="js_add_customer_save_btn" disabled type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
      </div>
    </div>
  </div>
</div>
<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Customer</h4>
      </div>
      <div id="edit_customer_modal_content" class="modal-body">
          <div class="row">
            <div class="col-lg-4">
                <div class="input-group">
                    <input id="js_edit_customer_first_name" type="text" class="form-control" placeholder="First Name">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="input-group">
                    <input id="js_edit_customer_last_name" type="text" class="form-control" placeholder="Last Name">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="input-group">
                    <select id="js_edit_customer_status" name="select"> <!--Supplement an id here instead of using 'name'-->
                      <option value="1">Active</option> 
                      <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button id="js_save_customer_changes_btn" disabled type="button" class="btn btn-primary" data-dismiss="modal">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Customer Modal -->
<div class="modal fade" id="deleteCustomerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Delete Customer</h4>
      </div>
      <div id="rental_list_modal_content" class="modal-body">
        <p>Are you sure you want to delete this customer?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button id="js_delete_customer_btn" type="button" class="btn btn-primary" data-dismiss="modal">Confirm</button>
      </div>
    </div>
  </div>
</div>