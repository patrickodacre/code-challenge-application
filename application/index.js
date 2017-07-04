import main from './assets/main'
import stores from './assets/stores'
import customers from './assets/customers'

$( document ).ready(function() {
  // Let's make sure we're only initializing the modules we need for any given page:
  const page_id = document.getElementById('page_id').getAttribute('data-module')

  const modules = {
    home : main,
    about : main,
    stores_index : stores,
    store_customers : customers
  }

  modules[page_id] ? modules[page_id].run() : false
});