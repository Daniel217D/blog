export default () => {
    const btnAddSetting = document.getElementById('setting-add');
    const selectSettingType = document.getElementById('setting-type');
    const tableSettings = document.getElementById('settings-table');

    if (!btnAddSetting || !selectSettingType || !tableSettings) {
        return;
    }

    document.addEventListener('click', function (e) {
        if(e.target.closest('.js-remove-button')) {
            e.preventDefault();
            e.target.closest('.js-setting').remove();
        }
    } )

    btnAddSetting.addEventListener('click', function (e) {
        e.preventDefault();

        const id = `new_${Date.now()}`

        tableSettings.appendChild(template('setting', {
            id,
            field: template(`setting-${selectSettingType.value}`, {
                id
            })
        }));
    } )
}

/**
 *
 * @param name
 * @param vars
 * @return {Element|HTMLCollection|null}
 */
const template = (name, vars) => {
    return fromHTML(Object.entries(vars).reduce(
        (html, [key, value]) => html.replaceAll(
            `%%${key}%%`,
        value instanceof Element ? value.outerHTML : value
        ),
        document.getElementById(`tmpl-${name}`).innerHTML.trim()
    ))
}

/**
 * @param {String} html representing a single element.
 * @param {Boolean} trim representing whether to trim input whitespace, defaults to true.
 * @return {Element | HTMLCollection | null}
 */
function fromHTML(html, trim = true) {
    // Process the HTML string.
    html = trim ? html.trim() : html;
    if (!html) return null;

    // Then set up a new template element.
    const template = document.createElement('template');
    template.innerHTML = html;
    const result = template.content.children;

    // Then return either an HTMLElement or HTMLCollection,
    // based on whether the input HTML had one or more roots.
    if (result.length === 1) return result[0];
    return result;
}
