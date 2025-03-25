
window.addEventListener('load', function() { // when page is loaded

    // get all calendar items and add an event listener to each
    // -> will allow the details of the calendar item to be expanded/collapsed
    document.querySelectorAll('.calendar_button').forEach(item => {
        item.addEventListener('click', (event) => {
            const eventId = event.currentTarget.getAttribute('data-event-id');
            toggleCalDetails(event, eventId);
        });
    });

    // get all summary elements and add an event listener to each
    // -> clicking on the summary element will trigger the click event on the corresponding calendar item
    document.querySelectorAll('.calendar_item_desc_ctrl').forEach(item => {
        item.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            const eventId = event.currentTarget.getAttribute('data-event-id');
            // click the corresponding calendar item
            document.getElementById('calendar_button_' + eventId).click();
        });
    });

    // get all share buttons and add an event listener to each
    document.querySelectorAll('.event_share_btn').forEach(item => {
        if (navigator.share) {
            item.addEventListener('click', (event) => {
                event.stopPropagation();

                const link = event.currentTarget.getAttribute('data-link');
                const name = event.currentTarget.getAttribute('data-name');
                const time = event.currentTarget.getAttribute('data-time');
                const loc = event.currentTarget.getAttribute('data-loc');

                navigator.share({
                    title: name + " | LGBTQIA+ & friends",
                    text: name + "\n\n" + time + "\n" + loc + "\n ",
                    url: link
                }).then()
            });
        } else {
            // TODO: add fallback where share() is not supported (e.g. Firefox Desktop)
            item.style.display = 'none';
        }
    });

    // add an event listener to the default calendar copy button
    let copyBtn = document.getElementById('default_calendar_copy_button');
    copyBtn.addEventListener('click', () => {
        let descText = copyBtn.getAttribute('data-desc');
        let copyText = copyBtn.getAttribute('data-link');
        navigator.clipboard.writeText(copyText).then(function() {
            // show a success message
            alert(descText + '\n' + copyText);
        });
    });
});

function toggleCalDetails(event, eventId) {
    // this function toggles the visibility of the corresponding calendar details
    const event_button = event.currentTarget;
    const details = document.getElementById('event_det_' + eventId);

    const isExpanded = details.hasAttribute('open');

    if (isExpanded) {
        // if the details are already open, close them
        details.removeAttribute('open');

        // add calendar_item_past class if the event is in the past
        if (event_button.getAttribute('data-past-class') === 'true') {
            event_button.classList.add('calendar_item_past');
            event_button.removeAttribute('data-past-class');
        }
    } else {
        // if the details are closed, open them
        details.setAttribute('open', 'open');

        // remove calendar_item_past class & store it in temp attribute
        if (event_button.classList.contains('calendar_item_past')) {
            event_button.setAttribute('data-past-class', 'true');
            event_button.classList.remove('calendar_item_past');
        }
    }
}
