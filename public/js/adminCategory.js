function renderModalDeleteCat(id, ref)
{
    const modal = document.getElementById('deleteModal');
    const form = modal.querySelector('form');
    const h3 = form.querySelector('h3');
    const inputId = document.getElementById('deleteModalInput');

    h3.textContent = `Etes-vous sûr de vouloir supprimer cette catégorie - #${ref} ?`;
    inputId.value = id;
}

function renderModalRestoreCat(id, ref)
{
    const modal = document.getElementById('restoreModal');
    const form = modal.querySelector('form');
    const h3 = form.querySelector('h3');
    const inputId = document.getElementById('restoreModalInput');

    h3.textContent = `Restaurer la catégorie - #${ref} ?`;
    inputId.value = id;
}

function renderModalHideCat(id, ref) {
    const modal = document.getElementById('hideModal');
    const form = modal.querySelector('form');
    const h3 = form.querySelector('h3');
    const inputId = document.getElementById('hideModalInput');

    h3.textContent = `Masquer  - #${ref} ?`;
    inputId.value = id;
}

function renderModalShowCat(id, ref) {
    const modal = document.getElementById('showModal');
    const form = modal.querySelector('form');
    const h3 = form.querySelector('h3');
    const inputId = document.getElementById('showModalInput');

    h3.textContent = `Ré-afficher  - #${ref} ?`;
    inputId.value = id;
}