
const addPropikLink = document.createElement('a')
addPropikLink.classList.add('add_propik_list')
addPropikLink.href='#'
addPropikLink.innerText='Добавить свойство'

addPropikLink.dataset.collectionHolderClass='product_properties'

const newLinkLiPropik = document.createElement('li').append(addPropikLink)

const collectionHolderPropik = document.querySelector('ul.product_properties')
collectionHolderPropik.appendChild(addPropikLink)

const addFormToCollectionPropik = (e) => {
  const collectionHolderPropik = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

      const itemPropik = document.createElement('li');

      itemPropik.innerHTML = collectionHolderPropik
        .dataset
        .prototype
        .replace(
          /__name__/g,
          collectionHolderPropik.dataset.index
        );

      collectionHolderPropik.appendChild(itemPropik);

      collectionHolderPropik.dataset.index++;
}

addPropikLink.addEventListener("click", addFormToCollectionPropik)






