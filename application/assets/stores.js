import axios from 'axios'
import helpers from './helpers'

export default {
    run
}

const util = helpers()

// important stuff
let cityHistory         = {} // keep a history of our retrieved cities.
let locationsHistory     = {} // keep a history of our retrieved locations.
let selectedLocation    = {}
let addCountryOptions // function to update options
let addCityOptions // function to update options
let countriesById = {}
let citiesById    = {}

// Elements
let countrySelect 
let citySelect 
let errorMessage 
let resultsTableWrap 
let resultsTable 
let resultsTableHeading 

/**
 * Kick off the Stores module:
 */
function run() {
    console.log('Stores module initiated.');

    // Elements:
    countrySelect       = document.getElementById('js_country_select')
    citySelect          = document.getElementById('js_city_select')
    errorMessage        = document.getElementById('js_err_msg')
    resultsTableWrap    = document.getElementById('js_results_wrap')
    resultsTableHeading = document.getElementById('js_results_heading')
    resultsTable        = document.getElementById('js_results_table')

    // helpers used to populate the 'select' elements with options
    addCountryOptions = util.createOptionsBuilder(countrySelect, 'country', 'country_id')
    addCityOptions    = util.createOptionsBuilder(citySelect, 'city', 'city_id')

    // Event Handlers:
    countrySelect.addEventListener('change', handleCountryChange)
    citySelect.addEventListener('change', handleCityChange)

    getCountries()
}

function getCountries() {

    $.get("http://challenge.dev/api/countries")
    .then(resp => {
        if (resp.status === 200) {
            // we require this lookup to properly set selected country
            countriesById = resp.data.reduce((carry, data) => {
                carry[data.country_id] = data.country
                return carry
            }, {})

            addCountryOptions(resp.data)
            /* normally a getCities() fires on a select change event,
            so we select the first option as a convenience for the user */
            getCities(resp.data[0].country_id)
            countrySelect.focus() // save the user a click
        } else {
            showError(`Could not retrieve any countries. Please contact support.`)
        }
    })
}

function getCities(country_id) {
    // save selected country for map link
    selectedLocation.country = countriesById[country_id]

    // start fresh
    util.removeData(citySelect)
    hideError()

    if (cityHistory[country_id]) {
        addCityOptions(cityHistory[country_id])
        getStoreLocations(cityHistory[country_id][0].city_id)
    } else {
        $.get(`http://challenge.dev/api/cities/${country_id}`)
        .then( resp => {
            if (resp.status === 200) {
                /* we require this lookup to properly set selected city
                we can continually overwrite it as it isn't an expensive operation */
                citiesById = resp.data.reduce((carry, data) => {
                    carry[data.city_id] = data.city
                    return carry
                }, {})

                // store this for the next time and save another trip to the server:
                cityHistory[country_id] = resp.data

                addCityOptions(resp.data)
                /* normally a getStoreLocations() fires on a select change event,
                so we select the first option as a convenience for the user */
                getStoreLocations(resp.data[0].city_id)
            } else {
                addCityOptions([{city: 'No cities available', city_id: false}])
            }
        })
    }
}

function getStoreLocations(city_id) {

    // save selected city for map link
    selectedLocation.city = citiesById[city_id]

    // start fresh
    util.removeData(resultsTable)
    hideError()

    if (locationsHistory[city_id]) {
        if (locationsHistory[city_id].length > 0) {
            buildResultsTable(locationsHistory[city_id])
            showTable()
        } else {
            showError(`There aren't any stores in the selected city.`)
            hideTable()
        }
        
    } else {
        $.get(`http://challenge.dev/api/addresses/${city_id}`)
        .then(resp => {

            if (resp.status === 200) {
                // store this for the next time and save another trip to the server:
                locationsHistory[city_id] = resp.data

                // update local storage to persist our store data for the manage page
                resp.data.forEach(location => {
                    localStorage[location.store_id] = location.address
                    localStorage.selectedCountry    = selectedLocation.country
                    localStorage.selectedCity       = selectedLocation.city
                })

                buildResultsTable(resp.data)
                showTable()
            } else {
                showError(resp.message)
                hideTable()
            }
        })
    }
}

/* ==================================
Table:
==================================== */
function buildResultsTable(data) {

    data.forEach(location => {
        const {
            store_id,
            movie_count,
            address
        } = location

        const tableRow = document.createElement('div')
        
        tableRow.innerHTML = `
            <div>${address}</div>
            <div>${movie_count} titles</div>
            <div>
                <a href="/stores/customers/${store_id}">
                    <button 
                        type="button" 
                        id="${store_id}" 
                        class="btn btn-primary"
                        >
                        Manage
                    </button>
                </a>
            </div>
        `
        // Add our rows to the table
        resultsTable.appendChild(tableRow)
    })

    const msg = data.length === 1
                    ? `${data.length} Store Found`
                    : `${data.length} Stores Found`

    setTableHeading(msg)
}

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
Errors:
==================================== */
function showError(msg) {
    errorMessage.firstElementChild.innerHTML = msg
    errorMessage.removeAttribute('style')
}

function hideError() {
    errorMessage.setAttribute('style', 'display: none;')
}

/* ==================================
Event handlers:
==================================== */

/**
 * @listens countrySelect~event:change
 * @param {object} evt
 */
function handleCountryChange(evt) {
    getCities(evt.currentTarget.value)
}

/**
 * @listens citySelect~event:change
 * @param {object} evt
 */
function handleCityChange(evt) {
    getStoreLocations(evt.currentTarget.value)
}