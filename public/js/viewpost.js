console.log("load function");

function respondComment(id) {
    const modal = document.getElementById('commentRespond');
    const inputId = document.getElementById('commentId');

    inputId.value = id;
}