import axios from 'axios'
import helpers from './helpers'

export default {
    run
}

const util = helpers()

// arrays and objects, etc.
let selected_store_id
let customersById // handy for fast customer lookups
let selectedCustomer // handy for fast customer lookups

// Elements:
let errorMessage
let successMessage
let storeDetailsWrap
let storeDetails
let resultsTableWrap 
let resultsTable 
let resultsTableHeading 
let addCustomerBtn
let addCustomerFirstName
let addCustomerLastName
let editCustomerFirstName
let editCustomerLastName
let editCustomerStatus
let addCustomerSaveBtn
let deleteCustomerBtn
let editCustomerModalContent
let saveCustomerChangesBtn

function run() {
    console.log('Customer module loaded');

    selected_store_id        = document.getElementById('page_id').getAttribute('data-store-id')
    storeDetailsWrap         = document.getElementById('js_store_details_wrap')
    storeDetails             = document.getElementById('js_store_details')
    errorMessage             = document.getElementById('js_err_msg')
    successMessage           = document.getElementById('js_success_msg')
    resultsTableWrap         = document.getElementById('js_results_wrap')
    resultsTableHeading      = document.getElementById('js_results_heading')
    resultsTable             = document.getElementById('js_results_table')
    addCustomerBtn           = document.getElementById('js_add_customer_btn')

    addCustomerFirstName     = document.getElementById('js_add_customer_first_name')
    addCustomerLastName      = document.getElementById('js_add_customer_last_name')
    editCustomerFirstName    = document.getElementById('js_edit_customer_first_name')
    editCustomerLastName     = document.getElementById('js_edit_customer_last_name')
    editCustomerStatus       = document.getElementById('js_edit_customer_status')
    
    addCustomerSaveBtn       = document.getElementById('js_add_customer_save_btn')
    deleteCustomerBtn        = document.getElementById('js_delete_customer_btn')
    editCustomerModalContent = document.getElementById('edit_customer_modal_content')
    saveCustomerChangesBtn   = document.getElementById('js_save_customer_changes_btn')

    // wire up event listeners:
    addCustomerBtn.addEventListener('click', afterAddCustomerClicked)

    // create customer
    addCustomerFirstName.addEventListener('input', afterInputFirstName)
    addCustomerFirstName.addEventListener('onkeydown', afterInputFirstName)
    addCustomerLastName.addEventListener('input', afterInputLastName)
    addCustomerLastName.addEventListener('onkeydown', afterInputLastName)

    // edit customer
    editCustomerFirstName.addEventListener('input', afterInputFirstName_edit)
    editCustomerFirstName.addEventListener('onkeydown', afterInputFirstName_edit)
    editCustomerLastName.addEventListener('input', afterInputLastName_edit)
    editCustomerLastName.addEventListener('onkeydown', afterInputLastName_edit)
    editCustomerStatus.addEventListener('change', afterActiveStatus_edit)

    // buttons - confirmations
    addCustomerSaveBtn.addEventListener('click', createCustomer)
    deleteCustomerBtn.addEventListener('click', afterDeleteCustomer)
    saveCustomerChangesBtn.addEventListener('click', editCustomer)

    // init page details:
    const {
        selectedCountry,
        selectedCity
    } = localStorage

    // update store details:
    storeDetails.innerHTML = `
        <h2>Store ID: ${selected_store_id}</h2>
        <div class="storeDetails">
            <div>${localStorage[selected_store_id]}, ${selectedCity}, ${selectedCountry}</div>
        </div>
    `

    hideError()
    hideTable()

    getCustomers()
}

function getCustomers() {

    // remove anything that may be there, just in case we're refreshing the list.
    util.removeData(resultsTable) 

    $.get(`http://challenge.dev/api/customers/${selected_store_id}`)
    .then(resp => {
        if (resp.status === 200) {
            
            // save a quick customer data lookup to use elsewhere
            customersById = resp.data.reduce((carry, customer) => {
                carry[customer.customer_id] = customer
                return carry
            }, {})

            buildResultsTable(resp.data)
            showTable()

            // after the table is visible, we need to attach our event listeners:
            const rentalCells = document.querySelectorAll('.rentalCountCell')
            const editIcons   = document.querySelectorAll('.js_edit_customer_icn')
            const deleteIcons = document.querySelectorAll('.js_delete_customer_icn')
            rentalCells.forEach(cell => cell.addEventListener('click', afterRentalCellClicked))
            editIcons.forEach(cell => cell.addEventListener('click', afterEditIconClicked))
            deleteIcons.forEach(cell => cell.addEventListener('click', afterDeleteIconClicked))
        }
    })
}

function buildResultsTable(data) {

    data.forEach(customer => {
        const {
            first_name,
            last_name,
            active,
            rentals,
            rental_count,
            customer_id
        } = customer

        const statusClass = active === '1' 
                                ? `glyphicon glyphicon-ok-sign green` 
                                : `glyphicon glyphicon-remove-sign red`

        const tableRow = document.createElement('div')

        tableRow.id = customer_id
        tableRow.innerHTML = `
            <div>${last_name}</div>
            <div>${first_name}</div>
            <div><div class="${statusClass}"></div></div>
            <div data-customer="${customer_id}" data-toggle="modal" data-target="#myModal" class="rentalCountCell">${rental_count}</div>
            <div class="actionCell">
                <div data-customer="${customer_id}" 
                    data-toggle="modal" 
                    data-target="#editCustomerModal" 
                    class="glyphicon glyphicon-edit js_edit_customer_icn">
                </div>
                <div data-customer="${customer_id}" 
                    data-toggle="modal" 
                    data-target="#deleteCustomerModal" 
                    class="glyphicon glyphicon-trash js_delete_customer_icn">
                </div>
            </div>
        `
        
        // tableRow.addEventListener('click', customerSelected)
        // Add our rows to the table
        resultsTable.appendChild(tableRow)
    })

    const msg = data.length === 1
                    ? `${data.length} Customer Found`
                    : `${data.length} Customers Found`

    setTableHeading(msg)
}

/* ==================================
Store Details:
==================================== */

function showStoreDetails() {
    storeDetailsWrap.removeAttribute('style')
}

function hideStoreDetails() {
    storeDetailsWrap.setAttribute('style', 'display: none;')
}

/* ==================================
Table:
==================================== */

function showTable() {
    resultsTableWrap.removeAttribute('style')
}

function hideTable() {
    resultsTableWrap.setAttribute('style', 'display: none;')
}

function setTableHeading(msg) {
    resultsTableHeading.innerHTML = msg
}

/* ==================================
Alerts:
==================================== */
function showError(msg) {
    errorMessage.firstElementChild.innerHTML = msg
    errorMessage.removeAttribute('style')
}

function hideError() {
    errorMessage.setAttribute('style', 'display: none;')
}

function showSuccess(msg) {
    successMessage.firstElementChild.innerHTML = msg
    successMessage.removeAttribute('style')
}

function hideSuccess() {
    // setTimeout(() => {
    //     successMessage.setAttribute('style', 'display: none;')
    // }, 100000)
}

/* ==================================
Event handlers:
==================================== */

/**
 * After the rental count cell is clicked
 * we need to display a list of the actual
 * movies being rented by that customer.
 */
function afterRentalCellClicked(evt) {
    // look up the customer details that we saved in getCustomers()
    const customerID       = evt.currentTarget.getAttribute('data-customer')
    selectedCustomer       = customersById[customerID]
    const rentalListToShow = selectedCustomer.rentals
    // grab the modal so we can place our content
    const modalContent     = document.getElementById('rental_list_modal_content')
    
    // clear any existing data
    util.removeData(modalContent)

    // build our list in the modal
    if (rentalListToShow.length > 0) {
        rentalListToShow.forEach(rental => {
            const listItem = document.createElement('div')
            listItem.innerHTML = `<div class="title">${rental.title}</div><div class="description">${rental.description}</div>`
            modalContent.appendChild(listItem)
        })
    } else {
        modalContent.innerHTML = `This customer has not rented any movies.`
    }
}

function afterEditIconClicked(evt) {
    // look up the customer details that we saved in getCustomers()
    const customerID = evt.currentTarget.getAttribute('data-customer')
    selectedCustomer = customersById[customerID]

    // we want our edit form to show our current values
    editCustomerFirstName.value = selectedCustomer.first_name
    editCustomerLastName.value  = selectedCustomer.last_name
    editCustomerStatus.value    = selectedCustomer.active
}

function afterDeleteIconClicked(evt) {
    // look up the customer details that we saved in getCustomers()
    const customerID = evt.currentTarget.getAttribute('data-customer')
    selectedCustomer = customersById[customerID]
}

function afterAddCustomerClicked(evt) {
    // debugger
}

/* ==================================
Event Handlers to make sure we
have the required fields for adding / editing customers.
==================================== */
function afterInputFirstName(evt) {
    if (canSaveCustomer()) {
        addCustomerSaveBtn.removeAttribute('disabled')
    } else {
        addCustomerSaveBtn.setAttribute('disabled', 'true')
    }
}
function afterInputLastName(evt) {
    if (canSaveCustomer()) {
        addCustomerSaveBtn.removeAttribute('disabled')
    } else {
        addCustomerSaveBtn.setAttribute('disabled', 'true')
    }
}
function afterInputFirstName_edit(evt) {
    if (canEditCustomer()) {
        saveCustomerChangesBtn.removeAttribute('disabled')
    } else {
        saveCustomerChangesBtn.setAttribute('disabled', 'true')
    }
}
function afterInputLastName_edit(evt) {
    if (canEditCustomer()) {
        saveCustomerChangesBtn.removeAttribute('disabled')
    } else {
        saveCustomerChangesBtn.setAttribute('disabled', 'true')
    }
}
function afterActiveStatus_edit(evt) {
    if (canEditCustomer()) {
        saveCustomerChangesBtn.removeAttribute('disabled')
    } else {
        saveCustomerChangesBtn.setAttribute('disabled', 'true')
    }
}

/* ==================================
Create, Delete, Edit customer:
==================================== */
function createCustomer(evt) {
    const first_name = document.getElementById('js_add_customer_first_name').value
    const last_name  = document.getElementById('js_add_customer_last_name').value

    $.post("http://challenge.dev/api/customers", {first_name, last_name, store_id: selected_store_id})
    .then(resp => {
        if (resp.status === 201) {
            // success, so let's getCustomers() again
            showSuccess(resp.message)
            getCustomers()
            hideSuccess()
        } else {
            showError(resp.message)
        }
    })
}

function afterDeleteCustomer(evt) {
    $.ajax({
        url: `http://challenge.dev/api/customers/${selectedCustomer.customer_id}`,
        type: 'DELETE'
    })
    .then(resp => {
        if (resp.status === 200) {
            // success, so let's getCustomers() again
            showSuccess(resp.message)
            getCustomers()
            hideSuccess()
        } else {
            showError(resp.message)
        }
    })
}

function editCustomer(evt) {
    const editFirstNameValue = document.getElementById('js_edit_customer_first_name').value
    const editLastNameValue  = document.getElementById('js_edit_customer_last_name').value
    const editActiveValue    = document.getElementById('js_edit_customer_status').value

    $.ajax({
        url: `http://challenge.dev/api/customers/${selectedCustomer.customer_id}`,
        type: 'PUT',
        data: {
            first_name: editFirstNameValue,
            last_name: editLastNameValue,
            active: editActiveValue
        }
    })
    .then(resp => {
        if (resp.status === 200) {
            // success, so let's getCustomers() again
            // if we weren't using alpha ordering, I would just .push() the new record to the list perhaps to try and save us another trip to the server
            showSuccess(resp.message)
            getCustomers()
            hideSuccess()
        } else {
            showError(resp.message)
        }
    })
}

/* ==================================
Basic Validation Checks:
==================================== */

function canSaveCustomer() {
    const addCustomerFirstName = document.getElementById('js_add_customer_first_name')
    const addCustomerLastName  = document.getElementById('js_add_customer_last_name')

    return addCustomerFirstName.value.length > 0 && addCustomerLastName.value.length > 0
}

function canEditCustomer() {
    const editCustomerFirstName = document.getElementById('js_edit_customer_first_name')
    const editCustomerLastName  = document.getElementById('js_edit_customer_last_name')

    return editCustomerFirstName.value.length > 0 && editCustomerLastName.value.length > 0
}