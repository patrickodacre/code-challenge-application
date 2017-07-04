export default () => {
    return {
        createOptionsBuilder,
        removeData
    }

    function removeData(el, breakAtId = 'js_results_header') {
        while (el.hasChildNodes()) {
            if (el.lastChild.id === breakAtId) break // stop removal once we hit the header
            el.removeChild(el.lastChild);
        }
    }

    function createOptionsBuilder(el, text, key) {
        return data => {
            data.map(item => {
                const option = document.createElement('option')
                option.innerHTML = item[text]
                option.value = item[key]
                el.appendChild(option)
            })
        }
    }
}