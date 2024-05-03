const counter = document.getElementById('counter');
const updateDescription = document.getElementById('updateDescription');
console.log(updateDescription);
updateDescription.addEventListener('input', () => {
    counter.innerText = updateDescription.value.length;
    if (updateDescription.value.length > 500) {
        counter.classList.add('text-red-500 font-bold');
    } else if (updateDescription.value.length < 500) {
        counter.style.color = 'text-green-500';
    }
});