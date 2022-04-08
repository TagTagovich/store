const tags = document.querySelectorAll('ul.product_photos')
tags.forEach((tag) => {
    addTagFormDeleteLink(tag)
})

// ... the rest of the block from above

function addFormToCollection() {
    const addTagFormDeleteLink = (tagFormLi) => {
    const removeFormButton = document.createElement('button')
    removeFormButton.classList
    removeFormButton.innerText = 'Delete this tag'

    tagFormLi.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault()
        // remove the li for the tag form
        tagFormLi.remove();
    });
}

    // add a delete link to the new form
    addTagFormDeleteLink(item);
}