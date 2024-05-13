const btnDelete = document.querySelectorAll('.btnDelete');

function renderModalDeletePost(id, ref)
{
    const modal = document.getElementById('deleteModal');
    const form = modal.querySelector('form');
    const h3 = form.querySelector('h3');
    const inputId = document.getElementById('deleteModalInput');

    h3.textContent = `Etes-vous sûr de vouloir supprimer ce post - #${ref} ?`;
    inputId.value = id;
}

function renderModalRestorePost(id, ref)
{
    const modal = document.getElementById('restoreModal');
    const form = modal.querySelector('form');
    const h3 = form.querySelector('h3');
    const inputId = document.getElementById('restoreModalInput');

    h3.textContent = `Restaurer le post - #${ref} ?`;
    inputId.value = id;
}