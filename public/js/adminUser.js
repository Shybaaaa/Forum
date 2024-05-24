function renderModalBanned(id, name)
{
    const modal = document.getElementById('banModal');
    const form = modal.querySelector('form');
    const h3 = form.querySelector('h3');
    const inputId = document.getElementById('banModalInput');

    h3.textContent = `Etes-vous sûr de vouloir bannir cet utilisateur - ${name} ?`;
    inputId.value = id;
}
function renderModalDeban(id, name)
{
    const modal = document.getElementById('debanModal');
    const form = modal.querySelector('form');
    const h3 = form.querySelector('h3');
    const inputId = document.getElementById('debanModalInput');

    h3.textContent = `Etes-vous sûr de vouloir débannir cet utilisateur - ${name} ?`;
    inputId.value = id;
}

function renderModalEditUser()
{
    // console.log(user)
    const modal = document.getElementById('editUserModal')

    modal.classList.remove('-translate-x-full')

}