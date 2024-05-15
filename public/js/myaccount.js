const counter = document.getElementById('counter');
const updateDescription = document.getElementById('updateDescription');

updateDescription.addEventListener('input', () => {
    counter.innerText = updateDescription.value.length;
    if (updateDescription.value.length > 500) {
        counter.classList.remove('text-green-500');
        counter.classList.add('text-red-500');
        counter.classList.add('font-bold')
    } else if (updateDescription.value.length <= 500) {
        counter.classList.remove('text-red-500');
        counter.classList.remove('font-bold');
        counter.classList.add('text-green-500');
    } else if (updateDescription.value.length < 0) {
        counter.classList.remove('text-green-500');
        counter.classList.add('text-red-500');
        counter.classList.add('font-bold')
    }
});