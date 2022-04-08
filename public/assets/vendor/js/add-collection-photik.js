
const addTagLink = document.createElement('a')
addTagLink.classList.add('add_tag_list')
addTagLink.href='#'
addTagLink.innerText='Add a tag'

addTagLink.dataset.collectionHolderClass='product_properties'

const newLinkLi = document.createElement('li').append(addTagLink)

const collectionHolder = document.querySelector('ul.product_properties')
collectionHolder.appendChild(addTagLink)

const addFormToCollection = (e) => {
  const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

      const item = document.createElement('li');

      item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
          /__name__/g,
          collectionHolder.dataset.index
        );

      collectionHolder.appendChild(item);

      collectionHolder.dataset.index++;
}

addTagLink.addEventListener("click", addFormToCollection)






