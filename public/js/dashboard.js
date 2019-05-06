//Menu Responsive
function showMenu(){
    $('#menu').css('top', '0');
}
function hiddeMenu(){
    $('#menu').css('top', '-100vh');
}

//FORMS
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
//Loaders
function progressBar(){
    $( ".dash-botoes" ).hide(); 
    $( ".dash-spinner" ).show(); 
    var timer;
    var i = 50;
    timer = setInterval(function(e){
        console.log('%: '+i);
        i= i+0.1;
        if(i < 90 ){
            $("#progresso").css('width', i+'%');
        }
    }, 300);
}
function spinner(){
    $( ".dash-botoes" ).hide(); 
    $( ".dash-spinner" ).show(); 
}