const counter = document.getElementById('counter');
const commentrespond = document.getElementById('commentrespond');

function respondComment(id) {
    const modal = document.getElementById('commentRespond');
    const inputId = document.getElementById('commentId');

    inputId.value = id;
}

commentrespond.addEventListener('input', () => {
    counter.innerText = commentrespond.value.length;
    if (commentrespond.value.length > 2000) {
        counter.classList.remove('text-green-500');
        counter.classList.add('text-red-500');
        counter.classList.add('font-bold')
    } else if (commentrespond.value.length <= 2000) {
        counter.classList.remove('text-red-500');
        counter.classList.remove('font-bold');
        counter.classList.add('text-gray-900');
    } else if (commentrespond.value.length < 0) {
        counter.classList.remove('text-green-500');
        counter.classList.add('text-red-500');
        counter.classList.add('font-bold')
    }
})
