//Menu Responsive
function showMenu(){
    $('#menu').css('top', '0');
}
function hiddeMenu(){
    $('#menu').css('top', '-100vh');
}

//Alert
function hiddeAlert(){
    $('.alert').addClass("d-none");
}

//Carregar Imagem
function loadImg(e, idDisplay, idLabel) {
    var selectedFile = event.target.files[0];
    var reader = new FileReader();

    var imgtag = document.getElementById(idDisplay);
    imgtag.title = selectedFile.name;

    reader.onload = function(event) {
        imgtag.src = event.target.result;
    };

    reader.readAsDataURL(selectedFile);
    document.getElementById(idLabel).innerHTML = event.target.files[0].name;
}